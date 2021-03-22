<div class="bg-gray-200 mb-15 d-none d-xl-block">
  <div class="container">
    <div class="font-bold font-13 pt-6 pb-4 px-15 pl-lg-50 pr-lg-25 d-flx align-items-center">
      @foreach($breadcrumbs as $key => $b)
      	@if($key != sizeof($breadcrumbs)-1)
      		<a href="{{$b['url']}}" class="no_link"><span class="color-blue3">{{$b['name']}}</span></a> 
      		<i class="fas fa-angle-right ml-15 mr-5 color-gray-900"></i> 
      	@else	
      		<span class="color-gray-900">{{$b['name']}}</span> 
      	@endif
      @endforeach
    </div>
  </div>
</div>