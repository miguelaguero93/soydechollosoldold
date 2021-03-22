<div class="container py-20">
  <div class="mb-20 text-center">
    <div class="font-bold font-18">
      Los mejores codigos de descuento y cupones.
    </div>
    <div class="font-16">
      Actualizados y comprobados
    </div>
  </div>
  <div v-cloak v-if="items.length">
    <div v-for="item in items" class="mb-20 shadow rounded overflow-hidden" :class="{'unavailable':checkAvailability(item)}">
      <div class="d-lg-flex bg-white collapse-coupon-wrap">
        <div class="w-px-lg-300 border-bottom border-lg-bottom-0 p-20 p-md-30 border-lg-right text-center">
          <div class="d-flex flex-lg-column line-10 align-items-center justify-content-center h-100 font-bold color-green1">
            <div class="font-35 font-md-60">
              <span v-if="item.amount > 0 & item.type != 3">@{{ item.amount }}</span>
              <span v-if="item.type == 1">%</span>
              <span v-if="item.type == 2">€</span>
              <span v-if="item.type == 3">Envío Gratis</span>
            </div>
            <div class="font-20 font-md-24 ml-10 ml-lg-0">
              Ahorro
            </div>
          </div>
        </div>
        <div class="w-100">
          <div class="d-lg-flex w-100 p-20 p-md-30">
            <div class="w-100 line-14 pr-md-30">
              <div class="font-bold font-md-18">@{{ item.name }} </div>
              <div class="font-md-15">
                @{{ maxlength(item.description) }} 
              </div>
              <div v-if="item.is_exclusive" class="d-flex align-items-center mt-5">
                <i class="fas fa-star font-25 color-yellow2"></i>
                <span class="ml-5 font-bold pt-5 font-md-15">Cupón Exclusivo</span>
              </div>
            </div>
            <div v-if="item.code != null" class="d-lg-flex align-items-center mt-20 mt-lg-0">
              <button v-if="!item.code_visible" @click="showCode(item)" class="btn w-px-200 mx-auto mx-md-0 rounded-25 btn-green font-md-18 pt-5">Ver código</button>

             <div  v-else class="mb-20" style="display: flex;">
                <div class="coupon coupon-link">
                  <div class="coupon-content">
                    <span id="code" class="text-truncate" style="font-size: 20px; padding-right: 20px;">@{{ item.code }}</span>
                  </div>
                </div>
                <span class="coupon_after" @click="copyCode(item.code)"><i class="fa fa-clone"></i></span>
              </div>
            </div>
          </div>
          <div class="d-md-flex w-100 justify-content-between border-top">
            <div class="d-flex py-12 px-20 pl-md-30 pr-md-15 flex-wrap">

              <div class="d-flex align-items-center mr-md-20 w-50 w-md-auto text-nowrap">
                <span class="mr-5 pt-5">
                  <span v-if="item.no_minimum">Sin Compra Mínima</span> 
                  <span v-if="item.minimum_purchase > 0">Compra Mínima @{{ item.minimum_purchase }} €</span>
                </span>
                <i class="fas fa-check-in font-20"></i>
              </div>

              <div v-if="item.no_max_discount == 0 && item.max_discount != null" class="d-flex align-items-center mr-md-20 w-50 w-md-auto text-nowrap">
                <span class="mr-5 pt-5">
                  Descuento Máximo @{{ item.max_discount }}€</span>
                </span>
                <i class="fas fa-check-in font-20"></i>
              </div>

              <div class="d-flex align-items-center mt-10 mt-lg-0 w-100 w-lg-auto">
                <div class="mr-10 mr-sm-15 pt-5 text-nowrap">
                  ¿Te ha funcionado el cupón?
                </div>

                <div class="d-flex align-items-center">
                  <div @click="likeCoupon(item,1)" class="d-flex align-items-center mr-15 hover-c-blue1">
                    <div class="fas fa-thumbs-up font-20"></div>
                    <div class="ml-5 pt-4">
                      @{{ item.works }}  
                    </div>
                  </div>

                  <div @click="likeCoupon(item,2)" class="d-flex align-items-center hover-c-red1">
                    <div class="fas fa-thumbs-down font-20"></div>
                    <div class="ml-5 pt-4">
                      @{{ item.not_work }}
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div v-if="item.url != null && item.url.length > 0" class="info-chollo p-5">
              <a class="btn btn-right" :href="'/api/gotostore_d/'+item.id" target="new">
                <span class="name">
                  Ir al sitio
                </span>
                <span class="icon">
                  <i class="icon-external"></i>
                </span>
              </a>
           </div>
          </div>
        </div>
      </div>
      <div class="collapse-coupon-content">
        <div class="d-lg-flex border-top bg-gray-200 py-12 py-lg-0">
          <div  class="w-px-md-300 border-md-right px-20 px-md-30 py-lg-12 d-flex align-items-center text-center justify-content-lg-center">
            <div v-if="item.until != null">
              <i class="far fa-clock font-20"></i>
              <span class="ml-5 pt-5">
                @{{ item.until | relativeTimeCoupon }} 
                <br>
                <small>@{{ parsedTime(item.until) }} </small>
              </span>
            </div>
          </div>
          <div v-if="item.code != null" class="px-20 px-md-30 py-lg-12 d-lg-flex">
            <div class="d-flex align-items-center">
              <i class="fas fa-chart-line font-22"></i>
              <span class="ml-5 pt-4">
                Canjeado @{{ item.copied }}  veces
              </span>
            </div>
            
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="text-center" v-else>
  
    <h3>Ningún cupón encontrado.</h3>

    <div class="col-12 text-center">
      <a href="/nuevo/cupon" style="text-decoration: none;">
        <div class="bg-white p-25 rounded mb-20 mb-md-100 hoverable">
          <span class="font-bold color-blue2 font-22">Compartir Cupón </span> 
        </div>
      </a>
    </div>

  </div>
 

</div>