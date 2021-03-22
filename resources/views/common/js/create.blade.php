<script type="text/javascript">
	var createMixin = {
			data: {
				site_url: '',
				name: '',
				coupon: '',
				description: '',
				selected_category: null,
				start: '',
				end: '',
				start_time: '00:00',
				end_time: '23:59',
			    all_spain: true,
			    selected_provinces: provinces,
			    sexual_content: false,
			    categories: {!! $categories !!},
				error_images: null,
			    provinces: provinces,
			    site_name: ''

			},
			methods:{
				deleteStartDate(){
					this.start = ''
					document.getElementById('datepickerstart').value = null
				},

				deleteEndDate(){
					this.end = ''
					document.getElementById('datepickerend').value = null
				},
					
				descriptionPreview(length){
					var stripped = this.description.replace(/<[^>]*>?/gm, '');
					return stripped.substring(0, length)+'...'
				},
				currentTime(){
					moment.locale('es')
					return moment().locale('es').format('DD MMMM HH:mm')

				},
				end_time(){
					validateDates()
				},
				start_time(){
					validateDates()
				},
				selectCategory(index){
					this.selected_category = this.categories[index]
				},
				unSelectCategory(){
					this.selected_category = null
					this.realCat=null
				}
			},
			computed:{
				
				number_of_provinces(){
					return this.selected_provinces.length
				}

			}
	}

</script>