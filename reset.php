<?php
session_start();

print_r($_SESSION);
$_SESSION = array();

if(isset($_COOKIE['count'])){
	setcookie('count', '', time() - 1800, '/');
}
if(isset($_COOKIE['put'])){
	setcookie('put', '', time() - 1800, '/');
}

session_destroy();

echo '<p>Data was deleted.</p>';

print_r($_SESSION);

echo '<p><a href="/">Back</a></p>';