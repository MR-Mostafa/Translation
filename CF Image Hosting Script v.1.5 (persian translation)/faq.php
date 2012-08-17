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
 *   Used For:     Frequently Asked Questions Page
 *   Last edited:  07/03/2012
 *
 *************************************************************************************************************/

	require_once('./inc/config.php');

	if(!$settings['SET_HIDE_FAQ']){
		header('Location: index.php');
		exit();
	}

	$menu = 'faq_page';
	$page_title = ' - '.$lang["site_faq_title"];
	include './header.php';
?>
		<div class="contentBox">
			<div id="faq">
				<h2><?php echo $lang["site_faq_title"];?></h2>
					<div class="faq_box">
						<a href="#" name="faq-1" class="title"><?php echo sprintf($lang["site_faq_q1"],$settings['SET_TITLE']);?></a><br />
						<div class="answer" id="faq-1"><?php echo $lang["site_faq_a1"];?></div>
					</div>

					<div class="faq_box">
						<a href="#" name="faq-2" class="title"><?php echo $lang["site_faq_q2"];?></a><br />
						<div class="answer" id="faq-2"><?php echo sprintf($lang["site_faq_a2"],$settings['SET_TITLE']);?></div>
					</div>

					<div class="faq_box">
						<a href="#" name="faq-3" class="title"><?php echo $lang["site_faq_q3"];?></a><br />
						<div class="answer" id="faq-3"><?php echo strtoupper(implode(', ',$imgFormats));?> <?php echo $lang["site_faq_a3"];?></div>
					</div>

					<div class="faq_box">
						<a href="#" name="faq-4" class="title"><?php echo $lang["site_faq_q4"];?></a><br />
						<div class="answer" id="faq-4"><?php echo $lang["site_faq_a4"];?></div>
					</div>

					<div class="faq_box">
						<a href="#" name="faq-5" class="title"><?php echo $lang["site_faq_q5"];?></a><br />
						<div class="answer" id="faq-5"><?php echo $lang["site_faq_a5"];?></div>
					</div>

					<div class="faq_box">
						<a href="#" name="faq-6" class="title"><?php echo $lang["site_faq_q6"];?></a><br />
						<div class="answer" id="faq-6"><?php echo $lang["site_faq_a6"];?> <?php echo (format_size(1048576*$settings['SET_MAX_BANDWIDTH'])).' '.$lang["site_index_max_bandwidth_per"].($settings['SET_AUTO_DELETED_JUMP'] == 'm' ? $lang["site_index_max_bandwidth_per_month"]:$lang["site_index_max_bandwidth_per_week"]);?>.</div>
					</div>

					<div class="faq_box">
						<a href="#" name="faq-7" class="title"><?php echo $lang["site_faq_q7"];?></a><br />
						<div class="answer" id="faq-7"><?php echo sprintf($lang["site_faq_a7_1"],$lang["site_menu_tos"]);?><?php if($settings['SET_AUTO_DELETED']){?><?php echo sprintf($lang["site_faq_a7_2"],$settings['SET_AUTO_DELETED_TIME']);?><?php } ?></div>
					</div>

					<div class="faq_box">
						<a href="#" name="faq-8" class="title"><?php echo $lang["site_faq_q8"];?></a><br />
						<div class="answer" id="faq-8"><?php echo $lang["site_faq_a8"];?> <?php echo format_size($settings['SET_MAXSIZE']);?>.</div>
					</div>

					<div class="faq_box">
						<a href="#" name="faq-9" class="title"><?php echo $lang["site_faq_q9"];?></a><br />
						<div class="answer" id="faq-9"><?php echo $lang["site_faq_a9"];?></div>
					</div>

					<div class="faq_box">
						<a href="#" name="faq-10" class="title"><?php echo $lang["site_faq_q10"];?></a><br />
						<div class="answer" id="faq-10"><?php echo $lang["site_faq_a10"];?></div>
					</div>
			</div>
		</div>
<?
	include './footer.php';