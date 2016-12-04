<?php
//include stuff needed for session, database connection, and stuff
include('system_prerequisite.php');
//require('custom/buffer/buffer.php');

if(PAGE_RESPONSE_ENABLED)
	$start = utime();

checkLogout($mySession,$cas,$_GET['logout']);
setDefaultLanguage();
checkIfLanguageChanged($myQuery);
checkDebugStatus();
urlSecurity();

if($_GET['menuID'])
{
	//check public page
	if(!$_SESSION['userID'])
	{
		//check public page
		$publicMenu = "select * from FLC_MENU where MENUID = ".$_GET['menuID']." and LINKTYPE = 'P'";
		$publicMenuRs = $myQuery->query($publicMenu,'SELECT','NAME');
		$publicMenuRsCount = count($publicMenuRs);
	}
}

//if have post
if(is_array($_POST))
	$_POST = convertToDBSafe($_POST);

//get accessible menu
if((!$_SESSION['MENU'] || $_POST['menuForceRefresh']) && $_SESSION['userID'])
	$_SESSION['MENU'] = $mySQL->getMenuList();

?>

<?php  include('header.php'); ?>

<?php if($_SESSION['loginFlag'] || $publicMenuRsCount) {  //if session var login flag is set to true, user is logged in ?>
<!-- LOGGED IN USER SECTION ONLY -->
<!-- MAIN CONTENT SECTION -->




<?php if(HEADER_ENABLED && $_GET['HEADER_ENABLED'] ==""){?>



	<?php if(SUB_HEADER_ENABLED && $_GET['SUB_HEADER_ENABLED'] ==""){?>
		<!-- <div id="topMenu">

		  <?php
		  	if(strtoupper($_SESSION['userName']) == "PUBLIC" && MAINMENU_PUBLIC_DISABLE ) {
		  		echo "";
		  	}
		  	else {
		  		if(HOME_PAGE&&HOME_PAGE_URL) {
		  			echo '<a href="'.HOME_PAGE_URL.'">
		  				  <div id="topHomeLink" class="topMenuDiv">
		  				  	<div id="topHomeLinkIcon" class="topIcon"><br/></div>
		  				  	'.HOME_PAGE.'
		  				  </div>
		  				  </a>';
		  		}

		  		if(strtoupper($_SESSION['userName']) == "PUBLIC" && LOGOUT_PUBLIC_DISABLE) {
		  			echo "";
		  		}
		  		else {
		  			if(LOGOUT&&LOGOUT_URL) {

		  			}
		  		}
		  	}
		  ?>
		</div> -->
	<?php }?>


<?php }?>

<?php if(FLC_LANGUAGE){?>
<div id="languageSelector" style="position:absolute; top:5px; right:5px; ">
<?php
echo getLanguage($myQuery,$_GET); ?>
</div>
<?php }?>

<?php
//fetch translations
getTranslation($myQuery,$_SESSION['language']);

if($_SESSION['LAYOUT'] == '3')
{
	?>
	<div id="topMenuBar">
		<div id="topMenuBarBG"></div>
		<?php build_menu_list($_SESSION['MENU'],1,true);?>
	</div>
	<?php
}
?>

<div id="main-content" style="margin-top:<?php echo ($_SESSION['userID'] ? '80px' : '50px'); ?>;">
	<!-- CENTER CONTENT SECTION -->
	<?php displayContent($dbc,$myQuery,$mySQL);?>
	<!-- CENTER CONTENT SECTION -->
</div>

<!-- //END LOGGED IN USER SECTION ONLY -->

<?php } //END if session var login flag is set to true, user is logged in
//else show login screen
else
	redirect('login.php?'.$_SERVER['QUERY_STRING']);
?>
<?php include('footer.php'); ?>
<?php //$myDbConn->disconnect(); ?>