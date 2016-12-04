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

$lokasi = $sqlRs[0][3];
$lokasi2 = strtoupper($lokasi);
//echo '<pre>';
//print_r($sqlRs);
//echo '</pre>';
?>

<html>
<head>
<title>MAP PILI BOMBA <?php echo $lokasi2?></title>
<div style="background-color:#fbb450; border:1px solid #c97e1c; overflow:hidden;" align="center"; width="100%";>
<header>MAP PILI BOMBA <?php echo $lokasi2?></header>
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
div.info_content
{
	color:black;
	text-shadow:none;
	font-size: 10px;
	height: auto;
	width: 270px;
}
div.info_content_human
{
	color:black;
	text-align:center;
	text-shadow:none;
	font-size: 10px;
	width: 270px;
}
</style>

<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?sensor=true"></script>

<script type="text/javascript">
  function loadMap() 
  {
	//if(navigator.geolocation) 
	//{
	//	browserSupportFlag = true;
	//	navigator.geolocation.getCurrentPosition(function(position) 
	//	{
	//		initialLocation = new google.maps.LatLng(position.coords.latitude,position.coords.longitude);
	//		map.setCenter(initialLocation);
	//		var latitude = position.coords.latitude;
	//		var longitude = position.coords.longitude;
	//		var geolocpoint = new google.maps.LatLng(latitude, longitude);
	//	
	//		var mapOptions = {
	//			zoom: 4,
	//			center: geolocpoint,
	//			mapTypeId: google.maps.MapTypeId.HYBRID
	//		}
	//		// Place a marker
	//		var geolocation = new google.maps.Marker({
	//			position: geolocpoint,
	//			map: map,
	//			title: 'Lokasi Anda',
	//			icon: human
	//		});
	//		
	//		var bounds = new google.maps.LatLngBounds();
    //
	//			var markers = [
	//			['Lokasi Anda', latitude,longitude],
	//			];
	//			
	//			var infoWindowContent = [
	//				['<div class="info_content_human">' +
	//				'<p>Lokasi Anda</p>' +
	//				'<p>latitude : ' + latitude + '&deg<br/>longitude : ' +longitude + '&deg</p>' +
	//				'</div>']
	//			];
    //
	//			var infoWindow = new google.maps.InfoWindow(), marker, i;
	//		
	//			for( i = 0; i < markers.length; i++ ) {
	//				var position = new google.maps.LatLng(markers[i][1], markers[i][2]);
	//				bounds.extend(position);
	//				marker = new google.maps.Marker({
	//					position: position,
	//					map: map,
	//					icon: human,
	//					title: markers[i][0]
	//				});
	//				
	//				// Allow each marker to have an info window    
	//				google.maps.event.addListener(marker, 'click', (function(marker, i) {
	//					return function() {
	//						infoWindow.setContent(infoWindowContent[i][0]);
	//						infoWindow.open(map, marker);
	//					}
	//				})(marker, i));
    //
	//				// Automatically center the map fitting all markers on the screen
	//				map.fitBounds(bounds);
	//			}
	//			
	//			// Override our map zoom level once our fitBounds function runs (Make sure it only runs once)
	//			var boundsListener = google.maps.event.addListener((map), 'bounds_changed', function(event) {
	//				this.setZoom(14);
	//				google.maps.event.removeListener(boundsListener);
	//			});
	//				
	//	});
	//}
			
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

		<?php
		$lat = substr($sqlRs[0][4],0,8);
		$long = substr($sqlRs[0][4],9,10);
		$lokasi = $sqlRs[0][3];
		$alamat = $sqlRs[0][2];
		$image = $sqlRs[0][15];
		echo 'var valLat ='.$lat.';';
		echo 'var valLong ='.$long.';';
		echo 'var valLokasi ="'.strtoupper($lokasi).'";';
		echo 'var valAlamat ="'.strtoupper($alamat).'";';
		echo 'var valImage ="'.$image.'";';
		?>
		
	    var markers = [
        ['Pili Bomba Setapak',valLat,valLong],
		];
		
		var infoWindowContent = [
			['<div class="info_content">' +
			'<img src=\"'+valImage+'\" ALIGN="Right" alt=\"Pili Bomba\" height=\"80px\" width=\"55px\">'+
			'<p>PILI BOMBA ' + valLokasi + '</p>' +
			'<p>' + valAlamat + '</p>' +
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
  }
</script>
</head>

<body onload="loadMap()">
<div id="map_container"></div>
</body>

</html>