@if(!isset($store_object) && isset($banner))
	<div class="banner">
	    <div class="container">
	      <div class="wrap">
			<a href="{{$banner->link}}">
	        	<img src="{{asset('/storage/'.$banner->image)}}" alt="{{$banner->name}}">
			</a>
	      </div>
	      <div class="icon"><i class="fas fa-times font-20" aria-hidden="true"></i></div>
	    </div>
	</div>
@else
	@include('includes.store_banner')
@endif