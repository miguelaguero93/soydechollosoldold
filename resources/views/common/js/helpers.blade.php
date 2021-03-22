<script type="text/javascript">
	
	var helpersMixin = {
			
			methods:{
				namePreviewChollo(value){
					
					if (typeof screen_vertical == 'undefined') {
						
						var screen_width = document.getElementsByClassName('content-vertical').length
						
						if (screen_width == 0) {
							screen_vertical = true
						}else{
							screen_vertical = false
						}

					}
					

					
					if (screen_vertical) {
						length = 40
					}else{
						length = 70
					}


					if (value.length > length) {
						return value.substring(0, length)+'...'
					}

					return value
				},

				descriptionPreviewChollo(item){
					value = item.description
					if (value != null) {

						if (typeof screen_vertical  == 'undefined') {
							var screen_width = document.getElementsByClassName('content-vertical').length
							if (screen_width == 0) {
								screen_vertical = true
							}else{
								screen_vertical = false
							}
						}

						// var stripped = value
						var stripped = value.replace(/<[^>]*>?/gm, ' ').replace(/&nbsp;/gi,' ')
						stripped = stripped.trim()

						if (screen_vertical) {
							stripped = stripped.substring(0, 60)
						}else{
							if (stripped.length > 150) {
								stripped = stripped.substring(0, 150) + '... <a href="/'+ item.slug +'">leer más</a>'
							}
						}
						
						var txt = document.createElement('textarea');
						txt.innerHTML = stripped;
						return txt.value;
					}

				},

				triggerSocialModal(url){
					var link = 'https://soydechollos.com'+url
					var modal = document.getElementById("socialModal");
						modal.style.display = "block";
					$("#share").jsSocials({
					      url: link,
					      shareIn: "popup",
					      showLabel: false,
					      showCount: false,
					      shares: ["facebook","twitter","whatsapp","messenger","telegram","email"]
					});
				},
				cholloLinkComments(item){
					if (item != null) {
						return '/'+item.slug+'#comments'
					}
				},
				maxlength(value,length){
					var value = value.replace(/<[^>]*>?/gm, '');
					if (value.length > length) {
						return value.substring(0, length)+'...'
					}
					return value
				},
				parsedTime(date){
					moment.locale('es')
					return moment(date).locale('es').format('DD MMMM HH:mm')

				},
				cholloLink(item){ 
					return '/'+item.slug
				},
				cholloLinkv2(slug){ 
					return '/'+slug
				},

				userLink(item){
					var url = item.name.replace('á', 'a').replace('é','e').replace('í', 'i').replace('ó', 'o').replace('ú', 'u')
					url = url.replace(/[^\w ]/g,'')
					url = url.replace(/ /g,'-');
					url = url.toLowerCase() 
					if (window.innerWidth < 720) {
						return '/estadisticas/'+item.id+'/'+url
					}
					return '/medallas/'+item.id+'/'+url
				},

				storeLink(item){
					return '/tienda/'+item.slug
				},
				categoryLink(slug){
					return '/categoria/'+slug
				},
				
				namePreview(value,length){
					if (value.length > length) {
						return value.substring(0, length)+'...'
					}
					return value
				},

				nameCommentPreview(item,length){
					if (item.chollo != null) {
						if (item.chollo.name.length > length) {
							return item.chollo.name.substring(0, length)+'...'
						}
						return item.chollo.name
					}
				},
				descriptionPreview(value){
					if (value != null) {
						var string = value.trim().substring(0, 300)
						var stripped = string.replace(/<[^>]*>?/gm, '').replace(/&nbsp;/gi,'')
						var txt = document.createElement('textarea');
						txt.innerHTML = stripped;
						return txt.value;
					}

				}

			},
			filters: {
				numberformat: function(value){
			      	var price =  new Intl.NumberFormat('es-ES', { style:'currency', currency:'EUR'}).format(value).replace(',00', '').replace(/\s/g, '')
					
			      	return price
			    },
			    relativeTime(value){
		        	return moment(value).tz('Europe/Madrid').locale('es').fromNow()
		    	},

		    	relativeTimeCoupon(value){
		    		var expiry = moment(value)
		    		var now = moment()
		    		if ( expiry > now ) {
		        		return 'Caduca '+expiry.locale('es').fromNow()
		    		}else{
		        		return 'Caducado '+expiry.locale('es').fromNow()
		    		}
		    	},

		    	capitalize(value){
					if (value != null) {
						return value.charAt(0).toUpperCase() + value.slice(1)
					}
				}	
			}
	}

</script>