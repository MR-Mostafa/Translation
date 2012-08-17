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
 *   Used For:     Web site Footer
 *   Last edited:  07/03/2012
 *
 *************************************************************************************************************/

// theme design by footer link
	if(!isset($theme['designby'])) $theme['designby'] = 'codefuture.co.uk';
	if(!isset($theme['url'])) $theme['url'] = 'http://codefuture.co.uk';
	if(!isset($theme['linktitle'])) $theme['linktitle']	= 'codefuture.co.uk - online webmaster tools,code Generators';

?>
		<div class="clear"></div>
<?php
 if($settings['SET_GOOGLE_ADS']){?>
	<div class="footer_ad"><?php echo $footer_AdSense;?></div>
<?php } ?>
</div>
	<div id="footer">
		<p><?php echo $settings['SET_COPYRIGHT'];?></p>
		<?php if($settings['SET_HIDE_FEED']){?>
			<div id="feed"><a href="<?php echo $settings['SET_SITEURL'];?>/feed.php" title="<?php echo $lang["footer_feed_title"];?>"><span><?php echo $lang["footer_feed_title"];?></span></a></div>
		<?php } ?>
		<div class="sp"></div>
		<p>Powered By <a href="http://codefuture.co.uk/projects/imagehost/" title="Free PHP Image Hosting Script">CF Image Hosting script</a> | Design By <a href="<?php echo $theme['url'];?>" title="<?php echo $theme['linktitle'];?>"><?php echo $theme['designby'];?></a></p>
	</div>
</div>

<?php if($settings['SET_GOOGLE_ANALYTICS']){?>
<script type="text/javascript">
	var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
	document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
	try {
		var pageTracker = _gat._getTracker("<?php echo $settings['SET_GOOGLE_ANALYTICS'];?>");
		pageTracker._trackPageview();
	} 
	catch(err) {}
</script>
<?php } ?>
<!-- Grab Google CDN's jQuery, with a protocol relative URL; fall back to local if necessary -->
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
	<script>window.jQuery || document.write("<script src='js/jquery-1.7.1.min.js'>\x3C/script>")</script>
	<script type="text/javascript" src="<?PHP echo $settings['SET_SITEURL'];?>/js/tipsy.js"></script>
	<script type="text/javascript" src="<?PHP echo $settings['SET_SITEURL'];?>/js/user.js"></script>
<!-- footer hook -->
	<?PHP echo (isset($footer_hook)?$footer_hook:'');?>
	<!--[if lt IE 7 ]>
		<script src="js/dd_belatedpng.js"></script>
		<script>DD_belatedPNG.fix("img, .png_bg"); // Fix any <img> or .png_bg bg-images. Also, please read goo.gl/mZiyb </script>
	<![endif]-->
</body>
</html>