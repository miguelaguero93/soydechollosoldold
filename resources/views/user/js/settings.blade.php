<script type="text/javascript">
var app = new Vue({
	el: '#app',
	data: {
		settings: {!! $settings !!}
	},
	methods:{

		update(){
			
			setTimeout(function(){
				let payload = {
					settings:app.settings
				}		
				axios.post('/api/settings/update',payload);
			},1000)

		}
	}

});
</script>