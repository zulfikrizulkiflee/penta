<?php
//validate that user have session
require_once('func_common.php');
validateUserSession();

//if edit screen submitted
if($_POST['savePassword'])
{
	//if have email
	if($_POST['userEmail'])
	{
		//update email
		$updateEmail = "update PRUSER set EMAIL = '".$_POST['userEmail']."' where USERID = ".$_SESSION['userID'];
		$updateEmailRs = $myQuery->query($updateEmail,'RUN');
		
		//notification
		showNotificationInfo('Email telah berjaya dikemaskini');
	}//eof if
	else
		showNotificationError('Ralat! Sila masukkan email!');
	
	//if have password
	if($_POST['userPassword'])
	{
		//if new password is equal
		if($_POST['userNewPassword_1'] == $_POST['userNewPassword_2'])
		{
			//check user password, correct or not
			$check = "select USERID,USERNAME,USERPASSWORD 
						from PRUSER where USERID = ".$_SESSION['userID']." 
						and USERPASSWORD = '".md5($_POST["userPassword"])."'";
			$checkRs = $myQuery->query($check,'SELECT','NAME');
			$checkRsCount = count($checkRs);
		
			//if password is correct
			if($checkRsCount)
			{	
				//check length of password, if less than min length:ref
				if(strlen($_POST['userNewPassword_1']) < CPWD_MIN_LENGTH)
				{	
					//notification
					showNotificationError(CPWD_MSG_ERR_3);
				}//eof if
				
				//if length > than min, update user password
				else
				{
					//update password
					$update = "update PRUSER 
								set USERPASSWORD = '".md5($_POST['userNewPassword_1'])."'
								where USERID = ".$_SESSION['userID'];
					$updateSuccess = $myQuery->query($update,'RUN');
					
					//notification
					showNotificationInfo(CPWD_MSG_SUCCESS.' Sila <a href="index.php?logout=true">log masuk</a> semula untuk menggunakan sistem.',3);
					echo '<script>setTimeout("window.location=\'index.php?logout=true\';",3000);</script>';
				}//eof else
			}//eof if
			else
			{
				//notification
				showNotificationError(CPWD_MSG_ERR_1);
			}//eof else
		}//eof if
		else
		{
			//notification
			showNotificationError(CPWD_MSG_ERR_2);
		}//eof else
	}//eof if
}//eof if
else
{
	//1st time user
	if($_GET['s'])
		showNotificationError('Sila tukar kata laluan dan kemaskini email terkini anda untuk menggunakan sistem ini!',5);
}//eof else

$getEmail = "select EMAIL from PRUSER where USERID = ".$_SESSION['userID'];
$getEmailRs = $myQuery->query($getEmail, 'SELECT', 'NAME');
?>

<script>
//show password strength
function showPasswordStrength(password)
{
	var pwdStrength = checkPasswordStrength(password);
	
	if(pwdStrength == 'Weak')			var pwdStrengthMy = 'Lemah';
	else if(pwdStrength == 'Better')	var pwdStrengthMy = 'Baik';
	else if(pwdStrength == 'Medium')	var pwdStrengthMy = 'Sederhana';
	else if(pwdStrength == 'Strong')	var pwdStrengthMy = 'Kuat';
	else if(pwdStrength == 'Strongest')	var pwdStrengthMy = 'Paling Kuat';
	else								var pwdStrengthMy = '';
	
	document.getElementById('passwordStrength').innerHTML = pwdStrengthMy;
	document.getElementById('passwordStrength').className = 'passwordStrength'+pwdStrength;
}//eof function
</script>

<div id="breadcrumbs">Profil / Tukar Kata Laluan</div>
<h1>Tukar Kata Laluan</h1>

<form method="post" name="form1">
  <table border="0" cellpadding="3" cellspacing="0" class="tableContent">
    <tr>
      <th colspan="2">Maklumat Email</th>
    </tr>
    <tr>
      <td class="inputLabel">Email : </td>
      <td>
      	<input name="userEmail" type="text" class="inputInput" id="userEmail" size="50" value="<?php echo $getEmailRs[0]['EMAIL'];?>" />
        <label class="labelMandatory">*</label><br />
        <label class="labelNote">Nota: Akan digunakan sekiranya anda terlupa kata laluan.</label>
      </td>
    </tr>
  </table>
  <br />
  <table border="0" cellpadding="3" cellspacing="0" class="tableContent">
    <tr>
      <th colspan="2">Maklumat Kata Laluan</th>
    </tr>
    <tr>
      <td class="inputLabel">Kata Laluan Asal : </td>
      <td>
      	<input name="userPassword" type="password" class="inputInput" id="userPassword" size="40" />
      </td>
    </tr>
    <tr>
      <td class="inputLabel">Kata Laluan Baru : </td>
      <td>
      	<input name="userNewPassword_1" type="password" class="inputInput" id="userNewPassword_1" size="40" onkeyup="showPasswordStrength(this.value);" value="" />
        <label id="passwordStrength"></label><br />
        <label class="labelNote">Nota: Minimum <?php echo CPWD_MIN_LENGTH;?> aksara.</label>
      </td>
    </tr>
    <tr>
      <td class="inputLabel">Kata Laluan Baru [Semula] : </td>
      <td>
      	<input name="userNewPassword_2" type="password" class="inputInput" id="userNewPassword_2" size="40" onkeyup="if(this.value == document.getElementById('userNewPassword_1').value) document.getElementById('passwordCheck').innerHTML = '*  <?php echo CPWD_MSG_ERR_5;?>!'; else document.getElementById('passwordCheck').innerHTML = '* <?php echo CPWD_MSG_ERR_4;?>'" value="" />
        <label id="passwordCheck"></label>
      </td>
    </tr>
    <tr>
      <td class="contentButtonFooter" colspan="2">
        <input name="savePassword" type="submit" class="inputButton" id="savePassword" value="Simpan" />
        <input name="cancelScreenNew" type="reset" class="inputButton" value="Semula" />
      </td>
    </tr>
  </table>
</form>