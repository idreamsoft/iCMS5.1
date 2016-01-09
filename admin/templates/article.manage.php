<?php /**
 * @package iCMS
 * @copyright 2007-2010, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */
!defined('iPATH') && exit('What are you doing?'); 
admincp::head();
?>
<div class="position">当前位置：管理中心&nbsp;&raquo;&nbsp;文章管理<?php echo $position;?></div>
<script type="text/javascript">
$(function(){
<?php if(isset($_GET['at'])){  ?>$("#at").val("<?php echo $_GET['at'] ; ?>");
<?php } if($_GET['fid']){  ?>$("#fid").val("<?php echo $_GET['fid'] ; ?>");
<?php } if($_GET['st']){  ?>$("#st").val("<?php echo $_GET['st'] ; ?>");
<?php } if($_GET['orderby']){  ?>$("#orderby").val("<?php echo $_GET['orderby'] ; ?>");
<?php } if($_GET['sub']=="on"){  ?>$("#sub").attr("checked",true);
<?php } if($_GET['nopic']=="on"){  ?>$("#nopic").attr("checked",true);
<?php }  ?>	
	  $("#alist tr").mouseover(function(){
	  	  $(this).find(".do").show();
	  }).mouseout(function(){
	  	  $(this).find(".do").hide();
	  });
	$(".close").click(function(){
		var parentdiv=$(this).attr("parent");
	    $("#"+parentdiv).hide();
	    $("#do").val("");
	});
});
function doaction(obj){
	var offset 		= $(obj).offset();
	var snapTop 	= offset.top-$("#"+obj.value+"-div").height()-36;
	var snapLeft 	= offset.left;
//	$(".tipsdiv").slideUp("slow");
	$(".tipsdiv").hide();
	$("#"+obj.value+'-div').css({"top" : snapTop, "left" : snapLeft}).show().draggable();
	if(obj.value=='dels'){
		if(confirm("确定要删除！！！")){
			return true;
		}else{
			obj.value="empty";
			return false;
		}
	}
}
</script>
<form action="<?php echo __SELF__ ; ?>" method="get">
  <input type="hidden" name="mo" value="article" />
  <input type="hidden" name="do" value="manage" />
  <input type="hidden" name="type" value="<?php echo $_GET['type'] ; ?>" />
  <input type="hidden" name="act" value="<?php echo $act ; ?>" />
  <table class="adminlist">
    <tr>
      <td class="tipsblock"><select name="at" id="at">
          <option value="-1"> == 文章属性 == </option>
          <option value="0">普通文章[type='0']</option>
          <?php echo contentype("article") ; ?>
        </select>
        <select name="fid" id="fid">
          <option value="0"> == 按栏目 == </option>
          <?php echo $forum->select(0,0,1,'all') ; ?>
        </select>
        <input type="checkbox" name="sub" class="checkbox" id="sub"/>
        子栏目
        <select name="st" id="st">
          <option value="title">标题</option>
          <option value="id">ID</option>
          <option value="top">置顶权重</option>
          <option value="tkd">标题/关键字/简介</option>
        </select>
        <select name="orderby" id="orderby">
          <option value=""> == 排序 == </option>
          <optgroup label="降序"></optgroup>
          <option value="id DESC">ID[降序]</option>
          <option value="hits DESC">点击[降序]</option>
          <option value="good DESC">顶[降序]</option>
          <option value="pubdate DESC">时间[降序]</option>
          <option value="comments DESC">评论[降序]</option>
          <optgroup label="升序"></optgroup>
          <option value="id ASC">ID[升序]</option>
          <option value="hits ASC">点击[升序]</option>
          <option value="good ASC">顶[升序]</option>
          <option value="pubdate ASC">时间[升序]</option>
          <option value="comments ASC">评论[升序]</option>
        </select>
        <input type="checkbox" name="nopic" class="checkbox" id="nopic"/>
        无缩略图 </td>
    </tr>
    <tr>
      <td class="tipsblock">开始时间：
        <input type="text" class="txt datepicker" name="starttime" value="<?php echo $_GET['starttime'] ; ?>" style="width:80px">
        -结束时间：
        <input type="text" class="txt datepicker" name="endtime" value="<?php echo $_GET['endtime'] ; ?>" style="width:80px">
        关键字：
        <input type="text" name="keywords" class="txt" id="keywords" value="<?php echo $_GET['keywords'] ; ?>" size="30" />
        每页显示
        <input type="text" name="perpage" class="txt" id="perpage" value="<?php echo $_GET['perpage']?$_GET['perpage']:20 ; ?>" style="width:30px;" />
        <input type="submit" class="submit" value="搜索"/></td>
    </tr>
  </table>
</form>
<form action="<?php echo __ADMINCP__; ?>=article" method="post" target="iCMS_FRAME">
  <table class="adminlist">
    <thead>
      <tr>
        <th width="30" height="22">选择</th>
        <th width="30">排序</th>
        <th>标 题</th>
        <th width="10%">栏目</th>
        <th width="4%">编辑</th>
        <th width="5%">属性/权重</th>
        <th width="120">日期</th>
      </tr>
    </thead>
    <tbody id="alist">
      <?php for($i=0;$i<$_count;$i++){
      	$ourl=$rs[$i]['url'];
      	$F=$forum->forum[$rs[$i]['fid']];
		$iurl=$this->iCMS->iurl('show',array($rs[$i],$F));
		empty($ourl) && $htmlurl=$iurl->path;
		$rs[$i]['url']=$iurl->href;
	  ?>
      <tr class="row<?php echo ($i%2) ; ?>" id="aid<?php echo $rs[$i]['id'] ; ?>">
        <td><input type="checkbox" class="checkbox" name="id[]" value="<?php echo $rs[$i]['id'] ; ?>" /></td>
        <td><input type="text" name="orderNum[<?php echo $rs[$i]['id'] ; ?>]" value="<?php echo $rs[$i]['orderNum'] ; ?>" style="width:24px;border:1px #F6F6F6 solid;"/></td>
        <td title="<?php echo $rs[$i]['title'] ; ?>&#10;文章ID:<?php echo $rs[$i]['id'] ; ?>&#10;点击数:<?php echo $rs[$i]['hits'] ; ?>&#10;评论数:<?php echo $rs[$i]['comments'] ; ?>&#10;顶:<?php echo $rs[$i]['good'] ; ?>&#10;踩:<?php echo $rs[$i]['bad'] ; ?>"><div style="height:20px;width:360px;overflow:hidden;">
            <?php if($rs[$i]['isPic'])echo '<img src="admin/images/image.gif" align="absmiddle">'?>
            <?php echo $rs[$i]['title'] ; ?></div>
          <div class="row-actions">
            <div class="do" style="display:none;">
          	<?php if($rs[$i]['status']=="0"||$rs[$i]['status']=="1"){  ?>
          	  <a href="<?php echo __ADMINCP__; ?>=article&do=status&id=<?php echo $rs[$i]['id'] ; ?>&s=<?php echo $rs[$i]['status'] ; ?>" target="iCMS_FRAME"><?php echo $type?$_ptxt[$rs[$i]['status']]:'转为草稿' ; ?></a> | 
           <?php } ?>
              <?php if($fid && $fid!=$rs[$i]['fid'] && $_GET['sub']!='on'){?>
              	<a href="<?php echo __ADMINCP__; ?>=article&do=delvlink&id=<?php echo $rs[$i]['id'] ; ?>&fid=<?php echo $fid ; ?>" onclick="return confirm('删除<?php echo $rs[$i]['title'] ; ?>此栏目的虚链接?');" target="iCMS_FRAME" class="del">删除</a>
              <?php }else{?>
	              <?php if ($F['mode'] && strstr($F['contentRule'],'{PHP}')===false && $rs[$i]['status']=="1" && empty($ourl)){  ?>
		              <a href="<?php echo __ADMINCP__; ?>=article&do=updateHTML&id=<?php echo $rs[$i]['id'] ; ?>" target="iCMS_FRAME" class="update"><?php echo file_exists($htmlurl)?"更新静态":"生成静态" ; ?></a> |
	              <?php }  ?>
              <!--<a href="<?php echo __ADMINCP__; ?>=article&do=copy&id=<?php echo $rs[$i]['id'] ; ?>"  class="copy">复制</a> | -->
              	<a href="<?php echo __ADMINCP__; ?>=article&do=add&id=<?php echo $rs[$i]['id'] ; ?>" class="edit" title="编辑此文章">编辑</a> | <!--<a href="<?php echo __ADMINCP__; ?>=push&do=add&id=<?php echo $rs[$i]['id'] ; ?>&TB_iframe=true&height=480&width=800" class="thickbox" title="推送">推送</a> | -->
	              <?php if($rs[$i]['status']=="1"){  ?>
	              	  <a href="<?php echo $rs[$i]['url'] ; ?>"  target="_blank" class="view" title="查看“<?php echo $rs[$i]['title'] ; ?>”">查看</a> | 
	              	  <a href="<?php echo $this->iCMS->config['publicURL']; ?>/comment.php?indexId=<?php echo $rs[$i]['id'] ; ?>&mId=0&sortId=<?php echo $rs[$i]['fid'] ; ?>" target="_blank" title="查看评论" class="comment">评论</a> | 
	              <?php }  ?>
	              <?php if($rs[$i]['status']=="2"){  ?>
	              	  <a href="<?php echo __ADMINCP__; ?>=article&do=recover&id=<?php echo $rs[$i]['id'] ; ?>" target="iCMS_FRAME" class="del" title='还原此文章'>还原</a> | 
	              	  <a href="<?php echo __ADMINCP__; ?>=article&do=del&id=<?php echo $rs[$i]['id'] ; ?>" target="iCMS_FRAME" class="del" onclick="return confirm('确定要删除“<?php echo HTML2JS($rs[$i]['title']) ; ?>”?');" title='永久删除此文章,此操作不可恢复'>永久删除</a>
	              <?php }else{?>
	              	 <a href="<?php echo __ADMINCP__; ?>=article&do=trash&id=<?php echo $rs[$i]['id'] ; ?>" target="iCMS_FRAME" class="del" title='移动此文章到回收站'>删除</a>
	              <?php } ?>
              <?php }  ?>
            </div>
          </div></td>
        <td><a href="<?php echo __ADMINCP__; ?>=article&do=manage&fid=<?php echo $rs[$i]['fid'] ; ?><?php echo $uri ; ?>"><?php echo $F['name'] ; ?></a></td>
        <td><?php echo $rs[$i]['editor'] ; ?></td>
        <td><?php if($ourl){  ?>
          <img src="admin/images/olink.gif" align="absmiddle" alt="外部链接">
          <?php }elseif($fid && $fid!=$rs[$i]['fid'] && empty($_GET['sub'])){  ?>
          虚
          <?php }else{ echo $rs[$i]['type']; }  ?>/<?php echo _int($rs[$i]['top']); ?></td>
        <td><?php echo get_date($rs[$i]['pubdate'],'Y-m-d H:i');?><br /><?php if ($F['mode'] && strstr($F['contentRule'],'{PHP}')===false && $rs[$i]['status']=="1" && empty($ourl)){
      	  echo file_exists($htmlurl)?"静态":"无静态" ;
      }else{
      	  echo '动态';
      }?></td>
      </tr>
      <?php }  ?>
    </tbody>
    <tr>
      <td colspan="7" class="pagenav"><?php echo $this->pagenav ; ?></td>
    </tr>
    <tr>
      <td colspan="7"><input type="checkbox" name="chkall" id="chkall" class="checkbox" onclick="iCMS.checkAll('prefix', this.form, 'id')" />
        <label for="chkall">全选</label>
        <input type="hidden" name="type" value="<?php echo $type;?>" />
        <select name="do" id="do" onChange="doaction(this);">
          <option value="empty">========批 量 操 作=======</option>
          <?php if($type){?>
          <?php if($_GET['act']||$type!='user'){?>
          <option value="passed"><?php echo $_ptxt[0];?></option>
          <option value="passTime"><?php echo $_ptxt[0]."&更新时间";?></option>
          <optgroup label="===================="></optgroup>
          <option value="passALL">全部<?php echo $_ptxt[0]; ?></option>
          <option value="TimeALL">全部更新时间</option>
          <option value="passTimeALL">全部<?php echo $_ptxt[0]."&更新时间" ; ?></option>
          <?php }else{?>
          <option value="cancel"><?php echo $_ptxt[1];?></option>
          <?php } ?>
          <?php }else{?>
          <option value="cancel">转为草稿</option>
          <?php } ?>
          <optgroup label="===================="></optgroup>
          <option value="order">更新排序</option>
          <option value="top">设置置顶权重</option>
          <option value="contentype">设置文章属性</option>
          <option value="thumb">提取缩略图</option>
          <option value="move">移动栏目</option>
          <option value="keyword">设置关键字</option>
          <option value="tag">设置标签</option>
          <option value="vlink">设置虚拟链接</option>
          <!--option value="push">推送</option-->
          <optgroup label="===================="></optgroup>
          <option value="dels">删除</option>
        </select>
        <input type="submit" class="submit" name="forumlinksubmit" value="提交"  /></td>
    </tr>
  </table>
  <div class="tipsdiv" id="contentype-div">
    <table class="adminlist" style="width:210px;">
      <thead>
        <tr>
          <th><span style="float:right;margin-top:4px;" class="close" parent="contentype-div"><img src="admin/images/close.gif" /></span>选择文章属性</th>
        </tr>
      </thead>
      <tr>
        <td class="tipsblock" style="padding-left:5px;"><select name="type" size="10" id="type" style="width:98%;">
            <option value="0">普通文章[type='0']</option>
            <?php echo contentype("article") ; ?>
          </select></td>
      </tr>
    </table>
  </div>
  </div>
  <div class="tipsdiv" id="move-div">
    <table class="adminlist" style="width:280px;">
      <thead>
        <tr>
          <th><span style="float:right;margin-top:4px;" class="close" parent="move-div"><img src="admin/images/close.gif" /></span>请选择栏目</th>
        </tr>
      </thead>
      <tr>
        <td class="tipsblock" style="padding-left:5px;"><select name="fid" id="fid" style="width:98%;">
            <option value="">请选择目标栏目</option>
            <?php echo $forum->select(0,0,1,'all') ; ?>
          </select></td>
      </tr>
    </table>
  </div>
  <div class="tipsdiv" id="keyword-div">
    <table class="adminlist" style="width:280px;">
      <thead>
        <tr>
          <th><span style="float:right;margin-top:4px;" class="close" parent="keyword-div"><img src="admin/images/close.gif" /></span>请输入关键字</th>
        </tr>
      </thead>
      <tr>
        <td class="tipsblock" style="padding-left:5px;"><textarea name="keyword" id="keyword" onKeyUp="textareasize(this)" class="tarea"></textarea>
          <br />
          追加
          <input name="pattern" type="radio" class="radio" value="addto" />
          替换
          <input name="pattern" type="radio" class="radio" value="replace" /></td>
      </tr>
    </table>
  </div>
  <div class="tipsdiv" id="top-div">
    <table class="adminlist" style="width:280px;">
      <thead>
        <tr>
          <th><span style="float:right;margin-top:4px;" class="close" parent="top-div"><img src="admin/images/close.gif" /></span>请输入权重</th>
        </tr>
      </thead>
      <tr>
        <td class="tipsblock" style="padding-left:5px;"><input type="text" name="top" class="txt" id="top" value=""/></td>
      </tr>
    </table>
  </div>
  <div class="tipsdiv" id="tag-div">
    <table class="adminlist" style="width:280px;">
      <thead>
        <tr>
          <th><span style="float:right;margin-top:4px;" class="close" parent="tag-div"><img src="admin/images/close.gif" /></span>请输入标签</th>
        </tr>
      </thead>
      <tr>
        <td class="tipsblock" style="padding-left:5px;"><textarea name="tag" id="tag" onKeyUp="textareasize(this)" class="tarea"></textarea>
          <br />
          追加
          <input name="pattern" type="radio" class="radio" value="addto" />
          替换
          <input name="pattern" type="radio" class="radio" value="replace" /></td>
      </tr>
    </table>
  </div>
  <div class="tipsdiv" id="vlink-div">
    <table class="adminlist" style="width:280px;">
      <thead>
        <tr>
          <th><span style="float:right;margin-top:4px;" class="close" parent="vlink-div"><img src="admin/images/close.gif" /></span>请选择关联栏目</th>
        </tr>
      </thead>
      <tr>
        <td class="tipsblock" style="padding-left:5px;"><select name="vlink[]" size="10" multiple="multiple" id="vlink" style="width:98%;">
            <?php echo $forum->select(0,0,1,'all') ; ?>
          </select>
          <br />
          追加
          <input name="pattern" type="radio" class="radio" value="addto" />
          替换
          <input name="pattern" type="radio" class="radio" value="replace" /></td>
      </tr>
    </table>
  </div>
</form>
</body></html>