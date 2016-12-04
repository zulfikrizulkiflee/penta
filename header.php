<!doctype html>
	<html class="no-js" lang="en">
	<head>
		<meta charset="utf-8">
		<meta charset=utf-8>
		<title>Sistem Pengurusan Pili Bomba</title>
		<link rel="stylesheet" href="css/normalize.css"/>
		<link rel="stylesheet" href="css/epili.css"/>
		<link rel="stylesheet" href="css/coin-slider-styles.css"/>
		<link href="tools/datepicker/css/datepicker.css" rel="stylesheet" type="text/css" />
		<script language="javascript" type="text/javascript" src="tools/prototype.js"></script>
		<script language="javascript" type="text/javascript" src="tools/jquery.js"></script>
		<script>jQuery = jQuery.noConflict();</script>
		<script language="javascript" type="text/javascript" src="tools/scriptaculous/scriptaculous.js"></script>
		<script language="javascript" type="text/javascript" src="js/common.js"></script>
		<script language="javascript" type="text/javascript" src="js/coin-slider.min.js"></script>
		<script language="javascript" type="text/javascript" src="js/maths_functions.js"></script>
		<script language="javascript" type="text/javascript" src="js/string_functions.js"></script>
		<script language="javascript" type="text/javascript" src="js/func_lib.js"></script>
		<script language="javascript" type="text/javascript" src="tools/datepicker/js/datepicker.packed.js">{"describedby":"fd-dp-aria-describedby"}</script>
		<script language="javascript" type="text/javascript" src="tools/perfect-scrollbar/jquery.mousewheel.js" defer="defer"></script>
		<script language="javascript" type="text/javascript" src="tools/perfect-scrollbar/perfect-scrollbar.js" defer="defer"></script>
		<script language="javascript" type="text/javascript">
		function logoutFader()
		{
			if(window.confirm('Anda pasti untuk log keluar?'))
			{
				window.location = 'index.php?logout=true';
			}
			else
			{
				return false;
			}
		}

		jQuery('document').ready(function(){

			var leftOffset = parseInt(jQuery("#headerbg").css('left')); //Grab the left position left first
			jQuery(window).scroll(function(){
			    jQuery('#headerbg').css({
			        'left': jQuery(this).scrollLeft() + leftOffset //Use it later
			    });

			    jQuery('#menu-sistem-bg').css({
			        'left': jQuery(this).scrollLeft() + leftOffset //Use it later
			    });
			});

			jQuery('#slider').coinslider({ width: 640, height:424, navigation: false });


			function MuatkanTable()
			{
				var a = jQuery('table.tableContent');
				var b = jQuery('#main-content').width();

				jQuery.each(a,function(){
					var c = jQuery(this).width();

					if(c > b){
						var d = jQuery(this);
						var e = d.parent();

						e.css({'width':b+'px','overflow':'auto'});
					}
				})
			}
			MuatkanTable();

		});


		</script>
	</head>
<body>
<?php
	if($_SESSION['userID'])
	{
		$session= $_SESSION['userID'];
		$SQLSession = "SELECT name,designation FROM epili.PRUSER where userid=$session";
		$RsSQLSession = $myQuery->query($SQLSession,'SELECT','NAME');
		$name = $RsSQLSession[0]['NAME'];
		$designation = $RsSQLSession[0]['DESIGNATION'];
	}
?>
	<?php

	function hideHeaderIfRequire()
	{
		if (isset($_GET['HEADER_ENABLED']) && $_GET['HEADER_ENABLED'] == 'false')
		{
			echo 'style="display:none"';
		}
	}

	?>


	<div id="headerbg" <?php hideHeaderIfRequire() ?>></div>
	<div id="whole-page-wrapper">

		<div id="header" <?php hideHeaderIfRequire() ?>>
			<a href="index.php?page=dashboard&menuID=302"><div id="logo"></div></a>
			<div id="lambang"></div>
			<div id="top-login-area">
				<?php if(!$_SESSION['userID']) { ?>

					<form id="form1" name="form1" method="post" action="login.php">
						<table>
							<tr>
								<td class="login-label align-right">Nama Pengguna :</td>
								<td><input autocomplete="off" placeholder="Username" type="text" name="userID" id="userID" class=""/></td>
							</tr>
							<tr>
								<td class="login-label align-right">Kata Laluan :</td>
								<td><input autocomplete="off" placeholder="Password" type="password" name="userPassword" id="userPassword" class=""/></td>
							</tr>
							<tr>
								<td colspan="2" class="align-right">
									<input type="submit" class="red-button" name="login" value="LOGIN" onClick="if(form1.userID.value != '' && form1.userPassword.value != '') {return true;} else {alert('<?php echo LOGIN_ERROR_MSG; ?>'); form1.userID.focus(); return false; }"/>
								</td>
							</tr>
						</table>
					</form>

				<?php } else { ?>

					<div id="tarikh" style="color:#ffffff; margin:5px 0;">
						<?php echo dayToMalay(date(l)).', '.monthToMalay(date('F'),'long').' '.date('d, Y');?>
                        <?php echo "<br/>"."Nama Pengguna: ".$name; ?>
                        <?php echo "<br/>"."Jawatan: ".$designation; ?>
					</div>

					<?php if(LOGOUT&&LOGOUT_URL) { ?>
						<a class="red-button" href="javascript:void(0)" onClick="logoutFader()"><?php echo LOGOUT; ?></a>
					<?php } ?>

				<?php } ?>
			</div>
			<div id="public-top-menu">

            <!--div style="border:solid" style="position:inherit">uasdhasd</div-->
				<ul>
                <!--li align="right" style="position:static">adad</li-->

					<!--<li class="portal-menu-link"><a href="portal.php?page=utama">Laman Utama</a></li>-->
					<?php //if(HOME_PAGE&&HOME_PAGE_URL) { echo '<li><a href="'.HOME_PAGE_URL.'">'.HOME_PAGE.'</a></li>'; } ?>
					<!--<li class="portal-menu-link"><a href="portal.php?page=mengenaikami">Mengenai Kami</a></li>-->
					<!--<li class="portal-menu-link"><a href="portal.php?page=latarbelakang">Latarbelakang</a></li>-->
					<!--<li class="portal-menu-link"><a href="portal.php?page=pilibomba">Pili Bomba</a></li>-->
					<!--<li class="portal-menu-link"><a href="portal.php?page=aduan">Aduan</a></li>-->
					<!--<li class="portal-menu-link"><a href="portal.php?page=hubungi">Hubungi</a></li>-->
					<?php if($_SESSION['userID']) { ?>
						<li id="menu-sistem-label" class="portal-menu-link">Menu Sistem</li>
						<div id="menu-sistem-container">
							<?php build_menu_list($_SESSION['MENU'],1,true,$myQuery);?>
						</div>
					<?php } ?>
				</ul>
			</div>
			<?php if($_SESSION['userID']) { echo '<div id="menu-sistem-bg"></div>'; } ?>
		</div>

