<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script type="text/javascript">
	var app = new Vue({
		el: '#app',
		data: {
			items: {!! $items !!},
			amazon: {!! $amazon !!},
			query: '{{$query}}',
			keywords_ids: {!! $keywords_ids !!},
			logged: {!! $logged !!},
			activateAlert: {{$activateAlert}},
			itemsCount: {!!  $itemsCount !!},
			currentPage: 1,
			loading: true,
			firstLoading: true,
			first: true
		},
		mixins:[helpersMixin,favoriteMixin],
		methods:{
			scrollToEl(element){
				$([document.documentElement, document.body]).animate({
					scrollTop: $("#" + element).offset().top - 100
				}, 2000);
			},
			searchMore(){
				if (this.currentPage * 16 < this.itemsCount) {
					if (this.loading == false) {
						this.loading = true
						let payload = {
							query: '' + this.query,
							keywords_ids: this.keywords_ids,
							page: this.currentPage+1,
							pagination: 16
						}	
						axios.post('/api/ofertas/getMore',payload).then(function(response){
							app.currentPage = app.currentPage + 1;
							app.items = app.items.concat(response.data);
							app.loading = false;
							app.firstLoading = false;
						}).catch(function(error){
							console.log(error)
							alert(error)
						})
					}
				}
			},
			goToSettingsAlerts(){
				window.location.href = '/alertas';
			},
			checkAvailability(item){
				if (!item.available) {
					return true
				}else{
				    if(item.until != null) {
				        now = moment().tz('Europe/Madrid').format('YYYY-MM-DD HH:mm:ss')         
				        xxx = now > item.until
				        if (xxx) {
				        	return true;
				        }
				    } 
				}
				return false;
			},
			getAmazon(){
				let payload = {
					keyword:this.query
				}		
				axios.post('/api/amazon',payload).then(function(response){
					console.log(response.data);
					if(typeof(response.data) == 'object'){
						app.amazon = response.data
					}
					if(app.amazon.length === 0){
						$('#sl-1').addClass('special');
						$('#sl-2').addClass('special');
					}
					app.loading = false;
					app.firstLoading = false;
					app.first = false;
				}).catch(function(error){
					console.log(error)
					alert(error)
				})
			},
			addIntKeyword(keywordToSave, id){
				if($('#' + id + ' div').hasClass('btn-green')){
					$('#' + id + ' div').removeClass('btn-green');
					$('#' + id + ' div i').addClass('fa-plus');
					$('#' + id + ' div i').removeClass('fa-check');
				}else{
					$('#' + id + ' div').addClass('btn-green');
					$('#' + id + ' div i').removeClass('fa-plus');
					$('#' + id + ' div i').addClass('fa-check');
				}
				let payload = {
					keyword: keywordToSave
				}		
				axios.post('/api/keyword',payload).then(function(response){
					if(response.data){
						snackSuccess('¡Palabra clave agregada correctamente!');
						return true;
					}

					snackSuccess('Notificación cancelada.');
					return false;
				}).catch(function(error){
					console.log(error)
					alert(error)
				})
			},
			addToKeywords(){

				if (!this.logged){
					activateAlert = false
					return triggerLoginModal()
				}

				let payload = {
					keyword:this.query
				}		

				axios.post('/api/keyword',payload).then(function(response){
					if(response.data){
						let payload = {
							query: '' + app.query,
						}		
						return axios.post('/api/getRelatedkeywords',payload).then(function(responseIn){
							let finalHtml = '';
							const keywords = responseIn.data.keywords.map(x=>x.keyword);
							responseIn.data.categories.forEach(element => {
								console.log(element.name);
								if(keywords.includes((element.name).toLowerCase())){
									return finalHtml += '<div id="cat-' + element.id + '" onclick="app.addIntKeyword(`' + element.name + '`, `cat-' + element.id + '`)"><div class="btn btn-small m-5 btn-green"><i class="fas fa-check mr-1 font-15 mr-5"></i> <span>' + element.name + ' </span></div></div>';
								}
								finalHtml += '<div id="cat-' + element.id + '" onclick="app.addIntKeyword(`' + element.name + '`, `cat-' + element.id + '`)"><div class="btn btn-small m-5"><i class="fas fa-plus mr-1 font-15 mr-5"></i> <span>' + element.name + ' </span></div></div>';
							});
							responseIn.data.stores.forEach(element => {
								if(keywords.includes((element.name).toLowerCase())){
									return finalHtml += '<div id="store-' + element.id + '" onclick="app.addIntKeyword(`' + element.name + '`, `store-' + element.id + '`)"><div class="btn btn-small m-5 btn-green"><i class="fas fa-check mr-1 font-15 mr-5"></i> <span>' + element.name + ' </span></div></div>';
								}
								finalHtml += '<div id="store-' + element.id + '" onclick="app.addIntKeyword(`' + element.name + '`, `store-' + element.id + '`)"><div class="btn btn-small m-5"><i class="fas fa-plus mr-1 font-15 mr-5"></i> <span>' + element.name + ' </span></div></div>';
							});
							responseIn.data.brands.forEach(element => {
								if(keywords.includes((element.value).toLowerCase())){
									return finalHtml += '<div id="brand-' + element.id + '" onclick="app.addIntKeyword(`' + element.value + '`, `brand-' + element.id + '`)"><div class="btn btn-small m-5 btn-green"><i class="fas fa-check mr-1 font-15 mr-5"></i> <span>' + element.value + ' </span></div></div>';
								}
								finalHtml += '<div id="brand-' + element.id + '" onclick="app.addIntKeyword(`' + element.value + '`, `brand-' + element.id + '`)"><div class="btn btn-small m-5"><i class="fas fa-plus mr-1 font-15 mr-5"></i> <span>' + element.value + ' </span></div></div>';
							});
							return Swal.fire({
								width: 750,
								showConfirmButton: false,
								html: '<div class="success my-25"><div class="icon-fire font-35 pr-10"></div> <div class="ml-5"><div class="font-bold">¡Palabra clave agregada correctamente!</div> <div>Te recomendamos agregar palabras claves relacionadas para que no se te escape ningun chollo, algunas sugerencias:</div></div></div> <div class="overflow-auto overflow-md-visible"><div class="d-flex flex-md-wrap mb-20 m-n5 j-center">' + finalHtml + '</div></div>', 
							});
						}).catch(function(error){
							console.log(error)
							alert(error)
						})
					}

					snackSuccess('Notificación cancelada.');
				}).catch(function(error){
					console.log(error)
					alert(error)
				})

			},
			copyCode(value) {
			  var $temp = $("<input>");
			  $("body").append($temp);
			  $temp.val(value).select();
			  document.execCommand("copy");
			  $temp.remove();
			  snackSuccess('Codigo copiado!')
			},
			handleScroll(){
				var el1 = document.querySelector('.container.py-20').offsetTop;
				var el2 = document.querySelector('.tabs').offsetTop;
				var el3 = document.querySelector('#app > div.container.py-20 > div > div > div:nth-child(2) > ul').offsetTop;
				var el4 = document.querySelector('.tabs.mtop100').offsetTop;
				

				if (window.pageYOffset > el1+el2+el3 && window.pageYOffset < (el1+el2+el3+el4 - 250)) {
					$('#menuFixedTop').show()
				} else {
					$('#menuFixedTop').hide()
				}

				// Tambien cambiar el translate como un click
				var searchMoreBtn = document.querySelector('.search-more').offsetTop + 300;
				if (window.pageYOffset > searchMoreBtn) {
					$('#tab2').prop('checked', true);
				} else {
					$('#tab1').prop('checked', true);
				}
			}
			
		},
		watch:{
			activateAlert(){
				this.addToKeywords()
			}
		},
		created(){
			this.getAmazon();
    		window.addEventListener('scroll', this.handleScroll);
		}
	})
</script>