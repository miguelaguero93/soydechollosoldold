<script src="https://unpkg.com/vue-select@latest"></script>
<link rel="stylesheet" href="https://unpkg.com/vue-select@latest/dist/vue-select.css">
<script type="text/javascript">
Vue.component('v-select', VueSelect.VueSelect)

var app = new Vue({
	el: '#app',
	data: {
		items: {!! $items !!},
		options: {!! $options !!},
		cupons: {!! $cupons !!},
		itemSelected: null
	},
	created(){
		const options = {!! $options !!};
		this.options = options.sort((a,b) => (a.name > b.name) ? 1 : ((b.name > a.name) ? -1 : 0))
	},
	watch:{
		itemSelected(value){
			showBlanket()
			if (value != null) {
				if (this.cupons == 1) {
					window.location.href = '/codigos-descuento/'+value.slug;
				}else{
					// window.location.href = '/api/store/'+value 
					window.location.href = '/tienda/'+value.slug;
				}
			}
		}
	}
})
</script>	