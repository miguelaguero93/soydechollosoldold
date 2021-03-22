<script type="text/javascript">
image = document.getElementById('image')

function uploadOwnFile(){
	image.click()
}

image.addEventListener('change', function(event){
	
	file = event.target.files[0]
	let img = new Image()
	img.src = URL.createObjectURL(file)
	img.onload = () => {
	   if (img.width > 2000 || img.height > 2000){
	   		snackError('Tu imagen no debe ser mayor a 2000px de ancho o alto')
	   		return	
	   } 

	   app.own_image = true
	   app.main_image = img.src
	}

})
</script>