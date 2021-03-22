<div v-cloak class="container px-0 p-lx-15 pb-20">
	<div class="row m-0">
		<div class="col-12 col-md-3 d-none d-md-block">
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
		<div class="col-12 col-md-9 p-0">
			
			<div class="w-100" style="overflow: auto;">
		      <div class="navigation_mobile rounded-5 mb-10">
		        <a href="/estadisticas/{{$user->id}}/{{nicename($user->name)}}" class="navigation_mobile_item d-md-none">
		          <div class="pb-10 pt-15 px-15">
		            ESTADISTICAS
		          </div>
		        </a>
		        <a href="/medallas/{{$user->id}}/{{nicename($user->name)}}" class="navigation_mobile_item">
		          <div class="pb-10 pt-15 px-15">
		            MEDALLAS
		          </div>
		        </a>
		        <a href="/seguidores/{{$user->id}}/{{nicename($user->name)}}"  class="navigation_mobile_item">
		          <div class="pb-10 pt-15 px-15">
		            SEGUIDORES
		          </div>
		        </a>
		        <a href="#" class="navigation_mobile_item selected">
		          <div class="pb-10 pt-15 px-15">
		            SIGUIENDO
		          </div>
		        </a>  
		        <a href="/chollos/{{$user->id}}/{{nicename($user->name)}}" class="navigation_mobile_item">
		          <div class="pb-10 pt-15 px-15">
		            CHOLLOS PUBLICADOS
		          </div>
		        </a>
		      </div>
		    </div>


			<div v-if="items.length"  class="row m-0">
				<div v-for="(it,index) in items" class="col-12 col-md-6">
					<a :href="'/chollos/'+it.id+'/'+it.name" class="no_link">
						<div class="bg-white px-md-30 py-md-20 mb-10 rounded p-10 m-10 row hoverable">
							<div class="col-3">
								<div style="border-radius: 100%; overflow: hidden"><img :src="it.avatar" class="w-100"></div>
							</div>
							<div class="col-9 text-center">
								<div><b>@{{ it.name }}</b></div>
								<p>@{{ namePreview(it.about,50) }}</p>
								<br> 
							</div>
						</div>
					</a>
				</div>
			</div>

			<div v-else class="text-center mt-20 w-100">
		      <h3>Ningún seguidor aquí :(</h3>
		  	</div>

		</div>
	</div>
</div>