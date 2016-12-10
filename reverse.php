<?php
define('BLACK',"&#x25cf;");
define('WHITE',"&#x25cb;");

session_start();

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

if(!isset($_SESSION['count'])) {
	$_SESSION['count'] = 0;
}else{
	++$_SESSION['count'];
}

$put = $_POST['put'];
echo '<p>You put at ',$put,'</p>';

//文字列分解
$data = str_split($put);

//置いた所を黒くする
$map[$data[0]][$data[1]] = BLACK;

for($i=-1;$i<2;++$i){
	for($j=-1;$j<2;++$j){
		if(!isset($map[$data[0]+$i][$data[1]+$j]) || ($i == 0 && $j == 0)){continue;}
		for($k=1;$k<=8;++$k){
			$x = $i * $k + $data[0];
			$y = $j * $k + $data[1];
			if(!isset($map[$x][$y]) || $map[$x][$y] == BLACK){break;}
			$map[$x][$y] = BLACK;
		}
	}
}







//置ける場所から削除
$can_put = array_diff($can_put,array($put));
//indexを詰める
//$can_put = array_values($can_put);


/*
if(!isset($_SESSION['put'])) {
	$_SESSION['put'] = 0;
}else{
	++$_SESSION['put'];
}*/
