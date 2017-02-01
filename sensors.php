<?php
	//datei einlesen
	$data = (array)json_decode(file_get_contents('sensors.json'));
	//daten ändern
	if(isset($_GET)) {
		foreach($_GET as $k=>$v) {
			$data[$k] = $v;
		}
	//datei abspeichern
		file_put_contents('sensors.json',json_encode($data));
	}
	print_r($data);
?>
