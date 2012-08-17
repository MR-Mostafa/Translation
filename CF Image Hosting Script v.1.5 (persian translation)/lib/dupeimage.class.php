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
 *   Used For:     class that checks for duplicate Images
 *   Last edited:  07/03/2012
 *
 *************************************************************************************************************/

class phpDupeImage {

// Width for thumbnail images we use for fingerprinting.
// Default is 150 which works pretty well.
	public $thumbWidth = 150;
// Sets how sensitive the fingerprinting will be.
// Higher numbers are less sensitive (more likely to match). Floats are allowed.
	public $sensitivity = 2;
// Sets how much deviation is tolerated between two images when doing an thorough comparison.
	public $deviation = 1;
// Sets the width and height of the thumbnail sized image we use for deep comparison.
	public $small_size = 32;

	private function openImage($src){
		switch( exif_imagetype($src)){
			case IMAGETYPE_PNG:
				$img = @imagecreatefrompng($src);
				break;
			case IMAGETYPE_GIF:
				$img = @imagecreatefromgif($src);
				break;
			case IMAGETYPE_JPEG:
				$img = @imagecreatefromjpeg($src);
				break;
			case IMAGETYPE_BMP:
				$img = @ImageCreateFromBMP($src);
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

    /* *******************************************************
    *   Are Duplicates
    *
    *   This function compares two images by resizing them
    *   to a common size and then analysing the colours of
    *   each pixel and calculating the difference between
    *   both images for each colour channel and returns
    *   an index representing how similar they are.
    ******************************************************* */
    function are_duplicates($file1, $file2) {
	// Load in both images and resize them to 16x16 pixels
		$image1_small = $this->resizeImage($file1);
		$image2_small = $this->resizeImage($file2);

	// Compare the pixels of each image and figure out the colour difference between them
		$difference =0;
		for ($x = 0; $x < $this->small_size; $x++) {
			for ($y = 0; $y < $this->small_size; $y++) {
				$image1_color = imagecolorsforindex($image1_small, 
				imagecolorat($image1_small, $x, $y));
				$image2_color = imagecolorsforindex($image2_small, 
				imagecolorat($image2_small, $x, $y));
				$difference +=  abs($image1_color['red'] - $image2_color['red']) + 
								abs($image1_color['green'] - $image2_color['green']) +
								abs($image1_color['blue'] - $image2_color['blue']);
			}
		}
		$difference = $difference / 256;

		if ($difference <= $this->deviation) {
			return 1;
		} else {
			return 0;
		}

	}
	function resizeImage($originalImage){
		list($width, $height) = getimagesize($originalImage);
		$imageResized = imagecreatetruecolor($this->small_size,$this->small_size);
		$imageTmp     = @$this->openImage($originalImage);
		@imagecopyresampled($imageResized, $imageTmp, 0, 0, 0, 0, $this->small_size,$this->small_size, $width, $height);
		return $imageResized;
	} 



/* *******************************************************
*   Fingerprint
*
*   This function analyses the filename passed to it and
*   returns an md5 checksum of the file's histogram.
******************************************************* */
	function fingerprint($src_or_resource) {
	
		if (is_resource($src_or_resource))
			$image = $src_or_resource;
		else{
			// Load the image. Escape out if it's not a valid images.
			if (!$image = @$this->openImage($src_or_resource)) return -1;
		}

	// Create thumbnail sized copy for fingerprinting
		$width = imagesx($image);
		$height = imagesy($image);
		$ratio = $this->thumbWidth / $width;
		$newwidth = $this->thumbWidth;
		$newheight = round($height * $ratio); 
		$smallimage = imagecreatetruecolor($newwidth, $newheight);
		imagecopyresampled($smallimage, $image, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
		$palette = imagecreatetruecolor(1, 1);
		$gsimage = imagecreatetruecolor($newwidth, $newheight);

	// Convert each pixel to greyscale, round it off, and add it to the histogram count
		$numpixels = $newwidth * $newheight;
		$histogram = array();
		for ($i = 0; $i < $newwidth; $i++) {
			for ($j = 0; $j < $newheight; $j++) {
				$pos = imagecolorat($smallimage, $i, $j);
				$cols = imagecolorsforindex($smallimage, $pos);
				$r = $cols['red'];
				$g = $cols['green'];
				$b = $cols['blue'];
				// Convert the colour to greyscale using 30% Red, 59% Blue and 11% Green
				$greyscale = round(($r * 0.3) + ($g * 0.59) + ($b * 0.11));                 
				$greyscale++;
				$value = (round($greyscale / 16) * 16) -1;
				@$histogram[$value]++;
			}
		}

	// Normalize the histogram by dividing the total of each colour by the total number of pixels
		$normhist = array();
		foreach ($histogram as $value => $count) {
			$normhist[$value] = $count / $numpixels;
		}

	// Find maximum value (most frequent colour)
		$max = 0;
		for ($i=0; $i<255; $i++) {
			if (@$normhist[$i] > $max) {
				$max = $normhist[$i];
			}
		}   

	// Create a string from the histogram (with all possible values)
		$histstring = "";
		for ($i = -1; $i <= 255; $i = $i + 16) {
			$h = @(@$normhist[$i] / $max) * $this->sensitivity;
			if ($i < 0) {
				$index = 0;
			} else {
				$index = $i;
			}
			$height = round($h);
			$histstring .= $height;
		}

	// Destroy all the images that we've created
		imagedestroy($image);
		imagedestroy($smallimage);
		imagedestroy($palette);
		imagedestroy($gsimage);

	// Generate an md5sum of the histogram values and return it
		$checksum = md5($histstring);
		return $checksum;

	}

}

if( ! function_exists('exif_imagetype') ){
	function exif_imagetype ( $f ){
		if ( false !== ( list(,,$type,) = getimagesize( $f ) ) )
			return $type;
		return IMAGETYPE_PNG; // meh
	}
}

?>