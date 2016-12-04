<?php
if($_POST['task'] == 'ajax_toggleMenu')
{
	include_once('system_prerequisite.php');
	
	$toggle_value = $_SESSION['toggleState'][$_POST['menuId']];
	
	$_SESSION['toggleState'][$_POST['menuId']] = ($toggle_value == 1 ? 0 : 1);
	
	exit;
}

//fetch translations
getTranslation($myQuery,$_SESSION['language']);
?>

<?php if($_SESSION['userID'] && PROFILE_ENABLED){?>
  <div id="profileBlock">
    <div class="profileImageBlock">
      <img src="<?php if($_SESSION['userImage']) echo $_SESSION['userImage']; else echo 'img/default.gif'; ?>" class="profileImage" />
    </div>

    <div class="profileTextBlock">
      <?php echo TR8N_SIDE_MENU_LEFT_USERNAME; ?>: <?php echo ucwords($_SESSION['userName']);?><br />
      <?php echo TR8N_SIDE_MENU_LEFT_NAME; ?>: <?php echo ucwords($_SESSION['Name']);?><br />
      <?php echo TR8N_SIDE_MENU_LEFT_GROUP; ?>: <?php echo ucwords(strtolower($_SESSION['userGroupCode']));?><br />
      <?php echo TR8N_SIDE_MENU_LEFT_DEPARTMENT; ?>: <?php echo $_SESSION['departmentCode'];?>
    </div>
  </div>
<?php }?>
<?php
//side menu
/*if($_SESSION['LAYOUT'] != '3'){
	build_menu_list($_SESSION['MENU'],1,true);
	?>
	<script>
	jQuery('.sideMenuList > li > a').click(function(){
		var theMenu = jQuery(this).parent().children('ul');
		var isHidden = theMenu.is(':hidden');
		
		if(isHidden){theMenu.slideDown('fast');}
		else{theMenu.slideUp('fast');}
	});
	</script>
	<?php
}*/
?>