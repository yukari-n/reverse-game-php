<?php
function before_put(){
	$html = '<p>前回置いたのは'.$prev_put.'</p><p>プレイヤーが置く前</p><table>';
	for($j=0;$j<=8;++$j){//x,yにするため表示はiとjが逆
		$html .= '<tr>';
		for($i=0;$i<=8;++$i){
			$html .= '<td>';
			if($i == 0){
				$html .= '<span class="white">'.$j.'</span>';
			}
			elseif($j == 0){
				$html .= '<span class="white">'.$i.'</span>';
			}
			elseif(!isset($_SESSION['map'][$i][$j])){
				$html .= '　';
			}
			else{
				$html .= $_SESSION['map'][$i][$j];
			}
			$html .= '</td>';
		}
		$html .= '</tr>';
	}
	$html .= '</table>';
	return $html;
}
