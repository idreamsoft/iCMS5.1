/**
 * @package iCMS
 *
 * jquery.scaling.js v0.1.0
 * jQuery Image Scaling Plugin
 * @author coolmoo<idreamsoft@qq.com>
 * @param int width
 * @param int height
 * @param string loadpic
**/
jQuery.fn.scaling=function(width,height,loadpic){
    loadpic=loadpic||"admin/images/ajax_loader.gif";
	return this.each(function(){
		height = height || 99999
		this.removeAttribute("width");
		this.removeAttribute("height");
		if( this.width/this.height > width/height  && this.width >width ){
			this.height = this.height * (width/this.width)
			this.width = width
			this.parentNode.style.height = this.height * (width/this.width) + 'px'
		}else if( this.width/this.height <= width/height && this.height >height){
			this.width = this.width * (height/this.height)
			this.height = height
			this.parentNode.style.height = height + 'px'
		}
	});
}

jQuery.fn.snap=function(a,w,h,left,top,thumbpath){
	a = a|| "src";
    w = w|| "400";h = h	|| "400";
    left = left|| 10;top = top || 10;
    thumbpath = thumbpath|| "admin/images/ajax_loader.gif";
    return this.each(function(){
    	//$("#preview_div").remove();
        if(!document.getElementById('preview_div')){
            $('body').append("<div id='preview_div' style='position:absolute'></div>");
        }
    	var src	= $(this).attr(a),timeOutID = null,d = $("#preview_div");
        d.html("<img id='preview_img' src='"+thumbpath+"'>").show();
    	if(thumbpath=="admin/images/ajax_loader.gif"){
    		d.hide();
    	}
    	var hidePreview = function(){d.hide();};
		$(this).mouseover(function(){
			window.clearTimeout(timeOutID);
			if(!in_array(src.substr(src.lastIndexOf(".")+1), ['gif', 'jpeg', 'jpg', 'png', 'bmp'])|| src==thumbpath){
				return;
			}
			var offset 	= $(this).offset();
			var snapTop = offset.top+top;
			var snapLeft= offset.left+left;
			d.css({"top" : snapTop, "left" : snapLeft}).show();
			$("#preview_img").attr("src",src).scaling(w,h).mouseover(function(){
				window.clearTimeout(timeOutID);
				d.show();
			}).mouseout(function(){
				timeOutID = window.setTimeout(hidePreview,2000);
			});
		}).mouseout(function(){
			timeOutID = window.setTimeout(hidePreview,3000);
		});
    });
};