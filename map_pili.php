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
	['Pili Bomba Setapak', 3.19376,101.717385],
	['Pili Bomba Taman Melati', 3.225317,101.728213],
	['Pili Bomba Jinjang', 3.20914,101.659947],
	['Pili Bomba Chow Kit', 3.174111,101.699352],
	['Pili Bomba Sentul', 3.178224,101.691799],
	['Pili Bomba Sentul', 3.166226,101.690426],
	['Pili Bomba TTDI', 3.147715,101.628971],
	['Pili Bomba Taman Desa', 3.106578,101.695919],
	['Pili Bomba Cheras', 3.099036,101.743298],
	['Pili Bomba Bukit Jalil', 3.060639,101.682873],
	['Pili Bomba Putrajaya', 2.951955,101.676292],
	['Pili Bomba Cyberjaya', 2.913447,101.656662],
	['Cadangan Pili Baru', 3.209755,101.755306],
	['Cadangan Pili Baru', 3.204105,101.716808],
	['Cadangan Pili Baru', 3.157038,101.751798],
	['Cadangan Pili Baru', 3.063103,101.695372],
	];
	
	var infoWindowContent = [
		['<div class="info_content">' +
		'<img src=\"pili_hd4.jpg\" ALIGN="Right" alt=\"Pili Bomba\" height=\"80px\" width=\"55px\">'+
		'<p>Pili Bomba Setapak</p>' +
		'<p>Pili Bomba Setapak Jalan Ayer Jernih<br/>53200 Kuala Lumpur, Setapak, Malaysia<br/>+60 3-4023 5544</p>' +
		'</div>'],
		['<div class="info_content">' +
		'<img src=\"pili_hd5.jpg\" ALIGN="Right" alt=\"Pili Bomba\" height=\"80px\" width=\"55px\">'+
		'<p>Pili Bomba Taman Melati</p>' +
		'<p>Jalan Taman Melati 1<br/>Mukim Setapak, Kuala Lumpur, Federal Territory of<br/>Kuala Lumpur, Malaysia<br/>+60 3-4107 3444</p>' +
		'</div>'],
		['<div class="info_content">' +
		'<img src=\"pili_hd.jpg\" ALIGN="Right" alt=\"Pili Bomba\" height=\"80px\" width=\"55px\">'+
		'<p>Pili Bomba Jinjang</p>' +
		'<p>Jalan Jinjang Utara,<br/>52000, Kuala Lumpur<br/>Malaysia<br/>+60 3-6257 4444</p>' +
		'</div>'],
		['<div class="info_content">' +
		'<img src=\"pili_hd.jpg\" ALIGN="Right" alt=\"Pili Bomba\" height=\"80px\" width=\"55px\">'+
		'<p>Pili Bomba Chow Kit</p>' +
		'<p>Jalan Haji Hussin,<br/>50300, Kuala Lumpur<br/>Malaysia<br/>+60 3-2691 3703</p>' +
		'</div>'],
		['<div class="info_content">' +
		'<img src=\"pili_hd.jpg\" ALIGN="Right" alt=\"Pili Bomba\" height=\"80px\" width=\"55px\">'+
		'<p>Pili Bomba Sentul</p>' +
		'<p>Pili Bomba Sentul Jalan Tun Ismail<br/>50480 Kuala Lumpur, Sentul,<br/>Malaysia<br/>+60 3-6257 4444</p>' +
		'</div>'],
		['<div class="info_content">' +
		'<img src=\"pili_hd3.jpg\" ALIGN="Right" alt=\"Pili Bomba\" height=\"80px\" width=\"55px\">'+
		'<p>Pili Bomba Sentul</p>' +
		'<p>Jalan Tun Ismail,<br/>50480, Kuala Lumpur<br/>Malaysia<br/>+60 3-4044 1994</p>' +
		'</div>'],
		['<div class="info_content">' +
		'<img src=\"pili_hd.jpg\" ALIGN="Right" alt=\"Pili Bomba\" height=\"80px\" width=\"55px\">'+
		'<p>Pili Bomba TTDI</p>' +
		'<p>Pili Bomba Taman Tun Dr Ismail<br/>Jalan Tun Mohd Fuad<br/>60000 Kuala Lumpur,<br/>Malaysia<br/>+60 3-7728 4444</p>' +
		'</div>'],
		['<div class="info_content">' +
		'<img src=\"pili_hd4.jpg\" ALIGN="Right" alt=\"Pili Bomba\" height=\"80px\" width=\"55px\">'+
		'<p>Pili Bomba Taman Desa</p>' +
		'<p>Jalan Taman Desa,<br/>58100, Kuala Lumpur,<br/>Malaysia<br/>+60 3-7984 8753</p>' +
		'</div>'],
		['<div class="info_content">' +
		'<img src=\"pili_hd3.jpg\" ALIGN="Right" alt=\"Pili Bomba\" height=\"80px\" width=\"55px\">'+
		'<p>Pili Bomba Cheras</p>' +
		'<p>Jalan Manis 1,<br/>56100, Kuala Lumpur,<br/>Malaysia<br/>+60 3-9132 9490</p>' +
		'</div>'],
		['<div class="info_content">' +
		'<img src=\"pili_hd3.jpg\" ALIGN="Right" alt=\"Pili Bomba\" height=\"80px\" width=\"55px\">'+
		'<p>Pili Bomba Bukit Jalil</p>' +
		'<p>Lebuhraya Bukit Jalil,<br/>57000, Kuala Lumpur,<br/>Malaysia<br/>+60 3-8996 7457</p>' +
		'</div>'],
		['<div class="info_content">' +
		'<img src=\"pili_hd5.jpg\" ALIGN="Right" alt=\"Pili Bomba\" height=\"80px\" width=\"55px\">'+
		'<p>Pili Bomba Putrajaya</p>' +
		'<p>Lebuh Wawasan, Presint 7,<br/>62250 Putrajaya Malaysia<br/>+60 3-8888 0036</p>' +
		'</div>'],
		['<div class="info_content">' +
		'<img src=\"pili_hd.jpg\" ALIGN="Right" alt=\"Pili Bomba\" height=\"80px\" width=\"55px\">'+
		'<p>Pili Bomba Cyberjaya</p>' +
		'<p>Persiaran Apec,<br/>Cyberjaya,<br/>Selangor, Malaysia</p>' +
		'</div>'],
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
		'<p> Endah Parade 1 Jalan 1/149e,<br/>Taman Sri Endah,<br/>46150 Kuala Lumpur<br/>Malaysia</p>' +
		'</div>']
	];
		
	var infoWindow = new google.maps.InfoWindow(), marker, i;

	for( i = 0; i < markers.length; i++ ) {
		var position = new google.maps.LatLng(markers[i][1], markers[i][2]);
		bounds.extend(position);
		//if(i == 0 || i == 1 || i == 3 || i == 7 || i == 9 || i == 10)
		//{
		//	marker = new google.maps.Marker({
		//    position: position,
		//    map: map,
		//	icon: image2,
		//    title: markers[i][0]
		//}
		//else
		//{
		if(i == 0 || i == 1 || i == 3 || i == 7 || i == 9 || i == 10)
		{
			var image = 'images/pili2.png';
		}
		else if(i > 11)
		{
			var image = 'images/pili3.png';
		}
		else
		{
			var image = 'images/pili.png';
		}
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