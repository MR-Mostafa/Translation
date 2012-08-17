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
 *   Used For:     ReMakeImage Database
 *   Last edited:  07/03/2012
 *
 *************************************************************************************************************/

	ini_set("max_execution_time", "600");
	ini_set("max_input_time", "600");

	$stopwatch = new StopWatch();
//	require_once("../inc/config.php");
	
	if(!isset($autouse))$autouse =0;

	if(!$autouse&&!checklogin()){
		header('Location: ../index.php');
		exit();
	}

if(!$autouse){
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title>ReMakeImage Database</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="description" content="" />
	<meta name="keywords" content="" />
	<meta name="robots" content="index,follow" />
	<style type="text/css">
		* {font-family: "Lucida Grande", "Lucida Sans", "Lucida", Arial, Verdana, Helvetica, sans-serif;font-size: 100%;margin: 0;padding: 0;}
		html { overflow-y: scroll; }
		body {background:#f9f9f9;color: #666;font-size: 75%;}
		h2{font-weight: normal;color: #BBBBBB;display: inline-block;font-size: 20px;margin: 0 0 0px;padding: 0 0 0px;}
		.teaser {color: #444;font-size: 12px;line-height: 18px;margin: 5px 20px;text-align: left;}
	</style>
</head>
<body>
<?php if(isset($_GET['n'])){?>
</body>
</html>
<?
exit;
}// GET[n]?>

------------------------------------------------------------------<br/>
--- <h2> ReMakeImage Datebase </h2><br/>
---  Start time <?php echo date('h:m:s');?><br/>
-----<br/>
--- Checking for Image datebase<br/>
<?
	flushNow(1);
/////////////////////////////////////////////////////////////////////////
// check db
}

	$img_db = loadDBGlobal();
	$imgArray = $img_db->fetch_all();
	$imgArrayCount = count($imgArray);

	if($imgArrayCount>0){
		if(!$autouse) echo '--- found datebase with '.$imgArrayCount.' images <br/>';
		$in_old='';
	}else{
		if(!$autouse) echo '--- No datebase found<br/>';
	}
	if(!$autouse){
		echo '--- done - '.$stopwatch->clock().' seconds<br />';
		echo '------------------------------------------------------------------<br/>';
		flushNow(1);
	}

/////////////////////////////////////////////////////////////////////////
// check image folders
if(!$autouse){
	echo '--- Check for images, in "'.$DIR_IMAGE.'" directory<br/>';
	flushNow(1);
}
// list images
	$file_array = array();
	$d = opendir($DIR_IMAGE);
	while($file = readdir($d)) {
		$path_info = pathinfo($DIR_IMAGE.$file);
		$cheFormats = array('png'=>'png', 'jpg'=>'jpg', 'jpeg'=>'jpeg', 'gif'=>'gif');
		if(isset($path_info['extension']) && isset($cheFormats[strtolower($path_info['extension'])])){
			$file_array[] =array('file' => $file,'time' => filemtime($DIR_IMAGE.$file));
		}
	}
	usort($file_array, create_function('$a, $b', 'return strcmp($a["time"], $b["time"]);'));
if(!$autouse){
	echo '--- Found '.count($file_array).' images<br/>';
	echo '--- done - '.$stopwatch->clock().' seconds<br />';
	echo '------------------------------------------------------------------<br/>';
	flushNow(1);
}
/////////////////////////////////////////////////////////////////////////
// check image folders
if(!$autouse){
	echo '--- Start to Add/remove items to/from the database <br/>';
	echo '------------------------------------------------------------------<br/>';
	flushNow();
}
	$i=0;
	$ic=1;
	
	foreach($file_array as $file){
	
		$file_name = $DIR_IMAGE.$file['file'];
		$path_info = pathinfo($file_name);

	// image in array
		if ( !in_multiarray($path_info['filename'],$imgArray) ){

			$imgSize 	= @getimagesize($file_name);
			$image['type']	= $imgSize['mime'];

		//check file type
			$image['ext'] = get_extension( $image['type'], false );

			if($settings['SET_NODUPLICATE']){
				require_once("./lib/dupeimage.class.php");
			}

			//Make Image fingerprint
			if($settings['SET_NODUPLICATE']){
				$finger = new phpDupeImage();
				$fingerprint = $finger->fingerprint($file_name);
			}else{
				$fingerprint = 0;
			}

			$image['name'] = $path_info['filename'];

		//Image address
			$IMG_NAME = $image['name'].'.'.$image['ext'];
			$IMG_ADDRESS = $DIR_IMAGE.$IMG_NAME;

		//Thumb address
			$THUMB_NAME = $image['name'].'.'.$image['ext'];
			$THUMB_ADDRESS = $DIR_THUMB.$THUMB_NAME;
			$THUMB_MID_ADDRESS = $DIR_THUMB_MID.$THUMB_NAME;

		// Make image info array to save to db
			$newImageArray = array(	'id'		=> $image['name'],
									'name'		=> $path_info['basename'],
									'alt'		=> $image['name'],
									'added'		=> $file['time'],
									'ext'		=> $image['ext'],
									'ip'		=> '0.0.0.0',
									'size'		=> @filesize($file_name),
									'deleteid'	=> ($image['name'].'123xxx'),
									'thumbsize' => @filesize($THUMB_MID_ADDRESS),
									'sthumbsize'=> @filesize($THUMB_ADDRESS),
									'private'	=> 0,
									'report'	=> 0,
									'shorturl'	=> null,
									'fingerprint'  =>$fingerprint,
								);

		// add image to db
			addNewImage($newImageArray);
			
			$i++;
		}else{
			$in_old .= $path_info['filename'].' - ';
			$ic++;
		}
		
		if(!$autouse){
			if(($i+$ic)%50==0 && $ic){
				echo '--- '.($i+$ic).' images done in '.$stopwatch->elapsed().' seconds<br />';
				flushNow();
			}

			
			if($stopwatch->elapsed() > 575){
				
				echo '--- <div style="color:red;display: inline-block;">the script has timed out. ('.$stopwatch->elapsed().' seconds)</div><br />';
				flushNow();
				break;
			}
		}

	}//for

	if(!$autouse){
?>
------------------------------------------------------------------<br/>
--- <?php echo $ic-1;?> image/s found in the datebase and image folder<br/>
--- <?php echo $i;?> image/s found and added to the image datebase<br/>
--- Time Elapsed: <?php echo $stopwatch->elapsed();?> seconds<br />
------------------------------------------------------------------<br/>
<?php
}
	//remove images that are not found in the image folder
		if($imgArrayCount>count($file_array)){
	if(!$autouse)		echo '--- Removeing Images from the database that are not found in the image folder<br />';
			$remove_array =array();
			$imgArray = $img_db->fetch_all();
			foreach($imgArray as $id=>$info){
				$img_address = $DIR_IMAGE.$info['id'].'.'.$info['ext'];
				if(!file_exists($img_address))$remove_array[]=$info['deleteid'];
			}
			
			if(isset($remove_array[0])){
				foreach($remove_array as $deleteid){
					removeImage($deleteid);
				}
			}
			if(!$autouse){
				echo '--- Remove '.count($remove_array).' images from the database  ('.$stopwatch->elapsed().' seconds)<br />';
				echo '------------------------------------------------------------------<br/>';
			}
		}
if(!$autouse){
?>
--- Done<br/>
--- Total Time Elapsed: <?php echo $stopwatch->elapsed();?> seconds<br />
------------------------------------------------------------------<br/>
</body>
</html>
<?
}
//////////////////////////////////////////////////////////////////////////////////////////
// functions

	function get_extension($imagetype, $includeDot = false){
		global $acceptedFormats;
		if(empty($imagetype)) return false;
		$dot = $includeDot ? '.' : '';
		if(isset($acceptedFormats[$imagetype])){
			return $dot.$acceptedFormats[$imagetype];
		}
		return false;
	}

	function in_multiarray($elem, $array){
		if( is_array( $array )){
			if( is_array( $array ) && isset($array['id']) && $array['id'] == $elem  )
				return true;
			foreach( $array as $array_element ){
				if( is_array( $array_element ) && in_multiarray( $elem, $array_element ) ){
					return true;
					exit;
				}
			}
		}
		return false;
	}

	class StopWatch {
		public $total;
		public $time;
		public function __construct() {$this->total = $this->time = microtime(true);}
		public function clock() {return -$this->time + ($this->time = microtime(true));}
		public function elapsed() {return microtime(true) - $this->total;}
		public function reset() {$this->total=$this->time=microtime(true);}
	} 
?>