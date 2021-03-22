@extends('layouts.main')
@section('content')
	@include('includes.banner')
  	@include('includes.navigation')
	<div v-cloak class="container px-0 p-lx-15">
	  <div class="d-xl-flex justify-content-between">
	  	<div class="d-none d-xl-block w-px-lg-250 ml-xl-15 mb-100">
		  <div class="bg-white px-30 py-20 rounded mr-10">
				<div class="mb-30">
					<div class="row">
						<div class="col-4">
							<div style="border-radius: 100%; overflow: hidden"><img :src="user.avatar" class="w-100"></div>
						</div>
						<div class="col-8" style="display: flex; align-items: center; font-size: 1.5rem; font-weight: 800; text-align: center;">
							<div>@{{ user.name }}</div> 
						</div>
					</div>
					@if($id != $logged)
						<button v-if="follows == 0" class="btn w-100 mt-10" @click="followUser(1)"><i class="fas fa-bell mr-10"></i> Seguir usuario</button>
						<button v-else class="btn w-100 mt-10" @click="followUser(0)"><i class="fas fa-bell mr-10"></i> Siguiendo</button>
					@endif
				</div>
				<h4><i class="fas fa-chart-bar"></i> Estadisticas</h4>
				<div class="stats_panel">
					<label class="pb-8 pt-15">Publicaciones</label>
					<div> <i class="fas fa-tag"></i> <span><b>{{ $stats[0] }}</b></span> Ofertas </div>
					<div> <i class="fas fa-ticket-alt"></i> <span><b>{{ $stats[1] }}</b></span> Cupones </div>
					<div> <i class="fas fa-heart"></i> <span><b>{{ $stats[2] }}</b></span> Favoritos </div>
					<div> <i class="fas fa-hashtag"></i> <span><b>{{ $stats[3] }}</b></span> Palabras en AvisadorPRO </div>
					<label class="pb-8 pt-15">Tendencias</label>
					<div> <i class="icon-fire"></i> <span><b>{{ $stats[4] }}°</b></span> Más votado </div>
					<div> <i class="fas fa-chart-bar"></i> <span><b>{{ $stats[5] }}°</b></span> Promedio </div>
					<div> <i class="far fa-money-bill-alt"></i> <span><b>{{ $stats[6] }}</b></span> Premios canjeados </div>
					<label class="pb-8 pt-15">Comunidad</label>
					<div> <i class="fas fa-bell"></i> <span><b>{{ $stats[7] }}</b></span> Seguidores </div>
					<div> <i class="fas fa-bell"></i> <span><b>{{ $stats[8] }}</b></span> Siguiendo </div>
					<div> <i class="fas fa-comment"></i> <span><b>{{ $stats[9] }}</b></span> Comentarios </div>
					<div> <i class="fas fa-thumbs-up"></i> <span><b>{{ $stats[10] }}</b></span> Likes </div>
					<div> <i class="fas fa-plus"></i> <span><b>{{ $stats[11] }}</b></span> Votos </div>
				</div>
			</div>
		</div>
	    @include('index.items')
	  </div>
	</div>
@endsection
@section('scripts')
@include('common.js.helpers')
<script type="text/javascript">
	var sidebarMixin = {
		data: {
			id:{{ $id }}, 
			follows:{{ $follows }},
			logged:{{ $logged }},
			user:{!! $user !!}
		},
		methods: {
			followUser(action){
				if (this.logged == 0) {
					return triggerLoginModal()
				}

				let payload = {
					id:this.id,
					action:action
				}		
				axios.post('/api/user/follow',payload)

				this.follows = action
				if (action == 1) {
					snackSuccess('Estas siguiendo a '+this.user.name)
				}else{
					snackSuccess('Ya no estas siguiendo a '+this.user.name)
				}
			}
		}
	}
</script>
@include('common.js.favorite_mixin')
@include('index.js.index')
@include('index.js.banners')
@endsection