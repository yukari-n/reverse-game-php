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

//黒が置ける場所の初期値
$can_put = array(43,34,65,56);


session_start();

if(!isset($_SESSION['count'])) {
	$_SESSION['count'] = 0;
}else{
	++$_SESSION['count'];
}

$putted = $_POST['putted'];

//文字列分解
$data = str_split($putted);

//置いた所を黒くする
$map[$data[0]][$data[1]] = BLACK;
//置ける場所から削除
$can_put = array_diff($can_put,$putted);
//indexを詰める
$can_put = array_values($can_put);


/*
if(!isset($_SESSION['put'])) {
	$_SESSION['put'] = 0;
}else{
	++$_SESSION['put'];
}*/
