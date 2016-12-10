<?php
//ひっくり返す処理
function reverse_stone($color,$stonemap,$target){
	if($color == 'B'){
		$me = BLACK;
		$you = WHITE;
	}
	else{
		$me = WHITE;
		$you = BLACK;
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
					array_push($stonemap,$target);
					array_push($stonemap,$reverse);
					break; //k
				}
				else{ //挟めなかった
					//echo '<p>I will check another direction.</p>';
					break; //k
				}
			} //k
			//echo '<p>I checked ',$checked,' directions.</p>';
			if($do_put){
				echo '<p>I put at (',$data[0],',',$data[1],').</p>';
				break; //j
			}
		} //j
		if($do_put){
			break; //i
		}
	} //i

	return array($do_put,$stonemap);
}