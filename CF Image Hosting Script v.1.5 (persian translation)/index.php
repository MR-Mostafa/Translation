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
 *   Used For:     index/home page
 *   Last edited:  07/03/2012
 *
 *************************************************************************************************************/

	require_once('./inc/config.php');

// LOAD IMAGE
	loadImage();

// DELETE IMAGE
	removeImage();

// check to see if we need to open a thumbnail page
	if (isset($_GET['pt'])||isset($_GET['pm'])){
		require('thumbnail.php');// THUMB PAGE
	}

// check for errors coming from other pages
	if(isset($_SESSION['err'])){
		$Err = $_SESSION['err'];
		unset($_SESSION['err']);
	}

// set any header hooks
	$header_hook = '
	<script type= "text/javascript">
		var max = '.$settings['SET_MAX_UPLOAD'].';
		var extArray = new Array("'.implode('","',$imgFormats).'");
	</script>'."\n\r";

// set page name for menu
	$menu = 'home';
// set page title
	$page_title = ' - '.$settings['SET_SLOGAN'];

// 404 page error
	$page_error = '';
	if (isset($_GET['err'])){
		header($errorCode[$_GET['err']][0]);	// Send correct HTTP Header
		$page_error = array($errorCode[$_GET['err']][1]);
	}

// load header
	include './header.php'; 
// check for any notes
	success_note($Suc);
// 404 page error
	error_note($page_error); 
// check for any errors
	error_note($Err); 

//home page (upload) variables
	$homeVar['inactive_files']		= $settings['SET_AUTO_DELETED']	? sprintf($lang["site_index_auto_deleted"],$settings['SET_AUTO_DELETED_TIME']).'<br />':'';
	$homeVar['hot_linking_limit']	= $settings['SET_MAX_BANDWIDTH']? '<b>'.$lang["site_index_max_bandwidth"].':</b> '.(format_size(1048576*$settings['SET_MAX_BANDWIDTH'])).' '.$lang["site_index_max_bandwidth_per"].($settings['SET_AUTO_DELETED_JUMP'] == 'm' ? $lang["site_index_max_bandwidth_per_month"]:$lang["site_index_max_bandwidth_per_week"]).'<br />':'';
	$homeVar['max_upload']			= $settings['SET_MAX_UPLOAD']	? '<div class="Upload_Multiple"><span>'.$lang["site_index_max_upload"].' </span><a href="#" class="add_another_file_input"></a><small>('.$lang["site_index_max_upload_max"].' '.$settings['SET_MAX_UPLOAD'].')</small></div>':'';
	$homeVar['hide_tos']			= $settings['SET_HIDE_TOS']		? '<p>'.sprintf($lang["site_index_tos_des"],'<a href="tos.php" title="'.$lang["site_menu_tos"].'">'.$lang["site_menu_tos"].'</a>').'</p>':'';
	$homeVar['private_img']			= $settings['SET_PRIVATE_IMG_ON']? '<input id="private" name="private[0]" value="1" type="checkbox" /> <label for="private">'.$lang["site_index_private_img"].'</label><br/>':'';
	$homeVar['short_url']			= $settings['SET_SHORT_URL_ON']	? '<input id="shorturl" name="shorturl[0]" value="1" type="checkbox" /> <label for="shorturl">'.$lang["site_index_short_url"].' '.$settings['SET_SHORT_URL_API'].'</label><br/>':'';
	$homeVar['resize_img']			= $settings['SET_RESIZE_IMG_ON']? '<span class="title">'.$lang["site_index_resize_title"].':</span> <label for="new_height">'.$lang["site_index_resize_height"].'</label> <input type="text" maxlength="4" size="4" class="text_input" id="new_height" name="new_height[]"><br/><label for="new_width">'.$lang["site_index_resize_width"].'</label> <input type="text" maxlength="4" size="4" class="text_input" id="new_width" name="new_width[]"><span class="small">'.$lang["site_index_resize_des"].'</span>':'';

?>
		<div class="contentBox">
			<div class="<?php /* if($settings['SET_GOOGLE_ADS'] !=''){?> upload_adson<?php }*/ ?>">
			<?php if(!$settings['SET_DIS_UPLOAD']||checklogin()){?>
				<p class="teaser"><?php echo $lang["site_index_des"];?></p>
				<p class="teaser">
					<?php echo $homeVar['inactive_files'];?>
					<?php echo $homeVar['hot_linking_limit'];?>
					<b><?php echo $lang["site_index_Image_Formats"];?>:</b> <?PHP echo implode(", ",$imgFormats); ?><br />
					<b><?php echo $lang["site_index_maximum_filesize"];?>:</b> <?php echo format_size($settings['SET_MAXSIZE']);?>
				</p>
				<form enctype="multipart/form-data" action="upload.php" method="post" class="upform" name="upload" id="upload">
					<div class="upload_op">
						<a id="linklocal" class="linklocal show" title="<?php echo $lang["site_index_local_image_upload_title"];?>"><?php echo $lang["site_index_local_image_upload"];?></a>
						<a id="linkremote" class="linkremote" title="<?php echo $lang["site_index_Remote_image_copy_title"];?>"><?php echo $lang["site_index_Remote_image_copy"];?></a>
					</div>
					<div class="loading">
						<label><?php echo $lang["site_index_uploading_image"];?></label>
						<div id="uoloadingImage"></div>
					</div>
					<div class="input file">
					<a class="closeUpload" href="#" title="Close"> </a>
						<div class="upload_form">
							<div id="remote_panel" class="file_url" style="display: none;">
								<label for="imgUrl"><?php echo $lang["site_index_Remote_image"];?></label>
								<input type="text" name="imgUrl" id="imgUrl"  class="text_input long" />
							</div>
							<div id="local_panel" class="file_upload">
								<label for="file"><?php echo $lang["site_index_upload_image"];?>: </label>
								<div class="file_input_div"><input type="text" id="fileName" name="fileName[]"  class="text_input long" readonly="readonly" />
									<input type="button" value="<?php echo $lang["site_index_upload_browse_button"];?>" name="Search files" class="file_input_button button" />
									<input type="file" name="file[]" id="file" class="file_input_hidden" onchange="javascript: copyfileName()" />
								</div>
							</div>
						</div>
						<label for="alt" class="des"><?php echo $lang["site_index_upload_description"];?></label>
						<input type="text" name="alt[]" id="alt" class="text_input long_des" />

						<?php if($settings['SET_PRIVATE_IMG_ON'] || $settings['SET_SHORT_URL_ON'] || $settings['SET_RESIZE_IMG_ON']){?>
							<div class="pref_title"><?php echo $lang["site_index_upload_preferences"];?></div>
							<div class="preferences">
								<?php echo $homeVar['private_img'];?>
								<?php echo $homeVar['short_url'];?>
								<?php echo $homeVar['resize_img'];?>
							</div>
						<?php } ?>
					</div>
					<?php echo $homeVar['max_upload'];?>
					<input name="submit" type="submit" id="uploadbutton" value="<?php echo $lang["site_index_upload_button"];?>" class="uploadbutton button" onclick="return fileExt(extArray)" />
					<div class="clear_both"></div>
					<?php echo $homeVar['hide_tos'];?>
				</form>
			</div>
			<?php /*if($settings['SET_GOOGLE_ADS'] !=''){?>
			<div class="ad_index"><?php echo $index_AdSense;?></div>
			<div class="ad_index"><?php echo $index_AdSense;?></div>
			<?php }*/ ?>
		<?php }else{//upload disable?>
			<p class="teaser"><b><?php echo $lang["site_index_upload_disable"];?></b></p>
		<?php } ?>
		</div>
<?php
	if(isset($settings['SET_IMAGE_WIDGIT']) && $settings['SET_IMAGE_WIDGIT']){
		ImageWidget($ROW_RANDIMG);
	}
	include './footer.php';