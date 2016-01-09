<?php /**
 * @package iCMS
 * @copyright 2007-2010, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */
!defined('iPATH') && exit('What are you doing?'); 
admincp::head();
?>
<div class="position">当前位置：管理中心&nbsp;&raquo;&nbsp;<?php echo $model['name'] ; ?>管理&nbsp;&raquo;&nbsp;<?php echo empty($id)?'添加':'编辑'?><?php echo $model['name'] ; ?></div>
<script type="text/javascript">
$(function(){
	$("#title").focus();
	$("#savecontent").submit(function(){
		if($("#fid option:selected").attr("value")=="0"){
			alert("请选择所属栏目");
			$("#fid").focus();
			return false;
		}
		if($("#title").val()==''){
			alert("标题不能为空!");
			$("#title").focus();
			return false;
		}
    <?php if($formArray)foreach($formArray AS $i=>$form){
    	if($form['js']){
    		echo $form['js'];
    	}
    }?>
	}); 
	$(".selectdefault").click(function(){
		var offset 		= $(this).offset();
		var snapTop 	= offset.top+25;
		var snapLeft 	= offset.left;
//		alert(snapTop+"-"+snapLeft);
		var def			= $(".tipsdiv");
		var inid		= $(this).attr("to");
		$("#dtitle").html("选择");
		$("#defaultbody").empty().html($("#"+inid+"_menu").html());

		def.hide().addClass("selectdefaultdiv")
		.css({"top" : snapTop, "left" : snapLeft,"width":"120"})
		.slideDown("slow");
	});
});
function viewPic(id){
	var path	=$("#"+id).val();
	if(path){
		iCMS.showDialog("<?php echo __ADMINCP__; ?>=dialog&do=viewPic",path,'查看缩略图');
		$('.close').click();
	}else{
		alert("没有图片!");
	}
}
var iCMS_WINDOW_<?php echo iCMSKEY?>;
function crop(id){
	var path	=$("#"+id).val();
	if(path){
//		showDialog("<?php echo __ADMINCP__; ?>=dialog&do=crop&pic="+path,id,'剪裁图片');
	    var w = 760,h = 720;
	    var winleft=0;//($('body').width()-w)/2;
	    var wintop=0;//($('body').height()-h)/2;
	    iCMS_WINDOW_<?php echo iCMSKEY?> = window.open("<?php echo __ADMINCP__; ?>=dialog&do=crop&pic="+path+"&callback="+id,"iCMS_WINDOW_<?php echo iCMSKEY?>","menubar=no,location=no,resizable=no,scrollbars=yes,status=no,width="+w+",height="+h+",left="+winleft+", top="+wintop);
		$('.close').click();
	}else{
		alert("没有图片!");
	}
}
</script>
<div class="tipsdiv">
  <table class="adminlist">
    <thead>
      <tr>
        <th><span style="float:right;margin-top:2px;" class="close"><img src="admin/images/close.gif" /></span><span id="dtitle">预设</span></th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td id="defaultbody"></td>
      </tr>
    </tbody>
  </table>
</div>
<table class="adminlist" border="0" cellspacing="0" cellpadding="0">
  <thead>
    <tr>
      <th>技巧提示</th>
    </tr>
  </thead>
  <tr>
    <td><ul id="tipslis">
        <li>点击ID可查看该<?php echo $model['name'] ; ?></li>
      </ul></td>
  </tr>
</table>

<form action="<?php echo __ADMINCP__; ?>=content" method="post" enctype="multipart/form-data" name="savecontent" id="savecontent"  target="iCMS_FRAME">
  <input type="hidden" name="do" value="save" />
  <input name="mid" type="hidden" id="mid" value="<?php echo $mid?>" />
  <input name="table" type="hidden" id="table" value="<?php echo $model['table'] ; ?>" />
  <?php if($model['form']){
	  echo stripslashes(unhtmlspecialchars($model['form']));
  }else{
  ?>
  <table class="adminlist" border="0" cellspacing="0" cellpadding="0" style="margin-top:0px;">
    <?php if($formArray)foreach($formArray AS $i=>$form){
    	if($form['general']){?>
	    <tr>
	      <td class="td80"><?php echo $form['general']['label']?>：</td>
	      <td class="rowform" style="width:auto"><?php echo $form['general']['html']?>
	        <?php if($form['general']['description']){ echo "<br />".$form['general']['description'];}?></td>
	    </tr>
    <?php }}?>
  </table>
  <?php }?>
  <table class="adminlist" border="0" cellspacing="0" cellpadding="0" style=" border-top:0px;">
    <tr>
      <td colspan="2"><?php if($formArray)foreach($formArray AS $i=>$form){
	        if($form['hidden']){
	        	echo $form['hidden'];
	        }
      } ?>
        <input name="id" type="hidden" id="id" value="<?php echo $id?>" />
        <input name="content[userid]" type="hidden" id="userid" value="<?php echo $rs['userid']?>" />
        <input name="content[postype]" type="hidden" id="postype" value="1" />
        <input name="REFERER" type="hidden" id="REFERER" value="<?php echo $REFERER ; ?>" />
        <input type="submit" value="提交" class="submit" />
        &nbsp;&nbsp;
        <input type="reset" value="重置" class="submit" /></td>
    </tr>
  </table>
</form>
</div>
</body></html>