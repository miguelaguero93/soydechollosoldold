<div class="container py-20">

  <div class="row justify-content-center">
    <div class="col-lg-12">
      <div class="mb-15 text-center mb-30">
        <div class="font-bold font-18">
          Conseguir chollocoins
        </div>
        <div class="font-16">
          Consigue chollocoins enviando chollos de calidad y recomendándonos a tus amig@s. Luego los podrás canjear por fantásticos premios.
        </div>
      </div>
      <div class="row justify-content-center text-center mb-50">
        <div class="col-lg-8">

          @if(!is_null($auth))

            <div class="d-flex justify-content-center align-items-center bg-white mb-40 rounded pt-15 pb-20">
              
              <div>
                <div class="w-px-70 mb-5">
                  <img src="images/cofre.png" class="w-100 d-block" alt="">
                </div>
                <div class="bg-orange1 d-inline-flex rounded-20 d-flex align-items-center text-white pl-15 pr-10 py-4">
                  <span class="mr-10 font-normal pt-3">{{ $auth->coins }}</span>
                  <i class="ico ico-small money"></i>
                </div>
              </div>
              
              <div class="ml-20 font-bold font-16">
                Chollocoins en tu monedero: {{ $auth->coins }} <br>
                Chollocoins conseguidas hoy: {{ $total_coins_today }} 
              </div>

            </div>
          
          @endif

          <h2 class="font-24 text-center mb-20">¿Cómo conseguir chollocoins?</h2>

          <div class="d-md-flex justify-content-center">
            <div class="bg-white p-25 rounded mr-md-10 w-md-50 mb-20 mb-md-0">
              <h3 class="font-18">Invitando a un amigo</h3>
              <p class="mb-20">
                Invita a un amigo a nuestra plataforma y obtendrás 1 chollocoin por amigo que se registre
              </p>
              <div class="bg-blue1 mb-30 d-inline-flex rounded-20 d-flex align-items-center text-white px-15 py-4">
                <span class="mr-10 font-normal pt-3">+ 1</span>
                <i class="ico ico-small money"></i>
              </div>
              @if(!is_null($auth))
                <div class="mb-20" style="display: flex;">
                  <div class="coupon coupon-link">
                    <div class="coupon-content">
                      <span id="code" class="text-truncate">https://soydechollos.com?r={{$auth->id}}</span>
                    </div>
                  </div>
                  <span class="coupon_after" onclick="copyCode()"><i class="far fa-clone"></i></span>
                </div>
                <div>
                  <button onclick="copyCode()" class="btn btn-green d-inline-flex px-30 rounded-25">
                    <span class="ml-5">Copiar Link</span>
                  </button>
                  <br>
                  <small>Máximo 50 amigos. No vale auto invitarse ;)</small>
                </div>
              @else
                <div>Registrate o inicia sesion para acceder a tu link de referido y comenzar a ganar chollocoins</div>
                <div>
                  <button class="btn btn-green d-inline-flex px-30 rounded-25" onclick="triggerLoginModal()">
                    <i class="fas fa-user-plus color-orange2"></i>
                    <span class="ml-5">Registrarse</span>
                  </button>
                </div>
              @endif
            </div>

            <div class="bg-white p-25 rounded d-flex align-items-center ml-md-10 w-md-50 justify-content-center">
              <div class="">
                <h3 class="font-18">Enviando un chollo</h3>
                <p class="mb-20">
                  Consigue ser el chollo más votado del día y obtendrás 1 chollocoin
                </p>
                <div class="bg-orange1 mb-30 d-inline-flex rounded-20 d-flex align-items-center text-white px-15 py-4">
                  <span class="mr-10 font-normal pt-3">+ 1</span>
                  <i class="ico ico-small money"></i>
                </div>
                <div>
                  @if(!is_null($auth))
                    <a href="/nuevo/chollo" class="btn btn-green d-inline-flex px-30 rounded-25">
                      <i class="icon-fire color-orange2"></i>
                      <span class="ml-5">Enviar Chollo</span>
                    </a>
                  @else
                    <button onclick="triggerLoginModal()" class="btn btn-green d-inline-flex px-30 rounded-25">
                      <i class="icon-fire color-orange2"></i>
                      <span class="ml-5">Enviar Chollo</span>
                    </button>
                  @endif
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <h2 class="font-24 text-center mb-20">
        Premios para canjear disponibles
      </h2>
      <div class="row">
        @foreach($prizes as $item)
        <div class="col-lg-4 mb-20">
          <div class="bg-white d-flex rounded overflow-hidden">
            <div class="p-20 bg-orange1 text-white d-flex align-items-center font-20 font-bold w-30 justify-content-center">
              @if($item->value > 0)
              {{$item->value}}€
              @endif
            </div>
            <div class="p-20">
              <strong>
                {{$item->name}}
              </strong>
              <div>
                {{$item->description}}
              </div>
              <div class="mt-20">
                <button onclick="claimPrice({{$item->id}})" class="btn btn-orange1 pt-2 rounded-25">
                  <span class="mr-10">Comprar |  {{ $item->coins }} </span>
                  <span class="ico ico-small money"></span>
                </button>
              </div>
            </div>
          </div>
        </div>
        @endforeach

      </div>

    </div>
  </div>

</div>