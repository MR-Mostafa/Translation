<?php

/**************************************************************************************************************
 *
 *   CF Image Hosting Script
 *   ---------------------------------
 *
 *   Author:    codefuture.co.uk
 *   Version:   1.5
 *   Date:       25 February 2011
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
 *   Used For:     Script Installer
 *   Last edited:  16/02/2012
 *
 *************************************************************************************************************/

// stop the nav to install.php
	if (basename($_SERVER['PHP_SELF']) == 'install.php'){
		header('Location: index.php');
		exit();
	}

// remove install.php and send to admin login
	if(isset($_POST['installed'])){
		@unlink('install.php');
		header('Location: admin.php');
		exit();
	}

// set time out timer to 10mins
	ini_set("max_execution_time", "600");
	ini_set("max_input_time", "600");

// set version
	$SET_VERSION = '1.5';

	$Err_found	= false;
	$path		= dirname( __FILE__ ).'/';
flushNow();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title>CF Image Hosting Installer</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link rel="stylesheet" href="css/admin.css" type="text/css" />
</head>
<body>
<div class="logo"><a href="http://www.pledgie.com/campaigns/11487"><img border="0" src="http://www.pledgie.com/campaigns/11487.png?skin_name=chrome" alt="Click here to lend your support to: CF Image Hosting Donation and make a donation at www.pledgie.com !"></a></div>
<div id="admin_bar">
	<div class="title">CF Image Hosting Installer</div>
</div>
<div id="wrap">
	<div id="content">
		<div class="box installer">

<?
	if(!isset($_POST['accept'])){
		$lines = file('license.txt');
		echo '<div class="ibox">
		<h2>License</h2>
		<textarea name="license" id="license" rows="10" cols="50" class="text_input" readonly="true">';
		foreach ($lines as $line_num => $line) {
			echo $line;
		}
		echo '		</textarea>
		</div>
		<div class="ibox">
			<h2>Accept License & Install</h2>
			<form method="post" action="index.php" class="license">
			<input type="checkbox" name="accept" id="accept"><label for="accept">I accept the terms of the license.</label><br/>
			<input type="submit" value="INSTALL" class="button big">
			</form>
		</div>';
	}else{

		if(file_exists($DIR_DATA.$SET_VERSION.'.v')){
			echo '<div class="ibox"><h2>Checking for old install</h2>';
			error_note('YOU CAN NOT RUN THE INSTALL MORE THEN ONCE');
			echo '</div>';
			$Err_found = true;
		}

	// Checking for old install
		if(!$Err_found){
			echo '<div class="ibox"><h2>Checking for old install</h2>';
			if(isset($settings['SET_TITLE']) || file_exists($DIR_DATA.'settings.cdb')){
				$update_old = 0;
				if (!isset($settings['SET_VERSION']) && file_exists($DIR_DATA.'settings.cdb')){
					$settings = load_old_db($DIR_DATA.'settings.cdb');
					$update_old = 1;
				}
				echo '<div id="suc" class="notification information"><a class="close" href="#"></a> Found old version '.$settings['SET_VERSION'].' installed</div>';
			}else{
				//echo 'No old install found<br/>';
				echo '<div id="suc" class="notification information"><a class="close" href="#"></a> Did not find any.</div>';
				
				$update_old = 0;
			}
			echo '</div>';
			flushNow();
		}

	// Check for extension
		if(!$Err_found){
			echo '<div class="ibox"><h2>Checking for PHP Extensions</h2>';
		// GD
			if (!extension_loaded('gd') || !function_exists('gd_info')) {
				error_note('error: You need the GD extension installed for this script work. <a href="http://php.net/manual/en/book.image.php">http://php.net/manual/en/book.image.php</a>');
				$Err_found = true;
			}else{
				success_note(array( 'GD extension looks to be installed<br/>'));
			}
			flushNow();
		//cURL
			if (!extension_loaded("curl") || !function_exists('curl_init')){
				error_note('error: You need the cURL extension installed to use the url shortening service part of this script.(the script can be used without cURL extension installed) <a href="http://php.net/manual/en/book.curl.php">http://php.net/manual/en/book.curl.php</a>');
				$settings['SET_SHORT_URL_ON'] = 0;
			}else{
				success_note(array( 'cURL extension looks to be installed<br/>'));
			}
			/*
			flushNow();
		//exif
			if (!extension_loaded("exif") || !function_exists('exif_read_data')){
				error_note('error: Exif extension is not installed.(the script can be used without exif extension installed) <a href="http://php.net/manual/en/book.exif.php">http://php.net/manual/en/book.exif.php</a>');
				$settings['SET_SHORT_URL_ON'] = 0;
			}else{
				success_note(array( 'Exif extension looks to be installed<br/>'));
			}*/

			echo '</div>';
			flushNow();
		}

	// setup folders
		if(!$Err_found){
			echo '<div class="ibox"><h2>Checking folders</h2>';
			if(rmkdir($path.$DIR_UPLOAD,FALSE))success_note(array($path.$DIR_UPLOAD.' - ok'));
			flushNow();
			if(rmkdir($path.$DIR_IMAGE))success_note(array($path.$DIR_IMAGE.' - ok'));
			flushNow();
			if(rmkdir($path.$DIR_THUMB_MID))success_note(array($path.$DIR_THUMB_MID.' - ok'));
			flushNow();
			if(rmkdir($path.$DIR_THUMB))success_note(array($path.$DIR_THUMB.' - ok'));
			flushNow();
			if(rmkdir($path.$DIR_DATA))success_note(array($path.$DIR_DATA.' - ok'));
			flushNow();
			if(rmkdir($path.$DIR_BANDWIDTH))success_note(array($path.$DIR_BANDWIDTH.' - ok'));
			flushNow();
			if(rmkdir($path.'inc/'))success_note(array($path.'inc/ - ok'));
			flushNow();
			if(rmkdir($path.$DIR_BACKUP))success_note(array($path.$DIR_BACKUP.' - ok'));
			flushNow();
			echo '</div>';
			flushNow();
		}
		
	// remove old files
		if($update_old && !$Err_found){
			//echo '<div class="ibox"><h2>Removing old files</h2>';
			if(file_exists($path.'inc/phpDupeImage.php'))@unlink ($path.'inc/phpDupeImage.php');
			//echo '</div>';
			//flushNow();
		}

	// make settings
		if(!$Err_found){
			echo '<div class="ibox"><h2>Making/Update setting file</h2>';

			if(!isset($settings['SET_VERSION']) || $settings['SET_VERSION']< 1.3){

				$self = $_SERVER['PHP_SELF'];
				$script_url = 'http://'.$_SERVER['HTTP_HOST'].dirname($self);
				if(substr($script_url, -1) == "/") $script_url = substr($script_url, 0, -1);

				$salting = md5(time().rand(0,14));
			}

			$content = array(
							'SET_PASSWORD'			=>(isset($settings['SET_PASSWORD'])			?$settings['SET_PASSWORD']:md5(md5('password').$salting)),
							'SET_USERNAME'			=>(isset($settings['SET_USERNAME'])			?$settings['SET_USERNAME']:'admin'),
							'SET_CONTACT'			=>(isset($settings['SET_CONTACT'])			?$settings['SET_CONTACT']:'your@email.com'),
							'SET_SITEURL'			=>(isset($settings['SET_SITEURL'])			?$settings['SET_SITEURL']:$script_url),
							'SET_TITLE'				=>(isset($settings['SET_TITLE'])			?$settings['SET_TITLE']:'CF Image Host'),
							'SET_SLOGAN'			=>(isset($settings['SET_SLOGAN'])			?$settings['SET_SLOGAN']:'Free CF Image Host'),
							'SET_MAXSIZE'			=>(isset($settings['SET_MAXSIZE'])			?$settings['SET_MAXSIZE']:'1048576'),
							'SET_IMG_ON_PAGE'		=>(isset($settings['SET_IMG_ON_PAGE'])		?$settings['SET_IMG_ON_PAGE']:8),
							'SET_COPYRIGHT'			=>(isset($settings['SET_COPYRIGHT'])		?$settings['SET_COPYRIGHT']:'Copyright &copy; - All Rights Reserved.'),
							'SET_THEME'				=>'day',//(isset($settings['SET_THEME'])?$settings['SET_THEME']:'day'),//updated 1.4
							'SET_SALTING'			=>(isset($settings['SET_SALTING'])			?$settings['SET_SALTING']:$salting),
							'SET_MOD_REWRITE'		=>(isset($settings['SET_MOD_REWRITE'])		?$settings['SET_MOD_REWRITE']:0),
							'SET_ADMIN_MENU'		=>(isset($settings['SET_ADMIN_MENU'])		?$settings['SET_ADMIN_MENU']:0),
							'SET_MAX_BANDWIDTH'		=>(isset($settings['SET_MAX_BANDWIDTH'])	?$settings['SET_MAX_BANDWIDTH']:1024),
							'SET_VERSION'			=>$SET_VERSION,
							'SET_GOOGLE_ANALYTICS'	=>(isset($settings['SET_GOOGLE_ANALYTICS'])	?$settings['SET_GOOGLE_ANALYTICS']:''),
							'SET_GOOGLE_ADS'		=>(isset($settings['SET_GOOGLE_ADS'])		?$settings['SET_GOOGLE_ADS']:''),
							'SET_GOOGLE_CHANNAL'	=>(isset($settings['SET_GOOGLE_CHANNAL'])	?$settings['SET_GOOGLE_CHANNAL']:''),
							'SET_BANDWIDTH_RESET'	=>(isset($settings['SET_BANDWIDTH_RESET'])	?$settings['SET_BANDWIDTH_RESET']:'m'),
							'SET_MAX_UPLOAD'		=>(isset($settings['SET_MAX_UPLOAD'])		?$settings['SET_MAX_UPLOAD']:5),
							'SET_AUTO_DELETED'		=>(isset($settings['SET_AUTO_DELETED'])		?$settings['SET_AUTO_DELETED']:0),
							'SET_AUTO_DELETED_TIME'	=>(isset($settings['SET_AUTO_DELETED_TIME'])?$settings['SET_AUTO_DELETED_TIME']:'60'),
							'SET_AUTO_DELETED_JUMP'	=>(isset($settings['SET_AUTO_DELETED_JUMP'])?$settings['SET_AUTO_DELETED_JUMP']:'m'),
							'SET_HIDE_CONTACT'		=>(isset($settings['SET_HIDE_CONTACT'])		?$settings['SET_HIDE_CONTACT']:1),
							'SET_HIDE_TOS'			=>(isset($settings['SET_HIDE_TOS'])			?$settings['SET_HIDE_TOS']:1),
							'SET_HIDE_GALLERY'		=>(isset($settings['SET_HIDE_GALLERY'])		?$settings['SET_HIDE_GALLERY']:1),
							'SET_HIDE_FAQ'			=>(isset($settings['SET_HIDE_FAQ'])			?$settings['SET_HIDE_FAQ']:1),
							'SET_HIDE_SEARCH'		=>(isset($settings['SET_HIDE_SEARCH'])		?$settings['SET_HIDE_SEARCH']:1),
							'SET_EMAIL_REPORT'		=>(isset($settings['SET_EMAIL_REPORT'])		?$settings['SET_EMAIL_REPORT']:0),
							'SET_ALLOW_REPORT'		=>(isset($settings['SET_ALLOW_REPORT'])		?$settings['SET_ALLOW_REPORT']:1),
							'SET_SHORT_URL_ON'		=>(isset($settings['SET_SHORT_URL_ON'])		?$settings['SET_SHORT_URL_ON']:1),
							'SET_PRIVATE_IMG_ON'	=>(isset($settings['SET_PRIVATE_IMG_ON'])	?$settings['SET_PRIVATE_IMG_ON']:1),
							'SET_DIS_UPLOAD'		=>(isset($settings['SET_DIS_UPLOAD'])		?$settings['SET_DIS_UPLOAD']:0),
							'SET_LANGUAGE'			=>(isset($settings['SET_LANGUAGE'])			?$settings['SET_LANGUAGE']:'english'),
							'SET_SHORT_URL_API'		=>(isset($settings['SET_SHORT_URL_API'])	?$settings['SET_SHORT_URL_API']:'b54'),
							'SET_SHORT_URL_API_URL'	=>(isset($settings['SET_SHORT_URL_API_URL'])?$settings['SET_SHORT_URL_API_URL']:''),
							'SET_SHORT_URL_PASS'	=>(isset($settings['SET_SHORT_URL_PASS'])	?$settings['SET_SHORT_URL_PASS']:''),
							'SET_SHORT_URL_USER'	=>(isset($settings['SET_SHORT_URL_USER'])	?$settings['SET_SHORT_URL_USER']:''),
							'SET_WATERMARK'			=>(isset($settings['SET_WATERMARK'])		?$settings['SET_WATERMARK']:0),
							'SET_WATERMARK_TEXT'	=>(isset($settings['SET_WATERMARK_TEXT'])	?$settings['SET_WATERMARK_TEXT']:0),
							'SET_WATERMARK_PLACED'	=>(!isset($settings['SET_WATERMARK_PLACED'])?0:(is_numeric($settings['SET_WATERMARK_PLACED'])?$settings['SET_WATERMARK_PLACED']:0)),
							'SET_WATERMARK_IMAGE'	=>(isset($settings['SET_WATERMARK_IMAGE'])	?$settings['SET_WATERMARK_IMAGE']:''),
							'SET_IMAGE_WIDGIT'		=>(isset($settings['SET_IMAGE_WIDGIT'])		?$settings['SET_IMAGE_WIDGIT']:1),
							'SET_NODUPLICATE'		=>(isset($settings['SET_NODUPLICATE'])		?$settings['SET_NODUPLICATE']:0),
							'SET_RESIZE_IMG_ON'		=>(isset($settings['SET_RESIZE_IMG_ON'])	?$settings['SET_RESIZE_IMG_ON']:1),
							'SET_ADDTHIS'			=>(isset($settings['SET_ADDTHIS'])			?$settings['SET_ADDTHIS']:''),
							'SET_LAST_BACKUP_BANDWIDTH'		=>(isset($settings['SET_LAST_BACKUP_BANDWIDTH'])		?$settings['SET_LAST_BACKUP_BANDWIDTH']:0),
							'SET_LAST_BACKUP_IMAGE'		=>(isset($settings['SET_LAST_BACKUP_IMAGE'])		?$settings['SET_LAST_BACKUP_IMAGE']:0),
							'SET_HIDE_FEED'		=>(isset($settings['SET_HIDE_FEED'])		?$settings['SET_HIDE_FEED']:1),
							'SET_HIDE_SITEMAP'		=>(isset($settings['SET_HIDE_SITEMAP'])		?$settings['SET_HIDE_SITEMAP']:1),
							'SET_BACKUP_AUTO_ON'		=>(isset($settings['SET_BACKUP_AUTO_ON'])		?$settings['SET_BACKUP_AUTO_ON']:1),
							'SET_BACKUP_AUTO_TIME'		=>(isset($settings['SET_BACKUP_AUTO_TIME'])		?$settings['SET_BACKUP_AUTO_TIME']:1),
							'SET_BACKUP_AUTO_USE'		=>(isset($settings['SET_BACKUP_AUTO_USE'])		?$settings['SET_BACKUP_AUTO_USE']:1),
							'SET_BACKUP_AUTO_REBUILD'		=>(isset($settings['SET_BACKUP_AUTO_REBUILD'])		?$settings['SET_BACKUP_AUTO_REBUILD']:1),
						);

			if(!saveSettings('inc/set.php',$content)){
				error_note(array('Error Making "Settings" file!<br/>'));
				$Err_found = true;
			}else{
				success_note(array('Settings Made/Updated'));
			}
			echo '</div>';
			flushNow();
		}

	// update database
		if(!$Err_found && $update_old){
			echo '<div class="ibox"><h2>update old database</h2>';
		// update image db
			if (file_exists($DIR_DATA.'imgdb.ihdb')){
				if(file_exists($DIR_DATA.'1.3.83.v')){
					$db = new array_db($DIR_DATA.'imgdb.ihdb');
					$empty_db = $db->db_not_empty();
					$img_db = $db->fetch_all();
				}else{
					$img_db = load_old_db($DIR_DATA.'imgdb.ihdb');
					$empty_db = count($img_db);
				}

				include("lib/dupeimage.php");
				$finger = new phpDupeImage();
				if($empty_db){
					foreach ($img_db as $k => $v){
						echo 'Updating image - '.$v['id'].' - ';
					// updating from below v1.2
						if(!isset($img_db[$k]['thumbsize'])){
							$THUMB_ADDRESS = $DIR_THUMB.$v['id'].'.png';
							$THUMB_MID_ADDRESS =  $DIR_THUMB_MID.$v['id'].'.png';
							$img_db[$k]['thumbsize'] = @filesize($THUMB_MID_ADDRESS);
							$img_db[$k]['sthumbsize']= @filesize($THUMB_ADDRESS);
							echo 'T';
						}
					//updating from below v1.3
						if(!isset($img_db[$k]['shorturl'])){
							$img_db[$k]['shorturl'] = null;
							echo 'S';
						}
					//updating form below 1.4
						if(!isset($img_db[$k]['fingerprint'])){
							$img_db[$k]['fingerprint'] = $finger->fingerprint($img_db[$k]['id'],$img_db[$k]['ext']);
							echo 'F';
						}
						echo ' <span style="color:green;">Done..</span><br/>';
						flushNow(1);
					}
					if(file_exists($DIR_DATA.'1.3.83.v')){
						$db->set_db($img_db);
						$db->save_db_now($ADD_DB_IMG);// save new db
					}else{
						save_new_db($ADD_DB_IMG,$img_db);
					}
					save_new_db($DIR_DATA.'1.4.v',array('1.4'));
					success_note(array('new database setup done'));
				}
			}
			echo '</div>';
			flushNow();
		}elseif(!$Err_found){
			echo '<div class="ibox"><h2>Make database</h2>';
			if (!file_exists($ADD_DB_IMG)){
				if(save_file($ADD_DB_IMG)){
					success_note(array('Made New database file'));
				}
			}else{
				success_note(array('Database file exists!'));
			}
			echo '</div>';
			flushNow();
		}

	// install done
		if(!$Err_found){
			echo '<div class="ibox"><h2>Install/Updated!</h2>';
			success_note(array(' Please remove the <b>install.php</b> file from your server.'));
			echo '</div>';
		}
	}
?>
			<div class="clear"></div>
		</div>
	</div>
</div>
	<div id="footer">
		<span class="copyright"><?php echo $lang["admin_footer_powered_by"];?> <a href="http://codefuture.co.uk/projects/imagehost/" title="Free PHP Image Hosting Script">CF Image Hosting script</a></span>
		<span class="version"><b><?php echo $lang["admin_footer_version"];?>:</b> <?php echo $SET_VERSION;?></span>
	</div>
</html>
<?

exit();

function load_old_db($fileaddress){
	if (file_exists($fileaddress )){
		$filearray = @unserialize(file_get_contents($fileaddress));
		if(!is_array($filearray)){
			$filearray = unserialize(base64_decode(file_get_contents($fileaddress)));
		}
	}else{
		$filearray = array();
	}
	return $filearray;
}

function save_new_db($fileaddress,$db){
	$fp = fopen($fileaddress, 'w+') or die("I could not open ".$fileaddress);
	while (!flock($fp, LOCK_EX | LOCK_NB)) {
		//Lock not acquired, try again in:
		usleep(round(rand(0, 100)*1000)); //0-100 miliseconds
	}
	fwrite($fp, base64_encode(serialize($db)));
	flock($fp, LOCK_UN); // release the lock
	fclose($fp);
	return true;
}

function file_nw($path){
	error_note(array('File Exists but Not writable<br />
		Please make sure PHP scripts can write to the <b>'.$path.'</b> file.
		On Linux servers CHMOD this file to 666 (rw-rw-rw-).<br/>Then reload this page.'));
}
function file_nc($path){
	error_note(array('Can not create a File<br />
		Please make a file named <b>'.basename($path).'</b> in <b>'.$path.'</b> .
		and please make sure that PHP scripts can write to the file.
		On Linux servers CHMOD this file to 666 (rw-rw-rw-).<br/>Then reload this page.'));
}
function folder_nw($path){
	error_note(array('Folder Exists but Not writable<br />
		Please make sure PHP scripts can write to the <b>'.$path.'</b> folder.
		On Linux servers CHMOD this file to 777 (rwxrwxrwx).<br/>Then reload this page.'));
}
function folder_nc($path){
	error_note(array('Can not create a folder.<br />
		Please make sure a folder called <b>'.$path.'</b> exists on your server
		and that PHP scripts have permission to write to it.
		On Linux servers CHMOD this folder to 777 (rwxrwxrwx).<br/>Then reload this page.'));
}

function save_file($fileaddress){
	global $Err_found;
	if(file_exists($fileaddress)){
		if( @file_perms($fileaddress) != 666 &&
			@file_perms($fileaddress) != 664 &&
			@file_perms($fileaddress) != 644){
			file_nw($fileaddress);
		}else{
			return true;
		}
	}
	if(!$fp = @fopen($fileaddress, 'w+')){
		file_nc($fileaddress);
		$Err_found = true;
		return false;
	}
	if (fwrite($fp, base64_encode(serialize(array()))) === false) {
		file_nw($fileaddress);
		$Err_found = true;
		return false;
	}
	fclose($fp);
	return true;
}

function rmkdir($folder,$writable = true) {
	global $Err_found;
	if(@is_dir($folder)){
		if($writable){
			// testing dir is really writable to PHP scripts
			$tf = $folder.'/'.md5(rand()).".test";
			$f = @fopen($tf, "w");
			if ($f == false){
				@folder_nw($folder);
				$Err_found = true;
				return false;
			}
			fclose($f);
			unlink($tf); 
		}
		return true;
	}
	folder_nc($folder);
	$Err_found = true;
	return false;
}

function file_perms($file){
	if(!file_exists($file)) return false;
	$perms = fileperms($file);
	return substr(decoct($perms), -3);
}


?>