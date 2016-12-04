<?php $id=$_SESSION['userID']; 
 $idpili = $_GET['idpili']; 
 $idkompaun= $_GET['idkompaun']; 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>

<link rel="stylesheet" media="all" type="text/css" href="css/menu-style.css" />
<script type='text/javascript' src='js/menu.js'></script>

<div id="qm0" class="qmmc" style="height:50px;">	
   <!--<a href="<?php echo 'index.php?a='.('page=page_wrapper&menuID=452&idpili='.$idpili);?>">PROFIL PILI BOMBA</a-->
	<a href="index.php?page=page_wrapper&menuID=111&idpili=<?php echo $idpili;?>">PROFIL PILI BOMBA</a>
	<a href="index.php?page=page_wrapper&menuID=123&idpili=<?php echo $idpili;?>">PROFIL TEMAN PILI BOMBA</a>
	<a href="index.php?page=page_wrapper&menuID=168&idpili=<?php echo $idpili;?>">PEMERIKSAAN</a>
	<a href="index.php?page=page_wrapper&menuID=174&idpili=<?php echo $idpili;?>">PENYELENGGARAAN</a>
	<a href="index.php?page=page_wrapper&menuID=112&idpili=<?php echo $idpili;?>">KOMPAUN</a>
	<a href="index.php?page=page_wrapper&menuID=132&idpili=<?php echo $idpili;?>">PELAPORAN KEROSAKAN</a>
	

	
	
	


	<span class="qmclear"> </span>
</div>


<br>
<br>

<script type="text/javascript">qm_create(0,false,0,500,false,true,true,true,false);</script>
</body>
</html>
