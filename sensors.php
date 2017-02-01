<?php
	//datei einlesen
	$data = json_decode(file_get_contents('sensors.json'));
	//daten ändern
	if(isset($_GET['keys']) && isset($_GET['vals'])) {}
		$keys = explode(',',$_GET['keys']);
		$vals = explode(',',$_GET['vals']);
		foreach($keys as $i=>$k) {
			$data[$k] = $vals[$i]
		}
	//datei abspeichern
		file_put_contents('sensors.json',json_encode($data));
	}
	print_r($data);
?>