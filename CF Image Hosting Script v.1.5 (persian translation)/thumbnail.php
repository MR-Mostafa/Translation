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
 *   Used For:     THUMB PAGE
 *   Last edited:  07/03/2012
 *
 *************************************************************************************************************/

 
	require_once('./inc/config.php');

// find image id 
	if(isset($_GET['pt'])||isset($_GET['pm'])){
		unset($_SESSION['upload']);
		$_SESSION['upload'][0]['id'] = isset($_GET['pt'])?$_GET['pt']:$_GET['pm'];
	}
// if no image found and no upload array found send user back to index page
	elseif(!isset($_SESSION['upload'])){
		header('Location: index.php');
		exit();
	}

// check for errors coming from other pages
	if(isset($_SESSION['err'])){
		$Err = $_SESSION['err'];
		unset($_SESSION['err']);
	}

// check what thumb to show!
	$showImage = isset($_GET['pt'])?1:0;

// count number of thumb's on page
	$countThumb = 0;

// image loop
	foreach($_SESSION['upload'] as $k=>$uploadimage){

		if(isset($uploadimage['id'])){
			$img_id =$uploadimage['id'];
		}
		if(isset($uploadimage['did'])){
			$delete_id =$uploadimage['did'];
		}
		unset($_SESSION['upload'][$k]);

		if(isset($img_id) && preg_replace("/[^0-9A-Za-z]/","",$img_id) == $img_id){
		//see if image exists
			if ($image = getImage($img_id)){

			// hold thumbnail page html
				if(!isset($thumbHtml))$thumbHtml = '';

			//only count image if not on upload page
				if(!isset($delete_id))countSave($image,4);

			// Thumbnail page variables
				$thumb_link		= imageAddress(3,$image,'pt');
				$thumb_url		= imageAddress(3,$image,'dt');
				$thumb_mid_link	= imageAddress(2,$image,'pm');
				$thumb_mid_url	= imageAddress(2,$image,'dm');
				$imgurl			= imageAddress(1,$image,'di');
				$alt			= $image['alt'];
				$shorturl		= $image['shorturl'];
				$bookmarking	= bookmarking(($shorturl ==null?$thumb_mid_link:$shorturl),$alt);
				$thumb_show		= $showImage?$thumb_url:$thumb_mid_url;

			// make image links
				$links[$countThumb] = array(
									'thumb_bbcode'		=> imageLinkCode('bbcode',$thumb_url,$thumb_link),
									'thumb_html'		=> imageLinkCode('html',$thumb_url,$thumb_link,$alt),
									'thumb_mid_bbcode'	=> imageLinkCode('bbcode',$thumb_mid_url,$thumb_mid_link),
									'thumb_mid_html'	=> imageLinkCode('html',$thumb_mid_url,$thumb_mid_link,$alt),
									'image_bbcode'		=> imageLinkCode('bbcode',$imgurl),
									'image_direct'		=> $imgurl,
									'delete_url'		=> (isset($delete_id)?$settings['SET_SITEURL'].'/?d='.$delete_id:'')
									);
			// comments layout
				$layout = ' full';

				$thumbHtml .= '<div class="img_ad_box '.(isset($countThumb) && $countThumb > 0?' nextbox':'').'">';
			// AdSense
				$ad_added = 0;
				if($settings['SET_GOOGLE_ADS'] !=''){
					if(!isset($countThumb) || $countThumb < 2){
						$thumbHtml .= '<div class="thumb_Ad">'.$thumb_AdSense.'</div>';
						$ad_added = 1;
					}
				}
			//image box
				$thumbHtml .= '<div class="img_box'.($ad_added?' left':'').'"><a href="'.$imgurl.'" title="'.$alt.'" id="fancybox" ><img src="'.$thumb_show.'" alt="'.$alt.'" /><br/><span>'.$alt.'</span></a></div>
						<div style="clear: both;"></div>
					</div>';


			//image links
				$thumbHtml .= '<div id="links" class="boxpanel'.$layout.'">
									<h2 class="boxtitle">'.$lang["site_index_hide_link"].'</h2>
									<div class="code_box"><label id="toplabel">'.$lang["site_index_social_networks"].':</label>'.$bookmarking.'</div>';
			// Short URL
				if ($shorturl != null && !empty($shorturl))	$thumbHtml .= '
									<div class="code_box"><label for="shorturl">'.$lang["site_index_short_url_link"].':</label> <input type="text" id="codehtml" value="'.$shorturl.'" onclick="javascript:this.focus();this.select();" readonly="true" class="text_input long" /></div>';

			// Image Links
				$thumbHtml .= '
						<h3>'.$lang["site_index_small_thumbnail_link"].'</h3>
							<div class="code_box"><label for="codelbb">'.$lang["site_index_bbcode"].':</label> <input type="text" id="codelbb" value="'.$links[$countThumb]['thumb_bbcode'].'" onclick="javascript:this.focus();this.select();" readonly="true" class="text_input long" /></div>
							<div class="code_box"><label for="codehtml"><a href="'.$thumb_link.'" title="'.$alt.'" >'.$lang["site_index_html_code"].'</a> :</label> <input type="text" id="codehtml" value="'.$links[$countThumb]['thumb_html'].'" onclick="javascript:this.focus();this.select();" readonly="true" class="text_input long" /></div>
						<h3>'.$lang["site_index_thumbnail_link"].'</h3>
							<div class="code_box"><label for="codelbb">'.$lang["site_index_bbcode"].':</label> <input type="text" id="codelbb" value="'.$links[$countThumb]['thumb_mid_bbcode'].'" onclick="javascript:this.focus();this.select();" readonly="true" class="text_input long" /></div>
							<div class="code_box"><label for="codehtml"><a href="'.$thumb_mid_link.'" title="'.$alt.'" >'.$lang["site_index_html_code"].'</a> :</label> <input type="text" id="codehtml" value="'.$links[$countThumb]['thumb_mid_html'].'" onclick="javascript:this.focus();this.select();" readonly="true" class="text_input long" /></div>
						<h3>'.$lang["site_index_image_link"].'</h3>
						<div class="code_box"><label for="codebb">'.$lang["site_index_bbcode"].':</label> <input type="text" id="codebb" value="'.$links[$countThumb]['image_bbcode'].'" onclick="javascript:this.focus();this.select();" readonly="true" class="text_input long" /></div>
						<div class="code_box"><label for="codedirect">'.$lang["site_index_direct_link"].'</label> <input type="text" id="codedirect" value="'.$links[$countThumb]['image_direct'].'" onclick="javascript:this.focus();this.select();" readonly="true" class="text_input long" /></div>';

				if(isset($delete_id)){
					$thumbHtml .= '
					<h3>حذف تصویر</h3>
						<div class="code_box"><label for="deletecode">'.$lang["site_index_delete_url"].':</label> <input type="text" id="deletecode" value="'.$links[$countThumb]['delete_url'].'" onclick="javascript:this.focus();this.select();" readonly="true" class="text_input long" /></div>
						<p class="teaser">'.$lang["site_index_delete_url_des"].'</p>';
				}
				$thumbHtml .= '</div>';
				$thumbHtml .= '<div style="clear: both;"></div>';
			}else{
				$Err['thumbs_page'] = $lang["site_index_thumbs_page_err"];
			}
		}
		$countThumb++;
	}// end uploaded loop

// unset upload array
	unset($_SESSION['upload']);

////////////////////////////////////////////////////////////////////////////////////
// MAKE PAGE

// error send back to home page and show the error
	if(!isset($thumbHtml)){
		unset($_GET);
		$_GET['err'] = '404';
		include('index.php');
		exit();
	}

// set any header hooks
	$header_hook = '<link rel="stylesheet" type="text/css" href="'.$settings['SET_SITEURL'].'/lightbox/jquery.fancybox-1.3.4.css" media="screen" />'."\n\r";
	$header_hook .= '
	<script type= "text/javascript">
		var homeUrl = "'.$settings['SET_SITEURL'].'";
	</script>'."\n\r";

// set any footer hooks
	$footer_hook ='	<script type="text/javascript" src="'.$settings['SET_SITEURL'].'/lightbox/jquery.fancybox-1.3.4.pack.js"></script>
	<script type="text/javascript">$(document).ready(function(){$("a#fancybox").fancybox({\'titlePosition\' : \'inside\', \'type\' : \'image\'});});</script>'."\n\r";

// set thumb page
	$menu = 'thumb';
// set page title
	$page_title = ' - '.(isset($alt)?$alt:' No Image Found');
// load header
	include './header.php'; 
// check for any notes
	success_note($Suc);
// check for any errors
	error_note($Err);
	echo '<div id="msg"></div>';
// print Thumbnail page
	echo $thumbHtml;

	if(isset($settings['SET_IMAGE_WIDGIT']) && $settings['SET_IMAGE_WIDGIT']){
		ImageWidget($ROW_RANDIMG);
	}
	include './footer.php';
	exit;
