<?php
echo '<p>(・ω・)',count($_SESSION['cp_map']),'個の白石と',count($_SESSION['pl_map']),'の黒石があるね</p>';

if($white_count > $black_count){
	echo '<p style="color:#00f;">(・∀・)ぼくの勝ち！</p>';
}
elseif($white_count < $black_count){
	echo '<p style="color:#f00;">(´・ω・｀)きみの勝ち！</p>';
}
else{
	echo '<p style="color:#f0f;">(・ω・)引き分け！</p>';
}
