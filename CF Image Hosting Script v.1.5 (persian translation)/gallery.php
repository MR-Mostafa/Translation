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
 *   Used For:     Gallery Page
 *   Last edited:  07/03/2012
 *
 *************************************************************************************************************/
	require_once('./inc/config.php');

	if(!$settings['SET_HIDE_GALLERY']){
		header('Location: index.php');
		exit();
	}

// see what page we are on
	$page_number = (isset($_GET['p']) && is_int((int)$_GET['p'])?$_GET['p']-1:0);	
// hold gallery html
	$imgGallery = '';

// setup pagination address
	if($settings['SET_MOD_REWRITE']){
		$pagination_address = $settings['SET_SITEURL'].'/gallery/%1$s/';
	}else{
		$pagination_address = $settings['SET_SITEURL'].'/gallery.php?p=%1$s';
	}

// get images for page
	if($imageList = imageList(($page_number*$settings['SET_IMG_ON_PAGE']))){

	// page pagination
		if(!isset($paginationLinkNo))$paginationLinkNo=null;
		$pagination = pagination($page_number, $settings['SET_IMG_ON_PAGE'], $DBCOUNT,$pagination_address,$paginationLinkNo);

	//inline ad
		$inline_ad = ($settings['SET_GOOGLE_ADS']?'<div class="gallery_ad">'.$gallery_AdSense.'</div>':'');

	// make gallery
		$imgGallery = '<ul class="gallery" id="row1">';
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
// check to see if the page number is right
	elseif(($page_number+1) > ceil($DBCOUNT/$settings['SET_IMG_ON_PAGE'])){
		header('Location: '.sprintf($pagination_address, ceil($DBCOUNT/$settings['SET_IMG_ON_PAGE'])));
	}
// no images in the db
	else{
		$Err['noImage'] = $lang["site_gallery_err_no_image"];
	}

// set any header hooks
	$header_hook = '
	<script type= "text/javascript">
		var homeUrl = "'.$settings['SET_SITEURL'].'";
	</script>'."\n\r";

	$menu = "gallery";// set page name for menu
	$page_title = ' - '.$lang["site_gallery_page_title"].' '.($page_number+1); // set page title
	include './header.php'; // load header
		error_note($Err);
		success_note($Suc);
		echo '<div id="msg"></div>';
		echo $imgGallery;
	include './footer.php';
