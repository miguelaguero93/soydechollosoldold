<div class="container py-20">
  <div v-if="notifications.length">
    <div v-for="(ms,index) in notifications" class="notifications-item-wrapper mb-5">
      <a :href="ms.link" class="link-none text-gray-dark" style="cursor: pointer">
        <div class="notifications-item" v-html="ms.value"></div>
        <span class="notification-time">@{{ms.created_at|relativeTime}}<span>
      </a>
    </div>
  </div>
  <div v-else class="p-15">
    <p>No tienes notificaciones</p>
  </div>
</div>