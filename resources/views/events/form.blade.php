 <div v-cloak class="container my-25">
  <div class="bg-white p-20 px-lg-60 py-lg-50">
    <div class="row">
      <div class="col-12">
        <div class="mr-lg-50">

          <h1 class="font-23 color-blue2 mb-40">Compartir un sorteo o un evento</h1>
  

          <div class="row">
            <div class="col-12 mb-30">
              
              <h2 class="font-19 color-blue2">
                Selecciona evento o sorteo 
              </h2>

              <div class="row">
              
                <div class="col-12 col-md-4">
                  <div class="selectable text-center" :class="{selected:type==1}" @click="selectType(1)">Evento</div>
                </div>
              
                <div class="col-12 col-md-4">
                  <div class="selectable text-center" :class="{selected:type==2}" @click="selectType(2)">Sorteo</div>
                </div>
              
              </div>

            </div>


          </div>
          
          <h2 class="font-19 color-blue2">
            Enlace del evento / sorteo <span class="font-13 font-normal color-gray-800">(Opcional)</span>
          </h2>
 

          <div class="row">
            <div class="col-lg-12">
              <div class="input mb-10">
                <input type="text" placeholder="http://www.pagina.com/cupon..." v-model="site_url"  maxlength="512">
              </div>
            </div>
          </div>
          
          <div class="font-13 mb-30 color-gray-990">
            Pega el enlace donde se puede ver más información al respecto. 
          </div>
          
          <h2 class="font-19 color-blue2">Titulo</h2>
          <div class="row">
            <div class="col-12 mb-10">
              <div class="input">
                <input type="text" placeholder="Descripción del evento/sorteo" maxlength="255" v-model="name">
              </div>
            </div>
          </div>
          {{-- <hr> <hr> --}}
          <div class="mb-30 mt-30">
            <h2 class="font-19 color-blue2">
            Imagen - 900px x 320px
            </h2>
            <input type="file" name="image" id="image" accept="image/gif, image/jpeg, image/png" class="form-control-file">
            <div class="font-13 mb-30 color-gray-990">
              Selecciona una imagen de 900px por 320px. 
            </div>
            <img src="" id="image-container" style="max-width: 100%">
          </div>

          <div class="mb-30">
            <h2 class="font-19 color-blue2">
            Descripción
            </h2>
            <div class="textarea" id="editor-container" style="height: 200px">
            </div>
          </div>


            
            
          <div class="row">
            <div class="col-sm-6 mb-30">
              <h3 class="font-16 color-gray-800">
                ¿Cuando comenzará?
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
                  </select>
                </div>
              </div>
            </div>

            <div class="col-sm-6 mb-30">
              <h3 class="font-16 color-gray-800">
                ¿Cuando finalizará?
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
            <span class="name">Compartir</span>
          </button>

        </div>
      </div>
    </div>
  </div>
</div>