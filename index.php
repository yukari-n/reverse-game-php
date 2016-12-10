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
</head>
<body><?php
echo 'under construction';

define('BLACK',"&#x25cf;");
define('WHITE',"&#x25cb;");

//初期化
$map = array();
for($i=1;$i<=8;++$i){ //[x][y]
	for($j=1;$j<=8;$j++){
		$map[$i][$j] = null;
	}
}
$map[5][4] = BLACK;
$map[4][5] = BLACK;
$map[4][4] = WHITE;
$map[5][5] = WHITE;

echo '――――――――<br />';
for($i=1;$i<=8;++$i){
	echo '|';
	for($j=1;$j<=8;++$j){
		if(!isset($map[$i][$j])){
			echo '　';
		}
		else{
			echo $map[$i][$j];
		}
		echo '|';
	}
	echo '|<br />';
	echo '――――――――<br />';
}
?></body>
</html>