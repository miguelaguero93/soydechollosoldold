<div id="comments" class="collapse-title">
  <span>Comentarios</span>
</div>
<div class="collapse-content">
  <div class="listcomment">
    <div v-if="logged" class="listcomment-item">
      <div class="listcomment-avatar">
        <img :src="logged.avatar">
      </div>
      <div class="listcomment-content">
        <textarea cols="30" rows="10" placeholder="Añade un comentario" v-model="newComment" maxlength="21845"></textarea>
        <div class="text-right">
          <button v-show="newComment.length > 3" class="btn btn-green float-right" @click="submitComment()">
            <i class="far fa-comment font-20 color-orange2"></i> 
            <span class="name">Comentar</span>
          </button>
        </div>
      </div>
    </div>
    <div v-else class="listcomment-item">
      <div class="listcomment-avatar">
        <img src="/images/default.png">
      </div>
      <div class="listcomment-content">
        <textarea id="comment_area" cols="30" rows="10" maxlength="21845" placeholder="Añade un comentario" @click="triggerLogin()"></textarea>
      </div>
    </div>
    <div v-for="(item,index) in item_comments" class="listcomment-item">
      <div class="listcomment-avatar">
        <div class="happy-hover">
          <a :href="userLink(item.user)"><img :src="item.user.avatar"></a>
        </div>
      </div>
      <div class="listcomment-content">
        <div class="d-flex align-items-center">
          <span class="font-bold color-blue1 hover-c-blue1"><a :href="userLink(item.user)">@{{ item.user.name }}</a></span> <span class="ml-15 color-gray-900">@{{ item.created_at | relativeTime }} </span>
        </div>
        <div class="mt-5 line-14">
          @{{ item.value }} 
        </div>
        <div class="d-flex mt-10 align-items-center">
          <div class="d-flex align-items-center">
            <i class="fas fa-thumbs-up font-22 hover-c-blue1" :class="{selected:item.vote == 1}" @click="voteComment(item,1)"></i>
            <span class="ml-5 pt-6">@{{ item.plus }} </span>
          </div>
          <div class="d-flex align-items-center ml-15">
            <i class="fas fa-thumbs-down font-22 hover-c-blue1" :class="{selected:item.vote == -1}" @click="voteComment(item,-1)"></i>
            <span class="ml-5 pt-6">@{{ item.minus }} </span>
          </div>
          <div class="ml-15 pt-5 hover-c-blue1" @click="reply(index)">
            RESPONDER
          </div>
        </div>
        <div v-if="logged && item.replying" class="listcomment-item">
          <div class="listcomment-avatar smaller-avatar">
            <img :src="logged.avatar">
          </div>
          <div class="listcomment-content">
            <textarea cols="30" rows="10" maxlength="21845" placeholder="Añade un comentario" v-model="item.reply"></textarea>
            <div class="text-right">
              <button v-show="item.reply.length > 3" class="btn btn-green float-right" @click="submitReplyComment(index)">
                <i class="far fa-comment color-orange2 font-20"></i> 
                <span class="name"> Responder </span>
              </button>
            </div>
          </div>
        </div>
        <div v-if="item.children.length" class="listcomment-more" @click="showChildren(index)">
          <span class="name">Ver las @{{ item.children.length }} respuestas</span> <i class="fas fa-caret-down pl-5"></i>
        </div>
        <div v-show="item.visibleChildren">
          <div v-for="children in item.children" class="listcomment-item">
            <div class="listcomment-avatar smaller-avatar">
              <a :href="userLink(item.user)"><img :src="children.user.avatar"></a>
            </div>
            <div class="listcomment-content">
              <div class="d-flex align-items-center">
                <span class="font-bold color-blue1"><a :href="userLink(item.user)">@{{ children.user.name }}</a></span> <span class="ml-15 color-gray-900"> @{{ children.created_at | relativeTime }} </span>
              </div>
              <div class="mt-5 line-14">
                @{{ children.value }}   
              </div>
              <div class="d-flex mt-10 align-items-center">
                <div class="d-flex align-items-center">
                  <i class="fas fa-thumbs-up font-22 hover-c-blue1" :class="{selected:children.vote == 1}" @click="voteComment(children,1)"></i>
                  <span class="ml-5 pt-6">@{{ children.plus }}</span>
                </div>
                <div class="d-flex align-items-center ml-15">
                  <i class="fas fa-thumbs-down font-22 hover-c-blue1" :class="{selected:children.vote == -1}" @click="voteComment(children,-1)"></i>
                  <span class="ml-5 pt-6">@{{ children.minus }}</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>