<div class="menu-responsive">
  <div class="menu-close">
    <i class="fa fa-times-circle"></i>
  </div>
  <div id="left-menu" class="menu-content vertically-scrollable">
    
    @guest
      

      <div class="bg-blue2 py-15 px-20">
        <div class="d-flex align-items-center justify-content-between">
          <div class="mr-15">
            <i class="ico user wh-60"></i>
          </div>
      
          <a href="/seleccionar"  class="btn btn-white">
            <i class="icon-fire color-orange2 font-27"></i>
            <span class="ml-5 pt-4 text-nowrap">
              Enviar chollo
            </span>
          </a>
        </div>

        <div class="d-flex align-items-center justify-content-between color-white mt-15">
          <div class="text-nowrap d-flex mr-5">
            <span class="mr-5 link" onclick="triggerLoginModal(); hideSideMenu()">
              Iniciar sesión / Registrarse
            </span>
          </div>
        </div>
      </div>

    @else

      <div class="bg-blue2 py-15 px-20">
        
        <div class="d-flex align-items-center justify-content-between">
          <a href="/perfil">
            <div class="ico wh-40 cursor-pointer">
              <img width="40" src="{{Auth::user()->avatar}}">
            </div>
          </a>
          
          <a href="/seleccionar" class="btn btn-white">
            <i class="icon-fire color-orange2 font-20"></i>
            <span class="ml-5 pt-4 text-nowrap">
              Enviar chollo
            </span>
          </a>
        
        </div>

        <div class="d-flex align-items-center justify-content-between color-white mt-15">
          <a href="/perfil">
            <div class="link text-nowrap text-white">
                {{ Auth::user()->name }}
            </div>
          </a>
        </div>
      </div>

    @endguest

    <div class="">
      <a href="/avisador" class="text-nowrap w-100 bg-green2 px-15 h-px-40 justify-content-center color-white d-inline-flex align-items-center font-bold link-none">
        ¡Avisador PRO!
      </a>
    </div>

    <div class="menu-options">
      <a href="/" class="link-none">
        <div class="menu-options-item">
          <i class="fa font-20 fa-tag"></i>
          <span>Todos los chollos</span>
        </div>
      </a>
      <a href="/alertas" class="link-none">
        <div class="menu-options-item">
          <i class="fa font-20 fa-plus"></i>
          <span>Crear/Ver Alertas</span>
        </div>
      </a>
      <a href="/populares" class="link-none">
        <div class="menu-options-item">
          <i class="icon-award font-28"></i>
          <span>Populares</span>
        </div>
      </a>
      <a href="/nuevos" class="link-none">
        <div class="menu-options-item">
          <i class="icon-new font-28"></i>
          <span>Nuevos</span>
        </div>
      </a>
      <a href="/comentados" class="link-none">
        <div class="menu-options-item">
          <i class="icon-comment font-28"></i>
          <span>Comentados</span>
        </div>
      </a>

      <a href="/codigos-descuento" class="link-none">
        <div class="menu-options-item">
          <i class="fas fa-ticket-alt font-20" aria-hidden="true"></i>
          <span>Cupones</span>
        </div>
      </a>
      <a href="/eventos" class="link-none">
        <div class="menu-options-item">
          <i class="fas font-20 fa-calendar" aria-hidden="true"></i>
          <span>Eventos y Sorteos</span>
        </div>
      </a>
      <div class="menu-options-item-line"></div>
      <a href="/tiendas" class="link-none">
        <div class="menu-options-item">          
          <i class="fas font-20 fa-store" aria-hidden="true"></i>
          <span>Tiendas</span>
        </div>
      </a>
      <a href="/etiquetas" class="link-none">
        <div class="menu-options-item">          
          <i class="fas font-20 fa-hashtag" aria-hidden="true"></i>
          <span>Etiquetas</span>
        </div>
      </a>
      <a href="/busquedas" class="link-none">
        <div class="menu-options-item">          
          <i class="fas font-20 fa-search" aria-hidden="true"></i>
          <span>Busquedas</span>
        </div>
      </a>

      @if(Auth::check())
        <div class="menu-options-item-line"></div>
        <a href="/favoritos" class="link-none">
          <div class="menu-options-item">
            <i class="fas font-20 fa-star"></i>
            <span>Mis favoritos</span>
          </div>
        </a>
        <a href="/enviados" class="link-none">
          <div class="menu-options-item">
            <i class="fas font-20 fa-paper-plane" aria-hidden="true"></i>
            <span>Mis chollos enviados</span>
          </div>
        </a>
        <a href="/mis_cupones" class="link-none">
          <div class="menu-options-item">
            <i class="fas font-20 fa-paper-plane" aria-hidden="true"></i>
            <span>Mis cupones enviados</span>
          </div>
        </a>
        <a href="/notifications" class="link-none">
          <div class="menu-options-item">
            <i class="fas fa-bell font-20"></i>
            <span>Notificaciones</span>
          </div>
        </a>
      @endif

      <div class="menu-options-item-line"></div>

{{--       <a href="https://foro.soydechollos.com" class="link-none">
        <div class="menu-options-item">
          <i class="fas fa-comments font-20"  aria-hidden="true"></i>
          <span>Foro de discusión</span>
        </div>
      </a>

 --}}

      <a href="/monedas" class="link-none">
        <div class="menu-options-item">
          <i class="ico money"></i>
          <span>Chollocoins</span>
        </div>
      </a>

      <a href="/contacto" class="link-none">
        <div class="menu-options-item">
          <i class="fas fa-paper-plane"></i>
          <span>Contactános</span>
        </div>
      </a>

      @if(Auth::check())
        <a href="#" class="link-none" onclick="event.preventDefault(); logOutUser()">
          <div class="menu-options-item">
            <i class="fas fa-sign-out-alt"></i>
            <span>Cerrar sesión</span>
          </div>
        </a>
      @endif
    
    </div>
  </div>
</div>