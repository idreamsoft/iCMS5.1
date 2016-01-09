<?php
/**
 * @package iCMS
 * @copyright 2007-2010, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */
!defined('iPATH') && exit('What are you doing?');
?>
<script type="text/javascript">
$(function(){
<?php if($_GET['fid']){  ?>
$("#fid").val("<?php echo $_GET['fid'] ; ?>");
<?php }  ?>	
});
</script>

<div class="pwrap">
  <div class="phead">
    <div class="phead-inner">
      <div class="phead-inner">
        <h3 class="ptitle"><span>我的<?php echo $model['name'];?></span></h3>
        <div class="clearfix articlesearchform">
          <form method="get" action="<?php echo __SELF__ ; ?>" name="form3">
            <input type="hidden" name="mo" value="content" />
            <input type="hidden" name="do" value="manage" />
            <input type="hidden" name="mid" value="<?php echo $mid ?>" />
            <input type="hidden" name="table" value="<?php echo $model['table'] ?>" />
            <div class="inner">
              <select id="fid" name="fid">
                <option value="">===请选择栏目===</option>
                <?php echo $forum->user_select(0,0,1,1,$mid) ; ?>
              </select>
              <input type="text" value="<?php echo $_GET['keyword'] ; ?>" id="keyword" name="keyword" class="text">
              <select name="status" id="status">
                <option value="">状态</option>
                <option value="1"<?php $_GET['status']=='1' && print(' selected="selected"')?>>已审核</option>
                <option value="0"<?php $_GET['status']=='0' && print(' selected="selected"')?>>未审核</option>
              </select>
              <span class="btn-wrap"><span class="btn">
              <input type="submit" value="搜索" class="button">
              </span></span> </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  <div class="pbody">
    <div class="datatable-wrap">
      <table class="datatable">
        <thead>
          <tr>
            <td><?php echo $model['name'];?>标题</td>
            <td>发布时间</td>
            <td>版块</td>
            <td>点击</td>
            <td>状态</td>
            <td>操作</td>
          </tr>
        </thead>
        <tbody>
          <?php if($rs)for($i=0;$i<$_count;$i++){
		      	$F=$forum->forum[$rs[$i]['fid']];
				$iurl=$this->iCMS->iurl('content',array($rs[$i],$F));
				$rs[$i]['url']=$rs[$i]['status']?$iurl->href:"javascript:iCMS.showDialog('".__USERCP__.'=content&do=preview&mid='.$mid.'&table='.$model['table'].'&id='.$rs[$i]['id']."','preview','预览');\" target=\"iCMS_FRAME";
				if(stristr($rs[$i]['url'], 'content.php')) {
				    $rs[$i]['url']='../'.$rs[$i]['url'];
				}
	  ?>
          <tr id="tr-<?php echo $rs[$i]['id'] ; ?>">
            <td><a target="_blank" href="<?php echo $rs[$i]['url'] ; ?>"><?php echo $rs[$i]['title'] ; ?></a></td>
            <td><?php echo get_date($rs[$i]['pubdate'],'Y-m-d');?></td>
            <td><?php echo $F['name'] ; ?></td>
            <td><?php echo $rs[$i]['hits'] ; ?></td>
            <td><?php if($rs[$i]['status']=="1"){ ?>
              已审核
              <?php }else{?>
              <font color="red">未审核</font>
              <?php } ?></td>
            <td><a href="<?php echo __USERCP__; ?>=content&do=add&mid=<?php echo $mid;?>&table=<?php echo $model['table'];?>&id=<?php echo $rs[$i]['id'] ; ?>" class="edit" title="编辑此<?php echo $model['name'];?>">编辑</a> | <a href="<?php echo __USERCP__; ?>=content&do=del&mid=<?php echo $mid;?>&table=<?php echo $model['table'];?>&id=<?php echo $rs[$i]['id'] ; ?>" target="iCMS_FRAME" class="del" onclick="return confirm('确定要删除“<?php echo HTML2JS($rs[$i]['title']) ; ?>”?');" title='删除此<?php echo $model['name'];?>,此操作不可恢复'>删除</a> | <a target="_blank" href="<?php echo $rs[$i]['url'] ; ?>">
              <?php if($rs[$i]['status']=="1"){ ?>
              查看
              <?php }else{?>
              预览
              <?php } ?>
              </a></td>
          </tr>
          <?php }else{ ?>
          <tr>
            <td colspan="6"><div class="nodata">当前没有任何<?php echo $model['name'];?>.</div></td>
          </tr>
          <?php }  ?>
        </tbody>
      </table>
      <div class="clearfix pagination">
        <div class="pagination-inner"><?php echo $this->pagenav ; ?></div>
      </div>
    </div>
  </div>
  <div class="pfoot">
    <p><b>-</b></p>
  </div>
</div>
