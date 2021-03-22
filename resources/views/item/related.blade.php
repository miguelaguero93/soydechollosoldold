<div class="collapse-content">
  <div class="row">
    <div v-for="item in related" class="col-6 col-md-3 mb-20">
      <a class="no_link" :href="cholloLink(item)">
        <div class="border rounded text-center p-10 related-box">
          <div class="image image_realted">
            <img :src="item.image_small" class="d-block" :alt="item.name">
          </div>
          <div class="font-bold mb-5 font-11 font-lg-13 no_link"> 
            @{{item.name}}
          </div>
          <div class="font-weight font-18 font-bold my-5 no_link">
            @{{ item.price | numberformat }}
          </div>
          <a :href="cholloLink(item)" class="btn btn-small d-inline-flex">
            Ir al chollo
          </a>
        </div>
      </a>
    </div>
  </div>
</div>