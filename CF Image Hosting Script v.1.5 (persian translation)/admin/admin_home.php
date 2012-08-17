<?php

/**************************************************************************************************************
 *
 *   CF Image Hosting Script
 *   ---------------------------------
 *
 *   Author:    codefuture.co.uk
 *   Version:   1.5
 *   Date:       25 February 2011
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
 *   Used For:     Admin Dashboard
 *   Last edited:  15/02/2012
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


/* 
 * Check for images in database
 */
	if(imageList(0,1)){
	
	/* 
	* Image Report List
	*/
	$report_list = null;
	if($settings['SET_ALLOW_REPORT'] && $act != 'images'){

		if ($act == "report" && input($_GET['id']) != ''){
			if(db_removeFromReportList(input($_GET['id']))){
				$Suc['image_report_remove'] = $lang["admin_home_report_remove_suc"];
			}else{
				$Err['image_report_remove'] = $lang["admin_home_report_remove_err"];
			}
		}

		if($full_report = db_imageReportList()){
			foreach ($full_report as $k => $image){
				$odd_class		= empty($odd_class) ? ' class="odd"' : '';
				$img_alt		= $image['alt'];
				$img_name		= $image['name'];
				$img_deleteid	= $image['deleteid'];
				$img_url		= imageAddress(1,$image);
				$img_thumb_url	= imageAddress(3,$image);
				$report_list .= '<tr'.$odd_class.'>
								<td>
									<a href="admin.php?act=report&id='.$image['id'].'" class="tip" title="'.$lang["admin_home_report_alt_remove"].'"><img src="img/Image-Ok.png" height="16" width="16" border="0" alt="'.$lang["admin_home_report_alt_remove"].'" /></a>
									<a href="#" id="'.$img_deleteid.'" class="tip delete"  title="'.$lang["admin_home_report_alt_delete"].'" ret="'.sprintf($lang["admin_home_report_delete"],$image['id']).'"><img src="img/Image-Del.png" height="16" width="16" border="0" alt="'.$lang["admin_home_report_alt_delete"].'" /></a>
									<a href="admin.php?act=ban&id='.$image['id'].'" class="tip" title="'.$lang["admin_home_report_alt_ban"].'"><img src="img/User-Block.png" height="16" width="16" border="0" alt="'.$lang["admin_home_report_alt_ban"].'" /></a>
								</td>
								<td><a href="'.$img_url.'" target="_blank" title="'.$img_alt.'" img_src="<img src=\''.$img_thumb_url.'\' />" class="imglink img_tooltip" id="fancybox">'.$img_name.'</a></td>';
			}
		}
	} // Report end

// workout the last reset date for home page and image list
	if ($settings['SET_BANDWIDTH_RESET'] == 'm'){
		 $resetdate = strtotime('01 '.date('M Y'));
	}else{
		 $resetdate = strtotime("last Monday");
	}

	function echo_memory_usage() {
		$mem_usage = memory_get_usage(true);
		if ($mem_usage < 1024) echo $mem_usage." bytes";
		elseif ($mem_usage < 1048576) echo round($mem_usage/1024,2)." kilobytes";
		else echo round($mem_usage/1048576,2)." megabytes";
		echo "<br/>";
	}


	//var for totals
		$total_bw = 0;

	// add bandwidth & counter to image DB
		$db_img = imageList(0,'all');
		$total_image_size = 0;
		$ro = 0;
		$rp = 0;
		foreach ($db_img as $k => $v){
			$ro++;
		// image view counter db
			$hc = array('image'=>0,'thumb_mid'=>0,'thumb'=>0,'date'=>0,'hotlinked'=>0,'gallery'=>0);
			if($listFromReset = db_imageCounterList($resetdate,$v['id'])){
				unset($ImageDbCounter);// empty memory
				foreach($listFromReset as $kCount => $vCount){
					$rp++;

					$hc = array(
							'date'		=> ($vCount['date'] >= $hc['date']?$vCount['date']:$hc['date']),
							'image'		=> $hc['image']+$vCount['image'],
							'thumb_mid'	=> $hc['thumb_mid']+$vCount['thumb_mid'],
							'thumb'		=> $hc['thumb']+$vCount['thumb'],
							'hotlinked'	=> $hc['hotlinked']+($vCount['image']||$vCount['thumb_mid']||$vCount['thumb']? 1:0),
							'gallery'	=> $hc['gallery']+$vCount['gallery'],
							);
				}
			}

			$db_img[$k]['bandwidth']	= 0+($v['size']*$hc['image'])+($v['thumbsize']*$hc['thumb_mid'])+($v['sthumbsize']*$hc['thumb']);
			$db_img[$k]['lastviewed']	= ($hc['date'] == (int)0 ? round(((time() - $v['added']) / 86400),2):round(((time() - $hc['date']) / 86400),2));
			$db_img[$k]['hotlink']		= 0+$hc['image']+$hc['thumb_mid']+$hc['thumb'];
			$db_img[$k]['gallery']		= (!isset($hc['gallery']) ? 0:$hc['gallery']);

			if($act !='images'){
			//totals
				$total_bw = $total_bw + $db_img[$k]['bandwidth'];
				$total_image_size = $total_image_size + $v['size'];

			// TOP IMAGES
				if(!isset($mostBwImage) || $mostBwImage['bandwidth'] < $db_img[$k]['bandwidth']){
					$mostBwImage = $db_img[$k];
				}
				if(!isset($mostViewImage) || $mostViewImage['hotlink'] < $db_img[$k]['hotlink']){
					$mostViewImage = $db_img[$k];
				}
			}
		}
	// empty memory
		unset($hc);
		unset($ImageDbCounter);
		unset($IMAGEDB);
		if($act !='images'){
			unset($db_img);
		}
		
	}else{
		$orderBy				= '';
		$db_img_count			= '';
		$total_image_size		= '';
		$total_bw 				= '';
		$list_item				= '';
		$pagination				= '';
		$mostBwImage = array('id' => null,'alt' => '', 'bandwidth' => 0,'hotlink' =>0, 'ext' => '');
		$mostViewImage = array('id' => null,'alt' => '', 'bandwidth' => 0,'hotlink' =>0, 'ext' => '');
	}
	
	

// page settings
	$page['id']					= 'home';
	$page['title']				= $lang["admin_menu_home"];
	$page['description']	= '';
	$page['tipsy'] 			= true;
	$page['fancybox']		= true;

// load page header
	require_once('admin/admin_page_header.php');
?>
<!-- admin home -->
			<div class="ibox top_img">
				<h2><?php echo $lang["admin_menu_home"];?></h2>
				<div class="quickview">
					<h3><?php echo $lang["admin_home_overview"];?></h3>
					<ul>
					<li><?php echo $lang["admin_home_total_images"];?>: <span class="number"><?php echo $DBCOUNT;?></span></li>
					<li><?php echo $lang["admin_home_private_images"];?>: <span class="number"><?php echo $DbPrivate;?></span></li>
					<li><?php echo $lang["admin_home_filespace_used"];?>: <span class="number"><?php echo format_size($total_image_size);?></span></li>
					<li><?php echo $lang["admin_home_total_bandwidth"];?>: <span class="number"><?php echo format_size($total_bw);?></span></li>
					<li><?php echo $lang["admin_home_last_backup"];?>: <span class="number"><?php echo date("F j, Y, g:i a",$settings['SET_LAST_BACKUP_IMAGE']);?></span></li>
					
					</ul>
				</div>
			</div>
			<div class="ibox">
				<h2><?php echo $lang["admin_home_top_image"];?></h2>
				<div class="quickview tInfo">
					<h3><?php echo $lang["admin_home_by_bandwidth"];?></h3>
					<ul>
						<li><?php echo $lang["admin_home_id"];?>: <span class="number"><a href="<?php echo imageAddress(1,$mostBwImage);?>"  target="_blank" title="<?php echo $mostBwImage['alt'];?>" img_src="<img src='<?php echo imageAddress(3,$mostBwImage);?>' />" class="imglink img_tooltip" id="fancybox"><?php echo $mostBwImage['id'];?></a></span></li>
						<li><?php echo $lang["admin_home_name"];?>: <span class="number"> <?php echo $mostBwImage['alt'];?></span></li>
						<li><?php echo $lang["admin_home_bandwidth"];?>: <span class="number"> <?php echo format_size($mostBwImage['bandwidth']);?></span></li>
						<li><?php echo $lang["admin_home_hotlink_views"];?>: <span class="number"> <?php echo $mostBwImage['hotlink'];?></span></li>
					</ul>
				</div>
				<div class="quickview tInfo">
					<h3><?php echo $lang["admin_home_by_hotlink_views"];?></h3>
					<ul>
						<li><?php echo $lang["admin_home_id"];?>: <span class="number"><a href="<?php echo imageAddress(1,$mostViewImage);?>"  target="_blank" title="<?php echo $mostViewImage['alt'];?>" img_src="<img src='<?php echo imageAddress(3,$mostViewImage)?>' />" class="imglink img_tooltip" id="fancybox"><?php echo $mostViewImage['id'];?></a></span></li>
						<li><?php echo $lang["admin_home_name"];?>: <span class="number"> <?php echo $mostViewImage['alt'];?></span></li>
						<li><?php echo $lang["admin_home_bandwidth"];?>: <span class="number"> <?php echo format_size($mostViewImage['bandwidth']);?></span></li>
						<li><?php echo $lang["admin_home_hotlink_views"];?>: <span class="number"> <?php echo $mostViewImage['hotlink'];?></span></li>
					</ul>
				</div>
			</div>
			<div class="clear"></div>
		<?php if($settings['SET_ALLOW_REPORT']){?>
			<div class="ibox top_img">
				<h2><?php echo $lang["admin_home_reported_images"];?></h2>
				<?php if(isset($report_list) && $report_list!=null){?>
				<table class="table_small">
					<thead>
						<tr>
							<th>&nbsp;</th>
							<th scope="col" title="<?php echo $lang["admin_home_tooltip_image_name"];?>"><?php echo $lang["admin_home_image_name"];?></th>
						</tr>
					</thead>
					<tbody>
						<?php echo $report_list;?>
					</tbody>
				</table>
				<?php }else{?>
					<center><?PHP echo $lang["admin_home_noreported"];?></center>
				<?php } ?>
			</div>
<?php } ?>
			<div class="clear"></div>
		</div>


<?php


	require_once('admin/admin_page_footer.php');
	die();
	exit;