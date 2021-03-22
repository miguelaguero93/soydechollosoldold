<script type="text/javascript">
function claimPrice(id){
	let payload = {
		id:id
	}		
	showBlanket()
	axios.post('/api/claim',payload).then(function(response){
		if(typeof(response.data) == 'number'){
			if (response.data == 0) {
				hideBlanket()
				triggerLoginModal();
			}else{
				window.location.reload()
			}
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

function copyCode() {
  var $temp = $("<input>");
  $("body").append($temp);
  $temp.val($("#code").text()).select();
  document.execCommand("copy");
  $temp.remove();
  snackSuccess('Codigo copiado!')
}
</script>