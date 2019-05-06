<?php
	function redirection_app($file, $prev=true){
		if ($prev){
			$prev='../';
		}else{
			$prev='';
		}
		$file = basename($file);
		$file = substr($file, 0, strlen($file)-4);
		header("Location: ".$prev."app.php/" . $file);
	}
?>