<script type="text/javascript">
var app = new Vue({
		el: '#app',
		data: {
			id:null,
			avaiable_images: [],
			loading_images: false,
			main_image: null,
			existing: null,
			price: '',
			regular_price: '',
			shipping_cost: null,
			price_on_error: false,
			regular_price_on_error: false,
			delivery_on_error: false,
			free_shipping: false,
			keywords_input: '',
			keywords: [],
			options: countries,
		    country: 'España',
		    another_country: null,
		    final_country: null,
		    own_image: false,
		    uploadingFromURL: false,
		    image_url:null,
		    item: null,
		    admin: {{$admin}},
		    editing: false,
		    realCat:null,
		    all_categories: {!! $all_categories !!}, 
			brands: {!! $brands !!},
			stores: {!! $stores !!},
			brand_id: null,
			store_id: null,
			updated_at: null,
			available: null,
			updated_at_time: '00:00',
            correct:0,
            failed:0,
            total:0,
            multiple:0,
            resultCategories:[]
		},
		mixins:[helpersMixin,createMixin],
		methods:{

			asignCategory(cats){
				this.selected_category = this.categories.find(item => item.id == cats[0])
				this.realCat = cats[1]
			},

			getCategory(){
				let payload = {
					name:this.name
				}
				let _this = this;
                this.resultCategories = [];


                if(this.name.length > 0){
                    axios.post('/api/getcategory',payload).then(function(response){

                        $('#categorysChollos').empty();

                        let data = response.data;

                        let count = data.length;

                        _this.total = _this.total + 1;
                        if(count > 0) {

                            _this.correct = _this.correct + 1;


                            if (count > 1) {


                                var modal = document.getElementById("testModal");
                                modal.style.display = "block";

                                Object.entries(response.data).forEach(([key, value]) => {

                                    _this.resultCategories.push(value);
                                    // $('#modalCategories').append("<div class=\"col-4\"><a @click=\"selectCategory('"+value[3]+"','"+value[4]+"','"+value[0]+"','"+value[1]+"')\"><div class=\"tag\" style='font-size: 0.7rem;'><span><i class=\"fas fa-microchip\"></i></span> <span>" + value[3] + "</span></a></div></div>");

                                });

                            }else {



                                /* app.asignCategory(response.data);*/

                                Object.entries(response.data).forEach(([key, value]) => {

                                    let category = [value[0],value[1]];
                                    _this.asignCategory(category);
                                    Object.entries(value).forEach(([key2, value2]) => {

                                        if (key2 != 0 && key2 != 1 && key2 != 2 && key2 != 4){
                                            $('#categorysChollos').append("<div class=\"tag\"><span>" + value2 + "</span></div>");
                                        }

                                    });
                                });


                            }
                        }
                        else{

                            _this.failed = _this.failed + 1;
                        }


                    }).catch(function(error){
                        console.log(error)
                        alert(error)
                    })
                }

			},
            selectCategory(name,cleaned_word,parentCategory,category){

               /* $('#modalMainCategory').empty().append("<div class=\"col-12 col-xl-3 col-lg-4 col-md-4\"><div class=\"tag\"><span>" + name + "</span></div></div> " +
                    "<div class=\"col-12\">" +
                    "<button  " +
                    "</div> ");*/


                if(confirm('¿Estás seguro que '+ name +' es la categoría?')){

                    var modal = document.getElementById("testModal");
                    modal.style.display = "none";
                    let data = [parentCategory,category];
                    app.asignCategory(data);

                    let payload = {};
                    payload.word = cleaned_word;
                    payload.category_id = category;

                    axios.post('/api/pool/add',payload).then(function(response){

                    }).catch(function(error){
                        console.log(error)
                        alert(error)
                    });

                }



            },
            omitcategory(){
                var modal = document.getElementById("testModal");
                modal.style.display = "none";
            },
            corregirFallo(){

			    this.correct = this.correct - 1;
			    this.failed = this.failed + 1;

            },

            corregirMultiple(){

			    this.failed = this.failed - 1;
			    this.correct = this.correct + 1;

			    this.multiple = this.multiple +1;

            },

			handleBlur(e) {
		      this.getCategory()
		    },

			cancelCreation(){
				window.location.href = '/'
			},

			continueCreation(){
				this.existing = null
				this.getImages(this.site_url)
			},

			uploadFromURL(){
				this.uploadingFromURL = !this.uploadingFromURL 
			},

			validate(){

				this.description = tinymce.get("myTextarea").getContent();

			
				if (this.main_image == null ) {
					snackError('Ingresa una imagen para el chollo')
					return false
				}

				if (this.name.length < 10 || this.name.length > 512) {
					snackError('Aún no le has dado un titulo al chollo')
					return false
				}
				if (this.description.length < 10) {
					snackError('Ingresa una descripción.')
					return false
				}
				if (this.price != null) {
					if (this.price.length) {
						if (!isNumeric(this.price) || this.price < 0) {
							snackError('El precio es invalido.')
							return false
						}
					}
				}
				if (this.regular_price != null) {
					if (this.regular_price.length) {
						if (!isNumeric(this.regular_price) || this.regular_price < 0) {
							snackError('El precio habitual es invalido.')
							return false
						}
					}
				}

				if (this.shipping_cost != null && this.shipping_cost.length) {
					if (!isNumeric(this.shipping_cost)) {
						snackError('El gasto de envío es invalido.')
						return false
					}
				}
				if (this.coupon != null && this.coupon.length > 50) {
					snackError('El cupon no puede ser mas largo de 50 caracteres.')
					return false
				}
				if (true) {}
				if (this.selected_category == null && this.realCat == null) {
					snackError('Seleccciona una categoria para el chollo.')
					return false
				}
				if (this.keywords.length == 0) {
					snackError('Ingresa al menos una palabra clave.')
					return false
				}
				if (this.all_spain == false) {
					if (this.selected_provinces.length == 0) {
						snackError('Seleccciona al menos una provincia.')
						return false
					}
				}

				if (this.country == 'Otro') {
					if (this.another_country != null && this.another_country != '' ) {
						this.final_country = this.another_country
					}else{
						snackError('Selecccione país de envío.')
						return false
					}
				}else{
					this.final_country = this.country
				}

				return true

			},

			submit(){
				if (this.validate()) {
					showBlanket()
					
					let payload = {
						id:this.id,
						image : this.main_image,
						site_url : this.site_url,
						name : this.name,
						description : this.description,
						price : this.price,
						regular_price : this.regular_price,
						shipping_cost : this.shipping_cost,
						keywords : this.keywords,
						country : this.final_country,
						all_spain : this.all_spain,
						sexual_content : this.sexual_content,
						discount_code : this.coupon,
						site_name : this.site_name,
						discount : this.discount,
						free_shipping : this.free_shipping,
						own_image : this.own_image,
						real_cat : this.realCat,
						brand_id : this.brand_id,
						store_id : this.store_id,
						available : this.available,
						updated_at : this.updated_at+' '+this.updated_at_time+':00' 
					}	
					if (this.selected_category == null) {
						if (this.realCat != null) {
							payload.category_id = this.realCat
						}else{
							payload.category_id = 1
						}
					}else{
						payload.category_id = this.selected_category.id
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
					console.log(payload)
					axios.post('/api/item/save',payload).then(function(response){
						console.log(response.data)
						if(typeof(response.data) == 'object'){
							app.success(response.data)
						}else{
							hideBlanket()
							snackError(response.data)
						}
					}).catch(function(error){
						hideBlanket()
						console.log(error)
						alert(error)
					})
				}
			},
			success(res){
				var item = {
					name: this.name,
					id:res[0]
				}
				if (this.own_image) {
					image = document.getElementById('image').files[0]
					if (image != undefined) {
						const data = new FormData();
  						data.append('image', image);
  						data.append('id', res[0]);
						axios.post('/api/item/pic',data, {
						  headers: {
						    'Content-Type': 'multipart/form-data'
						  }
						}).then(function(response){
							console.log(response.data)
							window.location.href = app.cholloLinkv2(res[1])
						}).catch(function(error){
							hideBlanket()
							console.log(error)
							snackError(error)
						})

					}

				}else{
					window.location.href = app.cholloLinkv2(res[1])
				}
			},
			removeTag(index){
				this.keywords.split(index,1)
			},

			
			findItem: _.debounce(function(url){

				if (url.length > 0) {

					let payload = {
						url:url,
						isEditing: this.isEditing
					}		
					axios.post('/api/item/find',payload).then(function(response){
						if(typeof(response.data) == 'object'){
							app.existing = response.data
							app.error_images = null							
						}else{
							app.getImages(url)
						}
					}).catch(function(error){
						console.log(error)
						alert(error)
					})
				}else{
					app.error_images = null
				}

			},500),

			updateKeyWords: _.debounce(function(string){
				app.keywords = []
				var split = string.split(',')
				for(item of split){
					var tag = item.trim().replace('á','a').replace('é','e').replace('í','i').replace('ó','o').replace('ú','u')
					var find  = app.keywords.find(item => item == tag)
					if (find == undefined) {
						if (tag.length > 2) {
							app.keywords.push(tag)
						}
					}
				}
			},400),

			resetVariables(){
				this.loading_images = true
				this.avaiable_images = []
				this.error_images = null
				this.existing = null
				this.name = ''
				this.description = ''
				this.site_name = ''
			},

			getImages(url){				
				this.resetVariables()

				let payload = {
					url:url
				}

				axios.post('/api/url_images',payload,{timeout: 10000}).then(function(response){
					app.imagesResponse(response.data)
				}).catch(function(error){
					app.loading_images = false
					app.error_images = true
				})

			},

			imagesResponse(data){
				this.loading_images = false					
				if (typeof data == 'object'){
					if (data[0].length == 0) {
						this.error_images = 'Sube tu propia imagen';
					}else{
						this.avaiable_images = data[0]
						this.name = data[1]					
						this.site_name = data[3]					
						this.getCategory()					
					}
					this.updateContainerWidth(data[0].length)					
					this.setMainImage(0)
				}else{
					this.error_images = data
					this.avaiable_images = []			
				}
			},

			unSelectAllImage(){
				for(let image of this.avaiable_images){
					image.selected = false
				}
			},

			setMainImage(index){
				this.unSelectAllImage()
				if (this.avaiable_images.length) {
					let main_image = this.avaiable_images[index]
					main_image.selected = true
					this.main_image = main_image.src
				}
			},

			updateContainerWidth(length){
				let new_length = length * 190
				document.getElementById('images_container').style.width = new_length+'px'
			},

			validateImageFormat: _.debounce(function(url){

				var is_image = url.match(/^http.*\.(jpeg|jpg|gif|png|webp)$/gi) != null;
				if (is_image) {
					app.main_image = url
				}else{
					app.main_image = null
					snackError('URL no es una imagen valida.')
				}

			},500),

			setData(){

				this.id = this.item.id 
				this.site_url =  this.item.link
				this.main_image =  this.item.image
				this.name =  this.item.name
				this.price =  this.item.price
				this.regular_price =  this.item.regular_price
			    this.country =  this.item.country
			    this.sexual_content =  this.item.sexual_content
				this.coupon =  this.item.discount_code
				this.shipping_cost =  this.item.shipping_cost
				this.free_shipping =  !!this.item.free_shipping
				this.realCat = this.item.category_id
				this.brand_id = this.item.brand_id
				this.store_id = this.item.store_id
				this.available = this.item.available
				this.updated_at = moment(this.item.updated_at).format('Y-MM-D') 


				this.selected_category = this.categories.find( i => i.id == this.item.category_id ) 
				
				setTimeout(
					function(){
						tinymce.get("myTextarea").setContent(app.item.description)
					},3000
				)

				this.description = this.item.description
				for(keyword of this.item.keywords){
					this.keywords.push(keyword.keyword)
				}

				if (this.item.from != null) {
					var from = moment(this.item.from)
					this.start = from.format('Y-MM-D')
					this.start_time = from.format('H:mm')
					start_picker.setDate(this.start)
				}
				if (this.item.until != null) {
					var until = moment(this.item.until)
					this.end = until.format('Y-MM-D')
					this.end_time = until.format('H:mm')
					end_picker.setDate(this.end)
				}
				this.country = this.item.country
				if (this.country != 'España' && this.country != 'China') {
					this.another_country = this.country
					this.country = 'Otro'
				}

			    this.all_spain =  !!this.item.all_spain
			    this.selected_provinces = JSON.parse(this.item.provinces)

			}
			
		},
		watch:{
			image_url(new_value){
				
				this.validateImageFormat(new_value)
			},

			site_url(new_value){
				if (!this.editing) {
					this.findItem(new_value);
				}
			},

			regular_price(new_value){
				if (new_value != null) {
					if (!isNumeric(new_value) && new_value.length != '' ) {
						this.regular_price_on_error = true
					}else{
						this.regular_price_on_error = false
					}
				}
			},

			price(new_value){
				if (new_value != null) {
					if (!isNumeric(new_value) &&  new_value.length != '') {
						this.price_on_error = true
					}else{
						this.price_on_error = false
					}	
				}
			},
			shipping_cost(new_value){
				if (!isNumeric(new_value) &&  new_value.length != '') {
					this.delivery_on_error = true
				}else{
					this.delivery_on_error = false
				}	
			},
			free_shipping(new_value){
				if (new_value == true) {
					this.shipping_cost = ''
				}
			},
			keywords_input(value){
				this.updateKeyWords(value)
			},


		},
		computed: {
			
			discount(){
				if (isNumeric(this.price) && isNumeric(this.regular_price)) {
					var discount  = ( (this.regular_price - this.price) * 100) / this.regular_price 
					if (discount>0) {
						return discount.toFixed()
					}else{
						return null
					}
				}
				return null
			},
		},
		created(){
			var time = new Date()
			var hour = time.getHours()
			if (hour != '23') {
				var next = (hour + 1) + ':00'
				this.updated_at_time = next
			} 

		},
});

@if(isset($item))
$(function(){
	app.item = {!!$item!!}
	app.editing = true
	app.setData()
})
@endif



</script>