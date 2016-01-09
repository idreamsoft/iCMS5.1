var userAgent = navigator.userAgent.toLowerCase();
var is_opera = userAgent.indexOf('opera') != -1 && opera.version();
var is_moz = (navigator.product == 'Gecko') && userAgent.substr(userAgent.indexOf('firefox') + 8, 3);
var is_ie = (userAgent.indexOf('msie') != -1 && !is_opera) && userAgent.substr(userAgent.indexOf('msie') + 5, 3);
var iCMS=iCMS||{};
iCMS.publicURL="public";
iCMS.enable_editor	= true;iCMS.editor_id	='icms_editor_1';
iCMS.D =function(html,title){
    title=title||'iCMS - 提示信息';
    var d = $('#iCMS_DIALOG');
    d.attr("title",title).html(html).dialog('open');
    return d;
}
iCMS.msg = function(text,title,callback){
    this.D(text,title).dialog({
	    height: '400',
	    width: '500',
        modal: false
    });
}
iCMS.ok = function(text,title,callback){
    this.D(text,title).dialog({
        modal: true,
        buttons: {
            "确定": function() {
                if(callback.fn && typeof callback.fn =='function'){
                	callback.fn();
                }else{
                    $(this).dialog('close');
                }
            }
        }
    });
}
iCMS.yORn = function(text,title,callback){
    this.D(text,title).dialog({
        modal: true,
        buttons: {
            '确定': function() {
                if(callback.yf && typeof callback.yf =='function'){
                    callback.yf();
                }else{
                    $(this).dialog('close');
                }
            },
            '取消': function() {
                if(callback.nf && typeof callback.nf =='function'){
                    callback.nf();
                }else{
                    $(this).dialog('close');
                }
            }
        }
    });
}
iCMS.CDB = function(text,title,callback){
   this.D(text,title).dialog({
	   height: callback.height ? callback.height:'300',
	   width: callback.width ? callback.width:'350',
       modal:true,
       buttons:callback.buttons
    });
}
iCMS.closeDialog=function(){
    $('#iCMS_DIALOG').dialog('close');
}
iCMS.checkAll=function(type, form, value, checkall, changestyle) {
	var checkall = checkall ? checkall : 'chkall';
	for(var i = 0; i < form.elements.length; i++) {
		var e = form.elements[i];
		if(type == 'option' && e.type == 'radio' && e.value == value && e.disabled != true) {
			e.checked = true;
		} else if(type == 'value' && e.type == 'checkbox' && e.value == value) {
			e.checked = form.elements[checkall].checked;
		} else if(type == 'prefix' && e.name && e.name != checkall && (!value || (value && e.name.match(value)))) {
			e.checked = form.elements[checkall].checked;
			if(changestyle && e.parentNode && e.parentNode.tagName.toLowerCase() == 'li') {
				e.parentNode.className = e.checked ? 'checked' : '';
			}
		}
	}
}
iCMS.setEditorSize=function (o,h,e){
	e=e||iCMS.editor_id
	var id="#"+e+"___Frame";
	var oh=parseInt($(id).height());
	if(o=="+"){
		$(id).height(oh+parseInt(h));
	}else{
		if(oh>400){
			$(id).height(oh-parseInt(h));
		}
	}
}
iCMS.insert=function (val,id){
	if(id=='iCMSEDITOR'){
		if(in_array(val.substr(val.lastIndexOf(".")+1), ['gif', 'jpeg', 'jpg', 'png', 'bmp'])){
			var content='<p><img src=\"'+val+'\" /></p>';
		}else{
			var name=val.substr(val.lastIndexOf("/")+1);
			var content='<p class="attachment"><a href="'+ val +'" target="_blank"><img src="images/attachment.gif" border="0" align="center"></a>&nbsp;<a href="'+ val +'" target="_blank"><u>'+ name +'</u></a></p>';
		}
		appendEditor(content);
	}else{
		$("#"+id).val(val);
	}
	$('#iCMS_DIALOG').dialog('close');
}
iCMS.showDialog=function(url,callback,title,width,height){
	$('.close').click();
	width=width||770;
	height=height||510;
	$.get(url,{'callback':callback},function(html){
	    title=title||'iCMS - 提示信息';
	    var d = $('#iCMS_DIALOG');
	    d.attr("title",title).html(html).dialog({
	        modal: true,
	        minHeight: height,
	        minWidth: width,
			width: width,
			height: height,
	        buttons: {
	            '确定': function() {
	            	if(document.getElementById('iDF')){
	                    $('#iDF').submit();
	                }else{
	                	d.dialog('close');
	                }
	            },
	            '取消': function() {
	                    d.dialog('close');
	            }
	        }
	    }).dialog('open');
	});
}
iCMS.setcookie = function (cookieName, cookieValue, seconds, path, domain, secure) {
	var expires = new Date();
	expires.setTime(expires.getTime() + seconds);
	document.cookie = escape(cookieName) + '=' + escape(cookieValue)
		+ (expires ? '; expires=' + expires.toGMTString() : '')
		+ (path ? '; path=' + path : '/')
		+ (domain ? '; domain=' + domain : '')
		+ (secure ? '; secure' : '');
}
iCMS.getcookie = function (name) {
	var cookie_start = document.cookie.indexOf(name);
	var cookie_end = document.cookie.indexOf(";", cookie_start);
	return cookie_start == -1 ? '' : unescape(document.cookie.substring(cookie_start + name.length + 1, (cookie_end > cookie_start ? cookie_end : document.cookie.length)));
}
iCMS.SH = function (s,h){
	$('#'+s).show();
	$('#'+h).hide();
}
function reloadDialog(url){
	$.get(url,function(html){
		$('#iCMS_DIALOG').html(html);
	});
}
Array.prototype.indexOf = function (vItem) {
  for (var i=0; i<this.length; i++) {
    if (vItem == this[i]) {
	  return i;
	}
  }
  return -1;
}
function in_array(needle, haystack) {
	if(typeof needle == 'string' || typeof needle == 'number') {
		for(var i in haystack) {
			if(haystack[i] == needle) {
					return true;
			}
		}
	}
	return false;
}
function isUndefined(variable) {
    return typeof variable == 'undefined' ? true : false;
}
function appendEditor(html){
	var oEditor = FCKeditorAPI.GetInstance(iCMS.editor_id);
	oEditor.InsertHtml(html);
}

function textareasize(obj) {
    if(obj.scrollHeight > 70) {
        obj.style.height = obj.scrollHeight + 'px';
    }
}