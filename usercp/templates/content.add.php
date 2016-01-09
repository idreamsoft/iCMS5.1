<?php
/**
 * @package iCMS
 * @copyright 2007-2010, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */
!defined('iPATH') && exit('What are you doing?');
?>
<style type="text/css">
.adminlist .txt{width:600px !important;}
</style>
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
    <?php foreach((array)$formArray AS $i=>$form){
		if($form['js']) echo $form['js'];
    }?>
	}); 
});
</script>
<div class="pwrap">
  <div class="phead">
    <div class="phead-inner">
      <div class="phead-inner">
        <h3 class="ptitle"><span><?php echo empty($id)?'添加':'编辑' ; ?><?php echo $model['name'] ; ?></span></h3>
      </div>
    </div>
  </div>
  <div class="pbody">
<form action="<?php echo __USERCP__; ?>=content" method="post" enctype="multipart/form-data" name="savecontent" id="savecontent"  target="iCMS_FRAME">
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
	      <td class="td40"><?php echo $form['general']['label']?>：</td>
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
        <input name="REFERER" type="hidden" id="REFERER" value="<?php echo $REFERER ; ?>" />
        <input type="submit" value="提交" class="submit" />
        &nbsp;&nbsp;
        <input type="reset" value="重置" class="submit" /></td>
    </tr>
  </table>
</form>
  </div>
  <div class="pfoot">
    <p><b>-</b></p>
  </div>
</div>
