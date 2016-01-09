/**
 * iCMS FCKeditor plugin.
 */

(function($) {
	window.iCMSed = window.iCMSed || {};
	iCMSed.cleanup = function() {
		var ed = FCKeditorAPI.GetInstance(iCMS.editor_id),html = ed.GetXHTML(true);
		html = html.replace(/\/"/g,'"');
		html = html.replace(/\\\&quot;/g,"");
		html = html.replace(/<div style="page-break-after: always; "><span style="DISPLAY:none">&nbsp;<\/span><\/div>/g, '#--iCMS.PageBreak--#');
		html = html.replace(/<div style="page-break-after: always"><span style="display: none">&nbsp;<\/span><\/div>/g, '#--iCMS.PageBreak--#');

		html = html.replace(/\r/g,"");
		html = html.replace(/on(load|click|dbclick|mouseover|mousedown|mouseup)="[^"]+"/ig,"");
		html = html.replace(/<script[^>]*?>([\w\W]*?)<\/script>/ig,"");
		html = html.replace(/<style[^>]*?>([\w\W]*?)<\/style>/ig,"");
		html = html.replace(/<a[^>]+href=[" ]?([^"]+)[" ]?[^>]*>(.*?)<\/a>/ig,"[url=$1]$2[/url]");
		html = html.replace(/<img[^>]+src=[" ]?([^"]+)[" ]?[^>]*>/ig,"[img]$1[/img]");
		html = html.replace(/<embed[^>]+src=[" ]?([^"]+)[" ]\s+width=[" ]?([^"]\d+)[" ]\s+height=[" ]?([^"]\d+)[" ]?[^>]*>.*?<\/embed>/ig,"[media=$2,$3]$1[/media]");
		html = html.replace(/<embed[^>]+src=[" ]?([^"]+)[" ]?[^>]*>.*?<\/embed>/ig,"[media]$1[/media]");
		html = html.replace(/<b[^>]*>(.*?)<\/b>/ig,"[b]$1[/b]");
		html = html.replace(/<strong[^>]*>(.*?)<\/strong>/ig,"[b]$1[/b]");
		html = html.replace(/<p[^>]*?>/g,"\n\n");

		html = html.replace(/&nbsp;/g," ");
		html = html.replace(/&amp;/g,"&");
		html = html.replace(/&quot;/g,"\"");
		html = html.replace(/&lt;/g,"<");
		html = html.replace(/&gt;/g,">");

		html = html.replace(/<[^>]*?>/g,"");
		html = html.replace(/\[url=([^\]]+)\]\n(\[img\]\1\[\/img\])\n\[\/url\]/g,"$2");
		html = html.replace(/\n+/g,"[iCMS.N]");
		html = this._format(html);
		html = html.replace(/\[img\](.*?)\[\/img\]/ig,'<p><img src="$1" /></p>');
		html = html.replace(/\[b\](.*?)\[\/b\]/ig,'<b>$1</b>');
		html = html.replace(/\[url=([^\]|#]+)\](.*?)\[\/url\]/g,'$2');
		html = html.replace(/\[url=([^\]]+)\](.*?)\[\/url\]/g,'<a target="_blank" href="$1">$2</a>');
		html = html.replace(/<p>#--iCMS\.PageBreak--#<\/p>/g, '<div style="page-break-after: always"><span style="display: none">&nbsp;</span></div>');
		ed.SetHTML(html);
	}

	iCMSed._format	= function (str){
		var cc,tempstr;
		cc = str;tempstr = "";
		var ss=cc.split("[iCMS.N]");
		for (var i=0; i< ss.length; i++ ){
			while (ss[i].substr(0,1)==" "||ss[i].substr(0,1)=="　"){
				ss[i]=ss[i].substr(1,ss[i].length);
			}
			if (ss[i].length>0) tempstr+="<p>"+ss[i]+"</p>";
		}
		return tempstr;
	}
})($);
