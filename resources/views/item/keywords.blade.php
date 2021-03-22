<div class="px-15 px-md-0">
  <div class="success my-25">
    <div class="icon-fire font-35 pr-10"></div>
    <div class="ml-5">
      <div class="font-bold">
        ¡No te vuelvas a perder un chollo como este!
      </div>
      <div>
        Si agregas las palabras clave a tus avisos te notifcaremos cuando existan chollos relacionados
      </div>
    </div>
  </div>
  <div class="overflow-auto overflow-md-visible">
    <div class="d-flex flex-md-wrap mb-20 m-n5">
      <div v-for="(item,index) of keywords">
        <div class="btn btn-small m-5" :class="{'btn-green':item.selected}" @click="addToKeywords(index)">
          <i v-show="!item.selected" class="fas fa-plus mr-1 font-15 mr-5"></i>
          <i v-show="item.selected" class="fas fa-check mr-1 font-15 mr-5"></i>
          <span>@{{ item.keyword }} </span>
        </div>
      </div>
    </div>
  </div>
</div>