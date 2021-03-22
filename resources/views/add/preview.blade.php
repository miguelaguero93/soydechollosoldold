<div id="left" class="col-lg-4 mt-40 mt-lg-0">
  <h2 class="font-22 color-blue2 text-center">Vista previa</h2>            
  <div id="sidebar" class="w-px-lg-390 d-flex vertically-scrollable">
    <div class="w-100">
      <div class="content content-column container-preview">
       <div class="item" style="width: 280px !important; margin: auto;">
          <div class="item-image">
            <div class="image image-preview w-100">
              <img v-if="main_image != null" :src="main_image">
              <div v-else class="text-center" onclick="uploadOwnFile()">
                <i class="fas fa-plus font-90 color-blue2"></i>
                <div class="font-16 font-bold mb-20 color-blue2" >
                  Sube tu propia imagen
                </div>
              </div>
              <span class="top-left bg-green1 color-white rounded-15 px-8 pt-4 pb-2 font-13">-@{{discount}}%</span>
              <i class="icon-fire color-red1 font-29 top-right"></i>
            </div>
            <input type="file" name="image" id="image" accept="image/gif, image/jpeg, image/png" class="form-control-file" style="visibility: hidden;">

            <div class="coupon">
              <div class="coupon-content">
                <span>REALME10</span>
              </div>
            </div>
          </div>
          <div class="info">
    
            <div class="info-header">
  
              <div class="info-header-author">
                <div class="item_avatar"><img src="https://soydechollos.com/images/default.png"></div>
                <div class="info-header-author-avatar ml-5 ml-lg-10 line-11">
                  <div class="color-blue1">
                    <span class="font-bold font-lg-15">Chollos</span>
                    <i class="icon-shield ml-5 font-15 font-lg-17"></i>
                  </div>
                  <div class="font-12 fontlg--13 color-gray-990 text-right">
                    @{{ currentTime() }} 
                  </div>
                </div>
              </div>
      
              <div class="info-header-featured">
                <div class="d-lg-flex align-items-center">
                  <div class="info-header-comment">
                    <div class="d-flex border rounded-15 align-items-center px-8 py-4 color-gray-900">
                      <i class="fas fa-comments pr-2"></i> <span class="ml-5 font-13 pt-2">24</span>
                    </div>
                  </div>
          
                  <div v-if="site_name.length" class="info-header-shop">
                    <div class="d-inline-flex rounded-15 align-items-center px-15 py-4 bg-orange1 bg-orange1-hover cursor-pointer text-white">
                      <i class="fas fa-shopping-cart font-16"></i>
                      <span class="ml-5 font-13 pt-2">@{{ site_name }} </span>
                    </div>
                  </div>
        
                  <div v-if="selected_category != null" class="info-header-type info-header-category">
                    <span class="ml-5 font-11 pt-2 link cursor-pointer"><i class="fa fa-tag font-11"></i> @{{ selected_category.name }} </span>
                  </div>
                </div>
              </div>

            </div>
            
            <div class="info-content">
              <div class="font-bold mb-5 font-13 font-lg-14">
                <span v-if="name.length">
                  @{{ maxlength(name,60) }} 
                </span>
                <span v-else>
                  Lorem ipsum dolor sit amet, consectetur adipisicing elit
                </span>
              </div>
              <div class="d-none d-lg-block">
                <span v-if="description.length" v-html="descriptionPreview(90)">
                </span>
                <span v-else>
                  Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                  tempor...
                </span>
              </div>
            </div>

            <div v-if="coupon != null && coupon.length > 3" class="info-coupon">
              <div class="coupon">
                <div class="coupon-content">
                  <span>@{{ coupon }} </span>
                </div>
              </div>
            </div>

            <div class="info-rating d-none d-lg-flex">
              <div class="rating">
                <div class="rating-icon">
                  <i class="fas fa-minus-circle color-red1"></i>
                </div>
                <div class="rating-name">
                  4456
                </div>
                <div class="rating-icon">
                  <i class="fas fa-plus-circle color-green2"></i>
                </div>
              </div>
            </div>

            <div class="info-share">
              <div class="circle">
                <div class="save active">
                  <div class="save-primary"></div>
                  <div class="save-secundary"></div>
                </div>
              </div>
              <div class="circle" style="left: 36px">
                <i class="fas fa-share-alt"></i>
              </div>
            </div>
            
            <div class="info-comment">
              <div class="comment">
                <i class="fas fa-comments pr-2"></i> <span>24</span>
              </div>
            </div>
    
            <div class="info-block">
              <div class="info-price">
                <div class="font-16 font-lg-20 text-through color-gray-600">
                  @{{ regular_price | numberformat }} 
                </div>
                <div class="font-20 font-lg-25 color-orange3 pl-10 font-bold">
                  @{{ price | numberformat }}
                </div>
              </div>
            </div>
            <div class="info-block">
              <i class="fas fa-truck ml-10 font-12"></i>
              <div v-if="free_shipping">
                  <label style="position: relative;bottom: 5px"> Envío gratuito </label>
              </div>
              <div v-if="shipping_cost > 0">
                @{{ shipping_cost | numberformat }} Envío  
              </div>
            </div>

            <div class="info-chollo">
              <a class="btn btn-right" href="#">
                <span class="name">
                  Ir al chollo
                </span>
                <span class="icon">
                  <i class="fas fa-external-link-alt"></i>
                </span>
              </a>
           </div>          
          </div>
        </div>
      </div>
    </div>
  </div>
</div>