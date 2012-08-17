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
 *   Used For:     Image Upload Code
 *   Last edited:  07/03/2012
 *
 *************************************************************************************************************/

	require_once('./inc/config.php');

	if($settings['SET_DIS_UPLOAD'] && !checklogin()){
		header('Location: index.php');
		exit();
	}

// set time out timer to 10mins
	ini_set("max_execution_time", "600");
	ini_set("max_input_time", "600");

//unset session
	unset($_SESSION['upload']);
	unset($_SESSION['err']);

////////////////////////////////////////////////////////////////////////////////////
// UPLOAD CODE START

// see if user is banned
	if (db_isBanned()){
		sendError($lang["site_upload_banned"]);
	}

// see if a image url has been posted
	if((isset($_POST['imgUrl']) && !empty($_POST['imgUrl'])) || isset($imgApiUrl)){
		$image['URL'] = input(isset($_POST['imgUrl'])?$_POST['imgUrl']:$imgApiUrl);
		require_once("lib/remoteImage.class.php");
		$remoteImage = new remoteImages($DIR_TEMP,$settings,$lang);
		$remoteImage->validExtensions	= $imgFormats;
		$remoteImage->validType			= $acceptedFormats;
		$remoteImage->minDimensions		= $IMG_MIN_SIZE;
		$remoteImage->maxDimensions		= $IMG_MAX_SIZE;

		if($remoteImage->getImage($image['URL'])){
		//check that a file has been found and copyed to the temp dir
			if(!is_null($remoteImage->file)){
				$_FILES = $remoteImage->file;
			}
		}else{
			sendError($remoteImage->error);
		}
	}

	if(	($_SERVER['REQUEST_METHOD'] == 'POST' ||
		isset($api)) &&
		$_FILES['file']['name'][0] !='' && 
		!isset($_SESSION['err'])){

		require_once("./lib/resize.class.php");
		require_once("./lib/dupeimage.class.php");

		$imgCount = 0;

		for($i=0; $i < count($_FILES['file']['name']);++$i){
		
		// setup image var
			$pieces		= explode("?", $_FILES['file']['name'][$i]);
			$pathInfo	= pathinfo($_FILES['file']['name'][$i]);
			$imgSize 	= @getimagesize($_FILES['file']['tmp_name'][$i]);

			$image['name']	= $pieces[0];
			$image['alt']	= removeSymbols(input(!empty($_POST["alt"][$i])?$_POST["alt"][$i]:$pathInfo['filename']));
			$image['temp']	= $_FILES['file']['tmp_name'][$i];
			$image['type']	= $imgSize['mime'];
			$image['size']	= ($_FILES['file']['size'][$i] < 1?@filesize($image['temp']):$_FILES['file']['size'][$i]);
			$removeList[]	= $image['temp'];

		//check it's a image & set image extension
			if(!$image['ext'] = get_extension( $image['type'], false )){
				sendError(sprintf('<b>'.$image['name'].'</b> '.$lang["site_upload_types_accepted"],implode(", ",$imgFormats)),'extension');
				continue;
			}

		//min size(pixels)
			if ($imgSize[0] < $IMG_MIN_SIZE || $imgSize[1] < $IMG_MIN_SIZE ){
				sendError(sprintf('<b>'.$image['name'].'</b> '.$lang["site_upload_to_small"],$IMG_MIN_SIZE.'x'.$IMG_MIN_SIZE),'tosmall');
				continue;
			}

		// max size(pixels)
			if ($imgSize[0] > $IMG_MAX_SIZE || $imgSize[1] > $IMG_MAX_SIZE ){
				sendError(sprintf('<b>'.$image['name'].'</b> '.$lang["site_upload_to_big"],$IMG_MAX_SIZE.'x'.$IMG_MAX_SIZE));
				continue;
			}

		//Check file size (kb)
			if($image['size'] > $settings['SET_MAXSIZE']){
				sendError(sprintf('<b>'.$image['name'].'</b> '.$lang["site_upload_size_accepted"],format_size($settings['SET_MAXSIZE'])),'tobig');
				continue;
			}

		//Make Image fingerprint
			$finger = new phpDupeImage();
			$fingerprint = $finger->fingerprint($image['temp']);

		//check for Duplicate Images
			if($settings['SET_NODUPLICATE']){
			// If similar files exist, check them
				if($fp=findImage('fingerprint',$fingerprint)){
					foreach($fp as $fpItem){
						if ($finger->are_duplicates($image['temp'],imageAddress(1,$fpItem))){
							$dupFound = true;
							break;
						}
					}
					if(isset($dupFound)){
						sendError('<b>'.$image['name'].'</b> '.$lang["upload_duplicate_found"],'duplicate');
						continue;
					}
				}
			}

		//New random name
		//Number of Possible Combinations
		//  3 digit code 46656
		//  4 digit code 1679616
		//  5 digit code 60466176
			$image['id'] = createHash(4,true);

		//random delete ID
			$image['did'] = $image['id'].createHash(6);

		//Image address
			$image['new']		= $image['id'].'.'.$image['ext'];
			$image['address']	= $DIR_IMAGE.$image['new'];

		// convert BMP to JPG and PSD to PNG
			if(in_array($image['ext'],array('psd','bmp'))){
			//Reset Image address
				$image['ext']		= ($image['ext'] == 'psd'?$PNG_SAVE_EXT:$JPG_SAVE_EXT);
				$image['new']		= $image['id'].'.'.$image['ext'];
				$image['address']	= $DIR_IMAGE.$image['new'];
			// convert psd/bmp to png
				$convert = new resize($image['temp']);
				$convert->imageConvert($image['address'],($image['ext'] == 'psd'?$PNG_QUALITY:$JPG_QUALITY));
				$convert->destroyImage();
			}
		
		//move image from remote server
			elseif(isset($image['URL']) && input($image['URL']) != ''){
				@copy($image['temp'],$image['address']);
			}
		
		//move uploaded image
			else{
				move_uploaded_file($image['temp'],$image['address']);
			}

		// move file check
			if(!file_exists($image['address'])){
				sendError('<b>'.$image['name'].'</b> '.$lang["site_upload_err"].' .','filemove');
				continue;
			}

		//Resize image if needed
			if ($settings['SET_RESIZE_IMG_ON']) {

				if((isset($_POST['new_width'][$i]) && !empty($_POST['new_width'][$i])) ||
					(isset($_POST['new_height'][$i]) && !empty($_POST['new_height'][$i]))){

					$new_dim = new resize($image['address']);
					$new_dim -> stretchSmallImages(TRUE);

					if(!empty($_POST['new_width'][$i]) && !empty($_POST['new_height'][$i])){
						$new_dim -> resizeImage($_POST['new_width'][$i], $_POST['new_height'][$i], 'exact');
					}
					elseif(!empty($_POST['new_width'][$i]) && empty($_POST['new_height'][$i])){
						$new_dim -> resizeImage($_POST['new_width'][$i], $imgSize[0], 'landscape');
					}
					elseif(empty($_POST['new_width'][$i]) && !empty($_POST['new_height'][$i])){
						$new_dim -> resizeImage($imgSize[1], $_POST['new_height'][$i], 'portrait');
					}

					$new_dim -> saveImage($image['address'],100);
					$new_dim -> destroyImage();
					$imgSize = @getimagesize($image['address']);// update image size for db
				}
			}


		//Thumb address
			$THUMB_ADDRESS = $DIR_THUMB.$image['new'];
			$THUMB_MID_ADDRESS = $DIR_THUMB_MID.$image['new'];

		// thumb
			$resize = new resize($image['address']);
			$resize ->setMemoryLimit ($IMG_MEMORY_LIMIT);
			$resize ->setTweakFactor ($IMG_TWEAK_FACTOR);
		// make thumb
			$resize -> resizeImage($THUMB_MID_MAX_WIDTH, $THUMB_MID_MAX_HEIGHT, $THUMB_MID_OPTION);
			$resize -> saveImage($THUMB_MID_ADDRESS, ($image['ext'] == 'png'?$PNG_QUALITY:$JPG_QUALITY));
		// make small thumb
			$resize -> resizeImage($THUMB_MAX_WIDTH, $THUMB_MAX_HEIGHT, $THUMB_OPTION);
			$resize -> saveImage($THUMB_ADDRESS, ($image['ext'] == 'png'?$PNG_QUALITY:$JPG_QUALITY));
			$resize -> destroyImage();

		//see if thumb's got made
			if(!file_exists($THUMB_ADDRESS) || !file_exists($THUMB_MID_ADDRESS)){
				@unlink($image['address']);
				@unlink($THUMB_ADDRESS);
				@unlink($THUMB_MID_ADDRESS);
				sendError('<b>'.$image['name'].'</b> '.$lang["site_upload_err"].' ..','thumbmade');
				continue;
			}

		// see if we need to get a short url for the image
			$shorturl = null;
			if (isset($_POST['shorturl'][$i]) && $_POST['shorturl'][$i] == 1 && $settings['SET_SHORT_URL_ON']){
				$shorturl = shorturl_url('http://'.$_SERVER['HTTP_HOST'].preg_replace('/\/([^\/]+?)$/', '/', $_SERVER['PHP_SELF']).'?di='.$image['id']);
			}

		// get thumb's file size
			$image['size'] = @filesize($image['address']);
			$thumbsize = @filesize($THUMB_MID_ADDRESS);
			$sthumbsize = @filesize($THUMB_ADDRESS);

		// Make image info array to save to db
			$newImageArray = array(	'id'		=> $image['id'],
									'name'		=> $image['name'],
									'alt'		=> $image['alt'],
									'added'		=> time(),
									'ext'		=> $image['ext'],
									'ip'		=> $_SERVER['REMOTE_ADDR'],
									'size'		=> $image['size'],
									'deleteid'	=> $image['did'],
									'thumbsize' => $thumbsize,
									'sthumbsize'=> $sthumbsize,
									'private'	=> (isset($_POST['private'][$i])?1:0),
									'report'	=> 0,
									'shorturl'	=> $shorturl,
									'fingerprint'  =>$fingerprint,
									);

		//save new image to database
			if(addNewImage($newImageArray)){

			// save image to upload array to be sent to thumb page
				$_SESSION['upload'][] = array('id' => $image['id'],'did' => $image['did']);

			// count images uploaded
				$imgCount++;
				if($imgCount >= $settings['SET_MAX_UPLOAD'] && !checklogin()){
					break; // break upload loop as you have updated max number of images in one go...
				}

			}else{
				sendError('<b>'.$image['name'].'</b> '.$lang["site_index_delete_image_err_db"],'savedb');
				continue;
			}
		}// end image upload loop
	}
// error uploading image
	elseif(!isset($_SESSION['err'])){
		sendError($lang["site_upload_err"].' ...','uk');
	}

// remove temp images
	if(isset($removeList)){
		foreach ($removeList as $tempImg){
			// remove old file
				if(file_exists($tempImg)){
					unlink($tempImg);
				}
		}
	}

// UPLOAD CODE END
////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////
// MAKE PAGE

// admin bulk uploader 
	if(isset($admin_upload)){
		header('Location: '. $settings['SET_SITEURL'].'/admin.php?act=bulk');
		exit();
	}

// error send back to home page and show the error
	if(!isset($_SESSION['upload'])){
		header('Location: '. $settings['SET_SITEURL'].'/index.php');
		exit();
	}

// open thumb page and show upload images
	header('Location: '. $settings['SET_SITEURL'].'/thumbnail.php');
	die();


// MAKE PAGE END
////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////
// functions

// send error back to index page
	function sendError($msg,$id=''){
		if(isset($_SESSION['err'][$id])) $id = $id.'_'.time();
		$_SESSION['err'][$id] = $msg;
	}

// get file ext from mine
	function get_extension($imagetype, $includeDot = false){
		global $acceptedFormats;
		if(empty($imagetype)) return false;
		$dot = $includeDot ? '.' : '';
		if(isset($acceptedFormats[$imagetype])){
			return $dot.$acceptedFormats[$imagetype];
		}
		return false;
	}


// very simple hash function to generate a random string
	function createHash($length=5,$check=null){
		$valid	= 0;
		$hash	= '';
		while(!$valid){
			for($i=0;$i<$length;++$i){
			// if you want to add letters you will have to change the id to varchar instead of int
				$possibleValues = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
			// pick a random character from the possible ones
	    		$hash .= substr($possibleValues, mt_rand(0, strlen($possibleValues)-1), 1);
			}
		// checks if the hash allready exists in the db
			if(is_null($check) || !getImage($hash)){
				$valid = 1;
			}
		}
		return $hash;
	}