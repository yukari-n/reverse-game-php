<?php
//置ける場所の追加

/*
 * 既に黒石がある所に置いてしまうバグあり（黒石マップに不具合？）←再現なし
 * 黒が置けるはずの所に置けないバグあり←黒石・白石マップに不具合があるからだと思われる
 * 置けない場所を追加しないシステムが必要
 */

function search_can_put($map /* 相手の石マップ */  ,$putmap /* 置ける場所マップ */){
	//$putmap = array();
	foreach($putmap as $coord){
		$count = 0;
		for($i=-1;$i<=1;++$i){ //横方向
			for($j=-1;$j<=1;++$j){ //縦方向
				if($i == 0 && $j == 0){continue;}
				$tonari = $coord + (10 * $i) + $j;
				if(!in_array($tonari,$map)){
					++$count; //隣が敵石ではない数を数える
				}
			}
		}
		if($count == 8){
			$putmap = array_diff($putmap,array($coord));
		}
	}
	foreach($map as $coord){
		$stone = str_split($coord);
		for($i=-1;$i<=1;++$i){ //横方向
			$x = $stone[0] + $i;
			if($x < 1 || $x > 8){continue;} //枠外
			for($j=-1;$j<=1;++$j){ //縦方向
				$y = $stone[1] + $j;
				if($y < 1 || $y > 8){continue;} //枠外
				if(isset($_SESSION['map'][$x][$y]) || ($i == 0 && $j == 0)){
					continue;
				}
				array_push($putmap,$x.$y);
			}
		}
	}
	//念のため
	$putmap = array_diff($putmap,array(00));
	//$putmap = array_diff($putmap,$_SESSION['cp_map']);
	//$putmap = array_diff($putmap,$_SESSION['pl_map']);
	$putmap = array_unique($putmap);
	$putmap = array_values($putmap);

	return $putmap;
}