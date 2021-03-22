<div v-cloak class="container py-20">
  @if($cupons == 1)
    <div class="mb-20 text-center">
      <div class="font-bold font-18">
        Consigue cupones de descuento en estas tiendas
      </div>
      <div class="font-16">
        Busca tu tienda y disfruta las ofertas
      </div>
    </div>
  @else
    <div class="mb-20 text-center">
      <div class="font-bold font-18">
        Consigue descuentos en estas tiendas
      </div>
      <div class="font-16">
        Busca tu tienda y disfruta las ofertas
      </div>
    </div>
  @endif
  <form>
  <div class="d-sm-flex justify-content-between mb-30 flex-row-reverse">
    <div class="d-flex align-items-center w-100 w-max-px-sm-400">
      <div class="country_selector w-100">
        <v-select :options="options" label="name" placeholder="Buscar tienda" :value="options" v-model="itemSelected">
          <span slot="no-options">Lo sentimos, no se ha encontrado esta tienda.</span>
        </v-select>
      </div>
    </div>
  </div>
  </form>
  <h2 class="mb-15 font-20 font-bold color-gray-500">Tiendas Populares</h2>
  @include('stores.slider')
  <div class="bg-white p-10 mb-20">
    <ul class="alphabet">
      <li><a href="#L_A">A</a></li>
      <li><a href="#L_B">B</a></li>
      <li><a href="#L_C">C</a></li>
      <li><a href="#L_D">D</a></li>
      <li><a href="#L_E">E</a></li>
      <li><a href="#L_F">F</a></li>
      <li><a href="#L_G">G</a></li>
      <li><a href="#L_H">H</a></li>
      <li><a href="#L_I">I</a></li>
      <li><a href="#L_J">J</a></li>
      <li><a href="#L_K">K</a></li>
      <li><a href="#L_L">L</a></li>
      <li><a href="#L_M">M</a></li>
      <li><a href="#L_N">N</a></li>
      <li><a href="#L_O">O</a></li>
      <li><a href="#L_P">P</a></li>
      <li><a href="#L_Q">Q</a></li>
      <li><a href="#L_R">R</a></li>
      <li><a href="#L_S">S</a></li>
      <li><a href="#L_T">T</a></li>
      <li><a href="#L_U">U</a></li>
      <li><a href="#L_U">V</a></li>
      <li><a href="#L_W">W</a></li>
      <li><a href="#L_Y">Y</a></li>
      <li><a href="#L_Z">Z</a></li>
    </ul>
  </div>

  <div v-for="(item,index) in items" :id="'L_'+index" class="bg-white d-md-flex py-40 px-20 mb-20">
    <div class="w-px-md-300 border-bottom border-md-bottom-0 border-md-right mr-md-70 mb-20 mb-md-0 text-sm-center">
      <div class="d-md-flex align-items-center justify-content-center h-100 font-30 font-md-90 font-bold">
        @{{ index }} 
      </div>
    </div>
    <div class="w-100">
      <ul class="listcategory">
        <li v-for="store in item">
          <a v-if="cupons" :href="'/codigos-descuento/'+store.slug">@{{ (store.visible_name).trim().toLowerCase().replace(/\w\S*/g, (w) => (w.replace(/^\w/, (c) => c.toUpperCase()))) }}</a>
          <a v-else :href="'/tienda/'+store.slug">@{{ (store.visible_name).trim().toLowerCase().replace(/\w\S*/g, (w) => (w.replace(/^\w/, (c) => c.toUpperCase()))) }}</a>
        </li>
      </ul>
    </div>
  </div>

</div>