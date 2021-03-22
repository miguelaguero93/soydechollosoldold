function showfooter(){
  document.getElementsByTagName('footer')[0].style.display = 'block'
}


function isNumeric(n) {
    return !isNaN(parseFloat(n)) && isFinite(n);
}

function snackError(text){
  Snackbar.show({text: '<i class="fa fa-warning"></i> '+text, pos: 'top-left', backgroundColor: '#ff6000', textColor: 'white', showAction: true,  duration: 8000, actionText: 'Cerrar', actionTextColor : '#fff' } );
} 

function snackSuccess(text){
  Snackbar.show({text: '<i class="fa fa-check"></i> '+text, pos: 'top-right', backgroundColor: '#06ba43', textColor: 'white', showAction: true,  duration: 8000, actionText: 'Cerrar', actionTextColor : '#fff' } );
} 

function isValidURL(string) {
  var res = string.match(/(http(s)?:\/\/.)?(www\.)?[-a-zA-Z0-9@:%._\+~#=]{2,256}\.[a-z]{2,6}\b([-a-zA-Z0-9@:%_\+.~#?&//=]*)/g);
  return (res !== null)
}

function logOutUser(){
   document.getElementById('logout-form').submit();
}
function showBlanket(){
   document.getElementById('blanket').style.display = 'block';
}
function hideBlanket(){
   document.getElementById('blanket').style.display = 'none';
}

function showFilter(){
  document.getElementById("blanket").addEventListener("click", hideFilter)
  document.getElementById('filter').style.display = 'block'
  showBlanket()
}

function showTopsFilter(){
  document.getElementById("blanket").addEventListener("click", hideFilter)
  document.getElementById('topfilter').style.display = 'block'
  showBlanket()
}
function showCommFilter(){
  document.getElementById("blanket").addEventListener("click", hideFilter)
  document.getElementById('commfilter').style.display = 'block'
  showBlanket()
}

function hideFilter(){
  document.getElementById('filter').style.display = 'none'

  var topfilter = document.getElementById('topfilter')
  if (topfilter != null) {
    topfilter.style.display = 'none'
  }

  var comfilter = document.getElementById('commfilter')
  if (comfilter != null) {
    comfilter.style.display = 'none'
  }
  hideBlanket()

}

function showStoreDetails(){
  document.getElementById('details_container').style.display = 'block'
  document.getElementById('show_less').style.display = 'block'
  document.getElementById('show_more').style.display = 'none'
}
function hideStoreDetails(){
  document.getElementById('details_container').style.display = 'none'
  document.getElementById('show_less').style.display = 'none'
  document.getElementById('show_more').style.display = 'block'
}


function uploadOwnFile(){
  image = document.getElementById('image')
  image.click()
  image.addEventListener('change', function(event){
    
    form = document.getElementById('form')
    form.submit()
   
  })
}

function showNotifications(){
  document.getElementsByClassName("menu-count-options")[0].style.display = 'block'
}
function closeNotifications(){
  document.getElementsByClassName("menu-count-options")[0].style.display = 'none'
}

function docReady(fn) {
    // see if DOM is already available
    if (document.readyState === "complete" || document.readyState === "interactive") {
        setTimeout(fn, 1);
    } else {
        document.addEventListener("DOMContentLoaded", fn);
    }
}

docReady(function() {
    showfooter()

    $('.collapse-title').on("click", function() {
      $(this).toggleClass("active");

      if ($(this).hasClass("active")) {
        $(this).next().slideUp(500);
      } else {
        $(this).next().slideDown();
      }
      
    });

  
    $(".menu-click").on("click", function() {
      $(this).toggleClass("active");
      if ($(this).hasClass("active")) {
        $("body").addClass("hidden");
        $('.menu-responsive').animate({
          width:'toggle'
        }, 400);
      }
      document.getElementById("left-menu").scrollTop = 0
    })

    $(".menu-close").on("click", function() {
      $(".menu-click").removeClass("active");

      $("body").removeClass("hidden");

      $('.menu-responsive').animate({
        width:'toggle'
      }, 400);
      document.getElementById("left-menu").scrollTop = 0
    });

    $(".banner .icon").on("click", function() {
      $(".banner").fadeOut(300);
    })


    $(document).mouseup(function(e) {
      var container = $(".menu-responsive");

      if (!container.is(e.target) && container.has(e.target).length === 0) {
        $(".menu-click").removeClass("active");

        $("body").removeClass("hidden");

        container.hide();
        document.getElementById("left-menu").scrollTop = 0
      }
    })

    var pathname = window.location.pathname
    if (!pathname.startsWith('/editar') && !pathname.startsWith('/nuevo') ) {
      var anchors = document.getElementsByTagName("a");
      var exclude = ['www.soydechollos.com','soydechollos.com','foro.soydechollos.com','www.facebook.com','www.instagram.com','twitter.com','api.whatsapp.com','facebook.com','instagram.com','www.telegram.org','telegram.org']
      for (var i = 0; i < anchors.length; i++) {    
        if (anchors[i].href != '') {
          var url = new URL(anchors[i].href)
          if (exclude.indexOf(url.hostname) == -1 && url.hostname.length > 0) {
              anchors[i].href = "https://www.soydechollos.com/api/redirect?redirect=" + anchors[i].href
              anchors[i].target = "_blank"
          }
        }
      }
    } 
    

    var filter_mobile = document.getElementById("filter_mobile")

    if (filter_mobile != null) {
      filter_mobile.addEventListener('change',function(value){
          console.log('adding event listener for filter')
          var newvalue = value.target.value
          document.getElementById('period_filter').value = newvalue
          document.getElementById('filter_form').submit()
      })
    }


    // $('.owl-carousel').owlCarousel({
    //   loop:true,
    //   margin:0,
    //   nav:false,
    //   autoplay:false,
    //   responsive:{
    //       0:{
    //           items:1,
    //           nav:true
    //       },
    //       600:{
    //           items:1,
    //           nav:false
    //       },
    //       1000:{
    //           items:1,
    //           nav:true,
    //           loop:false
    //       }
    //   },
    // });

    // setTimeout(function(){
    //   $('.owl-access .owl-item.active .f-middle > span').show();
    //   $('.owl-access .owl-item.active > div > div').addClass('animate__fadeIn');
    // },1);

    // $('.owl-carousel').on('changed.owl.carousel', function(event) {
    //   setTimeout(function(){
    //     $('.owl-access .owl-item > div > div').removeClass('animate__fadeIn');
    //     $('.owl-access .owl-item.active > div > div').addClass('animate__fadeIn');
    //     $('.owl-access .owl-item .f-middle > span').hide();
    //     $('.owl-access .owl-item.active .f-middle > span').show();
    //   },1);
    // });

    $( ".am-inside" ).click(function(e) {
      if($(e.target).is('.eye-icon')){
        event.preventDefault();
        var x = $('.access-modal form input#password').attr('type');
        if (x === 'password') {
          $('.access-modal form input#password').prop('type','text');
        } else {
          $('.access-modal form input#password').prop('type','password');
        }
        return false
        
      } else if($(e.target).is('.eye-icon-register')) {
        event.preventDefault();
        var x = $('.access-modal form input#password_register').attr('type');
        if (x === 'password') {
          $('.access-modal form input#password_register').prop('type','text');
        } else {
          $('.access-modal form input#password_register').prop('type','password');
        }
        return false
      }
      event.stopPropagation();
    });


    $( ".menu-user i" ).click(function() {
      event.preventDefault();
      $('.access-modal').addClass('show');
      return false
    });

    $( ".close-icon" ).click(function() {
      event.preventDefault();
      $('.access-modal').removeClass('show');
      
    });
    $( "#create-account" ).click(function() {
      $('.access-login').removeClass('active');
      $('.access-register').addClass('animate__fadeInRight');
      $('.access-register').addClass('active');
      $( ".login-account" ).addClass('active');
      $( ".create-account" ).removeClass('active');
      $(this).hide();
      auth.username = ''
    });

    $( "#login-account" ).click(function() {
      $('.access-login').addClass('active');
      $('.access-login').addClass('animate__fadeInRight');
      $('.access-register').removeClass('active');
      $( ".login-account" ).removeClass('active');
      $( ".create-account" ).addClass('active');
      $(this).hide();
      
    });

    var nots = new Vue({
      el:"#notifications",
      data: {
        total: 0,
        msg: []
      },

      filters: {
        relativeTime(value) {
            var utcdate = moment.utc(value)
            return utcdate.local().fromNow()
        }
      },

      methods: {
        openTab(link){
          window.open(link)
          this.hideBlanket();
        },

        dismiss(index,link){
            var not = this.msg[index];
            axios.get('/api/notification/dismiss/'+not.id).catch(function(error){
              console.log(error)
            })
            if (link != null) {
              window.location.href = link
            }else{
              not.read_at = 1
            }
            this.total = this.total-1
        },
        
        
        allread(){
          axios.get('/api/notification/dismiss')
          .then(function(response){        
          }).catch(function(error){
            console.log(error)
          })
          for (var i = this.msg.length - 1; i >= 0; i--) {
            this.msg[i].read_at = 1;
          }
          this.total = 0
        },
        getNotifications(){
          axios.get('/api/notification/get')
          .then(function(response){
            nots.msg = response.data[0]
            nots.total = response.data[1];
          }).catch(function(error){
          console.log(error)
          })
        }
      },

      created: function(){
        this.getNotifications();
      }
    })
    if (typeof scrollPreview !== 'undefined') {
      scrollPreview()
    }
})