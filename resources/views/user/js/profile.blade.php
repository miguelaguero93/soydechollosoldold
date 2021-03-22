<script type="text/javascript">
var app = new Vue({
		el: '#app',
		data: {},
		mixins:[],
		methods:{
			toggleModal(){
				var modal = document.getElementById("deleteModal")
          		var state = modal.style.display;
          		if (state == 'flex') {
          			modal.style.display = 'none'
          		}else{
          			modal.style.display = 'flex'
          		}
			},
			confirmDeleteAccount(){
				showBlanket()
				let payload = {
				}		
				axios.post('/api/user/delete',payload).then(function(response){
					if(typeof(response.data) == 'number'){
						window.location.href = '/'						
					}else{
						snackWarning(response.data)
					}
				}).catch(function(error){
					console.log(error)
					alert(error)
				})
				
			}
		},
		watch:{},
		computed:{}
	});
</script>