<?php
$white_count = count($_SESSION['cp_map']);
$black_count = count($_SESSION['pl_map']);
echo '<p>(・ω・)',$white_count,'個の白石と',$black_count,'の黒石があるね</p>';

if($white_count > $black_count){
	echo '<p style="color:#00f;">(・∀・)ぼくの勝ち！</p>';
}
elseif($white_count < $black_count){
	echo '<p style="color:#f00;">(´・ω・｀)きみの勝ち！</p>';
}
else{
	echo '<p style="color:#f0f;">(・ω・)引き分け！</p>';
}
