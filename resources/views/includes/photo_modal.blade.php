<div id="photoModal" class="modal" onclick="closeModals(event)">
  <div class="modal-content modal-content-small animate__animated animate__fadeIn" style="margin: auto">
    <span class="close" onclick="closeModals(event)">&times;</span>
    <div class="row" style="margin: 0">
      <div class="col-12 pt-20 pb-20">
          
            <div class="row my-20">
              <div class="col-12 col-md-4">
                <div class="text-center">
                  <img style="max-width: 100%; border-radius: 100%;" :src="picture">
                </div>
              </div>
              <div class="col-12 col-md-6">
                
                <h4>Añade una imagen a tu perfil.</h4>
                <button type="button" class="btn btn-green" onclick="uploadOwnFile()"> Cambiar imagen</button>
                <small>Para mejores resultados sube una foto cuadrada</small>
              
              </div>
            </div>

          <div class="mt-10">
            <button type="button" class="btn btn-access" onclick="redirectAfterRegister()"> Continuar</button>
          </div>
        
        </div>
    </div>
  </div>
</div>