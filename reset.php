<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8" />
<meta name="robots" content="noindex" />
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta http-equiv="imagetoolbar" content="no" />
<meta name="viewport" content="width=device-width,initial-scale=1" />
<meta name="format-detection" content="telephone=no,address=no,email=no" />
<title>はさんでひっくりかえすやつ</title>
<link href="https://ajax.aspnetcdn.com/ajax/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" />
<link href="style.css" rel="stylesheet" />
</head>
<body>
<h1>はさんでひっくりかえすやつ</h1><?php
session_start();

//print_r($_SESSION);
$_SESSION = array();

if(isset($_COOKIE['count'])){
	setcookie('count', '', time() - 1800, '/');
}
if(isset($_COOKIE['put'])){
	setcookie('put', '', time() - 1800, '/');
}

session_destroy();

echo '<p>(・ω・)りせっとしました</p>';

//print_r($_SESSION);

echo '<p><a href="/">戻る</a></p>';
?></body>
</html>