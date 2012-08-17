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
 *   Used For:     Web site Header
 *   Last edited:  07/03/2012
 *
 *************************************************************************************************************/

// set menu 
	if(!isset($menu))$menu = 'home';
	flushNow(1);
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="<?php echo $lang["site_charset"];?>">
	<meta http-equiv="X-UA-Compatible" content="IE=8,chrome=1" />
	<title><?php echo $settings['SET_TITLE'];?><?php echo (isset($page_title)?$page_title:'');?></title>
	<meta name="description" content="Free Image Hosting <?php echo (isset($page_title)?$page_title:'');?> - powered by CF Image Hosting" />
	<meta name="keywords" content="images, photos, image hosting, photo hosting, free image hosting"/>
	<meta name="robots" content="index,follow"/>
	<link href="favicon.ico" rel="icon" type="image/x-icon" />
	<link rel="alternate" type="application/rss+xml" title="<?php echo $settings['SET_TITLE'];?> Feed" href="<?PHP echo $settings['SET_SITEURL'];?>/feed.php" />
	<link rel="stylesheet" href="<?PHP echo $settings['SET_SITEURL'];?>/themes/<?php echo $settings['SET_THEME'];?>/<?php echo $settings['SET_THEME'];?>.css?v=<?php echo $settings['SET_VERSION'];?>" type="text/css" />
	<?PHP echo (isset($header_hook)?$header_hook:'');?>
</head>
<body id="<?PHP echo $menu;?>">
<?php if(checklogin()){?>
	<div id="admin_bar">
		<div class="title">Admin Menu</div>
		<ul class="nav">
			<li><a href="<?PHP echo $settings['SET_SITEURL'];?>/admin.php?act=logout" title="<?php echo $lang["admin_menu_logout"];?>"><?php echo $lang["admin_menu_logout"];?></a></li>
			<li><a href="<?PHP echo $settings['SET_SITEURL'];?>/admin.php?act=set" title="<?php echo $lang["admin_menu_settings"];?>"><?php echo $lang["admin_menu_settings"];?></a></li>
			<li><a href="<?PHP echo $settings['SET_SITEURL'];?>/admin.php?act=ban" title="<?php echo $lang["admin_menu_banned"];?>"><?php echo $lang["admin_menu_banned"];?></a></li>
			<li><a href="<?PHP echo $settings['SET_SITEURL'];?>/admin.php?act=db" title="<?php echo $lang["admin_menu_database"];?>"><?php echo $lang["admin_menu_database"];?></a></li>
			<li><a href="<?PHP echo $settings['SET_SITEURL'];?>/admin.php?act=images" title="<?php echo $lang["admin_menu_image_list"];?>"><?php echo $lang["admin_menu_image_list"];?></a></li>
		<li><a href="<?PHP echo $settings['SET_SITEURL'];?>/admin.php" title="<?php echo $lang["admin_menu_home"];?>"><?php echo $lang["admin_menu_home"];?></a></li>
		</ul>
	</div>
<?php } ?>
<div id="wrap">
	<div id="header" class="clear">
		<div id="logo">
			<h1><a href="<?PHP echo $settings['SET_SITEURL'];?>/index.php" title="<?php echo $settings['SET_TITLE'];?> <?php echo $lang["site_menu_home"];?>"><?php echo $settings['SET_TITLE'];?></a></h1>
			<h2><?php echo $settings['SET_SLOGAN'];?></h2>
		</div>

<?php	if ($listLanguages= listLanguages()){?>
		<div class="languages"<?php if(!isset($listLanguagesOver)){ ?> onMouseOver="document.getElementById('language').style.display='block'" onMouseOut="document.getElementById('language').style.display='none'"<?php } ?>>
			<div class="lan_on"><span><?php echo $lang["site_language"];?></span><img src="<?PHP echo $settings['SET_SITEURL'];?>/languages/<?php echo $settings['SET_LANGUAGE'];?>.png" width="23" height="15" alt="<?php echo $settings['SET_LANGUAGE'];?>" title="<?php echo $settings['SET_LANGUAGE'];?>" /></div>
			<div id="language" class="language" ><?php echo listLanguages();?></div>
			<div class="clear_both"></div>
		</div>
<?php } ?>

	<?php if($settings['SET_HIDE_SEARCH']){?>
		<div id="search">
			<form method="get" action="<?PHP echo $settings['SET_SITEURL'];?>/search.php">
				<input type="text" size="28" name="search" id="searchBox" class="text_input" onblur="if(this.value=='')value='<?php echo $lang["site_search_text"];?>';" onfocus="if(this.value=='<?php echo $lang["site_search_text"];?>')value=''" value="<?php echo $lang["site_search_text"];?>" /><input type="submit" value="<?php echo $lang["site_search_button"];?>" class="button" />
			</form>
		</div>
	<?php } ?>
		<div id="nav">
			<ul id="main-nav">
				<li><a <?php echo ($menu=="home" ? ' class="current" ':'');?> href="<?PHP echo $settings['SET_SITEURL'];?>/" title="<?php echo $lang["site_menu_home"];?>"><?php echo $lang["site_menu_home"];?></a></li>
				<?php if($settings['SET_HIDE_GALLERY']){?><li><a <?php echo ($menu=="gallery" ? ' class="current" ':'');?> href="<?PHP echo $settings['SET_SITEURL'];?>/gallery.php" title="<?php echo $lang["site_menu_gallery"];?>"><?php echo $lang["site_menu_gallery"];?></a></li><?php } ?>
				<?php if($settings['SET_HIDE_FAQ']){?><li><a <?php echo ($menu=="faq_page" ? ' class="current"':'');?> href="<?PHP echo $settings['SET_SITEURL'];?>/faq.php" title="<?php echo $lang["site_menu_faq"];?>"><?php echo $lang["site_menu_faq"];?></a></li><?php } ?>
				<?php if($settings['SET_HIDE_TOS']){?><li><a <?php echo ($menu=="tos_page" ? ' class="current" ':'');?> href="<?PHP echo $settings['SET_SITEURL'];?>/tos.php" title="<?php echo $lang["site_menu_tos"];?>"><?php echo $lang["site_menu_tos"];?></a></li><?php } ?>
				<?php if($settings['SET_HIDE_CONTACT']){?><li><a <?php echo ($menu=="contact_page" ? ' class="current" ':'');?> href="<?PHP echo $settings['SET_SITEURL'];?>/contact.php" title="<?php echo $lang["site_menu_contact"];?>"><?php echo $lang["site_menu_contact"];?></a></li><?php } ?>
			</ul>
		</div>
	<div class="clear"></div>
	</div>
	<div id="content">
<?php if($settings['SET_GOOGLE_ADS']){?>
		<div class="top_ad"><?php echo $header_AdSense;?></div>
<?php }
	flushNow(1);
?>