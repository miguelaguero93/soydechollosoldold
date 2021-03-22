@if(!Session::has('categories'))
  @php
    $categories = DB::table('categories')->where('deleted_at',null)->where('parent_id',null)->orderBy('order')->select('id','name','slug')->get();
    Session::put('categories',$categories);
  @endphp
@endif
@if(!Session::has('links'))
  @php
    $links = DB::table('pages')->where('status','ACTIVE')->orderBy('id')->select('title','slug','status')->get();
    Session::put('links',$links);
  @endphp
@endif
<footer style="display: none">
  <div class="bg-blue3 py-20 py-lg-40 color-white">
    <div class="container">
      <div class="row">
        <div class="col-lg-4 d-flex align-items-center mb-20 mb-lg-0">
          <img src="/images/footer.png" class="mx-auto mx-lg-0" alt="">
        </div>
        <div class="col-lg-4 color-gray-100 text-center text-lg-left mb-20 mb-lg-0">
          <h3 class="font-22 color-white font-normal mb-20">
            ¿Qué es SOYDECHOLLOS?
          </h3>
          <p class="line-16">
            {{setting('site.description')}} 
            <br>
            @foreach(Session::get('links') as $link)
              <a href="/pagina/{{$link->slug}}" target="_blank">{{$link->title}} <i class="fas fa-external-link-alt"></i></a> <br> 
            @endforeach
          </p>
        </div>
        <div class="col-sm-6 col-lg-2 color-gray-100">
          <h3 class="font-22 color-white font-normal mb-20">Categorías</h3>
          <ul class="list-unstyled list">
            @foreach(Session::get('categories') as $cat)
            <li class="mb-7">
              <a href="/categoria/{{$cat->slug}}">
                {{$cat->name}}
              </a>
            </li>
            @endforeach
          </ul>
        </div>
        <div class="col-sm-6 col-lg-2 color-gray-100">
          <h3 class="font-22 color-white font-normal mb-20">Siguenos</h3>
          <ul class="list-unstyled">
            <li class="my-13">
              <div class="d-flex align-items-center color-white">
                <span class="circle-small">
                  <i class="fas fa-envelope"></i>
                </span>
                <a class="link-none text-white pl-10" href="/contacto">Contactanos</a>
              </div>
            </li>
            <li class="my-13">
              <div class="d-flex align-items-center color-white">
                <span class="circle-small">
                  <i class="fab fa-facebook-f"></i>
                </span>
                <a class="link-none text-white pl-10" href="{{setting('site.facebook')}}">Facebook</a>
              </div>
            </li>
            <li class="my-13">
              <div class="d-flex align-items-center color-white">
                <span class="circle-small">
                  <i class="fab fa-instagram"></i>
                </span>
                <a class="link-none text-white pl-10" href="{{setting('site.instagram')}}">Instagram</a>
              </div>
            </li>
            <li class="my-13">
              <div class="d-flex align-items-center color-white">
                <span class="circle-small">
                  <i class="fab fa-twitter"></i>
                </span>
                <a class="link-none text-white pl-10" href="{{setting('site.twitter')}}">Twitter</a>
              </div>
            </li>
            <li class="my-13">
              <div class="d-flex align-items-center color-white">
                <span class="circle-small">
                  <i class="fas fa-envelope"></i>
                </span>
                <a class="link-none text-white pl-10" href="mailto:{{setting('site.email')}}">Email</a>
              </div>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>
  <div class="bg-blue2 text-center p-10 color-white letter-spacing-1">
    &copy; 2020 Soydechollos Todos los derechos reservados
  </div>
  <form id="logout-form" action="/logout" method="POST" style="display: none;">
      {{ csrf_field() }}
  </form>
</footer>
<script type="text/javascript" src="/js/assets.js"></script>
<script type="text/javascript" src="/js/defer.js"></script>
<script src="/js/custom.js?v=19"></script>
@include('common.js.facebook')
@yield('scripts')
@if(Session::has('success'))
  <script type="text/javascript">
    snackSuccess('{{Session::get('success')}}')
  </script>
@endif
@if(Session::has('error'))
  <script type="text/javascript">
    snackError('{{Session::get('error')}}')
  </script>
@endif
@if(isset($_GET['login']) && is_null(Auth::user()))
    <script type="text/javascript">
      triggerLoginModal()
    </script>
@endif
<form action="/api/perfil" id="form" method="post" enctype="multipart/form-data">
  @csrf
  <input type="file" id="image" name="image" accept="image/x-png,image/gif,image/jpeg" style="display: none;">
</form>
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async defer src="https://www.googletagmanager.com/gtag/js?id=UA-66485014-1"></script>
<script async defer>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());
  gtag('config', 'UA-66485014-1');
</script>
<script>
  $('.see-more-cat').hide();
  document.querySelector('.spinner').hidden = true;
  //setup before functions
  var typingTimer;                //timer identifier
  var doneTypingInterval = 1000;  //time in ms, 5 second for example
  var $input = $('#search_input');
  var value = '';
  var allCategories = [];

  //on keyup, start the countdown
  $input.on('keyup', function () {
    document.querySelector('.fas.fa-search').hidden = true;
    document.querySelector('.spinner').hidden = false;
    value=jQuery(this).val();
    clearTimeout(typingTimer);
    typingTimer = setTimeout(doneTyping, doneTypingInterval);
  });

  //on keydown, clear the countdown 
  $input.on('keydown', function () {
    clearTimeout(typingTimer);
  });

  function goToOferts(){
    window.open('/ofertas/' + value, '_self');
  }

  function getMoreCat(){
    $('.see-more-cat').hide();
    for (let index = 3; index < allCategories.length; index++) {
      const element = allCategories[index];
      image = element.image == null ? 'https://soydechollos.com/storage/users/1552005727.png' : element.image;
      html = '<li class="search-li">' +
        '<a class="search-li-a" href="https://soydechollos.com/categoria/' + element.slug +'">' +
          '<div class="flex-shrink-0">' +
            '<img class="search-li-a-div-img" src="' + image +' ">' +
          '</div>' +
          '<div class="flex-grow-1">' +
            '<div class="search-li-title">' +
              element.name +
            '</div>' +
            '<div class="search-li-subtitle">' +
              element.count + ' chollos' +
            '</div>' +
          '</div>' +
        '</a>' +
      '</li>';
      $('#categories ol').append(html);
    }
  }
  var normalize = (function() {
    var from = "ÃÀÁÄÂÈÉËÊÌÍÏÎÒÓÖÔÙÚÜÛãàáäâèéëêìíïîòóöôùúüûÑñÇç", 
        to   = "AAAAAEEEEIIIIOOOOUUUUaaaaaeeeeiiiioooouuuunncc",
        mapping = {};
  
    for(var i = 0, j = from.length; i < j; i++ )
        mapping[ from.charAt( i ) ] = to.charAt( i );
  
    return function( str ) {
        var ret = [];
        for( var i = 0, j = str.length; i < j; i++ ) {
            var c = str.charAt( i );
            if( mapping.hasOwnProperty( str.charAt( i ) ) )
                ret.push( mapping[ c ] );
            else
                ret.push( c );
        }      
        return ret.join( '' );
    }
  
  })();
  //user is "finished typing," do something
  function doneTyping () {
    console.log(value);
    jQuery.ajax({
      type : 'get',
      url : '/api/searchAjax',
      data:{'search':value},
      success:function(data){
        //jQuery('tbody').html(data);
        $('#categories').hide();
        $('#shops').hide();
        $('#brands').hide();
        $('#chollosList').hide();
        if(data.categories.length > 0){
          allCategories = data.categories;
          $('#categories').show();
          $('#categories ol').empty();

          if(data.categories.length <= 3){
            data.categories.forEach(element => {
              image = element.image == null ? 'https://soydechollos.com/storage/users/1552005727.png' : '/storage/' + element.image;
              html = '<li class="search-li">' +
                '<a class="search-li-a" href="https://soydechollos.com/categoria/' + element.slug +'">' +
                  '<div class="flex-shrink-0">' +
                    '<img class="search-li-a-div-img" src="' + image +' ">' +
                  '</div>' +
                  '<div class="flex-grow-1">' +
                    '<div class="search-li-title">' +
                      element.name +
                    '</div>' +
                    '<div class="search-li-subtitle">' +
                      element.count + ' chollos' +
                    '</div>' +
                  '</div>' +
                '</a>' +
              '</li>';
              $('#categories ol').append(html);
            });
          }

          if(data.categories.length > 3){
            $('.see-more-cat').show();
            for (let index = 0; index < 3; index++) {
              let element = data.categories[index];
              image = element.image == null ? 'https://soydechollos.com/storage/users/1552005727.png' : '/storage/' + element.image;
              html = '<li class="search-li">' +
                '<a class="search-li-a" href="https://soydechollos.com/categoria/' + element.slug +'">' +
                  '<div class="flex-shrink-0">' +
                    '<img class="search-li-a-div-img" src="' + image +' ">' +
                  '</div>' +
                  '<div class="flex-grow-1">' +
                    '<div class="search-li-title">' +
                      element.name +
                    '</div>' +
                    '<div class="search-li-subtitle">' +
                      element.count + ' chollos' +
                    '</div>' +
                  '</div>' +
                '</a>' +
              '</li>';
              $('#categories ol').append(html);
            }
          }
        }
        if(data.stores.length > 0){
          $('#shops').show();
          $('#shops ol').empty();
          data.stores.forEach(element => {
            image = element.image == null ? 'https://soydechollos.com/storage/users/1552005727.png' : 'https://soydechollos.com/storage/' + element.image;
            html = '<li class="search-li">' +
              '<a class="search-li-a" href="https://soydechollos.com/tienda/' + element.slug +'">' +
                '<div class="flex-shrink-0">' +
                  '<img class="search-li-a-div-img" src="' + image +'">' +
                '</div>' +
                '<div class="flex-grow-1">' +
                  '<div class="search-li-title">' +
                    element.visible_name +
                  '</div>' +
                  '<div class="search-li-subtitle">' +
                    element.count + ' chollos' +
                  '</div>' +
                '</div>' +
              '</a>' +
            '</li>';
            $('#shops ol').append(html);
          });
        }
        if(data.brands.length > 0){
          $('#brands').show();
          $('#brands ol').empty();
          data.brands.forEach(element => {
            image = element.image == null ? 'https://soydechollos.com/storage/users/1552005727.png' : 'https://soydechollos.com/storage/' + element.image;
            html = '<li class="search-li">' +
              '<a class="search-li-a" href="https://www.soydechollos.com/marca/' + normalize((element.value).toLowerCase()).replaceAll(' ', '-') + '">' +
                '<div class="flex-shrink-0">' +
                  '<img class="search-li-a-div-img" src="'+image+'">' +
                '</div>' +
                '<div class="flex-grow-1">' +
                  '<div class="search-li-title">' +
                    (element.value).trim().toLowerCase().replace(/\w\S*/g, (w) => (w.replace(/^\w/, (c) => c.toUpperCase()))) +
                  '</div>' +
                  '<div class="search-li-subtitle">' +
                    element.count + ' chollos' +
                  '</div>' +
                '</div>' +
              '</a>' +
            '</li>';
            $('#brands ol').append(html);
          });
        }
        if(data.chollos.length > 0){
          $('#chollosList').show();
          $('#chollosList ol').empty();
          data.chollos.forEach(element => {
            console.log(element);
            price = element.price == null ? '' : element.price + '€';
            html = '<li class="search-li">' +
              '<a class="search-li-a" href="https://soydechollos.com/' + element.slug.replace('¡','&iexcl;') +'">' +
                '<div class="flex-shrink-0">' +
                  '<img class="search-li-a-div-img" src="' + element.image_small.replace('¡','&iexcl;') +'">' +
                '</div>' +
                '<div class="flex-grow-1">' +
                  '<div class="search-li-title">' +
                    element.name +
                  '</div>' +
                  '<div class="search-li-subtitle">' +
                    price +
                  '</div>' +
                '</div>' +
              '</a>' +
            '</li>';
            $('#chollosList ol').append(html);
          });
        }
        document.querySelector('.spinner').hidden = true;
        document.querySelector('.fas.fa-search').hidden = false;
        $('.after-search').show();
      }
    });
  }
  $(window).click(function() {
    if (!$(event.target).parents('div#search-element').length) {
      $('.after-search').hide();
    }
  });
  jQuery.ajaxSetup({ headers: { 'csrftoken' : '{{ csrf_token() }}' } });
  document.addEventListener("DOMContentLoaded", function(event) {
    document.querySelector('.after-search').style.width = (document.getElementById('search_input').offsetWidth + 45) + "px";
  });
</script>
</body>
</html>