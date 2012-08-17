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
 *   Used For:     Admin page footer
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
	
?>
<!-- admin page footer -->
	</div></div></div>
	<div id="footer">
		<span class="copyright"><?php echo $lang["admin_footer_powered_by"];?> <a href="http://codefuture.co.uk/projects/imagehost/" title="Free PHP Image Hosting Script">CF Image Hosting script</a></span>
		<span class="version"><b><?php echo $lang["admin_footer_version"];?>:</b> <?php echo $settings['SET_VERSION'];?></span>
	</div>
	<script src="http://cdn.jquerytools.org/1.2.5/jquery.tools.min.js"></script>
<?php if(isset($page['tipsy'])){ ?>
	<script type="text/javascript" src="js/tipsy.js"></script>
<?php } ?>
<?php if(isset($page['fancybox'])){ ?>
	<script type="text/javascript" src="lightbox/jquery.fancybox-1.3.4.pack.js"></script>
<?php } ?>
	<script src="js/admin.js"></script>
</body>
</html>
