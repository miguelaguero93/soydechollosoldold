<script type="text/javascript">
var app = new Vue({
	el: '#app',
	data: {
		items: {!!$items!!},
		ads: {!!$ads!!},
		currentPage: {{$page}},
		pagination: {{$pagination}},
		autoload:{{$autoload}},
		originalAutoload:{{$original_autoload}},
		categoryId:{{$category_id}},
		favorites:{{$favorites}},
		sent:{{$sent}},
		popular:{{$popular}},
		new:{{$new}},
		commented:{{$commented}},
		store: {{$store}},
		brand: {{$brand}},
		user_id: {{$user_id}},
		tag: '{{$tag}}',
		loading: false,
		noMoreToload: false,
		pageBreaks:[],
		logged: {{(int)Auth::id()}}
		  
	},
	created(){
		var items = {!!$items!!};
		this.items = items.sort((a,b) => (a.updated_at < b.updated_at) ? 1 : ((b.updated_at < a.updated_at) ? -1 : 0));
	},
	mixins:[helpersMixin,sidebarMixin,favoriteMixin],
	methods:{

		scrollToFooter(){
			this.loading = true
			window.scrollTo(0,document.body.scrollHeight);
		},
		
		getStoreColor(item){
			if (!item.available) {
				return '#cccccc'
			}else{
			    if(item.until != null) {
			        now = moment().tz('Europe/Madrid').format('YYYY-MM-DD HH:mm:ss')         
			        xxx = now > item.until
			        if (xxx) {
						return '#cccccc'
			        }
			    } 

			}
			return item.store.color
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
		vote(index,operation){
			if (this.logged == 0) {
				return triggerLoginModal()
			}
			item = this.items[index]
			item.votes += operation
			item.user_vote = operation
			snackSuccess('Chollo votado!')

			this.submitVote(item,operation)
		},
		loadMore(scroll){
			if (this.currentPage < this.autoload && !this.noMoreToload) {
				if (this.loading == false) {
					this.loading = true
					let payload = {
						brand:this.brand,
						category_id:this.categoryId,
						page:this.currentPage+1,
						favorites:this.favorites,
						popular:this.popular,
						new:this.new,
						sent:this.sent,
						commented:this.commented,
						store:this.store,
						tag:this.tag,
						user_id:this.user_id
					}	
					axios.post('/api/chollo/getMore',payload).then(function(response){
						app.loadingSuccess(response.data)
					}).catch(function(error){
						console.log(error)
						alert(error)
					})
				}
			}else{
				// console.log('not loading more')
			}
		},
		loadingSuccess(data){
			for(item of data){
				this.items.push(item)
			}
			this.hideLoading()
			if (data.length < this.pagination){
				this.noMoreToload = true
				this.pagination = data.length
			}
			if (data.length) {
				$('#pagination').addClass("loading")
				this.currentPage++
				this.pageBreaks.push({'page':this.currentPage,'scroll':scroll})
				window.history.pushState("object or string", "Title", window.location.pathname+"?page="+this.currentPage);
			}
			setTimeout(insertBanners,1000)
		},
		hideLoading(){
			app.loading = false
			setTimeout(
				function(){
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
					scroll = $(window).scrollTop()
					this.loadMore(scroll)
				}
			}
		},
		copyCode(value) {
		  var $temp = $("<input>");
		  $("body").append($temp);
		  $temp.val(value).select();
		  document.execCommand("copy");
		  $temp.remove();
		  snackSuccess('Codigo copiado!')
		}
		
	},
})

</script>