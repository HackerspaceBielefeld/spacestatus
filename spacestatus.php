<?php
header('Cache-Control: no-cache');
header('Content-type: application/json');
header('Access-Control-Allow-Origin: *');

$data = (array)json_decode(file_get_contents('vars.json'));

if(isset($_GET) || isset($_POST)) {
	if (isset($_POST['status'])) {
		$data['status'] = (strcmp($_POST['status'], 'open') == 0);
	}

	foreach($_GET as $k=>$v) {
		if(strtolower($v) == 'true')
			$v = true;
		if(strtolower($v) == 'false')
			$v = false;

		$data[$k] = $v;
	}
	//datei abspeichern
	file_put_contents('vars.json',json_encode($data));
}

$projects = [
	'https://github.com/HackerspaceBielefeld',
	'https://wiki.hackerspace-bielefeld.de/Kategorie:Projekt',
	'http://luftdaten-bielefeld.de/',
];

$sensors = [
	'temperature' => [
		0 => [
			'value' => (double)$data['temp_out'],
			'unit' => '°C',
			'location' => 'Outside',
			'name' => "Street"
		]
	],
	'humidity' => [
		0 => [
			'value' => (double)$data['humidity'],
			'unit' => '%',
			'location' => 'Outside',
			'name' => "Street"
		]
	],
	'smog' => [
		0 => [
			'value' => (double)$data['smog10'],
			'unit' => 'µg/m³',
			'location' => 'Outside',
			'name' => '10µm'
		],
		1 => [
			'value' => (double)$data['smog25'],
			'unit' => 'µg/m³',
			'location' => 'Outside',
			'name' => '2.5µm'
		]		
	]
];

$feeds = array(
	'wiki' => [
		'type' => 'atom',
		'url' => 'https://wiki.hackerspace-bielefeld.de/api.php?hidebots=1&days=7&limit=50&action=feedrecentchanges&feedformat=atom'
	],
	'calendar' => [
		'type' => 'ical',
		'url' => 'http://hackerspace-bielefeld.de/?plugin=all-in-one-event-calendar&controller=ai1ec_exporter_controller&action=export_events&no_html=true'
	]
);

$string = [
	'api' => '0.13',
	'space' => 'Hackerspace Bielefeld e.V.',
	'logo' => 'https://hackerspace-bielefeld.de/spacestatus/hackerspace-bielefeld-logo.gif',
	'url' => 'https://hackerspace-bielefeld.de',
	'location' => [
		'lat' => 52.038224,
		'lon' => 8.533056,
		'address' => 'Sudbrackstrasse 42, 33611 Bielefeld, Germany'
	],
	'contact' => [
		'phone' => '+49-52-1337-322-42',
		'jabber' => 'hsb@chat.jabber.space.bi',
		'ml' => 'public@hackerspace-bielefeld.de',
		'twitter' => '@HackerspaceBI',
		'email' => 'info@hackerspace-bielefeld.de',
		'facebook' => 'https://www.facebook.com/HackerspaceBielefeld',
		'issue_mail' => 'admin@hackerspace-bielefeld.de'
	],
	'state' => [
		"icon" =>
			[
				'open' => 'https://hackerspace-bielefeld.de/spacestatus/hackerspace-bielefeld-open.png',
				'closed' => 'https://hackerspace-bielefeld.de/spacestatus/hackerspace-bielefeld-closed.png'],
		'open' => $data['status'],
		'lastchange' => time(),
		'message' => "https://hackerspace-bielefeld.de/spacestatus/display.php",
		'trigger_person' => "infobot"
	],
	'icon' => [
		'open' => 'https://hackerspace-bielefeld.de/spacestatus/hackerspace-bielefeld-open.gif',
		'closed' => 'https://hackerspace-bielefeld.de/spacestatus/hackerspace-bielefeld-closed.gif'],
	'open' => $data['status'],
	'sensors' => $sensors,
	'feeds' => $feeds,
	'projects' => $projects,
	'issue_report_channels' => [
		'email'
	],
];

$jsonOutput = json_encode($string, JSON_PRETTY_PRINT + JSON_UNESCAPED_SLASHES);
file_put_contents("status.json", $jsonOutput);
print_r($jsonOutput);
include('statistic.php');
?>
