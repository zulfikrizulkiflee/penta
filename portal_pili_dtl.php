<?php
include('db.php');
$piliID = $_GET['idpili'];

$sql = "
SELECT 
	DISTINCT
	pro_pili.no_siri,
	upper(pro_pili.alamat) alamat,
	upper(pro_pili.lokasi) lokasi,
	substr(pro_pili.lat_long,1,8) latitude,
	substr(pro_pili.lat_long,10,10) longitude,
	upper(ruj_jenis_pili.ket_jenis_pili) jenis_pili,
	upper(ruj_pemilikan_pili.ket_pemilikan_pili) pemilikan_pili,
	upper(ruj_tekanan_air.ket_tekanan_air) tekanan_air,
	upper(ruj_lokaliti_adun.ket_lokaliti_adun) adun,
	upper(ruj_lokaliti_daerah.ket_lokaliti_daerah) daerah,
	upper(ruj_lokaliti_parlimen.ket_lokaliti_parlimen) perlimen,
	upper(ruj_status_pili.ket_status_pili) status,
	upper(pro_pili.catatan) catatan,
	upper(pro_balai_bomba.nama_balai_bomba) balai_bomba,
	upper(ruj_syarikat_air.ket_syarikat_air) ket_syarikat_air,
	pro_pili.image_1
FROM 
	epili_usr.pro_pili pro_pili, 
	epili_usr.ruj_jenis_pili ruj_jenis_pili, 
	epili_usr.ruj_pemilikan_pili ruj_pemilikan_pili, 
	epili_usr.ruj_tekanan_air ruj_tekanan_air, 
	epili_usr.ruj_lokaliti_adun ruj_lokaliti_adun,
	epili_usr.ruj_lokaliti_daerah ruj_lokaliti_daerah,
	epili_usr.ruj_lokaliti_parlimen ruj_lokaliti_parlimen,
	epili_usr.ruj_status_pili ruj_status_pili,
	epili_usr.pro_balai_bomba pro_balai_bomba,
	epili_usr.ruj_syarikat_air ruj_syarikat_air
WHERE 
	pro_pili.kod_jenis_pili = ruj_jenis_pili.kod_jenis_pili
	AND pro_pili.kod_lokaliti_daerah = ruj_lokaliti_daerah.kod_lokaliti_daerah
	AND pro_pili.kod_lokaliti_dun = ruj_lokaliti_adun.kod_lokaliti_adun
	AND pro_pili.kod_lokaliti_parlimen = ruj_lokaliti_parlimen.kod_lokaliti_parlimen
	AND pro_pili.kod_pemilikan_pili = ruj_pemilikan_pili.kod_pemilikan_pili
	and pro_pili.kod_status_pili = ruj_status_pili.kod_status_pili
	and pro_pili.kod_syarikat_bekal_air = ruj_pemilikan_pili.kod_pemilikan_pili
	and pro_pili.kod_tekanan_air = ruj_tekanan_air.kod_tekanan_air
	AND pro_pili.id_balai_bomba = pro_balai_bomba.id_balai_bomba
	AND pro_pili.kod_syarikat_bekal_air = ruj_syarikat_air.kod_syarikat_air
	
	and pro_pili.no_siri = '$piliID'
";
$sqlRS = $myQuery->Query($sql,'SELECT','NAME');

//echo $sql;
?>	
<style>
.contact_tel { font-size:14px; font-weight:normal; background:url(img/tel.png) no-repeat left top; padding:0 0 30px 50px;}
.contact_email{ font-size:14px; font-weight:normal; background:url(img/email.png) no-repeat left top; padding:0 0 30px 50px;}
.contact_address{ font-size:14px; font-weight:normal; background:url(img/pobox.png) no-repeat left top; padding:0 0 30px 50px;}
</style>


<table width="100%" border="0" cellpadding="0" cellspacing="0" class="tableContent">
  <tr>
	<th colspan="2">Pili Bomba</th>
  </tr>
  
  <tr><td width="100" class="inputLabel">No Siri</td> <td class="inputArea"><?php echo $sqlRS[0]['NO_SIRI']; ?></td></tr>
  <tr><td width="" class="inputLabel">Alamat</td> <td class="inputArea"><?php echo $sqlRS[0]['ALAMAT']; ?></td></tr>
  <tr><td width="" class="inputLabel">Kedudukan Lokaliti</td> <td class="inputArea"><?php echo $sqlRS[0]['LOKASI']; ?></td></tr>
  <tr><td width="" class="inputLabel">latitude</td> <td class="inputArea"><?php echo $sqlRS[0]['LATITUDE']; ?></td></tr>
  <tr><td width="" class="inputLabel">Longitude</td> <td class="inputArea"><?php echo $sqlRS[0]['LONGITUDE']; ?></td></tr>
  <tr><td width="" class="inputLabel">Gambar</td> <td class="inputArea"><img src="<?php echo $sqlRS[0]['IMAGE_1']; ?>" width='70' /></td></tr>
  <tr><td width="" class="inputLabel">Jenis Pili</td> <td class="inputArea"><?php echo $sqlRS[0]['JENIS_PILI']; ?></td></tr>
  <tr><td width="" class="inputLabel">Status Pili</td> <td class="inputArea"><?php echo $sqlRS[0]['STATUS']; ?></td></tr>
  <tr><td width="" class="inputLabel">Pemilikan</td> <td class="inputArea"><?php echo $sqlRS[0]['NO_SIRI']; ?></td></tr>
  <tr><td width="" class="inputLabel">Tekanan Air</td> <td class="inputArea"><?php echo $sqlRS[0]['TEKANAN_AIR']; ?></td></tr>
  <tr><td width="" class="inputLabel">Syarikat Bekalan Air</td> <td class="inputArea"><?php echo $sqlRS[0]['KET_SYARIKAT_AIR']; ?></td></tr>
  <tr><td width="" class="inputLabel">Catatan</td> <td class="inputArea"><?php echo $sqlRS[0]['CATATAN']; ?></td></tr>
</table>

<br/>
<table border="0" cellspacing="0" cellpadding="0" class="tableContent" width="99%">
  <thead>
	<tr id=""><th colspan="7">Teman Pili</th></tr>
	<tr id="">
		<th class="listingHead">Gambar</th>
		<th class="listingHead">Maklumat</th>
		<th class="listingHead">Lokasi</th>
		<th class="listingHead">Imej</th>
	</tr>
  </thead>
  <tbody id="tableGridLayer9003099221" >
  
	<?php
	$sql2 = "
	SELECT
		pro_teman_pili.image as gambar_teman,
		upper(pro_teman_pili.nama_teman_pili) nama,
		pro_teman_pili.no_kp nokp,
		upper(pro_teman_pili.email) email,
		upper(pro_teman_pili.alamat) alamat,
		pro_teman_pili.poskod poskod,
		substr(pro_pili.lat_long,1,8) latitude,
		substr(pro_pili.lat_long,10,10) longitude,
		pro_pili.image_1 as gambar_pili
	FROM
		epili_usr.pro_pili pro_pili,
		epili_usr.pro_teman_pili pro_teman_pili
	WHERE
		pro_pili.id_pili = pro_teman_pili.id_pili
		AND pro_pili.no_siri = '$piliID'
	";
	$sqlRS2 = $myQuery->Query($sql2,'SELECT','NAME');	
	
	if(count($sqlRS) == 0) {
		?><tr id="row_1">
			<td class="listingContent" colspan='4'> Tiada Data</td>
		</tr><?php
	} else {
		for($a=0; $a<count($sqlRS2); $a++) {
			?>
			<tr id="">
				<td class="listingContent" width=""><img src="<?php echo $sqlRS2[$a]['GAMBAR_TEMAN']; ?>" width="100" height="133" /></td>
				<td class="listingContent" width="">
					<?php echo $sqlRS2[$a]['NAMA']; ?><br/>
					<?php echo $sqlRS2[$a]['EMAIL']; ?>
				</td>
				<td class="listingContent" width="">
					<?php echo $sqlRS2[$a]['ALAMAT']; ?><br/>
					Latitide : <?php echo $sqlRS2[$a]['LATITUDE']; ?><br/>
					Longitud : <?php echo $sqlRS2[$a]['LONGITUDE']; ?>
				</td>
				<td class="listingContent" width=""><img src="<?php echo $sqlRS2[$a]['GAMBAR_PILI']; ?>" width="100" height="133" /></td>
			</tr>	
			<?php
		}
	}	
	?>		
  </tbody>
</table>

<br/>

<style type="text/css">
div#Map
{
	height: 500px;
}
</style>

<div id="Map">
<body>
<?php include('map_pili_filter.php'); ?>
</body>
</div>

