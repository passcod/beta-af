/*
<script type='text/javascript' src='gallery/jw/swfobject.js'></script>
<div id='mediaspace' style="margin-right: 35px">_</div>
<script type='text/javascript'>
	var so = new SWFObject('gallery/jw/player.swf','mpl','540','405','9');
	so.addParam('allowfullscreen','true');
	so.addParam('allowscriptaccess','always');
	//so.addParam('wmode','opaque');
	so.addVariable('file','http://beta.adventureforest.co.nz/gallery/jw/video.flv');
	//so.addVariable('file','http://www.youtube.com/watch?v=aBJQ5085kSo');
	so.addVariable('image','http://beta.adventureforest.co.nz/gallery/jw/preview.jpg');
	so.addVariable('backcolor','#281D19');
	so.addVariable('frontcolor','#F0AA31');
	so.addVariable('screencolor','#281D19');
	so.addVariable('autostart','true');
	so.write('mediaspace');
</script>
*/

$(function() {
	$.getScript('/gallery/jw/swfobject.js', function() {
		$('#layout-left').remove();
		var cntr = $('#layout-center');
		cntr.removeClass('withsidebar');
		cntr.html('<div id="mediaspace" style="padding: 50px">EMPTY</div>');
	});
});
