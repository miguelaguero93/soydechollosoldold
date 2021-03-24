<div id="loginModal" class="access-modal" onclick="closeAccessModals(event)">    
    <div class="am-inside">
      <div class="am-left" id="login_gif">
        
        <div class="px-3" style="position: absolute; top: 39px; text-align: center;"><img src="{{asset('/images/logo.png')}}" width="200px"></div>
        <div class="f-middle p-10">
          <span>Disfruta de las ventajas</span>
          <span>Recibe Alertas</span>
          <span>Comenta y vota</span>
        </div>

      </div>
      <div>
        <div class="close-icon">X</div>
        <form class="access-login animate__animated active">
          <div class="px-10 row">
            <div class="col-6 d-block text-center" style="border-bottom: 3px solid #0038aa">ENTRAR</div>
            <div class="col-6 text-center d-block link" id="create-account">REGISTRARSE</div>
          </div>
          <div>
            <label for="email">Email</label>
            <input type="text" v-model="username">
          </div>
          <div>
            <label for="password">Contraseña</label>
            <fieldset>
              <input type="password" id="password" v-model="password">
              <span class="eye-icon" id="eyeIcon"></span>
            </fieldset>
            
            <div>
              <div class="form-check">
                <input type="checkbox" v-model="remember_me">
                <label for="box-1">Recordar contraseña</label>
              </div>
              <a href="/password/reset">¿Olvidaste tu contraseña?</a>
            </div>
          </div>

          <button type="button" class="btn btn-access" @click="loginUser()">Iniciar sesión</button>
          
          <div class="separator_line_wrapper"><div class="separator_line"></div> <div class="line_text"><span>O continua con</span></div></div>

          <ul class="social-connect">
            <li  style="box-shadow: 0px 3px 9px -2px #afafaf"><a href="#" onclick="logWithFacebook()"><img src="https://image.flaticon.com/icons/svg/1312/1312139.svg"><span style="margin: auto;">Facebook</span></a></li>
            <li style="border: none;">
              <div id="my-signin2">Entrar con Google</div>
            </li>
          </ul>
        </form>


        <form class="access-register animate__animated pt-md-35 pt-20">
          <div class="px-10 row">
            <div class="col-6 d-block text-center link" id="login-account" >ENTRAR</div>
            <div class="col-6 text-center d-block" style="border-bottom: 3px solid #0038aa">REGISTRARSE</div>
          </div>
          <div>
            <label for="username">Alias</label>
            <input type="text" v-model="username" maxlength="15">
            <small>Una sola palabra. Será visible para todos.</small>
            <div v-if="nameError" class="pill pill-orange mt-2">
              <small>
                <i class="fa fa-info-circle"></i> Ingresa un nombre de usuario de una sola palabra y sin @. 
              </small>
            </div>
          </div>

          <div>
            <label for="email">Email</label>
            <input type="email" id="email" name="email" v-model="email" maxlength="100">
            <span v-if="emailError" class="pill pill-orange mt-2">
              <small>
                <i class="fa fa-info-circle"></i> Ingresa un email valido. 
              </small>
            </span>
          </div>


          <div>
            <label for="password_register">Contraseña</label>
            <fieldset>
              <input type="password" id="password_register" v-model="password">
              <span class="eye-icon-register" id="eyeIconRegister"></span>
            </fieldset>
            <span v-if="passwordError" class="pill pill-orange mt-2">
              <small>
                <i class="fa fa-info-circle"></i> Ingresa una contraseña de al menos 5 caracteres 
              </small>
            </span>
          </div>

          <div>

            <div>
              <div class="form-check">
                <input type="checkbox" id="box-2" v-model="subscribed">
                <label for="box-2">Quiero un resumen por email de los chollos, ¡Para no perderte nada!</label>
              </div>
            </div>

            <div>
              <div class="form-check">
                <input type="checkbox" id="box-3" v-model="terms">
                <label for="box-3">He leido y acepto las &nbsp; <a href="/pagina/politica-privacidad" target="_blank">condiciones de uso <i class="fas fa-external-link-alt"></i></a> y la <a href="pagina/aviso-legal-y-condiciones-de-uso" target="_blank">política de privacidad <i class="fas fa-external-link-alt"></i></a></label> 
              </div>
            </div>            
            <span v-if="termsError" class="pill pill-orange mt-2">
              <small>
                <i class="fa fa-info-circle"></i> Debes aceptar lor términos y condiciones 
              </small>
            </span>
          </div>

          <button type="button" @click="submitRegister()" class="btn btn-access">Crear cuenta</button>
          <div class="separator_line_wrapper"><div class="separator_line"></div> <div class="line_text"><span>O continua con</span></div></div>

          <ul class="social-connect">
            <li style="box-shadow: 0px 3px 9px -2px #afafaf"><a href="#" onclick="logWithFacebook()"><img src="https://image.flaticon.com/icons/svg/1312/1312139.svg"><span style="margin: auto;">Facebook</span></a></li>
            <li style="border: none;">
              <div id="my-signin3">Entrar con Google</div>
            </li>
          </ul>
        </form>

      </div>
    </div>
  </div>