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
 *   Used For:     Admin page header
 *   Last edited:  07/03/2012
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


////////////////////////////////////////////////////////////////////////////////////
//SAVE NEW SETTINGS

	if(isset($_POST['changesettings'])) {

	//Password
		if(!empty($_POST['oldPassword']) && !empty($_POST['newPassword']) && !empty($_POST['newConfirm'])){
			if (input($_POST['newPassword']) == input($_POST['newConfirm'])){
				if (md5(md5(input($_POST['oldPassword'])).$settings['SET_SALTING']) == $settings['SET_PASSWORD']){
					$settings['SET_PASSWORD'] = md5(md5(input($_POST['newPassword'])).$settings['SET_SALTING']);
				}else{
					$Err['password_wrong'] = $lang["admin_set_err_password_wrong"];
				}
			}else{
				$Err['password_wrong'] = $lang["admin_set_err_password_wrong"];
			}
		}else if(!empty($_POST['oldPassword']) || !empty($_POST['newPassword']) || !empty($_POST['newConfirm'])){
			$Err['password_wrong'] = $lang["admin_set_err_password_both"];
		}

	//UserName
		if (!empty($_POST['setUserName'])){
			$settings['SET_USERNAME'] = input($_POST['setUserName']);
		}else{
			$Err['err_username'] = $lang["admin_set_err_username"];
		}

	//email
		if(eregi('^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$', input($_POST['setEmail']))){
			$settings['SET_CONTACT'] = input($_POST['setEmail']);
		}else{
			$Err['email_invalid'] = $lang["admin_set_err_email_invalid"];
		}

	// Script Url
		$scriptUrl = input($_POST['setScriptUrl']);
		if(substr($scriptUrl, -1) == "/") $scriptUrl = substr($scriptUrl, 0, -1);
		if (!empty($scriptUrl)){
			$settings['SET_SITEURL'] = $scriptUrl;
		}else{
			$Err['err_script_url']= $lang["admin_set_err_script_url"];
		}

		$settings['SET_WATERMARK']			= input($_POST['setWaterMark']) == 1? 1:0;
		$settings['SET_WATERMARK_TEXT']		= input($_POST['setWatermarkText']);
		$settings['SET_WATERMARK_PLACED']	= (int)input($_POST['setWatermarkPlaced']);
		$settings['SET_WATERMARK_IMAGE']	= input($_POST['setWatermarkImage']);

		$settings['SET_MOD_REWRITE']		= input($_POST['setModRewrite']) == 1? 1:0;
		$settings['SET_AUTO_DELETED']		= input($_POST['setAutoDeleted']) == 1? 1:0;
		$settings['SET_HIDE_SEARCH']		= input($_POST['setHideSearch']) == 1? 1:0;
		$settings['SET_HIDE_CONTACT']		= input($_POST['setHideContact']) == 1? 1:0;
		$settings['SET_HIDE_TOS']			= input($_POST['setHideTos']) == 1? 1:0;
		$settings['SET_HIDE_GALLERY']		= input($_POST['setHideGallery']) == 1? 1:0;
		$settings['SET_HIDE_FAQ']			= input($_POST['setHideFaq']) == 1? 1:0;
		$settings['SET_HIDE_FEED']			= input($_POST['setHideFeed']) == 1? 1:0;
		$settings['SET_HIDE_SITEMAP']		= input($_POST['setHideSitemap']) == 1? 1:0;
		$settings['SET_EMAIL_REPORT']		= input($_POST['setEmailReport']) == 1? 1:0;
		$settings['SET_ALLOW_REPORT']		= input($_POST['setAllowReport']) == 1? 1:0;
		$settings['SET_MAX_BANDWIDTH']		= (int)empty($_POST['setMaxBandwidth'])?0:$_POST['setMaxBandwidth'];
		$settings['SET_TITLE']				= input($_POST['setTitle']);
		$settings['SET_SLOGAN']				= input($_POST['setSlogan']);
		$settings['SET_COPYRIGHT']			= input($_POST['setCopyright']);
		$settings['SET_MAXSIZE']			= (int)input($_POST['setMaxSize']);
		$settings['SET_IMG_ON_PAGE']		= (int)input($_POST['setImgOnPage']);
		$settings['SET_MAX_UPLOAD']			= (int)input($_POST['setMaxUpload']);
		$settings['SET_THEME']				= input($_POST['setTheme']);
		$settings['SET_GOOGLE_ANALYTICS']	= input($_POST['setAnalytics']);
		$settings['SET_GOOGLE_CHANNAL']		= input($_POST['setGoogleCha']);
		$settings['SET_GOOGLE_ADS']			= input($_POST['setGoogleAds']);
		$settings['SET_BANDWIDTH_RESET']	= input($_POST['setBandwidthReset']);
		$settings['SET_AUTO_DELETED_TIME']	= (int)input($_POST['setAutoDeletedTime']);
		$settings['SET_AUTO_DELETED_JUMP']	= input($_POST['setAutoDeletedJump']);
		$settings['SET_SHORT_URL_ON']		= input($_POST['setShortUrl']) == 1? 1:0;
		$settings['SET_PRIVATE_IMG_ON']		= input($_POST['setPrivateImg']) == 1? 1:0;
		$settings['SET_DIS_UPLOAD']			= input($_POST['setDisUpload']) == 1? 1:0;
		$settings['SET_LANGUAGE']			= input($_POST['setLanguage']);
		$settings['SET_IMAGE_WIDGIT']		= input($_POST['setImageWidgit']) == 1? 1:0;
		$settings['SET_NODUPLICATE']		= input($_POST['setNoDuplicate']) == 1? 1:0;
		$settings['SET_RESIZE_IMG_ON']		= input($_POST['setResizeImg']) == 1? 1:0;
		$settings['SET_ADDTHIS']			= input($_POST['setAddThis']);
//		$settings['SET_BACKUP_AUTO_ON']		= input($_POST['setBackupAuto']) == 1? 1:0;
//		$settings['SET_BACKUP_AUTO_TIME']	= input($_POST['setBackupTime']);

	//Short url settings
		$settings['SET_SHORT_URL_API']		= input($_POST['setSUrlApi']);
		$settings['SET_SHORT_URL_API_URL']	= input($_POST['setSUrlApiUrl']);
		$settings['SET_SHORT_URL_PASS']		= input($_POST['setSUrlApiPass']);
		$settings['SET_SHORT_URL_USER']		= input($_POST['setSUrlApiUesr']);

	// save settings
		if (empty($Err)){
			if(saveSettings('inc/set.php',$settings)){
				$Suc['saveing_settings'] = $lang["admin_set_suc_update"];
			}else
				$Err['saveing_settings'] = $lang["admin_set_err_saveing_settings"];
		}
	}


// page settings
	$page['id']					= 'set';
	$page['title']				= 'Admin Settings page';
	$page['description']	= '';
	$page['tipsy'] 			= true;
	$page['fancybox']		= true;

	require_once('admin/admin_page_header.php');
?>
<!-- admin settings -->
		<div id="msg"></div>
		<form method="POST" action="admin.php?act=set">
			<div class="tabs">
				<div id="setAdmin" class="">
					<ul class="tabNavigation">
						<li><a href="#setAdmin"><?php echo $lang["admin_set_title_admin_setting"];?></a></li>
						<li><a href="#setSite"><?php echo $lang["admin_set_title_site_setting"];?></a></li>
						<li><a href="#setGallery"><?php echo $lang["admin_set_title_gallery_setting"];?></a></li>
						<li><a href="#setPage"><?php echo $lang["admin_set_title_hide_page"];?></a></li>
						<li><a href="#setDeleted"><?php echo $lang["admin_set_title_auto_deleted"];?></a></li>
						<li><a href="#setUpload"><?php echo $lang["admin_set_title_upload_setting"];?></a></li>
						<li><a href="#setWatermark"><?php echo $lang["admin_set_watermark_title"];?></a></li>
						<li><a href="#setShoetUrl"><?php echo $lang["admin_set_title_url_shortener"];?></a></li>
						<li><a href="#setGoogle"><?php echo $lang["admin_set_title_google_setting"];?></a></li>
					</ul>
					<div class="clear"></div>
				</div>
				<div id="panes" class="ibox">
				<!--admin setting-->
					<div id="setAdmin" class="panel">
					<?php
						optionTitle($lang["admin_set_title_admin_setting"]);
						optionText($lang["admin_set_old_password"],'oldPassword','',null,'password');
						optionText($lang["admin_set_new_password"],'newPassword','',null,'password');
						optionText($lang["admin_set_confirm_new_password"],'newConfirm','',null,'password');
						optionText($lang["admin_set_admin_username"],'setUserName',$settings['SET_USERNAME']);
						optionText($lang["admin_set_email_address"],'setEmail',$settings['SET_CONTACT']);
						submitButton();
					?>
					</div>

				<!--site_setting-->
					<div id="setSite" class="panel">
					<?php
						optionTitle($lang["admin_set_title_site_setting"]);
						optionText($lang["admin_set_script_url"],'setScriptUrl',$settings['SET_SITEURL'],'long');
						optionText($lang["admin_set_site_title"],'setTitle',$settings['SET_TITLE'],'long');
						optionText($lang["admin_set_site_slogan"],'setSlogan',$settings['SET_SLOGAN'],'long');
						optionText($lang["admin_set_footer_copyright"],'setCopyright',$settings['SET_COPYRIGHT'],'long');
						optionList($lang["admin_set_site_theme"],'setTheme',$settings['SET_THEME'], makeThemeArray());
						optionOnOff($lang["admin_set_mod_rewrite"],'setModRewrite',$settings['SET_MOD_REWRITE']);
						optionText($lang["admin_set_addthis"],'setAddThis',$settings['SET_ADDTHIS'],'long');
						optionOnOff($lang["admin_set_image_widgit"],'setImageWidgit',$settings['SET_IMAGE_WIDGIT']);// yes 1/no 0
						optionOnOff($lang["admin_set_hide_feed"]	,'setHideFeed',$settings['SET_HIDE_FEED']);// yes 1/no 0
						optionOnOff($lang["admin_set_hide_sitemap"],'setHideSitemap',$settings['SET_HIDE_SITEMAP']);// yes 1/no 0
						optionList($lang["admin_set_language"],'setLanguage',$settings['SET_LANGUAGE'], makeLanguageArray());
						submitButton();
					?>
					</div>

				<!--Gallery Settings -->
					<div id="setGallery" class="panel">
					<?php
						optionTitle($lang["admin_set_title_gallery_setting"]);
						optionList($lang["admin_set_images_per_gallery_page"],'setImgOnPage',$settings['SET_IMG_ON_PAGE'],array('4'=>'4','8'=>'8','12'=>'12','16'=>'16','20'=>'20','24'=>'24'));
						optionOnOff($lang["admin_set_report_allow"],'setAllowReport',$settings['SET_ALLOW_REPORT']);
						optionOnOff($lang["admin_set_report_Send_email"],'setEmailReport',$settings['SET_EMAIL_REPORT']);
						submitButton();
					?>
					</div>

				<!--hide page-->
					<div id="setPage" class="panel">
					<?php
						optionTitle($lang["admin_set_title_hide_page"]);
						optionOnOff($lang["admin_set_hide_gallery"],'setHideGallery',$settings['SET_HIDE_GALLERY']);// yes 1/no 0
						optionOnOff($lang["admin_set_hide_contact"],'setHideContact',$settings['SET_HIDE_CONTACT']);
						optionOnOff($lang["admin_set_hide_tos"],'setHideTos',$settings['SET_HIDE_TOS']);// yes 1/no 0
						optionOnOff($lang["admin_set_hide_search"],'setHideSearch',$settings['SET_HIDE_SEARCH']);// yes 1/no 0
						optionOnOff($lang["admin_set_hide_faq"],'setHideFaq',$settings['SET_HIDE_FAQ']);// yes 1/no 0
						submitButton();
					?>
					</div>

				<!--auto deleted-->
					<div id="setDeleted" class="panel">
					<?php
						optionTitle($lang["admin_set_title_auto_deleted"]);
						optionDescription($lang["admin_set_des_auto_deleted"]);
						optionOnOff($lang["admin_set_auto_deleted"],'setAutoDeleted',$settings['SET_AUTO_DELETED']);// yes 1/no 0
						optionList($lang["admin_set_auto_deleted_for"],'setAutoDeletedTime',$settings['SET_AUTO_DELETED_TIME'],array('120'=>'120 '.$lang["admin_set_auto_deleted_days"],'90'=>'90 '.$lang["admin_set_auto_deleted_days"],'60'=>'60 '.$lang["admin_set_auto_deleted_days"],'30'=>'30 '.$lang["admin_set_auto_deleted_days"]));
						optionList($lang["admin_set_run_auto_deleted"],'setAutoDeletedJump',$settings['SET_AUTO_DELETED_JUMP'],array('m'=>$lang["admin_set_run_auto_deleted_Month"],'W'=>$lang["admin_set_run_auto_deleted_week"],'z'=>$lang["admin_set_run_auto_deleted_day"]));
						submitButton();
					?>
					</div>

				<!--upload_setting-->
					<div id="setUpload" class="panel">
					<?php
						optionTitle($lang["admin_set_title_upload_setting"]);
						optionOnOff($lang["admin_set_disable_upload"],'setDisUpload',$settings['SET_DIS_UPLOAD']);// yes 0/no 1
						optionList($lang["admin_set_max_upload_file_size"],'setMaxSize',$settings['SET_MAXSIZE'], array('256000'=>'250 kb','512000'=>'500 kb','1048576'=>'1 mb','2097152'=>'2 mb','5242880'=>'5 mb','10485760'=>'10 mb'));
						optionDescription($lang["admin_set_image_max_bandwidth_des"]);
						optionText($lang["admin_set_image_max_bandwidth"],'setMaxBandwidth',$settings['SET_MAX_BANDWIDTH']);
						optionList($lang["admin_set_auto_reset_bandwidth"],'setBandwidthReset',$settings['SET_BANDWIDTH_RESET'], array('m'=>$lang["admin_set_run_auto_deleted_Month"],'W'=>$lang["admin_set_run_auto_deleted_week"]));
						optionList($lang["admin_set_multiple_upload_max"],'setMaxUpload',$settings['SET_MAX_UPLOAD'], array('0'=>'none','5'=>'5','10'=>'10'));
						optionOnOff($lang["admin_set_allow_duplicate"],'setNoDuplicate',$settings['SET_NODUPLICATE']);// yes 0/no 1
						optionOnOff($lang["admin_set_allow_image_resize"],'setResizeImg',$settings['SET_RESIZE_IMG_ON']);// yes 1/no 0
						optionOnOff($lang["admin_set_private_image_upload"],'setPrivateImg',$settings['SET_PRIVATE_IMG_ON']);// yes 0/no 1
						submitButton();
					?>
					</div>

				<!--watermark-->
					<div id="setWatermark" class="panel">
					<?php
						optionTitle($lang["admin_set_watermark_title"]);
						optionDescription($lang["admin_set_watermark_des"]);
						optionOnOff($lang["admin_set_watermark_title"],'setWaterMark',$settings['SET_WATERMARK']);// off 1/on 0
						optionText($lang["admin_set_watermark_text"],'setWatermarkText',$settings['SET_WATERMARK_TEXT'],'long');
						optionText($lang["admin_set_watermark_image"],'setWatermarkImage',$settings['SET_WATERMARK_IMAGE'],'long');
						optionList($lang["admin_set_watermark_position"],'setWatermarkPlaced',$settings['SET_WATERMARK_PLACED'], array(1=>$lang["admin_set_watermark_top"].' '.$lang["admin_set_watermark_left"],
																																				2=>$lang["admin_set_watermark_top"].' '.$lang["admin_set_watermark_center"],
																																				3=>$lang["admin_set_watermark_top"].' '.$lang["admin_set_watermark_right"],
																																				4=>$lang["admin_set_watermark_center"].' '.$lang["admin_set_watermark_left"],
																																				5=>$lang["admin_set_watermark_center"].' '.$lang["admin_set_watermark_center"],
																																				6=>$lang["admin_set_watermark_center"].' '.$lang["admin_set_watermark_right"],
																																				7=>$lang["admin_set_watermark_bottom"].' '.$lang["admin_set_watermark_left"],
																																				8=>$lang["admin_set_watermark_bottom"].' '.$lang["admin_set_watermark_center"],
																																				9=>$lang["admin_set_watermark_bottom"].' '.$lang["admin_set_watermark_right"]
																																				));
						submitButton();
					?>
					</div>

				<!--url_shortener-->
					<div id="setShoetUrl" class="panel">
					<?php
						optionTitle($lang["admin_set_title_url_shortener"]);
						optionOnOff($lang["admin_set_url_shortener"],'setShortUrl',$settings['SET_SHORT_URL_ON']);// off 0/on 1
						optionList($lang["admin_set_url_short_service"],'setSUrlApi',$settings['SET_SHORT_URL_API'], array('b54'=>'B54.in','yourls'=>'yourls','bitly'=>'bit.ly','tinyurl'=>'tinyurl.com','isgd'=>'is.gd','googl'=>'goo.gl'));
						optionText($lang["admin_set_url_short_api_url"],'setSUrlApiUrl',$settings['SET_SHORT_URL_API_URL']);
						optionText($lang["admin_set_url_short_api_username"],'setSUrlApiUesr',$settings['SET_SHORT_URL_USER']);
						optionText($lang["admin_set_url_short_api_password"],'setSUrlApiPass',$settings['SET_SHORT_URL_PASS']);
						submitButton();
					?>
					</div>

				<!--google_setting-->
					<div id="setGoogle" class="panel">
					<?php
						optionTitle($lang["admin_set_title_google_setting"]);
						optionDescription($lang["admin_set_google_setting_des"]);
						optionText($lang["admin_set_google_analytics_code"],'setAnalytics',$settings['SET_GOOGLE_ANALYTICS'],'long');
						optionText($lang["admin_set_google_channal_code"],'setGoogleCha',$settings['SET_GOOGLE_CHANNAL'],'long');
						optionText($lang["admin_set_google_adsense_code"],'setGoogleAds',$settings['SET_GOOGLE_ADS'],'long');
						submitButton();
					?>
					</div>
					<div class="clear"></div>
				</div>
				<div class="clear"></div>
			</div>
		</form>

<?php

// SETTINGS FUNCTIONS

	// settings functions
	function optionList($label,$name,$setting,$list,$return=0){
		$html = '
		<div class="code_box"><label>'.$label.' :</label>
		<select name="'.$name.'" class="text_input">';
		foreach ($list  as $k => $v){
			$html .=  '<option value="'.$k.'" '.($setting==$k?'selected="selected"':'').'>'.$v.'</option>';
		}
		$html .=  '</select></div>';
		if($return) return $html;
		echo $html;
	}
	function optionOnOff($label,$name,$setting,$info = null,$return=0){
		global $lang;
		$html = '
		<div class="code_box"><label>'.$label.' :</label>
		<select name="'.$name.'" class="text_input">
			<option value="0" '.(!$setting?'selected="selected"':'').'>'.$lang["admin_set_option_off"].'</option>
			<option value="1" '.($setting?'selected="selected"':'').'>'.$lang["admin_set_option_on"].'</option>
		</select>'.(!is_null($info)?'<span>'.$info.'</span>':'').'</div>';
		if($return) return $html;
		echo $html;
	}
	function optionText($label,$name,$setting,$size=null,$type=null){
		$eClass = (is_null($size)?'text_input':'text_input long');
		$eType = (is_null($type)?'text':$type);
		echo '<div class="code_box"><label>'.$label.' :</label><input class="'.$eClass.'" type="'.$eType.'" name="'.$name.'" value="'.$setting.'" autocomplete="off" size="20" /></div>';
	}
	function submitButton(){
		global $lang;
		echo '<div class="code_box"><label></label><input class="button button_cen" type="submit" value="'.$lang["admin_set_save_button"].'" name="changesettings[]"></div>';
	}
	function optionTitle($title){
		echo '<h2>'.$title.'</h2>';
	}
	function optionDescription($des){
		echo '<p class="teaser">'.$des.'</p>';
	}
	function makeThemeArray(){
		$dirname = "themes/";// Define the path to the themes folder
		$path_len = strlen($dirname);
		foreach(glob($dirname . '*', GLOB_ONLYDIR) as $dir) {
			$dir = substr($dir, $path_len);
			if(file_exists($dirname.$dir.'/'.$dir.'.css')){
				$array[$dir] = $dir;
			}
		}
		return $array;
	}
	function makeLanguageArray(){
		$dirname = "languages/";
		$dir_list = opendir($dirname);
		while(false != ($file = readdir($dir_list))){
			if(($file != ".") && ($file != "..")){
				$lang_name = explode(".", $file);
				if (count($lang_name) > 2 && $lang_name[1].'.'.$lang_name[2] == 'lang.php'){
					$array[$lang_name[0]]=$lang_name[0];
				}
			}
		}
		return $array;
	}

// PAGE END
	require_once('admin/admin_page_footer.php');
	die();
	exit;