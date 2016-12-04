<?php include('db.php')?>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
<script>jQuery = jQuery.noConflict();</script>
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script>
<script>
function flc_googleMap(lat,long,height,width,target,locations)
{		
	jQuery(document).ready(function() {
		var latlng = new google.maps.LatLng(lat,long);
		var opt =
		{ 
			center:latlng,
			zoom:12,
			mapTypeId: google.maps.MapTypeId.ROADMAP,
			navigationControl:true,
			streetViewControl:false,
			navigationControlOptions: {style:google.maps.NavigationControlStyle.DEFAULT},
			mapTypeControl:true,
			mapTypeControlOptions: {style:google.maps.MapTypeControlStyle.DEFAULT}
		};
		
		var map = new google.maps.Map(document.getElementById(target),opt);
		var marker;
		
		jQuery('#'+target).css('width',width+'px').css('height',height+'px');		//set width and height
		
		for(x=0; x < locations.length; x++)
		{
			if(x==0)
				markerImage = 'marker3.png';
			else
				markerImage = 'hydrant.png';
			 
			 if(x==0)
			 {
				 marker= new google.maps.Marker(
				{
					position: new google.maps.LatLng(locations[x][1],locations[x][2]),
					clickable: true,
				//	animation: google.maps.Animation.BOUNCE,
					map: map,
					icon: 'images/'+markerImage,
					title: 'Incident spot! '+'(Loc:'+locations[x][1]+', '+locations[x][2]+')'
				});
			 }
			 else
			 {
				 marker= new google.maps.Marker(
				{
					position: new google.maps.LatLng(locations[x][1],locations[x][2]),
					clickable: true,
					//animation: google.maps.Animation.BOUNCE,
					map: map,
					icon: 'images/'+markerImage,
					title: "Fire Hydrant No "+(x+1)+' (Loc:'+locations[x][1]+', '+locations[x][2]+')'
				});
			}
		}
		
		var styles = 
		[
			{
				featureType:		"all",
				elementType:		"geometry",
				stylers: 
				[
					{hue:			"#AD5700"},
					{saturation:	100},
					{lightness: 	20}
				]
			},
			{
				featureType:	"all",
				elementType:	"labels.text",
				stylers: 
				[
					{color:		"#4e4e4e"},
					{weight: 	"0.1"}
				]
			}
		];
		map.setOptions({styles: styles});
		
		//radius
		var hydrantRadius = 
		{
			strokeColor: '#FF0000',
			strokeOpacity: 0.8,
			strokeWeight: 1,
			fillColor: '#FF0000',
			fillOpacity: 0.15,
			map: map,
			center: latlng,
			radius: 0
		};
		cityCircle = new google.maps.Circle(hydrantRadius);
	});
}

 <?php
 
 $id_balai = $_GET['idbalai']; 
// $id_balai = '1';
		
		//location : balai bomba
		 $sql="select 
				substr(lat_long,1,7) as LATITUD
				,substr(lat_long,9) as Longitud
				from
				epili_usr.pro_balai_bomba
				where id_balai_bomba ='$id_balai'";
		$resultRS = $myQuery->query($sql,'SELECT','INDEX');	
		
		$lat = $resultRS[0][0]; 
		$longi = $resultRS[0][1];
            
		echo 'var latitud='.$lat.';'; 
		echo 'var longitud='.$longi.';';

		//location : pili
		 $sqlPili="select  
				  substr(lat_long,1,8) as LATITUD
				, substr(lat_long,10,8) as LONGITUD
				from 
				epili_usr.pro_pili 
				where
				id_balai_bomba ='$id_balai'";
		$resultPili = $myQuery->query($sqlPili,'SELECT','INDEX');	
		 	
		for($i =0; $i <count($resultPili); $i++)
		{

			$latPili = $resultPili[$i][0] ;
			$longPili = $resultPili[$i][1] ; 

			$location .= "['',".$latPili.",". $longPili."],";
		}

		$location = substr($location,0,-1); //buang comma
		
		echo '/*FOR FIRE HYDRANT LOCATIONS*/';
		echo "var locations = [
				['',$lat,$longi],
				$location
				];"; 
 ?>

flc_googleMap(latitud,longitud,330,418,'map',locations);
</script>
<div id="map"></div>

