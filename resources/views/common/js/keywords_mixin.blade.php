<script type="text/javascript">
var keywordsMixin = {			
	data:{
		keywords: {!!$keywords!!}
	},
	methods:{
		addToKeywords(index){		
			item = this.keywords[index]
			
			if (!this.logged) {
				return triggerLoginModal()
			}

			let payload = {
				keyword:item.keyword
			}	
				
			axios.post('/api/keyword',payload).then(function(response){
				snackSuccess(response.data);
			}).catch(function(error){
				console.log(error)
				alert(error)
			})	

			item.selected = !item.selected
		}		

	}
}
</script>