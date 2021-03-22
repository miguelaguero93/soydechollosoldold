<div class="bg-white text-center rounded-lg p-5"> 
  <div class="d-flex align-items-center justify-content-between border-bottom px-10 py-5" style="position: relative">
    <div class="font-bold font-15">
      Los Top
    </div>
    <div style="cursor: pointer;" onclick="showTopsFilter()">
      <i class="icon-setting color-blue3 font-25"></i>
      <div class="filter" id="topfilter" style="right: 0">
        <form action="/filter" method="POST">
          @csrf
          <input type="hidden" name="topfilter" value="true">
          <input type="hidden" name="url" value="{{URL::current()}}">  
          <div class="select filter_item mb-10">
            <select name="period">
              <option value="1">Hoy</option>
              <option value="2">Ultima Semana</option>
              <option value="3">Ultimo Mes</option>
              <option value="4">De Siempre</option>
            </select>
          </div>
          <div class="pt-5">
            <button type="submit" class="w-100 btn" ><i class="fas fa-check font-16 pr-5"></i> Aplicar</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <div class="collapse">
    <div class="collapse-title">
      @if(!isset($filter[1]))
        Ultima Semana
      @else
        {{$filter[1]['name']}}
      @endif
    </div>
    <div class="collapse-content">
      <div v-for="(item,index) in top" class="mb-15">
        <div class="mb-15 p-10 image_realted">
          <a :href="cholloLink(item)"><img :src="item.image_small" :alt="item.name"></a>
        </div>

        <div class="d-flex align-items-center justify-content-center">
          <div class="font-13 text-through color-gray-600">
            @{{ item.regular_price | numberformat }}
          </div>
          <div class="font-17 color-orange3 pl-10 font-bold">
            @{{ item.price | numberformat }}
          </div>
        </div>

        <div class="font-bold line-13">
          <a :href="cholloLink(item)" style="color:#5c5c5c" class="link-none">@{{ namePreview(item.name,40) }}</a>
        </div>

        <div class="rating rating-inline mt-10">
          <div class="rating-icon" v-show="item.user_vote == null"  @click="voteTop(index,-1)">
            <i class="fas fa-minus-circle color-red1"></i>
          </div>
          <div class="rating-name">
            @{{ item.votes }}
          </div>
          <div class="rating-icon" v-show="item.user_vote == null" @click="voteTop(index,3)">
            <i class="fa fa-plus-circle color-green2"></i>
          </div>
          <div v-show="item.user_vote != null" class="vote_icon animate__animated animate__bounceInRight" >
            <i v-show="item.user_vote == -1" class="fa fa-minus-circle color-red1"></i>
            <i v-show="item.user_vote > 1" class="fa fa-plus-circle color-green2"></i>
          </div>
        </div>
      </div>

      <div class="text-center font-bold border-top pt-15 w-80 mx-auto">
        <a href="/populares">
          <span class="link cursor-pointer">Ver más</span>
        </a>
      </div>
    </div>
  </div>
</div>