<script src="https://unpkg.com/vue-select@latest"></script>
<link rel="stylesheet" href="https://unpkg.com/vue-select@latest/dist/vue-select.css">
<script type="text/javascript">
Vue.component('v-select', VueSelect.VueSelect)

var app = new Vue({
	el: '#app',
	data: {
		items: {!! $items !!},
		options: {!! $options !!},
		cupons: {!! $cupons !!},
		itemSelected: null,
		top5: 'asd',
		ano: '2020',
		month: 'Enero',
		months: ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
	},
	methods: {
		normalize: function(str) {
			var from = "ÃÀÁÄÂÈÉËÊÌÍÏÎÒÓÖÔÙÚÜÛãàáäâèéëêìíïîòóöôùúüûÑñÇç", 
				to   = "AAAAAEEEEIIIIOOOOUUUUaaaaaeeeeiiiioooouuuunncc",
				mapping = {};
		
			for(var i = 0, j = from.length; i < j; i++ )
				mapping[ from.charAt( i ) ] = to.charAt( i );
		
			var ret = [];
			for( var i = 0, j = str.length; i < j; i++ ) {
				var c = str.charAt( i );
				if( mapping.hasOwnProperty( str.charAt( i ) ) )
					ret.push( mapping[ c ] );
				else
					ret.push( c );
			}      
			return ret.join( '' );
		
		},
	},
	created(){
		const options = {!! $options !!};
		this.options = options.map((x) => {
			return x.trim().toLowerCase().replace(/\w\S*/g, (w) => (w.replace(/^\w/, (c) => c.toUpperCase())));
		});		
		const populars = {!! $popular1 !!};
		let top5 = '';
		for (let index = 0; index <= 4; index++) {
			if(index !== 4){
				top5 = ' ' + top5 + populars[index]['value'].toString() + ', ';
			}else{
				top5 = ' ' + top5 + populars[index]['value'].toString();
			}
		}
		this.top5 = top5;
		var d = new Date();
		this.ano = d.getFullYear();
		this.month = this.months[d.getMonth()];
	},
	watch:{
		itemSelected(value){
			showBlanket()
			if (value != null) {
				window.location.href = '/marca/'+value.toLowerCase().replaceAll(' ','-')
			}
		}
	}
})
</script>	