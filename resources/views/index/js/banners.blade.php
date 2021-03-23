<script type="text/javascript">
	
window.onload = (event) => {

	window_height = $(window).height()
	bottom_offset =  $('footer').height()
	offset = window_height/2
	document.documentElement.scrollTop = 0
	setTimeout(setScrollEvent(),2000)
	insertSocialBanner()
}


function setScrollEvent(){
	$(window).scroll(function () {

	  document_height = $(document).height()
	  scroll = $(this).scrollTop()
	  let x = document_height - window_height - bottom_offset - offset - 200
	  if (x < scroll) {
	  	app.loadMore(scroll)
	  }else{
	  	var breakPoint = app.pageBreaks.length
	  	if (breakPoint>0) {
	  		var lastBreakIndex = breakPoint-1
	  		var lastBreak = app.pageBreaks[lastBreakIndex]
	  		if (scroll < lastBreak.scroll) {
	  			var spliceIndex = (app.items.length) - app.pagination
	  			app.items.splice(spliceIndex,app.pagination) 
	  			app.pageBreaks.splice(lastBreakIndex,1)
	  			app.currentPage = app.currentPage-1
	  			if (app.currentPage > 1) {
	  				window.history.pushState("object or string", "Title", window.location.pathname+"?page="+app.currentPage)
	  			}else{
	  				window.history.pushState("object or string", "Title", window.location.pathname)
	  			}
	  		}
	  	}
	  }	   
	})
}	

function insertSocialBanner(){
	
	offer_items = document.getElementsByClassName('item')
	banner_position = {{setting('site.social_banner')}}
	after_position = banner_position - 2

	if (offer_items.length > after_position) {

		offer_items[after_position].insertAdjacentHTML('afterend','<div class="item item-ads"> <div class="item-ads-content"> <div class="font-bold font-18"> ¡No te pierdas nada! </div> <div class="font-16"> Te avisamos de los <br> chollos por tus canales <br> preferidos </div> </div> <div class="item-ads-share"> <div class="item-ads-share-icons"> <a href="https://api.whatsapp.com/send?phone={{setting('site.whatsapp')}}" target="_blank" class="link-none"> <div class="bg-whatsapp item-ads-share-button hover"> <div class="item-ads-share-text"> <div class="siguenos bg-whatsapp"> <span>Síguenos en</span> </div> <div class="font-bold font-16 mt-5 item-ads-share-name"> WhatsApp </div> </div> <div class="item-ads-share-icon bg-whatsapp"> <span class="icon-whatsapp"></span> </div> <div class="font-bold font-16 item-ads-share-copy"> WhatsApp </div> </div> </a> <a href="{{setting('site.telegram')}}" target="_blank" class="link-none"> <div class="bg-telegram item-ads-share-button hover"> <div class="item-ads-share-text"> <div class="siguenos bg-telegram"> <span>Síguenos en</span> </div> <div class="font-bold font-16 mt-5 item-ads-share-name"> Telegram </div> </div> <div class="item-ads-share-icon bg-telegram"> <span class="icon-telegram"></span> </div> <div class="font-bold font-16 item-ads-share-copy"> Telegram </div> </div> </a> <a href="{{setting('site.facebook')}}" target="_blank" class="link-none"> <div class="bg-facebook item-ads-share-button hover"> <div class="item-ads-share-text"> <div class="siguenos bg-facebook"> <span>Síguenos en</span> </div> <div class="font-bold font-16 mt-5 item-ads-share-name"> Facebook </div> </div> <div class="item-ads-share-icon bg-facebook"> <span class="icon-facebook"></span> </div> <div class="font-bold font-16 item-ads-share-copy"> Facebook </div> </div> </a> <a href="{{setting('site.twitter')}}" target="_blank" class="link-none"> <div class="bg-twitter item-ads-share-button hover"> <div class="item-ads-share-text"> <div class="siguenos bg-twitter"> <span>Síguenos en</span> </div> <div class="font-bold font-16 mt-5 item-ads-share-name"> Twitter </div> </div> <div class="item-ads-share-icon bg-twitter"> <span class="icon-twitter"></span> </div> <div class="font-bold font-16 item-ads-share-copy"> Twitter </div> </div> </a> </div> </div> </div>')
	}
	insertBanners()
}

function insertBanners(){

	if (app.ads.length) {

		for (var ad = app.ads.length - 1; ad >= 0; ad--) {
		
			offer_items = document.getElementsByClassName('item')
			banner_position = app.ads[ad].position
			after_position = banner_position - 2
			if (offer_items.length > after_position && offer_items.length > 0) {
				if (is_vertical) {
					banner_image = app.ads[ad].image_vertical 
				}else{
					banner_image = app.ads[ad].imagen_horizontal 
				}

				if (banner_image.length) {
					banner_content = '<div class="item item-design"><a href="'+app.ads[ad].link+'"><img src="{{asset("storage/")}}/'+banner_image+'" class="w-100 d-block" alt="'+app.ads[ad].name+'"></a></div>'
					if (after_position >= 0) {
						offer_items[after_position].insertAdjacentHTML('afterend',banner_content)
					}else{
						offer_items[0].insertAdjacentHTML('beforebegin',banner_content)
					}
				}
				app.ads.splice(ad,1)

			}
		}
	}

}

is_vertical = document.getElementsByClassName('content-column').length == 1
</script>