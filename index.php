<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8" />
<meta name="robots" content="noindex" />
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta http-equiv="imagetoolbar" content="no" />
<meta name="viewport" content="width=device-width,initial-scale=1" />
<meta name="format-detection" content="telephone=no,address=no,email=no" />
<title>under construction</title>
<link href="https://fonts.googleapis.com/earlyaccess/sawarabigothic.css" rel="stylesheet" />
<link href="style.css" rel="stylesheet" />
</head>
<body>
<h1>under construction</h1>
<?php
define('BLACK','&#x25cf;');
define('WHITE','<span class="white">&#x25cf;</span>');//&#x25cb;

session_start();

require_once('reverse.php');
require_once('search.php');

//初期化
if(!isset($_SESSION['map'])){
	$_SESSION['map'] = array();
	for($i=1;$i<=8;++$i){ //[x][y]
		for($j=1;$j<=8;$j++){
			$_SESSION['map'][$i][$j] = null;
		}
	}
}
//黒の初期位置
if(!isset($_SESSION['pl_map'])){
	$_SESSION['pl_map'] = array(54,45);
	foreach($_SESSION['pl_map'] as $pl){
		$data = str_split($pl);
		$_SESSION['map'][$data[0]][$data[1]] = BLACK;
	}
}
//白の初期位置
if(!isset($_SESSION['cp_map'])){
	$_SESSION['cp_map'] = array(44,55);
	foreach($_SESSION['cp_map'] as $cp){
		$data = str_split($cp);
		$_SESSION['map'][$data[0]][$data[1]] = WHITE;
	}
}

//黒が置ける場所の初期値
if(!isset($_SESSION['pl_can_put'])){
	$_SESSION['pl_can_put'] = array(43,34,65,56);
}
//白が置ける場所の初期値
if(!isset($_SESSION['cp_can_put'])) {
	$_SESSION['cp_can_put'] = array(53,46,35,64);
}

if(!isset($_SESSION['count'])){
	$_SESSION['count'] = 0;
}
else{ //if($_POST['put'])
	++$_SESSION['count'];
}

if($_SESSION['count'] > 0){
	echo '<p>プレイヤーが置く前</p><table>';
	for($j=0;$j<=8;++$j){//x,yにするため表示はiとjが逆
		echo '<tr>';
		for($i=0;$i<=8;++$i){
			echo '<td>';
			$coord = $i.$j;
			if($i == 0){
				echo '<span class="white">',$j,'</span>';
			}
			elseif($j == 0){
				echo '<span class="white">',$i,'</span>';
			}
			elseif(!isset($_SESSION['map'][$i][$j])){
				echo '　';
			}
			else{
				echo $_SESSION['map'][$i][$j];
			}
			echo '</td>';
		}
		echo '</tr>';
	}
	echo '</table>';

	$put = $_POST['put'];
	//ひっくり返す
	$do_reverse = reverse_stone('B',$put);
	$_SESSION['pl_map'] = $do_reverse[1];
	$_SESSION['cp_map'] = $do_reverse[2];
	$_SESSION['pl_can_put'] = $do_reverse[3];
	$_SESSION['cp_can_put'] = $do_reverse[4];

	echo '<p>プレイヤーが置いた時点</p><table>';
	for($j=0;$j<=8;++$j){//x,yにするため表示はiとjが逆
		echo '<tr>';
		for($i=0;$i<=8;++$i){
			echo '<td>';
			$coord = $i.$j;
			if($i == 0){
				echo '<span class="white">',$j,'</span>';
			}
			elseif($j == 0){
				echo '<span class="white">',$i,'</span>';
			}
			elseif(!isset($_SESSION['map'][$i][$j])){
				echo '　';
			}
			else{
				echo $_SESSION['map'][$i][$j];
			}
			echo '</td>';
		}
		echo '</tr>';
	}
	echo '</table>';

	//コンピューターが置ける場所を探索
	$_SESSION['cp_can_put'] = search_can_put($_SESSION['pl_map'],$_SESSION['cp_can_put']);
	//とりあえず無作為における場所を探す
	$max = count($_SESSION['cp_can_put']);
	if($max == 0 || count($_SESSION['cp_map']) == 0){
		echo '<p style="color:#f00;">You win!</p>';
	}
	else{
		//echo '<p>I could put ',$max,' places.</p>';
		//print_r($_SESSION['cp_can_put']);
		$count = 0;
		$do_reverse = null;
		while($count < $max && !isset($do_reverse[0])){
			$target_id = array_rand($_SESSION['cp_can_put']);
			$target = $_SESSION['cp_can_put'][$target_id];
			$data = str_split($target);
			echo '<p>I choose (',$data[0],',',$data[1],').</p>';
			//ひっくり返せる場所を探す
			$do_reverse = reverse_stone('W',$target);
			$_SESSION['cp_map'] = $do_reverse[1];
			$_SESSION['pl_map'] = $do_reverse[2];
			$_SESSION['cp_can_put'] = $do_reverse[3];
			$_SESSION['pl_can_put'] = $do_reverse[4];
			++$count;
		}
	}
	if(!isset($do_reverse[0])){
		echo '<p>I cannot put my stone! Please continue.</p>';
	}
	//プレイヤーが置ける場所を探索
	$_SESSION['pl_can_put'] = search_can_put($_SESSION['cp_map'],$_SESSION['pl_can_put']);
}
/*
 * あとやること
 * 置ける場所の更新（追加）：ユーザーがおき終わった時点でやるべし
 * 置いてはいけない場所の削除
 * パス機能（自動でも良い）
 * 全部埋まるかパスが続いたら終了、戦績を計算
 */

echo $_SESSION['count'].'手目';

echo '<form action="index.php" method="post">';

if($_SESSION['count'] > 0 && count($_SESSION['cp_can_put']) == 0 && count($_SESSION['pl_can_put']) == 0){

	echo '<p>There are ',count($_SESSION['cp_map']),' white stones.</p>';
	echo '<p>There are ',count($_SESSION['pl_map']),' black stones.</p>';
	//数がおかしい（少ない）

	echo '<p>I could put ',count($_SESSION['cp_can_put']),' places.</p>';
	//print_r($_SESSION['cp_can_put']);

	//$black_count = count($_SESSION['pl_map']);
	//$white_count = count($_SESSION['cp_map']);
	$black_count = 0;
	$white_count = 0;
	for($i=-1;$i<2;++$i){ //横方向
		for($j=-1;$j<2;++$j){ //縦方向
			if($_SESSION['map'][$i][$j] == BLACK){
				++$black_count;
			}
			elseif($_SESSION['map'][$i][$j] == WHITE){
				++$black_count;
			}
		}
	}

	echo '<p>There are ',$white_count,' white stones.</p>';
	echo '<p>There are ',$black_count,' black stones.</p>';

	if($white_count > $black_count){
		echo '<p style="color:#00f;">I win!</p>';
	}
	elseif($white_count < $black_count){
		echo '<p style="color:#f00;">You win!</p>';
	}
	else{
		echo '<p style="color:#f0f;">Draw!</p>';
	}
}
else{
	echo '<input type="submit" value="OK">';
}

echo '<table>';
for($j=0;$j<=8;++$j){//x,yにするため表示はiとjが逆
	echo '<tr>';
	for($i=0;$i<=8;++$i){
		echo '<td>';
		$coord = $i.$j;
		if($i == 0){
			echo '<span class="white">',$j,'</span>';
		}
		elseif($j == 0){
			echo '<span class="white">',$i,'</span>';
		}
		elseif(isset($_SESSION['map'][$i][$j])){
			echo $_SESSION['map'][$i][$j];
		}
		elseif(in_array($coord,$_SESSION['pl_can_put'])){
			echo '<input type="radio" id="',$coord,'" name="put" value="',$coord,'"><label for="',$coord,'" class="selected">&#x25a0;</label>';
		}
		else{
			echo '　';
		}
		echo '</td>';
	}
	echo '</tr>';
}
echo '</table></form>';
?>
<a href="reset.php">Reset</a>
<h2>How to play</h2>
<p>You are BLACK.</p>
</body>
</html>