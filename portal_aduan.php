
<?php
$textCarian = $_POST['CarianText'];

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
	upper(pro_balai_bomba.nama_balai_bomba) balai_bomba
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
	
	and concat( upper(pro_pili.alamat), upper(pro_pili.lokasi), pro_pili.lat_long, upper(ruj_jenis_pili.ket_jenis_pili), upper(ruj_pemilikan_pili.ket_pemilikan_pili), upper(ruj_tekanan_air.ket_tekanan_air), upper(ruj_lokaliti_adun.ket_lokaliti_adun), upper(ruj_lokaliti_daerah.ket_lokaliti_daerah), upper(ruj_lokaliti_parlimen.ket_lokaliti_parlimen), upper(ruj_status_pili.ket_status_pili), upper(pro_pili.catatan), upper(pro_balai_bomba.nama_balai_bomba) ) like '%$textCarian%'
";	
$sqlRS = $myQuery->Query($sql,'SELECT','NAME');

//echo $sql;
?>	

<form id="form1" name="form1" method="post" action="portal.php?page=aduan" enctype="multipart/form-data" accept-charset="utf-8" >

	<p>Sila isi ruangan berikut jika terdapat sebarang aduan tentang kerosakan pili</p>
	
	<table width="100%" border="0" cellpadding="0" cellspacing="0" class="tableContent">
	  <tr>
		<th colspan="2">Maklumat Aduan</th>
	  </tr>
	  
	  <tr id="column_10_3" >
		<td width="150" class="inputLabel">Nama</td>
		<td class="inputArea"><input name="namaPengadu" id="namaPengadu" type="text" tabindex="" maxlength="" class="inputInput" value="<?php echo $textCarian; ?>" size="70" style="text-align:left;" title="" /> </td>
	  </tr>
	  
	  <tr id="column_10_3" >
		<td width="150" class="inputLabel">No. Telefon</td>
		<td class="inputArea"><input name="NoTel" id="NoTel" type="text" tabindex="" maxlength="" class="inputInput" value="<?php echo $textCarian; ?>" size="30" style="text-align:left;" title="" /> </td>
	  </tr>
	  
	  <tr id="column_10_3" >
		<td width="150" class="inputLabel">Email</td>
		<td class="inputArea"><input name="email" id="email" type="text" tabindex="" maxlength="" class="inputInput" value="<?php echo $textCarian; ?>" size="70" style="text-align:left;" title="" /> </td>
	  </tr>
	  
	  <tr id="column_10_3" >
		<td width="150" class="inputLabel">Jenis Aduan</td>
		<td class="inputArea"><select class="inputInput" id="lokasi" name="lokasi">
			<option value=""></option>
			<option value="1">Kerosakan</option>
			<option value="2">Kebocoran</option>
			<option value="3">Kegunaan Haram</option>
			<option value="4">Lain-lain</option>
		</select></td>
	  </tr>
	  
	  <tr id="column_10_3" >
		<td width="150" class="inputLabel">Lokasi Pili Bomba</td>
		<td class="inputArea"><textarea id="lokasi" name="lokasi" class="inputInput" style="text-align:left;" rows="3" cols="67" title="" tabindex=""></textarea></td>
	  </tr>
	  
	  <tr id="column_10_3" >
		<td width="150" class="inputLabel">Keterangan Aduan</td>
		<td class="inputArea"><textarea id="lokasi" name="lokasi" class="inputInput" style="text-align:left;" rows="10" cols="67" title="" tabindex=""></textarea></td>
	  </tr>
	  

	  <tr id="footer_Carian" >
		  <td colspan="2" class="contentButtonFooter"><input name="Cari" id="Cari" type="submit" class="inputButton" value="Hantar"  title="" /> </td>
	  </tr>
	</table>
	
	<br/>

</form>