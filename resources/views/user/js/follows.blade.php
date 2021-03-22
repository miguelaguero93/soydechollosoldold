<script type="text/javascript">
var app = new Vue({
	el: '#app',
	data: {
		followers: {!! $followers !!}
	},
	mixins: [helpersMixin],
	methods:{
		followUser(id,action,index){
		
			let payload = {
				id:id,
				action:action
			}
			var name = this.followers[index].name		
			axios.post('/api/user/follow',payload)
			snackSuccess('Ya no estas siguiendo a '+name)
			this.followers.splice(index,1)
		},
		namePreview(value,length){
			if (value != null) {
				if (value.length > length) {
					return value.substring(0, length)+'...'
				}
				return value
			}
		}
	}
})
</script>