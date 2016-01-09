<?php /**
 * @package iCMS
 * @copyright 2007-2010, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */
!defined('iPATH') && exit('What are you doing?'); 
admincp::head();
?>
<script type="text/javascript">
	$(function(){
		$("#binding").click(function(){
		    $('#tableText').html("模块表名:");
		    $('#tableTips').html("请以字母开头,留空将按模块名称拼音");
		    if(this.checked){
			    $('#tableText').html("模块名:");
		    	$('#tableTips').html("模块文件 xxxx.mo.php 请填上模块名xxxx <br />例:<br /> 模块文件:download.mo.php <br />模块名:download");
		    }
		  }); 
		  $("input[name=position]").click(function(){
			  var pt=$("#posText").html();
			  $(".pos").empty().hide();
			  $("#pos"+this.value).html(pt).show();
		  });
		 $("input[name=position][value=<?php echo $model['position'];?>]").click();
		 $("input[name=pos][value=<?php echo $model['position2'];?>]").attr('checked','checked');
	})
</script>
<style type="text/css">
.pos {
	border: 1px dotted #B0B0B0;
	background: #F6F6F6;
	display:none;
	padding:2px;
	float:right;
}
#posText { display:none; }
</style>

<div class="position">当前位置：管理中心&nbsp;&raquo;&nbsp;自定义模块&nbsp;&raquo;&nbsp;<?php echo empty($id)?'新增':'编辑';?>模块</div>
<form action="<?php echo __ADMINCP__; ?>=models" method="post" target="iCMS_FRAME">
  <input type="hidden" name="do" value="save" />
  <input type="hidden" name="id" value="<?php echo $mid ; ?>" />
  <table class="adminlist">
    <thead>
      <tr>
        <th colspan="4"><?php echo empty($id)?'新增':'编辑' ; ?>模块</th>
      </tr>
    </thead>
    <tr>
      <td class="td80">模块名称:</td>
      <td class="rowform"><input name="name" type="text" id="name" value="<?php echo $model['name'] ; ?>" class="txt" /></td>
      <td class="tips2" colspan="2">&nbsp;</td>
    </tr>
    <tr>
      <td class="td80" id="tableText">模块表名:</td>
      <td class="rowform"><input name="table" type="text" id="table" value="<?php echo $model['table'] ; ?>" class="txt" />
        <br />
        <br />
        <input name="binding" type="checkbox" class="checkbox" id="binding" value="1" <?php echo $model['binding']?'checked="checked" ':'';?>/>
        绑定已有模块</td>
      <td id="tableTips" colspan="2">请以字母开头,留空将按模块名称拼音</td>
    </tr>
    <tr>
      <td class="td80">模块说明:</td>
      <td class="rowform"><textarea  rows="6" onkeyup="textareasize(this)" name="desc" id="desc" cols="50" class="tarea"><?php echo $model['desc'] ; ?></textarea></td>
      <td class="tips2" colspan="2">100字以内</td>
    </tr>
    <tr>
      <td class="td80">用户中心显示:</td>
      <td class="rowform"><ul onmouseover="altStyle(this);">
          <li<?php if($model['show']=='1') echo ' class="checked"'  ?>>
            <input class="radio" type="radio" name="show" value="1" <?php if($model['show']=='1') echo 'checked'  ?>>
            是</li>
          <li<?php if($model['show']=='0') echo ' class="checked"'  ?>>
            <input class="radio" type="radio" name="show" value="0" <?php if($model['show']=='0') echo 'checked'  ?>>
            否</li>
        </ul></td>
      <td class="tips2" colspan="2">是否在用户中心显示</td>
    </tr>    <tr>
      <td class="td80">菜单位置:</td>
      <td class="rowform"><?php foreach(menu::load() AS $H=>$value){
      	  	if($model['table']!=$H){
		  	$model['position']==$H && $checked=' checked="checked" ';
			echo '<span id="pos'.$H.'" class="pos"></span><input type="radio" name="position" class="radio" value="'.$H.'"'.$checked.'/> '.UI::lang('header_'.$H).'<br /><br />';
    }} ?></td>
      <td class="tips2" colspan="2">选择菜单所在位置</td>
    </tr>
    <thead>
      <tr>
        <th colspan="4">高级</th>
      </tr>
    </thead>
    <tr>
      <td class="td120">自定义表单结构<br />html代码:</td>
      <td colspan="3"><textarea  rows="10" onkeyup="textareasize(this)" name="form" id="form" class="tarea" style="width:98%"><?php echo $model['form'] ; ?></textarea></td>
    </tr>
    <tr>
      <td colspan="4"><input type="submit" class="submit" value="提交"  /></td>
    </tr>
  </table>
</form>
</div>
<div id="posText">
  <input name="pos" type="radio" value="left" class="radio" />
  左边
  <input name="pos" type="radio" value="sub" class="radio" />
  子菜单
  <input name="pos" type="radio" value="right" class="radio" />
  右边</div>
</body></html>