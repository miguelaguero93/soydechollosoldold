<div class="w-cal-xl-250">
  <div v-if="checkAvailability()" class="text-center text-white" style="background-color: rgb(255, 88, 88); padding: 5px; font-size: large">
    <p class="m-10">Esta oferta ya no existe. <a href="/nuevos" class="text-white"><b>Ir a novedades</b></a></p>
  </div>
  <div class="bg-white px-md-30 py-md-20 rounded">
    <div class="row" :class="{'unavailable':checkAvailability()}">
      <div class="col-lg-6">
        <div class="image">
          <img :src="item.image" class="d-flex single-item-image" :alt="'Comprar '+item.name">
        </div>
      </div>
      <div class="col-lg-6 mt-20 mt-lg-0">
        <div class="px-15 px-md-0">          
          <div class="d-flex mb-15">
            <a v-if="item.store != null" :href="storeLink(item.store)" class="no_link">
              <div class="d-inline-flex rounded-15 align-items-center px-15 py-4 cursor-pointer text-white" :style="{backgroundColor:getStoreColor()}">
                <i class="fas fa-shopping-cart font-16"></i>
                <span class="ml-5 font-13 pt-2">@{{ item.store.slug | capitalize }} </span>
              </div>
            </a>
            <div class="d-flex ml-20 align-items-center">
              <a class="ml-5 font-13 pt-2 link cursor-pointer text-center no_link" style="line-height: 1" :href="categoryLink(item.category.slug)">
                <i class="fas fa-tags pr-2"></i> @{{ item.category.name }}
              </a>
            </div>
          </div>
          <div class="d-flex justify-content-between mb-10">
            <div class="d-flex align-items-center hover-block-blue1">
              <div class="item_avatar">
                <img width="34" :src="item.user.avatar">
              </div>
              <div class="ml-5 ml-lg-10 line-11">
                <div class="color-blue1 hover-c-blue1 text-nowrap">
                  <span class="font-bold font-lg-15"><a :href="userLink(item.user)">@{{ item.user.name }}</a></span>
                </div>
                <div class="font-12 fontlg--13 color-gray-990">
                  @{{ parsedTime(item.updated_at) }} 
                </div>
              </div>
            </div>

            <div class="ml-auto d-flex align-items-center">
              <div class="w-px-130">
                <div class="rating">
                  <div class="rating-icon" v-show="voted == 0" @click="vote(-1)">
                    <i class="fas fa-minus-circle color-red1"></i>
                  </div>
                  <div class="rating-name">
                    @{{ item.votes }} 
                  </div>
                  <div class="rating-icon"  v-show="voted == 0" @click="vote(3)">
                    <i class="fas fa-plus-circle color-green2"></i>
                  </div>
                  <div v-show="voted != 0" class="vote_icon animate__animated animate__bounceInRight" >
                    <i v-show="voted == -1" class="fa fa-minus-circle color-red1"></i>
                    <i v-show="voted > 0" class="fa fa-plus-circle color-green2"></i>
                  </div>
                </div>
              </div>
              <div class="ml-5 icon-fire font-25 color-red1"></div>
            </div>
          </div>
          <div class="mb-10 font-16 item-description">
            <h1>@{{ item.name }}</h1> 
          </div>          
          <div v-if="item.discount_code" class="coupon coupon-copy mb-15 w-max-px-300 d-none d-lg-flex">
            <div class="coupon-content">
              <span id="code">@{{ item.discount_code }}</span>
            </div>
            <span class="coupon_after" onclick="copyCode()"><i class="far fa-clone pr-5"></i> Copiar</span>
          </div>
          <div class="d-flex mb-15">
            <div class="d-flex align-items-end price">
              <div class="font-16 font-lg-20 color-gray-600 pb-3">
                <div v-if="item.discount > 0">
                  (-@{{ item.discount }} %)
                </div>
                <div v-if="item.regular_price > 0" class="text-through ">
                  @{{ item.regular_price | numberformat }} 
                </div>
              </div>
              <div v-if="item.price > 0" class="font-20 font-lg-25 color-orange3 pl-10 font-bold">
                @{{ item.price | numberformat }} 
              </div>
              <div class="font-10 pl-10">
                <div v-if="item.shipping_cost > 0">
                  <i class="fas fa-truck ml-10 font-12"></i> @{{ item.shipping_cost | numberformat }}  
                </div>
              </div>
            </div>
            <div v-if='item.link != null && item.link.length > 0' class="ml-auto mt-auto d-none d-lg-flex">
              <a class="btn btn-right pl-25" rel="nofollow noopener" :href="'/api/gotostore/'+item.id" target="_blank">
                <span class="name">
                  Ir al chollo
                </span>
                <span class="icon">
                  <i class="icon-external"></i>
                </span>
              </a>
            </div>
          </div>
          <div class="buy-fixed">
            <div class="coupon coupon-lg w-100 w-max-px-300 my-5 mx-15">
              <div class="coupon-content">
                <span style="max-width: 100%" v-if="item.discount_code" onclick="copyCode()"><i class="fas fa-cut"></i> @{{ item.discount_code }}</span>
                <span v-else> @{{ item.price | numberformat }}</span>
              </div>
            </div>
            <div class="ml-auto d-flex">
              <a class="btn btn-right px-35 h-auto rounded-0 bg-white" rel="nofollow noopener" :href="'/api/gotostore/'+item.id" target="_blank">
                <span class="name">
                  Ir al chollo
                </span>
                <span class="icon">
                  <i class="icon-external"></i>
                </span>
              </a>
            </div>
          </div>
          <div class="mx-n15 mx-md-0" v-show="!checkAvailability()">
            <div class="d-flex bg-gray-200 rounded-md w-100 py-10 px-25 px-md-50 text-center justify-content-between">
              <div class="cursor-pointer">
                <div class="h-px-40 mb-md-5 pt-5 save-click" @click="addFavorite()">
                  <div class="save save-hover save-lg mx-auto" :class="{animate:favorite,active:favorite}">
                    <div class="save-primary"></div>
                    <div class="save-secundary"></div>
                  </div>
                </div>
                <div class="font-16">Favoritos</div>
              </div>
              <div class="cursor-pointer" onclick="triggerKeywordsModal()">
                <div class="h-px-40 mb-md-5">
                  <i class="far fa-bell font-30 color-blue1 icolink" style="padding-top: 9px"></i>
                </div>
                <div class="font-16">Avísame</div>
              </div>
              <div class="cursor-pointer" @click="triggerSocialModal()">
                <div class="h-px-40 mb-md-5">
                  <i class="icon-share font-40 color-blue1 icolink" style="padding-top: 9px"></i>
                </div>
                <div class="font-16">Compartir</div>
              </div>
            </div>
          </div>
          <div class="px-15 px-md-0">
            <div class="warning mt-10">
              <i class="fas fa-bell color-yellow2 font-15"></i>
              <span class="ml-5 font-13">
                ¿Oferta agotada, precio o link erróneo? <span style="text-decoration: underline; cursor: pointer; " @click="toggleReportModal()">Reportar oferta</span>
              </span>
            </div>
          </div>
          <div class="row">
            <div class="col-12 col-md-6 text-center pt-12" v-if="item.from != null">
              <i class="fas fa-clock color-blue1"></i> Empieza @{{ parsedTime(item.from) }} 
            </div> 
            <div class="col-12 col-md-6 text-center pt-12" v-if="item.until != null">
              <i class="fas fa-clock color-blue1"></i> Termina @{{ parsedTime(item.until) }} 
            </div> 
          </div>
          <div class="px-15 text-center color-blue1 pt-10" v-if="item.country !== 'España'">
            <i class="fas fa-globe"></i> Envío desde @{{ item.country }} 
          </div>
        </div>
      </div>
    </div>
    @include('item.keywords')
    <div class="single">
      <div class="collapse-title">
        Descripción
      </div>
      <div class="collapse-content description-item" v-html="item.description">
      </div>
      @include('item.comments')
      <div class="collapse-title">
        Chollos relacionados
      </div>
      @include('item.related')
    </div>
  </div>
</div>