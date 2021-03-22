<div id="registerModal" class="modal" onclick="closeModals(event)">
  <div class="modal-content animate__animated animate__fadeIn">
    <span class="close" onclick="closeModals(event)">&times;</span>
    <div class="row" style="margin: 0">
    	<div class="col-12 col-md-6" style="padding: 20px">
	    		<h4>Tus datos</h4>
    			<div>
	    			<h2 class="font-19 color-blue2">Nombre De Usuario</h2>
	    			<div class="input mb-8">
	    				<input type="text" class="form-control w-100" v-model="username" maxlength="15">
	    			</div>
	    			<small>una sola palabra</small>
    			</div>
    			<span v-if="nameError" class="pill pill-orange mt-2">
	              <small>
	                <i class="fa fa-info-circle"></i> Ingresa un nombre de usuario de una sola palabra. 
	              </small>
	            </span>
    			<div class="pt-15 pb-5">
	    			<h2 class="font-19 color-blue2">Email</h2>
	    			<div class="input mb-8">
	    				<input type="email" id="email" class="form-control w-100" name="email" v-model="email" maxlength="100">
	    			</div>
    				<span v-if="emailError" class="pill pill-orange mt-2">
		              <small>
		                <i class="fa fa-info-circle"></i> Ingresa un email valido. 
		              </small>
		            </span>
    			</div>
    			<div class="pt-15 pb-5">
	    			<h2 class="font-19 color-blue2">Contraseña</h2>
	    			<div class="input mb-8" maxlength="25">
	    				<input type="password" id="password" class="form-control w-100" name="password" v-model="password">
	    			</div>
	    			<span v-if="passwordError" class="pill pill-orange mt-2">
		              <small>
		                <i class="fa fa-info-circle"></i> Ingresa una contraseña de al menos 5 caracteres 
		              </small>
		            </span>
    			</div>	
    			<div>
					<label class="custom_checkbox">Quiero un resume de los chollos a mi email
	                  <input type="checkbox" v-model="subscribed">
	                  <span class="checkmark"></span>
	                </label>
    			</div>
    			<div class="pt-15">
	                <label class="custom_checkbox">He leído y acepto el <a href="/politicas">Condiciones de Uso y la Política de Privacidad</a>
	                  <input type="checkbox" v-model="terms">
	                  <span class="checkmark"></span>
	                </label>
	                <span v-if="termsError" class="pill pill-orange mt-2">
		              <small>
		                <i class="fa fa-info-circle"></i> Debes aceptar lor terminos y condiciones 
		              </small>
		            </span>
    			</div>
    			<button type="button" class="btn btn-blue mt-20" @click="submitRegister()">
		            <i class="fa fa-check"></i>
		            <span class="ml-5 pt-4">
		              Registrarme
		            </span>
		        </button>
    	</div>
    	<div class="col-12 col-md-6 text-center" style="display: flex; align-items: center; background:#E3F2FD;padding: 15px">
	    	<div>
	    		
		    	<h4>Abre tu cuenta rápidamente usando tu red social favorita</h4>
	    		<button class="btn-block btn-social btn-lg btn-facebook" onclick="logWithFacebook()"><i class="fab fa-facebook"></i> Entrar con Facebook</button>

                <div class="text-center pt-10">
                    <div id="my-signin2"></div>
                </div>
	    		<hr>
	    		<div class="pt-20">
	    			<button class="btn-social btn-lg btn-white" onclick="triggerLoginModal()" style="border: 1px solid #0038aa;"><i class="fa fa-user"></i> Ya tienes cuenta?</button>
	    		</div>
	    	</div>

    	</div>
    </div>
  </div>
</div>
