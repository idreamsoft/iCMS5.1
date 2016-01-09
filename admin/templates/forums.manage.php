<?php /**
 * @package iCMS
 * @copyright 2007-2010, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */
!defined('iPATH') && exit('What are you doing?'); 
admincp::head();
?>
<link rel="stylesheet" href="admin/css/jquery.treeview.css" />
<script src="admin/js/jquery.cookie.js" type="text/javascript"></script>
<script src="admin/js/jquery.treeview.js" type="text/javascript"></script>
<script src="admin/js/jquery.treeview.async.js" type="text/javascript"></script>
<script type="text/javascript">
    $(function(){
        $("#tree").treeview({
        	url:'<?php echo __ADMINCP__; ?>=ajax&do=forums&hasChildren=true&jt=<?php echo time(); ?>',
            collapsed: false,
            animated: "medium",
            control:".tabs",
            persist: "cookie",
            cookieId: "iCMS-treeview-black"
        });
        $('.tabs a').click(function(){
        	$(this).parent().click();
        });
    });
</script>
<div class="position">当前位置：管理中心&nbsp;&raquo;&nbsp;版块管理</div>
<table class="adminlist" border="0" cellspacing="0" cellpadding="0">
  <thead>
    <tr>
      <th>技巧提示</th>
    </tr>
  </thead>
  <tr>
    <td>点击FID后面数字，可查看该版块</td>
  </tr>
  <tr>
    <td>★:实版块 ☆:虚版块 ∞:外部URL版块</td>
  </tr>
</table>
<style type="text/css">
.tabs a{
	height:24px;
	line-height:24px;
	display: block;
}
</style>
<div class="tabs">
	<ul>
		<li ref="tabs-base"><a href="javascript:void(0);" hidefocus=true>收缩所有版块</a></li>
		<li class="active" ref="tabs-publish"><a href="javascript:void(0);" hidefocus=true>展开所有版块</a></li>
	</ul>
</div>
<form name="cpform" method="post" action="<?php echo __ADMINCP__; ?>=forums" id="cpform" target="iCMS_FRAME" >
  <input type="hidden" name="do" value="edit" />
  <table class="adminlist" border="0" cellspacing="0" cellpadding="0" style="margin-top:0px;">
    <thead>
      <tr>
        <th><div class="ordernum">顺序</div>
          <div class="name">版块名称</div>
          <div class="operation">管理</div></th>
      </tr>
    </thead>
    <tbody id="clist">
      <tr>
        <td><ul id="tree"></ul></td>
      </tr>
    </tbody>
    <tfoot>
      <tr>
        <td><input type="submit" class="submit" name="editsubmit" value="提交"  /></td>
      </tr>
    </tfoot>
  </table>
</form>
</body></html>