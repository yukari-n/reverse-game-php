<?php
//上手く動いてない
print_r($_SESSION);
$_SESSION = array();

if(isset($_COOKIE['count'])){
	setcookie('count', '', time() - 1800, '/');
}
if(isset($_COOKIE['putted'])){
	setcookie('putted', '', time() - 1800, '/');
}

session_destroy();

echo '<p>Data was deleted.</p>';

print_r($_SESSION);

echo '<p><a href="/">Back</a></p>';