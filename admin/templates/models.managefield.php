<?php /**
 * @package iCMS
 * @copyright 2007-2010, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */
!defined('iPATH') && exit('What are you doing?'); 
admincp::head();
?>
<style type="text/css">
.tbody-selected {background:#F2F9FD;border:1px solid #A6C9D7;height:22px;}
.sortable tbody tr td{cursor: hand; cursor: pointer;}
</style>
<script type="text/javascript" src="admin/js/jquery.sortable.js"></script>
<script type="text/javascript">
$(function(){
	$(".sortable").sortable({
		placeholder: 'tbody-selected',
		stop:function(e,ui){
			$('form').submit();
		}
	}).disableSelection();
}); 
function doaction(obj){
	switch(obj.value){ 
		case "dels":
			if(confirm("确定要删除！！！")){
				return true;
			}else{
				obj.value="empty";
				return false;
			}
		break;
	}
}
</script>

<div class="position">当前位置：管理中心&nbsp;&raquo;&nbsp;自定义模块&nbsp;&raquo;&nbsp;字段管理</div>
<table class="adminlist" id="tips">
<tr>
  <th class="partition">技巧提示</th>
</tr>
<tr>
  <td class="tipsblock"><ul id="tipslis">
      <li>排序:表单的显示顺序</li>
      <li>字段排序可拖拉</li>
    </ul></td>
</tr>
</table>
<input type="button" class="submit big" value="内容管理" onclick="window.location.href='<?php echo __ADMINCP__; ?>=content&do=manage&mid=<?php echo $mid;?>&table=<?php echo $model['table'];?>';" />
<input type="button" class="submit big" value="添加内容" onclick="window.location.href='<?php echo __ADMINCP__; ?>=content&do=add&mid=<?php echo $mid;?>&table=<?php echo $model['table'];?>';" />
<input type="button" class="submit big" value="添加字段" onclick="window.location.href='<?php echo __ADMINCP__; ?>=models&do=addfield&id=<?php echo $mid;?>';" />
<form action="<?php echo __ADMINCP__;?>=models" method="post" target="iCMS_FRAME">
  <input type="hidden" name="do" value="sortable" />
  <input type="hidden" name="mid" value="<?php echo $mid;?>" />
  <table class="adminlist">
    <thead>
      <tr>
        <th>排序</th>
        <th>字段ID</th>
        <th>字段名称</th>
        <th>字段</th>
        <th>类型</th>
        <th>说明</th>
        <th width="180">模板标签</th>
        <th width="90">管理</th>
      </tr>
    </thead>
    <tbody class="sortable">
	    <?php foreach($fArray AS $i=>$field){
	    	$rs=$FieldArray[$field];
		?>
	    <tr id="field_<?php echo $rs['id'];?>">
	      <td><?php echo $i+1;?></td>
	      <td><input type="text" name="field[]" value="<?php echo $rs['field'];?>" style="display:none;"/><?php echo $rs['id'];?></td>
	      <td><?php echo $rs['name'];?></td>
	      <td><?php echo $rs['field'];?></td>
	      <td><?php echo model::FieldType($rs['type']);?><?php if($rs['hidden']){?>[隐藏字段]<?php } ?></td>
	      <td><?php echo $rs['description'];?></td>
	      <td>&lt;!--{$content.<?php echo $rs['field'];?>}--&gt;</td>
	      <td><?php if(!model::isDefField($rs['field'])){?>
	        <a href="<?php echo __ADMINCP__;?>=models&do=addfield&id=<?php echo $rs['mid'];?>&fid=<?php echo $rs['id'];?>">编辑</a> |
	        <a href="<?php echo __ADMINCP__;?>=models&do=delfield&id=<?php echo $rs['mid'];?>&fid=<?php echo $rs['id'];?>&field=<?php echo $rs['field'];?>"onClick="return confirm('确定要删除字段?');" target="iCMS_FRAME">删除</a>
	      <?php }else{ ?>
	      		系统默认字段
	      <?php } ?>
	        </td>
	    </tr>
	    <?php } ?>
    </tbody>
  </table>
</form>
</body></html>