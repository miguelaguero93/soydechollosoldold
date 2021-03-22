<div id="confirmModal" class="modal" onclick="closeModals(event)">
  <div class="modal-content animate__animated animate__fadeIn" style="margin: auto">
    <span class="close" onclick="closeModals(event)">&times;</span>
    <div class="row" style="margin: 0">
      <div class="col-12 pt-20 pb-20">
          <div>
            <h4>Por favor introduce un Alias y una contraseña. Confirma que has leido tanto el Aviso legal, condiciones de uso y política de privacidad.</h4>
            <input type="text" v-model="username" class="form-control w-100">
            <small>Una sola palabra. Será visible para todos.</small>
            <div v-if="nameError" class="pill pill-orange mt-2">
              <small>
                <i class="fa fa-info-circle"></i> Ingresa un nombre de usuario de una sola palabra y sin @. 
              </small>
            </div>
            <div class="mt-20 mb-30">
              <label class="custom_checkbox"> He leído y acepto el <a href="/pagina/aviso-legal-y-condiciones-de-uso" target="_blank"> Aviso legal, condiciones de uso <i class="fas fa-external-link-alt"></i> </a> y la  <a href="/pagina/politica-privacidad" target="_blank">política de privacidad <i class="fas fa-external-link-alt"></i>.</a>
                <input type="checkbox" v-model="terms">
                <span class="checkmark"></span>
              </label>
            </div>
            <span v-if="termsError" class="pill pill-orange mt-2">
              <small>
                <i class="fa fa-info-circle"></i> Debes aceptar lor términos y condiciones 
              </small>
            </span>
          </div>
          <div class="mt-10">
            <button type="button" class="btn btn-access" @click="confirmSocialRegister()"> Continuar</button>
          </div>
        
        </div>
    </div>
  </div>
</div>