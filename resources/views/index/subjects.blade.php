<div class="bg-white text-center rounded-lg p-5 mt-10">
  <div class="d-flex align-items-center justify-content-between border-bottom px-10 py-5 mt-5" style="position: relative">
    <div class="font-bold font-15">
      Comentarios recientes
    </div>
    <div style="cursor: pointer;" onclick="showCommFilter()">
      <i class="icon-setting color-blue3 font-25"></i>
      <div class="filter" id="commfilter" style="right: 0">
        <form action="/filter" method="POST">
          @csrf
          <input type="hidden" name="commfilter" value="true">
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
      @if(!isset($filter[2]))
        Ultima Semana
      @else
        {{$filter[2]['name']}}
      @endif
    </div>         
    <div class="collapse-content">
      <div class="comments-sidebar" :class="{show_all:commentsAll}">
        <div v-for="item in comments" class="text-left">
          <div style="display: flex;">
            <div class="item_avatar">
              <img width="40px" :src="item.user.avatar">
            </div> 
            <div class="ml-5 ml-lg-10 line-11">
              <div class="color-blue1">
                <span class="font-bold font-lg-15">
                  @{{ item.user.name }} 
                </span> 
              </div> 
              <div class="font-12 fontlg--13 color-gray-990 pt-1">En <a class="color-gray-990" :href="cholloLinkComments(item.chollo)"><b>@{{ nameCommentPreview(item,30) }}</b></a></div>
            </div>
          </div>
          <div class="comment-side-bar p-3 pb-4 overflow-description mb-30" style="margin-bottom: 18px">
             <a :href="cholloLinkComments(item.chollo)" class="link-none color-gray-990">
              @{{ descriptionPreview(item.value) }}
             </a> 
          </div>
        </div>
      </div>
      <div class="font-bold mb-10 font-15 pt-15" v-show="!commentsAll">
        <span class="color-blue1 link link-none cursor-pointer" @click="showAllComments()">
          Ver más
        </span>
      </div>   
    </div>
  </div>
</div>

