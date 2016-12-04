			<div id="footer">
				<div id="bottom">
					<!-- FOOTER SECTION -->
					<?php if(FOOTER_ENABLED && $_GET['FOOTER_ENABLED'] =="") {?>

					<?php echo FOOTER_TEXT;?>

					<?php } ?>
					<!-- //END FOOTER SECTION -->
					<!-- //END MAIN CONTENT SECTION -->

					<!-- PAGE GENERATION TIME -->
					<?php if(PAGE_RESPONSE_ENABLED) { ?>
					<div id="<?php echo PAGE_RESPONSE_ID?>">Response time: <?php echo pageGenerationTime($start,' secs');?> </div>
					<?php } ?>
					<!-- //END PAGE GENERATION TIME -->
				</div>

				<!-- SESSION TIMEOUT SECTION -->
				<?php if(SESSION_TIMEOUT_DURATION){?>
				<div id="sessionTimeout" style="display:none">
					<div id="sessionTimeoutBg"></div>
					<div id="sessionTimeoutDialog">
						<img src="img/warning.png"/>
						<br/><br/>
						You have been on this page for too long.<br/>Your session will expire in <br/><br/><span id="sessionTimeoutLabel"></span>
						<br/><br/>
						<input id="sessionContinue" name="sessionContinue" type="button" value="Continue?" onclick="continueSession(<?php echo SESSION_TIMEOUT_DURATION;?>*60*1000);" />
						<script>setTimeout("checkSessionTimeout();",<?php echo SESSION_TIMEOUT_DURATION;?>*60*1000); //monitorSessionActivity(<?php echo SESSION_TIMEOUT_DURATION;?>*60*1000);</script>
					</div>
				</div>
				<?php }?>
				<!-- SESSION TIMEOUT SECTION -->
			</div>
		</div>
	</body>
</html>