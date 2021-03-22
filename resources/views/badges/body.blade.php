<div v-cloak class="container px-0 p-lx-15 pb-20">
	<div class="row">
		<div class="col-12 col-md-3">
			<div class="bg-white px-30 py-20 rounded">
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
		<div class="col-12 col-md-9">
			
			<div class="w-100" style="overflow: auto;">
		      <div class="navigation_mobile rounded-5 mb-10" style="min-width: 550px">
		        <a href="/chollos/{{$user->id}}/{{nicename($user->name)}}" class="navigation_mobile_item">
		          <div class="pb-10 pt-15 px-15">
		            CHOLLOS PUBLICADOS
		          </div>
		        </a>
		        <a href="/medallas/{{$user->id}}/{{nicename($user->name)}}" class="navigation_mobile_item selected">
		          <div class="pb-10 pt-15 px-15">
		            MEDALLAS
		          </div>
		        </a>
		        <a href="/#" class="navigation_mobile_item selected">
		          <div class="pb-10 pt-15 px-15">
		            SEGUIDORES
		          </div>
		        </a>
		        <a href="/comentados" class="navigation_mobile_item comentados">
		          <div class="pb-10 pt-15 px-15">
		            SIGUIENDO
		          </div>
		        </a>  
		      </div>
		    </div>

			<div class="bg-white px-30 py-20 rounded">
				
			</div>
		</div>
	</div>
</div>