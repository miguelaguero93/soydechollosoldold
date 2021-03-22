@extends('layouts.main')
@section('content')
    @include('includes.navigation')
    @include('badges.stats_body')
@endsection
@section('scripts')
    @include('badges.js.followers')
@endsection