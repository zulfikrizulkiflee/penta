<?php
//validate that user have session
require_once('func_common.php');
validateUserSession();

include_once('system_prerequisite.php');

//if ajax filter
if(isset($_GET['filter_sub']))
{
	$_POST['code'] = $_GET['parentid'];
	$_POST['showScreen'] = true;
}

//if show disabled parent menu not set
if(!isset($_POST['showDisabled']))
	$_POST['showDisabled'] = 0;

//============================================ parent menu ============================================
//if new screen submitted
if($_POST["newCategory"])
{
	//get parent menu order
	$parentOrder = "select max(MENUORDER) + 1 as MAXPARENTORDER 
					from FLC_MENU 
					where MENULEVEL = 1";
	$parentOrderRsRows = $myQuery->query($parentOrder,'SELECT','NAME');
}

//if main category edit button clicked
if($_POST["code"] && $_POST["editCategory"])
{
	//show info of selected category
	$showCatInfo = "select * from FLC_TRANSLATION_LANGUAGE where LANG_ID = ".$_POST["code"];
	$showCatInfoRsRows = $myQuery->query($showCatInfo,'SELECT','NAME');
}

//if new language added
if($_POST["saveScreenNew"])
{	
	//get max menuid
	$getMaxRs = $mySQL->maxValue('FLC_TRANSLATION_LANGUAGE','LANG_ID',0)+1;

	$insertCat = "insert into FLC_TRANSLATION_LANGUAGE (LANG_ID,LANG_NAME,LANG_FLAG_URL,ADDED_BY,ADDED_DATETIME)
					values (".$getMaxRs.", '".$_POST['newName']."','".$_POST['flag']."',".$_SESSION['userID'].",".$mySQL->currentDate().")";
	$insertCatRs = $myQuery->query($insertCat,'RUN');
}

//if edit screen submitted
if($_POST["saveScreenEdit"])
{
	$updateCat = "update FLC_TRANSLATION_LANGUAGE set 
					LANG_NAME = '".$_POST['editName']."',
					LANG_FLAG_URL = '".$_POST['flag']."'
					where LANG_ID = ".$_POST['code'];
	$myQuery->query($updateCat,'RUN');
}//eof id

else if($_POST["deleteCategory"])
{	
	//delete the language
	$deleteMenu = "delete from FLC_TRANSLATION_LANGUAGE 
					where LANG_ID = ".$_POST["code"];
	$deleteMenuRs = $myQuery->query($deleteMenu,'RUN');
	
	//delete translation
	$deletePerm = "delete from FLC_TRANSLATION 
				where TRANS_LANGUAGE = ".$_POST['code'];
	$deletePermRs = $myQuery->query($deletePerm,'RUN');
}
//=====================================================================================================

//===========================================reference=================================================
//if new reference added
if($_POST['saveScreenRefNew'])
{
	//get max menuid
	$getMaxRs = $mySQL->maxValue('FLC_TRANSLATION','TRANS_ID',0)+1;

	$sourceSplit = explode('|',$_POST['newItemTranslate']);

	$insertRef = "insert into FLC_TRANSLATION
					(TRANS_ID, TRANS_TYPE, TRANS_LANGUAGE, TRANS_TEXT, TRANS_SOURCE_ID, TRANS_SOURCE_TABLE, TRANS_SOURCE_COLUMN) 
	 				values
					(".$getMaxRs.", '".$_POST['trans_type']."',".$_POST['code'].",'".$_POST['newTransText']."',
					'".$_POST['newSourceID']."','".$sourceSplit[0]."','".$sourceSplit[1]."')";
	$insertRefRs = $myQuery->query($insertRef,'RUN');
	
	//dummy to trigger 
	$_POST['showScreen'] = true;
}

//if save screen edit reference
else if($_POST['saveScreenRefEdit'])
{	
	$sourceSplit = explode('|',$_POST['editItemTranslate']);

	//update statement
	$updateRef = "update FLC_TRANSLATION 
					set TRANS_TEXT = '".$_POST['editTransText']."',
					TRANS_SOURCE_ID = ".$_POST['editSourceID'].",
					TRANS_SOURCE_TABLE = '".$sourceSplit[0]."',
					TRANS_SOURCE_COLUMN = '".$sourceSplit[1]."'
					where TRANS_ID = ".$_POST['hiddenCode'];
	$updateRefRs =  $myQuery->query($updateRef,'RUN');
	
	//dummy
	$_POST['showScreen'] = true;
}

//if reference deleted
if($_POST['deleteTrans'])
{
	$deleteTranslation = "delete from FLC_TRANSLATION where TRANS_ID = ".$_POST['hiddenCode'];
	$deleteTranslationRs = $myQuery->query($deleteTranslation,'RUN');
	
	//dummy to trigger 
	$_POST['showScreen'] = true;
}

//if edit reference clicked, show detail
if($_POST['editReference'])
{
	//show reference detail
	$showRef = "select *
				from FLC_TRANSLATION  
				where TRANS_ID = ".$_POST['hiddenCode'];
	$showRefRsRows = $myQuery->query($showRef,'SELECT','NAME');
}
//===========================================//reference===============================================

function languageEditor_getTranslation($myQuery,$type,$lang,$id,$col)
{
	//index page
	if($type == 1)
	{
	}
	
	//menu
	else if($type == 2)
	{
		$srcTable = 'FLC_MENU';
		$primaryKey = 'MENUID';
		$col = 'MENUTITLE';
	}
	
	//page
	else if($type == 3)
	{
		$srcTable = 'FLC_PAGE';
		$primaryKey = 'PAGEID';
	}
	
	//component
	else if($type == 4)
	{
		$srcTable = 'FLC_PAGE_COMPONENT';
		$primaryKey = 'COMPONENTID';
	}
	
	//item
	else if($type == 5)
	{
		$srcTable = 'FLC_PAGE_COMPONENT_ITEMS';
		$primaryKey = 'ITEMID';
	}
	
	//control
	else if($type == 6)
	{
		$srcTable = 'FLC_PAGE_CONTROL';
		$primaryKey = 'CONTROLID';
		$col = 'CONTROLTITLE';
	}
	
	$qry = "select ".$col." as TRANS_TEXT from ".$srcTable." where ".$primaryKey."=".$id;
	$qryRs = $myQuery->query($qry,'SELECT','NAME');
	
	if(count($qryRs) > 0)
		return $qryRs[0]['TRANS_TEXT'];
	else
		return '<span style="color:red;">--- Item Deleted ---</span>';
}

//=========================================== page ordering ===========================================
//if showScreen and code not null
if($_POST['showScreen'] && $_POST['code'])
{
	$_GET['original'] = trim(str_replace('search here..','',$_GET['original']));
	$_GET['translated'] = trim(str_replace('search here..','',$_GET['translated']));
	
	$filterArr = array();
	
	if(!empty($_GET['original']))
		$filterArr['original'] = $_GET['original'];
	
	if(!empty($_GET['translated']))
		$filterArr['translated'] = $_GET['translated'];
	
	//if have filter		
	if(count($filter) > 0)
	{
		if(array_key_exists('original',$filter))
			$extraMenu=" and upper(b.MENUTITLE) like upper('%".$filter['parentmenu']."%')";
		
		if(array_key_exists('translated',$filter))
			$extraParent=" and upper(a.MENUTITLE) like upper('%".$filter['title']."%')";
	}
	
	$reference = "select * from FLC_TRANSLATION 
	 				where TRANS_LANGUAGE = ".$_POST['code']." and TRANS_TYPE = ".$_POST['trans_type']." 
					order by TRANS_TEXT";
	$referenceRsArr = $myQuery->query($reference,'SELECT','NAME');	
}

$general = "select * from FLC_TRANSLATION_LANGUAGE order by LANG_ID";
$generalRsArr = $myQuery->query($general,'SELECT','NAME');

//get flag;
$flagPath = 'img/flag/gif/';
$flag = scandir($flagPath);
array_shift($flag);
array_shift($flag);
?>

<script language="javascript">
function codeDropDown(elem)
{	
	if(elem.selectedIndex != 0) 
	{ 
		document.form1.showScreen.disabled = false; 
		document.form1.editCategory.disabled = false; 
		document.form1.deleteCategory.disabled = false;
	} 
	else 
	{	
		document.form1.showScreen.disabled = true; 
		document.form1.editCategory.disabled = true; 
		document.form1.deleteCategory.disabled = true;
	}
}
</script>
<script language="javascript" src="js/editor.js"></script>
<?php 
//if ajax filter
if(!isset($_GET['filter_sub_menu'])) { ?>
<div id="breadcrumbs">System Development / Configuration / Language Editor /
  <?php if($_POST["editScreen"]) echo " Edit /"?>
</div>
<h1>Language Editor </h1>
<?php } ?>
<?php 
if($insertCatRs) 
{ 
	//notification
	showNotificationInfo('New language added.');
} 
if($deleteCatRs && $deleteCatChildRs) 
{
	//notification
	showNotificationInfo('Selected language has been deleted!');
}
if($updateCatRs) 
{
	//notification
	showNotificationInfo('Language has been updated');
}
if($insertRefRs) 
{
	//notification
	showNotificationInfo('New translation has been added');
}	  
if($deleteTranslationRs) 
{
	//notification
	showNotificationInfo('Translation has been deleted!');
}	  
if($updateRefRs) 
{
	//notification
	showNotificationInfo('Translation has been updated');
}	  

?>
<form action="" method="post" name="form1">
  <?php if(!isset($_POST["editScreen"]) && !isset($_GET['filter_sub_menu']))  { ?>
  <table width="100%" border="0" cellpadding="3" cellspacing="0" class="tableContent">
    <tr>
      <th colspan="2">Language List</th>
    </tr>
    <tr>
      <td width="74" nowrap="nowrap" class="inputLabel">Available Language(s) : </td>
      <td>
        <select name="code" class="inputList" id="code" onchange="" style="width:300px;">
          <?php for($x=0; $x < count($generalRsArr); $x++) { ?>
          <option value="<?php echo $generalRsArr[$x]['LANG_ID']?>" label="<?php echo $generalRsArr[$x]['LANG_NAME']?>" <?php if($_POST['code'] == $generalRsArr[$x]['LANG_ID']) echo "selected";?>><?php echo $generalRsArr[$x]['LANG_NAME']?></option>
          <?php } ?>
        </select>
        <input name="showScreen" type="submit" class="inputButton" id="showScreen" value="Get Translation" />
      </td>
    </tr>
      <tr>
      <td width="74" nowrap="nowrap" class="inputLabel">Translation Type : </td>
      <td >
		<label><input type="radio" onchange="$('showScreen').click()" <?php if($_POST['trans_type'] == 1) echo 'checked'; if(!isset($_POST['trans_type'])) echo 'checked'; ?> name="trans_type" value="1" /> Index</label>
        <label><input type="radio" onchange="$('showScreen').click()" <?php if($_POST['trans_type'] == 2) echo 'checked';?> name="trans_type" value="2" /> Menu</label>
		<label><input type="radio" onchange="$('showScreen').click()" <?php if($_POST['trans_type'] == 3) echo 'checked';?> name="trans_type" value="3" /> Page</label>
		<label><input type="radio" onchange="$('showScreen').click()" <?php if($_POST['trans_type'] == 4) echo 'checked';?> name="trans_type" value="4" /> Component</label>
		<label><input type="radio" onchange="$('showScreen').click()" <?php if($_POST['trans_type'] == 5) echo 'checked';?> name="trans_type" value="5" /> Item</label>
		<label><input type="radio" onchange="$('showScreen').click()" <?php if($_POST['trans_type'] == 6) echo 'checked'; ?> name="trans_type" value="6" />
	     Control</label>
      </td>
    </tr>
    <tr>
      <td colspan="2" class="contentButtonFooter"><div align="right">
          <input name="newCategory" type="submit" class="inputButton" value="Add New Language" />
		  <input name="editCategory" type="submit" class="inputButton" value="Update" <?php if($_POST["code"] == "" || isset($_POST["deleteCategory"])) { ?>disabled="disabled" <?php } ?> />
          <input name="deleteCategory" type="submit" class="inputButton" value="Delete" <?php if($_POST["code"] == "" || isset($_POST["deleteCategory"])) { ?>disabled="disabled" <?php } ?> onClick="if(window.confirm('Are you sure you want to DELETE this language?\nThis will also delete ALL translations under for this language.')) {return true} else {return false}" />
        </div></td>
    </tr>
  </table>
  <?php } ?>
  <?php if($_POST["newCategory"]) { ?>
  <br>
  <table width="100%" border="0" cellpadding="3" cellspacing="0" class="tableContent">
    <tr>
      <th colspan="2">New Language Information</th>
    </tr>
    <tr>
      <td width="74" class="inputLabel">Language Name :</td>
      <td ><input name="newName" type="text" class="inputInput" id="newName" size="50" onKeyUp="form1.saveScreenNew.disabled = false"></td>
    </tr>
    <tr>
      <td class="inputLabel">Flag Icon : </td>
      <td>
		<?php for($x=0;$x < count($flag); $x++) { ?>
          <div style="width:40px; padding:3px; float:left;cursor:pointer;" onclick="this.down().checked = true;">
          <input type="radio" name="flag" id="flag" <?php if($x == 0) echo 'checked';  ?> value="<?php echo $flagPath.$flag[$x]; ?>" /><img title="<?php echo $flag[$x]; ?>" src="<?php echo $flagPath.$flag[$x]; ?>" />
          </div>
          <?php } ?>
        </td>
    </tr>
    <tr>
      <td colspan="2" bgcolor="#F7F3F7"><div align="right">
          <input name="saveScreenNew" type="submit" class="inputButton" value="Save" disabled="disabled" />
          <input name="cancelScreenNew" type="submit" class="inputButton" value="Cancel" />
        </div></td>
    </tr>
  </table>
  <?php } ?>
  <?php if($_POST["editCategory"]) { ?>
  <br>
  <table width="100%" border="0" cellpadding="3" cellspacing="0" class="tableContent">
    <tr>
      <th colspan="2">Update Language Information</th>
    </tr>
    <tr>
      <td width="74" class="inputLabel">Language Name :</td>
      <td ><input name="editName" type="text" class="inputInput" id="editName" size="50" onKeyUp="form1.saveScreenEdit.disabled = false" value="<?php echo $showCatInfoRsRows[0]["LANG_NAME"]?>"></td>
    </tr>
   
    <tr>
     <td class="inputLabel">Flag Icon : </td>
      <td>
		<?php for($x=0;$x < count($flag); $x++) { ?>
          <div style="width:40px; padding:3px; float:left;cursor:pointer;" onclick="this.down().checked = true;">
          <input type="radio" name="flag" id="flag" <?php if($showCatInfoRsRows[0]["LANG_FLAG_URL"] == $flagPath.$flag[$x]) echo 'checked';  ?> value="<?php echo $flagPath.$flag[$x]; ?>" /><img title="<?php echo $flag[$x]; ?>" src="<?php echo $flagPath.$flag[$x]; ?>" />
          </div>
          <?php } ?>
        </td>
    </tr>
    <tr>
      <td colspan="2" bgcolor="#F7F3F7"><div align="right">
          <input name="saveScreenEdit" type="submit" class="inputButton" id="saveScreenEdit" value="Save" />
          <input name="cancelScreenEdit" type="submit" class="inputButton" id="cancelScreenEdit" value="Cancel" />
        </div></td>
    </tr>
  </table>
  <?php } ?>
  <?php if($_POST["newReference"]) { ?>
  <br />
  <table width="100%" border="0" cellpadding="3" cellspacing="0" class="tableContent">
    <tr>
      <th colspan="2">Add Translation</th>
    </tr>
    <tr>
      <td width="74" class="inputLabel">Translation Text : </td>
      <td ><input name="newTransText" type="text" class="inputInput" id="newTransText" size="70" onkeyup="form1.saveScreenRefNew.disabled = false" /></td>
    </tr>
    <tr>
      <td width="74" class="inputLabel">Source ID : </td>
      <td ><input name="newSourceID" type="text" class="inputInput" id="newSourceID" size="5" onkeyup="" /></td>
    </tr>
    <tr>
      <td class="inputLabel">Item To Translate : </td>
      <td><select name="newItemTranslate" class="inputList" id="newItemTranslate">
			<?php if($_POST['trans_type'] == 2) { ?>
			<option value="FLC_MENU|MENUTITLE">Menu Title</option>
			<?php } ?>
			<?php if($_POST['trans_type'] == 3) { ?>
			<option value="FLC_PAGE|PAGETITLE">Page Title</option>
			<option value="FLC_PAGE|PAGEBREADCRUMBS">Page Breadcrumbs</option>
			<option value="FLC_PAGE|PAGEPRESCRIPT">Page Prescript</option>
			<option value="FLC_PAGE|PAGEPOSTSCRIPT">Page Postscript</option>
			<?php } ?>
			<?php if($_POST['trans_type'] == 4) { ?>
			<option value="FLC_PAGE_COMPONENT|COMPONENTTITLE">Component Title</option>
			<?php } ?>
			<?php if($_POST['trans_type'] == 5) { ?>
			<option value="FLC_PAGE_COMPONENT_ITEMS|ITEMTITLE">Item Title</option>
			<option value="FLC_PAGE_COMPONENT_ITEMS|ITEMNOTES">Item Notes</option>
			<?php } ?>
			<?php if($_POST['trans_type'] == 6) { ?>
			<option value="FLC_PAGE_CONTROL|CONTROLTITLE">Control Title</option>
			<?php } ?>
			</select></td>
    </tr>
    <tr>
      <td colspan="2" bgcolor="#F7F3F7"><div align="right">
          <input name="saveScreenRefNew" type="submit" disabled="disabled" class="inputButton" id="saveScreenRefNew" value="Save" />
          <input name="cancelScreen" type="submit" class="inputButton" id="cancelScreen" value="Cancel" />
        </div></td>
    </tr>
  </table>
  <?php } ?>
  <?php if($_POST["editReference"]) { ?>
  <br />
  <table width="100%" border="0" cellpadding="3" cellspacing="0" class="tableContent">
    <tr>
      <th colspan="2">Update Translation</th>
    </tr>
     <tr>
      <td width="74" class="inputLabel">Translation Text : </td>
      <td ><input name="editTransText" type="text" value="<?php echo $showRefRsRows[0]['TRANS_TEXT'];?>" class="inputInput" id="editTransText" size="70" onkeyup="form1.saveScreenRefEdit.disabled = false" /></td>
    </tr>
    <tr>
      <td width="74" class="inputLabel">Source ID : </td>
      <td ><input name="editSourceID" type="text"  value="<?php echo $showRefRsRows[0]['TRANS_SOURCE_ID'];?>" class="inputInput" id="editSourceID" size="5" onkeyup="" /></td>
    </tr>
    <tr>
      <td class="inputLabel">Item To Translate : </td>
      <td><select name="editItemTranslate" class="inputList" id="editItemTranslate">
			<?php if($_POST['trans_type'] == 2) { ?>
			<option value="FLC_MENU|MENUTITLE">Menu Title</option>
			<?php } ?>
			<?php if($_POST['trans_type'] == 3) { ?>
			<option value="FLC_PAGE|PAGETITLE">Page Title</option>
			<option value="FLC_PAGE|PAGEBREADCRUMBS">Page Breadcrumbs</option>
			<option value="FLC_PAGE|PAGEPRESCRIPT">Page Prescript</option>
			<option value="FLC_PAGE|PAGEPOSTSCRIPT">Page Postscript</option>
			<?php } ?>
			<?php if($_POST['trans_type'] == 4) { ?>
			<option value="FLC_PAGE_COMPONENT|COMPONENTTITLE">Component Title</option>
			<?php } ?>
			<?php if($_POST['trans_type'] == 5) { ?>
			<option value="FLC_PAGE_COMPONENT_ITEMS|ITEMTITLE">Item Title</option>
			<option value="FLC_PAGE_COMPONENT_ITEMS|ITEMNOTES">Item Notes</option>
			<?php } ?>
			<?php if($_POST['trans_type'] == 6) { ?>
			<option value="FLC_PAGE_CONTROL|CONTROLTITLE">Control Title</option>
			<?php } ?>
			</select></td>
    </tr>
    <tr>
      <td colspan="2" bgcolor="#F7F3F7"><div align="right">
          <input name="hiddenCode" type="hidden" id="hiddenCode" value="<?php echo $_POST['hiddenCode'];?>" />
          <input name="saveScreenRefEdit" type="submit" class="inputButton" id="saveScreenRefEdit" value="Save" />
          <input name="cancelScreen" type="submit" class="inputButton" id="cancelScreen" value="Cancel" />
        </div></td>
    </tr>
  </table>
  <?php } ?>
</form>
<?php if(($_POST["showScreen"] && $_POST["code"] != "") || $_GET['filter_sub_menu']) { ?>
<?php if(!isset($_GET['filter_sub_menu'])) { ?><br /><?php } ?>
<div id="language_editor_translation_list">
	<table width="100%" border="0" cellpadding="3" cellspacing="0" class="tableContent">
	  <tr>
		<th colspan="9">Translation List</th>
	  </tr>
	 <?php if(count($referenceRsArr) > 0) { ?>
	  <tr>
		<td width="15" class="listingHead">#</td>
		
		<td class="listingHead">Translated Text</td>
        <td class="listingHead">Original Text</td>
		<td width="85" class="listingHeadRight">Action</td>
	  </tr>
	  <?php for($x=0; $x < count($referenceRsArr); $x++) { ?>
	  <tr>
		<td class="listingContent"><?php echo $x+1;?>.</td>
		<td class="listingContent"><?php echo $referenceRsArr[$x]['TRANS_TEXT'];?></td>
        <td class="listingContent"><?php echo languageEditor_getTranslation($myQuery,$_POST['trans_type'],$_POST['code'],$referenceRsArr[$x]['TRANS_SOURCE_ID'],$referenceRsArr[$x]['TRANS_SOURCE_COLUMN']);?></td>
		<td nowrap="nowrap" class="listingContentRight">
		  <form id="formReference<?php echo $referenceRsArr[$x]['TRANS_ID'];?>" name="formReference<?php echo $referenceRsArr[$x]['MENUID'];?>" method="post">
			 <input name="editReference" type="submit" class="inputButton" id="editReference" value="Update" />
			<input name="deleteTrans" type="submit" class="inputButton" id="deleteReference" value="Delete" onClick="if(window.confirm('Are you sure you want to DELETE this translation?')) {return true} else {return false}"/>
			<input name="hiddenCode" type="hidden" id="hiddenCode" value="<?php echo $referenceRsArr[$x]['TRANS_ID'];?>" />
			<input name="trans_type" type="hidden" id="trans_type" value="<?php echo $_POST['trans_type'];?>" />
			<input name="code" type="hidden" id="code" value="<?php echo $_POST['code'];?>" />
		  </form>
        </td>
	  </tr>
	  <?php 		} //end for ?>
	  <?php 	}//end if 
		else 	{ ?>
	  <tr>
		<td colspan="9" class="myContentInput">No translation(s) found... </td>
	  </tr>
	  <?php 	} //end else?>
	  <tr>
		<td colspan="9" class="contentButtonFooter">
			<form id="form2" name="form2" method="post" action="">
			  <input name="code" type="hidden" id="code" value="<?php echo $_POST['code'];?>" />
			  <input name="trans_type" type="hidden" id="trans_type_code" value="<?php echo $_POST['trans_type'];?>" />
			  <input name="newReference" type="submit" class="inputButton" id="newReference" value="Add Translation" />
			  <input name="saveScreen2" type="submit" class="inputButton" value="Close" />
			</form>
        </td>
	  </tr>
	</table>
</div>
<?php } ?>