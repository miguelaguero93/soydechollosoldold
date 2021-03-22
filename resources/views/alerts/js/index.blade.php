<!-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script> -->
<script type="text/javascript">
	var app = new Vue({
		el: '#app',
		data: {
			logged: {!! $logged !!}
		},
		mixins:[keywordsMixin],
		created(){
			// Swal.fire({
			// 	title: 'Error!',
			// 	text: 'Do you want to continue',
			// 	icon: 'error',
			// 	confirmButtonText: 'Cool'
			// })
		}
	});
</script>