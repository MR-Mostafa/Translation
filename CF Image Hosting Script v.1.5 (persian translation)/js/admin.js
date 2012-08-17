
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
 *   Used For:     Admin JS Code
 *   Last edited:  07/03/2012
 *
 *************************************************************************************************************/

function doconfirm(message) {
	if (confirm(message)){
		return true;
	}else{
		return false;
	}
}
// lightbox
$(document).ready(function(){
	$("a#fancybox").fancybox({
		'titlePosition'	: 'inside'
	});
});

// remove image
$(function() {
	$(".delete").click(function() {
		var msg = $(this).attr('ret');
		var test=$(this).closest("tr");
		var id = $(this).attr("id");
		var string = 'd='+ id +'&act=remove';
		if(doconfirm(msg)){
			$('#load').fadeIn();
			$.ajax({
				type: "GET",
				dataType: "json",
				url: "admin.php",
				data: string,
				cache: false,
				success: function(data){
					if(data.status)test.fadeTo(400, 0, function () {test.remove();});
					else alert(data.html);
				}
			});
			return false;
		}
	});
});


//backup
$(function() {
	$(".backup").click(function() {
		var msg = $(this).attr('ret');
		var id = $(this).attr("id");
		if(doconfirm(msg)){
			var dataString = 'act=backup&id='+id;
			$.ajax({
				type: "POST",
				url: "cfajax.php",
				data: dataString,
				dataType: 'json',
				cache: false,
				success: function(result){
					$("#msg").html(result['suc']).slideDown(400).fadeTo(400, 100);
				},
				error: function(errorThrown){
					$("#msg").html(errorThrown['error']);
				}
			});
			return false;
		}
	});
});
//unzip backup
$(function() {
	$(".unzip").click(function() {
		var msg = $(this).attr('ret');
		var fname = $(this).attr("alt");
		if(doconfirm(msg)){
			var dataString = 'act=unzip&name='+fname;
			$.ajax({
				type: "POST",
				url: "cfajax.php",
				data: dataString,
				dataType: 'json',
				cache: false,
				success: function(result){
					$("#msg").html(result['suc']).slideDown(400).fadeTo(400, 100);
				},
				error: function(errorThrown){
					$("#msg").html(errorThrown['error']);
				}
			});
			return false;
		}
	});
});
//remove backup
$(function() {
	$(".remove").click(function() {
		var msg = $(this).attr('ret');
		var test=$(this).closest("tr");
		var fname = $(this).attr("alt");
		if(doconfirm(msg)){
			var dataString = 'act=remove&name='+fname;
			$.ajax({
				type: "POST",
				url: "cfajax.php",
				data: dataString,
				dataType: 'json',
				cache: false,
				success: function(result){
					$("#msg").html(result['suc']).slideDown(400).fadeTo(400, 100);
					test.remove();
				},
				error: function(errorThrown){
					$("#msg").html(errorThrown['error']);
				}
			});
			return false;
		}
	});
});


$(document).ready(function (){
// settings panel
	var lastOpen = '';
	$(".flip").click(function(){
		if(lastOpen != $(this)){
			$(lastOpen).next('.panel').animate({"height": "toggle", "opacity": "toggle"}, "slow");
			$(this).next('.panel').animate({"height": "toggle", "opacity": "toggle"}, "slow");
			lastOpen = $(this);
		}
	});

});

// Close button:
	$(".close").live('click',function(){
		$(this).parent().fadeTo(400, 0, function () { // Links with the class "close" will close parent
			$(this).slideUp(400,function() {$(this).remove();});
		});
		$("#msg").slideUp().fadeTo(400, 0);// image remove fix
		return false;
	});


// tipsy tooltip
	$(function() {
		$('a.img_tooltip').tipsy({title: 'img_src',fade: true, gravity: 's',html: true});
		$('#content th[title]').tipsy({fade: true, gravity: 's'});
		$('#content a.tip[title]').tipsy({fade: true, gravity: 's'});
	})

	$(".tabs_list").tabs(".panes > div", {effect: 'fade', fadeOutSpeed: 400});
	$(".tabNavigation").tabs("#panes > div.panel");
