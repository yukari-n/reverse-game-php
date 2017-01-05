<?php
echo '<p>(・ω・)',count($_SESSION['cp_map']),'個の白石と',count($_SESSION['pl_map']),'の黒石があるね</p>';
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
