<div class="slider mb-25 bg-white rounded">
  <div class="cycle-slideshow" data-cycle-fx="scrollHorz" data-cycle-slides=".slider-item" data-cycle-timeout="0" data-cycle-prev="#prev" data-cycle-next="#next">
    
    <div class="slider-item" style="height: initial;">
      <div class="row no-gutters py-20 p-md-20">
        
        @foreach($popular1 as $item)
            <div class="col-4 col-md-2 my-15 px-5 px-md-10">
              <div class="border hover-shadow" style="height: 100%; display: flex; vertical-align: middle; align-items: center; padding: 10px;">
                @if($cupons == 0)
                  <a href="/tienda/{{$item->slug}}" class="no_link" style="margin: auto">
                    <div class="text-center">
                      @if(!is_null($item->image))
                        <img src="{{asset('storage/'.$item->image)}}" class="d-block w-100" alt="">
                      @else
                        <h3>{{$item->visible_name}}</h3>
                      @endif
                    </div>
                  </a>
                @else

                  <a href="/codigos-descuento/{{$item->slug}}" class="no_link" style="margin: auto">
                    <div class="text-center">
                      @if(!is_null($item->image))
                        <img src="{{'storage/'.$item->image}}" class="d-block w-100" alt="">
                      @else
                        <h3>{{$item->visible_name}}</h3>
                      @endif
                    </div>
                  </a>

                @endif
              </div>
            </div>
        @endforeach
      
      </div>
    </div>

    
  </div>
</div>