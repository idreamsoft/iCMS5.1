<?php /**
 * @package iCMS
 * @copyright 2007-2010, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */
!defined('iPATH') && exit('What are you doing?'); 
admincp::head();
?>

<div class="position">当前位置：管理中心&nbsp;&raquo;&nbsp;组管理</div>
<table class="adminlist" id="tips">
  <thead>
    <tr>
      <th class="partition">技巧提示</th>
    </tr>
  </thead>
  <tr>
    <td class="tipsblock"><ul id="tipslis">
        <li>超级管理员禁止删除</li>
      </ul></td>
  </tr>
</table>
<div class="tabs">
	<ul>
		<li class="active" ref="tabs-admin">管理组</li>
		<li ref="tabs-user">会员组</li>
	</ul>
</div>
<div id="tabs-admin">
<form action="<?php echo __ADMINCP__; ?>=groups" method="post" target="iCMS_FRAME">
  <input type="hidden" name="do" value="edit" />
  <input type="hidden" name="type" value="a" />
  <table class="adminlist" style="margin-top:0px;">
    <thead>
      <tr>
        <th>排序</th>
        <th>GID</th>
        <th>名称</th>
        <th>管理</th>
      </tr>
    </thead>
    <?php 
		$rs	= $group->group['a'];
		$_count	= count($rs);
      	for($i=0;$i<$_count;$i++){
      ?>
    <tr id="gid<?php echo $rs[$i]['gid'] ; ?>">
      <td><input type="text" name="order[<?php echo $rs[$i]['gid'] ; ?>]" value="<?php echo $rs[$i]['order'] ; ?>" style="width:20px;border:1px #F6F6F6 solid;"/></td>
      <td><?php echo $rs[$i]['gid'] ; ?></td>
      <td><input name="name[<?php echo $rs[$i]['gid'] ; ?>]" type="text" class="txt" value="<?php echo $rs[$i]['name'] ; ?>"/></td>
      <td><a href="<?php echo __ADMINCP__; ?>=groups&do=power&groupid=<?php echo $rs[$i]['gid'] ; ?>">后台权限</a> | <a href="<?php echo __ADMINCP__; ?>=groups&do=fpower&groupid=<?php echo $rs[$i]['gid'] ; ?>">栏目权限</a> <?php if($rs[$i]['gid']!='1'){  ?> | <a href="<?php echo __ADMINCP__; ?>=groups&do=del&groupid=<?php echo $rs[$i]['gid'] ; ?>"  onclick='return confirm("确定要删除该管理组?");' target="iCMS_FRAME">删除</a><?php }  ?></td>
    </tr>
    <?php }  ?>
    <tr>
      <td><input type="text" name="addneworder" value="<?php echo $i+1 ; ?>" style="width:20px;border:1px #F6F6F6 solid;"/></td>
      <td><input name="addnewname" type="text" class="txt" value=""/>
        添加新组</td>
      <td></td>
    </tr>
    <tr>
      <td colspan="3"><input type="submit" class="submit" value="提交"  /></td>
    </tr>
  </table>
</form>
</div><div id="tabs-user" style="display:none;">
<form action="<?php echo __ADMINCP__; ?>=groups" method="post" target="iCMS_FRAME">
  <input type="hidden" name="do" value="edit" />
  <input type="hidden" name="type" value="u" />
  <table class="adminlist" style="margin-top:0px;">
    <thead>
      <tr>
        <th>排序</th>
        <th>GID</th>
        <th>名称</th>
        <th>管理</th>
      </tr>
    </thead>
    <?php 
		$rs	= $group->group['u'];
		$_count	= count($rs);
      	for($i=0;$i<$_count;$i++){
      ?>
    <tr id="gid<?php echo $rs[$i]['gid'] ; ?>">
      <td><input type="text" name="order[<?php echo $rs[$i]['gid'] ; ?>]" value="<?php echo $rs[$i]['order'] ; ?>" style="width:20px;border:1px #F6F6F6 solid;"/></td>
      <td><?php echo $rs[$i]['gid'] ; ?></td>
      <td><input name="name[<?php echo $rs[$i]['gid'] ; ?>]" type="text" class="txt" value="<?php echo $rs[$i]['name'] ; ?>"/></td>
      <td><a href="<?php echo __ADMINCP__; ?>=groups&do=del&groupid=<?php echo $rs[$i]['gid'] ; ?>"  onclick='return confirm("确定要删除该管理组?");' target="iCMS_FRAME">删除</a></td>
    </tr>
    <?php }  ?>
    <tr>
      <td><input type="text" name="addneworder" value="<?php echo $i+1 ; ?>" style="width:20px;border:1px #F6F6F6 solid;"/></td>
      <td><input name="addnewname" type="text" class="txt" value=""/>
        添加新组</td>
      <td></td>
    </tr>
    <tr>
      <td colspan="3"><input type="submit" class="submit" value="提交"  /></td>
    </tr>
  </table>
</form>
</div>
</body></html>