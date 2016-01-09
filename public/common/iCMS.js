/**
 * @package iCMS
 * @copyright 2007-2010, iDreamSoft
 * @license http://www.idreamsoft.cn iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
**/
(function($) {
	window.iCMS = window.iCMS || {};
	iCMS.config = {
		commURI:'/action.php?do=comment&callback=?&action=',
		diggURI:'/action.php?do=digg&callback=?&action='
	}
	iCMS.digg = function (act,indexId,cid){
		if(act=='up'||act=='down'){
			var pars = {'id':indexId,'cid':cid,'ajax':'1'};
			$.getJSON(this.publicURL+this.config.commURI+act,pars, function(o){
			 	if(o.state=='1'){
			 		var obj=$("#"+act+"_"+cid);
				 	var Num=parseInt(obj.text());
				 	obj.text(Num+1); 
			 	}else if(o.state=='0'){
			 	 	alert(o.msg);
			 	 }
			});
			return;
		}else if(act=='good'||act=='bad'){
			var pars = {'id':indexId,'mid':cid};
			$.getJSON(this.publicURL+this.config.diggURI+act,pars, function(o){
			 	if(o.state=='1'){
			 		var obj=$("#"+act+"_"+indexId);
				 	var Num=parseInt(obj.text());
				 	obj.text(Num+1); 
			 	}else if(o.state=='0'){
			 	 	alert(o.msg);
			 	 }
			});
			return;
		}
	}
	iCMS.quote = function (id,floor){
		$("#comment_quote").val(id);
		$("#comment_floor").val(parseInt(floor)+1);
		$("a[id^=unquote]").hide();
		$("a[id^=quote]").show();
		$("#quote"+id).hide();
		$("#unquote"+id).show();
		$('.comment-wrapper').hide();
		$("#comment-"+id+"-form").show().append($('.comment-wrapper').clone(true).show().addClass("comment-quote"));
		this.commentText('');
	}
	iCMS.unquote = function (id){
		$("#comment_quote").val(0);
		$("#comment_floor").val(0);
		$("#quote"+id).show();
		$("#unquote"+id).hide();
		$('.comment-wrapper').show();
		$("#comment-"+id+"-form").hide().empty();
	}
	iCMS.reply = function (cid){
			//this.addUBB('[reply]---[i]回复[/i] ' + $("#lou_" + cid).text() + ' [' + $("#comment_username_" + cid).text() + '] 时间:[' + $("#comment_time_" + cid).text() + "]---[/reply]\r\n");
	}
	iCMS.commentText = function (text){
		$('#comment_text').val(text).focus();
	}
	iCMS.smiley = function (sid){
		var cText=$('#comment_text').val();
		$('#comment_text').val(cText+"[s:"+sid+"]").focus();
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
	iCMS._cookie = function (name) {
		var cookie_start = document.cookie.indexOf(name);
		var cookie_end = document.cookie.indexOf(";", cookie_start);
		return cookie_start == -1 ? '' : decodeURI(document.cookie.substring(cookie_start + name.length + 1, (cookie_end > cookie_start ? cookie_end : document.cookie.length)));
	}
	iCMS.chkLogInput = function () {
		var p = {"username":$("#iCMS_UserName").val(),"password":$("#iCMS_PassWord").val()};
		if(p.username==""){
			alert("用户名不能为空!");
			$("#iCMS_UserName").focus();
			return false;
		}
		if(p.password==""){
			alert("密码不能为空!");
			$("#iCMS_PassWord").focus();
			return false;
		}
		return p;
	}
	iCMS.getUserInfo = function () {
		var u = this._cookie(this.cookiepre+'iCMS_USER_INFO');
		if(u){
			var info ='你好，<span>'+u+'</span>·<a href="'+this.usercpURL+'">设置</a>┆<a href="javascript:void(0);" onclick="return iCMS.logout();">退出</a>';
			$(".iCMS_userinfo").html(info).show();
			$(".iCMS_userlogin").hide();
			return true;
		}
		return false;
	}
	iCMS.logout = function () {
		$.get(this.publicURL+"/passport.php?do=logout");
		$(".iCMS_userinfo").empty().hide();
		$(".iCMS_userlogin").show();
	}
})($);
$(function(){
	iCMS.getUserInfo();
	$("#search").submit( function() {
		var Qval=$("#search #q").val();
		if(Qval==""||Qval=="请输入关键字"){
			alert("请填写关键字");
			if(Qval=="请输入关键字")$("#search #q").val("");
			$("#search #q").focus();
			return false;
		}
	});
	$("#forumAuth").submit( function() {
		var param={'password':$("#forumAuth #iCMS_forum_password").val(),'fid':$("#forumAuth #fid").val(),'forward':$("#forumAuth #forward").val()};
		if(param.password==""){
			alert("请输入密码");
			$("#forumAuth #iCMS_forum_password").focus();
			return false;
		}
		$.post(iCMS.publicURL+"/action.php?do=forumAuth",param,
			function(o){
				alert(o.msg);
				if(o.state=="1") window.location.reload();
			},"json");
		return false;
	});
});
function textareasize(obj,height) {
	height=height||70;
	if(obj.scrollHeight > height) {
		obj.style.height = obj.scrollHeight + 'px';
	}
}