<div class="container px-0 p-lx-15 pb-20">
	<div class="row m-0">
		<div class="col-12 col-md-3">
			<a href="/perfil" class="link-none">
			<div class="bg-white m-10 px-30 px-md-30 py-20 py-md-20 rounded">
				<div class="stats_panel">
					Perfil
				</div>
			</div>
			</a>
			<div class="bg-white m-10 px-30 px-md-30 py-20 py-md-20 rounded">
				<div class="stats_panel">
					<b>Siguiendo</b>
				</div>
			</div>
			<a href="/stats" class="link-none">
			<div class="bg-white m-10 px-30 px-md-30 py-20 py-md-20 rounded">
				<div class="stats_panel">
					Estadisticas
				</div>
			</div>
			</a>
			<a href="/settings" class="link-none">
			<div class="bg-white m-10 px-30 px-md-30 py-20 py-md-20 rounded">
				<div class="stats_panel">
					Notificaciones
				</div>
			</div>
			</a>
			<a href="/favoritos" class="link-none">
				<div class="bg-white m-10 px-30 px-md-30 py-20 py-md-20 rounded">
					<div class="stats_panel">
						Mis Favoritos
					</div>
				</div>
			</a>
			<a href="/enviados" class="link-none">
				<div class="bg-white m-10 px-30 px-md-30 py-20 py-md-20 rounded">
					<div class="stats_panel">
						Mis Chollos Enviados
					</div>
				</div>
			</a>
			<a href="/mis_cupones" class="link-none">
				<div class="bg-white m-10 px-30 px-md-30 py-20 py-md-20 rounded">
					<div class="stats_panel">
						Mis Cupones Enviados
					</div>
				</div>
			</a>
		</div>
		<div class="col-12 col-md-9">
				

			<div class="row" v-if="followers.length">
				<div v-for="(user,index) in followers" class="col-12 col-md-6">
					<div class="bg-white px-md-30 py-md-20 mb-10 rounded p-10 m-10 row">
						<div class="col-3">
							<div style="border-radius: 100%; overflow: hidden"><a :href="userLink(user)"><img :src="user.avatar" class="w-100"></a></div>
						</div>
						<div class="col-9 text-center">
							<div><a :href="userLink(user)"><b>@{{ user.name }}</b></a></div>
							<p>@{{ namePreview(user.about,50) }}</p>
							<br> 
							<button class="btn w-100 mt-10" @click="followUser(user.id,0,index)"><i class="fas fa-bell mr-10"></i> Siguiendo</button>
						</div>
					</div>
				</div>
			</div>
			<div v-else class="text-center mt-20 w-100">
		      <h3>No estas siguiendo a nadie :(</h3>
		  	</div>
		

		</div>
	</div>
</div>