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
 *   Used For:     Search Page
 *   Last edited:  07/03/2012
 *
 *************************************************************************************************************/

	require_once('./inc/config.php');

	if(!$settings['SET_HIDE_SEARCH']){
		header('Location: index.php');
		exit();
	}

	$string = '';

	if( !empty ( $_GET['search'] ) ){
		$string = input($_GET['search']);
		$clean_string = new cleaner();
		$stemmed_string = $clean_string->parseString($string);

		if(!empty($stemmed_string)){

			$new_string = '';
			foreach ( array_unique ( split ( " ",$stemmed_string ) ) as $array => $value ){
				if(strlen($value) > 2)
					$new_string .= ''.$value.' ';
			}
			$new_string = substr ( $new_string,0, ( strLen ( $new_string ) -1 ) );
			if ( strlen ( $new_string ) <= 2 ){
				$Err['site_search'] = $lang["site_search_err_short"];
			}
		}else{
			$Err['site_search'] = $lang["site_search_err_blank"];
		}
	}else{
		$Err['site_search'] = $lang["site_search_err_blank"];
	}

///////////////////////////////////////////////////

// see what page we are on
	$page_number = (isset($_GET['p']) ? input($_GET['p'])-1:0);

// setup pagination address
	$pagination_address = $settings['SET_SITEURL'].'/search.php?p=%1$s&search='.$string;

// count images in db to see if we need to make a page
	if(empty($Err) && $imageList = imageList(($page_number*$settings['SET_IMG_ON_PAGE']),null,'added','DESC',$new_string)){

	// page pagination
		//$pagination = pagination($page_number, $settings['SET_IMG_ON_PAGE'], $DBCOUNT,'search.php','&search='.$string.'&o='.$orderBy);
	// page pagination
		$pagination = pagination($page_number, $settings['SET_IMG_ON_PAGE'], $DBCOUNT,$pagination_address);

	//inline ad
		$inline_ad = ($settings['SET_GOOGLE_ADS']?'<div class="gallery_ad">'.$gallery_AdSense.'</div>':'');

	// make gallery
		$imgGallery ='<ul class="gallery" id="row1">';
		foreach($imageList as $k=>$image){

		// get image address
			$thumb_url = imageAddress(3,$image,"dt");
		// get thumb page address
			$thumb_mid_link	= imageAddress(2,$image,"pm");
		//see if there is a alt(title) if not use the image name
			$alt_text = ($image['alt'] !="" ? $image['alt']:$image['name']);
		//image list for page
			$imgGallery .= '
						<li><a href="'.$thumb_mid_link.'" title="'.$alt_text.'" class="thumb" >
							<img src="'.$thumb_url.'" alt="'.$alt_text.'" />
							</a><h2><a href="'.$thumb_mid_link.'" title="'.$alt_text.'">'.$alt_text.'</a></h2>
							'.($settings['SET_ALLOW_REPORT']?'<div class="img_report"><a rel="nofollow" href="#" title="'.$lang["site_gallery_report_title"].'" onclick="return doconfirm(\''.$lang["site_gallery_report_this"].'\',\''.$image['id'].'\','.($page_number+1).');">'.$lang["site_gallery_report"].'</a></div>':'').'
						</li>';
			if(!(($k+1) % $ROW_GALLERY) && ($k+1) != ($settings['SET_IMG_ON_PAGE']) && count($imageList)>($k+1)){
				$imgGallery .= '</ul>'.(isset($inline_ad)?$inline_ad:'').'<ul class="gallery" id="row'.((($k+1)/$ROW_GALLERY)+1).'">';
				unset($inline_ad);
			}

		}//	endfor
		$imgGallery .= '</ul>'.$pagination;
	}

// gallerie end
////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////
// MAKE PAGE END

// set any header hooks
	$header_hook = '
	<script type= "text/javascript">
		var homeUrl = "'.$settings['SET_SITEURL'].'";
	</script>'."\n\r";

	$menu = 'gallery';
	$page_title = $page_number == 0 ? ' - '.$lang["site_search_page_title"].' '.$string.' Page 1':' - '.$lang["site_search_page_title"].' '.$string.' Page '.($page_number+1);
	include './header.php';
		echo error_note($Err);
		echo '<div id="msg"></div>';
		if (isset($imgGallery)){
			echo '<h4 class="search">'.sprintf($lang["site_search_results"],'<span class="search_for">'.$DBCOUNT.'</span>').' <span class="search_for">'.$string.'</span></h4>';
			echo $imgGallery;
		}else{
			echo '<h4 class="search">'.$lang["site_search_no_results"].' <span class="search_for">'.$string.'</span></h4>';
			echo '<p class="search_sug">'.$lang["site_search_suggestions"].'</p>';
		}

	include './footer.php';

// MAKE PAGE END
////////////////////////////////////////////////////////////////////////////////////

class Cleaner {

	var $stopwords = array(" find ", " about ", " me ", " ever ", " each ", " the ", " jpg ", " gif ", " png ", " bmp ");//you need to extend this big time.
	var $symbols = array('/','\\','\'','"',',','.','<','>','?',';',':','[',']','{','}','|','=','+','-','_',')','(','*','&','^','%','$','#','@','!','~','`'	);//this will remove punctuation

	function parseString($string) {
		$string = ' '.$string.' ';
		$string = $this->removeStopwords($string);
		$string = $this->removeSymbols($string);
		return $string;
	}
	
	function removeStopwords($string) {
		for ($i = 0; $i < sizeof($this->stopwords); $i++) {
			$string = str_replace($this->stopwords[$i],' ',$string);
		}

		return trim($string);
	}

	function removeSymbols($string) {
		for ($i = 0; $i < sizeof($this->symbols); $i++) {
			$string = str_replace($this->symbols[$i],' ',$string);
		}

		return trim($string);
	}
}

