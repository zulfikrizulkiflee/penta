<?php 
//LAST EDITED: 20030329 0435PM CKM
if($componentArr[$x]['COMPONENTTYPE'] == 'tabnav'){?>
<div id="<?php echo $componentArr[$x]['COMPONENTNAME'];?>" <?php echo $js[$x];?> >
<script>
function goToTab(direction,curTab)
{
	if(direction == 'prev')
	{	
		if($('tab_' + $F(curTab)).previous(1) != undefined)
			$('tab_' + $F(curTab)).previous(1).down().onclick();
	}	
	else if(direction == 'next')
	{
		if($('tab_' + $F(curTab)).next(1) != undefined)
			$('tab_' + $F(curTab)).next(1).down().onclick();
	}
}

var timeOut;
function scrollTab(direction,tabdiv)
{	
	var speed = 3;
	var tabdivWidth = tabdiv.down().offsetWidth;
	var contentWidth = $('content').offsetWidth;
	
	if(direction == 'prev')
	{			
		//if tab is bigger than the content viewport, scroll
		if((tabdivWidth + 23*2) > contentWidth)
		{
			if(parseInt(tabdiv.down().style.left) < tabdiv.offsetWidth && parseInt(tabdiv.down().style.left) < -1)
			{	
				tabdiv.down().style.left = (parseInt(tabdiv.down().style.left) + 1) + 'px';
				timeOut = setTimeout("scrollTab('prev',$('tabdiv_<?php echo $componentArr[$x]['COMPONENTNAME']; ?>'))",speed);
			}
			else {}
		}	
		else {}
	}
	else if(direction == 'next')
	{
		//if tab is bigger than the content viewport, scroll
		if((tabdivWidth + 23*2) > contentWidth)
		{
			if(parseInt(tabdiv.down().style.left) > contentWidth - tabdiv.down().offsetWidth - 114)
			{					
				tabdiv.down().style.left = (parseInt(tabdiv.down().style.left) - 1) + 'px';
				timeOut = setTimeout("scrollTab('next',$('tabdiv_<?php echo $componentArr[$x]['COMPONENTNAME']; ?>'))",speed);
			}
		}
	}
	
	else if(direction == 'stop')
		clearTimeout(timeOut);
}
</script>
<?php 
//get list of tabbed components
//check if component exists
$checkExists = "select COMPONENTID, COMPONENTNAME 
					from FLC_PAGE_COMPONENT 
					where COMPONENTID in (".$componentArr[$x]['COMPONENTTABMEMBER'].")
					and COMPONENTSTATUS = 1
					order by COMPONENTORDER";
$checkExistsRs = $myQuery->query($checkExists,'SELECT','NAME');

$tabMember = array();
$tabMemberName = array();
for($g=0; $g < count($checkExistsRs); $g++)
{	
	$tabMember[] = $checkExistsRs[$g]['COMPONENTID'];
	$tabMemberName[] = $checkExistsRs[$g]['COMPONENTNAME'];
}

//if theres tabbed components selected
if(count($tabMember) > 0) {	?>
	<input type="hidden" id="currentTab_<?php echo $componentArr[$x]['COMPONENTNAME']; ?>" />
	<div style="margin-left:20px; width:23px; float:left;">
		<table style="height:26px;" cellspacing="0" cellpadding="0">
			<tr>
				<td class="tabNaviLeft" onmouseout="scrollTab('stop',$('tabdiv_<?php echo $componentArr[$x]['COMPONENTNAME']; ?>'))" onmouseover="scrollTab('prev',$('tabdiv_<?php echo $componentArr[$x]['COMPONENTNAME']; ?>'));">
					<a href="javascript:void(0)" title="Previous Tab"><img src="img/tab_prev.png" /></a>
				</td>
			</tr>
		</table>
	</div>
	<div id="tabdiv_<?php echo $componentArr[$x]['COMPONENTNAME']; ?>" style="width:200px; overflow:hidden; float:left;background-color:#F3F3F3;">
		<table style="margin-left:0px; height:26px; position:relative; left:-1px; right:0px;" cellspacing="0" cellpadding="0">
			<tr>
	<?php
	$jsTab = array();
	
	//generate show/hide javascript
	for($k=0; $k < count($tabMember); $k++)
	{
		$showHideStr = '';
		
		for($l=0; $l < count($tabMember); $l++)
		{	
			//if currently selected tab
			if($tabMemberName[$k] == $tabMemberName[$l]) 
				$showHideStr .= "	$('".$tabMemberName[$l]."').show(); 
									$('tab_".$tabMemberName[$l]."').className = 'tabMemberActive';
									$('currentTab_".$componentArr[$x]['COMPONENTNAME']."').value = '".$tabMemberName[$l]."'; 
									var footer = $('footer_".$tabMemberName[$l]."');
									
									if(footer != undefined)										
										$('footer_".$tabMemberName[$l]."').show(); ";
			else
				$showHideStr .= "	$('".$tabMemberName[$l]."').hide(); 
									$('tab_".$tabMemberName[$l]."').className = 'tabMember';
									var footer = $('footer_".$tabMemberName[$l]."');
									
									if(footer != undefined)
										$('footer_".$tabMemberName[$l]."').hide(); ";
		}
		$jsTab[] = $showHideStr;
	}
	
	//generate tab
	for($k=0; $k<count($tabMember); $k++)
	{
		$getTabRs = $myQuery->query("select * from FLC_PAGE_COMPONENT where COMPONENTID = ".$tabMember[$k],'SELECT','NAME');
		?>
				<td nowrap id="tab_<?php echo $getTabRs[0]['COMPONENTNAME'];?>" class="tabMember" style="cursor:pointer; <?php if($k+1 == count($tabMember)){?>border-right:1px solid #B3C5D7;<?php }?>" onclick="<?php echo $jsTab[$k];?>">
					<?php echo $getTabRs[0]['COMPONENTTITLE'];?>
				</td>
	<?php } ?>
			</tr>
		</table>
	</div>
	<div style="margin-right:0px; width:23px; float:left;">
		<table style="height:26px;" cellspacing="0" cellpadding="0">
			<tr>
				<td class="tabNaviRight" onmouseout="scrollTab('stop',$('tabdiv_<?php echo $componentArr[$x]['COMPONENTNAME']; ?>'))" onmouseover="scrollTab('next',$('tabdiv_<?php echo $componentArr[$x]['COMPONENTNAME']; ?>'));">
					<a href="javascript:void(0)" title="Next Tab"><img src="img/tab_next.png" /></a>
				</td>
			</tr>
		</table>
	</div>
	<div style="clear:both"></div>
	<script>
	//to select first tab on page load
	addLoadEvent(function() { 
		<?php echo $jsTab[0]; ?>
		var tabListStr = '<?php echo implode(',',$tabMemberName); ?>';
		var tabListArr = tabListStr.split(',');
		var bodyWidth = jQuery('body').width()-274;				//asal nye -310
		
		jQuery('#tabdiv_<?php echo $componentArr[$x]['COMPONENTNAME']; ?>').css('width',bodyWidth-56+'px');
		jQuery('#<?php echo $componentArr[$x]['COMPONENTNAME']; ?> #tableContent').css('width',(bodyWidth)+'px').attr('width','');
		
		for(var x=0; x < tabListArr.length; x++)
		{
			//jQuery('#'+tabListArr[x]+' thead th').eq(0).hide();			//to hide the comp title
			jQuery('#'+tabListArr[x]).css('overflow','auto').css('margin-left','20px').css('padding','0px').css('width',(bodyWidth-10)+'px');
			jQuery('#'+tabListArr[x]+' #tableContent').css('padding','0px').css('margin','0px').css('width','100%');
		}
	});
	</script>
<?php } ?>
</div>
<?php }//end if tabnav ?>
