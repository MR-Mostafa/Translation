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
 *   Used For:     Admin Ban User & Banned User list
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

	// ban user
		if(isset($_POST['changesettings'])) {
			$banIp = input($_POST['banIP']);
			$banDescription = input($_POST['banDes']);
			if ($banIp != ''){
				if(db_BanUser($banIp,$banDescription)){
					 $Suc['banned'] = sprintf($lang["admin_ban_suc"],$banIp);
				}else{
					 $Err['banned'] = $lang["admin_ban_err_save_db"];
				}
			}else{
				 $Err['banned'] = $lang["admin_ban_err_no_ip"];
			}
		}

	//unban user
		$ip_to_unban = isset($_GET['ip']) ? input($_GET['ip']):'';
		if ($ip_to_unban != ''){
			if (db_removeFromBanList($ip_to_unban)){
				$Suc['banned'] = sprintf($lang["admin_ban_suc_unbanned"],$ip_to_unban);
			}
		}
	// find ip from image ID
		$id_to_ban = isset($_GET['id']) ? input($_GET['id']):'';
		if ($id_to_ban != ''){
			$BAN_IP = getImage($id_to_ban);
			$BAN_IP = $BAN_IP['ip'];
		}

	// List Banned IP's
		$banned_list='';
		if($banList = db_listBannedUers()){
			foreach ($banList as $k => $v){
				$odd_class = empty($odd_class) ? ' class="odd"' : '';
				$banned_list .= '<tr'.$odd_class.'>
								<td class="textleft"><a href="admin.php?act=ban&ip='.$v['ip'].'" class="tip" title="'.$lang["admin_ban_alt_unban"].'"><img src="img/User-Ok.png" height="16" width="16" border="0" alt="'.$lang["admin_ban_alt_unban"].'"/></a></td>
								<td class="textleft">'.date('d M y',$v['date']).'</td>
								<td class="textleft">'.$v['ip'].'</td><td class="textleft">'.$v['des'].'</td></tr>';
			}
		}


// page settings
	$page['id']					= 'ban';
	$page['title']				= $lang["admin_ban_form_title"];
	$page['description']	= '';
	$page['tipsy']			= true;

	require_once('admin/admin_page_header.php');
?>
<!-- admin Ban -->
			<div class="ibox banForm">
				<h2><?php echo $lang["admin_ban_form_title"];?></h2>
				<form method="POST" action="admin.php?act=ban">
					<div class="code_box <?php echo (isset($ERR_PI)?$ERR_PI:'');?>"><label><?php echo $lang["admin_ban_form_ip"];?> : </label><input class="text_input" type="text" name="banIP" value="<?php echo (isset($BAN_IP)?$BAN_IP:'');?>" size="20" /></div>
					<div class="code_box"><label><?php echo $lang["admin_ban_form_reason"];?> : </label><input class="text_input" type="text" name="banDes" value="" size="20" /></div>
					<input class="button button_cen" onclick="" type="submit" value="<?php echo $lang["admin_ban_form_button"];?>" name="changesettings">
				</form>
			</div>
			<div class="ibox">
				<h2><?php echo $lang["admin_ban_form_title"];?></h2>
				<table class="table_small">
					<thead>
					<tr class="odd">
						<th>&nbsp;</th>
						<th scope="col" title="<?php echo $lang["admin_ban_list_tt_date_banned"];?>"><?php echo $lang["admin_ban_list_date_banned"];?></th>
						<th scope="col" title="<?php echo $lang["admin_ban_list_tt_ip"];?>"><?php echo $lang["admin_ban_list_ip"];?></th>
						<th scope="col" title="<?php echo $lang["admin_ban_list_tt_reason"];?>"><?php echo $lang["admin_ban_list_reason"];?></th>
					</tr>
					</thead>
					<tbody>
						<?php echo (isset($banned_list)?$banned_list:'');?>
					</tbody>
				</table>
			</div>
			<div class="clear"></div>
		</div>
<?php
	require_once('admin/admin_page_footer.php');
	die();
	exit;