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
			<a href="/stats" class="link-none">
			<div class="bg-white m-10 px-30 px-md-30 py-20 py-md-20 rounded">
				<div class="stats_panel">
					Estadisticas
				</div>
			</div>
			</a>
			<div class="bg-white m-10 px-30 px-md-30 py-20 py-md-20 rounded">
				<div class="stats_panel">
					<b>Notificaciones</b>
				</div>
			</div>
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
			<div class="bg-white px-md-30 py-md-20 rounded m-10 p-10">
				<div class="row">
					<div class="col-12 col-lg-6">Notificación de productos nuevos con mis palabras clave (AvisadorPRO).</div>
					<div class="col-12 col-lg-3 text-center">
						<span><i class="fas fa-bell"></i> En plataforma</span>
						<br>
						<label class="switch mt-10"><input type="checkbox" v-model="settings[1].system" @click="update()"> <span class="switch-mark switch-mark-round"></span></label>
					</div>
					<div class="col-12 col-lg-3 text-center">
						<span><i class="fas fa-envelope"></i> Al correo</span>
						<br>
						<label class="switch mt-10"><input type="checkbox" v-model="settings[1].email" @click="update()"> <span class="switch-mark switch-mark-round"></span></label>
					</div>
				</div>
				<div class="row mt-20">
					<div class="col-12 col-lg-6">Notificación de comentarios nuevos en mi oferta.</div>
					<div class="col-12 col-lg-3 text-center">
						<span><i class="fas fa-bell"></i> En plataforma</span>
						<br>
						<label class="switch mt-10"><input type="checkbox" v-model="settings[2].system" @click="update()"> <span class="switch-mark switch-mark-round"></span></label>
					</div>
					<div class="col-12 col-lg-3 text-center">
						<span><i class="fas fa-envelope"></i> Al correo</span>
						<br>
						<label class="switch mt-10"><input type="checkbox" v-model="settings[2].email" @click="update()"> <span class="switch-mark switch-mark-round"></span></label>
					</div>
				</div>
				<div class="row mt-20">
					<div class="col-12 col-lg-6">Notificación de respuesta a un comentario mío.</div>
					<div class="col-12 col-lg-3 text-center">
						<span><i class="fas fa-bell"></i> En plataforma</span>
						<br>
						<label class="switch mt-10"><input type="checkbox" v-model="settings[3].system" @click="update()"> <span class="switch-mark switch-mark-round"></span></label>
					</div>
					<div class="col-12 col-lg-3 text-center">
						<span><i class="fas fa-envelope"></i> Al correo</span>
						<br>
						<label class="switch mt-10"><input type="checkbox" v-model="settings[3].email" @click="update()"> <span class="switch-mark switch-mark-round"></span></label>
					</div>
				</div>
				<div class="row mt-20">
					<div class="col-12 col-lg-6">Notificación cuando recibo una medalla nueva.</div>
					<div class="col-12 col-lg-3 text-center">
						<span><i class="fas fa-bell"></i> En plataforma</span>
						<br>
						<label class="switch mt-10"><input type="checkbox" v-model="settings[4].system" @click="update()"> <span class="switch-mark switch-mark-round"></span></label>
					</div>
					<div class="col-12 col-lg-3 text-center">
						<span><i class="fas fa-envelope"></i> Al correo</span>
						<br>
						<label class="switch mt-10"><input type="checkbox" v-model="settings[4].email" @click="update()"> <span class="switch-mark switch-mark-round"></span></label>
					</div>
				</div>
				<div class="row mt-20">
					<div class="col-12 col-lg-6">Notificación cuando recibo chollocoins.</div>
					<div class="col-12 col-lg-3 text-center">
						<span><i class="fas fa-bell"></i> En plataforma</span>
						<br>
						<label class="switch mt-10"><input type="checkbox" v-model="settings[5].system" @click="update()"> <span class="switch-mark switch-mark-round"></span></label>
					</div>
					<div class="col-12 col-lg-3 text-center">
						<span><i class="fas fa-envelope"></i> Al correo</span>
						<br>
						<label class="switch mt-10"><input type="checkbox" v-model="settings[5].email" @click="update()"> <span class="switch-mark switch-mark-round"></span></label>
					</div>
				</div>
				<div class="row mt-20">
					<div class="col-12 col-lg-6">Notificación cuando un usuario al que sigo sube un chollo.</div>
					<div class="col-12 col-lg-3 text-center">
						<span><i class="fas fa-bell"></i> En plataforma</span>
						<br>
						<label class="switch mt-10"><input type="checkbox" v-model="settings[6].system" @click="update()"> <span class="switch-mark switch-mark-round"></span></label>
					</div>
					<div class="col-12 col-lg-3 text-center">
						<span><i class="fas fa-envelope"></i> Al correo</span>
						<br>
						<label class="switch mt-10"><input type="checkbox" v-model="settings[6].email" @click="update()"> <span class="switch-mark switch-mark-round"></span></label>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>