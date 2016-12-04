<?php
//include stuff needed for session, database connection, and stuff
include('system_prerequisite.php');

if($_GET['menuID'])
{
	//check if menu punya page is in flc_extended_attr_val
	$checkExtended = $myQuery->query("select a.PAGEID from FLC_PAGE a, FLC_EXTENDED_ATTR_VAL b 
										where a.PAGEID = b.ATTR_PARENT_ID
										and b.ATTR_ID = 70 
										and a.MENUID = ".$_GET['menuID']." 									
										" ,'SELECT','NAME');
	if(count($checkExtended))
		ob_start("ob_gzhandler");
	
	//check public page
	if(!$_SESSION['userID'])
	{
		//check public page
		$publicMenu = "select * from FLC_MENU where MENUID = ".$_GET['menuID']." and LINKTYPE = 'P'";
		$publicMenuRs = $myQuery->query($publicMenu,'SELECT','NAME');
		$publicMenuRsCount = count($publicMenuRs);
	}//eof if
}

//debug
if($_GET['debug'] == 'on')
	$_SESSION['debug'] = true;
else if(!isset($_SESSION['debug']) || $_GET['debug'] == 'off')
	$_SESSION['debug'] = false;

//if url security is enabled
if(URL_SECURITY)
{
	//decode the url
	$decodeURL = flc_url_decode($_GET['a']);
	stringTo_GET($decodeURL);
}//eof if

//if have post
if(is_array($_POST))
	$_POST = convertToDBSafe($_POST);

//start calculate page generation time
$start = utime();

//check if user logged out
checkLogout($mySession,$cas,$_GET['logout']);

//set language
if(!isset($_SESSION['language']))
{
	if(FLC_LANGUAGE && FLC_LANGUAGE_DEFAULT)
		$_SESSION['language'] = FLC_LANGUAGE_DEFAULT;
	else
		$_SESSION['language'] = 2;
}//eof if

if($_GET['lang'] || $_GET['menuForceRefresh'])
{
	$_SESSION['language'] = $_GET['lang'];
	$_POST['menuForceRefresh'] = 1;
	
	//fetch translations
	getTranslation($myQuery,$_SESSION['language'],$dbc);
}

//get accessible menu
if((!$_SESSION['MENU'] || $_POST['menuForceRefresh']) && $_SESSION['userID'])
	$_SESSION['MENU'] = $mySQL->getMenuList();


//get theme,theme_folder & layout
/* disable theme : Luqman 
if($_SESSION['userID'])
{
	$_SESSION['THEME_AND_FOLDER_SET'] = false;
	$_SESSION['LAYOUT_SET'] = false;

	//proses theme & folder
	if(!$_SESSION['THEME_AND_FOLDER_SET'])
	{
		$getTheme = "select THEME from PRUSER where USERID = '".$_SESSION['userID']."'";
		$getThemeRs = $myQuery->query($getTheme,'SELECT','INDEX');
		$getThemeRs = $getThemeRs[0][0];
		if($getThemeRs == '') $getThemeRs = DEFAULT_THEME;
		$_SESSION['THEME'] = $getThemeRs;

		$getThemeFolder = "select THEME_FOLDER from FLC_THEME where THEME_ID = '".$getThemeRs."'";
		$getThemeFolderRs = $myQuery->query($getThemeFolder,'SELECT','INDEX');
		$getThemeFolderRs = $getThemeFolderRs[0][0];
		$_SESSION['THEME_FOLDER'] = $getThemeFolderRs;

		$_SESSION['THEME_AND_FOLDER_SET'] = true;
	}
	else
	{
		$getThemeRs = $_SESSION['THEME'];
		$getThemeFolderRs = $_SESSION['THEME_FOLDER'];
	}

	//process layout
	if(!$_SESSION['LAYOUT_SET'])
	{
		$getLayout = "select LAYOUT from PRUSER where USERID = '".$_SESSION['userID']."'";
		$getLayoutRs = $myQuery->query($getLayout,'SELECT','INDEX');
		$getLayoutRs = $getLayoutRs[0][0];
		if($getLayoutRs == '') $getLayoutRs = DEFAULT_LAYOUT;
		$_SESSION['LAYOUT'] = $getLayoutRs;

		$_SESSION['LAYOUT_SET'] = true;
	}
	else
	{
		$getLayoutRs = $_SESSION['LAYOUT'];
	}
	
}
else //public theme/layout atau first landing pada login page
{
	$getThemeRs = DEFAULT_THEME;
	$_SESSION['THEME'] = $getThemeRs;

	$getThemeFolder = "select THEME_FOLDER from FLC_THEME where THEME_ID = '".$getThemeRs."'";
	$getThemeFolderRs = $myQuery->query($getThemeFolder,'SELECT','INDEX');
	$getThemeFolderRs = $getThemeFolderRs[0][0];
	$_SESSION['THEME_FOLDER'] = $getThemeFolderRs;

	$getLayoutRs = DEFAULT_LAYOUT;
	$_SESSION['LAYOUT'] = $getLayoutRs;
}
*/

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo SYSTEM_FULL_NAME;?></title>
<link href="css/layout<?php echo /*$getLayoutRs;*/ '1' ?>.css" rel="stylesheet" type="text/css" />
<link href="themes/<?php echo /*$getThemeFolderRs;*/ 'Modern' ?>/main.css" rel="stylesheet" type="text/css" />
<link href="css/print.css" rel="stylesheet" type="text/css" media="print" />
<link rel="shortcut icon" type="image/x-icon" href="img/logo.ico">
<link href="tools/datepicker/css/datepicker.css" rel="stylesheet" type="text/css" />

<script language="javascript" type="text/javascript" src="tools/prototype.js"></script>
<script language="javascript" type="text/javascript" src="tools/jquery.js"></script>
<script>jQuery = jQuery.noConflict();</script>
<script type="text/javascript" src="themes/<?php echo /*$getThemeFolderRs;*/ 'Modern' ?>/main.js"></script>
<script language="javascript" type="text/javascript" src="tools/scriptaculous/scriptaculous.js"></script>
<script language="javascript" type="text/javascript" src="js/common.js"></script>
<script language="javascript" type="text/javascript" src="js/maths_functions.js"></script>
<script language="javascript" type="text/javascript" src="js/string_functions.js"></script>
<script language="javascript" type="text/javascript" src="js/func_lib.js"></script>
<script language="javascript" type="text/javascript" src="tools/datepicker/js/datepicker.packed.js">{"describedby":"fd-dp-aria-describedby"}</script>

<script language="javascript" type="text/javascript">
function logoutFader()
{
	new Effect.Opacity(document.getElementsByTagName('body')[0], { from: 1.0, to: 0.5, duration: 0.4 }); 
	if(window.confirm('Anda pasti untuk log keluar?')) 
	{
		window.location = 'index.php?logout=true';
	} 
	else 
	{
		new Effect.Opacity(document.getElementsByTagName('body')[0], { from: 1.0, to: 1.0, duration: 0.1 });
		return false;
	}
}
</script>
</head>
<body>
<div id="debug" style="display:none; background-color:#FA7D7D; padding-left:5px; font-weight:bold; color:white; font-size:9px;">
	<a href="index.php?debug=<?php if($_SESSION['debug'] == 'on') echo 'off'; else echo 'on';?>" style="color:white">DEBUG <?php if($_SESSION['debug'] == 'on') echo 'ON'; else echo 'OFF'; ?></a>
	<?php if($_SESSION['debug'] == 'on') { ?>
	<?php } ?>
</div>
<?php if($_SESSION['loginFlag'] || $publicMenuRsCount) {  //if session var login flag is set to true, user is logged in ?>
<!-- LOGGED IN USER SECTION ONLY -->
<!-- MAIN CONTENT SECTION -->
<?php if(HEADER_ENABLED && $_GET['HEADER_ENABLED'] ==""){?>
<div id="header">
	<div id="logo"></div>

	<?php if(SUB_HEADER_ENABLED && $_GET['SUB_HEADER_ENABLED'] ==""){?>
		<div id="topMenu">
		  <div id="topTime" class="topMenuDiv"><div id="topDateIcon" class="topIcon"></div><?php echo dayToMalay(date(l)).', '.monthToMalay(date('F'),'long').' '.date('d, Y');?></div>

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
		  				echo '<a href="javascript:void(0)" onclick="logoutFader()">
		  					  <div id="topLogoutLink" class="topMenuDiv">
		  					  	<div id="topLogoutLinkIcon" class="topIcon"><br/></div>
		  					  	'.LOGOUT.'
		  					  </div></a>';
		  			}
		  		}
		  	}
		  ?>
		</div>
	<?php }?>

</div>
<?php }?>

<?php if(FLC_LANGUAGE){?>
<div id="languageSelector" style="position:absolute; top:5px; right:5px; ">
<?php 
echo getLanguage($myQuery,$_GET); ?>
</div>
<?php }?>

<?php
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

<div id="sidebar">
    <div id="sidebarBG"></div>
    <div id="sideMenuLeft" <?php if($_GET['LEFT_MENU_ENABLED']){?> style="display:none;"<?php }?>>
        <?php include('menu_wrapper.php');?>
    </div>
</div>

<div id="content">
	<!-- CENTER CONTENT SECTION -->
	<?php displayContent($dbc,$myQuery,$mySQL);?>
	<!-- CENTER CONTENT SECTION -->

	<div id="bottom">
		<!-- FOOTER SECTION -->
		<?php if(FOOTER_ENABLED && $_GET['FOOTER_ENABLED'] =="") {?>
		<div id="<?php echo FOOTER_ID;?>">
		  <?php echo FOOTER_TEXT;?>
		</div>
		<?php } ?>
		<!-- //END FOOTER SECTION -->
		<!-- //END MAIN CONTENT SECTION -->

		<!-- PAGE GENERATION TIME -->
		<?php if(PAGE_RESPONSE_ENABLED) { ?>
		<div id="<?php echo PAGE_RESPONSE_ID?>">Response time: <?php echo pageGenerationTime($start,' secs');?> </div>
		<?php } ?>
		<!-- //END PAGE GENERATION TIME -->
	</div>
</div>

<!-- SESSION TIMEOUT SECTION -->
<?php if(SESSION_TIMEOUT_DURATION){?>
<div id="sessionTimeout" style="display:none;">
You have been on this page for too long. Your session will expire in <label id="sessionTimeoutLabel"></label>&nbsp;
<input id="sessionContinue" name="sessionContinue" type="button" class="inputButton" value="Continue?" 
	onclick="continueSession(<?php echo SESSION_TIMEOUT_DURATION;?>*60*1000);" />
<script>setTimeout("checkSessionTimeout();",<?php echo SESSION_TIMEOUT_DURATION;?>*60*1000);</script>
</div>
<?php }?>
<!-- SESSION TIMEOUT SECTION -->


<!-- //END LOGGED IN USER SECTION ONLY -->

<?php } //END if session var login flag is set to true, user is logged in
//else show login screen
else
	redirect('login.php?'.$_SERVER['QUERY_STRING']);
?>
</body>
</html>
<?php //$myDbConn->disconnect(); ?>