<script type="text/javascript">
var app = new Vue({
		el: '#app',
		data: {
			
			type: null,
			value: null,
		    no_minimum: false,
		    minimum_purchase: null,
		    no_max_discount: false,
		    max_discount: null,
			
		},
		mixins:[helpersMixin,createMixin],
		methods:{

			selectType(type){
				this.type = type
			},
			validate(){

				this.description = quill.root.innerHTML

				if (!isValidURL(this.site_url)) {
					this.site_url = ''
					snackError('Ingresa el enlace a la tienda donde se peude usar el cupón')
					return false
				}

				if (this.name.length < 5 || this.name.length > 255) {
					snackError('Aún no le has dado un titulo al chollo')
					return false
				}
				if (this.description.length < 10) {
					snackError('Ingresa una descripción.')
					return false
				}
				
				if (this.type == 1 ) {

					if ( !isNumeric(this.value) || this.value < 0 || this.value > 100 ) {
						
						snackError('Ingresa un porcentaje valido de descuento. Mayor a 0 y menor de 100.')
						
						return false
					
					}
				}

				if (this.type == 2) {

					if ( !isNumeric(this.value) || this.value < 0 ) {
							
						snackError('Ingresa un valor en euros valido para el descuento.')
						
						return false
					
					}
				}

				if (this.minimum_purchase != null) {
					if (!isNumeric(this.minimum_purchase) || this.minimum_purchase < 0) {
						snackError('Compra mínima invalido.')
						return false
					}
				}

				if (this.max_discount != null) {
					if (!isNumeric(this.max_discount) || this.max_discount < 0) {
						snackError('Descuento maximo invalido.')
						return false
					}
				}

				if (this.coupon.length == 0) {
					snackError('Inserta el código de descuento o cupón.')
					return false
				}

				if (this.coupon.length > 50) {
					snackError('El cupon no puede ser mas largo de 50 caracteres.')
					return false
				}


		
				
				if (this.all_spain == false) {
					if (this.selected_provinces.length == 0) {
						snackError('Seleccciona al menos una provincia.')
						return false
					}
				}

				return true
			},
			submit(){
				if (this.validate()) {
					showBlanket()
					let payload = {
						site_url : this.site_url,
						name : this.name,
						type: this.type,
						amount:  this.value,
						code : this.coupon,
						description : this.description,
						all_spain : this.all_spain,
						sexual_content : this.sexual_content,
						no_minimum: this.no_minimum,
						minimum_purchase: this.minimum_purchase,
						no_max_discount: this.no_max_discount,
						max_discount: this.max_discount
					}		

					if (this.all_spain == false) {
						payload.provinces = this.selected_provinces 
					}

					start_string = this.start+' '+this.start_time
					end_string = this.end+' '+this.end_time
					start = moment(start_string)
					end = moment(end_string)

					if (start.isValid()) {
						payload.from = start.format('Y-MM-D HH:mm:ss') 
					}
					if (end.isValid()) {
						payload.until = end.format('Y-MM-D HH:mm:ss') 
					}
					
					axios.post('/api/cupon/save',payload).then(function(response){
						console.log(response.data)
						if(typeof(response.data) == 'string'){
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
			},
			success(name){
				window.location.href = '/codigos-descuento/'+name
			}
			
		},
		watch:{
			no_minimum(){
				this.minimum_purchase = null
			},
			no_max_discount(){
				this.max_discount = null
			},

			site_url(new_value){
				if (!isValidURL(new_value)) {
					this.error_images = 'URL Invalida'
				}else{
					this.error_images = null
				}
			},			
		}
		
});
</script>