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
 *   Used For:     Admin Image List
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


/* 
 * Check for images in database
 */
	if(imageList(0,1)){
	
////////////////////////////////////////////////////////////////////////////////////
// workout the last reset date for home page and image list

	// workout the last reset date
		if ($settings['SET_BANDWIDTH_RESET'] == 'm'){
			 $resetdate = strtotime('01 '.date('M Y'));
		}else{
			 $resetdate = strtotime("last Monday");
		}

////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////
// (ADMIN HOME)
	//var for totals
		$total_bw			= 0;

	// add bandwidth & counter to image DB
		$db_img = imageList(0,'all');
		$total_image_size = 0;
		$ro = 0;
		$rp = 0;
		foreach ($db_img as $k => $v){
			$ro++;
		// image view counter db
			$hc = array('image'=>0,'thumb_mid'=>0,'thumb'=>0,'date'=>0,'hotlinked'=>0,'gallery'=>0);
			$db_img[$k]['bandwidth'] = 0;
			
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
						$db_img[$k]['bandwidth'] += $vCount['bandwidth'];
				}
			}

			$db_img[$k]['bandwidthall']	= 0+($v['size']*$hc['image'])+($v['thumbsize']*$hc['thumb_mid'])+($v['sthumbsize']*$hc['thumb']);
			$db_img[$k]['lastviewed']	= ($hc['date'] == (int)0 ? round(((time() - $v['added']) / 86400),2):round(((time() - $hc['date']) / 86400),2));
			$db_img[$k]['hotlink']		= 0+$hc['image']+$hc['thumb_mid']+$hc['thumb'];
			$db_img[$k]['gallery']		= (!isset($hc['gallery']) ? 0:$hc['gallery']);

		}
	// empty memory
		unset($hc);
		unset($ImageDbCounter);
		unset($IMAGEDB);
		if($act !='images'){
			unset($db_img);
		}

		// number of items on page
			$item_on_page = (isset($_GET['list']) ? input($_GET['list']):20);
			$list_url = ($item_on_page==20?'':'&list='.$item_on_page);

		// order image list by
			$orderBy	= (isset($_GET['orderBy']) ? input($_GET['orderBy']):'added');
			$orderby_url = ($orderBy=='added'?'':'&orderBy='.$orderBy);
		// sort order
			$order		= (isset($_GET['order']) ? 'ASC':'DESC');
			$order_url = ($order=='ASC'?'':'&order='.$order);


		// what page are we on
			$page_number = (isset($_GET['p']) ? input($_GET['p'])-1:0);

		// setup pagination address
			$pagination_address = $settings['SET_SITEURL'].'/admin.php?p=%1$s&act=images'.$list_url.$orderby_url; // %1 page number
		// page pagination
			$pagination = pagination($page_number, $item_on_page, $DBCOUNT,$pagination_address);

		// order DB
			order_by($db_img,$orderBy,$order);
			$imageList = array_slice($db_img, ($page_number*$item_on_page), $item_on_page);

		//make image list
			$list_item = '';
			foreach($imageList as $k=>$image){
				$bandwidthStyle = ($settings['SET_MAX_BANDWIDTH'] !=0 && ($settings['SET_MAX_BANDWIDTH']*1048576) < $image['bandwidth']?' style="background-color:red;color:#000"':'');

				$list_item .='
					<tr class="'.($odd = empty($odd) ? 'odd' : '').'">
						<td>
							<a href="'.imageAddress(2,$image,"pm").'" class="tip" title="'.$lang["admin_ilp_thumb_page_link"].'"><img src="img/Image-Info.png" height="16" width="16" border="0" alt="'.$lang["admin_ilp_thumb_page_link"].'" /></a>
							<a href="admin.php?act=edit&id='.$image['id'].'" class="tip" title="'.$lang["admin_ilp_edit_alt"].'"><img src="img/Image-Edit.png" height="16" width="16" border="0" alt="'.$lang["admin_ilp_edit_alt"].'" /></a>
							<a href="#" id="'.$image['deleteid'].'" class="tip delete" title="'.$lang["admin_ilp_report_alt_delete"].'" ret="'.sprintf($lang["admin_ilp_report_delete"],$image['id']).'" ><img src="img/Image-Del.png" height="16" width="16" border="0" alt="'.$lang["admin_ilp_report_alt_delete"].'" /></a>
							<a href="admin.php?act=ban&id='.$image['id'].'" class="tip" title="'.$lang["admin_ilp_report_alt_ban"].'"><img src="img/User-Block.png" height="16" width="16" border="0" alt="'.$lang["admin_ilp_report_alt_ban"].'" /></a>
						</td>
						<td>'.date('d M y',$image['added']).'</td>
						<td><a href="'.imageAddress(1,$image).'" target="_blank" title="'.$image['name'].'" img_src="<img src=\''.imageAddress(3,$image).'\'/>" class="imglink img_tooltip" id="fancybox">'.$image['alt'].'</a></td>
						<td>'.$image['lastviewed'].'</td>
						<td>'.$image['gallery'].'</td>
						<td>'.$image['hotlink'].'</td>
						<td'.$bandwidthStyle.'>'.format_size($image['bandwidth']).'</td>
						<td>'.(array_key_exists('private',$image) && $image['private']?'Yes':'No').'</td>
					</tr>';
			}
	}else{
		$orderBy				= '';
		$db_img_count		= '';
		$total_image_size	= '';
		$total_bw 				= '';
		$list_item				= '';
		$pagination			= '';
		$mostBwImage		= array('id' => null,'alt' => '', 'bandwidth' => 0,'hotlink' =>0, 'ext' => '');
		$mostViewImage	= array('id' => null,'alt' => '', 'bandwidth' => 0,'hotlink' =>0, 'ext' => '');
	}


// page settings
	$page['id']					= 'images';
	$page['title']				= $lang["admin_menu_image_list"];
	$page['description']	= '';
	$page['tipsy'] 			= true;
	$page['fancybox']		= true;

	require_once('admin/admin_page_header.php');

?>
<!-- admin image list -->
			<div class="ibox full">
				<h2><?php echo $lang["admin_menu_image_list"];?></h2>
				<div class="table_top">
					<div class="col">
						<select name="onPage" size="1" class="text_input" onChange="if(value) window.location.href = this.value;">
							<option selected value="admin.php?act=images<?php echo $order_url.$orderby_url;?>"><?php echo $lang["admin_ilp_number_to_list"];?></option>
							<option value="admin.php?act=images&list=20<?php echo $order_url.$orderby_url;?>">20</option>
							<option value="admin.php?act=images&list=40<?php echo $order_url.$orderby_url;?>">40</option>
							<option value="admin.php?act=images&list=80<?php echo $order_url.$orderby_url;?>">80</option>
							<option value="admin.php?act=images&list=100<?php echo $order_url.$orderby_url;?>">100</option>
							<option value="admin.php?act=images&list=999999<?php echo $order_url.$orderby_url;?>"><?php echo $lang["admin_ilp_number_to_list_all"];?></option>
						</select>
					</div>
					<div class="col">
						<select name="orderBy" size="1" class="text_input" onChange="if(value) window.location.href = this.value;">
							<option selected value="admin.php?act=images<?php echo $order_url.$list_url;?>"><?php echo $lang["admin_ilp_order_list"];?></option>
							<option <?php echo ($orderBy=="added"		? '':'');?> value="admin.php?act=images&orderBy=added<?php echo $order_url.$list_url;?>"><?php echo $lang["admin_ilp_order_list_date_added"];?></option>
							<option <?php echo ($orderBy=="lastviewed"	? 'selected="selected"':'');?> value="admin.php?act=images&orderBy=lastviewed<?php echo $order_url.$list_url;?>"><?php echo $lang["admin_ilp_order_list_last_viewed"];?></option>
							<option <?php echo ($orderBy=="hotlink"		? 'selected="selected"':'');?> value="admin.php?act=images&orderBy=hotlink<?php echo $order_url.$list_url;?>"><?php echo $lang["admin_ilp_order_list_hotlink_views"];?></option>
							<option <?php echo ($orderBy=="bandwidth"	? 'selected="selected"':'');?> value="admin.php?act=images&orderBy=bandwidth<?php echo $order_url.$list_url;?>"><?php echo $lang["admin_ilp_order_list_bandwidth_used"];?></option>
							<option <?php echo ($orderBy=="gallery"		? 'selected="selected"':'');?> value="admin.php?act=images&orderBy=gallery<?php echo $order_url.$list_url;?>"><?php echo $lang["admin_ilp_order_list_gallery_clicked"];?></option>
						</select>
					</div>
					<div class="clear"></div>
				</div>
				<table>
					<thead>
					<tr class="odd">
						<th>&nbsp;</th>
						<th scope="col" title="<?php echo $lang["admin_ilp_imglist_tt_image_added"];?>"><a href="admin.php?act=images&orderBy=added<?php echo $order_url.$list_url;?>" class="<?php echo ($orderBy=='added'?'on':'');?>"><?php echo $lang["admin_ilp_imglist_image_added"];?></a></th>
						<th scope="col" title="<?php echo $lang["admin_ilp_imglist_tt_image_name"];?>"><?php echo $lang["admin_ilp_imglist_image_name"];?></th>
						<th scope="col" title="<?php echo $lang["admin_ilp_imglist_tt_last_viewed"];?>"><a href="admin.php?act=images&orderBy=lastviewed<?php echo $order_url.$list_url;?>" class="<?php echo ($orderBy=='lastviewed'?'on':'');?>"><?php echo $lang["admin_ilp_imglist_last_viewed"];?></a></th>
						<th scope="col" title="<?php echo $lang["admin_ilp_imglist_tt_gallery_clicks"];?>"><a href="admin.php?act=images&orderBy=gallery<?php echo $order_url.$list_url;?>" class="<?php echo ($orderBy=='gallery'?'on':'');?>"><?php echo $lang["admin_ilp_imglist_gallery_clicks"];?></a></th>

						<th scope="col" title="<?php echo $lang["admin_ilp_imglist_tt_hotlink_views"];?>"><a href="admin.php?act=images&orderBy=hotlink<?php echo $order_url.$list_url;?>" class="<?php echo ($orderBy=='hotlink'?'on':'');?>"><?php echo $lang["admin_ilp_imglist_hotlink_views"];?></a></th>
						<th scope="col" title="<?php echo $lang["admin_ilp_imglist_tt_bandwidth_used"];?>"><a href="admin.php?act=images&orderBy=bandwidth<?php echo $order_url.$list_url;?>" class="<?php echo ($orderBy=='bandwidth'?'on':'');?>"><?php echo $lang["admin_ilp_imglist_bandwidth_used"];?></a></th>
						<th scope="col" title="<?php echo $lang["admin_ilp_imglist_tt_private"];?>"><?php echo $lang["admin_ilp_imglist_private"];?></th>
					</tr>
					</thead>
					<tbody>
						<?php echo $list_item;?>
					</tbody>
				</table>
				<?php echo $pagination;?>
			</div>
			<div class="clear"></div>
		</div>
		<div class="clear"></div>

<?php

	require_once('admin/admin_page_footer.php');
	die();
	exit;