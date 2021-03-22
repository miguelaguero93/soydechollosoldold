<script type="text/javascript">
var app = new Vue({
	el: '#app',
	data: {
		items: {!!$items!!},
		sent: {!!$sent!!},
		currentPage: {{$page}},
		pagination: {{$pagination}},
		autoload:{{$autoload}},
		originalAutoload:{{$original_autoload}},
		website: {{$website}},
		loading: false,
		noMoreToload: false,
		voted: [],
		pageBreaks:[]
	},
	mixins:[helpersMixin],
	methods:{
		checkAvailability(item){

		
		    if(item.until != null) {
		        now = moment().tz('Europe/Madrid').format('YYYY-MM-DD HH:mm:ss') 
		        xxx = now > item.until
		        if (xxx) {
		        	return true;
		        }
		    } 

			return false;
		},
		loadMore(){
			if (this.currentPage < this.autoload && !this.noMoreToload) {
				if (this.loading == false) {
					$('#pagination').addClass("loading")
					this.loading = true
					
					// console.log('page is' + this.currentPage)
					let payload = {
						page:this.currentPage+1,
						sent:this.sent,
						website:this.website
					}	
					axios.post('/api/cupon/getMore',payload).then(function(response){
						app.loadingSuccess(response.data)	
					}).catch(function(error){
						console.log(error)
						alert(error)
					})
				}
			}else{
				console.log('not loading more')
			}
		},
		loadingSuccess(data){
			for(item of data){
				this.items.push(item)
			}
			this.hideLoading()
			if (data.length < this.pagination){
				this.noMoreToload = true
			}
			if (data.length) {
				this.currentPage++
				this.pageBreaks.push({'page':this.currentPage,'scroll':scroll})
				window.history.pushState("object or string", "Title", window.location.pathname+"?page="+this.currentPage);
			}
		},
		hideLoading(){
			setTimeout(
				function(){
					app.loading = false
					$('#pagination').removeClass("loading")
				},3000
			)
		},
		goToPage(page){
			// is not the actual page but in relation to the currentPage
			if (page != 1) {

				showBlanket()
				page = this.currentPage+page
				window.location.href = window.location.pathname+"?page="+page

			}else{

				if (this.currentPage+page > this.autoload) {
					this.autoload = this.autoload + this.originalAutoload 
					this.loadMore()
				}
			}
		},
		showCode(item){
			item.code_visible = true 
			let payload = {
				id:item.id
			}
			item.copied = item.copied+1
			axios.post('/api/cupon/copied',payload)
		},
		copyCode(code) {
		  var $temp = $("<input>");
		  $("body").append($temp);
		  $temp.val(code).select();
		  document.execCommand("copy");
		  $temp.remove();
		  snackSuccess('Codigo '+ code +' copiado!')
		},

		likeCoupon(item,action) {

			if (this.voted.indexOf(item.id) == -1) {
				this.voted.push(item.id)
				if (action == 1) {
					item.works = item.works+1 		
				}else{
					item.not_work = item.not_work+1 		
				}

				let payload = {
					action:action,
					id:item.id
				}
				axios.post('api/cupon/liked',payload);
				snackSuccess('Gracias por el feedback!')
			}
		}
	}
})

$(document).ready(function(){
	window_height = $(window).height()
	bottom_offset =  $('footer').height()
	offset = window_height/2
	setTimeout(setScrollEvent(),2000)

})

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

</script>