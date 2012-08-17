<?php

/**************************************************************************************************************
 *
 *   CF Image Hosting Script
 *   ---------------------------------
 *
 *   Author:    codefuture.co.uk
 *   Version:   1.5
 *   Date:      09 March 2012
 *
 *   You can download the latest version from: http://codefuture.co.uk/projects/imagehost/
 *
 *   Copyright (c) 2010-2012 CodeFuture.co.uk
 *   This file is part of the CF Image Hosting Script.
 *
 *   THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 *   EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 *   FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR
 *   COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY,
 *   WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF
 *   OR  IN  CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 *
 *
 *   You may not modify and/or remove any copyright notices or labels on the software on each
 *   page (unless full license is purchase) and in the header of each script source file.
 *
 *   You should have received a full copy of the LICENSE AGREEMENT along with
 *   Codefuture Image Hosting Script. If not, see http://codefuture.co.uk/projects/imagehost/license/.
 *
 *
 *   ABOUT THIS PAGE -----
 *   Used For:     Admin Login Page
 *   Last edited:  09/03/2012
 *
 *************************************************************************************************************/

/*
 * check page is being loaded from within the admin.php page
 * If not send user back admin login page
 */
	if(!isset($admin_page) && $admin_page){
		header('Location: ../admin.php');
		exit();
	}


// LOGOUT
	if ($act == 'logout'){
		session_unset();
		session_destroy();
		if(isset($_COOKIE['Auth'])){// remove auto logo cookie
			$cookie_time = (3600 * 24 * 30); // 30 days
			setcookie ('Auth', '', time() - $cookie_time);
		}
		$Suc['logged_out'] = $lang["admin_log_out_suc"];
	}

// auto logo on
// Check if the cookie exists
	if(isset($_COOKIE['Auth']) && $act != 'logout'){
	// Register the session
		$_SESSION['loggedin'] = true;
		$_SESSION['set_name'] = $_COOKIE['Auth'];
	}

// LOGIN
	if(isset($_POST['enter']) && isset($_POST['passWord']) && isset($_POST['userName']) ){
		if(md5(md5(input($_POST['passWord'])).$settings['SET_SALTING']) == $settings['SET_PASSWORD'] &&
			input($_POST['userName']) == $settings['SET_USERNAME']){
		// Autologin Requested?
			if(isset($_POST['autologin']) && input($_POST['autologin']) == 1){
				$cookie_time = (3600 * 24 * 30); // 30 days
				$hash = md5($settings['SET_USERNAME'].$settings['SET_SALTING'].$settings['SET_PASSWORD']);
				setcookie ('Auth', $hash, time() + $cookie_time);
			}
			$_SESSION['loggedin'] = true;
			$_SESSION['set_name'] = md5($settings['SET_USERNAME'].$settings['SET_SALTING'].$settings['SET_PASSWORD']);
		}else{
			$Err['login']= $lang["admin_log_err"];
		}
	}

	$reset_password = false;

////////////////////////////////////////////////////////////////////////////////////
// FORGOT YOUR PASSWORD - SEND LINK

	if (isset($_POST['forgot'])){

		if(isset($_POST['email']) && input($_POST['email']) == $settings['SET_CONTACT']){
		
		// Always set content-type when sending HTML email
			$headers = "MIME-Version: 1.0" . "\r\n";
			$headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";
		// More headers
			$headers .= 'From: '.$settings['SET_CONTACT']."\r\n";
			$subject = $settings['SET_TITLE'].' - '.$lang["admin_log_forgot_password_email_subject"];

			$find = array( '{reset_url}', '{user_name}');
			$reset_id = md5(time().$settings['SET_SALTING']);
			$replace = array(
							'reset_url' => '<a href="'.$settings['SET_SITEURL'].'/admin.php?reset='.$reset_id.'">'.$settings['SET_SITEURL'].'/admin.php?reset='.$reset_id.'</a>',
							'user_name' => $settings['SET_USERNAME']
							);

			$msg = str_replace($find, $replace, $lang["admin_log_forgot_password_email_body"]);
			@mail($settings['SET_CONTACT'],$subject,$msg,$headers);

			if(savefile(array('id'=>$reset_id,'date'=>time()),$DIR_DATA.'rs.cf')){
				$Suc['forgot_password'] = $lang["admin_log_forgot_password_suc"];
				$forgot_note =1;
			}
		}else{
			$Err['forgot_password'] = $lang["admin_log_forgot_password_email_err"];
			$forgot_note =1;
		}
	}

// FORGOT YOUR PASSWORD - RESET LINK CHECK
	if (isset($_GET['reset'])){
		if(file_exists($DIR_DATA.'rs.cf')){
			$reset_db = loadfile($DIR_DATA.'rs.cf');
			if ($reset_db['id'] == input($_GET['reset']) &&	($reset_db['date']+3600) > time()){
				$reset_password = true;
				$_SESSION['reset_password']= input($_GET['reset']);
			}else{
				$Err['forgot_password'] = $lang["admin_log_forgot_password_err"];
			}
		}else{
			$Err['forgot_password'] = $lang["admin_log_forgot_password_err"];
		}
	}

// FORGOT YOUR PASSWORD - NEW PASSWORD
	if(isset($_POST['action']) && $_POST['action'] == 'reset'){
	//check reset code
		if(file_exists($DIR_DATA.'rs.cf')){
			$reset_db2 = loadfile($DIR_DATA.'rs.cf');
			if ($reset_db2['id'] == $_SESSION['reset_password'] && ($reset_db2['date']+3600) > time()){
	
			//Password
				if(!empty($_POST['newPassword']) && !empty($_POST['newConfirm'])){
					if (input($_POST['newPassword']) == input($_POST['newConfirm'])){
						$settings['SET_PASSWORD'] = md5(md5(input($_POST['newPassword'])).$settings['SET_SALTING']);
							// save settings

						if(saveSettings('inc/set.php',$settings)){
								$Suc['password_update'] = $lang["admin_log_forgot_password_update"];
								@unlink($DIR_DATA.'rs.cf');
						}else{
								$Err['password_update'] = $lang["admin_set_err_saveing_settings"];
								$reset_password = true;
						}
					}else{
						$Err['password_update'] = $lang["admin_set_err_password_wrong"];
						$reset_password = true;
					}
				}else{
					$Err['password_update'] = $lang["admin_set_err_password_both"];
					$reset_password = true;
				}
			}else{// end check code
				$Err['forgot_password'] = $lang["admin_log_forgot_password_err"]; 
			}
		}else{// end check file
			$Err['forgot_password'] = $lang["admin_log_forgot_password_err"];
		}
	}

// END FORGOT YOUR PASSWORD
////////////////////////////////////////////////////////////////////////////////////

if (!checklogin()) {

	$page['id']					= 'logon';
	$page['title']				= $lang["admin_log_title"];
	$page['description']	= '';
	require_once('admin/admin_page_header.php');
	echo "<!-- admin login -->";

	if (!$reset_password){// LOGIN PAGE?>
		<div class="tabs">
			<h2><?php echo $lang["admin_log_title"];?></h2>
			<ul class="tabs_list">
				<li><a href="#tabs-1"><?php echo $lang["admin_log_title"];?></a></li>
				<li><a href="#tabs-2" title="<?php echo $lang["admin_log_forgot_password"];?>"><?php echo $lang["admin_log_forgot_password"];?></a></li>
			</ul>
			<div class="panes">
				<div id="tabs-1" class="tabbox">
<?php
if(!isset($forgot_note)){
	success_note($Suc);
	error_note($Err);
}
?>
					<form id="login-form" action="admin.php#tabs-1" method="POST">
							<div class="code_box"><label for="UserName"><?php echo $lang["admin_log_username"];?></label><input type="text" id="userName" name="userName" class="text_input"/></div>
							<div class="code_box"><label for="password"><?php echo $lang["admin_log_password"];?></label><input type="password" id="passWord" name="passWord" class="text_input"/></div>
							<div class="remember"><input type="checkbox" name="autologin" id="autologin" value="1"><label for="autologin"><?php echo $lang["admin_log_remember_me"]?></label></div>
							<input type="submit" class="button button_cen" name="enter" value="<?php echo $lang["admin_log_button"];?>"/><br/>
							<input type="hidden" name="action" value="login" style="display: none;">
					</form>
				</div>
				<div id="tabs-2" class="tabbox">
<?php
if(isset($forgot_note)){
	success_note($Suc);
	error_note($Err);
}
?>
					<form id="forgot-form" action="admin.php#tabs-2" method="POST">
							<div class="code_box"><label for="email"><?php echo $lang["admin_log_your_email"];?></label><input type="text" id="email" name="email" class="text_input"/></div>
							<input type="submit" class="button button_cen" name="enter" value="<?php echo $lang["admin_log_button"];?>"/><br/>
							<input type="hidden" name="forgot" value="forgot" style="display: none;">
					</form>
				</div>
			</div>
		<div class="clear"></div>
		</div>
<?php
	}// END LOGIN
	else
	{// RESET PASSWORD

	success_note($Suc);
	error_note($Err);
?>
<!-- reset password page -->
		<h2 class="title"><?php echo $lang["admin_log_forgot_password_title"];?></h2>
		<form id="reset-form" action="admin.php" method="POST">
			<div class="code_box"><label><?php echo $lang["admin_set_new_password"];?> :</label><input class="text_input" type="password" name="newPassword" value="" size="20" /></div>
			<div class="code_box"><label><?php echo $lang["admin_set_confirm_new_password"];?> :</label><input class="text_input" type="password" name="newConfirm" value="" size="20" /></div>
			<input type="submit" class="button button_cen" name="enter" value="<?php echo $lang["admin_set_save_button"];?>"/>
			<input type="hidden" name="action" value="reset" style="display: none;">
		</form>
		<div class="clear"></div>
<?php
	}// END RESET PASSWORD


	require_once('admin/admin_page_footer.php');
	die();
	exit;

} // END NOT LOGGED IN PAGES

