<script type="text/javascript">
var app = new Vue({
	el: '#app',
	data: {
		items: {!!$items!!},
		filtered: null	
	},
	mixins:[helpersMixin],
	methods: {
		
		getDates(item){
		
			return  item.from.substring(0,10) + ' al ' + item.to.substring(0,10)
		
		},

		filter(type){
			
			if (type == this.filtered) {
				this.filtered = null
				for(item of this.items){
					item.visible = true
				}

				return

			}

			this.filtered = type
			// console.log(type)
			for(item of this.items){
				if (item.type_id != type) {
					item.visible = false
				}else{
					item.visible = true
				}
			}

		}

	}
})


function addVisibleClass(item){

	if(!item.classList.contains('h-100')){
		
		item.classList.add('h-100')
	}else{
		item.classList.remove('h-100')
	}
}

</script>