<div v-cloak class="container py-20">

  <div class="mb-20 text-center">
    <div class="font-bold font-18">
      Las busquedas más realizadas 
    </div>
    <div class="font-16">
      Estas son los productos mas populares en nuestra comunidad
    </div>
  </div>

  <div class="d-sm-flex justify-content-between mb-30 flex-row-reverse">
      
      <div class="d-flex align-items-center w-100 w-max-px-sm-400">
        <form class="w-100">
          <div class="search">
              <input type="text" class="h-px-45" placeholder="Buscar etiquetas" name="q">
              <button type="submit">
                <i class="fas fa-search font-20 color-blue4"></i>
              </button>
          </div>
        </form>
      </div>
  </div>

  <div class="bg-white p-10 mb-20">
    <ul class="alphabet">
      <li><a href="?l=1">1</a></li>
      <li><a href="?l=2">2</a></li>
      <li><a href="?l=3">3</a></li>
      <li><a href="?l=4">4</a></li>
      <li><a href="?l=5">5</a></li>
      <li><a href="?l=6">6</a></li>
      <li><a href="?l=7">7</a></li>
      <li><a href="?l=8">8</a></li>
      <li><a href="?l=9">9</a></li>
    </ul>
    <ul class="alphabet">
      <li><a href="?l=A">A</a></li>
      <li><a href="?l=B">B</a></li>
      <li><a href="?l=C">C</a></li>
      <li><a href="?l=D">D</a></li>
      <li><a href="?l=E">E</a></li>
      <li><a href="?l=F">F</a></li>
      <li><a href="?l=G">G</a></li>
      <li><a href="?l=H">H</a></li>
      <li><a href="?l=I">I</a></li>
      <li><a href="?l=J">J</a></li>
      <li><a href="?l=K">K</a></li>
      <li><a href="?l=L">L</a></li>
      <li><a href="?l=M">M</a></li>
      <li><a href="?l=N">N</a></li>
      <li><a href="?l=O">O</a></li>
      <li><a href="?l=P">P</a></li>
      <li><a href="?l=Q">Q</a></li>
      <li><a href="?l=R">R</a></li>
      <li><a href="?l=S">S</a></li>
      <li><a href="?l=T">T</a></li>
      <li><a href="?l=U">U</a></li>
      <li><a href="?l=U">V</a></li>
      <li><a href="?l=W">W</a></li>
      <li><a href="?l=W">X</a></li>
      <li><a href="?l=Y">Y</a></li>
      <li><a href="?l=Z">Z</a></li>
    </ul>
  </div>

  <div class="bg-white d-md-flex py-40 px-20 mb-20">
    <div class="w-100">
      <ul class="listcategory list-tags">
        <li v-for="item in items" class="hvr-icon-push"><i class="icon-fire color-red1 hvr-icon"></i><a :href="'/ofertas/'+item.slug">@{{ item.string }}</a></li>
      </ul>
    </div>
  </div>

  <div class="bg-white d-md-flex py-40 px-20 mb-20">
      <ul class="pagination-1">
        
        {{$q->links('vendor.pagination.custom')}}
      
      </ul>
  </div>

</div>