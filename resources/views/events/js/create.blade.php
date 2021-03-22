<script type="text/javascript">
var app = new Vue({
	el: '#app',
	data: {
		site_url:'',
		name:'',
		type: 0,
		start: '',
		end: '',
		start_time: '00:00',
		end_time: '23:59',
		sexual_content: false,
		description: ''
	},
	mixins:[helpersMixin],
	methods:{
		validate(){

			this.description = quill.root.innerHTML

			if (this.type < 1) {
				snackError('Selecciona evento o sorteo')
				return false
			}
			if (!isValidURL(this.site_url)) {
				this.site_url = ''
			}
			if (this.name.length < 5 || this.name.length > 255) {
				snackError('Aún no le has dado un titulo al evento')
				return false
			}
			if (this.description.length < 10) {
				snackError('Ingresa una descripción.')
				return false
			}
			if (this.start == '') {
				snackError('Selecciona fecha de inicio')
				return false
			}
			if (this.end == '') {
				snackError('Selecciona fecha de finalización')
				return false
			}

			return true

		},
		selectType(type){
			this.type = type
		},
		success(id){
			window.location.href = '/eventos'
		},
		submit(){
			if (this.validate()){
				
				showBlanket()
				
				
				image = document.getElementById('image').files[0]


				const data = new FormData();
				data.append('site_url',this.site_url)
				data.append('name',this.name)
				data.append('type_id',this.type)
				data.append('description',this.description)
				data.append('sexual_content',this.sexual_content)

				if (image != undefined) {
  					data.append('image', image);
				}

				start_string = this.start+' '+this.start_time
				end_string = this.end+' '+this.end_time
				start = moment(start_string)
				end = moment(end_string)
				from = null
				until = null
				if (start.isValid()) {
					from = start.format('Y-MM-D HH:mm:ss')
				}
				if (end.isValid()) {
					until = end.format('Y-MM-D HH:mm:ss')
				}
				data.append('from',from)
				data.append('until',until)

				axios.post('/api/event/save',data, {
				  headers: {
				    'Content-Type': 'multipart/form-data'
				  }
				}).then(function(response){
					console.log(response.data)
					if(typeof(response.data) == 'number'){
						app.success(response.data)
					}else{
						hideBlanket()
						snackError(response.data)
					}
				}).catch(function(error){
					hideBlanket()
					console.log(error)
					snackError(error)
				})
			}
		}
	}
});

image = document.getElementById('image')

image.addEventListener('change', function(event){
	
	file = event.target.files[0]
	let img = new Image()
	
	img.src = URL.createObjectURL(file)
	
	img.onload = () => {
		
	   if (img.width != 900 || img.height != 320) {

	   		snackError('La imagen debe ser 900px de ancho por 320px de alto')
	  	 	image.value = ''
	   		return
	   }

	   console.log(img.src)
	   cont = document.getElementById('image-container')
	   cont.src = img.src
	
	}

})

</script>