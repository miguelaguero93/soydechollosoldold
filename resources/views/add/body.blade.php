 <div v-cloak class="container my-25">
  <div class="bg-white p-20 px-lg-60 py-lg-50">
    <div class="row">
      <div class="col-lg-8">
        <div class="mr-lg-50">
            @if(isset($item))
              <h1 class="font-23 color-blue2 mb-40">Editar chollo</h1>
            @else
              <h1 class="font-23 color-blue2 mb-40">Compartir un nuevo chollo</h1>
            @endif
          <h2 class="font-19 color-blue2">
            Enlace del chollo <span class="font-13 font-normal color-gray-800">(Opcional)</span>
          </h2>
          <div class="row">
            <div class="col-lg-12">
              <div class="input mb-10">
                <input type="text" placeholder="http://www.pagina.com/soychollo..." v-model="site_url" autofocus maxlength="512">
              </div>
            </div>
          </div>
          
          <div class="font-13 mb-30 color-gray-990">
            Pega el link donde los demás miembros podrán encontrar más información o comprar el artículo del chollo
          </div>
          
          <div class="mb-30" v-show="loading_images"> 
            <span class="pill pill-blue">
              <i class="fas fa-circle-notch fa-spin"></i> Cargando Imagenes
            </span>
          </div>


          <div class="mb-30 own_image" v-show="error_images !== null && admin == 0"> 
            <span class="pill pill-orange" style="cursor: pointer" onclick="uploadOwnFile()">
              <small>
                <i class="fa fa-info-circle"></i> Sube tu propia imagen 
              </small>
            </span>
            <span class="pill pill-blue ml-3" style="cursor: pointer" @click="uploadFromURL()">
              <small>
                <i class="fas fa-link"></i> Añadir desde URL 
              </small>
            </span>
          </div>

          <div class="mb-30 own_image" v-show="admin == 1"> 
            <span class="pill pill-orange" style="cursor: pointer" onclick="uploadOwnFile()">
              <small>
                <i class="fa fa-info-circle"></i> Subir imagen 
              </small>
            </span>
            <span class="pill pill-blue ml-3" style="cursor: pointer" @click="uploadFromURL()">
              <small>
                <i class="fas fa-link"></i> Añadir desde URL 
              </small>
            </span>
          </div>

          <div v-if="uploadingFromURL" class="row mb-30">
            <div class="col-12">
              <h2 class="font-19 color-blue2">
                Inserte URL de la imagen
              </h2>
            </div>
            <div class="col-lg-12">
              <div class="input mb-10">
                <input type="text" placeholder="http://www.pagina.com/imagen.jpg" v-model="image_url" maxlength="512">
              </div>
            </div>
          </div>
          <div class="mb-30 duplicate_alert" v-if="existing != null"> 
           <div class="color-orange3 font-bold text-center mb-10">
              <i class="fas fa-exclamation-triangle"></i> 
              ATENCIÓN! Puede que el chollo ya esté publicado
           </div>
           <div class="row">
             <div class="col-12 col-md-3">
               <img class="w-100" :src="existing.image">
             </div>
             <div class="col-12 col-md-9">
               <b><a :href="cholloLink(existing)" target="_blank">@{{ existing.name }}</a></b> <br>
               <span class="color-orange3 font-bold">@{{ existing.price | numberformat }}</span> -
               Publicado el @{{ parsedTime(existing.created_at) }}
               <div class="text-right mt-15">
                 <button class="btn btn-white" style="display: inline-block" @click="cancelCreation">Cancelar</button>
                 <button class="btn" style="display: inline-block" @click="continueCreation()">No es el mismo, continuar</button>
               </div>
             </div>
           </div>
          </div>

          <div class="mb-30" v-show="avaiable_images.length > 0"> 
            <h2 class="font-19 color-blue2 mb-30">Imagen del chollo</h2>            
            <div class="vertically-scrollable" style="overflow-x: auto"> 
              <div id="images_container">
                <div v-for="(item,index) in avaiable_images" class="image_option" :class="{selected:item.selected}" @click="setMainImage(index)">
                  <img :src="item.src" width="200px">
                </div>
              </div>
            </div>
          </div>
        
          <h2 class="font-19 color-blue2">Titulo del chollo</h2>
          <div class="row">
            <div class="col-12 mb-10">
              <div class="input">
                <input type="text" placeholder="Indica de qué producto o promocion se trata" maxlength="255" v-model="name"  v-on:blur="handleBlur">
              </div>
            </div>
          </div>
          <div class="font-13 mb-30 color-gray-990">
            Por favor, asegúrate que el título es claro y sencillo.
          </div>


          <h2 class="font-19 color-blue2 mb-30">Detalles del chollo</h2>
          <div class="row">
            <div class="col-sm-6 mb-30">
              <h3 class="font-16 color-gray-800 font-normal">
                Precio del Chollo <span class="font-13 ml-20">(Opcional)</span>
              </h3>
              <div class="input input-blue">
                <input type="number" name="price" id="price" v-model="price" min="0">
                <span class="symbol">
                  <i class="fas fa-euro-sign"></i>
                </span>
              </div>
              <div v-show="price_on_error" class="pill pill-red"><small><i class="fa fa-warning"></i> Ingresa solo números y puntos.</small></div>
            </div>
  
            <div class="col-sm-6 mb-30">
              <h3 class="font-16 color-gray-800 font-normal">
                Precio Habitual <span class="font-13 ml-20">(Opcional)</span>
              </h3>
  
              <div class="input input-blue">
                <input type="number" name="price" id="price" v-model="regular_price" min="0">
                <span class="symbol">
                  <i class="fas fa-euro-sign"></i>
                </span>
              </div>

              <div v-show="regular_price_on_error" class="pill pill-red"><small><i class="fa fa-warning"></i> Ingresa solo números y puntos.</small></div>
            </div>
          </div>
  
          <div class="row mb-30">
  
            <div class="col-sm-6">
              <h3 class="font-16 color-gray-800 font-normal">
                Gastos de envío <span class="font-13 ml-20">(Opcional)</span>
              </h3>
              <div class="input input-blue">
                <input type="number" v-model="shipping_cost" :disabled="free_shipping">
                <span class="symbol">
                  <i class="fas fa-euro-sign"></i>
                </span>
              </div>
              <div v-show="delivery_on_error" class="pill pill-red"><small><i class="fa fa-warning"></i> Ingresa solo números y puntos.</small></div>
            </div>
            <div class="col-sm-6">
              <div class="d-flex mt-35 align-items-center">
                <label class="custom_checkbox">Envío gratuito
                  <input type="checkbox" v-model="free_shipping">
                  <span class="checkmark"></span>
                </label>
                <i class="fas fa-truck ml-10 font-12"></i>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-12 mb-30">
              <h3 class="font-16 color-gray-800 font-normal">
                Código descuento o cupón <span class="font-13 ml-20">(Opcional)</span>
              </h3>
              <div class="input">
                <input type="text" placeholder="Código a utilizar para obtener el descuento" v-model="coupon" maxlength="50">
              </div>
            </div>
          </div>
          <div class="mb-30">
            <h3 class="font-16 color-gray-800 font-normal">
              Descripción
            </h3>
            <div class="textarea mb-10" id="editor-container" style="height: 200px">
            </div>
            <div class="font-13 mb-30 color-gray-990">
              Si eres especialista en la temática, tus aportaciones pueden tener muchísimo valor para la comunidad, siéntente libre de añadir tu valoración personal en la descripción
            </div>
          </div>
          <div class="mb-30">
            <h3 class="font-16 color-gray-800">
              Categoría principal
            </h3>
            <div v-if="selected_category == null" class="d-flex flex-wrap mt-10">
              <div class="font-14 color-gray-990 mb-10">
                Por favor, selecciona al menos una categoría que se corresponda realmente al chollo, así los demás podrán encontrar lo que buscan:
              </div>
              <div v-for="(item,index) in categories" class="tag" @click="selectCategory(index)">
                <span v-html="item.icon"></span>
                <span>@{{ item.name }} </span>
              </div>
            </div>
            <div v-else class="d-flex flex-wrap mt-10">
              <div class="font-14 color-gray-990 mb-10">
                Has seleccionado la siguiente categoría. ¿No es la categoría más relevante para el chollo? Haz clic en la categoría para poder cambiarla.
              </div>
              <div class="tag selected" @click="unSelectCategory()">
                <i class="fa fa-plus" style="font-size: 14px"></i>
                <span>@{{ selected_category.name }} </span>
              </div>
            </div>
          </div>
          <div class="mb-30">
            <h3 class="font-16 color-gray-800">
              Palabras claves
            </h3>
            <div class="input">
              <input type="text" maxlength="100" placeholder="Palabras claves separadas por comas. Ejemplo: apple, huawei, xaomi" v-model="keywords_input">
            </div>
            <div class="font-13 mb-30 color-gray-990">
              Palabras claves separadas por comas. Ejemplo: apple, huawei, xaomi
            </div>
            <div class="d-flex flex-wrap mt-10">
              <div v-for="(item,index) in keywords" class="tag selected animated bounceIn" @click="removeTag(index)">
                  <small><i class="fa fa-hashtag"></i></small>
                  <span>@{{ item }} </span>
              </div>
            </div>
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
                    <option selected>00:00</option>
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
                    <option selected>23:59</option>

                  </select>
                </div>
              </div>
              <div class="pt-10" v-show="end.length">
                <span class="cursor-pointer" @click="deleteEndDate()"><i class="fas fa-times"></i> Borrar fecha</span>
              </div>
            </div>
            
          </div>
          <div class="mb-10">
            <h3 class="font-16 color-gray-800">
              Envío desde? 
            </h3>

            <div>
              <label class="container-check">España
                <input type="radio" checked="checked" name="country_selector" value="España" v-model="country">
                <span class="radio"></span>
              </label>
              <label class="container-check">China
                <input type="radio" name="country_selector" value="China" v-model="country">
                <span class="radio"></span>
              </label>
              <label class="container-check">Otro
                <input type="radio" name="country_selector" value="Otro" v-model="country">
                <span class="radio"></span>
              </label>            
            </div>
          </div>

          <div class="mb-30" v-if="country == 'Otro'">  
            <form>
              <div class="country_selector">
                <label>Seleccione país de envío </label>
                <v-select :options="options" v-model="another_country"></v-select>
              </div>
            </form>
          </div>

          <div class="mb-30">
            <h3 class="font-16 color-gray-800"> Envío a toda España ? </h3>
            
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
          @if($admin == 1)
            <div class="mt-20 mb-30">
              
              <hr>
              <b>Edición Admin</b>
              <div class="mt-20 mb-30">
                <label class="custom_checkbox">Disponible
                  <input type="checkbox" v-model="available">
                  <span class="checkmark"></span>
                </label>
              </div>
              <div class="mt-20 mb-30">
                <h3 class="font-16 color-gray-800">
                    Fecha Edición 
                </h3>
                <input type="date" class="from-control" v-model="updated_at">
              </div>
              <div class="mt-20 mb-30">
                <h3 class="font-16 color-gray-800">
                    Categoria 
                </h3>
                <v-select class="style-chooser" :options="all_categories" label="name" v-model="realCat" :reduce="name => name.id" placeholder="Buscar"></v-select>
              </div>
              <div class="mt-20 mb-30">
                <h3 class="font-16 color-gray-800">
                    Marca 
                </h3>
                <v-select class="style-chooser" :options="brands" label="value" v-model="brand_id" :reduce="value => value.id" placeholder="Buscar"></v-select>
              </div>
              <div class="mt-20 mb-30">
                <h3 class="font-16 color-gray-800">
                    Tienda 
                </h3>
                <v-select class="style-chooser" :options="stores" label="name" v-model="store_id" :reduce="name => name.id" placeholder="Buscar"></v-select>
              </div>
            </div>
          @endif

          <button class="btn btn-primary" @click="submit()">
            <i class="fas fa-paper-plane"></i>
            @if(isset($item))
              <span class="name">Guardar cambios</span>
            @else
              <span class="name">Compartir un chollo</span>
            @endif
          </button>
        </div>
      </div>
      @include('add.preview')
    </div>
  </div>
</div>