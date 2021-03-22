<div class="container px-0 p-lx-15 pb-20">
	<div class="row m-0">
		<div class="col-12 col-md-3">
			<div class="bg-white m-10 px-30 px-md-30 py-20 py-md-20 rounded">
				<div class="stats_panel">
					<b>Perfil</b>
				</div>
			</div>
			<a href="/follows" class="link-none">
				<div class="bg-white m-10 px-30 px-md-30 py-20 py-md-20 rounded">
					<div class="stats_panel">
						Siguiendo
					</div>
				</div>
			</a>
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
			<div class="bg-white px-md-30 py-md-20 mb-10 rounded p-10 m-10">
				<div class="row">
					<div class="col-12 pb-30">
					Usuario desde: {{$user->created_at}}
					</div>
					<div class="col-12 col-md-2 p-30">
						<div>
							<img style="max-width: 100%; border-radius: 100%;" src="{{Auth::user()->avatar}}">
						</div>
						<br>
						<br>
						<span class="cursor-pointer pt-10" onclick="uploadOwnFile()"><i class="fas fa-edit"></i> Cambiar</span>
					</div>
					<div class="col-12 col-md-10 p-30">
						<label>Acerca de mi</label>
						<form method="post" action="/api/perfil">
							@csrf
							<textarea maxlength="512" name="about" required class="form-control input p-10" rows="4" placeholder="Escribe aquí una breve biografía o algo con lo que te identifiques.">{{$user->about}}</textarea>
							<div class="mt-10">
								<button type="submit" class="btn">Guardar</button>
							</div>
						</form>
					</div>
				</div>
			</div>

			<div class="bg-white px-md-30 py-md-20 mb-10 rounded p-10 m-10">
				<div class="row">
					<div class="col-12 pb-30">
						<form method="post" action="/password">
							<label> Actualizar Contraseña </label>
							@csrf
							<input class="form-control" required type="password" name="new_password" placeholder="nueva contraseña">
							<label> Confirmar Contraseña </label>
							<input class="form-control" required type="password" name="confirm_password" placeholder="confirmar contraseña">
							<div class="mt-10">
								<button type="submit" class="btn">Actualizar</button>
							</div>
						</form>	
					</div>
					
				</div>
			</div>

			<div class="bg-white px-md-30 py-md-20 mb-10 rounded p-10 m-10">
				<div class="row">
					<div class="col-12 pb-30">
						<form method="post" action="/email">
							<label> Actualizar Email </label>
							@csrf
							<br>
							<br>
							<p>Tu email actual es: {{Auth::user()->email}}</p>
							<input class="form-control" required type="text" maxlength="50" name="new_email" placeholder="nuevo email">
							<label> Confirmar email </label>
							<input class="form-control" required type="text" maxlength="50" name="confirm_email" placeholder="confirmar email">
							<div class="mt-10">
								<button type="submit" class="btn">Actualizar</button>
							</div>
						</form>	
					</div>
					
				</div>
			</div>

			<button class="btn btn-red ml-15" @click="toggleModal()"> ELIMINAR MI CUENTA</button>
		</div>
	</div>
</div>