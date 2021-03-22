<div class="container py-20">
  <div class="row justify-content-center">
    <div class="col-lg-12">
      <div class="mb-15 text-center">
        <div class="font-bold font-18">
          Tus alertas
        </div>
        <div class="font-16">
          Te avisaremos cada vez que una oferta contenga tus <b>palabras claves</b>  
        </div>
        
        <div class="d-sm-flex justify-content-center mb-30 mt-5">
          <div class="d-flex align-items-center w-100 w-max-px-sm-400">
            <form class="w-100" onsubmit="showBlanket()">
              <div class="search">
                  <input type="text" name="keyword" placeholder="Agregar alerta" class="h-px-45 no_autofield">
                  <button >
                    <i class="fas fa-plus font-20 color-blue4"></i>
                  </button>
              </div>
            </form>
          </div>
        </div>

        <div v-cloak class="d-flex flex-md-wrap mb-20 m-n5 mt-30">
            <div v-for="(item,index) of keywords">
              <div class="btn btn-small m-5" :class="{'btn-green':item.selected}" @click="addToKeywords(index)">
              <i v-show="!item.selected" class="fas fa-plus mr-1 pr-5 font-16"></i>
              <i v-show="item.selected" class="fas fa-check mr-1 pr-5 font-16"></i>
              <span>@{{ item.keyword }} </span>          
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>