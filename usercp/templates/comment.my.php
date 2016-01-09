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
});
</script>

<div class="pwrap">
  <div class="phead">
    <div class="phead-inner">
      <div class="phead-inner">
        <h3 class="ptitle"><span>我的评论</span></h3>
        <div class="clearfix articlesearchform">
          <form method="get" action="<?php echo __SELF__ ; ?>" name="form3">
            <input type="hidden" name="mo" value="comment" />
            <input type="hidden" name="do" value="my" />
            <input type="hidden" name="mid" value="<?php echo $mid ; ?>" />
            <div class="inner">
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
            <td>评论内容</td>
            <td>标题</td>
            <td>评论时间</td>
            <td>支持反对</td>
            <td>状态</td>
            <td>操作</td>
          </tr>
        </thead>
        <tbody>
          <?php if($rs)for($i=0;$i<$_count;$i++){?>
          <tr id="tr-<?php echo $rs[$i]['id'] ; ?>">
            <td><div style="width:240px; height:36px; overflow:hidden;cursor:pointer;" title="点击查看详细内容" onclick="javascript:iCMS.showDialog('<?php echo __USERCP__; ?>=comment&do=view&id=<?php echo $rs[$i]['id'] ; ?>')"><?php echo $rs[$i]['contents'] ; ?></div></td>
            <td><a target="_blank" href="<?php echo __USERCP__; ?>=comment&do=url&mid=<?php echo $rs[$i]['mId'] ; ?>&id=<?php echo $rs[$i]['indexId'] ; ?>"><?php echo $rs[$i]['title'] ; ?></a></td>
            <td><?php echo get_date($rs[$i]['addtime'],'Y-m-d');?></td>
            <td>+<?php echo $rs[$i]['up'] ; ?>/-<?php echo $rs[$i]['down'] ; ?></td>
            <td><?php if($rs[$i]['status']=="1"){ ?>
              已审核
              <?php }else{?>
              <font color="red">未审核</font>
              <?php } ?></td>
            <td><a href="<?php echo __USERCP__; ?>=comment&do=del&mid=<?php echo $rs[$i]['mId'] ; ?>&id=<?php echo $rs[$i]['id'] ; ?>" target="iCMS_FRAME" class="del" onclick="return confirm('确定要删除“<?php echo HTML2JS($rs[$i]['title']) ; ?>”的评论?');" title='删除此评论,此操作不可恢复'>删除</a> | <a href="javascript:iCMS.showDialog('<?php echo __USERCP__; ?>=comment&do=view&id=<?php echo $rs[$i]['id'] ; ?>')">查看 </a></td>
          </tr>
          <?php }else{ ?>
          <tr>
            <td colspan="6"><div class="nodata">当前没有任何评论.</div></td>
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
