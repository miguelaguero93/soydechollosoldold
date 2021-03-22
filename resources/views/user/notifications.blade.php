@extends('layouts.main')
@section('content')
  	@include('includes.navigation')
  	@include('user.notifications.body')
@endsection
@section('scripts')
@include('user.js.notifications')
@endsection