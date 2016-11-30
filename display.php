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
		echo '<tr><td>';
		switch($w) {
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
			if($json->week[$w][$h]->chance < 1) $col = '#000000';
			else if($json->week[$w][$h]->chance < 25) $col = '#ff0000';
                        else if($json->week[$w][$h]->chance < 50) $col = '#ffa500';
                        else if($json->week[$w][$h]->chance < 75) $col = '#ffff00';
                        else if($json->week[$w][$h]->chance < 95) $col = '#00ff00';
                        else  $col = '#ffffff';

			echo '<td style="background-color:'. $col .';">'. floor($json->week[$w][$h]->chance) .'%</td>';

		}
	}

	echo '</table>';

?>
