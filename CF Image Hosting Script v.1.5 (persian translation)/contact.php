<?php

/**************************************************************************************************************
 *
 *   CF Image Hosting Script
 *   ---------------------------------
 *
 *   Author:    codefuture.co.uk
 *   Version:   1.5
 *   Date:       07 March 2012
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
 *   Used For:     Contact Us Form
 *   Last edited:  07/03/2012
 *
 *************************************************************************************************************/


	require_once('./inc/config.php');

/*
 * check to see if the page has been hidden (set in the admin panel),
 * if so then send user to the index/home page
 */
	if(!$settings['SET_HIDE_CONTACT']){
		header('Location: index.php');
		exit();
	}

/*
 * Set page variable values
 */
	$errors = array();
	$emailSent = false;

/*
 * check to see if form has been posted
 */
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {

	/*
	 * Clean input variable
	 */
		$name		= input($_POST['name']);
		$email		= input($_POST['email']);
		$comment	= input($_POST['comment']);
		$captcha	= input($_POST['captcha']);

	/*
	 * check for a name
	 */
		if(empty($name))	$errors['name'] = $lang["site_contact_err_name_blank"];

	/*
	 * check for a email address
	 */
		if(empty($email)){
			$errors['email'] = $lang["site_contact_err_email_blank"];
		}elseif(!eregi('^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$', $email) ||
				preg_match("/(%0A|%0D|\n+|\r+)(content-type:|to:|cc:|bcc:)/i", $email) ||
				preg_match("/(%0A|%0D|\n+|\r+)/i", $email)){
			$errors['email'] = $lang["site_contact_err_email_invalid"];
		}

	/*
	 * check for the email comment
	 */
		if(empty($comment)) $errors['comment'] = $lang["site_contact_err_comment_blank"];

	/*
	 * Set the captcha Error Messages (check_captcha())
	 */
		define('ERROR_MESSAGE_CAPTCHA'				, $lang["site_contact_err_captcha_blank"]);
		define('ERROR_MESSAGE_CAPTCHA_INVALID'	, $lang["site_contact_err_captcha_invalid"]);
		define('ERROR_MESSAGE_CAPTCHA_COOKIE'	, $lang["site_contact_err_captcha_cookie"]);

	/*
	 * check for captcha errors
	 */
		include_once('lib/captcha.class.php');
		check_captcha($_POST['captcha'],false);
		if(isset($error_captcha)){$errors['captcha'] = $error_captcha;}


	/*
	 * if no errors send email
	 */
		if( !is_errors() ){

			if(strtoupper(substr(PHP_OS, 0, 3) == 'WIN')) $ent = "\r\n";
			elseif(strtoupper(substr(PHP_OS, 0, 3) == 'MAC')) $ent = "\r";
			else $ent = "\n";

			$comment  = ' Name: '.$name.$ent.' E-mail: '.$email.$ent.' Comment: '.$ent.$comment;
			$boundary = '----=_NextPart_' . md5(rand());
			$headers  = 'From: ' . $name . '<' . $email . '>' . $ent;
			$headers .= 'X-Mailer: PHP/' . phpversion() . $ent;
			$headers .= 'MIME-Version: 1.0' . $ent;
			$headers .= 'Content-Type: multipart/mixed; boundary="' . $boundary . '"' . $ent . $ent;
			$message  = '--' . $boundary . $ent;
			$message .= 'Content-Type: text/plain; charset="utf-8"' . $ent;
			$message .= 'Content-Transfer-Encoding: base64' . $ent . $ent;
			$message .= chunk_split(base64_encode($comment));

			ini_set('sendmail_from', $email);
			mail($settings['SET_CONTACT'], 'Email from contact form on '.$settings['SET_TITLE'], strip_tags(html_entity_decode($message)), $headers);
			$emailSent = true;
		}
	}


/*
 * Set page variable values
 */
	$menu = 'contact_page';
	$page_title = ' - '.$lang["site_menu_contact"];

	include './header.php';
?>
		<div class="contentBox">
			<div id="contact">
				<h2><?php echo $lang["site_menu_contact"];?></h2>
				<div class="contact_box">
<?php if($emailSent){ // if email is sent say thanks ?>
				<p class="teaser"><?php echo sprintf($lang["site_contact_thank_you"],$name);?></p>
<?php }else{ // show contact form ?>
				<p class="teaser"><?php echo $lang["site_contact_des"];?></p>
				<div id="form">
					<table align="center">
						<form method="post" action="contact.php">
							<?php echo is_errors('name');?>
							<tr>
								<td><?php echo $lang["site_contact_form_name"];?></td>
								<td><input name="name" type="text" id="name" class="text_input" size="24" value="<?php echo err_ReSet('name');?>" /></td>
							</tr>
							<?php echo is_errors('email');?>
							<tr>
								<td><?php echo $lang["site_contact_form_email"];?></td>
								<td><input name="email" type="text" id="email" class="text_input" size="24" value="<?php echo err_ReSet('email');?>" /></td>
							</tr>
							<?php echo is_errors('comment');?>
							<tr>
								<td><?php echo $lang["site_contact_form_comment"];?></td>
								<td><textarea name="comment" id="comment" rows="8" cols="5" class="text_input long"><?php echo err_ReSet('comment');?></textarea></td>
							</tr>
							<?php echo is_errors('captcha');?>
							<tr>
								<td><?php echo $lang["site_contact_form_captcha"];?></td>
								<td><input name="captcha" type="text" id="captcha" size="24" class="text_input" /></td>
							</tr>
							<tr>
								<td><?php echo $lang["site_contact_form_captcha_img"];?></td>
								<td><a href="#Reload Captcha" onclick="document.getElementById('captchaImg').src = 'img/captcha.img.php?img<?php echo $CAPTCHA_BG;?>&amp' + Math.random(); return false" class="creload"><img src="img/captcha.img.php?img<?php echo $CAPTCHA_BG;?>&amp<?php echo time();?>" alt="captcha" title="<?php echo $lang["site_contact_form_captcha_image_title"];?>" class="captcha" id="captchaImg" /></a></td>
							</tr>
							<tr id="submit">
								<td colspan="2"><input type="submit" class="button" value="<?php echo $lang["site_contact_form_send"];?>" /></td>
							</tr>
						</form>
					</table>
				</div>
<?php } // end show contact form/thanks  ?>
			</div>
			</div>
		</div>
<?php
	include './footer.php';

/*
 * is_errors($name=null)
 * Checks to see if $name has a error and if so returns the error in a html span
 *
 */
	function is_errors($name=null){
		global $errors;
		if(is_null($name) && count($errors)>0)return true;
		if(isset($errors[$name]))
			return '<span class="error_message">'.$errors[$name].'</span>';
		return '';
	}

/*
 * err_ReSet($name)
 * Checks to see if there is a error and $name is not empty, if so returns $_POST[$name]
 *
 */
	function err_ReSet($name){
		global $errors;
		if(count($errors)>0 && isset($_POST[$name]))
			return input($_POST[$name]);
		return '';
	}
