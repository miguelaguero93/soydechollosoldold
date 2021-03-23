<div class="container py-20">
  <div class="mb-20 text-center">
    <div>
      <h1 class="font-bold font-18" style="color: grey;">Los mejores eventos y Sorteo para {{$month}} {{$year}}</h1>
    </div>
    <div>
      <h2 class="font-16" style="color: grey; font-family: sans-serif; font-weight: 100 !important;">Participa en todos estos eventos y sorteos gratis. ¡Mucha suerte!</h2>
    </div>
  </div>
  <div class="d-md-flex flex-row-reverse align-items-center justify-content-between mb-10">
    <div class="bg-white rounded-25 ml-sm-10 mb-10">
      <a href="/nuevo/evento" class="btn btn-small btn-green rounded-25 pl-20">
        <span class="mr-5">
          Enviar Sorteo/Evento
        </span>
        <i class="fas fa-arrow-right font-14"></i>
      </a>
    </div>

    <div class="d-flex flex-wrap align-items-center">
      <div class="mx-5 font-16 text-blue2 mb-10">
        Filtrar por:
      </div>
      
      <div class="ml-10 bg-white rounded-20 mb-10">
        <button @click="filter(1)"  class="btn btn-small" :class="{'btn-orange':filtered==1,'btn-orange-outline':filtered!=1}">Evento</button>
      </div>
      <div class="ml-10 bg-white rounded-20 mb-10">
        <button @click="filter(2)" class="btn btn-small" :class="{'btn-outline':filtered !== 2}">Sorteo</button>
      </div>
    </div>
  </div>
  
  <div class="row">
    <div v-for="item in items" v-show="item.visible" class="col-md-6 mb-30">
      <div class="bg-white rounded border overflow-hidden coupon-hover">
        <div class="position-relative">
          <a :href="item.url">
            <img :src="'/public/storage/'+item.imagen" alt="" class="w-100 d-block">
          </a>
          <div class="coupon-event bg-white rounded-20">
            <button v-if="item.type_id == 1" class="btn btn-small btn-orange">Evento</button>
            <button v-else class="btn btn-small">Sorteo</button>
          </div>
          <div class="coupon-date">
            @{{ getDates(item) }}
          </div>
        </div>
        <div class="px-20 pt-20 pb-15 event_title" onclick="addVisibleClass(this)">
          <div class="font-bold mb-5 font-16">
            @{{ item.name }} 
          </div>
          <div v-html="item.description" style="cursor: pointer"></div>
        </div>
      </div>
    </div>
  </div>
</div>
