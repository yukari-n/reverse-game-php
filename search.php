<?php
//置ける場所の追加
function search_can_put($map,$putmap){
	foreach($map as $coord){
		$stone = str_split($coord);
		for($i=-1;$i<=1;++$i){ //横方向
			$x = $stone[0] + $i;
			for($j=-1;$j<=1;++$j){ //縦方向
				$y = $stone[1] + $j;
				if(isset($_SESSION['map'][$x][$y]) || ($i == 0 && $j == 0)){
					continue;
				}
				array_push($putmap,$x.$y);
			}
		}
	}
	array_unique($putmap);
	$putmap = array_values($putmap);

	return $putmap;
}