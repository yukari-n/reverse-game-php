<?php
define('BLACK',"&#x25cf;");
define('WHITE',"&#x25cb;");

//初期化
$map = array();
for($i=1;$i<=8;++$i){ //[x][y]
	for($j=1;$j<=8;$j++){
		$map[$i][$j] = null;
	}
}
$map[5][4] = BLACK;
$map[4][5] = BLACK;
$map[4][4] = WHITE;
$map[5][5] = WHITE;

session_start();

if(!isset($_SESSION['count'])) {
	$_SESSION['count'] = 0;
}else{
	++$_SESSION['count'];
}
/*
if(!isset($_SESSION['put'])) {
	$_SESSION['put'] = 0;
}else{
	++$_SESSION['put'];
}*/
