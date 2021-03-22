@extends('voyager::master')
@section('page_title', 'Panel Inicial')
@section('page_header')
<style type="text/css">
@media (max-width:768px) {

	.item_editor{
		position: fixed;
	    bottom: 0px;
	    width: 100%;
	    left: 0px;
	    overflow: scroll;
	    padding: 0;
	    margin: 0;
	    max-height: 30vh;
	    margin-bottom: 0 !important;
	}
	.item_name{
		padding-top: 40px;
	}
	.left_panel{
		position: relative;
	}

}
</style>
@stop
@section('content')   
   <div class="row select-card" id="app">
   		<div class="col-12 col-md-8">
   			<div v-for="(item,index) of items" class="row">
   				<div class="col-sm-2">
   					<img v-if="item.image != null" :src="item.image" width="100%">
   				</div>
   				<div class="col-sm-10">
	   				<div class="card" :class="{selected: index==selected}">
		   				<div class="card-body">
				   			<div style="font-size: 12px" class="item_name">  
			   					<span v-if="item.category != null" class="pill">
			   						 @{{item.category.name}}
			   					</span> 
			   					<span v-if="item.brand != null" class="pill ml-2">
			   						 @{{item.brand.value}}
			   					</span> 
				   			</div>
				   			<div class="p-2">
			   					@{{ item.name }}
				   			</div>
				   			<div style="font-size: 12px">
				   				@{{  parsedTime(item.created_at) }}
				   			</div>
				   			<button class="btn btn-edit-item" type="button" @click="dissmiss(index)">OK</button>
				   			<button class="btn btn-edit-item" type="button" @click="selectItem(index)" style="right: 65px"><i class="icon voyager-edit"></i></button>
				   			<button class="btn btn-edit-item" type="button" @click="addToPool(index)" style="right: 130px"><i class="icon voyager-font"></i></button>
		   				</div>
	   				</div>
   				</div>
   			</div>
   		</div>
   		<div class="col-12 col-md-4 item_editor">
   			<div class="card left_panel">
   				<div class="card-body" v-show="selected != null && addingToPool == false">
   					<div>
   						<span v-for="(item,index) in breadcrumb" @click="setFromBread(index)" style="cursor: pointer">@{{ item.name }} > </span>
   					</div>
		   			<select class="form-control" v-model="categoryFocusId" style="font-size: 1.5em; height: 54px; background: #8f17fd; color: white;">
		   				<option v-for="category in main_categories" :value="category.id">
		   					@{{ category.name }} 
		   				</option>
		   			</select>

		   			<div v-if="sub_categories.length">
		   				<button v-for="(subcat,index) in sub_categories" @click="selectSubcategory(index)" class="btn m-5" style="font-size: 1.3em">
		   					@{{ subcat.name }}
		   				</button>
		   			</div>
		   			<div class="text-right">
		   				<button class="btn btn-success" v-show="visibleButton()" @click="makeUpdate()"><h4 class="p-7 m-0"><i class="icon voyager-check"></i>Actualizar</h4></button>
		   			</div>
   				</div>
   				<div class="card-body" v-show="addingToPool">
   					Seleccione palabras para agregar al pool de <span class="pill"> @{{categoryFocusName}} </span>
   					<div class="text-center">
   						<h2 id="selection"></h2>
   					</div>
   					<div class="text-center pt-4" id="add_selection">
		   				<button class="btn btn-success m-auto" @click="submitToPool()"><h4 class="p-7 m-0"><i class="icon voyager-check"></i>Agregar a pool</h4></button>
		   				<button class="btn btn-success m-auto" @click="submitToBrands()"><h4 class="p-7 m-0"><i class="icon voyager-check"></i>Es marca</h4></button>
   					</div>
   				</div>
   			</div>
   		</div>
   </div>
    
@stop

@section('css')
{{-- @if(!$dataType->server_side && config('dashboard.data_tables.responsive')) --}}
    {{-- <link rel="stylesheet" href="{{ voyager_asset('lib/css/responsive.dataTables.min.css') }}"> --}}
{{-- @endif --}}
@stop

@section('javascript')
	<script type="text/javascript">
		function getSelectionText() {
		    text = window.getSelection().toString();
		    return text;
		}

		document.onmouseup = document.onkeyup = document.onselectionchange = function() {
		  window.text = getSelectionText()
		  document.getElementById("selection").innerHTML = text 
		  if (text.length) {
		  	document.getElementById("add_selection").style.display = 'block' 
		  }else{
		  	document.getElementById("add_selection").style.display = 'none' 
		  }
		};
	</script>
    <!-- DataTables -->
    {{-- @if(!$dataType->server_side && config('dashboard.data_tables.responsive')) --}}
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.21.0/axios.min.js"></script>

    {{-- <script src="{{ voyager_asset('lib/js/dataTables.responsive.min.js') }}"></script> --}}
    {{-- @endif --}}
    <script type="text/javascript">
    	
	var app = new Vue({
		el: '#app',
		data: {
			items: {!! $items !!},
			categories: {!! $categories !!},
			selected: null,
			selectedItem: null,
			main_categories: [],
			sub_categories: [],
			categoryFocusId: null,
			categoryFocusName: null,
			breadcrumb: [],
			addingToPool:false
		},
		mixins:[],
		methods:{
			reset(){
				this.selected = null
				this.selectedItem =  null
				this.main_categories =  []
				this.sub_categories =  []
				this.categoryFocusId =  null
				this.categoryFocusName =  null
				this.breadcrumb =  []
				this.addingToPool = false
				this.setMainCategories()
			},
			dissmiss(index){

				var item = this.items[index]
				let payload = {
					id : item.id
				}		
				axios.post('/panel/chollo/dismiss',payload).then(function(response){
					app.items.splice(index,1)
				}).catch(function(error){
					console.log(error)
					alert(error)
				})
				this.reset()	
			},
			submitToBrands(){
				if (text.length) {

					let payload = {
						word:text,
						id:this.selectedItem.id
					}		
					axios.post('/panel/brand/single',payload).then(function(response){
						toastr.success(response.data)
						app.successBrand(text)
					}).catch(function(error){
						console.log(error)
						alert(error)
					})
				}
			},
			submitToPool(){
				if (text.length) {

					let payload = {
						category_id:this.categoryFocusId,
						word:text
					}		
					axios.post('/panel/category/single',payload).then(function(response){
						if(typeof(response.data) == 'number'){
							toastr.success('Palabra agregada')
						}else{
							toastr.error(response.data)
						}
					}).catch(function(error){
						console.log(error)
						alert(error)
					})
				}
			},
			addToPool(index){
				this.addingToPool = true
				var item = this.items[index]
				this.categoryFocusId = item.category.id
				this.categoryFocusName = item.category.name
				this.selected = index
				this.selectedItem = item
			},
			successBrand(value){
				if (this.selectedItem.brand == null) {
					this.selectedItem.brand = {
						value: value
					}
				}else{
					this.selectedItem.brand.value = value
				}
				// var item = this.categories.find(it => it.id == this.categoryFocusId)
			},
			success(){
				var item = this.categories.find(it => it.id == this.categoryFocusId)
				this.selectedItem.category = item
			},
			makeUpdate(){
				let payload = {
					item_id:this.selectedItem.id,
					category_id:this.categoryFocusId
				}		
				axios.post('/panel/category/update',payload).then(function(response){
					if(typeof(response.data) == 'number'){
						app.success()
					}else{
						alert(response.data)
					}
				}).catch(function(error){
					console.log(error)
					alert(error)
				})
			},
			visibleButton(){
				if (this.selectedItem != null ) {
					return this.selectedItem.category.id != this.categoryFocusId
				}
				return false
			},
			selectSubcategory(index){
				var bread = this.categories.find(item=> item.id == this.categoryFocusId)
				this.breadcrumb.push(bread)

				var item = this.sub_categories[index]
				this.categoryFocusId = this.sub_categories[index].id 
				this.categoryFocusName = this.sub_categories[index].name 
				this.main_categories = this.categories.filter(it => it.parent_id == item.parent_id )
			},
			selectItem(index){
				var item = this.items[index]
				this.categoryFocusId = item.category.id
				this.categoryFocusName = item.category.name
				this.selected = index
				this.selectedItem = item
				this.main_categories = this.categories.filter(it => it.parent_id == item.parent_id)
				this.addingToPool = false  
			},
			parsedTime(date){
				moment.locale('es')
				return moment(date).locale('es').format('DD MMMM HH:mm')
			},
			setFromBread(index){
				var item = this.breadcrumb[index]
			

				this.main_categories = this.categories.filter(it => it.parent_id == item.parent_id)  
				this.categoryFocusId = item.id
				this.categoryFocusName = item.name
				var length = this.breadcrumb.length - index
				this.breadcrumb.splice(index,length)
			},
			setMainCategories(){
				for(let item of this.categories){
					if (item.parent_id == null) {
						this.main_categories.push(item)
					}
				}
			}
		},
		watch:{
			categoryFocusId(value){
				this.sub_categories = this.categories.filter(item=> item.parent_id == value)
			}
		},
		computed:{

		},
		created(){
			this.setMainCategories()
		}
	})
    
    </script>
@stop
