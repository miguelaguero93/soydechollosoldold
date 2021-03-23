<div class="container py-20">
  <div class="row justify-content-center">
    <div class="col-lg-12">
      <div class="mb-15 text-center">
        <div class="font-bold font-18">
          Ofertas en tiendas populares
        </div>
        <div class="font-16">
          Utiliza el buscador y encuentra lo que buscas en nuestra web y en otras tiendas. Además, podrás activar alertas para recibir al instante los nuevos chollos que publiquemos en la web relacionados con tu búsqueda
        </div>
      </div>
      <div class="d-sm-flex justify-content-center mb-30">
        <div class="d-flex align-items-center w-100 w-max-px-sm-400">
          <form class="w-100" onsubmit="showBlanket()" action="/api/search">
            <div class="search">
                <input type="text" id="search" name="search" placeholder="Buscar en tiendas" class="h-px-45 no_autofield" autofocus @if(!is_null($query)) value="{{$query}}" @endif>
                <button>
                  <i class="fas fa-search font-20 color-blue4"></i>
                </button>
            </div>
          </form>
        </div>
      </div>

      @if(!empty($query))
        <div class="d-flex justify-content-between mb-30 bg-yellow1 border border-yellow2 pt-20 px-20 pb-15">
          <div class="">
            Resultados de <strong class="color-blue2">{{$query}}</strong>
          </div> 
      
          <div class="d-flex">
            <span>
              Activar alertas para {{$query}}
            </span>
            <div class="ml-15">
              <label class="switch">
                <input type="checkbox" v-model="activateAlert">
                <span class="switch-mark switch-mark-round"></span>
              </label>
            </div>
          </div>
        </div>

        <p class="mb-20"> Todas las ofertas que hemos encontrado por {{$query}} en tiempo real, en nuestra web y en las mejores tiendas</p>
    
        <div v-cloak class="bg-white p-5 p-md-20 mb-20">
          <div class="d-flex justify-content-between mb-15">
            <div>
              <h2 class="color-blue2 font-24 mb-0">Soydechollos</h2>
              <p class="m-0">
                Encontradas ahora mismo en nuestra web
              </p>
            </div>
            
          </div>

          <div class="slider slider-auto">
            <div class="slider-prev" id="prev">
              <i class="fas fa-angle-left"></i>
            </div>
            <div class="cycle-slideshow ml-n5 w-100" 
              data-cycle-slides=".slider-item" 
              data-cycle-fx="carousel" 
              data-cycle-timeout=0   
              data-cycle-next="#next" 
              data-cycle-prev="#prev" 
              data-allow-wrap=false>

              <div v-if="items.length" v-for="(item,index) in items" class="slider-item" :class="{'unavailable':checkAvailability(item)}">
                <div class="border hover-shadow bg-white rounded overflow-hidden w-100 m-5 amazon_content">
                  
                  <a :href="cholloLink(item)" class="w-100">
                    <div class="image image_search">
                      <img :src="item.image_small" :alt="item.name" class="d-block">
                      <span v-if="item.discount > 0" class="top-left bg-green1 color-white rounded-15 px-8 pt-4 pb-2 font-13">@{{item.discount}}%</span>
                    </div>
                  </a>
                  <div class="pl-15 pr-15 amazon_details">
                    <div class="font-bold mb-5">
                      <a :href="cholloLink(item)" class="link-none item_link w-100">
                        @{{ namePreview(item.name,80) }}
                      </a>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-10">
                      <div class="font-16 font-lg-20 color-gray-600">
                        <div class="text-through">
                          @{{ item.regular_price | numberformat }}
                        </div>
                      </div>
                      <div class="font-20 font-lg-24 color-orange3 pl-10 font-bold">
                        @{{ item.price | numberformat }}
                      </div>
                    </div>

                    <div class="d-flex justify-content-between text-center mb-2 px-20" v-show="!checkAvailability(item)">
                      <div class="cursor-pointer" @click="addFavorite(index)">
                        <div class="h-px-30 pt-10 save-click">
                          <div class="save save-hover mx-auto" :class="{animate:item.favorite,active:item.favorite}">
                            <div class="save-primary"></div>
                            <div class="save-secundary"></div>
                          </div>
                        </div>
                        <div class="font-14">Favoritos</div>
                      </div>            
                      <div class="cursor-pointer" @click="triggerSocialModal(cholloLink(item))">
                        <div class="h-px-30">
                          <i class="icon-share font-25 color-blue1"></i>
                        </div>
                        <div class="font-14">Compartir</div>
                      </div>
                    </div>      
                    
                    <div v-if="item.discount_code != null" class="info-coupon visible">
                      <div class="coupon mb-15">
                        <div class="coupon-content">
                          <span>@{{ item.discount_code }}</span>
                        </div>
                        <span class="coupon_after" @click="copyCode(item.discount_code)"><i class="far fa-clone pr-5"></i></span>
                      </div>
                    </div>
                  </div>
                  <div class="pl-15 pr-15 pt-0 pb-15">
                    <a class="btn btn-right" target="_blank" :href="'/api/gotostore/'+item.id">
                      <span class="name">
                        Ir al chollo
                      </span>
                      <span class="icon">
                        <i class="icon-external"></i>
                      </span>
                    </a>
                  </div>
                </div>
              </div>
            </div>
            <div class="slider-next" id="next">
              <i class="fas fa-angle-right"></i>
            </div>
          </div>
          
        </div>



        <div v-cloak class="bg-white p-5 p-md-20 mb-20">
          <div class="d-flex justify-content-between mb-15">
            <div>
              <h2 class="color-blue2 font-24 mb-0">Amazon</h2>
              <p class="m-0">
                Encontradas ahora mismo en Amazon
              </p>
            </div>
          </div>
          <div class="amazon_container">
            <div v-if="amazon.length" v-for="(item,index) in amazon" class="amazon_item">
                <div class="border hover-shadow bg-white rounded w-100 m-5 amazon_content">
                  <div class="image image_search">
                    <img :src="item.Images.Primary.Large.URL" :alt="item.ItemInfo.Title.DisplayValue" class="d-block p-10">
                  </div>
                  <div class="p-5 p-md-15 amazon_details">
                    <div class="font-bold mb-5 font-12 font-md-14">
                      @{{ namePreview(item.ItemInfo.Title.DisplayValue,180) }}
                    </div>
                    <div v-if="item.Offers != undefined" class="d-flex justify-content-between align-items-center mb-10">
                      <div class="font-20 font-lg-24 color-orange3 pl-10 font-bold mx-auto">
                        @{{ item.Offers.Listings[0].Price.Amount | numberformat }}
                      </div>
                    </div>
                  </div>
                  <div class="p-15">
                    <a class="btn btn-right" :href="'/api/gotoamazon?url='+encodeURIComponent(item.DetailPageURL)" target="_blank">
                      <span class="name">
                        Ir al chollo
                      </span>
                      <span class="icon">
                        <i class="icon-external"></i>
                      </span>
                    </a>
                  </div>
                </div>
            </div>
            <div v-show="loading" class="text-center w-100">
              <img src="{{asset('/images/svgs/loader.svg')}}">
            </div>
          </div>
          
        </div>


      @endif

      

    </div>
  </div>

</div>