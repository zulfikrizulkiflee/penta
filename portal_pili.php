
<?php
$textCarian = $_POST['CarianText'];

$sql = "
SELECT 
	
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
	epili_usr.pro_balai_bomba pro_balai_bomba
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
	
	/*and concat( upper(pro_pili.alamat), upper(pro_pili.lokasi), pro_pili.lat_long, upper(ruj_jenis_pili.ket_jenis_pili), upper(ruj_pemilikan_pili.ket_pemilikan_pili), upper(ruj_tekanan_air.ket_tekanan_air), upper(ruj_lokaliti_adun.ket_lokaliti_adun), upper(ruj_lokaliti_daerah.ket_lokaliti_daerah), upper(ruj_lokaliti_parlimen.ket_lokaliti_parlimen), upper(ruj_status_pili.ket_status_pili), upper(pro_pili.catatan), upper(pro_balai_bomba.nama_balai_bomba) ) like '%$textCarian%' */
	
	and concat( pro_pili.no_siri, pro_pili.alamat, ruj_jenis_pili.ket_jenis_pili, ruj_status_pili.ket_status_pili, pro_balai_bomba.nama_balai_bomba ) like '%$textCarian%'
";	
$sqlRS = $myQuery->Query($sql,'SELECT','NAME');

//echo $sql;
?>	

<form id="form1" name="form1" method="post" action="portal.php?page=pilibomba" enctype="multipart/form-data" accept-charset="utf-8" >

	<table width="100%" border="0" cellpadding="0" cellspacing="0" class="tableContent">
	  <tr>
		<th colspan="2">Carian Maklumat Pili</th>
	  </tr>
		<tr id="column_10_3" >
		<td width="150" class="inputLabel">Carian</td>
		<td class="inputArea"><input name="CarianText" id="CarianText" type="text" tabindex="" maxlength="" class="inputInput" value="<?php echo $textCarian; ?>" size="70" style="text-align:left;" title="" /> </td>
	  </tr>
		  <tr id="footer_Carian" >
		  <td colspan="2" class="contentButtonFooter"><input name="Cari" id="Cari" type="submit" class="inputButton" value="Cari"  title="" /> </td>
	  </tr>
	</table>
	
	<br/><br/>

	<?php if(isset($textCarian)) { ?>
	
	<table border="0" cellspacing="0" cellpadding="0" class="tableContent" width="99%">
	  <thead>
		<tr id=""><th colspan="7">Senarai</th></tr>
		<tr id="">
			<th class="listingHead">No Siri</th>
			<th class="listingHead">Alamat</th>
			<th class="listingHead">Latitud / Longitud</th>
			<th class="listingHead">Jenis Pili</th>
			<th class="listingHead">Status</th>
			<th class="listingHead">Catatan</th>
			<th class="listingHead">Imej</th>
		</tr>
	  </thead>
	  <tbody id="tableGridLayer9003099221" >
	  
		<?php
		if(count($sqlRS) == 0) {
			?><tr id="row_1">
				<td class="listingContent" colspan='7'> Tiada Data</td>
			</tr><?php
		} else {
			for($a=0; $a<count($sqlRS); $a++) {
				?>
				<tr id="row_1">
					<td class="listingContent" width=""><a href="portal.php?page=pilidtl&idpili=<?php echo $sqlRS[$a]['NO_SIRI']; ?>" ><?php echo $sqlRS[$a]['NO_SIRI']; ?></a></td>
					<td class="listingContent" width=""><?php echo $sqlRS[$a]['ALAMAT']; ?></td>
					<td class="listingContent" width=""><?php echo $sqlRS[$a]['LATITUDE'] .' / '. $sqlRS[$a]['LONGITUDE']; ?></td>
					<td class="listingContent" width=""><?php echo $sqlRS[$a]['JENIS_PILI']; ?></td>
					<td class="listingContent" width=""><?php echo $sqlRS[$a]['STATUS']; ?></td>
					<td class="listingContent" width=""><?php echo $sqlRS[$a]['CATATAN']; ?></td>
					<td class="listingContent" width=""><img src="<?php echo $sqlRS[$a]['IMAGE_1']; ?>" width='70' /></td>
				</tr>	
				<?php
			}
		}	
		?>		
	  </tbody>
	</table>	
	
	<?php } ?>

</form>