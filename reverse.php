<?php
define('BLACK',"&#x25cf;");
define('WHITE',"&#x25cb;");

session_start();

//初期化
if(!isset($_SESSION['map'])) {
	$_SESSION['map'] = array();
	for($i=1;$i<=8;++$i){ //[x][y]
		for($j=1;$j<=8;$j++){
			$_SESSION['map'][$i][$j] = null;
		}
	}
	$_SESSION['map'][5][4] = BLACK;
	$_SESSION['map'][4][5] = BLACK;
	$_SESSION['map'][4][4] = WHITE;
	$_SESSION['map'][5][5] = WHITE;
}

if(!isset($_SESSION['count'])) {
	$_SESSION['count'] = 0;
}else{
	++$_SESSION['count'];
}

//黒が置ける場所の初期値
if(!isset($_SESSION['can_put'])) {
	$_SESSION['can_put'] = array(43,34,65,56);
}

$put = $_POST['put'];
//文字列分解
$data = str_split($put);
echo '<p>You put at (',$data[0],',',$data[1],')</p>';

//置いた所を黒くする
$_SESSION['map'][$data[0]][$data[1]] = BLACK;

//ひっくり返す
for($i=-1;$i<2;++$i){
	for($j=-1;$j<2;++$j){
		if(!isset($_SESSION['map'][$data[0]+$i][$data[1]+$j]) || ($i == 0 && $j == 0)){continue;}
		for($k=1;$k<=8;++$k){
			$x = $i * $k + $data[0];
			$y = $j * $k + $data[1];
			if(!isset($_SESSION['map'][$x][$y]) || $_SESSION['map'][$x][$y] == BLACK){break;}
			$_SESSION['map'][$x][$y] = BLACK;
		}
	}
}

//置ける場所から削除
$_SESSION['can_put'] = array_diff($_SESSION['can_put'],array($put));
//indexを詰める
$_SESSION['can_put'] = array_values($_SESSION['can_put']);

/*
 * あとやること
 * 置ける場所の更新
 * 白（コンピューターの動作）
 */

echo '<p>I put at (hoge,hoge)</p>';