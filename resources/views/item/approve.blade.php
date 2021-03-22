@if(!is_null(Auth::user()) && (Auth::id() == $item->user_id || Auth::user()->role_id != 2))
	<div class="container">
		@if($item->approved == 0)
			<div class="mb-10 p-10 warning">
				Este chollo está pendiente de moderación.
			</div>
		@endif
		<div style="max-width: 200px; margin-left: auto;" class="pt-10 pb-10">
  		<a href="/editar/{{$item->id}}" class="btn btn-right pl-25">
  			<i class="fas fa-edit"></i>
  			<span class="name">
            	Editar 
        	</span> 
        </a>
		</div>
	</div>
@endif