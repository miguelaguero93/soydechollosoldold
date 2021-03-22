<script src="https://unpkg.com/vue-select@latest"></script>
<link rel="stylesheet" href="https://unpkg.com/vue-select@latest/dist/vue-select.css">
<script type="text/javascript">
Vue.component('v-select', VueSelect.VueSelect)

var app = new Vue({
	el: '#app',
	data: {
		categories: [],
		listCateg: [],
		categoriesWithSons: [],
		cantidadCat: 0,
		ano: '2020',
		month: 'Enero',
		months: ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
	},
	created(){
		var d = new Date();
		this.ano = d.getFullYear();
		this.month = this.months[d.getMonth()];
		/* First work with categories */
		const cat = {!! $categories !!};
		this.cantidadCat = {!!$count_categories!!};
		const listCat = [];
		const listCatR = categories_first = cat.sort((a,b) => (a.count < b.count) ? 1 : ((b.count < a.count) ? -1 : 0)).slice(0, 4);
		listCatR.forEach(element => {
			listCat.push(element.name);
		});
		this.listCateg = listCat;
		this.categories_first = cat.sort((a,b) => (a.count < b.count) ? 1 : ((b.count < a.count) ? -1 : 0)).slice(0, 3);

		this.categories_second = cat.sort((a,b) => (a.count < b.count) ? 1 : ((b.count < a.count) ? -1 : 0)).slice(3, 6);
		const catSon = [];
		cat.forEach(element => {
			element['son_first'] = element.sons.sort((a,b) => (a.count < b.count) ? 1 : ((b.count < a.count) ? -1 : 0)).slice(0, 4);
			element['son_second'] = element.sons.sort((a,b) => (a.count < b.count) ? 1 : ((b.count < a.count) ? -1 : 0)).slice(4, 7);
			if(element.id != 910){
				catSon.push(element)
			}
		});
		this.categoriesWithSons = catSon;
		console.log(catSon);
	},
	methods: {
		clickCategory: function(item){
			window.location.href = '/categoria/' + item.slug;
		}
	}
})
</script>	