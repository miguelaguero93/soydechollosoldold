<div class="w-cal-xl-250">
  @if(isset($user))
    <div class="w-100" style="overflow: auto;">
      <div class="navigation_mobile rounded-5 mb-10">
        <a href="/estadisticas/{{$user->id}}/{{nicename($user->name)}}" class="navigation_mobile_item d-md-none">
          <div class="pb-10 pt-15 px-15">
            ESTADISTICAS
          </div>
        </a>
        <a href="/medallas/{{$user->id}}/{{nicename($user->name)}}" class="navigation_mobile_item">
          <div class="pb-10 pt-15 px-15">
            MEDALLAS
          </div>
        </a>
        <a href="/seguidores/{{$user->id}}/{{nicename($user->name)}}" class="navigation_mobile_item comentados">
          <div class="pb-10 pt-15 px-15">
            SEGUIDORES
          </div>
        </a>
        <a href="/siguiendo/{{$user->id}}/{{nicename($user->name)}}" class="navigation_mobile_item comentados">
          <div class="pb-10 pt-15 px-15">
            SIGUIENDO
          </div>
        </a>  
        <a href="#" class="navigation_mobile_item  selected">
          <div class="pb-10 pt-15 px-15">
            CHOLLOS PUBLICADOS
          </div>
        </a>
      </div>
    </div>
  @endif
  @if(isset($_GET['page']) && $_GET['page'] > 1)
    @include('index.paginator_top')
  @endif
  <div v-if="items.length" class="content @if(!Cookie::has('display')) content-column @else content-vertical @endif">
    <div v-for="(item,index) in items" class="item" :class="{'unavailable':checkAvailability(item)}">
      <a :href="cholloLink(item)">
        <div class="item-image">
          <div class="image">
            <img v-if="item.image != null" :src="item.image_small" :alt="'Oferta '+item.name">
            <span v-if="item.discount > 0" class="top-left bg-green1 color-white rounded-15 px-8 pt-4 pb-2 font-13">@{{ item.discount }}%</span>
            <i class="icon-fire color-red1 font-20 top-right"></i>
          </div>
        </div>
      </a>
      <div class="info">
        <div class="info-header">
            <div class="info-header-author">
            <div class="item_avatar">
              <img :src="item.user.avatar">
            </div>
            <div class="info-header-author-avatar ml-5 ml-lg-10 line-11">
              <div class="color-blue1">
                <span class="font-bold font-lg-15"><a :href="userLink(item.user)">@{{ item.user.name }}</a></span>
              </div>
              <div class="font-12 fontlg--13 color-gray-990">
                @{{ parsedTime(item.updated_at) }} 
              </div>
            </div>
          </div>
          <div class="info-header-featured">
            <div class="d-lg-flex align-items-center">
              <div class="info-header-comment">
                <div class="d-flex border rounded-15 align-items-center px-8 py-4 color-gray-900">
                  <i class="fas fa-comment pr-3"></i>
                  <span class="ml-5 font-13 pt-2">@{{ item.comments_count }} </span>
                </div>
              </div>
              <div class="info-header-shop">
                <a v-if="item.store != null" :href="storeLink(item.store)" class="no_link">
                  <div class="d-inline-flex rounded-15 align-items-center px-15 py-4 cursor-pointer text-white" :style="{backgroundColor:getStoreColor(item)}">
                    <i class="fas fa-shopping-cart font-16"></i>
                    <span class="ml-5 font-13 pt-2">@{{ item.store.slug | capitalize }} </span>
                  </div>
                </a>
              </div>
              <div v-if="item.category != null" class="info-header-type info-header-category d-none d-md-block">
                <a class="ml-5 font-12 pt-2 link cursor-pointer text-center no_link" style="line-height: 1" :href="categoryLink(item.category.slug)">
                <i class="fas fa-tags pr-2"></i> @{{ namePreview(item.category.name,10) }}</a>
              </div>
            </div>
          </div>
        </div>
        <div class="info-content">
          <div class="font-bold mb-5 font-13 font-lg-16">
            <a :href="cholloLink(item)" class="link-none item_link">@{{ namePreviewChollo(item.name) }}</a> 
          </div>
          <div class="d-none d-lg-block overflow-description" v-html="item.snippet"></div>
        </div>
        <div class="info-coupon" :class="{visible_coupon:item.discount_code != null}">
          <div class="coupon" @click="copyCode(item.discount_code)">
            <div class="coupon-content overflow-coupon">
              <span class="coupon-span">@{{ item.discount_code }}</span>
            </div>
            <span class="coupon_after"><i class="far fa-clone pr-5"></i></span>
          </div>

        </div>
        <div class="info-rating d-none d-lg-flex">
          <div class="rating">
            <div class="rating-icon" v-show="item.user_vote == null"  @click="vote(index,-1)">
              <i class="fas fa-minus-circle color-red1"></i>
            </div>
            <div class="rating-name">
              @{{ item.votes }} 
            </div>
            <div class="rating-icon" v-show="item.user_vote == null" @click="vote(index,3)">
              <i class="fas fa-plus-circle color-green2"></i>
            </div>
            <div v-show="item.user_vote != null" class="vote_icon animate__animated animate__bounceInRight" >
              <i v-show="item.user_vote == -1" class="fa fa-minus-circle color-red1"></i>
              <i v-show="item.user_vote > 1" class="fa fa-plus-circle color-green2"></i>
            </div>
          </div>
        </div>
        <div class="info-share" v-show="!checkAvailability(item)">
          <div class="circle" @click="addFavorite(index)">
            <div class="save" :class="{animate:item.favorite,active:item.favorite}">
              <div class="save-primary"></div>
              <div class="save-secundary"></div>
            </div>
          </div>
          <div class="circle social-circle" @click="triggerSocialModal(cholloLink(item))">
            <i class="icon-share"></i>
          </div>
        </div>
        <div class="info-comment">
          <a :href="cholloLinkComments(item)" class="no_link">
              <div class="comment">
                <i class="fas fa-comment pr-3"></i> <span>@{{ item.comments_count }} </span>
              </div>
          </a>
        </div>    
        <div class="info-block">
          <div class="info-price">
            <div v-if="item.regular_price > 0" class="font-14 font-lg-20 text-through color-gray-600">
              @{{ item.regular_price | numberformat }} 
            </div>
            <div v-if="item.price > 0" class="font-18 font-lg-25 color-orange3 pl-5 font-bold">
              @{{ item.price | numberformat }}
            </div>
            <div class="font-10 pl-5">
              <div v-if="item.shipping_cost > 0">
                @{{ item.shipping_cost | numberformat }} Envío  
              </div>
              <div v-if="item.free_shipping" class="text-center">
                  <i class="fas fa-truck font-12"></i> Gratis 
              </div>
            </div>
          </div>
        </div>
        <div v-if="sent == 1" class="info-chollo">
          <a class="btn btn-right" rel="nofollow noopener" :href="'/editar/'+item.id">
            <span class="name">
              Editar
            </span>
            <span class="icon">
              <i class="fas fa-edit"></i>
            </span>
          </a>
       </div>
        <div class="info-chollo">
          <a class="btn btn-right offer_btn" rel="nofollow noopener" :href="'/api/gotostore/'+item.id" target="_blank">
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
  <div v-else class="text-center mt-20">
      <h3>Ningún chollo aquí.</h3>
      <div class="col-12 text-center">
        <a href="/nuevo/chollo" style="text-decoration: none;">
          <div class="bg-white p-25 rounded mb-20 mb-md-100 hoverable">
            <span class="font-bold color-blue2 font-22">Compartir chollo </span> 
          </div>
        </a>
      </div>
  </div>
  <div v-show="loading" class="text-center">
    <img src="/images/svgs/loader.svg">
  </div>
  @include('index.paginator')
</div>