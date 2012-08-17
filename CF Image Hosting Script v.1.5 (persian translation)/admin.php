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
 *   Used For:     admin home/menu page
 *   Last edited:  07/03/2012
 *
 *************************************************************************************************************/

	ini_set("max_execution_time", "600");
	ini_set("max_input_time", "600");
	require_once('./inc/config.php');

// used to check admin pages are being loaded from here....
	$admin_page = true;

// Waht page to load?
	$act = isset($_GET['act']) ? input($_GET['act']):'home';

// check if admin is loged in, if not show login page...
	require_once('admin/admin_login.php');

// load admin page
	switch( $act ) {

	// Delete image
		case 'remove' && (isset($_GET['d']) && input($_GET['d']) != ''):
			$img_del_code = input($_GET['d']);
		 //removed without error
			if(removeImage($img_del_code))	echo json_encode(array('status'=>1));
		// error when removing
			else echo json_encode(array('status'=>0,'html'=>error_note($Err,1)));
			exit;
			break;

	// Remake image database
		case 'rmid':
			require_once('admin/admin_rmid.php');
			break;

	// Settings page
		case 'set':
			require_once('admin/admin_settings.php');
			break;

	// Image list
		case 'images':
			require_once('admin/admin_imagelist.php');
			break;

	// Edit image title/private
		case 'edit':
			require_once('admin/admin_image_edit.php');
			break;

	// User ban page
		case 'ban':
			require_once('admin/admin_ban.php');
			break;
			
	// database tools
		case 'db':
			require_once('admin/admin_db_tools.php');
			break;
			
	// database tools
		case 'bulk':
			require_once('admin/admin_bulkupload.php');
			break;

	// Admin home
		case 'home':
		default:
			require_once('admin/admin_home.php');
	}
