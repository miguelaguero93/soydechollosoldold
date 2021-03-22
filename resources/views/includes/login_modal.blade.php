<div id="loginModal" class="modal" onclick="closeModals(event)">
  <div class="modal-content animate__animated animate__fadeIn" >
    <span class="close" onclick="closeModals(event)">&times;</span>
    <div class="row" style="margin: 0">
    	<div class="col-12 col-md-6" style="padding: 20px">
    		<h4>Inicia sesión con usuario y contraseña</h4>
    		<div class="mt-20">
    			<div>
	    			<h2 class="font-19 color-blue2">Nombre de usuario o email</h2>
	    			<div class="input">
	    				<input type="text" v-model="username" class="form-control w-100">
	    			</div>
    			</div>
    			<div class="pt-15 pb-5">
	    			<h2 class="font-19 color-blue2">Contraseña</h2>
	    			<div class="input">
	    				<input type="password" class="form-control w-100" v-model="password">
	    			</div>
    			</div>
				<label class="custom_checkbox">Recordarme
                  <input type="checkbox" v-model="remember_me">
                  <span class="checkmark"></span>
                </label>
    			<div class="form-group pt-5">
    				<a href="/"><b>Olvidaste tu contraseña?</b></a>
    			</div>
    			<button class="btn btn-blue mt-20" @click="loginUser()">
		            <span class="ml-5 pt-4">
		              Iniciar Sesion
		            </span>
		        </button>
    		</div>
    	</div>
    	<div class="col-12 col-md-6 text-center" style="display: flex; align-items: center; background:#E3F2FD;padding: 15px">
            <div class="w-100">                
                <h4>Inicia Sesión usando tu red social favorita.</h4>
                <button class="btn-block btn-social btn-lg btn-facebook" onclick="logWithFacebook()"><i class="fab fa-facebook"></i> Entrar con Facebook</button>
                <div class="text-center pt-10">
                    <div id="my-signin2">Entrar con Google</div>
                </div>
                <hr>
                <div class="pt-20">
                    <button class="btn-social btn-lg btn-white" onclick="triggerRegisterModal()" style="border: 1px solid #0038aa;"><i class="fa fa-user"></i> Registrarse con Email</button>
                </div>
            </div>

        </div>
    </div>
  </div>
</div>