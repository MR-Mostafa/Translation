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
 *   Used For:     Site Config File
 *   Last edited:  07/03/2012
 *
 *************************************************************************************************************/

//debug
	error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
// Hide all error messages from the public
//	error_reporting(E_ALL^E_NOTICE);
//	ini_set('display_errors', 0);

// stop the nav to config.php
	if (basename($_SERVER['PHP_SELF']) == 'config.php'){
		header('Location: ../index.php');
		exit();
	}

	define( 'CFIHP', '1.4.2' );

///////////////////////////////////////////////////////////////////////////////
// Fixed Settings

// Upload directory 
	$DIR_UPLOAD		= 'upload/';
	$DIR_IMAGE		= $DIR_UPLOAD.'images/';
	$DIR_THUMB_MID	= $DIR_UPLOAD.'thumbs/';
	$DIR_THUMB		= $DIR_UPLOAD.'smallthumbs/';
	$DIR_DATA		= $DIR_UPLOAD.'data/';
	$DIR_BANDWIDTH	= $DIR_UPLOAD.'bandwidth/';
	$DIR_TEMP		= $DIR_UPLOAD.'temp/';
	$DIR_BACKUP		= $DIR_UPLOAD.'backup/';
	$DIR_BULKUPLOAD	= $DIR_UPLOAD.'bulkupload/';

// gallery row
	$ROW_GALLERY = 4;
	$ROW_RANDIMG = 4;
	$CAPTCHA_BG	= '';

// upload image size(pixels)
	$IMG_MIN_SIZE = '16';
	$IMG_MAX_SIZE = '3300';

//setMemoryForImage in resize.class.php only needed if max image size is bigger
// them 2500 most of the time..
	$IMG_MEMORY_LIMIT = FALSE; 
	$IMG_TWEAK_FACTOR = 1.8; //setMemoryForImage multiplier 

//Thumb settins(both)
	$PNG_SAVE_EXT	= 'png';	// used for PSD and any png Thumb
	$PNG_QUALITY	= 60;		// used for PSD and any png Thumb (1-100)
	$JPG_SAVE_EXT	= 'jpg';	// used for BMP and any png Thumb
	$JPG_QUALITY	= 90;		// used for BMP and any png Thumb (1-100)


//Small Thumb settins
	$THUMB_OPTION		= 'auto'; //crop, auto, exact
	$THUMB_MAX_WIDTH	= 150;
	$THUMB_MAX_HEIGHT	= 150;

//Thumb settins
	$THUMB_MID_OPTION		= 'auto'; //crop, auto, exact
	$THUMB_MID_MAX_WIDTH	= 320;
	$THUMB_MID_MAX_HEIGHT	= 320;

// Image Formats
	$imgFormats = array('png', 'jpg', 'jpeg', 'gif', 'bmp', 'psd');
	$acceptedFormats =  array(
							'image/x-ms-bmp'=>'bmp',
							'image/bmp'		=>'bmp',
							'image/gif'		=>'gif',
							'image/pjpeg'	=>'jpg',
							'image/jpg'		=>'jpg',
							'image/jpeg'	=>'jpg',
							'image/tiff'	=>'tif',
							'image/x-icon'	=>'ico',
							'image/x-png'	=>'png',
							'image/png'		=>'png',
							'image/psd'		=>'psd',
							'application/octet-stream' =>'psd'
							);

//other global var
	$Err = '';
	$Suc = '';

///////////////////////////////////////////////////////////////////////////////
// include files

// check for settings file
	if(!file_exists('inc/set.php') && !file_exists('install.php')){
		die("Can't find setings!");
	}
// load settings
	@include('inc/set.php');

// load array class
	require_once('lib/arraydb.class.php');

// only load if loading site not just a hotlinked image
	if(!is_image_load()){
	// language pack
		require_once('./languages/'.setLanguage().'.lang.php');

	// Make sure the install.php file is deleted for normal usage
		if (file_exists('install.php')){
			include_once('./install.php');
			exit();
		}

	// theme settings
		if (checkThemeSettings($settings['SET_THEME'])){
			@include('./themes/'.$settings['SET_THEME'].'/settings.php');
		}

	// AdSense Codes
		if($settings['SET_GOOGLE_ADS'] ){
			include_once('./AdSense.php');
		}

	// Page errors
		$errorCode = array(
					'500' => array('HTTP/1.1 500 Internal Server Error', $lang["error_500"]),
					'404' => array('HTTP/1.1 404 Not Found', $lang["error_404"]),
					'403' => array('HTTP/1.1 403 Forbidden', $lang["error_403"]),
					'401' => array('HTTP/1.1 401 Unauthorized', $lang["error_401"]),
					'400' => array('HTTP/1.1 400 Bad Request',  $lang["error_400"])
					);

	// auto backup image database
		if($settings['SET_BACKUP_AUTO_ON']){
			if(time()>($settings['SET_LAST_BACKUP_IMAGE']+($settings['SET_BACKUP_AUTO_TIME']*(24 * 60 * 60)))){
					require_once('lib/backup.class.php');
					backup_imgdb(1,0);
					$settings['SET_LAST_BACKUP_IMAGE'] =time();
					saveSettings('inc/set.php',$settings);
			}
		}

	//run auto delete
		autoDeleted();
	
	}else{
		// reload settings (was else for theme settings)
			@include('./inc/set.php');
	}


// Image report
	if(isset($_GET['report']) && $settings['SET_ALLOW_REPORT']){
		report_img(input($_GET['report']));
	}

////////////////////////////////////////////////////////////////////////////////////
// functions
////////////////////////////////////////////////////////////////////////////////////

function is_image_load(){
	if (isset($_GET['di']) || isset($_GET['dm']) || isset($_GET['dt']))
		return true;
	return false;
}

function checkThemeSettings($theme){

// settings that can't be in the theme settings file
	$notSet = array(
				'SET_PASSWORD','SET_USERNAME','SET_CONTACT','SET_SITEURL','SET_TITLE','SET_SLOGAN',
				'SET_MAXSIZE','SET_COPYRIGHT','SET_THEME','SET_SALTING','SET_MOD_REWRITE','SET_MAX_BANDWIDTH',
				'SET_VERSION','SET_GOOGLE_ANALYTICS','SET_BANDWIDTH_RESET','SET_MAX_UPLOAD','SET_GOOGLE_ADS',
				'SET_AUTO_DELETED','SET_AUTO_DELETED_TIME','SET_AUTO_DELETED_JUMP','SET_EMAIL_REPORT',
				'SET_ALLOW_REPORT','SET_REMOVE_REPORT','SET_SHORT_URL_ON','SET_PRIVATE_IMG_ON','SET_DIS_UPLOAD',
				'SET_LANGUAGE','SET_SHORT_URL_API','SET_SHORT_URL_API_URL','SET_SHORT_URL_PASS','SET_SHORT_URL_USER',
				'SET_WATERMARK','SET_WATERMARK_TEXT','SET_WATERMARK_PLACED','SET_WATERMARK_IMAGE','SET_NODUPLICATE',
				'SET_GOOGLE_CHANNAL','SET_DB','SET_API_ON','SET_ADDTHIS'
				);

// check for file
	if (file_exists('themes/'.$theme.'/settings.php')){
		include('themes/'.$theme.'/settings.php');
		if(!isset($settings) || n_array_keys_exists($settings,$notSet)){
			return true;
		}
	}
	return false;
}
//not in array
function n_array_keys_exists($array,$keys) {
	foreach($keys as $k) {
		if(isset($array[$k])) {
			return false;
		}
	}
	return true;
}

function watermarkImage ($SourceFile) {
	global $settings,$DIR_TEMP;

	$font = 'lib/font/arial.ttf';// the location on the server that the font can be found
	$font_size = 40;// size of the font

	@include_once('lib/watermark.class.php');
	$img = new watermark($SourceFile, (empty($settings['SET_WATERMARK_IMAGE'])?null:$settings['SET_WATERMARK_IMAGE']));
	$img->cacheDir = $DIR_TEMP;
	//$img->saveQuality = 9;
	if(empty($settings['SET_WATERMARK_IMAGE'])){
		$img->padding = 10;
		$img->textWatermark($settings['SET_WATERMARK_TEXT'],$font_size,$font );
		$img->opacityVal = 30;
		$img->watermarkSizing(0.75);
	}
	$img->watermarkPosition($settings['SET_WATERMARK_PLACED']);
	$img->makeImage();
	return;
}

function listLanguages(){
	global $settings;
	$dir_list = opendir("languages/");
	$lang ='';
	while(false != ($file = readdir($dir_list))){
		if(($file != ".") && ($file != "..")){
			$lang_name = explode(".", $file);
			if (count($lang_name) > 2 && $lang_name[1].'.'.$lang_name[2] == 'lang.php'){
				 if($settings['SET_LANGUAGE']!=$lang_name[0])
					$lang .= '<a href="'.$settings['SET_SITEURL'].'/index.php?lang='.$lang_name[0].'" title="'.$lang_name[0].'" rel="nofollow"><img src="'.$settings['SET_SITEURL'].'/languages/'.$lang_name[0].'.png" alt="'.$lang_name[0].'" width="23" height="15" /></a> ';
			}
		}
	}
	if(empty($lang)) return false;
	return $lang;
}

//set site LANGUAGE
function setLanguage(){
	global $settings;
	
// see if cookie has been set before
	if(isset($_COOKIE['lang']) && file_exists('languages/'.$_COOKIE['lang'].'.lang.php'))
		$settings['SET_LANGUAGE'] = $_COOKIE['lang'];
//set cookie
	if(isset($_GET['lang'])){
		$getLang = input(removeSymbols(end(explode('/',$_GET['lang']))));
		if (file_exists('languages/'.$getLang.'.lang.php')){
			setcookie('lang', $getLang, null);
			$settings['SET_LANGUAGE'] = $getLang;
		}
	}

	if(!isset($settings['SET_LANGUAGE']))
		$settings['SET_LANGUAGE'] = 'english';

	if(file_exists('languages/'.$settings['SET_LANGUAGE'].'.lang.php')){
		return $settings['SET_LANGUAGE'];
	}

}

function ImageWidget($numImg=null, $return = null ){
	global $lang,$settings;
	if($imageList = imageList('rand',$numImg)){

		$rand_widget = '<div id="randWidget" class="boxpanel">
			<h2 class="boxtitle">'.$lang["home_image_widgit"].'</h2>
				<ul class="gallery">';
				
		foreach($imageList as $image){
		// get image address
			$thumb_url = imageAddress(3,$image,"dt");
		// get thumb page address
			$thumb_mid_link	= imageAddress(2,$image,"pm");
		//see if there is a alt(title) if not use the image name
			$alt_text = ($image['alt'] !="" ? $image['alt']:$image['name']);
		//image list for page
			$rand_widget .= '
						<li><a href="'.$thumb_mid_link.'" title="'.$alt_text.'" class="thumb" >
							<img src="'.$thumb_url.'" alt="'.$alt_text.'" />
							</a><h2><a href="'.$thumb_mid_link.'" title="'.$alt_text.'">'.$alt_text.'</a></h2>
						</li>';

		}//	endfor
		$rand_widget .= '</ul><div class="clear"></div></div>';
		if(!is_null($return)) return $rand_widget;
		echo $rand_widget;
	}
}

function savefile($menu_array=array(),$fileaddress){
	if($fp = @fopen($fileaddress, 'w+')){
		fwrite($fp, serialize($menu_array));
		fclose($fp);
		return true;
	}else
		return false;
}

function loadfile($fileaddress){
	if (file_exists($fileaddress)){
		$fp = fopen($fileaddress, 'r') or die("I could not read ".$fileaddress);
		$filearray = unserialize(fread($fp, filesize($fileaddress)));
		fclose($fp);
	}else{
		$filearray = array();
	}
		return $filearray;
}

function report_img($id){
	global	$settings,$Err,$Suc,$lang;
	$id = input($id);
	if(db_addReport($id)){
		$Suc['image_report'] = $lang["site_gallery_report_suc"];
		if ($settings['SET_EMAIL_REPORT'] && $settings['SET_CONTACT'] !='') {
			$subject = "Image Reported on ".$settings['SET_TITLE'];
			$message  = "reported image id: ".$id." \r\n";
			$message .= "reported on : ".$settings['SET_TITLE']." \r\n";
			$message .= "Admin Panel : ".$settings['SET_SITEURL']."/admin.php \r\n";
			$headers = "From:".$settings['SET_CONTACT']." <".$settings['SET_CONTACT'].">";
			mail($settings['SET_CONTACT'],$subject,$message,$headers);
		}
		return true;
	}
	$Err['cant_find_image'] = $lang["site_gallery_report_err_find"];
}

function hotlink($ref=''){
	global $settings;
	$referrer		= $ref !='' ? $ref:getenv( "HTTP_REFERER" );
	$ref_address	= explode('/',str_replace('www.', '', str_replace('http://', '',$referrer)));
	$home_address	= explode('/',str_replace('www.', '', str_replace('http://', '',$settings['SET_SITEURL'])));
	if($ref_address[0] == $home_address[0])
		return false;
	return true;
}

function not_max_bandwidth($image,$imgType){
	global $settings;
	if(!$settings['SET_MAX_BANDWIDTH'] == 0){
		if ($settings['SET_BANDWIDTH_RESET'] == 'm'){
			 $resetdate = strtotime('01 '.date('M Y'));
		}else{
			 $resetdate = strtotime("last Monday");
		}

		$maxb = maxedBandwidth($image['id'],$resetdate);
		if (($settings['SET_MAX_BANDWIDTH']*1048576) < $maxb){
			header('Content-type: image/png');
			readfile('img/bandwidth.png');
			exit();
		}
	}
	return true;
}

function countSave($image,$imgType){
	switch($imgType){
		case 1:
			$image_typ = 'size';
			break;
		case 2:
			$image_typ = 'thumbsize';
			break;
		case 3:
			$image_typ = 'sthumbsize';
			break;
		case 4:
		default:
			$image_typ = 'thumbsize';//gallery
			break;
	}

	$bandwidth = $image[$image_typ];

	$newdb = array(	'id'		=> $image['id'],
					'date'		=> time(),
					'image'		=> ($imgType == 1 ? 1:0),
					'thumb_mid'	=> ($imgType == 2 ? 1:0),
					'thumb'		=> ($imgType == 3 ? 1:0),
					'gallery'	=> ($imgType == 4 ? 1:0),
					'bandwidth'	=> ($imgType == 4 ? 0:$bandwidth),
				);

	return db_addCounter($newdb);

}

function shorturl_url( $url, $api=null){
	global $settings;

	$shorturl = '';
	if($api==null){
		if($settings['SET_SHORT_URL_API'] == 'b54'){
			$api = 'yourls';
			$settings['SET_SHORT_URL_API_URL'] = 'http://www.b54.in/api/';
		}else
			$api = $settings['SET_SHORT_URL_API'];
	}

	switch( $api ) {

		case 'yourls':
			$api_url = sprintf( $settings['SET_SHORT_URL_API_URL'] . '?username=%s&password=%s&url=%s&format=text&action=shorturl&source=plugin',$settings['SET_SHORT_URL_USER'], $settings['SET_SHORT_URL_PASS'], urlencode($url) );
			$shorturl = shorturl_url_simple( $api_url );
			break;

		case 'bitly':
			$api_url = sprintf( 'http://api.bit.ly/v3/shorten?longUrl=%s&login=%s&apiKey=%s&format=xml', urlencode($url), $settings['SET_SHORT_URL_USER'], $settings['SET_SHORT_URL_PASS'] );
			$shorturl = shorturl_url_xml( $api_url,'!<url>[^<]+</url>' );
			break;

		case 'tinyurl':
			$api_url = sprintf( 'http://tinyurl.com/api-create.php?url=%s', urlencode($url) );
			$shorturl = shorturl_url_simple( $api_url );
			break;

		case 'isgd':
			$api_url = sprintf( 'http://is.gd/api.php?longurl=%s', urlencode($url) );
			$shorturl = shorturl_url_simple( $api_url );
			break;
		case 'googl':
			include './lib/goo.class.php';
			$googer = new GoogleURLAPI($settings['SET_SHORT_URL_PASS']);
			$shorturl = $googer->shorten($url);
			break;
		default:
			$shorturl='';
	}
	return $shorturl;
}
function shorturl_url_xml($shorter_url,$preg_match){
	$ch = @curl_init();
	curl_setopt($ch, CURLOPT_URL, $shorter_url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$ShortURL = curl_exec($ch);
	curl_close($ch);
	preg_match($preg_match, $ShortURL, $elements);
	print_r($elements);
	return $elements[1];
}
function shorturl_url_simple($shorter_url){
	return @file_get_contents($shorter_url);
}

function error_note($myproblem,$ret = null) {
	global $lang;
	$err = '';
	if(!empty($myproblem) && is_array($myproblem)){
		foreach($myproblem as $v){
			$err .='<div id="err" class="notification error"><a class="close" href="#" alt="close" title="Close this notification"> </a> '.$v.'</div>';
		}
	}elseif(!empty($myproblem) && !is_array($myproblem)){
		$err ='<div id="err" class="notification error"><a class="close" href="#" alt="close" title="Close this notification"> </a> '.$myproblem.'</div>';
	}
	if(is_null($ret))echo $err;
	else return $err;
}

function success_note($mysuccess,$ret = null) {
	global $lang;
	$suc = '';
	if(!empty($mysuccess) && is_array($mysuccess)){
		foreach($mysuccess as $v){
			$suc .= '<div id="suc" class="notification success"><a class="close" href="#" alt="close" title="Close this notification"> </a>  '.$v.'</div>';
		}
	}elseif(!empty($mysuccess) && !is_array($mysuccess)){
		$suc = '<div id="err" class="notification success"><a class="close" href="#" alt="close" title="Close this notification"> </a>  '.$mysuccess.'</div>';
	}
	if(is_null($ret))echo $suc;
	else return $suc;
}

function input($in){
	$in = trim($in);
	if (strlen($in) == 0)
		return;
	return htmlspecialchars(stripslashes($in));
}

function removeSymbols($string) {
	$symbols = array('/','\\','\'','"',',','.','<','>','?',';',':','[',']','{','}','|','=','+','-','_',')','(','*','&','^','%','$','#','@','!','~','`');
	for ($i = 0; $i < count($symbols); $i++) {
		$string = str_replace($symbols[$i],' ',$string);
	}
	return trim($string);
}

function bookmarking($document_url,$document_title){
	global $settings;
	$ypid = (isset($settings['SET_ADDTHIS']) && !empty($settings['SET_ADDTHIS'])?'#pubid='.$settings['SET_ADDTHIS']:'');
	$text = '<div class="addthis">
	<!-- AddThis Button BEGIN -->
	<div class="addthis_toolbox addthis_default_style " addthis:url="'.$document_url.'" addthis:title="'.$document_title.'">
		<a class="addthis_button_preferred_1"></a>
		<a class="addthis_button_preferred_2"></a>
		<a class="addthis_button_email"></a>
		<a class="addthis_button_preferred_4"></a>
		<a class="addthis_button_preferred_5"></a>
		<a class="addthis_button_preferred_6"></a>
		<a class="addthis_button_preferred_7"></a>
		<a class="addthis_button_preferred_8"></a>
		<a class="addthis_button_compact"></a>
		<a class="addthis_counter addthis_bubble_style"></a>
	</div>
	<script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js'.$ypid.'"></script>
	<!-- AddThis Button END -->
</div>';
	return $text;
}

function imageLinkCode($type,$imageaddress,$linkaddress=null,$alt=null){
	switch($type){
		case 'bbcode':
			return (!is_null($linkaddress)?'[URL='.$linkaddress.']':'').'[IMG]'.$imageaddress.'[/IMG]'.(!is_null($linkaddress)?'[/URL]':'');
			break;
		case 'html':
			return '&lt;a href=&quot;'.$linkaddress.'&quot; title=&quot;'.$alt.'&quot; &gt;&lt;img src=&quot;'.$imageaddress.'&quot; alt=&quot;'.$alt.'&quot; /&gt;&lt/a&gt;';
			break;
	}
}

function imageAddress($imgType,$image,$linktype=null){
	global $DIR_THUMB,$DIR_THUMB_MID,$DIR_IMAGE,$settings;

	$ext = 'html';
	$thumb_ext = isset($image['ext'])?strtolower($image['ext']):'';
	switch($imgType){
		case 1:
			$ext = $thumb_ext;
			if ($ext!='html')$fileaddress = $DIR_IMAGE.$image['id'].'.'.$ext;
			if (!isset($fileaddress) || !file_exists($fileaddress)) return false;
			break;
		case 2:
			$fileaddress = $DIR_THUMB_MID.$image['id'].'.';
			if($linktype=='dm')$ext = $thumb_ext;
			if(!file_exists($fileaddress.$thumb_ext)) $notfound =1;
			else $fileaddress .= $thumb_ext;
			break;
		case 3:
			$fileaddress = $DIR_THUMB.$image['id'].'.';
			if($linktype=='dt')$ext = $thumb_ext;
			if(!file_exists($fileaddress.$thumb_ext)) $notfound =1;
			else $fileaddress .= $thumb_ext;
			break;
		case 4:
			$ext = $thumb_ext;
			$fileaddress = $DIR_IMAGE.$image['id'].'.'.$ext;
			if (!isset($fileaddress) || !file_exists($fileaddress)) return false;
			break;
	}

// look for the right file ext
	if(isset($notfound)){
		foreach (array('png','jpg','jpeg','gif') as $fileExt){
			if ($thumb_ext != $fileExt && file_exists($fileaddress.$fileExt)){
				$fileaddress .= $fileExt;
				if($linktype=='dt' || $linktype=='dm') $ext = $fileExt;
				break;
			}
		}
	}

	if (isset($fileaddress)){
		if (!is_null($linktype)){
			if($settings['SET_MOD_REWRITE']){
				$pieces = explode(".", $image['name']);
				return $settings['SET_SITEURL'].'/'.$linktype.'/'.$image['id'].'/'.$pieces[0].'.'.$ext;
			}else{
				return $settings['SET_SITEURL'].'/?'.$linktype.'='.$image['id'];
			}
		}
		elseif (is_null($linktype)){
			return $fileaddress;
		}
	}
	return false;
}

function loadImage(){
	global $settings;

	if (isset($_GET['di'])){
		$id = input($_GET['di']);
		$type = 1;
	}elseif (isset($_GET['dm'])){
		$id = input($_GET['dm']);
		$type = 2;
	}elseif (isset($_GET['dt'])){
		$id = input($_GET['dt']);
		$type = 3;
	}elseif (isset($_GET['dl'])){
		$id = input($_GET['dl']);
		$type = 4;
	}else
		return;

	if(preg_replace("/[^0-9A-Z]/","",$id) != $id){
		header('Content-type: image/png');
		readfile('img/notfound.png');
		exit();
	}

	if($image=getImage($id)){
		$image_time = $image['added'];
		if(array_key_exists("HTTP_IF_MODIFIED_SINCE",$_SERVER)){
			$if_modified_since=strtotime(preg_replace('/;.*$/','',$_SERVER["HTTP_IF_MODIFIED_SINCE"]));
			if($if_modified_since >= $image_time){
				header("HTTP/1.0 304 Not Modified");
				exit();
			}
		}

		header('Last-Modified: '.gmdate('D, d M Y H:i:s', $image_time).' GMT', true, 200);
		header('Expires: '.gmdate('D, d M Y H:i:s',  $image_time + 86400*365).' GMT', true, 200);
		header("Pragma: public");
		header("Cache-Control: maxage=".(86400*14));

		$img_address = imageAddress($type,$image);

		$pathinfo = pathinfo($img_address);
		$img_ext = strtolower($pathinfo['extension']);
		if($img_ext=='jpg') $img_ext = 'jpeg';

		if(!hotlink()){
			header('Content-type: image/'.$img_ext);
		// donwload image header
			if($type == 4){
				header('Content-Length: '.$image['size']);
				header('Content-Disposition: attachment;filename="'.$image['name'].'"');
			}
			readfile($img_address);
			$type = 4;
		}else{
			not_max_bandwidth($image,$type);
			if($settings['SET_WATERMARK']){
				watermarkImage($img_address);
			}else{
				header('Content-type: image/'.$img_ext);
				readfile($img_address);
			}
		}
	}else{
		header('Content-type: image/png');
		readfile('img/notfound.png');
	}

	if(4 != $type){
		flushNow(1);
		countSave($image,$type);
	}

	exit();
}

function order_by(&$db,$field, $order = 123) {
	if ($order == 'ASC' || $order == 123)$order = '$a,$b';
	if ($order == 'DESC' || $order == 321)$order = '$b,$a';
	$code = "return strnatcmp(\$a['$field'], \$b['$field']);";
	@usort($db, create_function($order, $code));
}

// user image romove function
function removeImage($imageDeleteCode=null){
	global $lang,$settings,$Suc,$Err,$DIR_DATA,$DIR_BANDWIDTH;
	
	if (is_null($imageDeleteCode) && isset($_GET['d'])){
		$imageDeleteCode = $_GET['d'];
	}elseif (is_null($imageDeleteCode)){
		return;
	}
	
	if (preg_replace("/[^0-9A-Za-z]/","",$imageDeleteCode) != $imageDeleteCode || empty($imageDeleteCode)){
		$Err['delete_image'] = $lang["site_index_delete_image_err_not_found"];
		return false;
	}

	if (!$image = getImage($imageDeleteCode,'deleteid')){
		$Err['delete_image'] = $lang["site_index_delete_image_err_not_found"];
		$_GET['err'] = '404';// not found (404)page error
		return false;
	}

// Remove Image
	if(@unlink(imageAddress(1,$image))){
		$Suc['delete_image'] = $lang["site_index_delete_image_suc"];
	}
// Remove small thumb
	@unlink(imageAddress(3,$image));
// Remove thumb
	@unlink(imageAddress(2,$image));

// Remove link from array
	if (!removeImageDb($image['id'])){
		$Err['delete_image'] = $lang["site_index_delete_image_err_db"];
		return false;
	}

// Remove bw db
	@unlink($DIR_BANDWIDTH.$image['id'].'_imgbw.db');
	return true;

}

function autoDeleted(){
	global $settings,$DIR_DATA,$Suc,$Err;

	if(!$settings['SET_AUTO_DELETED'])return;
	if(file_exists($DIR_DATA.'ad'.date($settings['SET_AUTO_DELETED_JUMP'])))return;

	$db_img = imageList(0,'all'); // get images
	
	if(empty($db_img)||count($db_img)<1) return; // if no images

	foreach ($db_img as $k => $image){

	//see if it is older then "SET_AUTO_DELETED_TIME"
		if( round(((time() - $image['added']) / 86400),2) > $settings['SET_AUTO_DELETED_TIME']){
		// see if it been viewed
			$db_count = db_imageCounterList(null,$image['id']);
			if (isset($db_count[0]['date'])){
				$lset_viewed = end($db_count);
				if( round(((time() - $lset_viewed['date']) / 86400),2) >= $settings['SET_AUTO_DELETED_TIME']){
					$delete_id[$image['id']] = array('deleteid' => $image['deleteid'],'d'=>round(((time() - $lset_viewed['added']) / 86400),2));
				}
			}
		// if it's not been viewed
			else{
	
				$delete_id[$image['id']] = array('deleteid' => $image['deleteid'],'d'=>round(((time() - $image['added']) / 86400),2));
			}
		}
	}

//remove images
	if(!empty($delete_id)){
		foreach ($delete_id as $k => $image){
			removeImage($image['deleteid']);
		}
	}

// remove image removed meg
	$Suc = array();

	if(savefile(array(),$DIR_DATA.'ad'.date($settings['SET_AUTO_DELETED_JUMP']))){
		if(file_exists($DIR_DATA.'ad'.(date($settings['SET_AUTO_DELETED_JUMP'])-1))){
			unlink ($DIR_DATA.'ad'.(date($settings['SET_AUTO_DELETED_JUMP'])-1));
		}
	}
}

function pageCount(){
	$result = false;
//bots
	$useragent = isset($_SERVER['HTTP_USER_AGENT'])?$_SERVER['HTTP_USER_AGENT']:'unknown';
	$searchengines = array("bot", "crawler", "spider", "google", "yahoo", "msn", "ask", "ia_archiver");
	foreach ($searchengines as $searchengine) {
		$match = "/$searchengine/i";
		if (preg_match($match, $useragent)){
			$result = true;
		}
	}

	if (!$result) {
		$ip = $_SERVER['REMOTE_ADDR'];
		$page = basename($_SERVER['SCRIPT_NAME']);//curPageURL(0);
		$newdb = array(
						'time'	=> time(),
						'page'	=> $page,
						'ip'	=> $ip,
					  );

		return db_addPageCounter($newdb);
	}
	return false;
}

function curPageURL($fix = TRUE) {
	$url = $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
	$url = (substr($url, 0,4) == "www." ? substr($url, 4):$url);
	$url = (substr($url, -1) == "/" ? $url.end(explode('/',$_SERVER['SCRIPT_NAME'])):$url);
	$url = (!$fix ? $url : (strpos($url, '?' )> 0 ? substr($url, 0, strpos($url, '?')):$url));
	return $url;
}

function format_size($size="",$file="") {
	if (empty($size) && !empty($file)) $size = @filesize($file);

	if (strlen($size) <= 9 && strlen($size) >= 7){
		$img_size = substr(number_format($size / 1048576,2), -2) == '00' 
					? number_format($size / 1048576,0):number_format($size / 1048576,2);
		$img_size .= " MB";
	}elseif (strlen($size) >= 10){
		$img_size = substr(number_format($size / 1073741824,2), -2) == '00' 
					? number_format($size / 1073741824,0):number_format($size / 1073741824,2);
		$img_size .= " GB";
	}else $img_size = number_format($size / 1024,0)." kb";

	return $img_size;
}

function checklogin() {
	global $settings;
	if(isset($_SESSION['loggedin'])){
		if ($_SESSION['set_name'] == md5($settings['SET_USERNAME'].$settings['SET_SALTING'].$settings['SET_PASSWORD'])){
			return true;
		}else{
			session_unset();
			session_destroy();
		}
	}
	return false;
}

function pagination($pageOn,$itemsOnPage,$itemCount,$pageAddress,$numberOfPageLinks = null){
	global $lang;

	$pageOn++;// add 1 to fix page number

// the number of links to show
	if(is_null($numberOfPageLinks))$numberOfPageLinks = 11;

// work out the No. of Pages
	$noOfPages = ceil($itemCount/$itemsOnPage);

// On page * of **
	$pagination = '<div class="pagination"><span class="pagecount">'.sprintf($lang["pagination_page_of"], $pageOn, $noOfPages).'</span>' ;

//first and prev buttons
	$pagination.= ($pageOn>1) ? '<a href="'.sprintf($pageAddress, 1).'" title="'.$lang["pagination_page_first_tip"].'">'.$lang["pagination_page_first"].'</a><a href="'.sprintf($pageAddress, ($pageOn-1)).'" title="'.$lang["pagination_previous_page_tip"].'">-</a>':'';


	$numberToList = $noOfPages > ($numberOfPageLinks-1) ? ($numberOfPageLinks-1) :($noOfPages-1);
	$listStart = (($pageOn-(($numberOfPageLinks-1)/2)) < 1) ? 1 : (($pageOn+(($numberOfPageLinks-1)/2))>$noOfPages ? ($noOfPages-$numberToList):($pageOn-(($numberOfPageLinks-1)/2)));

	for ($i = $listStart; $i <= ($listStart+$numberToList); $i++) {
		$pagination .=($i==$pageOn ? '<span class="current">'.$i.'</span>':'<a href="'.sprintf($pageAddress, $i).'" title="'.sprintf($lang["pagination_page_tip"],$i).'">'.$i.'</a>');
	}

// next and last pages
	$pagination .= ($pageOn) < $noOfPages ? '<a href="'.sprintf($pageAddress, ($pageOn+1)).'" title="'.$lang["pagination_next_page_tip"].'">+</a><a href="'.sprintf($pageAddress, $noOfPages).'" title="'.$lang["pagination_page_last_tip"].'">'.$lang["pagination_page_last"].'</a>':'';
	$pagination .='</div>';

	return $pagination;
}

function saveSettings($address,$settings){

$setFile ='<?php

// stop the nav to set.php
	if (!defined(\'CFIHP\')){
		header("Location: ../index.php");
		exit();
	}
// settings
';

	foreach ($settings as $n => $v){
		$setFile .= '	$settings[\''.$n.'\'] = '.(is_numeric($v)?$v:'\''.$v.'\'').";\n";

	}

	if($fp = @fopen($address, 'w+')){
		fwrite($fp, $setFile);
		fclose($fp);
		return true;
	}else
		return false;

}

function flushNow($now = null){
	echo(str_repeat(' ',256));
	// check that buffer is actually set before flushing
	if (ob_get_length()){           
		@ob_flush();
		@flush();
		@ob_end_flush();
	}
	@ob_start();
	if(is_null($now)) usleep(rand(2,4)*100000);
}

////////////////////////////////////////////////////////////////////////////////////
// Start session
	session_name();
	if (!session_start()) {
		$Err['session_error'] = $lang["admin_session_error"];
	}
