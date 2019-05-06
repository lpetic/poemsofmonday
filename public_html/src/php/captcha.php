<?php
session_start();

	$alpha = array("A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z","0","1","2","3","4","5","6","7","8","9","0","1","2","3","4","5","6","7","8","9");

	function simulationTilt(){
		$x = mt_rand(0,6);
		$deg = mt_rand(-4,4);
		$x=0;
		if ($deg>0) {
			$y=30;
		}else{
			$y=25;
		}
		$final = array('x'=>$x, 'y'=>$y, 'd'=>$deg);
		return $final;
	}

	function simulationPoint($img, $color){
		for ($i=0; $i <mt_rand(40,60); $i++){ 
			$x = mt_rand(0, imagesx($img));
			$y = mt_rand(0, imagesy($img));
			imagesetpixel ($img, $x, $y , $color);
		}
	}

	function simulationLine($img, $color){
		for ($i=0; $i < mt_rand(6,12) ; $i++){ 
			$x1 = mt_rand(0, imagesx($img));
			$y1 = mt_rand(0, imagesy($img));
			$x2 = mt_rand(0, imagesx($img));
			$y2 = mt_rand(0, imagesy($img));
			imageline($img, $x1, $y1, $x2, $y2, $color);
		}
	}

	$_SESSION['captcha']="";
	for ($i=0; $i < mt_rand(5,6); $i++) { 
		$_SESSION['captcha'] .= $alpha[mt_rand(0,45)];
	}

	$img = imagecreate(105,35);
	simulationPoint($img, 255);
	simulationLine($img, 255);
	$deg = simulationTilt();
	$font ="../../media/fonts/28 Days Later.ttf";
	$bg = imagecolorallocate($img, 255, 255, 255);
	$textcolor = imagecolorallocate($img, 0, 0, 0);
	$size = mt_rand(18,22);
	imagettftext($img, $size, $deg['d'], $deg['x'], $deg['y'], $textcolor, $font,$_SESSION['captcha']);
	header('Content-type:image/jpeg');
	imagejpeg($img);
	imagedestroy($img);

?>
