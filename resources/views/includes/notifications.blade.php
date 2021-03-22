<div id="notifications" class="d-flex icolink menu-count ml-15 ml-md-30 mr-10 mr-md-0 pt-6">
  <div v-cloak class="nav-icon">
    <i class="fas fa-bell font-30 cursor-pointer d-md-none" onclick="showNotifications()"></i>
    <i class="fas fa-bell font-30 cursor-pointer d-none d-md-block"></i>
    <span v-show="total > 0" class="menu-count-number">@{{total}}</span>
  </div>
  <div class="notifications-panel menu-count-options vertically-scrollable">
    <div class="menu-count-wrap">
      <div class="menu-count-title">
        <span class="d-none d-md-inline">Notificaciones</span>
        <span class="d-md-none" onclick="closeNotifications()"><i class="fas fa-times"></i> CERRAR</span>
        <span @click="allread()" class="pull-right config_link pr-2" title="Todas leidas" style="cursor: pointer"><i class="fas fa-check-square"></i> <span style="font-size: 12px">Todas leídas</span></span>
        <span class="pull-right mr-10"><a href="/settings" class="config_link pr-10"><i class="fas fa-cog"></i></a></span>
      </div>
      <a href="/alertas" style="color: black; text-decoration: none;">
        <div class="p-10 text-center" style="background-color: #fff5e5; border-bottom: 2px orange solid">
          <i class="fas fa-bell" style="color: orange"></i> AÑADIR PALABRAS CLAVES A MIS ALERTAS
        </div>
      </a>
      <div v-if="msg.length">
        <div v-for="(ms,index) in msg" class="notifications-item-wrapper" :class="{seen:ms.read_at != null}">
          <div @click="dismiss(index,ms.link)" style="cursor: pointer">
            <div class="notifications-item" v-html="ms.value"></div>
            <span class="notification-time">@{{ms.created_at|relativeTime}}<span>
          </div>           
          <div class="notifications-read">
            <i v-if="ms.read_at == null" @click="dismiss(index)" class="far fa-square" title="Marcar como leida" aria-hidden="true"></i>
          </div>
        </div>
      </div>
      <div v-else class="p-15">
        <p>No tienes nuevas notificaciones</p>
      </div>
    </div>
  </div>
  {{-- <div v-if="msg.length" style="position: absolute; bottom: 0; padding: 10px; background: white; width: 100%; text-align: center; border-top: 1px solid #b6b6b6;"><i class="fas fa-check-square"></i> Marcar todas como leídas</div> --}}
</div>