 <div v-cloak class="container my-25">
  <div class="bg-white p-20 px-lg-60 py-lg-50">
    <div class="row">
      <div class="col-lg-8">
        <div class="mr-lg-50">
          <h1 class="font-23 color-blue2">Compartir un cupón para una tienda</h1>
          <b><a href="/nuevo/chollo">Si es para un solo producto, compártelo como oferta en ESTE ENLACE</a></b>
          <h2 class="font-19 color-blue2 mt-40">
            Enlace de la tienda 
          </h2>
          <div class="row">
            <div class="col-lg-12">
              <div class="input mb-10">
                <input type="text" placeholder="http://www.pagina.com/cupon..." v-model="site_url" autofocus maxlength="512">
              </div>
            </div>
          </div>
          
          <div class="font-13 mb-30 color-gray-990">
            Pega el enlace donde se puede usar el cupón o ver más información al respecto. Asegúrate que cumple nuestras reglas de publicación
          </div>
          
          <div class="mb-30" v-show="error_images != null"> 
            <span class="pill pill-orange">
              <small>
                <i class="fa fa-info-circle"></i> @{{ error_images }} 
              </small>
            </span>
          </div>

          <h2 class="font-19 color-blue2">Titulo</h2>
          <div class="row">
            <div class="col-12 mb-10">
              <div class="input">
                <input type="text" placeholder="Descripción corta del cupón" maxlength="255" v-model="name">
              </div>
            </div>
          </div>
          
          <hr>

          <h2 class="font-19 color-blue2 mb-20 mt-20">Detalles del cupón</h2>

          <div class="row">
            <div class="col-12 mb-30">
              <h3 class="font-16 color-gray-800 font-normal">
                Tipo de descuento <span class="font-13 ml-20">(Opcional)</span>
              </h3>

              <div class="row">
                <div class="col-12 col-md-4">
                  <div class="selectable text-center" :class="{selected:type==1}" @click="selectType(1)">Porcentaje (%)</div>
                </div>
                <div class="col-12 col-md-4">
                  <div class="selectable text-center" :class="{selected:type==2}" @click="selectType(2)">Euros (€)</div>
                </div>
                <div class="col-12 col-md-4">                  
                  <div class="selectable text-center" :class="{selected:type==3}" @click="selectType(3)">Envío gratuito</div>
                </div>
              </div>
            </div>


  
            <div class="col-sm-6 mb-30" v-if="type==1">
              <h3 class="font-16 color-gray-800 font-normal">
                Porcentaje (%) descuento
              </h3>
              <div class="input input-blue">
                <input type="number" v-model="value" min="0">
                <span class="symbol">
                  <i class="fas fa-percent"></i>
                </span>
              </div>
            </div>

            <div class="col-sm-6 mb-30" v-if="type==2">
              <h3 class="font-16 color-gray-800 font-normal">
                Euros (€) descuento
              </h3>

              <div class="input input-blue">
                <input type="number" v-model="value" min="0">
                <span class="symbol">
                  <i class="fas fa-euro-sign"></i>
                </span>
              </div>
            </div>


          </div>
          
          <div class="row">  
            <div class="col-sm-6">
              <h3 class="font-16 color-gray-800 font-normal">
                Compra mínima <span class="font-13 ml-20">(Opcional)</span>
              </h3>
              <div class="input input-blue">
                <input type="number" v-model="minimum_purchase" :disabled="no_minimum" min="0">
                <span class="symbol">
                  <i class="fas fa-euro-sign"></i>
                </span>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="d-flex mt-35 align-items-center">
                <label class="custom_checkbox"> Sin compra mínima
                  <input type="checkbox" v-model="no_minimum">
                  <span class="checkmark"></span>
                </label>
              </div>
            </div>
          </div>

          <div class="row mt-35">  
            <div class="col-sm-6">
              <h3 class="font-16 color-gray-800 font-normal">
                Descuento máximo <span class="font-13 ml-20">(Opcional)</span>
              </h3>
              <div class="input input-blue">
                <input type="number" v-model="max_discount" :disabled="no_max_discount" min="0">
                <span class="symbol">
                  <i class="fas fa-euro-sign"></i>
                </span>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="d-flex mt-35 align-items-center">
                <label class="custom_checkbox"> Sin límite de descuento
                  <input type="checkbox" v-model="no_max_discount">
                  <span class="checkmark"></span>
                </label>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-12 mb-30 mt-35">
              <h3 class="font-16 color-gray-800 font-normal">
                Código descuento o cupón
              </h3>
              <div class="input">
                <input type="text" placeholder="Código a utilizar para obtener el descuento" v-model="coupon" maxlength="46">
              </div>
            </div>
          </div>

          <div class="mb-30">

            <h3 class="font-16 color-gray-800 font-normal">
              Descripción
            </h3>
            
            <textarea id="myTextarea"></textarea>
            
          </div>

          <div class="row">
            <div class="col-sm-6 mb-30">
              <h3 class="font-16 color-gray-800">
                ¿Cuando comenzará? <span class="font-13 ml-20 font-normal">(Opcional)</span>
              </h3>
              <div class="flex_group">
                
                <div class="input">
                  <input type="text" id="datepickerstart" readonly="">
                  <span class="symbol">
                    <i class="far fa-calendar" aria-hidden="true"></i>
                  </span>
                </div>

                <div class="select" v-show="start.length" style="margin-left: 10px">
                  <select v-model="start_time">
                    <option>00:00</option>
                    <option>01:00</option>
                    <option>02:00</option>
                    <option>03:00</option>
                    <option>04:00</option>
                    <option>05:00</option>
                    <option>06:00</option>
                    <option>07:00</option>
                    <option>08:00</option>
                    <option>09:00</option>
                    <option>10:00</option>
                    <option>11:00</option>
                    <option>12:00</option>
                    <option>13:00</option>
                    <option>14:00</option>
                    <option>15:00</option>
                    <option>16:00</option>
                    <option>17:00</option>
                    <option>18:00</option>
                    <option>19:00</option>
                    <option>20:00</option>
                    <option>21:00</option>
                    <option>22:00</option>
                    <option>23:00</option>
                    <option selected>23:59</option>
                  </select>
                </div>
              </div>
              <div class="pt-10" v-show="start.length">
                <span class="cursor-pointer" @click="deleteStartDate()"><i class="fas fa-times"></i> Borrar fecha</span>
              </div>
            </div>
            <div class="col-sm-6 mb-30">
              <h3 class="font-16 color-gray-800">
                ¿Cuando finalizará? <span class="font-13 ml-20 font-normal">(Opcional)</span>
              </h3>
              <div class="flex_group">
                <div class="input">
                  <input type="text" id="datepickerend" readonly="">
                  <span class="symbol">
                    <i class="far fa-calendar" aria-hidden="true"></i>
                  </span>
                </div>
                <div class="select" v-show="end.length" style="margin-left: 10px">
                  <select v-model="end_time">
                    <option>00:00</option>
                    <option>01:00</option>
                    <option>02:00</option>
                    <option>03:00</option>
                    <option>04:00</option>
                    <option>05:00</option>
                    <option>06:00</option>
                    <option>07:00</option>
                    <option>08:00</option>
                    <option>09:00</option>
                    <option>10:00</option>
                    <option>11:00</option>
                    <option>12:00</option>
                    <option>13:00</option>
                    <option>14:00</option>
                    <option>15:00</option>
                    <option>16:00</option>
                    <option>17:00</option>
                    <option>18:00</option>
                    <option>19:00</option>
                    <option>20:00</option>
                    <option>21:00</option>
                    <option>22:00</option>
                    <option>23:00</option>
                    <option>23:59</option>

                  </select>
                </div>
              </div>
              <div class="pt-10" v-show="end.length">
                <span class="cursor-pointer" @click="deleteEndDate()"><i class="fas fa-times"></i> Borrar fecha</span>
              </div>
            </div>
          </div>

          <div class="mb-30">
            <h3 class="font-16 color-gray-800"> Disponible en toda España ? </h3>
            
            <div>
              <label class="container-check">Si
                <input type="radio" checked="checked" name="spain_selector" :value="true" v-model="all_spain">
                <span class="radio"></span>
              </label>
              <label class="container-check">No
                <input type="radio" name="spain_selector" :value="false" v-model="all_spain">
                <span class="radio"></span>
              </label>
            </div>

            <div class="row bg-gray-150 rounded" style="margin:0">
              <div v-if="!all_spain" class="col-12 p-10">
                <div class="country_selector">
                  <label>Seleccione Comunidades</label>
                  <br>
                  <div class="dropdown">
                    <div class="dropdown-trigger"> <i class="fa fa-map-marker" aria-hidden="true"></i> @{{ number_of_provinces }}  Comunidades seleccionadas</div>
                    <div class="dropdown-content">
                      <div v-for="item in provinces" class="dropdown-item">                        
                        <label class="custom_checkbox">
                            <small>@{{ item }}</small> 
                            <input type="checkbox" v-model="selected_provinces" :value="item">
                            <span class="checkmark"></span>
                        </label>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>           

          <div class="mt-20 mb-30">
            <label class="custom_checkbox">Oferta con contenido sexual +18
              <input type="checkbox" v-model="sexual_content">
              <span class="checkmark"></span>
            </label>
          </div>

          <button class="btn btn-primary" @click="submit()">
            <i class="fas fa-paper-plane"></i>
            <span class="name">Compartir el cupón</span>
          </button>
        </div>
      </div>
      @include('cupon.preview')
    </div>
  </div>
</div>