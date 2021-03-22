@include('includes.head')
@include('includes.header')
@include('includes.menu')
<div id="app">
	@yield('content')
</div>
@if(!Auth::id())
	<div id="auth">
		@include('includes.login_modal_new')
		@include('includes.confirm_modal')
		@include('includes.photo_modal')
	</div>
@endif
@include('includes.social_modal')
@include('includes.footer')

