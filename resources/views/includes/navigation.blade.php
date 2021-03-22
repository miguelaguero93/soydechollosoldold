@if(isset($show_bar))
<div class="navigation py-10 bg-white d-none d-md-flex">
  <div class="container">
    <div style="position: relative;" class="d-flex align-items-center justify-content-between pl-xl-50 pr-xl-25">
      <div class="d-none d-lg-flex bg-blue2 bg-blue2-hover color-white align-items-center rounded-3 pl-15 pr-25 py-2 cursor-pointer pt-8 pb-8" onclick="showFilter()"><i class="fas fa-sliders-h color-orange2 font-20"></i>
        <span class="pl-10 pt-3">Filtrar</span>
          @if(isset($filter[0]) && count($filter[0]) > 0) <span class="filter_count">{{count($filter[0])}}</span> @endif
      </div>
      <div class="filter" id="filter">
        <form action="/filter" method="POST" id="filter_form">
          @csrf
          <input type="hidden" name="url" value="{{URL::current()}}">  
          @if(isset($with_period) && $with_period === true)
            <div class="select filter_item mb-10">
              <select name="period" id=period_filter>
                <option value="1" @if(isset($filter[0]['period']) && $filter[0]['period'] == 1) selected @endif>Hoy</option>
                <option value="2" @if((isset($filter[0]['period']) && $filter[0]['period'] == 2) || !isset($filter[0]['period'])) selected @endif>Ultima Semana</option>
                <option value="3" @if(isset($filter[0]['period']) && $filter[0]['period'] == 3) selected @endif>Ultimo Mes</option>
                <option value="4" @if(isset($filter[0]['period']) && $filter[0]['period'] == 4) selected @endif>De Siempre</option>
              </select>
            </div>
          @endif
          <div class="filter_item pb-10 mb-10">
            <label class="custom_checkbox"> Ocultar agotados
              <input type="checkbox" name="hide_expired" id="hide_expired_filter_box" @if(isset($filter[0]['hide_expired'])) checked @endif>
              <span class="checkmark"></span>
            </label> 
          </div>
          <div class="filter_item pb-10 mb-10">
            <label class="custom_checkbox"> Envío desde España
              <input type="checkbox" name="from_spain" @if(isset($filter[0]['from_spain'])) checked @endif>
              <span class="checkmark"></span>
            </label> 
          </div>
          <div class="pt-5">
            <button type="submit" class="w-100 btn" ><i class="fas fa-check font-16 pr-5"></i> Aplicar</button>
          </div>
        </form>
      </div>
      <div class="d-flex w-100 px-20 px-lg-80 justify-content-between">
        <div class="d-flex align-items-center justify-content-between w-100 pr-xl-80">
          <div class="text-nowrap">
            Ordenar por:
          </div>
          @if(isset($store) && $store != 0)
            <div class="ml-25 nav_link">
              <a href="/tienda/{{$store_object->slug}}/populares" class="d-flex align-items-center color-gray-500 link-none">
                <img width="26" src="{{asset('/public/images/svgs/medal.svg')}}">
                <span class="ml-10 font-16">Populares</span>
              </a>
            </div>
            <div class="ml-25 nav_link">
              <a href="/tienda/{{$store_object->slug}}/nuevos" class="d-flex align-items-center color-gray-500 link-none">
                <img width="26" src="{{asset('/public/images/svgs/new.svg')}}">
                <span class="ml-10 font-16">Nuevos</span>
              </a>
            </div>
            <div class="ml-25 nav_link">
              <a href="/tienda/{{$store_object->slug}}/comentados" class="d-flex align-items-center color-gray-500 link-none">
                <img width="26" src="{{asset('/public/images/svgs/comment.svg')}}">
                <span class="ml-10 font-16">Comentados</span>
              </a>
            </div>
          @elseif(isset($category_id) && $category_id >0)
            <div class="ml-25 nav_link">
              <a href="/categoria/{{$store_object->slug}}/populares" class="d-flex align-items-center color-gray-500 link-none">
                <img width="26" src="{{asset('/public/images/svgs/medal.svg')}}">
                <span class="ml-10 font-16">Populares</span>
              </a>
            </div>
            <div class="ml-25 nav_link">
              <a href="/categoria/{{$store_object->slug}}/nuevos" class="d-flex align-items-center color-gray-500 link-none">
                <img width="26" src="{{asset('/public/images/svgs/new.svg')}}">
                <span class="ml-10 font-16">Nuevos</span>
              </a>
            </div>
            <div class="ml-25 nav_link">
              <a href="/categoria/{{$store_object->slug}}/comentados" class="d-flex align-items-center color-gray-500 link-none">
                <img width="26" src="{{asset('/public/images/svgs/comment.svg')}}">
                <span class="ml-10 font-16">Comentados</span>
              </a>
            </div>
          @else
            <div class="ml-25 nav_link">
              <a href="/populares" class="d-flex align-items-center color-gray-500 link-none">
                <img width="26" src="{{asset('/public/images/svgs/medal.svg')}}">
                <span class="ml-10 font-16">Populares</span>
              </a>
            </div>
            <div class="ml-25 nav_link">
              <a href="/nuevos" class="d-flex align-items-center color-gray-500 link-none">
                <img width="26" src="{{asset('/public/images/svgs/new.svg')}}">
                <span class="ml-10 font-16">Nuevos</span>
              </a>
            </div>
            <div class="ml-25 nav_link">
              <a href="/comentados" class="d-flex align-items-center color-gray-500 link-none">
                <img width="26" src="{{asset('/public/images/svgs/comment.svg')}}">
                <span class="ml-10 font-16">Comentados</span>
              </a>
            </div>
            
          @endif
        </div>
        <div class="ml-20">
          <a href="/avisador" class="btn btn-green btn-small">
            ¡Avisador PRO!
          </a>
        </div>
      </div>
      <div class="option">
        @if(!Cookie::has('display')) 
          <a href="/api/setdisplay?d=vertical" class="link-none">
            <div class="option-item">
              <i class="icon-square-out"></i>
            </div>
          </a>
          <a href="/api/setdisplay?d=horizontal" class="link-none">
            <div class="option-item">
              <i class="icon-list"></i>
            </div>
          </a>
        @else 
          <a href="/api/setdisplay?d=vertical" class="link-none">
            <div class="option-item">
              <i class="icon-square"></i>
            </div>
          </a>
          <a href="/api/setdisplay?d=horizontal" class="link-none">
            <div class="option-item">
              <i class="icon-list-out"></i>
            </div>
          </a>
        @endif
      </div>
    </div>
  </div>
</div>
<div class="w-100" style="overflow: auto;">
  <div class="navigation_mobile d-md-none" style="min-width: auto">
    @if(isset($store) && $store != 0)
    <a href="/tienda/{{$store_object->slug}}/populares" class="navigation_mobile_item @if(Request::segment(1) == 'populares') selected @endif">
      <div class="pb-10 pt-15 px-15">
        POPULARES
      </div>
    </a>
    <a href="/tienda/{{$store_object->slug}}/nuevos" class="navigation_mobile_item  @if(Request::segment(1) == 'nuevos') selected @endif">
      <div class="pb-10 pt-15 px-15">
        NUEVOS
      </div>
    </a>
    <a href="/tienda/{{$store_object->slug}}/comentados" class="navigation_mobile_item @if(Request::segment(1) == 'comentados') selected @endif">
      <div class="pb-10 pt-15 px-15">
        COMENTADOS
      </div>
    </a>
    @elseif(isset($category_id) && $category_id >0) 
    <a href="/categoria/{{$store_object->slug}}/populares" class="navigation_mobile_item @if(Request::segment(1) == 'populares') selected @endif">
      <div class="pb-10 pt-15 px-15">
        POPULARES
      </div>
    </a>
    <a href="/categoria/{{$store_object->slug}}/nuevos" class="navigation_mobile_item  @if(Request::segment(1) == 'nuevos') selected @endif">
      <div class="pb-10 pt-15 px-15">
        NUEVOS
      </div>
    </a>
    <a href="/categoria/{{$store_object->slug}}/comentados" class="navigation_mobile_item @if(Request::segment(1) == 'comentados') selected @endif">
      <div class="pb-10 pt-15 px-15">
        COMENTADOS
      </div>
    </a>
    @else
    <a href="/populares" class="navigation_mobile_item @if(Request::segment(1) == 'populares') selected @endif">
      <div class="pb-10 pt-15 px-15">
        POPULARES
      </div>
    </a>
    <a href="/nuevos" class="navigation_mobile_item  @if(Request::segment(1) == 'nuevos') selected @endif">
      <div class="pb-10 pt-15 px-15">
        NUEVOS
      </div>
    </a>
    <a href="/comentados" class="navigation_mobile_item @if(Request::segment(1) == 'comentados') selected @endif">
      <div class="pb-10 pt-15 px-15">
        COMENTADOS
      </div>
    </a>
    @endif


  </div>
</div>
@endif
@if(isset($with_period))
<div class="filter_mobile d-md-none" style="border-bottom: 1px solid #d8d8d8">
  <div class="select filter_item mb-10" style="border-radius: 0px;border: none; margin: 0 !important;background: #fafafa;">
    <select name="period" id="filter_mobile">
      <option value="1" @if(isset($filter[0]['period']) && $filter[0]['period'] == 1) selected @endif>Hoy</option>
      <option value="2" @if((isset($filter[0]['period']) && $filter[0]['period'] == 2) || !isset($filter[0]['period'])) selected @endif>Ultima Semana</option>
      <option value="3" @if(isset($filter[0]['period']) && $filter[0]['period'] == 3) selected @endif>Ultimo Mes</option>
      <option value="4" @if(isset($filter[0]['period']) && $filter[0]['period'] == 4) selected @endif>De Siempre</option>
    </select>
  </div>
</div>
@endif
@include('includes.breadcrumbs')