<?php
define('BLACK',"&#x25cf;");
define('WHITE',"&#x25cb;");

session_start();

//初期化
if(!isset($_SESSION['map'])){
	$_SESSION['map'] = array();
	for($i=1;$i<=8;++$i){ //[x][y]
		for($j=1;$j<=8;$j++){
			$_SESSION['map'][$i][$j] = null;
		}
	}
	$_SESSION['map'][5][4] = BLACK;
	$_SESSION['map'][4][5] = BLACK;
}
//白の初期位置
if(!isset($_SESSION['cp_map'])){
	$_SESSION['cp_map'] = array(44,55);
	foreach($_SESSION['cp_map'] as $cp){
		$data = str_split($cp);
		$_SESSION['map'][$data[0]][$data[1]] = WHITE;
	}
}

if(!isset($_SESSION['count'])){
	$_SESSION['count'] = 0;
}else{
	++$_SESSION['count'];
}

//黒が置ける場所の初期値
if(!isset($_SESSION['can_put'])){
	$_SESSION['can_put'] = array(43,34,65,56);
}
//白が置ける場所の初期値
if(!isset($_SESSION['cp_can_put'])) {
	$_SESSION['cp_can_put'] = array(53,46,35,64);
}

$put = $_POST['put'];
//文字列分解
$data = str_split($put);
echo '<p>You put at (',$data[0],',',$data[1],')</p>';

//置いた所を黒くする
$_SESSION['map'][$data[0]][$data[1]] = BLACK;
//置ける場所から削除
$_SESSION['can_put'] = array_diff($_SESSION['can_put'],array($put));
$_SESSION['cp_can_put'] = array_diff($_SESSION['cp_can_put'],array($put));

//ひっくり返す
for($i=-1;$i<2;++$i){
	for($j=-1;$j<2;++$j){
		if(!isset($_SESSION['map'][$data[0]+$i][$data[1]+$j]) || ($i == 0 && $j == 0)){continue;}
		for($k=1;$k<=8;++$k){
			$x = $i * $k + $data[0];
			$y = $j * $k + $data[1];
			$put = $x.$y;
			if(!isset($_SESSION['map'][$x][$y]) || $_SESSION['map'][$x][$y] == BLACK){break;}
			$_SESSION['map'][$x][$y] = BLACK;
			//白石リストから削除
			$_SESSION['cp_map'] = array_diff($_SESSION['cp_map'],array($put));
			$_SESSION['cp_can_put'] = array_diff($_SESSION['cp_can_put'],array($put));
		}
	}
}
//indexを詰める（念のため）
$_SESSION['can_put'] = array_values($_SESSION['can_put']);
$_SESSION['cp_can_put'] = array_values($_SESSION['cp_can_put']);


//白が置ける場所を探索
//とりあえず無作為における場所を探す
$max = count($_SESSION['cp_can_put']);
$count = 0;
$do_put = null;
$rev_count = 0;
while($count<$max && !$do_put){
	$target = array_rand($_SESSION['cp_can_put']);
	$data = str_split($target);
	//ひっくり返せる場所を探す
	for($i=-1;$i<2;++$i){
		for($j=-1;$j<2;++$j){
			if(!isset($_SESSION['map'][$data[0]+$i][$data[1]+$j]) || ($i == 0 && $j == 0)){continue;}
			$reverse = array(); //ひっくり返すかもしれないもの
			for($k=1;$k<=8;++$k){
				$x = $i * $k + $data[0];
				$y = $j * $k + $data[1];
				$put = $x.$y;
				//石が無い場合はスキップ
				if(!isset($_SESSION['map'][$x][$y])){break;}
				elseif($_SESSION['map'][$x][$y] == BLACK){
					++$rev_count;
					array_push($reverse,$put);
				}
				elseif($rev_count > 0){ //白石発見かつ裏返すものがある
					$do_put = 1;
				}
				//白石リストから削除
				$_SESSION['cp_map'] = array_diff($_SESSION['cp_map'],array($put));
				$_SESSION['cp_can_put'] = array_diff($_SESSION['cp_can_put'],array($put));
			}
		}
	}

	++$count;
}
if($do_put){
	echo '<p>I put at (',$data[0],',',$data[1],').</p>';
}
else{
	echo '<p>I cannot put my stone! Please continue.</p>';
}

echo '<p>There are ',count($_SESSION['cp_map']),' white stones.</p>';

/*
 * あとやること
 * 置ける場所の更新
 * 白（コンピューターの動作）
 * パス機能
 * 全部埋まるかパスが続いたら終了、戦績を計算
 */