$(function(){
	$("#comment-login").click(function(){
		var param=iCMS.chkLogInput();
		if(!param)return false;
		param.action='login';
		$.post(iCMS.publicURL+"/passport.php",param,
			function(o){
				alert(o.msg);
				if(o.state=="1"){
					iCMS.getUserInfo();
				}
			},"json");
	});
	$("#anonymous").click(function(){
		if(this.checked){
			$('#iCMS_UserName').val(iCMS.anonymousname).attr("readonly",true).attr("disabled",true);
			$("#iCMS_PassWord").hide();
			$("label[for=iCMS_PassWord]").hide();
			$("#comment-login").hide();
		}else{
			$('#iCMS_UserName').val('').attr("readonly",false).attr("disabled",false);
			$("#iCMS_PassWord").show();
			$("label[for=iPassWord]").show();
			$("#comment-login").show();
		}
	});
//验证码
	$("#iSeccode").click(function(){
		$("#seccode-img").remove();
		$('<img />').attr('id',"seccode-img").attr('title',"看不清楚!点击图片换一张")
		.attr('src',iCMS.publicURL+"/seccode.php?"+Math.random())
		.insertAfter(this).bind("click",function(){$("#iSeccode").click();});
	}); 
//提交事件
	$("#iComment").submit(function (){
		if(iCMS.anonymous){
			comment();
		}else{
			var islogin=true;
			if(!iCMS.getUserInfo()){
				islogin=false;
				var param=iCMS.chkLogInput();
				if(!param)return false;
				param.action='login';
				$.post(iCMS.publicURL+"/passport.php",param,
					function(o){
						if(o.state=="0"){
							alert(o.msg);
							return false
						}else{
							iCMS.getUserInfo();
							comment();
						}
					},"json");
			}
			if(islogin) comment();
		}
	  return false;
	}); 
});

function comment(){
	var param={"do":'save',"username":$("#iCMS_UserName").val(),"seccode":$("#iSeccode").val(),"anonymous":(typeof $("#anonymous[checked]").val()=="undefined"?0:1),
		"title":$("#comment_title").val(),"indexId":$("#comment_indexId").val(),"mId":$("#comment_mid").val(),"sortId":$("#comment_sortId").val(),
		"quote":$("#comment_quote").val(),"floor":$("#comment_floor").val(),
		"commentext":$("#comment_text").val()
	}
	if(param.commentext==""){
		alert("评论内容不能为空!");
		$("#comment_text").focus();
		return false;
	}
	if(iCMS.seccode && param.seccode==""){
		$(".comment-seccode").show();
		$("#iSeccode").click().select();
		return false;
	}
	$.post(iCMS.publicURL+"/comment.php",param,
		function(o){
			$("#iSeccode").val('');
			$("#seccode-img").attr('src',iCMS.publicURL+"/seccode.php?"+Math.random());
			alert(o.msg);
			if(o.state=="1") window.location.reload();
		},"json");
}