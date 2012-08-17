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
 *   Used For:     Remote downloader class
 *   Last edited:  07/03/2012
 *
 *************************************************************************************************************/

class remoteImages {

	public $tempDir;
	public $error;
	public $validExtensions;
	public $validType;
	public $minDimensions;
	public $maxDimensions;
	public $file = null;

	private $lang;		//$lang
	private $set;		//settings
	private $source;	//url
	private $getImageSize; //getImageSize(source)

	function __construct($tempDir,$settings=null,$language=null) {
		$this->tempDir	= $tempDir;
		$this->set		= $settings;
		$this->lang		= $language;
	}

	public function getImage($url,$type='fopen',$pass=null){
		$url = $this->input($url);
		if(empty($url)){
			return false;
		}

		$this->source = $url;
		if(is_null($pass)){
			$this->imgTempName= $this->tempDir.rand(0,7).time().rand(0,7).'.'.strtolower(substr($this->source, -3));
		}
	// pass temp naming
		else{
			$this->imgTempName = $this->tempDir;
		}

	// connection was made to server at domain example.com
		if($type == 'fopen' && $fp = @fopen($this->source, 'r')){
			fclose($fp);
			$this->imgSize		= $this->get_remote_file_size($url);
			$this->getImageSize = @getImageSize($this->source);
			if(!$this->check() && is_null($pass)){
				return false;
			}
			if($this->saveImageFopen($this->source, $this->imgTempName)){
				$this->file = $this->imgFileArray();
				return true;
			}
		}
	//cURL
		elseif($this->saveImageCURL($this->source, $this->imgTempName)){

			$this->imgSize		= filesize($this->imgTempName);
			$this->getImageSize = @getImageSize($this->imgTempName);

			if(!$this->check() && is_null($pass)){
				unlink($this->imgTempName);// remove file
				return false;
			}
			$this->file	= $this->imgFileArray();
			return true;
		}
	// error no file
		$this->error = $this->lang["site_upload_err_no_image"];
		return false;
	}

	// Upload checks
	private function check(){
		$filePieces	= pathinfo($this->source);
		if(!$this->checkDimensions($this->getImageSize)) return false;
		if(!$this->checkFileSize($this->imgSize)) return false;
		if(!$this->checkFileType()) return false;
		return true;
	}

	private function get_remote_file_size($url){
		$parsed = parse_url($url);
		$host = $parsed["host"];
		$fp = @fsockopen($host, 80, $errno, $errstr, 20);
		if(!$fp){
			return false;
		}else {
			@fputs($fp, "HEAD $url HTTP/1.1\r\n");
			@fputs($fp, "HOST: $host\r\n");
			@fputs($fp, "Connection: close\r\n\r\n");
			$headers = "";
			while(!@feof($fp))$headers .= @fgets ($fp, 128);
		}
		@fclose ($fp);
		$return = false;
		$arr_headers = explode("\n", $headers);
		foreach($arr_headers as $header) {
			// follow redirect
			$s = 'Location: ';
			if(substr(strtolower ($header), 0, strlen($s)) == strtolower($s)) {
				$url = trim(substr($header, strlen($s)));
				return $this->get_remote_file_size($url);
			}
			// parse for content length
			$s = "Content-Length: ";
			if(substr(strtolower ($header), 0, strlen($s)) == strtolower($s)) {
				$return = trim(substr($header, strlen($s)));
				break;
			}
		}
		return $return;
	}

	//Download images from remote server
	private function saveImageFopen($inPath,$outPath){ 
		$in	= fopen($inPath, "rb");
		$out= fopen($outPath, "wb");
		while($chunk = fread($in,8192)){
			fwrite($out, $chunk, 8192);
		}
		fclose($in);
		fclose($out);
		return((bool) file_exists($outPath));
	}

	private function saveImageCURL($inPath,$outPath){
		$ch = curl_init($inPath);
		$fp = fopen($outPath, "wb");

	// set URL and other appropriate options
		$options = array(CURLOPT_FILE => $fp,
						 CURLOPT_HEADER => 0,
						 CURLOPT_FOLLOWLOCATION => 1,
						 CURLOPT_TIMEOUT => 60); // 1 minute timeout (should be enough)

		curl_setopt_array($ch, $options);

		curl_exec($ch);
		curl_close($ch);
		fclose($fp);
		return((bool) file_exists($outPath));
	}

	private function input($in){
		$in = trim($in);
		if (strlen($in) == 0)
			return;
		return htmlspecialchars(stripslashes($in));
	}

	//check size(pixels)
	private function checkDimensions($getImgSize){
	//min size
		if ($getImgSize[0] < $this->minDimensions ||
			$getImgSize[1] < $this->minDimensions ){
			$this->error = sprintf($this->lang["site_upload_to_small"],' '.$this->minDimensions.'x'.$this->minDimensions);
			return false;
		}

	// max size
		if ($getImgSize[0] > $this->maxDimensions ||
			$getImgSize[1] > $this->maxDimensions ){
			$this->error = sprintf($this->lang["site_upload_to_big"],$this->maxDimensions.'x'.$this->maxDimensions);
			return false;
		}
		return true;
	}

	//Check file size (kb)
	private function checkFileSize($fileSize){
		if($fileSize >= $this->set['SET_MAXSIZE']){
			$this->error = sprintf($this->lang["site_upload_size_accepted"],$this->format_size($this->set['SET_MAXSIZE']));
			return false;
		}
		return true;
	}

	//check file type
	private function checkFileType(){
		if(isset($this->validType[$this->getImageSize['mime']]) && in_array($this->validType[$this->getImageSize['mime']],$this->validExtensions)){
			return true;
		}
		$this->error = sprintf($this->lang["site_upload_types_accepted"],implode(", ",$this->validExtensions));
		return false;
	}
	
	//make image file array $_FILES
	private function imgFileArray(){
		$_FILES = Array (
						'file' => Array (
										'name' => Array ( basename($this->source)),
										'type' => Array ( $this->getImageSize['mime'] ),
										'tmp_name' => Array ( $this->imgTempName ),
										'error' => Array ( 0 ),
										'size' => Array ( $this->imgSize )
										)
						);
		return $_FILES;
	}

	private function format_size($size="",$file="") {
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
}