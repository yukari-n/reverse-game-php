<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8" />
<meta name="robots" content="noindex" />
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta http-equiv="imagetoolbar" content="no" />
<meta name="viewport" content="width=device-width,initial-scale=1" />
<meta name="format-detection" content="telephone=no,address=no,email=no" />
<title>under construction</title>
<link href="https://fonts.googleapis.com/earlyaccess/sawarabigothic.css" rel="stylesheet" />
<style>
table{border:solid 1px #000;border-collapse:collapse}
td{border:solid 1px #000;text-align:center;vertical-align:middle}
</style>
</head>
<body>
<h1>under construction</h1>
<?php

include('reverse.php');

echo $_SESSION['count'].'手目';
echo '<form action="index.php" method="post"><table>';
for($i=1;$i<=8;++$i){
	echo '<tr>';
	for($j=1;$j<=8;++$j){
		$coord = $i.$j;
		echo '<td>';
		if(in_array($coord,$can_put)){
			echo '<input type="radio" name="put" value="',$coord,'">';
		}
		elseif(!isset($map[$i][$j])){
			echo '　';
		}
		else{
			echo $map[$i][$j];
		}
		echo '</td>';
	}
	echo '</tr>';
}
echo '</table><input type="submit" value="OK"></form>';
?>
<a href="reset.php">Reset</a>
<h2>How to play</h2>
<p>You are BLACK.</p>
</body>
</html>