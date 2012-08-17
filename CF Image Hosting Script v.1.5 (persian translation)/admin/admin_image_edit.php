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
 *   Used For:     Admin Edit Image Info
 *   Last edited:  07/03/2012
 *
 *************************************************************************************************************/

/*
 * check page is being loaded from within the admin.php page
 * If not send user back admin login page
 */
	if(!isset($admin_page) && $admin_page){
		header('Location: ../admin.php');
		exit();
	}


// Get Image ID
	$edit_img_id = input($_GET['id']);

// Update Image Info If Needed
	if(isset($_POST['update'])){
		$pam = array (
					'alt'		=> input($_POST['setAlt']),
					'private'	=> (input($_POST['setPrivate']) == 1? TRUE:FALSE)
					);
		if(db_update($edit_img_id,$pam)){
			$Suc['edit_image'] = $lang["admin_iep_suc"];
		}
	}

// Get Image Info
	$edit_image = getImage($edit_img_id);

// page settings
	$page['id']					= 'edit';
	$page['title']				= $lang["admin_iep_title"];
	$page['description']	= '';
	$page['tipsy'] 			= true;
	$page['fancybox']		= true;

	require_once('admin/admin_page_header.php');
?>
<!-- admin image edit -->
			<div class="ibox imgedit_img"><a href="<?php echo imageAddress(1,$edit_image);?>" target="_blank" title="<?php echo $edit_image['alt'];?>" class="imglink" id="fancybox"><img src="<?php echo imageAddress(2,$edit_image);?>" title="<?php echo $edit_image['alt'];?>" /></a></div>

			<div class="ibox imgedit_form">
				<h2><?php echo $lang["admin_iep_title"];?></h2>
				<form method="POST" action="admin.php?act=edit&id=<?php echo $edit_img_id;?>" class="">
					<div class="code_box"><label><?php echo $lang["admin_iep_des_title"];?> :</label><input class="text_input" type="text" name="setAlt" value="<?php echo $edit_image['alt'];?>" size="20" /></div>
					<div class="code_box"><label><?php echo $lang["admin_iep_pp_title"];?> :</label>
						<select name="setPrivate" class="text_input">
							<option value="0" <?php echo (!$edit_image['private'] ? 'selected="selected"':'');?>><?php echo $lang["admin_iep_public"];?></option>
							<option value="1" <?php echo ($edit_image['private'] ? 'selected="selected"':'');?>><?php echo $lang["admin_iep_private"];?></option>
						</select></div>
					<div class="code_box"><label></label><input class="button button_cen" onclick="" type="submit" value="<?php echo $lang["admin_iep_button"];?>" name="update"></div>
				</form>
			</div>
			<div class="ibox imgedit_form"><h2>Last 14 Days</h2><?php echo imageCounter($edit_image,14);?></div>
			<div class="clear"></div>
			
<?php

	function imageCounter($image,$days=7){
		global $settings,$lang;
		$today	= time();
		$dball	= db_imageCounterList(ceil(($days*86400)-$today),$image['id']);
		$count	= array_fill(0,$days,0);
		foreach($dball as $k => $v){
			$reldays = ceil(($v['date'] - $today)/86400);
			if(isset($count[abs($reldays)]))$count[abs($reldays)]++;
		}
		krsort($count);

		$barTop = 0;
		foreach($count as $k=>$v){
			$val = (isset($count[$k])?$v:0);
			$barTop = ($barTop >$val?$barTop:$val);
		}
		
		$dt_dd ='';
		$x_axis= '';
		foreach($count as $k=>$v){
			$val = (isset($count[$k])?$v:0);
			$col_date = date('d M', strtotime(n_abs($k).' day'));
			$dt_dd .= '<dt>'.$col_date.'</dt><dd class="'.($odd = empty($odd) ? 'sub' : '').'"><span style="'.($val<=0?'display:none;':'height:'.($val/$barTop*100).'%').'"><a class="tip" title="'.$val.' views on '.$col_date.'">'.$val.'</a></span></dd>';
			$x_axis .='<li><a class="tip" title="'.$val.' views on '.$col_date.'">'.$col_date.'</a></li>';
		}

		$html = '
			<div id="bar_graph">
				<ul class="yAxis"><li class="odd"></li><li></li><li class="odd"></li><li></li><li class="odd"></li><li></li><li class="odd"></li><li></li><li class="odd"></li><li></li></ul>
				<dl id="csschart">'.$dt_dd.'</dl>
				<ul class="xAxis">'.$x_axis.'</ul>
			</div>';
		return $html;
	}
	function n_abs($v) { return ~abs($v) + 1; }

	require_once('admin/admin_page_footer.php');
	die();
	exit;