<header class="color-white">
  <div class="container">
    <div class="d-flex w-100 h-px-70 align-items-center justify-content-between">
      <div class="d-none d-xl-block">
        <a href="/">
          <img src="{{asset('/images/logo.png')}}" class="h-px-40" alt="Soy de chollos">
        </a>
      </div>
      <div class="d-flex align-items-center w-100 pr-md-20 pr-lg-50">
        <div class="text-center ml-xl-20 mr-15 mr-lg-30 line-10 menu-click">
          <i class="icon-menu font-40"></i>
          <div class="font-12 mt-n4">
            Menu
          </div>
        </div>
        <div class="d-flex align-items-center mr-md-30 w-100">
          <form class="w-100" action="/api/search" onsubmit="showBlanket()">
            <div class="search">
              <input type="text" name="search" placeholder="Buscar producto">
              <button>
                <i class="fas fa-search font-20 color-blue4"></i>
              </button>
            </div>
          </form>
        </div>
        <div class="d-none d-md-block w-px-150">
          <a href="/seleccionar" class="btn btn-white">
            <i class="icon-fire color-orange2 font-27"></i>
            <span class="ml-5 pt-4">
              Enviar chollo
            </span>
          </a>
        </div>
      </div>
      <div class="d-flex">
        @guest
          <div class="d-none d-md-block w-px-150">
            <button class="btn btn-white" onclick="triggerLoginModal()">
              <i class="fa fa-user"></i>
              <span class="ml-5 pt-4">
                Registrase/Iniciar Sesion
              </span>
            </button>
          </div>
          <div class="ml-30 icoico d-none d-md-flex">
            <a href="/monedas"><i class="ico money"></i></a>
          </div>
          <div class="ml-10 d-none d-md-flex ">
            <span class="notify">
             <a href="/monedas">
               0
             </a>
            </span>
          </div>
        @else
          {{-- <div class="line-0 icolink d-none d-md-flex mt-6">
            <a href="https://foro.soydechollos.com" class="color-white no_link"><i class="fas fa-comments font-30 cursor-pointer"></i></a>
          </div> --}}
          @include('includes.notifications')
          <div class="ml-30 icoico menu-user d-none d-md-flex">
            <div class="ico wh-40 cursor-pointer">
              <img width="40" src="{{Auth::user()->avatar}}">
            </div>
            <div class="menu-user-options">
              <ul>
                <li> <a href="/perfil"> {{ Auth::user()->name }} </a> </li>
                <li> <a href="/medallas/{{Auth::id()}}/{{urlencode(Auth::user()->name)}}" class="link-none"> <i class="fas fa-star"></i> <span> Mis medallas </span> </a> </li> 
                <li> <a href="/alertas"> <i class="fas fa-plus"></i> <span> Mis Alertas </span> </a> </li>
                <li> <a href="/notifications"> <i class="fas fa-bell"></i> <span> Mis notificaciones </span> </a> </li>
                <li> <a href="/enviados"> <i class="fas fa-tag"></i> <span> Mis ofertas</span> </a> </li>
                <li> <a href="/mis_cupones"> <i class="fas fa-tag"></i> <span> Mis cupones</span> </a> </li>
                <li> <a href="https://foro.soydechollos.com/u/{{Auth::user()->name}}"> <i class="fas fa-comment"></i> <span> Mis temas </span> </a> </li>
                <li> <a href="/favoritos"> <i class="fas fa-heart"></i> <span> Chollos guardados </span> </a> </li>
                <li> <a href="/settings"> <i class="fas fa-cog"></i> <span> Ajustes </span> </a> </li>
                <li> <a href="#" onclick="event.preventDefault(); logOutUser()"> <i class="fas fa-sign-out-alt"></i> <span> Cerrar sesión </span> </a> </li>
              </ul>
            </div>
          </div>
          <div class="ml-20 icoico d-none d-md-flex mt-6">
            <a href="/monedas"><i class="ico money"></i></a>
          </div>
          <div class="ml-10 d-none d-md-flex mt-6">
            <a href="/monedas">
              <span class="notify">
                {{Auth::user()->coins}}
              </span>
            </a>
          </div>
        @endguest
      </div>
    </div>
  </div>
</header>