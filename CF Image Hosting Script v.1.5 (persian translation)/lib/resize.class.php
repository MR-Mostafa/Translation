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
 *   Used For:     Resize image Class
 *   Last edited:  07/03/2012
 *
 *************************************************************************************************************/

		include_once('psd.class.php');


		Class resize{

			// *** Class variables
			private $image;
			private $width;
			private $height;
			private $imageResized;
			private $stretch = FALSE;
			private $transparency = TRUE;
			private $image_type;

			private $showMLimit = FALSE;
			private $MemoryLimit = FALSE;
			private $TweakFactor = 1.8;

			
			public function show_MemoryLimit ($var=FALSE){ $this->showMLimit  = ($var?TRUE:FALSE);}
			public function setMemoryLimit ($var=FALSE){ $this->setMemoryLimit = ($var?TRUE:FALSE);}
			public function setTweakFactor($var=1.8){ $this->TweakFactor = ((int)$var?$var:1.8);}

			public function stretchSmallImages($var=TRUE){ $this->stretch = $var;}
			public function DontkeepImageTransparency($var=FALSE){ $this->transparency = $var;}

			public function destroyImage(){@imagedestroy($this->image);	}
			public function imgResource(){return $this->image;}

			function __construct($src_or_resource=null){

				if(!is_null($src_or_resource)){
					if (is_resource($src_or_resource))
						$this->image = $src_or_resource;
					else
						$this->image = $this->openImage($src_or_resource);// Open up the file

					// *** Get width and height
					$this->width  = @imagesx($this->image);//@
					$this->height = @imagesy($this->image);//@
				}
			}

		## --------------------------------------------------------

			private function openImage($src){
			
				if($this->MemoryLimit)$this->setMemoryForImage($src,$this->TweakFactor);

				switch( ( $this->contenttype = exif_imagetype($src))){
					case IMAGETYPE_PNG:
						$img = @imagecreatefrompng($src);//@
						$this->image_type = IMAGETYPE_PNG;
						break;
					case IMAGETYPE_GIF:
						$img = @imagecreatefromgif($src);//@
						$this->image_type = IMAGETYPE_GIF;
						break;
					case IMAGETYPE_JPEG:
						$img = @imagecreatefromjpeg($src);//@
						break;
					case IMAGETYPE_BMP:
						$img = @ImageCreateFromBMP($src);//@
						break;
					case IMAGETYPE_PSD:
						$img = @imagecreatefrompsd($src);//@
						break;
					default:
						$img = false;
						break;
				}

				return $img;
			}


			## --------------------------------------------------------

			public function resizeImage($newWidth, $newHeight, $option="auto"){
				// *** Get optimal width and height - based on $option
				$optionArray = $this->getDimensions($newWidth, $newHeight, $option);

				$optimalWidth  = $optionArray['optimalWidth'];
				$optimalHeight = $optionArray['optimalHeight'];

				// *** Resample - create image canvas of x, y size
				$this->imageResized = @imagecreatetruecolor($optimalWidth, $optimalHeight);//@
				if($this->transparency && (($this->image_type == IMAGETYPE_GIF) || ($this->image_type == IMAGETYPE_PNG))) $this->setTransparency();
				@imagecopyresampled($this->imageResized, $this->image, 0, 0, 0, 0, $optimalWidth, $optimalHeight, $this->width, $this->height);//@


				// *** if option is 'crop', then crop too
				if ($option == 'crop') {
					$this->crop($optimalWidth, $optimalHeight, $newWidth, $newHeight);
				}
			}

			## --------------------------------------------------------
			
			private function getDimensions($newWidth, $newHeight, $option){

				if ($newHeight > $this->height && $newWidth > $this->width && !$this->stretch){
				//already smaller than the thumbnail
					$optimalWidth = $this->width;
					$optimalHeight= $this->height;
				}
				else{
					switch ($option){
						case 'exact':
							$optimalWidth = $newWidth;
							$optimalHeight= $newHeight;
							break;
						case 'portrait':
							$optimalWidth = $this->getSizeByFixedHeight($newHeight);
							$optimalHeight= $newHeight;
							break;
						case 'landscape':
							$optimalWidth = $newWidth;
							$optimalHeight= $this->getSizeByFixedWidth($newWidth);
							break;
						case 'auto':
							$optionArray = $this->getSizeByAuto($newWidth, $newHeight);
							$optimalWidth = $optionArray['optimalWidth'];
							$optimalHeight = $optionArray['optimalHeight'];
							break;
						case 'crop':
							$optionArray = $this->getOptimalCrop($newWidth, $newHeight);
							$optimalWidth = $optionArray['optimalWidth'];
							$optimalHeight = $optionArray['optimalHeight'];
							break;
					}
				}
				return array('optimalWidth' => $optimalWidth, 'optimalHeight' => $optimalHeight);
			}

			## --------------------------------------------------------

			private function getSizeByFixedHeight($newHeight){
				$ratio = $this->width / $this->height;
				$newWidth = $newHeight * $ratio;
				return $newWidth;
			}

			private function getSizeByFixedWidth($newWidth){
				$ratio = $this->height / $this->width;
				$newHeight = $newWidth * $ratio;
				return $newHeight;
			}

			private function getSizeByAuto($newWidth, $newHeight){
				if ($this->height < $this->width){
				// *** Image to be resized is wider (landscape)
					$optimalWidth = $newWidth;
					$optimalHeight= $this->getSizeByFixedWidth($newWidth);
				}elseif ($this->height > $this->width){
				// *** Image to be resized is taller (portrait)
					$optimalWidth = $this->getSizeByFixedHeight($newHeight);
					$optimalHeight= $newHeight;
				}else{
				// *** Image to be resizerd is a square
					if ($newHeight < $newWidth) {
						$optimalWidth = $newWidth;
						$optimalHeight= $this->getSizeByFixedWidth($newWidth);
					} else if ($newHeight > $newWidth) {
						$optimalWidth = $this->getSizeByFixedHeight($newHeight);
						$optimalHeight= $newHeight;
					} else {
						// *** Sqaure being resized to a square
						$optimalWidth = $newWidth;
						$optimalHeight= $newHeight;
					}
				}
				return array('optimalWidth' => $optimalWidth, 'optimalHeight' => $optimalHeight);
			}

			## --------------------------------------------------------

			private function getOptimalCrop($newWidth, $newHeight){

				$heightRatio = $this->height / $newHeight;
				$widthRatio  = $this->width /  $newWidth;

				if ($heightRatio < $widthRatio) {
					$optimalRatio = $heightRatio;
				} else {
					$optimalRatio = $widthRatio;
				}

				$optimalHeight = $this->height / $optimalRatio;
				$optimalWidth  = $this->width  / $optimalRatio;

				return array('optimalWidth' => $optimalWidth, 'optimalHeight' => $optimalHeight);
			}

			## --------------------------------------------------------

			private function crop($optimalWidth, $optimalHeight, $newWidth, $newHeight){
				// *** Find center - this will be used for the crop
				$cropStartX = ( $optimalWidth / 2) - ( $newWidth /2 );
				$cropStartY = ( $optimalHeight/ 2) - ( $newHeight/2 );

				$crop = $this->imageResized;
				//imagedestroy($this->imageResized);

				// *** Now crop from center to exact requested size
				$this->imageResized = imagecreatetruecolor($newWidth , $newHeight);
				imagecopyresampled($this->imageResized, $crop , 0, 0, $cropStartX, $cropStartY, $newWidth, $newHeight , $newWidth, $newHeight);
			}

			## --------------------------------------------------------

			private function setTransparency(){
				$transparencyIndex = @imagecolortransparent($this->image);//@
            
				if ($transparencyIndex >= 0) {
					$transparencyColor = @imagecolorsforindex($this->image, $transparencyIndex); 
					$transparencyIndex = imagecolorallocate($this->imageResized, $transparencyColor['red'], $transparencyColor['green'], $transparencyColor['blue']);
					imagefill($this->imageResized, 0, 0, $transparencyIndex);
					imagecolortransparent($this->imageResized, $transparencyIndex);
				}elseif ($this->image_type == IMAGETYPE_PNG) {
					imagealphablending($this->imageResized, false);
					$color = imagecolorallocatealpha($this->imageResized, 0, 0, 0, 127);
					imagefill($this->imageResized, 0, 0, $color);
					imagesavealpha($this->imageResized, true);
				  }
			}

			## --------------------------------------------------------

			private function imagesharpen($image) {
					$matrix = array(
						array(-1, -1, -1),
						array(-1, 16, -1),
						array(-1, -1, -1),
					);
					$divisor = array_sum(array_map('array_sum', $matrix));
					$offset = 0; 
					imageconvolution($image, $matrix, $divisor, $offset);
					return $image;
				}

			## --------------------------------------------------------

			private function is_ani($filename){
				if(!($fh = @fopen($filename, 'rb')))
					return false;
				$count = 0;
				//an animated gif contains multiple "frames", with each frame having a
				//header made up of:
				// * a static 4-byte sequence (\x00\x21\xF9\x04)
				// * 4 variable bytes
				// * a static 2-byte sequence (\x00\x2C)
			   
				// We read through the file til we reach the end of the file, or we've found
				// at least 2 frame headers
				while(!feof($fh) && $count < 2)
					$chunk = fread($fh, 1024 * 100); //read 100kb at a time
					$count += preg_match_all('#\x00\x21\xF9\x04.{4}\x00(\x2C|\x21)#s', $chunk, $matches);
			   
				fclose($fh);
				return $count > 1;
			}

			## --------------------------------------------------------

			/***
			 * Convert image then Save the image
			 * $savePath - save Path ( /img_dir/imagename.jpg)
			 * $saveQuality - image save quality ( 1 - 100 png and jpg only)
			 * $saveExt - save extension(type) (IMAGETYPE_PNG, IMAGETYPE_GIF, IMAGETYPE_JPEG)
			 */
			public function imageConvert($savePath,$saveQuality=100,$saveExt=null){
				$this->imageResized = $this->image;
				$this->saveImage($savePath,$saveQuality,$saveExt);
			}

			## --------------------------------------------------------

			/***
			 * Save the image to a different location
			 * $savePath - save Path ( /img_dir/imagename.jpg)
			 * $saveQuality - image save quality ( 1 - 100 png and jpg only)
			 * $saveExt - save extension(type) (IMAGETYPE_PNG, IMAGETYPE_GIF, IMAGETYPE_JPEG)
			 */
			public function saveImage($savePath,$saveQuality=100,$saveExt=null){

			//Sharpen Image
			//	$this->imageResized = resize::imagesharpen($this->imageResized);

				if(is_null($saveExt)){
					// *** Get extension
					$extension = strrchr($savePath, '.');
					$extension = strtolower($extension);
					switch($extension){
						case '.jpg':
						case '.jpeg':
							$saveExt = IMAGETYPE_JPEG;
							break;
						case '.gif':
							$saveExt = IMAGETYPE_GIF;
							break;
						case '.png':
							$saveExt = IMAGETYPE_PNG;
							break;
						default:
							$saveExt = IMAGETYPE_PNG;
							break;
					}
				}

				switch($saveExt){
					case IMAGETYPE_PNG:
					// *** Scale quality from 0-100 to 0-9
						$scaleQuality = round(($saveQuality/100) * 9);
					// *** Invert quality setting as 0 is best, not 9
						$invertScaleQuality = 9 - $scaleQuality;
						@imagepng($this->imageResized, $savePath, $invertScaleQuality);//@
					break;
					case IMAGETYPE_GIF:
						@imagegif($this->imageResized, $savePath);//@
					break;
					case IMAGETYPE_JPEG:
						@imagejpeg($this->imageResized, $savePath, $saveQuality);//@
					break;
				}
				@imagedestroy($this->imageResized);//@

			}

			//http://www.php.net/manual/en/function.imagecreatefromjpeg.php#64155
			public function setMemoryForImage( $filename, $TWEAKFACTOR = 1.8){
				$imageInfo = getimagesize($filename);
				$MB = 1048576;  // number of bytes in 1M
				$K64 = 65536;    // number of bytes in 64K
				//$TWEAKFACTOR = 1.8;  // Or whatever works for you
				$memoryNeeded = round( ( $imageInfo[0] * $imageInfo[1]
													   * $imageInfo['bits']
													   * $imageInfo['channels'] / 8
										 + $K64
									   ) * $TWEAKFACTOR
									 );
				//ini_get('memory_limit') only works if compiled with "--enable-memory-limit" also
				//Default memory limit is 8MB so well stick with that.
				//To find out what yours is, view your php.ini file.
				$memoryLimitMB = ((bool)ini_get('memory_limit')? ini_get('memory_limit'):8);
				$memoryLimit = $memoryLimitMB * $MB;
				if (function_exists('memory_get_usage') && memory_get_usage() + $memoryNeeded > $memoryLimit){
					$newLimit = $memoryLimitMB + ceil( ( memory_get_usage()
														+ $memoryNeeded
														- $memoryLimit
														) / $MB
													);
					if($this->showMLimit){
						echo 'used:' . memory_get_usage() . '<br/>'."\n ";
						echo 'Limit: ' . ini_get('memory_limit') . '<br/>'."\n ";
						echo 'new limit'. $newLimit . 'M<br/>'."\n ";
					}
					ini_set( 'memory_limit', $newLimit . 'M' );
					return true;
				}else
					return false;
			}

			## --------------------------------------------------------

		}

/*********************************************/
/* Fonction: ImageCreateFromBMP              */
/* Author:   DHKold                          */
/* Contact:  admin@dhkold.com                */
/* Date:     The 15th of June 2005           */
/* Version:  2.0B                            */
/*********************************************/

function ImageCreateFromBMP($filename){
//Ouverture du fichier en mode binaire
	if (! $f1 = fopen($filename,"rb")) return FALSE;

//1 : Chargement des ent?tes FICHIER
	$FILE = unpack("vfile_type/Vfile_size/Vreserved/Vbitmap_offset", fread($f1,14));
	if ($FILE['file_type'] != 19778) return FALSE;

//2 : Chargement des ent?tes BMP
	$BMP = unpack('Vheader_size/Vwidth/Vheight/vplanes/vbits_per_pixel'.
		 '/Vcompression/Vsize_bitmap/Vhoriz_resolution'.
		 '/Vvert_resolution/Vcolors_used/Vcolors_important', fread($f1,40));
	$BMP['colors'] = pow(2,$BMP['bits_per_pixel']);
	if ($BMP['size_bitmap'] == 0) $BMP['size_bitmap'] = $FILE['file_size'] - $FILE['bitmap_offset'];
	$BMP['bytes_per_pixel'] = $BMP['bits_per_pixel']/8;
	$BMP['bytes_per_pixel2'] = ceil($BMP['bytes_per_pixel']);
	$BMP['decal'] = ($BMP['width']*$BMP['bytes_per_pixel']/4);
	$BMP['decal'] -= floor($BMP['width']*$BMP['bytes_per_pixel']/4);
	$BMP['decal'] = 4-(4*$BMP['decal']);
	if ($BMP['decal'] == 4) $BMP['decal'] = 0;

//3 : Chargement des couleurs de la palette
	$PALETTE = array();
	if ($BMP['colors'] < 16777216){
		$PALETTE = unpack('V'.$BMP['colors'], fread($f1,$BMP['colors']*4));
	}

//4 : Cr?ation de l'image
	$IMG = fread($f1,$BMP['size_bitmap']);
	$VIDE = chr(0);

	$res = imagecreatetruecolor($BMP['width'],$BMP['height']);
	$P = 0;
	$Y = $BMP['height']-1;
	while ($Y >= 0) {
		$X=0;
		while ($X < $BMP['width']){
			if ($BMP['bits_per_pixel'] == 24)
				$COLOR = unpack("V",substr($IMG,$P,3).$VIDE);
			elseif ($BMP['bits_per_pixel'] == 16){ 
				$COLOR = unpack("n",substr($IMG,$P,2));
				$COLOR[1] = $PALETTE[$COLOR[1]+1];
			}elseif ($BMP['bits_per_pixel'] == 8){ 
				$COLOR = unpack("n",$VIDE.substr($IMG,$P,1));
				$COLOR[1] = $PALETTE[$COLOR[1]+1];
			}elseif ($BMP['bits_per_pixel'] == 4){
				$COLOR = unpack("n",$VIDE.substr($IMG,floor($P),1));
				if (($P*2)%2 == 0) $COLOR[1] = ($COLOR[1] >> 4) ; else $COLOR[1] = ($COLOR[1] & 0x0F);
				$COLOR[1] = $PALETTE[$COLOR[1]+1];
			}elseif ($BMP['bits_per_pixel'] == 1){
				$COLOR = unpack("n",$VIDE.substr($IMG,floor($P),1));
				if     (($P*8)%8 == 0) $COLOR[1] =  $COLOR[1]        >>7;
				elseif (($P*8)%8 == 1) $COLOR[1] = ($COLOR[1] & 0x40)>>6;
				elseif (($P*8)%8 == 2) $COLOR[1] = ($COLOR[1] & 0x20)>>5;
				elseif (($P*8)%8 == 3) $COLOR[1] = ($COLOR[1] & 0x10)>>4;
				elseif (($P*8)%8 == 4) $COLOR[1] = ($COLOR[1] & 0x8)>>3;
				elseif (($P*8)%8 == 5) $COLOR[1] = ($COLOR[1] & 0x4)>>2;
				elseif (($P*8)%8 == 6) $COLOR[1] = ($COLOR[1] & 0x2)>>1;
				elseif (($P*8)%8 == 7) $COLOR[1] = ($COLOR[1] & 0x1);
				$COLOR[1] = $PALETTE[$COLOR[1]+1];
			}else
				return FALSE;
			imagesetpixel($res,$X,$Y,$COLOR[1]);
			$X++;
			$P += $BMP['bytes_per_pixel'];
		}
	$Y--;
	$P+=$BMP['decal'];
	}

//Fermeture du fichier
	fclose($f1);

	return $res;
}


if( ! function_exists('exif_imagetype') ){
	function exif_imagetype ( $f ){
		if ( false !== ( list(,,$type,) = getimagesize( $f ) ) )
			return $type;
		return IMAGETYPE_PNG; // meh
	}
}


?>
