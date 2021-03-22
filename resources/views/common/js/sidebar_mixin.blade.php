<script type="text/javascript">
var sidebarMixin = {			
	data:{
		top: [],
		comments: [],
		commentsAll: false
	},
	methods:{
		// voteTop(index,operation){
		// 	var item = this.top[index]
		// 	this.submitVote(item,operation)
		// },

		voteTop(index,operation){
			if (this.logged == 0) {
				return triggerLoginModal()
			}
			item = this.top[index]
			item.votes += operation
			item.user_vote = operation
			snackSuccess('Chollo votado!')

			this.submitVote(item,operation)
		},
		
		submitVote(item,operation){
			
			let payload = {
				operation:operation,
				id:item.id
			}		

			axios.post('/api/chollo/vote',payload).then(function(response){
				
				if(typeof(response.data) == 'number'){
					if (response.data == 0) {
						triggerLoginModal()
					}
				}else{
					snackError(response.data)
				}

			}).catch(function(error){
				console.log(error)
				alert(error)
			})

		},
	
		showAllComments(){
			this.commentsAll = true
		},
		getSideBar(){	
			if (window.innerWidth >= 992) {
				axios.get('/api/getsidebar').then(function(response){
					app.top = response.data[0]
					app.comments = response.data[1]
				})
			}				
		}

		
		

	},
	created(){
		this.getSideBar()
	}
}
</script>