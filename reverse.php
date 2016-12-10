<?php
//ひっくり返す処理
function reverse_stone($color,$target){
	if($color == 'B'){
		$me = BLACK;
		$me_map = $_SESSION['pl_map'];
		$me_put_map = $_SESSION['pl_can_put'];
		$you = WHITE;
		$you_map = $_SESSION['cp_map'];
		$you_put_map = $_SESSION['cp_can_put'];
	}
	else{
		$me = WHITE;
		$me_map = $_SESSION['cp_map'];
		$me_put_map = $_SESSION['cp_can_put'];
		$you = BLACK;
		$you_map = $_SESSION['pl_map'];
		$you_put_map = $_SESSION['pl_can_put'];
	}
	$data = str_split($target);
	$do_put = null;
	$checked = 0;
	for($i=-1;$i<2;++$i){ //横方向
		for($j=-1;$j<2;++$j){ //縦方向
			++$checked;
			// 隣接石が相手ではないor置いた場所はスキップ
			if($_SESSION['map'][$data[0]+$i][$data[1]+$j] != $you || ($i == 0 && $j == 0)){
				continue;
			}
			$reverse = array(); //ひっくり返すかもしれないもの
			for($k=1;$k<=8;++$k){
				$x = $i * $k + $data[0];
				$y = $j * $k + $data[1];
				$put = $x.$y;
				if($_SESSION['map'][$x][$y] == $you){
					++$rev_count;
					array_push($reverse,$put);
				}
				elseif($_SESSION['map'][$x][$y] == $me){ //味方発見（隣接していない）
					$do_put = 1;
					foreach($reverse as $rev){
						$split = str_split($rev);
						$_SESSION['map'][$split[0]][$split[1]] = $me;
					}
					$_SESSION['map'][$data[0]][$data[1]] = $me;
					//見方リストに追加
					array_push($me_map,$target);
					array_push($me_map,$reverse);
					//相手リストから削除
					$you_map = array_diff($you_map,$reverse);
					$you_put_map = array_diff($you_put_map,$reverse);
					break; //k
				}
				else{ //挟めなかった
					//echo '<p>I will check another direction.</p>';
					break; //k
				}
			} //k
			//echo '<p>I checked ',$checked,' directions.</p>';
			if($color == 'W' && $do_put){
				echo '<p>I put at (',$data[0],',',$data[1],').</p>';
				break; //j
			}
		} //j
		if($color == 'W' && $do_put){
			break; //i
		}
	} //i

	return array($do_put,$me_map,$you_map,$me_put_map,$you_put_map);
}
/*
for($i=-1;$i<2;++$i){
	for($j=-1;$j<2;++$j){
		// 隣接石が無い方向・隣接石が黒or置いた場所はスキップ
		if($_SESSION['map'][$data[0]+$i][$data[1]+$j] != WHITE || ($i == 0 && $j == 0)){
			continue;
		}
		for($k=1;$k<=8;++$k){
			$x = $i * $k + $data[0];
			$y = $j * $k + $data[1];
			$put = $x.$y;
			if(!isset($_SESSION['map'][$x][$y]) || $_SESSION['map'][$x][$y] == BLACK){break;}
			$_SESSION['map'][$x][$y] = BLACK;
			array_push($_SESSION['pl_map'],$put);
		}
	}
}
*/