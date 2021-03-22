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
          <img src="{{asset('/images/footer.png')}}" class="mx-auto mx-lg-0" alt="">
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
<script type="text/javascript" src="{{asset('/js/assets.js')}}"></script>
<script type="text/javascript" src="{{asset('/js/defer.js')}}"></script>
<script src="{{asset('/js/custom.js?v=18')}}"></script>
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
</body>
</html>