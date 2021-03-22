<script type="text/javascript">
	
var favoriteMixin = {			
		
		methods:{
			
			addFavorite(index){
				item = this.items[index]
				let payload = {
					id:item.id,
					value:item.favorite	
				}
				console.log(this.items[index])
				axios.post('/api/chollo/favorite',payload).then(function(response){
					if(typeof(response.data) == 'string'){
						snackSuccess(response.data)
						item.favorite = !item.favorite
					}else{
						triggerLoginModal()
					}
				}).catch(function(error){
					console.log(error)
					alert(error)
				})
			}
		
		}
	}
</script>