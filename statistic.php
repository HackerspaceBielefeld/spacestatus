<?php

$DBFILE = 'data/data.db';

if(!file_exists($DBFILE)) {
	$db = new SQLite3($DBFILE);
	$db->query("CREATE TABLE log (
	  year INTEGER,
	  month INTEGER,
	  day INTEGER,
	  wday INTEGER,
	  hour INTEGER,
	  status TEXT);");
}else{
	$db = new SQLite3($DBFILE);
}

if(isset($_POST['status'])) {

        $time = explode('-',date("Y-m-d-w-H"));

        $statem = $db->prepare("DELETE FROM log WHERE
          year = :y AND month = :m AND day = :d AND hour = :h;");
        $statem->bindValue(':y',$time[0]);
        $statem->bindValue(':m',$time[1]);
        $statem->bindValue(':d',$time[2]);
        $statem->bindValue(':h',$time[4]);
        $statem->execute();

	if($_POST['status'] == 'open') {
		$statement = $db->prepare("INSERT INTO log (
		  year,month,day,wday,hour, status) VALUES (
		  :y,:m,:d,:w,:h,'open');");
		$statement->bindValue(':y', $time[0]);
                $statement->bindValue(':m', $time[1]);
                $statement->bindValue(':d', $time[2]);
                $statement->bindValue(':w', $time[3]);
                $statement->bindValue(':h', $time[4]);

		$result = $statement->execute(); 
		//echo 'opened';
	}else{
                $statement = $db->prepare("INSERT INTO log (
                  year,month,day,wday,hour, status) VALUES (
                  :y,:m,:d,:w,:h,'closed');");
                $statement->bindValue(':y', $time[0]);
                $statement->bindValue(':m', $time[1]);
                $statement->bindValue(':d', $time[2]);
                $statement->bindValue(':w', $time[3]);
                $statement->bindValue(':h', $time[4]);

                $result = $statement->execute(); 
                //wecho 'closed';

		// altedaten weg
		//$last = explode('-',date("Y-m-d-w-H",time()-(367*24*60*60)));
		$last = array(2016,11,13,0,20);
		/*$statement = $db->prepare("DELETE FROM log WHERE year < :y OR
		  (year = :y AND (month > :m OR day > :d));");
                $statement->bindValue(':y', $last[0]);
                $statement->bindValue(':m', $last[1]);
                $statement->bindValue(':d', $last[2]);


                $statement->execute();*/

		// daten aufbereiten
		$statement = $db->prepare("SELECT * FROM log ORDER BY year,month,day,hour");
		$result = $statement->execute();

		$js = array();
		$r = array();
		while($res = $result->fetchArray()) {
			$r[$res['year']][$res['month']][$res['day']][$res['hour']] = $res['status'];
		}
		
		$s = 'closed';
		$w = $last[3];
		for($y=$last[0];$y<=$time[0];$y++) {
			if($y==$time[0]) {
				$mstop = $time[1];
			}else{
				$mstop = 12;
			}

			if($y==$last[0]) {
				$mstart = $last[1];
			}else{
				$mstart = 1;
			}

			echo ':'.$mstart.'-'.$mstop.'<br/>';

			for($m=$mstart;$m<=$mstop;$m++) {
				switch($m) {
					case 1:
					case 3:
					case 5:
					case 7:
					case 8:
					case 10:
					case 12:
						$dmax = 31;
						break;
					case 4:
					case 6:
					case 9:
					case 11:
						$dmax = 30;
						break;
					case 2:
						if(($y % 4) == 0)
							$dmax = 27;
						else
							$dmax = 28;
				}
				
				if($y == $time[0] && $m == $time[1])
					$dstop = $time[2];
				else
					$dstop = $dmax;

				if($y == $last[0] && $m == $last[1])
					$dstart = $last[2];
				else
					$dstart = 1;

				//echo ':'.$dstart.'-'.$dstop.'<br/>';

				for($d=$dstart;$d<=$dstop;$d++) {
					for($h=0;$h<=23;$h++) {
						if(isset($r[$y][$m][$d][$h])) {
							$s = $r[$y][$m][$d][$h];
							//echo $y.'-'.$m.'-'.$d.'-'.$h.'_'.$s.'<br/>';
						}
						
						if(!isset($js['week'][$w][$h])) {
							$js['week'][$w][$h]['open'] = 0;
							$js['week'][$w][$h]['closed'] = 0;
						}

						$js['week'][$w][$h][$s]++;
						//echo ':'.$y.'-'.$m.'-'.$d.'-'.$w.'-'.$h.'_'.$s.'<br/>';
					}
					$w = ($w+1)%7;
				}
			}
		}

		foreach($js['week'] as $w => $v) {
			foreach($v as $h => $v2) {
				$vges = $v2['open'] + $v2['closed'];
				if($v2['open'] == 0)
					$js['week'][$w][$h]['chance'] = 0;
				else
					$js['week'][$w][$h]['chance'] = round((($v2['open']/$vges)*100),2);
			}
		}
		
		//echo '<pre>';
		//print_r($js);
		//echo '</pre>';

		$f = fopen('statistic.json','w');
		fputs($f,json_encode($js));
		fclose($f);
	}
}
?>
