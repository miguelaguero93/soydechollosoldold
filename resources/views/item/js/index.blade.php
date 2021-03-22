<script type="text/javascript">
var app = new Vue({
	el: '#app',
	data: {
		item: {!! $item !!},
		voted: {{$voted}},
		related: [],
		logged: {!! $logged !!},
		favorite: {{$favorite}},
		item_comments: {!! $item_comments !!},
		newComment: ''
	},
	mixins:[helpersMixin,sidebarMixin,keywordsMixin],
	methods:{
		
		toggleReportModal(){
			if (!this.logged) {
				return triggerLoginModal()
			}
			var modal = document.getElementById("reportModal")
      		var state = modal.style.display;
      		if (state == 'flex') {
      			modal.style.display = 'none'
      		}else{
      			modal.style.display = 'flex'
      		}
		},
		checkAvailability(){

			if (!this.item.available) {
				return true
			}else{
			    if(this.item.until != null) {
			        now = moment().tz('Europe/Madrid').format('YYYY-MM-DD HH:mm:ss')         
			        xxx = now > this.item.until
			        if (xxx) {
			        	return true;
			        }
			    } 

			}
			return false;
		},
		getStoreColor(){
			if (!this.item.available) {
				return '#cccccc'
			}else{
			    if(this.item.until != null) {
			        now = moment().tz('Europe/Madrid').format('YYYY-MM-DD HH:mm:ss')         
			        xxx = now > this.item.until
			        if (xxx) {
						return '#cccccc'
			        }
			    } 

			}
			return this.item.store.color
		},
		reportOffer(id){
			
			axios.get('/api/report/'+id)
			.then(function(response){
				snackSuccess(response.data)
			}).catch(function(error){
				snackWarning(error)
				console.log(error)
			})
			this.toggleReportModal()
		},
		
		triggerSocialModal(){
			var modal = document.getElementById("socialModal");
				modal.style.display = "block";
			$("#share").jsSocials({
			      shareIn: "popup",
			      showLabel: false,
			      showCount: false,
			      shares: ["facebook","twitter","whatsapp","messenger","telegram","email"]
			});

		},

		voteComment(item,value){
			if (!this.logged) {
				return triggerLoginModal()
			}
			let payload = {
				item_id:item.id,
				value:value
			}		
			axios.post('/api/comment/like',payload).then(function(response){
				app.updateVotes(response.data,item)
			}).catch(function(error){
				console.log(error)
				alert(error)
			})

		},
		updateVotes(comment,item){

			item.vote = comment.vote 
			item.plus = comment.plus
			item.minus = comment.minus
			
		},
		
		pushReplyComment(comment,index){
			item = this.item_comments[index] 
			comment.user = this.logged
			item.children.unshift(comment)
			item.reply = ''
			item.replying = false
			item.visibleChildren = true
		},
		showChildren(index){
			console.log(index)
			this.item_comments[index].visibleChildren =  !this.item_comments[index].visibleChildren
		},
		reply(index){
			if (!this.logged) {
				return triggerLoginModal()				
			}
			this.item_comments[index].replying =  !this.item_comments[index].replying
		},
		submitReplyComment(index){
			var parent_id = this.item_comments[index].id 
			var parent_user_id = this.item_comments[index].user_id 
			var comment = this.item_comments[index].reply 
			if (comment.length > 3) {
				let payload = {
					comment:comment,
					item_id:this.item.id,
					item_name:this.item.name,
					item_slug:this.item.slug,
					parent_id:parent_id,
					parent_user_id:parent_user_id
				}		
				axios.post('/api/comment/save',payload).then(function(response){
					app.pushReplyComment(response.data,index)
				}).catch(function(error){
					console.log(error)
					alert(error)
				})
			}
		},
		pushComment(comment){
			comment.user = this.logged
			this.item_comments.unshift(comment)
			this.newComment = ''
		},
		submitComment(){
			if (this.newComment.length > 3) {
				let payload = {
					comment:this.newComment,
					item_id:this.item.id,
					item_user_id:this.item.user_id,
					item_name:this.item.name,
					item_slug:this.item.slug
				}		
				axios.post('/api/comment/save',payload).then(function(response){
					app.pushComment(response.data)
				}).catch(function(error){
					console.log(error)
					alert(error)
				})
			}
		},
		triggerLogin(){
			$("#comment_area").prop('disabled',true)
			triggerLoginModal()
		},
		addFavorite(){
			item = this.item
			let payload = {
				id:item.id,
				value:this.favorite	
			}	
			axios.post('/api/chollo/favorite',payload).then(function(response){
				if(typeof(response.data) == 'string'){
					snackSuccess(response.data)
					app.favorite = !app.favorite
				}else{
					triggerLoginModal()
				}
			}).catch(function(error){
				console.log(error)
				alert(error)
			})
		},
		vote(operation){
			if (this.logged == 0) {
				return triggerLoginModal()
			}
			var item = this.item				
					
			let payload = {
				operation:operation,
				id:item.id
			}		
			axios.post('/api/chollo/vote',payload).then(function(response){
				if(typeof(response.data) == 'number'){
					snackSuccess('Chollo votado!')
					item.votes += operation
					this.voted = operation
				}else{
					snackError(response.data)
				}
			}).catch(function(error){
				console.log(error)
				alert(error)
			})
		},
		loadRelated(){
			let payload = {
				category_id : this.item.category_id,
				item_id: this.item.id
			}
			axios.post('/api/getrelated',payload).then(function(response){
				if(typeof(response.data) == 'object'){
					app.related = response.data
				}
			}).catch(function(error){
				console.log(error)
				alert(error)
			})
		}
	},
	created(){
		this.loadRelated()
	}
})

function copyCode() {
  var $temp = $("<input>");
  $("body").append($temp);
  $temp.val($("#code").text()).select();
  document.execCommand("copy");
  $temp.remove();
  snackSuccess('Codigo copiado!')
}

@if(isset($_REQUEST['parent_id']))
	$(function(){
		parent_id = {{$_REQUEST['parent_id']}}
		show_replies = app.item_comments.find(item => item.id == parent_id)
		if (show_replies != null) {
			show_replies.visibleChildren = true
		}
	})
@endif

$(function(){
	if (window.location.hash == '#comments') {
		var elmnt = document.getElementById("comments");
		elmnt.scrollIntoView();
	}
})
</script>