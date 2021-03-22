@if(isset($store_object))
<div style="background-color: #0038aa;">
	<div class="container store_banner row">
		@if(isset($store) && $store > 0)
			@php
				$link = '/tienda/'.$store_object->slug
			@endphp
		@elseif(isset($category_id) && $category_id > 0)
			@php
				$link = '/categoria/'.$store_object->slug
			@endphp
		@else
			@php
				$link = ''
			@endphp
		@endif
		
		<div class="col-12 col-md-12">
			<div class="store_description pt-15">
				<a href="{{$link}}">
					<h1 class="text-white">{{ucfirst($store_object['name'])}}</h1>
				</a>
				<div id="read_more">
					<h2> {!! $store_object['description'] !!} 
						@if(strlen($store_object['details']) > 0)
							<span id="show_more" style="cursor: pointer; text-decoration: underline" onclick="showStoreDetails()">
								<b>Leer más</b>
							</span>
							<span id="show_less" style="cursor: pointer; text-decoration: underline; display: none;" onclick="hideStoreDetails()">
								<b>Esconder detalles</b>
							</span>
						@endif
					</h2> 
				</div>
			</div>
		</div>
	</div>
</div>
<div id="details_container" class="container pt-30 store_details">
	{!! $store_object['details'] !!}
</div>
@endif
