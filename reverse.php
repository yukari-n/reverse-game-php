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
echo '<p>The white stones</p>';
print_r($_SESSION['cp_map']);

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
		$do_put = null;
		while($count < $max && !$do_put){
			$target_id = array_rand($_SESSION['cp_can_put']);
			$target = $_SESSION['cp_can_put'][$target_id];
			$data = str_split($target);
			echo '<p>I choose (',$data[0],',',$data[1],').</p>';
			//ひっくり返せる場所を探す
			$checked = 0;
			for($i=-1;$i<2;++$i){ //横方向
				for($j=-1;$j<2;++$j){ //縦方向
					++$checked;
					// 隣接石が無い方向・隣接石が白or置いた場所はスキップ
					if($_SESSION['map'][$data[0]+$i][$data[1]+$j] != BLACK || ($i == 0 && $j == 0)){
						continue;
					}
					$reverse = array(); //ひっくり返すかもしれないもの
					for($k=1;$k<=8;++$k){
						$x = $i * $k + $data[0];
						$y = $j * $k + $data[1];
						$put = $x.$y;
						if($_SESSION['map'][$x][$y] == BLACK){
							++$rev_count;
							array_push($reverse,$put);
						}
						elseif($_SESSION['map'][$x][$y] == WHITE){ //白石発見（隣接していない）
							$do_put = 1;
							foreach($reverse as $rev){
								$split = str_split($rev);
								$_SESSION['map'][$split[0]][$split[1]] = WHITE;
							}
							$_SESSION['map'][$data[0]][$data[1]] = WHITE;
							//白石リストに追加
							array_push($_SESSION['cp_map'],$target);
							array_push($_SESSION['cp_map'],$reverse);
							break; //k
						}
						else{ //挟めなかった
							echo '<p>I will check another direction.</p>';
							break; //k
						}
					} //k
					echo '<p>I checked ',$checked,' directions.</p>';
					if($do_put){
						echo '<p>I put at (',$data[0],',',$data[1],').</p>';
						break; //j
					}
				} //j
				if($do_put){
					break; //i
				}
				else{
					//置けない場所は削除
					$_SESSION['cp_can_put'] = array_diff($_SESSION['cp_can_put'],array($target));
				}
			} //i
			++$count;
		}
	}
	if(!$do_put){
		echo '<p>I cannot put my stone! Please continue.</p>';
	}
	echo '<p>There are ',count($_SESSION['cp_map']),' white stones.</p>';
	echo '<p>I could put ',count($_SESSION['cp_can_put']),' places.</p>';
	print_r($_SESSION['cp_can_put']);

	//置ける場所の追加
	foreach($_SESSION['cp_map'] as $white_coord){
		$white = str_split($white_coord);
		for($i=-1;$i<2;++$i){ //横方向
			for($j=-1;$j<2;++$j){ //縦方向
				if(isset($_SESSION['map'][$i][$j]) || ($i == 0 && $j == 0)){
					continue;
				}
				array_push($_SESSION['can_put'],$i.$j);
			}
		}
	}
}
/*
 * あとやること
 * 置ける場所の更新（追加）
 * 置いてはいけない場所の削除
 * パス機能（自動でも良い）
 * 全部埋まるかパスが続いたら終了、戦績を計算
 */