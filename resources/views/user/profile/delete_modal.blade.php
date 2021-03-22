<div id="deleteModal" class="modal" onclick="closeModals(event)">
  <div class="modal-content modal-content-small animate__animated animate__fadeIn" style="margin: auto">
    <span class="close" onclick="closeModals(event)">&times;</span>
    <div class="row" style="margin: 0">
      <div class="col-12 pt-20 pb-20">          
        <div class="row my-20">
          <div class="col-12">
             ¿Estás seguro de eliminar la cuenta? Esta acción es irreversible y perderás todo el acceso a la plataforma?
          </div>
        </div>
        <div class="mt-10">
          <button type="button" class="btn" @click="toggleModal()" style="display: inline-block"> Cancelar</button>
          <button type="button" class="btn btn-red" style="float: right" @click="confirmDeleteAccount()"> Confirmar</button>
        </div>
      </div>
    </div>
  </div>
</div>