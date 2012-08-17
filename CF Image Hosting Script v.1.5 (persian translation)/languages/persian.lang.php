<?php

/**************************************************************************************************************
 *
 *   CF Image Hosting Script
 *   ---------------------------------
 *
 *   Author:    codefuture.co.uk
 *   Version:   1.5
 *   Date:      07 March 2012
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
 *************************************************************************************************************
 *   
 *   File Name : persian.lang.php
 *   Description: CF Image Hosting Script v1.5 (except admin panel) is Compatibled with persian language. if you found any problem, Contact Us. also i thank the administrator of persianscript.ir (M.Majidi) for translating the Previous version (v1.4.1) of this script.
 *   Used For: persian translation
 *   Last edited:  08/17/2012
 *   Author: Mostafa Rahmati
 *   Author website: http://iranwebdesign.ir
 *   Author email: info@iranwebdesign.ir
 *	 
 *************************************************************************************************************/

	$lang = array();
	$lang["site_charset"] = 'UTF-8';

/*****************************************************************************
 *
 * Admin Language
 *
 *****************************************************************************/

/*
 * Admin Sitewide
 */
	$lang["admin_footer_powered_by"]	= 'Powered By';
	$lang["admin_footer_version"]		= 'Version';

/*
 * config
 */
	$lang["admin_session_error"]		= 'Cannot start a new PHP session. Please contact server administrator or webmaster!';
	$lang["error_500"]					= '500 Internal Server Error - Internal server error with the page you\'ve requested.';
	$lang["error_404"]					= 'متاسفیم، صفحه یا فایل درخواستی شما یافت نشد.';
	$lang["error_403"]					= '403 Forbidden - You do not have permission to access this area.';
	$lang["error_401"]					= '401 Unauthorized - You do not have permission to access this area..';
	$lang["error_400"]					= '400 Bad Request - Your browser sent a request that this server could not understand..';


/*
 * Login Page (log)
 */
 	$lang["admin_log_title"]		= 'Login';
 	$lang["admin_log_out_suc"]		= 'You have been successfully logged out.';
	$lang["admin_log_err"]			= 'You entered the wrong password or user name!';
	// forgot your password - send link
	$lang["admin_log_forgot_password_email_subject"]	= 'Password Reset Link';//SET_TITLE - Password Reset Link  (SET_TITLE is auto set)
	//{user_name} replace with the admin username
	//{reset_url} replace with a link to reset the admin password
	$lang["admin_log_forgot_password_email_body"]		= '
	<div style="width:700px; margin:0 auto;">
		Hi, {user_name}<br/>
		Can\'t remember your password, It happens to the best of us. <br/><br/>
		Please click on the link below or copy and paste the URL into your browser: <br/>
		{reset_url}<br/><br/>

		This will reset your password. You can then login and change it to something you\'ll remember. <br/>
		This is an automated response, please do not reply!
	</div>';
	$lang["admin_log_forgot_password_suc"]			= 'You have been sent a Email...';
	$lang["admin_log_forgot_password_email_err"]	= 'e-mail address is not not valid!';
	$lang["admin_log_forgot_password_err"]			= 'Error resetting your password......';
	$lang["admin_log_forgot_password"]				= 'Forgot Password';
	$lang["admin_log_username"]						= 'Username';
	$lang["admin_log_password"]						= 'Password';
	$lang["admin_log_remember_me"]					= 'Remember Me';
	$lang["admin_log_button"]						= 'Login';
	$lang["admin_log_your_email"]					= 'Your Email';
	$lang["admin_log_button_submit"]				= 'Submit';
 	$lang["admin_log_forgot_password_title"]		= 'Reset Password';
	$lang["admin_log_forgot_password_update"]		= 'Saved New Password';


/*
 * Admin Menu
 */
	$lang["admin_menu_visitsite"]	= 'Visit site';
 	$lang["admin_menu_logout"]		= 'Logout';
	$lang["admin_menu_image_list"] 	= 'Images';
	$lang["admin_menu_settings"]	= 'Settings';
	$lang["admin_menu_banned"]		= 'Ban User';
	$lang["admin_menu_bulk"]		= 'Bulk Upload';
	$lang["admin_menu_home"]		= 'Dashboard';
// not in pro
	$lang["admin_menu_database"]	= 'Database';


/*
 * admin Dashboard/home page
 */
	$lang["admin_home_overview"]				= 'Overview';
	$lang["admin_home_total_images"]			= 'Total images';
	$lang["admin_home_private_images"]			= 'Private images';
	$lang["admin_home_filespace_used"]			= 'Total Filespace Used';
	$lang["admin_home_total_bandwidth"]			= 'Total Bandwidth';
 	$lang["admin_home_last_backup"]				= 'Last Backup On';
	$lang["admin_home_top_image"]				= 'Top Image';
	$lang["admin_home_by_bandwidth"]			= 'by Bandwidth';
	$lang["admin_home_id"]						= 'ID';
	$lang["admin_home_name"]					= 'Name';
	$lang["admin_home_bandwidth"]				= 'Bandwidth';
	$lang["admin_home_hotlink_views"]			= 'Hotlink Views';
	$lang["admin_home_by_hotlink_views"]		= 'by HotLink Views';
	// Reported images / some used on image list page to
	$lang["admin_home_reported_images"]			= 'Reported Images';
	$lang["admin_home_tooltip_image_name"]		= 'Image Description/Name';
	$lang["admin_home_image_name"]				= 'Image Description/Name';
	$lang["admin_home_noreported"]				= 'No reported images found';
	$lang["admin_home_report_remove_suc"]		= 'Successfully removed image from reported list.';
	$lang["admin_home_report_remove_err"]		= 'A problem occurred removed image from reported list.';
	$lang["admin_home_report_alt_remove"]		= 'Remove Image from report list';
	$lang["admin_home_report_alt_delete"]		= 'Delete Image';
	$lang["admin_home_report_delete"]			= "Are you sure you want to remove Image ID '%s'? This cannot be undone!"; // %s = image id
	$lang["admin_home_report_alt_ban"]			= 'Ban user from uploading any images';



/*
 * admin Database page (not used in pro)
 */
	$lang["admin_db_title_database_setting"]			= 'Database Tools';
	$lang["admin_db_menu_auto"]							= 'Auto Database';
	$lang["admin_db_menu_image"]						= 'Image Database Backup';
	$lang["admin_db_menu_bandwidth"]					= 'Bandwidth Database backup';
	$lang["admin_db_menu_rebuild"]						= 'Rebuild Image Database';
	// Auto Database Setting
	$lang["admin_db_auto_title"]						= 'Auto Database Settings';
	$lang["admin_db_auto_backup"]						= 'Auto Database Backup';
	$lang["admin_db_auto_every"]						= 'Auto Backup Every';
	$lang["admin_db_auto_error"]						= 'If a database error is found use the last backup database';
	$lang["admin_db_auto_rebuild"]						= 'Run the database Rebuild tool if a backup datebase is use ';
	$lang["admin_db_auto_every_6hours"]					= '6 hours';
	$lang["admin_db_auto_every_12hours"]				= '12 hours';
	$lang["admin_db_auto_every_day"]					= 'Once a day';
	$lang["admin_db_auto_every_week"]					= 'Once a week';
	// Image Database Setting
	$lang["admin_db_database_image_title"]				= 'Image database backups';
	$lang["admin_db_database_image_replace"]			= 'Replace the current image database with this backup file (%1$s)?&#x0a;(please note this cannot be undone) ';// %1$s - file name
	$lang["admin_db_database_image_backup"]		 		= 'Backup the Image Database ';
	// Bandwidth Database Settings
	$lang["admin_db_database_bandwidth_title"]		 	= 'Bandwidth database backups ';
	$lang["admin_db_database_bandwidth_replace"]		= 'Replace the current bandwidth database with this backup file (%1$s)?&#x0a;(please note this cannot be undone) ';// %1$s - file name
	$lang["admin_db_database_bandwidth_backup"]			= 'Backup the bandwidth Database ';
	// Image & Bandwidth Database Settings
	$lang["admin_db_database_delete_backup"]			= 'Delete this backup file (%1$s)?&#x0a;(please note this cannot be undone) ';// %1$s - file name
	$lang["admin_db_database_delete_backup_tip"]		= 'Delete this backup file';
	$lang["admin_db_database_download_backup"]			= 'Download this backup ';
	$lang["admin_db_database_image_replace_tip"]		= 'Use this backup file ';
	$lang["admin_db_database_backup_table_date"]		= 'backup date ';
	$lang["admin_db_database_backup_table_date_tip"]	= 'The date the backup was made ';
	$lang["admin_db_database_backup_table_name"]		= 'Bakup file ';
	$lang["admin_db_database_backup_table_name_tip"]	= 'The name of the backup file ';
	// Rebuild Image Database
	$lang["admin_db_rebuild_title"]						= 'Rebuild your image database ';
	$lang["admin_db_rebuild_description"]				= 'This tool will check the image upload folder for any images that are not in the image database, if any are found not to be in the database it will add tham. You may need to run this more then once if you have thousands of images not in the database.';
	$lang["admin_db_rebuild_check"]						= 'Check your database:';



/*
 * Admin Settings Page
 */
 	$lang["admin_set_save_button"]				= 'Save Changes';
	$lang["admin_set_option_on"]				= 'On';
	$lang["admin_set_option_off"]				= 'Off';
	$lang["admin_set_option_yes"]				= 'Yes';
	$lang["admin_set_option_no"]				= 'No';
	// settings menu
 	$lang["admin_set_title_admin_setting"]		= 'Admin';
	$lang["admin_set_title_site_setting"]		= 'General';
	$lang["admin_set_title_gallery_setting"]	= 'Gallery';
	$lang["admin_set_title_hide_page"]			= 'Disabled Page';
	$lang["admin_set_title_auto_deleted"]		= 'Auto Deleted';
	$lang["admin_set_title_upload_setting"]		= 'Upload';
	$lang["admin_set_watermark_title"]			= 'Watermark';
	$lang["admin_set_title_url_shortener"]		= 'URL Shortener';
	$lang["admin_set_title_google_setting"]		= 'Google';
	// admin setting
	$lang["admin_set_old_password"]				= 'Old Password';
	$lang["admin_set_new_password"]				= 'New Password';
	$lang["admin_set_confirm_new_password"]		= 'Confirm Password';
	$lang["admin_set_admin_username"]			= 'Admin Username';
	$lang["admin_set_email_address"]			= 'Email address';
	// site setting
	$lang["admin_set_script_url"]				= 'Script URL';
	$lang["admin_set_site_title"]				= 'Site Title';
	$lang["admin_set_site_slogan"]				= 'Site Slogan';
	$lang["admin_set_footer_copyright"]			= 'Footer Copyright';
	$lang["admin_set_site_theme"]				= 'Site Theme';
	$lang["admin_set_mod_rewrite"]				= 'Mod Rewrite';
	$lang["admin_set_addthis"]					= 'Your AddThis pubid';
	$lang["admin_set_image_widgit"]				= 'Show Random Image Widgit';
	$lang["admin_set_hide_feed"]				= 'Disable Rss Feed';
	$lang["admin_set_hide_sitemap"]				= 'Disable Sitemap';
	$lang["admin_set_language"]					= 'Set Site Language';
	// Gallery Settings
	$lang["admin_set_images_per_gallery_page"]	= 'Images Per Gallery Page';
	$lang["admin_set_report_allow"]				= 'Allow report images';
	$lang["admin_set_report_Send_email"]		= 'Send email on image report';
	// Hide Pages
	$lang["admin_set_hide_gallery"]				= 'Gallery Page';
	$lang["admin_set_hide_contact"]				= 'Contact Page';
	$lang["admin_set_hide_tos"]					= 'Terms of Service Page';
	$lang["admin_set_hide_search"]				= 'Search bar';
	$lang["admin_set_hide_faq"]					= 'FAQ Page';
	// Auto Remove images (auto deleted)
	$lang["admin_set_des_auto_deleted"]			= 'Auto delete is a feature that will help you keep your site clean of old and unused images. This has the most use for sites which are general purpose image hosting site, but for those of you who are using this script to share images and photos with your friends and family this feature is best left off as you will be most likely actively administrating your image host.';
	$lang["admin_set_auto_deleted"]				= 'Auto Deleted(unviewed)';
	$lang["admin_set_auto_deleted_for"]			= 'Auto Deleted(unviewed for)';
	$lang["admin_set_auto_deleted_days"]		= 'Days';
	$lang["admin_set_run_auto_deleted"]			= 'Run Auto Deleted';
	$lang["admin_set_run_auto_deleted_day"]		= 'Day';
	$lang["admin_set_run_auto_deleted_week"]	= 'Week';
	$lang["admin_set_run_auto_deleted_Month"]	= 'Month';
	// Upload Settings
	$lang["admin_set_disable_upload"]			= 'Disable Upload';
	$lang["admin_set_max_upload_file_size"]		= 'Max Upload File Size';
	$lang["admin_set_image_max_bandwidth_des"]	= 'If you do not wish to set a maximum bandwidth limit, enter a 0 (zero) in Image Max Bandwidth.';
	$lang["admin_set_image_max_bandwidth"]		= 'Image Max Bandwidth';
	$lang["admin_set_auto_reset_bandwidth"]		= 'Auto reset Bandwidth';
	$lang["admin_set_multiple_upload_max"]		= 'Multiple Upload Max';
	$lang["admin_set_allow_duplicate"]			= 'Stop duplicate images from being uploaded';
	$lang["admin_set_allow_image_resize"]		= 'Allow users to resize Images on upload';
	$lang["admin_set_private_image_upload"]		= 'Private Image Upload';
	// Watermark Settings
	$lang["admin_set_watermark_des"]			= 'The watermark can be placed on the bottom or the top left corner of the image and is only add to the image when viewed from any other site then yours.';
	$lang["admin_set_watermark_text"]			= 'Watermark Text';
	$lang["admin_set_watermark_image"]			= 'Watermark Image Address';
	$lang["admin_set_watermark_position"]		= 'Watermark Position';
	$lang["admin_set_watermark_center"]			= 'center';
	$lang["admin_set_watermark_left"]			= 'left';
	$lang["admin_set_watermark_right"]			= 'right';
	$lang["admin_set_watermark_top"]			= 'top';
	$lang["admin_set_watermark_bottom"]			= 'bottom';
	// URL Shortener Settings
	$lang["admin_set_url_shortener"]				= 'URL Shortener(b54.in)';
	$lang["admin_set_url_short_service"]			= 'URL Shortener Service';
	$lang["admin_set_url_short_api_url"]			= 'API URL <small>(for Yourl only)</small>';
	$lang["admin_set_url_short_api_username"]		= 'API username <small>(for all other then B54)</small>';
	$lang["admin_set_url_short_api_password"]		= 'API Password/Key <small>(for all other then B54)</small>';
	// Google Settings
	$lang["admin_set_google_setting_des"]			= 'Google analytics and absent will only be added to the site if you enter your code below';
	$lang["admin_set_google_analytics_code"]		= 'Google Analytics code';
	$lang["admin_set_google_channal_code"]			= 'Adsense Custom channels ID';
	$lang["admin_set_google_adsense_code"]			= 'Google AdSense code';
	// Save Errors
	$lang["admin_set_err_password_wrong"]			= 'You entered the wrong password!';
	$lang["admin_set_err_password_both"]			= 'You need to enter both the old and new Passwords!';
	$lang["admin_set_err_username"]					= 'Username is a required field and can\'t be blank';
	$lang["admin_set_err_email_invalid"]			= 'The e-mail address that you provided is not valid.';
	$lang["admin_set_err_script_url"]				= 'Script URL is a required field and can\'t be blank';
	$lang["admin_set_suc_update"]					= 'Updated Settings!';
	$lang["admin_set_err_saveing_settings"]			= 'A problem occurred during saving of settings';


/*
 * Bulk upload page
 */
	$lang["admin_bulk_title"]			= "Bulk Image Upload";
	$lang["admin_bulk_des"]				= "With this tool you can upload images by ftp. Just upload images to \"%s\" folder then press the upload images button below.";// %s = folder name 
	$lang["admin_bulk_form_button"]		= "Uplaod Images";
	$lang["admin_bulk_no_image_err"]	= "No images found in %s to upload";// %s = folder name 

	
/*
 * Ban User Page
 */
	$lang["admin_ban_suc"]						= 'you have successfully banned the IP: %s from uploading any more images';
	$lang["admin_ban_err_save_db"]				= 'There was an error trying to save to the DB!';
	$lang["admin_ban_err_no_ip"]				= 'You need to enter a IP address to ban!';
	$lang["admin_ban_suc_unbanned"]				= 'You have successfully unbanned the IP: %s';
	$lang["admin_ban_alt_unban"]				= 'Remove Ban';
	$lang["admin_ban_form_title"]				= 'Ban a IP from uploading';
	$lang["admin_ban_form_ip"]					= 'IP';
	$lang["admin_ban_form_reason"]				= 'Reason';
	$lang["admin_ban_form_button"]				= 'Ban IP';
	$lang["admin_ban_list_tt_date_banned"]		= 'Date Added to banned list';
	$lang["admin_ban_list_date_banned"]			= 'Date Banned';
	$lang["admin_ban_list_tt_ip"]				= 'Banned IP address';
	$lang["admin_ban_list_ip"]					= 'IP';
	$lang["admin_ban_list_tt_reason"]			= 'The reason for the ban';
	$lang["admin_ban_list_reason"]				= 'Reason';


/*
 * Image List Page (ilp)
 */
	$lang["admin_ilp_thumb_page_link"]				= 'Link To Thumb Page';
	$lang["admin_ilp_edit_alt"]						= 'Edit image public/private & description/name';
	$lang["admin_ilp_report_alt_delete"]			= 'Delete Image';
	$lang["admin_ilp_report_delete"]				= "Are you sure you want to remove Image ID '%s'? This cannot be undone!"; // %s = image id
	$lang["admin_ilp_report_alt_ban"]				= 'Ban user from uploading any images';
	$lang["admin_ilp_number_to_list"]				= 'items in list';
	$lang["admin_ilp_number_to_list_all"]			= 'All';
	$lang["admin_ilp_order_list"]					= 'Order By';
	$lang["admin_ilp_order_list_date_added"]		= 'Date Added';
	$lang["admin_ilp_order_list_last_viewed"]		= 'Last Viewed';
	$lang["admin_ilp_order_list_hotlink_views"]		= 'Hotlink Views';
	$lang["admin_ilp_order_list_bandwidth_used"]	= 'Bandwidth Used';
	$lang["admin_ilp_order_list_gallery_clicked"]	= 'Gallery Clicked';
	$lang["admin_ilp_imglist_tt_image_added"]		= 'Date Image was added';
	$lang["admin_ilp_imglist_image_added"]			= 'Date Added';
	$lang["admin_ilp_imglist_tt_image_name"]		= 'Image Description/Name';
	$lang["admin_ilp_imglist_image_name"]			= 'Image Description/Name';
	$lang["admin_ilp_imglist_tt_last_viewed"]		= 'Number of days of inactivity';
	$lang["admin_ilp_imglist_last_viewed"]			= 'Last<br/>Viewed';
	$lang["admin_ilp_imglist_tt_gallery_clicks"]	= 'the number of times a image in the gallery has been clicked on';
	$lang["admin_ilp_imglist_gallery_clicks"]		= 'Gallery<br/>Clicks';
 	$lang["admin_ilp_imglist_tt_hotlink_views"]		= 'Number of times this image has benn viewed externally, (ie not from this site)';
	$lang["admin_ilp_imglist_hotlink_views"]		= 'Hotlink Views';
	$lang["admin_ilp_imglist_tt_bandwidth_used"]	= 'Hot linking bandwidth used';
	$lang["admin_ilp_imglist_bandwidth_used"]		= 'Bandwidth<br/>Used';
	$lang["admin_ilp_imglist_tt_private"]			= 'Hot linking bandwidth used';
	$lang["admin_ilp_imglist_private"]				= 'Private';


/*
 * Image edit Page (iep)
 */
	$lang["admin_iep_suc"]				= 'You have successfully updated the image.';
	$lang["admin_iep_title"]			= 'Edit Image';
	$lang["admin_iep_des_title"]		= 'Description';
	$lang["admin_iep_pp_title"]			= 'Image Public/Private';
	$lang["admin_iep_private"]			= 'Private';
	$lang["admin_iep_public"]			= 'Public';
	$lang["admin_iep_button"]			= 'Update';
	$lang["admin_iep_page_views"]		= 'Page Views';


/*
 * Site Pagination
 */
	$lang["pagination_next_page_tip"]			= "صفحه بعد";
	$lang["pagination_previous_page_tip"]		= "صفحه قبل";
	$lang["pagination_page_first_tip"]			= "صفحه نخست";
	$lang["pagination_page_last_tip"]			= "صفحه آخر";
	$lang["pagination_page_tip"]				= 'صفحه %1$d'; // %1$d - page number
	$lang["pagination_page_of"]					= 'صفحه %1$s of %2$s';// %1$s - page on / %2$s number of pages
	$lang["pagination_page_first"]				= 'اول';
	$lang["pagination_page_last"]				= 'آخر';


/*****************************************************************************
 *
 * Web Site Language
 *
 *****************************************************************************/


/*
 * Sitewide
 */
	$lang["site_search_text"]	= 'جستجوی تصویر';
	$lang["site_search_button"]	= 'جستجو';
	$lang["site_language"]		= 'زبان سایت :';
	$lang["home_image_widgit"]	= 'تصویر تصادفی';
	$lang["footer_feed_title"]	= 'فید سایت';


/*
 * Site Menu
 */
	$lang["site_menu_home"]		= 'صفحه اصلی';
	$lang["site_menu_gallery"]	= 'گالری';
	$lang["site_menu_faq"]		= 'سوالات متداول';
	$lang["site_menu_tos"]		= 'قوانین سرویس دهی';
	$lang["site_menu_contact"]	= 'تماس با ما';


 /*
 * Site Feed
 */
	$lang["feed_description"]	= '10 تصویر آخر';
	$lang["feed_language"]		= 'fa-ir';
	$lang["feed_image_name"]	= 'نام تصویر';
	$lang["feed_no_images"]		= 'هیچ تصویری آپلود نشده است.';


/*
 * Delete Images msg
 */
 	$lang["site_index_delete_image_suc"]				= 'تصویر شما با موفقیت حذف شد.';
	$lang["site_index_delete_image_err_db"]				= 'متاسفیم، خطایی در هنگام حذف تصویر به وجود آمده است. لطفا مجدد سعی نمایید.';
	$lang["site_index_delete_image_err_not_found"]		= 'متاسفیم، تصویری با این نام یافت نشد.';


/*
 * Upload images msg
 */
 	$lang["upload_duplicate_found"]						= 'یک یا چند تصویری که آپلود کرده اید، تکراری است. لطفا تصاویر تکراری را از لیست حذف کنید و مجدد جهت آپلود اقدام نمایید.';
	$lang["site_upload_err"]							= 'متاسفیم، یک خطا هنگام آپلود تصویر به وجود آمده است. لطفا مجدد سعی نمایید.';
	$lang["site_upload_err_no_image"]					= 'با عرض پوزش، ما هیچ تصویری جهت آپلود تصویر پیدا نکردیم.';
	$lang["site_upload_banned"]							= 'متاسفیم، به علت عدم رعایت قوانین دسترسی شما به ساید مسدود شده است.';
	$lang["site_upload_to_small"]						= 'متاسفیم! اندازه تصویر بیش از حد کوچک می باشد. حداقل اندازه مجاز %s می باشد.';
	$lang["site_upload_to_big"]							= 'با عرض پوزش! حجم تصویر بسیار زیاد می باشد. حداکثر حجم مجاز برای آپلود %s می باشد.';
	$lang["site_upload_size_accepted"]					= 'شما فقط مجاز به آپلود تصاویر %s می باشید.';
	$lang["site_upload_types_accepted"]					= 'شما فقط پسوند های %s را می توانید آپلود کنید.';


/*
 * Index/Home Page
 */
 	$lang["site_index_des"]								= 'تصاویر خود را به صورت رایگان آپلود کنید و برای دوستان، خانواده و یا در شبکه های اجتماعی و سایت های دیگر به به طور رایگان به اشتراک بگذارید.';
	$lang["site_index_Image_Formats"]					= 'پسوند های قابل پشتیبانی';
	$lang["site_index_maximum_filesize"]				= 'حداکثر حجم مجاز';
	$lang["site_index_uploading_image"]					= 'در حال آپلود تصویر ...';
	$lang["site_index_upload_image"]					= 'آپلود تصویر جدید';
	$lang["site_index_upload_browse_button"]			= 'انتخاب';
	$lang["site_index_upload_description"]				= 'توضیحات: (دلخواه)';
	$lang["site_index_upload_button"]					= 'آپلود';
	$lang["site_index_upload_disable"]					= 'آپلود تصویر غیرفعال شده است.';
	$lang["site_index_local_image_upload"]				= 'کامپیوتر شخصی';
	$lang["site_index_local_image_upload_title"]		= 'آپلود عکس از کامپیوتر شخصی';
	$lang["site_index_Remote_image_copy"]				= 'آپلود از لینک';
	$lang["site_index_Remote_image_copy_title"]			= 'آپلود تصویر از سایت های دیگر';
	$lang["site_index_Remote_image"]					= 'لطفا لینک تصویری را که می خواهید به این سایت منتقل کنید را وارد نمایید.';
	$lang["site_index_auto_deleted"]					= '<b>فایل های غیر فعال</b> : تصاویر به صورت اتوماتیک بعد از  %s روز حذف می شوند.';
	$lang["site_index_max_bandwidth"]					= 'محدودیت پهنای باند';
	$lang["site_index_max_bandwidth_per"]				= 'به ازای هر تصویر در ';
	$lang["site_index_max_bandwidth_per_week"] 			= 'هفته';
	$lang["site_index_max_bandwidth_per_month"]			= 'ماه';
	$lang["site_index_max_upload"]						= 'آپلود همزمان چند تصویر';
	$lang["site_index_max_upload_max"]					= 'حداکثر';
	$lang["site_index_tos_des"]							= 'تمامی تصاویری که خلاف قوانین این سایت باشند، حذف می شوند. لطفا  %s سایت را مشاهده نمایید.';//%s = Terms of Service link 

 	$lang["site_index_upload_preferences"]				= 'تنظیمات';
	$lang["site_index_resize_title"]					= 'تغییر اندازه تصویر شما در هنگام آپلود';
	$lang["site_index_resize_height"]					= 'ارتفاع';
	$lang["site_index_resize_width"]					= 'عرض';
	$lang["site_index_resize_des"]						= 'در صورتی که شما تنها ارتفاع یا عرض را وارد نمایید، سیستم به صورت خودکار به نسبت ارتفاع یا عرض وارد شده کل تصویر را به طور خودکار تغییر اندازه می دهد. همچنین اگر هم ارتفاع و هم عرض را وارد نمایید، تصویر به همان اندازه وارد شده تغییر خواهد کرد.';
	$lang["site_index_private_img"]						= 'آپلود عکس به صورت خصوصی';
	$lang["site_index_short_url"]						= 'ایجاد لینک کوتاه در';


/*
 * Thumbnail Page
 */
	$lang["site_index_hide_link"]						= 'لینک تصویر';
	$lang["site_index_social_networks"]					= 'شبکه های اجتماعی';
	$lang["site_index_short_url_link"]					= 'لینک کوتاه';
	$lang["site_index_bbcode"]							= 'تالار گفتگو';
	$lang["site_index_html_code"]						= 'کد HTML';
	$lang["site_index_direct_link"]						= 'لینک مستقیم';
	$lang["site_index_small_thumbnail_link"]			= 'لینک  بند انگشتی کوچک';
	$lang["site_index_thumbnail_link"]					= 'لینک بند انگشتی';
	$lang["site_index_image_link"]						= 'لینک تصویر';
	$lang["site_index_thumbs_page_err"]					= 'متاسفیم، تصویری با این نام یافت نشد.';
	$lang["site_index_delete_url"]						= 'لینک حذف تصویر';
	$lang["site_index_delete_url_des"]					= 'شما با استفاده از لینک زیر می توانید تصویر خود را در هر زمانی که بخواهید حذف کنید.';


/*
 * Gallery Page
 */
	$lang["site_gallery_report_suc"]					= 'گزارش شما با موفقیت ثبت شد، به زودی این تصویر مورد بررسی قرار خواهد گرفت. از همکاری شما سپاسگذاریم.';
	$lang["site_gallery_report_err_reporting"]			= 'متاسفیم! در هنگام ارسال گزارش تصیویر خطایی به وجود آمده است. لطفا مجدد سعی نمایید.';
	$lang["site_gallery_report_err_find"]				= 'متاسفیم، تصویر مورد نظر شما برای گزارش وجود ندارد. ممکن است این تصویر توسط مدیران هم اکنون حذف شده باشد.';
	$lang["site_gallery_report"]						= 'گزارش';
	$lang["site_gallery_report_title"]					= 'گزارش این تصویر';
	$lang["site_gallery_report_this"]					= 'آیا شما مطمئنید که می خواهید این تصویر را گزارش کنید؟';
	$lang["site_gallery_page_title"]					= 'صفحه گالری تصویر';
	$lang["site_gallery_err_no_image"]					= 'متاسفیم، تصویری در پایگاه داده ما ثبت نشده است.';


/*
 * search Page
 */
	$lang["site_search_err_short"]						= 'متن وارد شده بسیار کوتاه می باشد، لطفا بیشتر از 3 کاراکتر وارد نمایید.';
	$lang["site_search_err_blank"]						= 'شما چیزی برای جستجو وارد نکردید!';
	$lang["site_search_page_title"]						= 'جستجوی تصویر برای';
	$lang["site_search_results"]						= 'نتیجه جستجو برای %s';
	$lang["site_search_no_results"]						= 'متاسفیم، هیچ نتیجه ای یافت نشد برای';
	$lang["site_search_suggestions"]					= 'پیشنهادات:<br />- اطمینان حاصل نمایید تا کلمه ای که برای جستجو انتخاب کرده اید به درستی در فیلد جستجو وارد شده باشد.<br />- جهت جستجو دقیقتر از کلمات کوتاه تر استفاده نمایید.';


/*
 * Contact Us Page
 */
	$lang["site_contact_thank_you"]						= 'متشکریم %s.<br/>ما اطلاعات تماس شما را دریافت کردیم و در صورت نیاز به پیغام یا پیشنهاد شما پاسخ داده خواهد شد.';
	$lang["site_contact_des"]							= 'جهت ارتباط با ما می توانید از فرم زیر استفاده نمایید.<br/>لطفا آدرس ایمیل واقعی خود ارسال نمایید تا در صورت نیاز بتوانیم پاسخ شما را بدهیم. لازم به ذکر است که آدرس ایمیل شما در نزد ما محفوظ می باشد و به توسط ما منتشر نخواهد شد.';
	$lang["site_contact_form_name"]						= 'نام';
	$lang["site_contact_form_email"]					= 'ایمیل';
	$lang["site_contact_form_comment"]					= 'پیغام شما';
	$lang["site_contact_form_captcha"]					= 'کد امنیتی';
	$lang["site_contact_form_captcha_img"]				= 'با کلیک بر روی تصویر، کد امنیتی را به روز کنید.';
	$lang["site_contact_form_captcha_image_title"]		= 'به روز رسانی کد امنیتی';
	$lang["site_contact_form_send"]						= 'ارسال';
	$lang["site_contact_err_name_blank"]				= 'لطفا نام خود را وارد نمایید. این فیلد نمی تواند خالی باشد.';
	$lang["site_contact_err_email_blank"]				= 'لطفا ایمیل خود را وارد نمایید. این فیلد نمی تواند خالی باشد.';
	$lang["site_contact_err_email_invalid"]				= 'ایمیلی که وارد کرده اید، معتبر نمی باشد. لطفا از یک ایمیل معتبر و حقیقی استفاده نمایید.';
	$lang["site_contact_err_comment_blank"]				= 'لطفا پیغام خود را وارد نمایید. این فیلد نمی تواند خالی باشد.';
	$lang["site_contact_err_captcha_blank"]				= 'کد امنیتی را وارد نکرده اید.';
	$lang["site_contact_err_captcha_invalid"]			= 'کد امینتی را اشتباه وارد کرده اید.';
	$lang["site_contact_err_captcha_cookie"]			= 'کوکی مرورگر شما غیر فعال می باشد. لطفا آن را فعال کنید.';


/*
 * Terms of Service Page
 */
	$lang["site_tos_title"]								= 'قوانین سرویس دهی';
	$lang["site_tos_line1"]								= 'قبل از استفاده از خدمات این سایت، شما باید مروری بر این توافقنامه داشته باشید و با مفاد مذکور در آن موافقت نمایید. اگر چنانچه مخالفتی با توافقنامه زیر داشتید، لطفا از این استفاده نکنید. همچنین این سایت اینحق را دارد که به صلاحدید خود توافقنامه میان سایت و کاربران را تغییر دهد، این تغییر ممکن است بدون اطلاع قبلی به کاربران صورت پذیرد، هر تغییری در مقررات و قوانین این توافقنامه بلافاصله از نظر مسئولان سایت لازم الاجرا ست و عذر کاربران در صورت عدم آگاهی آنها از این تغییرات، به هیچ وجه پذیرفته نیست.';
	$lang["site_tos_line2"]								= 'این وبسایت یک سایت عمومی می باشد، از این رو کاربران نباید اطلاعاتی را که دارای حساسیت زیادی می باشند نظیر تصاویر خانوادگی و محرمانه برروی سایت قرار دهند، درغیراینصورت این وبسایت مسئولیتی درخصوص سوء استفاده، انتشار و یا حذف ناگهانی این اطلاعات را قبول نمی کند. ';
	$lang["site_tos_line3"]								= 'شما مجاز به اپلود فایل های زیر نیستید:';
	$lang["site_tos_line4"]								= 'تصاویری که به هر نحوی مشمول قوانین کپی رایت می شود.';
	$lang["site_tos_line5"]								= 'تصاویری که دارای محتوای غیر مجاز و غیر انسانی نظیر تصاویری مرتبط با مسائل جنسی و پورنوگرافی باشد.';
	$lang["site_tos_line6"]								= 'تهیه و ترویج اطلاعات سازماندهی شده درباره فعالیت های غیر قانونی، از نظیر ترویج آسیب های فیزیکی و صدمه زدن به گروهها و افراد (مثلا ترویج و آموزش ساخت بمب و یا سایر اسلحه ها و وسایل آتش زا)';
	$lang["site_tos_line7"]								= 'تصاویری که به هر نحوی به حریم خصوصی افراد و اشخاص دیگر تجاوز کند.';
	$lang["site_tos_line8"]								= 'تصاویر غیر مجازی که به هر نحو قوانین دولت جمهوری اسلامی ایران را نقض می کند.';
	$lang["site_tos_line9"]								= 'در صورت عدم رعایت قوانین این وبسایت، تصاویر آپلود شده توسط شما برای همیشه حذف و دسترسی شما به وبسایت مسدود خواهد شد.';
	$lang["site_tos_line10"]							= 'طبق این توافقنامه شما توافق می کنید که این سایت این حق را دارد که به صلاحدید خودش اقدام به حذف اطلاعات شما به هر دلیلی نماید. این دلایل می تواند شامل این موارد باشد: چنانچه شما از این سرویس سو استفاده داشته باشید، یا به این توافقنامه پایبند نبوده و در صورت اعتراض، تجاوز به حقوق افراد و مواردی از این قبیل و ... .';
	$lang["site_tos_line11"]							= 'این سایت می تواند این قرارداد را در هر زمان که بخواهد، تغییر دهد و این حق برای سرویس دهنده محفوظ می باشد. ما به شما توصیه می کنیم که به صورت دوره ای برای اطلاع از این تغییرات، به سایت مراجعه نمایید. پس از هر تغییر فرض ما بر این است که شما، آن تغییر را پذیرفته اید و در صورت عدم آگاهی از این تغییرات، هیچ عذری پذیرفته نمی باشد.';
	$lang["site_privacy_policy_title"]					= 'سیاست حفظ حریم خصوصی';
	$lang["site_privacy_policy_line1"]					= '- بر طبق این توافقنامه این سایت متعهد می شود به کلیه حقوق اختصاصی کاربران احترام بگذارد و در این توافقنامه از دیگر کاربران نیز می خواهد که آنها نیز همین کار را انجام دهند. تمام تلاش ما بر اینست که رضایت شما کاربران را فراهم آوریم، چنانچه اعتقادتان بر این می باشد که حقوق انحصاری شما در این سایت مورد تجاوز قرار گرفته است، حتما ما را در جریان بگذارید.<br />- ما بعنوان مسئولین این سایت، تلاش می کنیم که اطلاعات شخصی ای را که شما در اختیار ما می گذارید حفظ نماییم و این منطبق با سیاست حفظ اطلاعات خصوصی شما از جانب ماست. تحت هر شرایطی اطلاعاتی که شما برای ما فراهم کرده اید، محرمانه فرض می شود و به هیچ عنوان توسط ما منتشر نخواهد شد.
';


/*
 * Frequently Asked Questions Page
 */
	$lang["site_faq_title"]								= 'سوالات متداول کاربران';
	$lang["site_faq_q1"]								= '%s چیست؟ چطور می توانم از این سایت استفاده کنم؟';// %s site title
	$lang["site_faq_a1"]								= 'این سایت یک مرکز به اشتراک گذاری تصاویر می باشد. شما می توانید تصاویر خود را در این سایت بارگزاری نمایید و به دوستان، خانواده و یا اقوام خود نمایش دهید و یا می توانید این تصاویر را در شبکه های اجتماعی به اشتراک بگذارید.';
	$lang["site_faq_q2"]								= 'آیا برای استفاده از این سایت باید پولی بپردازم؟';
	$lang["site_faq_a2"]								= 'خیر! این وبسایت کاملا رایگان می باشد و هیچ هزینه ای جهت ارائه خدمات توسط ما دریافت نمی گردد.';// %s site title
	$lang["site_faq_q3"]								= 'من چه پسوند هایی را می توانم در این سایت آپلود کنم؟';
	$lang["site_faq_a3"]								= 'شما می توانید تصاویر و عکس های مورد علاقه خود از طریق این وبسایت به اشتراگ بگذارید.'; // file types are listed before this
	$lang["site_faq_q4"]								= 'من چه تصاویری را می توانم آپلود کنم؟';
	$lang["site_faq_a4"]								= 'شما مجاز به اشتراک گذاری هر نوع تصویری که قوانین ما را نقض نکند، می توانید آپلود کنید.';
	$lang["site_faq_q5"]								= 'چرا تصاویر من با پسوند BMP یا PSD بعد از آپلود تبدیل به یک پسوند PNG می شود؟';
	$lang["site_faq_a5"]								= 'چون این پسوند ها قابل نمایش در صفحات وب نمی باشند، به همین دلیل پسوند این تصاویر به طور خودکار توسط ما تغییر می کند تا شما بتونید آنها را مشاهده نمایید.';
	$lang["site_faq_q6"]								= 'تصاویری که من آپلود می کنم، آیا دارای لینک مستقیم می باشد؟';
	$lang["site_faq_a6"]								= 'بله. لینک تصاویر به چندین صورت لینک (بند انگشتی، تالارهای گفتگو ,تصویر بند انگشتی کوچک و لینک مستقیم) بارگزاری می شوند و کاربر می تواند به دلخواه یکی را انتخاب کند. پهنای باند مجاز برای هر تصویر';// bandwidth limit (set in settings) is added to the end
	$lang["site_faq_q7"]								= 'تصاویر من تا چه زمانی روی سرور های شما هستند؟';
	$lang["site_faq_a7_1"]								= 'در صورتی که تصاویر شما خلاف قوانین نباشند برای همیشه بر روی سرور های ما ذخیره خواهند. جهت مشاهده قوانین به این صفحه مراجعه نمایید. <a href="tos.php" title="%1$s">%1$s</a>';//%1$s tos- title
	$lang["site_faq_a7_2"]								= 'با این حال، اگر تصاویر شما بیش از %1$d روز آپلود شده باشد، امکان حذف آنها به صورت اتوماتیک می باشد.';//%1$d number of days
	$lang["site_faq_q8"]								= 'حداکثر حجم مجاز برای آپلود چه مقدار می باشد؟';
	$lang["site_faq_a8"]								= 'حداکثر اندازه مجاز جهت آپلود هر عکس ';// file size added to end (set in settings)
	$lang["site_faq_q9"]								= 'من چگونه می توانم به شما کمک کنم؟';
	$lang["site_faq_a9"]								= 'شما می توانید سایت ما را به دوستانتان معرفی کنید یا در سایت و وبلاگ خود، جهت حمایت از ما آدرس وبسایت ما را قرار دهید.';
	$lang["site_faq_q10"]								= 'سوالات دیگری دارم، چگونه آن را بپرسم؟';
	$lang["site_faq_a10"]								= 'جهت پرسیدن سوالات دیگر خود از صفحه <a href="contact.php" title="Contact page">تماس با ما</a> اقدام نمایید.';

?>