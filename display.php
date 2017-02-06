<!DOCTYPE html>
<html>
<head>	
	<meta charset="utf-8">
	<title>Space Statistik</title>
</head>
<body>
<h1>Space-Statistik</h1>
<div style="margin-bottom:10px;">
	Hier siehst du wann es am wahrscheinlichsten ist dass jemand hier ist. das ist keine Garantie.
</div>
<div style="border: 2px solid #000000;">
<?php
	$json = json_decode(file_get_contents('statistic.json'));

	echo '<table><tr>
		<td><b>&nbsp;</b></td>
		<td><b>00</b></td><td><b>01</b></td><td><b>02</b></td>
		<td><b>03</b></td><td><b>04</b></td><td><b>05</b></td>
		<td><b>06</b></td><td><b>07</b></td><td><b>08</b></td>
		<td><b>09</b></td><td><b>10</b></td><td><b>11</b></td>
		<td><b>12</b></td><td><b>13</b></td><td><b>14</b></td>
		<td><b>15</b></td><td><b>16</b></td><td><b>17</b></td>
		<td><b>18</b></td><td><b>19</b></td><td><b>20</b></td>
		<td><b>21</b></td><td><b>22</b></td><td><b>23</b></td>
	</tr>';

	for($w=0;$w<7;$w++) {
		$x = ($w+1)%7;
		echo '<tr><td>';
		switch($x) {
			case 0: echo 'Sonntag';break;
			case 1: echo 'Montag';break;
			case 2: echo 'Dienstag';break;
			case 3: echo 'Mittwoch';break;
			case 4: echo 'Donnerstag';break;
			case 5: echo 'Freitag';break;
			case 6: echo 'Samstag';break;
		}
		echo '</td>';
		for($h=0;$h<24;$h++) {
			if($json->week[$x][$h]->chance < 25) $col = '#000000';
			else if($json->week[$x][$h]->chance < 50) $col = '#ff0000';
                        else if($json->week[$x][$h]->chance < 75) $col = '#ffa500';
                        else if($json->week[$x][$h]->chance < 90) $col = '#ffff00';
                        else if($json->week[$x][$h]->chance < 95) $col = '#00ff00';
                        else  $col = '#ffffff';
			if(isset($_GET['debug']))
				echo '<td style="background-color:'. $col .'">'. $json->week[$x][$h]->chance .'</td>';
			else
				echo '<td style="background-color:'. $col .'; width: 17px;">&nbsp;</td>';


		}
	}

	echo '</table>
	<table><tr>
		<td style="background-color: #000000; width:17px;border: 1px solid #000000;">&nbsp;</td>
		<td>&lt; 25%</td>
		<td style="background-color: #ff0000; width:17px;border: 1px solid #000000;">&nbsp;</td>
                <td>25% - 50%</td>
		<td style="background-color: #ffa500; width:17px;border: 1px solid #000000;">&nbsp;</td>
                <td>50% - 75%</td>
		<td style="background-color: #ffff00; width:17px;border: 1px solid #000000;">&nbsp;</td>
                <td>75% - 90%</td>
                <td style="background-color: #00ff00; width:17px;border: 1px solid #000000;">&nbsp;</td>
                <td>90% - 95%</td>
                <td style="background-color: #ffffff; width:17px;border: 1px solid #000000;">&nbsp;</td>
                <td>&gt; 95%</td>
	</tr></table>';

?>
</div>
</body>
</html>
