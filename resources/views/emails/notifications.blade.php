<div style="max-width: 600px; margin: auto;">
	<div style="text-align: center; background-color: #0038aa">
		<img src="{{asset('images/logo.png')}}" style="padding: 10px">
	</div>
	<div style="padding: 50px 25px; font-size: 16px">
			Hola {{$user->name}}! 
			<br>
			<br>
			{!! $msg !!}
	</div>
	<div>
		<a href="{{url($link)}}" style="border: 1px solid #0038aa; background-color: #0038aa; display: block; color: #fff; border-radius: 4px; font-size: 15px; overflow: hidden; white-space: nowrap; padding-top: 2px; text-decoration: none; width: 270px; margin: auto; text-align: center; padding: 14px;">Ver en Soydechollos</a>
	</div>
</div>