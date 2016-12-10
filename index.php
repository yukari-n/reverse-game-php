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
<style>
table{border:solid 1px #000;border-collapse:collapse}
td{border:solid 1px #000;text-align:center;vertical-align:middle}
</style>
</head>
<body>
<h1>under construction</h1>
<?php
define('BLACK',"&#x25cf;");
define('WHITE',"&#x25cb;");

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
$do_reverse = reverse_stone('B',$put);
$_SESSION['pl_map'] = $do_reverse[1];
$_SESSION['cp_map'] = $do_reverse[2];
$_SESSION['cp_can_put'] = $do_reverse[4];

array_unique($_SESSION['pl_map']);
$_SESSION['pl_map'] = array_values($_SESSION['pl_map']);

echo '<p>The white stones</p>';
print_r($_SESSION['cp_map']);

//コンピューターが置ける場所
$_SESSION['cp_can_put'] = search_can_put($_SESSION['pl_map'],$_SESSION['cp_can_put']);

if($_SESSION['count'] > 0){
	echo '<p>プレイヤーが置いた時点</p><table>';
	for($j=0;$j<=8;++$j){//x,yにするため表示はiとjが逆
		echo '<tr>';
		for($i=0;$i<=8;++$i){
			echo '<td>';
			$coord = $i.$j;
			if($i == 0){
				echo $j;
			}
			elseif($j == 0){
				echo $i;
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

	//どこかに白石置いた所がまだ置ける状態になっているバグがある

	//白が置ける場所を探索
	//とりあえず無作為における場所を探す
	$max = count($_SESSION['cp_can_put']);
	if($max == 0 || count($_SESSION['cp_map']) == 0){
		echo '<p style="color:#f00;">You win!</p>';
	}
	else{
		echo '<p>I could put ',$max,' places.</p>';
		print_r($_SESSION['cp_can_put']);
		$count = 0;
		$do_reverse = null;
		while($count < $max && !isset($do_reverse[0])){
			$target_id = array_rand($_SESSION['cp_can_put']);
			$target = $_SESSION['cp_can_put'][$target_id];
			//置くおかないに関わらず削除
			$_SESSION['cp_can_put'] = array_diff($_SESSION['cp_can_put'],array($target));
			$data = str_split($target);
			echo '<p>I choose (',$data[0],',',$data[1],').</p>';
			//ひっくり返せる場所を探す
			$do_reverse = reverse_stone('W',$target);
			$_SESSION['cp_map'] = $do_reverse[1];
			$_SESSION['pl_map'] = $do_reverse[2];
			$_SESSION['pl_can_put'] = $do_reverse[4];
			++$count;
		}
	}
	if(!isset($do_reverse[0])){
		echo '<p>I cannot put my stone! Please continue.</p>';
	}
	echo '<p>There are ',count($_SESSION['cp_map']),' white stones.</p>';
	echo '<p>I could put ',count($_SESSION['cp_can_put']),' places.</p>';
	print_r($_SESSION['cp_can_put']);

	//プレイヤーが置ける場所
	$_SESSION['can_put'] = search_can_put($_SESSION['cp_map'],$_SESSION['can_put']);
}
/*
 * あとやること
 * 置ける場所の更新（追加）：ユーザーがおき終わった時点でやるべし
 * 置いてはいけない場所の削除
 * パス機能（自動でも良い）
 * 全部埋まるかパスが続いたら終了、戦績を計算
 */

echo $_SESSION['count'].'手目';
echo '<form action="index.php" method="post"><table>';
for($j=0;$j<=8;++$j){//x,yにするため表示はiとjが逆
	echo '<tr>';
	for($i=0;$i<=8;++$i){
		echo '<td>';
		$coord = $i.$j;
		if($i == 0){
			echo $j;
		}
		elseif($j == 0){
			echo $i;
		}
		elseif(isset($_SESSION['map'][$i][$j])){
			echo $_SESSION['map'][$i][$j];
		}
		elseif(in_array($coord,$_SESSION['can_put'])){
			echo '<input type="radio" name="put" value="',$coord,'">';
		}
		else{
			echo '　';
		}
		echo '</td>';
	}
	echo '</tr>';
}
echo '</table><input type="submit" value="OK"></form>';
?>
<a href="reset.php">Reset</a>
<h2>How to play</h2>
<p>You are BLACK.</p>
</body>
</html>