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
<link href="style.css" rel="stylesheet" />
</head>
<body>
<h1>はさんでひっくりかえすやつ</h1>
<p>注意：制作中の為、正しく遊べない場合があります。</p>
<?php
session_start();

require_once('reverse.php');
require_once('search.php');
require_once('debug.php');

if(!isset($_SESSION['count'])){
	$_SESSION['count'] = 0;
	echo '<p>(・ω・)はさんでひっくりかえすやつしよー</p>';
}
else{ //if($_POST['put'])
	++$_SESSION['count'];
}

if($_SESSION['count'] > 0){
	echo before_put();

	$put = $_POST['put'];
	if(!$put){
		echo '<p>(・ω・)パスするの？</p>';
	}
	else{//ひっくり返す
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
	}

	//コンピューターが置ける場所を探索
	$_SESSION['cp_can_put'] = search_can_put($_SESSION['pl_map'],$_SESSION['cp_can_put']);
	//とりあえず無作為における場所を探す
	$max = count($_SESSION['cp_can_put']);
	/* if($max == 0 || count($_SESSION['cp_map']) == 0){
		echo '<p style="color:#f00;">You win!</p>';
	}
	else{ */
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
	//}
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

if($_SESSION['count'] > 0 && count($_SESSION['cp_can_put']) == 0 && count($_SESSION['pl_can_put']) == 0 || count($_SESSION['cp_map']) == 0 || count($_SESSION['pl_map']) == 0){
	include('judge.php');
}
else{
	echo '<input type="submit" value="OK"> <input type="radio" id="pass" name="put" value=""><label for="pass" class="selected pass">&#x25a0; パス</label>';
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
<h2>遊び方</h2>
<p>(・ω・)黒が先手で相手を上下左右斜めにはさむよ</p>
<p>(・ω・)ぼくが白でいいよ</p>
<p>(・ω・)グレーの<span class="gray">&#x25a0;</span>から石を置く所を選んでね<br />
　このゲームは制作中なので、石をはさめない所も選べるようになっているから注意してね</p>
<p>(・ω・)ページを更新するとパス扱いになるよ</p>
<p>(´・ω・｀)あとぼく石を数えるのが苦手なんだ…　（作者注※最終結果のカウントに不具合があります）</p>
<p><a href="reset.php">Reset</a></p>
</body>
</html>