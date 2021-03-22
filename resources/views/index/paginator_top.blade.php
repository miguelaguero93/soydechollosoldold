<div  class="pagination mt-15 mb-25 my-lg-30">
  <div class="pagination-item" v-show="currentPage > 4" @click="goToPage(-4)"> @{{ currentPage - 4 }}</div>
  <div class="pagination-item" v-show="currentPage > 3" @click="goToPage(-3)"> @{{ currentPage - 3 }}</div>
  <div class="pagination-item" v-show="currentPage > 2" @click="goToPage(-2)"> @{{ currentPage - 2 }}</div>
  <div class="pagination-item" v-show="currentPage > 1" @click="goToPage(-1)"> @{{ currentPage - 1 }}</div>
  <div class="pagination-item pagination-active"> @{{ currentPage }}  </div>
  <div class="pagination-item" v-show="!noMoreToload" @click="goToPage(1)"> @{{ currentPage + 1 }}  </div>
  <div class="h-px-1 w-px-75 bg-gray-900 mx-10"></div>
  <div class="pagination-item" @click="goToPage(5)" v-show="!noMoreToload"> @{{ currentPage + 5 }}  </div>
</div>