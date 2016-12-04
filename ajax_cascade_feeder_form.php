<?php
//include stuff needed for session, database connection, and stuff
include('system_prerequisite.php');

//have item
if($_GET['item'])
{
	//get item query
	$sql = "select a.*
				from FLC_PAGE_COMPONENT_ITEMS a, FLC_PAGE_COMPONENT b, FLC_PAGE c
				where a.COMPONENTID = b.COMPONENTID and b.PAGEID = c.PAGEID
					and c.MENUID = ".$_SESSION['menuID']." and a.ITEMNAME = '".$_GET['item']."'";
	$sqlRs = $myQuery->query($sql,'SELECT','NAME');

	$sql = $sqlRs[0]['ITEMLOOKUP'];

	//and val=".$_GET['val'];
	$sql = str_replace('\\','',convertDBSafeToQuery($sql));
	$sql = str_replace('"',"'",$sql);
	$sql = str_replace('&quot;',"'",$sql);

	//run the itemlookup query
	$run = "select * from (".$sql.") a ".str_replace("\\",'',$_GET['where']);
	$runRs = $myQuery->query($run,'SELECT','NAME');
	$runRsCount = count($runRs);

	//generate javascript
	if($sqlRs[0]['ITEMJAVASCRIPTEVENT'] != '')
	{
		//get javascript event
		$getJavascriptEvent = $myQuery->query("select DESCRIPTION1 from REFSYSTEM
														where MASTERCODE = (select REFERENCECODE from REFSYSTEM where MASTERCODE = 'XXX'
																			and DESCRIPTION1 = 'JS_EVENT')
														and REFERENCECODE = '".$sqlRs[0]['ITEMJAVASCRIPTEVENT']."'",'SELECT','NAME');

		if($getJavascriptEvent[0]["DESCRIPTION1"] != "")
			$js .= $getJavascriptEvent[0]["DESCRIPTION1"]." = \"".convertDBSafeToQuery($sqlRs[0]['ITEMJAVASCRIPT'])."\"";
	}
}//eof if
?>
<select name="<?php echo $_GET['item'];?>" id="<?php echo $_GET['item'];?>" <?php echo $js;?> class="inputList">
<option >&nbsp;</option>
<?php for($x=0; $x<$runRsCount; $x++){?>
<option value="<?php echo $runRs[$x]['FLC_ID'];?>"><?php echo $runRs[$x]['FLC_NAME'];?></option>
<?php }?>
</select>