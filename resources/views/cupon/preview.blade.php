<div class="col-12 col-lg-4">
  <div class="text-center mt-50 mb-10">
    <h2 class="font-19 color-blue2">
        Vista Previa 
    </h2>
  </div>
  <div class="mb-20 shadow rounded overflow-hidden">
    <div class="bg-white collapse-coupon-wrap">
      <div class="p-20 text-center">
        <div class="d-flex flex-lg-column line-10 align-items-center justify-content-center h-100 font-bold color-green1 border-bottom ">
          <div class="font-35 font-md-40">
            <span v-if="value > 0 & type != 3">@{{ value }}</span>
            <span v-if="type == 1">%</span>
            <span v-if="type == 2">€</span>
            <span v-if="type == 3">Envío Gratis</span>
          </div>
          <div class="font-20 font-md-24 ml-10 ml-lg-0">
            Descuento
          </div>
        </div>
      </div>
      <div>
        <div class="w-100 pl-20 pb-20 pr-20">
          <div class="line-14 pr-md-30">
            <div class="font-bold font-md-18">
                <span v-if="name.length">
                  @{{ maxlength(name,60) }} 
                </span>
                <span v-else>
                  Lorem ipsum dolor sit amet, consectetur adipisicing elit
                </span>
            </div>
            <div class="font-md-15">
                <span v-if="description.length" v-html="descriptionPreview(150)">
                </span>
                <span v-else>
                  Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                  tempor. Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                  tempor.
                </span>
            </div>
            {{-- <div class="d-flex align-items-center mt-5"><i class="fas fa-star font-25 color-yellow2"></i> <span class="ml-5 font-bold pt-5 font-md-15">Cupón Exclusivo</span></div> --}}
          </div>
          <div class="d-flex mt-20" style=""><a href="#" class="btn w-px-200 rounded-25 btn-green font-md-18 pt-5 m-auto" style="
            ">Ver código</a></div>
        </div>
        <div class="d-md-flex w-100 justify-content-between border-top">
          <div class="d-flex py-12 px-20 pl-md-30 pr-md-15 flex-wrap">
            <div class="align-items-center mr-md-20 w-50  text-nowrap"><span class="mr-5 pt-5">
              </span> <i class="fas fa-check font-16"></i>
              <span v-if="no_minimum">Sin</span> compra mínima @{{ minimum_purchase }} <span v-if="minimum_purchase>0">€</span> 
            </div>
            <div class="d-flex align-items-center mt-10 mt-lg-0 w-100 w-lg-auto">
              <div class="mr-10 mr-sm-15 pt-5 text-nowrap">
                ¿Te ha funcionado el cupón? X
              </div>
              <div class="d-flex align-items-center">
                <div class="d-flex align-items-center mr-15 hover-c-blue1">
                  <div class="fas fa-thumbs-up font-20"></div>
                  <div class="ml-5 pt-4">
                    323
                  </div>
                </div>
                <div class="d-flex align-items-center hover-c-red1">
                  <div class="fas fa-thumbs-down font-20"></div>
                  <div class="ml-5 pt-4">
                    22
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>