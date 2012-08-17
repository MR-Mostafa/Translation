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
 *   Used For:     Holds the adsense code
 *   Last edited:  07/03/2012
 *
 *************************************************************************************************************/

/*
	google_color_border = "FFFFFF";
	google_color_bg = "FFFFFF";
	google_color_link = "0066CC";
	google_color_text = "eeeeee";
	google_color_url = "7F7F7F";
*/
// fix if theme has not set the colors
	if(!isset($AdSense_bg))		$AdSense_bg		 = 'ffffff';
	if(!isset($AdSense_bg_fix))	$AdSense_bg_fix	 = 'ffffff';
	if(!isset($AdSense_bg_fix2))$AdSense_bg_fix2 = 'ffffff';

//Header AdSense Code
	$header_AdSense = '
	<script type="text/javascript">
		<!--
			google_ad_client = "'.$settings['SET_GOOGLE_ADS'].'";
			google_ad_channel ="'.$settings['SET_GOOGLE_CHANNAL'].'";
			/* 728x15 */
			google_ad_width = 728;
			google_ad_height = 15;
			google_ad_format = "728x15_0ads_al_s";
			google_color_link = "'.(isset($adsense_header_cl)?$adsense_header_cl:'0066CC').'";
			google_color_text = "888888";
			google_color_url = "7F7F7F";
			google_color_bg = "'.$AdSense_bg.'";
			google_color_border = "'.$AdSense_bg.'";
		//-->
	</script>
	<script type="text/javascript" src="http://pagead2.googlesyndication.com/pagead/show_ads.js"></script>';


// Index page AdSense Code
	$index_AdSense = '
	<script type="text/javascript">
		<!--
			google_ad_client = "'.$settings['SET_GOOGLE_ADS'].'";
			google_ad_channel ="'.$settings['SET_GOOGLE_CHANNAL'].'";
			google_ad_width = 300;
			google_ad_height = 250;
			google_ad_format = "300x250_as";
			google_ad_type = "text";
			google_color_link = "0066CC";
			google_color_text = "888888";
			google_color_url = "7F7F7F";
			google_color_bg = "'.$AdSense_bg_fix2.'";
			google_color_border = "'.$AdSense_bg_fix2.'";
			google_ui_features = "rc:0";
		//-->
	</script>
	<script type="text/javascript" src="http://pagead2.googlesyndication.com/pagead/show_ads.js"></script>';


// Thumb page AdSense Code
	$thumb_AdSense = '
	<script type="text/javascript">
		<!--
			google_ad_client = "'.$settings['SET_GOOGLE_ADS'].'";
			google_ad_channel ="'.$settings['SET_GOOGLE_CHANNAL'].'";
			google_ad_width = 300;
			google_ad_height = 250;
			google_ad_format = "300x250_as";
			google_ad_type = "text";
			google_color_link = "'.(isset($adsense_thumb_cl)?$adsense_thumb_cl:'0066CC').'";
			google_color_text = "888888";
			google_color_url = "7F7F7F";
			google_color_bg = "'.$AdSense_bg_fix.'";
			google_color_border = "'.$AdSense_bg_fix.'";
			google_ui_features = "rc:0";
		//-->
	</script>
	<script type="text/javascript" src="http://pagead2.googlesyndication.com/pagead/show_ads.js"></script>';


//Gallery Inline AdSense Code
	$gallery_AdSense = '
	<script type="text/javascript">
		<!--
			google_ad_client = "'.$settings['SET_GOOGLE_ADS'].'";
			google_ad_channel ="'.$settings['SET_GOOGLE_CHANNAL'].'";
			/* 728x90 */
			google_ad_width = 728;
			google_ad_height = 90;
			google_ad_format = "728x90_as";
			google_ad_type = "text_image";
			google_color_link = "'.(isset($adsense_gallery_cl)?$adsense_gallery_cl:'0066CC').'";
			google_color_text = "888888";
			google_color_url = "7F7F7F";
			google_color_bg = "'.$AdSense_bg_fix.'";
			google_color_border = "'.$AdSense_bg_fix.'";
		//-->
	</script>
	<script type="text/javascript" src="http://pagead2.googlesyndication.com/pagead/show_ads.js"></script>';


//Footer Inline AdSense Code
	$footer_AdSense = '
	<script type="text/javascript">
		<!--
			google_ad_client = "'.$settings['SET_GOOGLE_ADS'].'";
			google_ad_channel ="'.$settings['SET_GOOGLE_CHANNAL'].'";
			/* 728x90 */
			google_ad_width = 728;
			google_ad_height = 90;
			google_ad_format = "728x90_as";
			google_ad_type = "image";
		//-->
	</script>
	<script type="text/javascript" src="http://pagead2.googlesyndication.com/pagead/show_ads.js"></script>';