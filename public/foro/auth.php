<?php
$expire = time() + (86400 * 3000);	
setcookie('flarum_remember', $_GET['token'], $expire, "/");	
header("Location: ".$_GET['redirect']);
