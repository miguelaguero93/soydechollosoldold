<div v-cloak class="container py-20">
  <div class="mb-20 text-center">
    <div class="font-bold font-18">
    Listado de categorias ofertas, chollos y descuentos - Actualizas a @{{ month }} de @{{ ano }}
    </div>
    <div class="font-16 pd-top-15">
      Encuentra y comparte chollos, ofertas y descuentos gratis, aquí encontraras el listado de nuestras @{{ cantidadCat }} categorías y chollos de @{{ listCateg[0] }},  chollos de @{{ listCateg[1] }},  chollos de @{{ listCateg[2] }},  chollos de @{{ listCateg[3] }}. ⌚ actualizados en tiempo real. 
    </div>
  </div>
  <div class="mb-20">
    <div class="font-bold font-18 text-category-par">
      Categorías más populares
    </div>
    <div class="hold-categories">
      <div class="category-row">
        <div class="category-element" v-on:click="clickCategory(item)" v-for="(item,index) in categories_first" style="background-image: url(/images/backcat.jpg);">
            <div class="image-in-cat">
                <img v-bind:src="[item.image == null ? 'https://soydechollos.com/storage/users/1552005727.png' : 'storage/' + item.image]">
            </div>
            <div class="text-in-cat">
              @{{ item['name'] }} 
            </div>
        </div>
      </div>
      <div class="category-row">
        <div class="category-element" v-on:click="clickCategory(item)"  v-for="(item,index) in categories_second" style="background-image: url(/images/backcat.jpg);">
            <div class="image-in-cat">
                <img v-bind:src="[item.image == null ? 'https://soydechollos.com/storage/users/1552005727.png' : 'storage/' + item.image]">
            </div>
            <div class="text-in-cat">
              @{{ item['name'] }} 
            </div>
        </div>
      </div>
    </div>
    <div class="font-bold font-18 text-category-par-second">
      Categorías populares por categoría principal
    </div>
    <div class="category-sons" v-for="(item,index) in categoriesWithSons">
      <div class="category-title-son">
        @{{ item['name'] }} 
      </div>
      <div class="category-row">
        <div class="category-element" v-on:click="clickCategory(itemIn)" v-for="(itemIn,indexIn) in item.son_first">
            <div class="image-in-cat">
                <img v-bind:src="[itemIn.image == null ? 'https://soydechollos.com/storage/users/1552005727.png' : 'storage/' + itemIn.image]">
            </div>
            <div class="text-in-cat-son">
              @{{ itemIn['name'] }} 
            </div>
        </div>
      </div>
      <div class="category-row">
        <div class="category-element" v-on:click="clickCategory(itemIn)" v-for="(itemIn,indexIn) in item.son_second">
            <div class="image-in-cat">
                <img v-bind:src="[itemIn.image == null ? 'https://soydechollos.com/storage/users/1552005727.png' : 'storage/' + itemIn.image]">
            </div>
            <div class="text-in-cat-son">
              @{{ itemIn['name'] }} 
            </div>
        </div>
        <div class="category-element son-button" v-on:click="clickCategory(item)">
            <div class="text-in-cat-son-button">
              <div>
                Más categorías relacionadas
              </div>
            </div>
        </div>
      </div>
    </div>
  </div>

</div>