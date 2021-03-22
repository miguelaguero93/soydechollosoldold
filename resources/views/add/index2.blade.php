@extends('layouts.main')
@section('content')
  @include('includes.breadcrumbs')
  @include('add.body2')
@endsection
@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/lodash.js/4.17.15/lodash.min.js"></script>
@include('add.js.editor')
@if($admin == 1)
<script src="https://unpkg.com/vue-select@latest"></script>
<link rel="stylesheet" href="https://unpkg.com/vue-select@latest/dist/vue-select.css">
@endif
@include('add.js.selectors')
@include('common.js.helpers')
@include('common.js.create')
@include('add.js.index')
@include('add.js.dates')
@include('add.js.scroll')	
@include('add.js.image')
@endsection
