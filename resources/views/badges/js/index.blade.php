<script type="text/javascript">
	var app = new Vue({
		el: '#app',
		data: {
			user:{!! $user !!},
			items:{!! $items !!},
			id:{{ $id }}, 
			follows:{{ $follows }},
			logged:{{ $logged }}
			 
		},
		methods:{
			followUser(action){
				if (this.logged == 0) {
					return triggerLoginModal()
				}

				let payload = {
					id:this.id,
					action:action
				}		
				axios.post('/api/user/follow',payload)

				this.follows = action
				if (action == 1) {
					snackSuccess('Estas siguiendo a '+this.user.name)
				}else{
					snackSuccess('Ya no estas siguiendo a '+this.user.name)
				}
			},
			checkAward(item_id){
				if (this.awarded.indexOf(item_id) != -1) {
					return true
				}
				return false
				
			}
		}
	});
</script>