<div id="menuFixedTop" class="animate__animated animate__fadeInDown menu-top-fixed" style="display: none;">
  <div class="container">
    <div class="topdivfixed">
      <input type="radio" id="tab1" name="tab-control" checked="">
      <input type="radio" id="tab2" name="tab-control">
      <input type="radio" id="tab3" name="tab-control">
      <input type="radio" id="tab4" name="tab-control">
      <ul>
        <li v-if="items.length" title="Features" v-on:click="scrollToEl('sect-soychollos')">
          <label for="tab1" role="button">
            <img class="search-li-a-div-img" src="https://soydechollos.com/storage/logo/logosoydechollos.png">
            <br>
            <span>SOYDECHOLLOS</span>
          </label>
        </li>
        <li v-if="amazon.length" title="Delivery Contents" v-on:click="scrollToEl('sect-amazon')">
          <label for="tab2" role="button">
            <img class="search-li-a-div-img" src="https://soydechollos.com/storage/stores/November2020/x4kR0OTM7pNqA2FjNGXr.png">
            <br><span>AMAZON</span>
          </label>
        </li>
      </ul>
      <div id="sl-1" class="slider">
        <div class="indicator"></div>
      </div>
    </div>
  </div>
</div>
<div class="container py-20">
  <div class="row justify-content-center">
    <div class="col-lg-12">
      <!--  -->

      @if(!empty($query))
        <!-- Section Alerts -->
        <div class="d-flex justify-content-between mb-30 bg-yellow1 border border-yellow2 pt-20 px-20 pb-15">
          <div class="f-1 text-center">
            <i class="far fa-bell font-30 cursor-pointer d-md-block"></i>
            <span class="btn-alert-search" v-on:click="goToSettingsAlerts">Gestiona alertas</span>            
          </div>  
          <div class="f-3 text-center container-alert-search">
            <span class="title-alert-search">! No te pierdas otro chollo de <strong>"{{$query}}"</strong> !</span>
            <span class="subtitle-alert-search">Recibe notificaciones cuando salgan nuevos chollos de {{$query}}</span>
          </div> 
      
          <div class="d-flex f-1 active-alerts">
            <div class="active-alerts-f1">
              <span>
                Activar alertas para {{$query}}
              </span>
              <div class="ml-15">
                <label class="switch">
                  <input type="checkbox" v-model="activateAlert">
                  <span class="switch-mark switch-mark-round" v-bind:class="{ off: !activateAlert }"></span>
                </label>
              </div>
            </div>
          </div>
        </div>
        <!-- END Section Alerts -->

        <div class="tabs" v-show="loading || (items.length || amazon.length)" >
          <input v-if="items.length"  type="radio" id="tab1" name="tab-control" checked="">
          <input type="radio" id="tab2" name="tab-control">
          <input type="radio" id="tab3" name="tab-control">
          <input type="radio" id="tab4" name="tab-control">
          <ul>
              <li v-if="items.length" title="Features" v-on:click="scrollToEl('sect-soychollos')">
                <label for="tab1" role="button">
                  <img class="search-li-a-div-img" src="https://soydechollos.com/storage/logo/logosoydechollos.png">
                  <br>
                  <span>SOYDECHOLLOS</span>
                </label>
              </li>
              <li v-if="amazon.length" title="Delivery Contents" v-on:click="scrollToEl('sect-amazon')">
                <label for="tab2" role="button">
                  <img class="search-li-a-div-img" src="https://soydechollos.com/storage/stores/November2020/x4kR0OTM7pNqA2FjNGXr.png">
                  <br><span>AMAZON</span>
                </label>
              </li>
          </ul>

          <div id="sl-2" class="slider" v-show="items.length || amazon.length">
              <div class="indicator"></div>
          </div>
          <div v-show="loading && (!items.length || !amazon.length)" class="text-center w-100">
            <img src="/images/svgs/loader.svg">
          </div>
          <div class="content">
              <section v-if="items.length" id="sect-soychollos">
                <div v-show="!loading || !first" >
                  <span class="span-in-search">Mostrando mas de {{ $itemsCount }} Ofertas de {{ $query }} en tiempo real y al mejor precio.</span>
                  <div class="amazon_container">
                    <div v-if="items.length" v-for="(item,index) in items" class="amazon_item" :class="{'unavailable':checkAvailability(item)}">
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
                  <div v-show="!loading && currentPage * 16 < itemsCount" class="search-more" v-on:click="searchMore"><span>Mostrar más Chollos...</span></div>
                  </div>
                  <div v-show="loading && !firstLoading" class="text-center w-100">
                    <img src="/images/svgs/loader.svg">
                  </div>
              </section>
              <section v-if="amazon.length" v-show="!loading || !first" id="sect-amazon">
                  <span class="span-in-search">Encontradas ahora mismo en Amazon, ofertas de {{ $query }} en tiempo real.</span>
                  <div class="amazon_container">
                    <div v-if="amazon.length" v-for="(item,index) in amazon" class="amazon_item">
                        <div class="border hover-shadow bg-white rounded w-100 m-5 amazon_content">
                          <div class="image image_search">
                            <img v-if="item.Images" :src="item.Images.Primary.Large.URL" :alt="item.ItemInfo.Title.DisplayValue" class="d-block p-10">
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
                  </div>
              </section>
          </div>
        </div>
    
        <!-- Not Found Search More -->
        <div class="tabs mtop100" v-show="!loading || !first">
          <div class="mb-15 text-center">
            <div class="not-found-text">
              <span>¿No has encontrado lo que buscabas?</span>
            </div>
          </div>
          <div class="d-flex mb-30">
            <div class="f-2 font-16 j-center">
              Prueba suerte con una nueva busqueda:
            </div>
            <div class="f-6 j-center">
              <div class="search-box">
                <form class="w-100" onsubmit="showBlanket()" action="/api/search">
                  <div class="search">
                      <input type="text" id="search" name="search" placeholder="Buscar producto" class="h-px-45 no_autofield" >
                      <button>
                        <i class="fas fa-search font-20 color-blue4"></i>
                      </button>
                  </div>
                </form>
              </div>
            </div>
          </div>
          <div class="d-flex">
            <div class="font-bold font-16 j-center">
              O establece alertas utilizando palabras clave de lo que estas buscando, y te notificaremos en que encontremos lo que buscas.
            </div>
            <div class="f-1 text-center d-flex j-center">
              <i class="far fa-bell font-30 cursor-pointer d-md-block j-center"></i>
              <span class="btn-alert-search" v-on:click="goToSettingsAlerts">Gestiona alertas</span>            
            </div>  
          </div>
        </div>
        <!-- Not Found Finish -->


      @endif

      

    </div>
  </div>

</div>