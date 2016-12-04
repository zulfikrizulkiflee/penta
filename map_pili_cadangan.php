<?php
include('db.php');
require_once('system_prerequisite.php');

$id = $_GET['idpili'];
$sql = "
		SELECT 
			*
		FROM 
			epili_usr.pro_pili pro_pili, 
			epili_usr.ruj_jenis_pili ruj_jenis_pili, 
			epili_usr.ruj_pemilikan_pili ruj_pemilikan_pili, 
			epili_usr.ruj_tekanan_air ruj_tekanan_air, 
			epili_usr.ruj_lokaliti_adun ruj_lokaliti_adun,
			epili_usr.ruj_lokaliti_daerah ruj_lokaliti_daerah,
			epili_usr.ruj_lokaliti_parlimen ruj_lokaliti_parlimen,
			epili_usr.ruj_status_pili ruj_status_pili,
			epili_usr.pro_balai_bomba pro_balai_bomba
		WHERE 
			pro_pili.no_siri = '$id'
			AND pro_pili.kod_jenis_pili = ruj_jenis_pili.kod_jenis_pili
			AND pro_pili.kod_lokaliti_daerah = ruj_lokaliti_daerah.kod_lokaliti_daerah
			AND pro_pili.kod_lokaliti_dun = ruj_lokaliti_adun.kod_lokaliti_adun
			AND pro_pili.kod_lokaliti_parlimen = ruj_lokaliti_parlimen.kod_lokaliti_parlimen
			AND pro_pili.kod_pemilikan_pili = ruj_pemilikan_pili.kod_pemilikan_pili
			AND pro_pili.kod_status_pili = ruj_status_pili.kod_status_pili
			AND pro_pili.kod_syarikat_bekal_air = ruj_pemilikan_pili.kod_pemilikan_pili
			AND pro_pili.kod_tekanan_air = ruj_tekanan_air.kod_tekanan_air
			AND pro_pili.id_balai_bomba = pro_balai_bomba.id_balai_bomba
		ORDER BY
			pro_pili.no_siri ASC
		";
$sqlRs = $myQuery->query($sql,'SELECT','INDEX');
?>

<?php
$lat = substr($sqlRs[0][4],0,8);
$long = substr($sqlRs[0][4],9,10);
'var a ='.$lat.';';
'var b ='.$long.';';
?>

<html>
<head>
<meta name="viewport" content="initial-scale=1.0, user-scalable=no">
<meta charset="utf-8">
<title>MAP PILI BOMBA</title>
<div style="background-color:#fbb450; border:1px solid #c97e1c; overflow:hidden;" align="center"; width="100%";>
<header>MAP PILI BOMBA</header>
</div>
<style type="text/css">
div#Map
{
	height: 500px;
}
div#map_container
{
	-moz-box-shadow:inset 1px 1px 1px 1px #ffe0b5;
	-webkit-box-shadow:inset 1px 1px 1px 1px #ffe0b5;
	box-shadow:inset 1px 1px 1px 1px #ffe0b5;

	background-color:#fbb450;
	border:1px solid #c97e1c;
	border-bottom:1px solid #c97e1c;
	color:#ffffff;
	font-size: 11px;
	font-weight:bold;
	padding:6px 11px;
	text-decoration:none;
	text-shadow:0px 1px 0px #8f7f24;
	height: 100%;
}
div#map_container2
{
	background-color:#fbb450;
	border:1px solid #c97e1c;
	bottom: 15px;
	color:black;
	font-size: 11px;
	font-weight:bold;
	padding:1px 11px;
	text-decoration:none;
	text-align:center;
}
div.info_content
{
	color:black;
	text-shadow:none;
	font-size: 10px;
	height: auto;
	width: 250px;
}
div.info_content_human
{
	color:black;
	text-align:center;
	text-shadow:none;
	font-size: 10px;
	width: 250px;
}

@media print 
{
	html, body 
	{
		height: auto;
	}

	#map-canvasss, #map_canvasss 
	{
		height: 650px;
	}
}

#panel {
  position: absolute;
  top: 5px;
  left: 50%;
  margin-left: -180px;
  z-index: 5;
  background-color: #fff;
  padding: 5px;
  border: 1px solid #999;
}
</style>

<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?sensor=true"></script>

<script type="text/javascript">
function loadMap() 
{
	if(navigator.geolocation) 
	{
		browserSupportFlag = true;
		navigator.geolocation.getCurrentPosition(function(position) 
		{
			initialLocation = new google.maps.LatLng(position.coords.latitude,position.coords.longitude);
			map.setCenter(initialLocation);
			var latitude = position.coords.latitude;
			var longitude = position.coords.longitude;
			var geolocpoint = new google.maps.LatLng(latitude, longitude);
		
			var mapOptions = {
				zoom: 4,
				center: geolocpoint,
				mapTypeId: google.maps.MapTypeId.HYBRID
			}

			var geolocation = new google.maps.Marker({
				position: geolocpoint,
				map: map,
				title: 'Lokasi Anda',
				icon: human
			});
			
			var bounds = new google.maps.LatLngBounds();

				var markers = [
				['Lokasi Anda', latitude,longitude],
				];
				
				var infoWindowContent = [
					['<div class="info_content_human">' +
					'<p>Lokasi Anda</p>' +
					'<p>Latitude : ' + latitude + '&deg<br/>Longitude : ' +longitude + '&deg</p>' +
					'</div>']
				];

				var infoWindow = new google.maps.InfoWindow(), marker, i;
			
				for( i = 0; i < markers.length; i++ ) {
					var position = new google.maps.LatLng(markers[i][1], markers[i][2]);
					bounds.extend(position);
					marker = new google.maps.Marker({
						position: position,
						map: map,
						icon: human,
						title: markers[i][0]
					});
					
					// Allow each marker to have an info window    
					google.maps.event.addListener(marker, 'click', (function(marker, i) {
						return function() {
							infoWindow.setContent(infoWindowContent[i][0]);
							infoWindow.open(map, marker);
						}
					})(marker, i));

					// Automatically center the map fitting all markers on the screen
					map.fitBounds(bounds);
				}
				
				// Override our map zoom level once our fitBounds function runs (Make sure it only runs once)
				var boundsListener = google.maps.event.addListener((map), 'bounds_changed', function(event) {
					this.setZoom(14);
					google.maps.event.removeListener(boundsListener);
				});
					
		});
	}
			
	var latlng = new google.maps.LatLng(2.951955,101.676292);
	var myOptions = 
	{
		zoom: 4,
		center: latlng,
		mapTypeId: google.maps.MapTypeId.ROADMAP
	};
	var map = new google.maps.Map(document.getElementById("map_container"),myOptions);
	var image = 'images/pili.png';
	var image2 = 'images/pili2.png';
	var human = 'images/human.png';
	var browserSupportFlag =  new Boolean();
	
	var bounds = new google.maps.LatLngBounds();

	var markers = [
	['Cadangan Pili Baru', 3.209755,101.755306],
	['Cadangan Pili Baru', 3.204105,101.716808],
	['Cadangan Pili Baru', 3.157038,101.751798],
	['Cadangan Pili Baru', 3.063103,101.695372],
	['Cadangan Pili Baru', 3.096764,101.684729],
	['Cadangan Pili Baru', 3.053514,101.692176],
	['Cadangan Pili Baru', 3.105731,101.678476],
	];
	
	var infoWindowContent = [
		['<div class="info_content">' +
		'<img src=\"progress.png\" ALIGN="Right" alt=\"Pili Bomba\" height=\"80px\" width=\"55px\">'+
		'<p>Cadangan Pili Baru</p>' +
		'<p> 76 Jalan J 1 Taman Melawati,<br/>53100 Kuala Lumpur,<br/>Malaysia</p>' +
		'</div>'],
		['<div class="info_content">' +
		'<img src=\"progress.png\" ALIGN="Right" alt=\"Pili Bomba\" height=\"80px\" width=\"55px\">'+
		'<p>Cadangan Pili Baru</p>' +
		'<p>Jalan 3/23d Danau Kota,<br/>53200 Kuala Lumpur,<br/>Malaysia</p>' +
		'</div>'],
		['<div class="info_content">' +
		'<img src=\"progress.png\" ALIGN="Right" alt=\"Pili Bomba\" height=\"80px\" width=\"55px\">'+
		'<p>Cadangan Pili Baru</p>' +
		'<p>Jalan MamAnda 7/1,<br/>Taman Dato Ahmad Razali,<br/>68000 Ampang,<br/>Selangor<br/>Malaysia</p>' +
		'</div>'],
		['<div class="info_content">' +
		'<img src=\"progress.png\" ALIGN="Right" alt=\"Pili Bomba\" height=\"80px\" width=\"55px\">'+
		'<p>Cadangan Pili Baru</p>' +
		'<p>Endah Parade 1 Jalan 1/149e,<br/>Taman Sri Endah,<br/>46150 Kuala Lumpur<br/>Malaysia</p>' +
		'</div>'],
		['<div class="info_content">' +
		'<img src=\"progress.png\" ALIGN="Right" alt=\"Pili Bomba\" height=\"80px\" width=\"55px\">'+
		'<p>Cadangan Pili Baru</p>' +
		'<p>Jalan Desa Taman Abadi Indah,<br/>58100 Kuala Lumpur,<br/>Wilayah Persekutuan Kuala Lumpur,<br/>Malaysia</p>' +
		'</div>'],
		['<div class="info_content">' +
		'<img src=\"progress.png\" ALIGN="Right" alt=\"Pili Bomba\" height=\"80px\" width=\"55px\">'+
		'<p>Cadangan Pili Baru</p>' +
		'<p>Kuala Lumpur - Putrajaya Hwy<br/>Bukit Jalil,<br/>47100 Kuala Lumpur,<br/>Malaysia</p>' +
		'</div>'],
		['<div class="info_content">' +
		'<img src=\"progress.png\" ALIGN="Right" alt=\"Pili Bomba\" height=\"80px\" width=\"55px\">'+
		'<p>Cadangan Pili Baru</p>' +
		'<p> Jalan Telok Gadong Taman Desa,<br/>58100 Kuala Lumpur,<br/>Malaysia</p>' +
		'</div>']
	];
		
	var infoWindow = new google.maps.InfoWindow(), marker, i;

	for( i = 0; i < markers.length; i++ ) {
		var position = new google.maps.LatLng(markers[i][1], markers[i][2]);
		bounds.extend(position);

			var image = 'images/pili3.png';

			marker = new google.maps.Marker({
			position: position,
			map: map,
			icon: image,
			title: markers[i][0]
		});
		//}
		
		// Allow each marker to have an info window    
		google.maps.event.addListener(marker, 'click', (function(marker, i) {
			return function() {
				infoWindow.setContent(infoWindowContent[i][0]);
				infoWindow.open(map, marker);
			}
		})(marker, i));

		// Automatically center the map fitting all markers on the screen
		map.fitBounds(bounds);
	}
	
	// Override our map zoom level once our fitBounds function runs (Make sure it only runs once)
	var boundsListener = google.maps.event.addListener((map), 'bounds_changed', function(event) {
		this.setZoom(14);
		google.maps.event.removeListener(boundsListener);
	});
	


var directionsDisplay;
var directionsService = new google.maps.DirectionsService();
var map;

function initialize() {
  directionsDisplay = new google.maps.DirectionsRenderer();
  var chicago = new google.maps.LatLng(41.850033, -87.6500523);
  var mapOptions = {
    zoom:7,
    mapTypeId: google.maps.MapTypeId.ROADMAP,
    center: chicago
  }
  map = new google.maps.Map(document.getElementById('map_container'), mapOptions);
  directionsDisplay.setMap(map);
}

function calcRoute() {
  var start = document.getElementById('start').value;
  var end = document.getElementById('end').value;
  var request = {
      origin:start,
      destination:end,
      travelMode: google.maps.DirectionsTravelMode.DRIVING
  };
  directionsService.route(request, function(response, status) {
    if (status == google.maps.DirectionsStatus.OK) {
      directionsDisplay.setDirections(response);
    }
  });
}

google.maps.event.addDomListener(window, 'load', initialize);

}
</script>
</head>
<body onload="loadMap()">
<div id="map_container"></div>
</body>

  <body>
    <div id="map_container2">
    <b>Dari : </b>
    <select id="start" onchange="calcRoute();">
		<option value="Lokasi Anda">Lokasi Anda</option>
		<option value="Setapak">Setapak</option>
		<option value="Taman Melati">Taman Melati</option>
		<option value="Jinjang">Jinjang</option>
		<option value="Chow Kit">Chow Kit</option>
		<option value="Sentul">Sentul</option>
		<option value="TTDI">TTDI</option>
		<option value="Taman Desa">Taman Desa</option>
		<option value="Cheras">Cheras</option>
		<option value="Bukit Jalil">Bukit Jalil</option>
		<option value="Putrajaya">Putrajaya</option>
		<option value="Cyberjaya">Cyberjaya</option>
    </select>
    <b>Hingga : </b>
    <select id="end" onchange="calcRoute();">
		<option value="Lokasi Anda">Lokasi Anda</option>
		<option value="Setapak">Setapak</option>
		<option value="Taman Melati">Taman Melati</option>
		<option value="Jinjang">Jinjang</option>
		<option value="Chow Kit">Chow Kit</option>
		<option value="Sentul">Sentul</option>
		<option value="TTDI">TTDI</option>
		<option value="Taman Desa">Taman Desa</option>
		<option value="Cheras">Cheras</option>
		<option value="Bukit Jalil">Bukit Jalil</option>
		<option value="Putrajaya">Putrajaya</option>
		<option value="Cyberjaya">Cyberjaya</option>
    </select>
    </div>
  </body>
</html>