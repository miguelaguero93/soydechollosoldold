<script type="text/javascript">
	var app = new Vue({
		el: '#app',
		data: {
			user:{!! $user !!},
			id:{{ $id }}, 
			items:{!! $items !!},
			follows:{{ $follows }},
			logged:{{ $logged }}
		},
		methods:{
			namePreview(value,length){
				if (value != null) {
					if (value.length > length) {
						return value.substring(0, length)+'...'
					}
					return value
				}
			},
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
			}
		}
	});
</script>