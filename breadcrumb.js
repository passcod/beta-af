var src_dir = "/images_nav/button_";
var src_ext = ".gif";
var src_act = "active_";
var src_ovr = "over_";

function breadcrumb(button_id, is_active_bool, is_over_bool)
{
	var button_no = button_id.replace(/button-/,"");
	
	var src_fin = src_dir;
	
	if ( is_active_bool ) { src_fin = ( src_fin + src_act ); }
	if ( is_over_bool ) { src_fin = ( src_fin + src_ovr ); }
	
	src_fin = ( src_fin + "0" + button_no + src_ext );
	
	document.getElementById(button_id).src = ( src_fin );	
}