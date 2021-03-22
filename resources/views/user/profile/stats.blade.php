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
			<a href="/follows" class="link-none">
				<div class="bg-white m-10 px-30 px-md-30 py-20 py-md-20 rounded">
					<div class="stats_panel">
						Siguiendo
					</div>
				</div>
			</a>
			<div class="bg-white m-10 px-30 px-md-30 py-20 py-md-20 rounded">
				<div class="stats_panel">
					<b>Estadisticas</b>
				</div>
			</div>
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
			<div class="bg-white m-10 mb-10 p-10 px-md-30 py-md-20 rounded">
				<div class="row">
					<div class="col-12 stats_panel">
						<label class="pb-8 pt-15">Publicaciones</label>
						<div> <i class="fas fa-tag"></i> <span><b>{{ $chollos }}</b></span> Ofertas </div>
						<div> <i class="fas fa-ticket-alt"></i> <span><b>{{ $coupons }}</b></span> Cupones </div>
						<div> <i class="fas fa-heart"></i> <span><b>{{ $favorites }}</b></span> Favoritos </div>
						<div> <i class="fas fa-hashtag"></i> <span><b>{{ $keywords }}</b></span> Palabras en AvisadorPRO </div>
						<label class="pb-8 pt-15">Tendencias</label>
						<div> <i class="icon-fire"></i> <span><b>{{ $highest }}°</b></span> Más votado </div>
						<div> <i class="fas fa-chart-bar"></i> <span><b>{{ $average }}°</b></span> Promedio </div>
						<div> <i class="far fa-money-bill-alt"></i> <span><b>{{ $prizes }}</b></span> Premios canjeados </div>
						<label class="pb-8 pt-15">Comunidad</label>
						<div> <i class="fas fa-comment"></i> <span><b>{{ $comments }}</b></span> Comentarios </div>
						<div> <i class="fas fa-thumbs-up"></i> <span><b>{{ $likes }}</b></span> Likes </div>
						<div> <i class="fas fa-plus"></i> <span><b>{{ $votes }}</b></span> Votos </div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>