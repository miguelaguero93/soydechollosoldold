<script src="https://apis.google.com/js/platform.js?onload=renderButton" async defer></script>

<script>


(function(d, s, id){
 var js, fjs = d.getElementsByTagName(s)[0];
 if (d.getElementById(id)) {return;}
 js = d.createElement(s); js.id = id;
 js.src = "https://connect.facebook.net/en_US/sdk.js";
 fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));


window.fbAsyncInit = function() {
  FB.init({
    appId      : '2977215382372741',
    cookie     : true,
    xfbml      : true,
    version    : 'v7.0'
  });
    
  FB.AppEvents.logPageView();   
  
}

function logWithFacebook(){
  FB.login(function(response) {
    callFbApi(response)
  },{scope: 'public_profile,email'})
}

function callFbApi(response){
    if (response.status === 'connected') {
      FB.api('/me?fields=id,name,email,picture', function(response) {
        
        if (response.error == undefined) {
          auth.proceedWithSocialLogin(response)
        }
       
      })
    } else {
      console.log('login failed')
    }
}


function googleOnFailure(error) {
  console.log(error);
}

function renderButton() {
  gapi.signin2.render('my-signin2', {
    'scope': 'profile email',
    'height': 50,
    'margin': 0,
    'box-shadow': 'none',
    'longtitle': false,
    'theme': 'light',
    'onsuccess': googleOnSuccess,
    'onfailure': googleOnFailure
  });
  gapi.signin2.render('my-signin3', {
    'scope': 'profile email',
    'height': 50,
    'margin': 0,
    'box-shadow': 'none',
    'longtitle': false,
    'theme': 'light',
    'onsuccess': googleOnSuccess,
    'onfailure': googleOnFailure
  });
}
function googleOnSuccess(googleUser) {
    var profile = googleUser.getBasicProfile()
    var auth2 = gapi.auth2.getAuthInstance()
    auth2.signOut()
    auth.proceedWithSocialLoginGoogle(profile)
}

function closeAccessModals(event){
  var classFirst = event.target.classList[0] 
  if (classFirst == 'access-modal'){
    modals = document.getElementsByClassName('access-modal')
    modals[0].classList.remove('show')
  }
}
function closeModals(event){
  var classFirst = event.target.classList[0] 
  if ( classFirst == 'modal' || classFirst == 'close' || classFirst == 'access-modal'){
    modals = document.getElementsByClassName('modal')
    for(item of modals){
      item.style.display = 'none'
    }
  }
}
function triggerLoginModal(){  
  if (window.innerWidth >= 992) {
    image = document.getElementById("login_gif");
    image.style.background = 'url("https://i.stack.imgur.com/spmUM.gif")'
  }
  modal = document.getElementById("loginModal");
  modal.classList.add('show');
}
function hideLoginModal(){  
  modal = document.getElementById("loginModal");
  modal.classList.remove('show');
}
function hideSideMenu(){
  $(".menu-close").click()
}
function triggerKeywordsModal(){
  if (app.logged == null) {
    return triggerLoginModal()
  }

  var modal = document.getElementById("keywordsModal");
  modal.style.display = "block";
}
function validateUser(name,email,picture,password){
  if (name != undefined && email != undefined && picture != undefined && password != undefined){
   return true
  }
  return false  
}
function logUser(name,password,remember_me,is_social = false){
  if (name != undefined && password != undefined){
    let payload = {
      name:name,
      password:password,
      remember_me:remember_me,
      is_social:is_social
    }    
    return axios.post('/api/user/login',payload).then(function(response){
      if (typeof response.data == 'object') {
        if (!is_social) {
          snackError(response.data[0])
          hideBlanket()
        }else{
          auth.proceedWithSocialRegister()
        }
      }else{
        // console.log(response.data)
        window.location.reload()
        // var redirectTo = window.location.href
        // window.location.href = 'https://foro.soydechollos.com/auth.php?token='+response.data+'&redirect='+redirectTo
      }
    }).catch(function(error){
      hideBlanket()
      console.log(error)
      alert(error)
    })
  }else{
    if (name == undefined || name == null) {
      snackError('Ingresa un email para completar el registro')
      $( "#create-account" ).click()
    }
  }
}
function hideConfirmModal(){
    var modal = document.getElementById("confirmModal")
    modal.style.display = "none";
}
function showPhotoModal(){
    hideBlanket()
    var modal = document.getElementById("photoModal")
    modal.style.display = "flex";
}

function storeSuccess(token){
    window.forumToken = token
    hideLoginModal()
    hideConfirmModal()
    showPhotoModal()
}

function redirectAfterRegister(){
    window.location.reload()
    // var redirectTo = window.location.href
    // window.location.href = 'https://foro.soydechollos.com/auth.php?token='+forumToken+'&redirect='+redirectTo
}

function storeUser(name,email,picture,password,subscribed,auto,ref){  
  if(validateUser(name,email,picture,password)){ 
    
    let payload = {
      name:name,
      email:email,
      picture:picture,
      password:password,
      subscribed:subscribed,
      auto:auto,
      ref:ref
    }   

    axios.post('/api/user/store',payload).then(function(response){
      if (typeof response.data == 'object') {
        snackError(response.data[0])
        hideBlanket()
      }else{
        storeSuccess(response.data)
      }
    }).catch(function(error){
      console.log(error)
      alert(error)
      hideBlanket()
    })


  }else{
    
    snackError('No es posible logearte con esta cuenta en este momento.')
    console.log('invalid call')
  
  }

}

function validateEmail(mail) {
 if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(mail)){
    return true
  }
    return false
}

function validateLogin(name,password){
  if (name.length < 4 || name.split(' ').length != 1) {
    snackError('Ingrese un usuario o email valido')
    return false
  }
  if (password.length < 5) {
    snackError('Ingrese una contraseña de mínimo 5 caracteres')
    return false
  }
  return true
}

function validateUserManualRegister(name,email,password,terms){
  
  if (!validateEmail(email)) {
    auth.emailError = true
    return false
  }
  if (name.length < 4 || name.split(' ').length != 1 || name.includes('@')){
    auth.nameError = true
    return false
  }
  if (password.length < 5) {
    auth.passwordError = true
    return false
  }
  if (!terms) {
    auth.termsError = true
    return false
  }
  return true

}
var auth = new Vue({
    el: '#auth',
    data: {
      email: '',
      emailError: false,
      username: '',
      nameError: false,
      picture:null,
      new_password:'',
      passwordError: false,
      password:'',
      subscribed: true,
      terms: false,
      termsError: false,
      remember_me: true,
      source: ''
    },
      
    mixins:[],
    methods:{

      formatUserName(item){
        var url = item.replace('á', 'a').replace('é','e').replace('í', 'i').replace('ó', 'o').replace('ú', 'u')
        url = url.replace(/[^\w ]/g,'')
        url = url.replace(/ /g,'.')
        url = url.toLowerCase() 
        url = url.substring(0,20)
        return url
      },

      proceedWithSocialRegister(){
          hideLoginModal()
          var modal = document.getElementById("confirmModal")
          modal.style.display = "flex";
      },

      proceedWithSocialLogin(response){

          this.email = response.email
          this.username = this.formatUserName(response.name)
          this.picture = response.picture.data.url
          this.password = response.id
          this.source = 'facebook'
          
          logUser(this.email,this.password,true,true)  
          
      },

      proceedWithSocialLoginGoogle(profile){
          

          
          this.email = profile.getEmail()
          this.username = this.formatUserName(profile.getName())
          this.picture = profile.getImageUrl()
          this.password = profile.getId()
          this.source = 'google'

          logUser(this.email,this.password,true,true)  

      },

      confirmSocialRegister(){
        this.emailError = false
        this.nameError = false
        this.passwordError = false
        this.termsError = false

        if(validateUserManualRegister(this.username,this.email,this.password,this.terms)){
          showBlanket()
          var ref = auth.source
          storeUser(this.username,this.email,this.picture,this.password,true,true,ref)
        }
      },

      submitRegister(){
        this.emailError = false
        this.nameError = false
        this.passwordError = false
        this.termsError = false
        this.picture = 'https://soydechollos.com/public/images/default.png';
        if(validateUserManualRegister(this.username,this.email,this.password,this.terms)){
          showBlanket()
          storeUser(this.username,this.email,this.picture,this.password,this.subscribed,false,'web')
        }
      },
      
      loginUser(){

        if (validateLogin(this.username,this.password)) {
          showBlanket()
          logUser(this.username,this.password,this.remember_me)
        }
      }
    }
})
</script>
