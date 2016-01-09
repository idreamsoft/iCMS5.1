<?php
/**
 * @package iCMS
 * @copyright 2007-2010, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */
!defined('iPATH') && exit('What are you doing?');
?>
<script type="text/javascript" src="<?php echo $this->iCMS->config['publicURL'];?>/editor/fckeditor.js"></script>
<script type="text/javascript" src="<?php echo $this->iCMS->config['publicURL'];?>/editor/editor_plugin.js"></script>
<script type="text/javascript">
$(function(){
	$("#title").focus();
	$("#savearticle").submit(function(){
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
		var ed = FCKeditorAPI.GetInstance('icms_editor') ;
		if(ed.GetXHTML( true )==''){
			alert("内容不能为空!");
			ed.Focus();
			return false;
		}
	}); 
	$("#fid").change(function(){
		$.get("<?php echo __USERCP__; ?>=ajax",{'do':'contentAttr','fid':this.value},
		  function(data){
			$("#tabs-attr").html(data).show();
		  }
		); 
	});
	$("#pic").click(function(){
		iCMS.showDialog("<?php echo __USERCP__; ?>=dialog&do=viewPic",$('[name=pic]').val(),'查看缩略图');
	});

});
</script>

<div class="pwrap">
  <div class="phead">
    <div class="phead-inner">
      <div class="phead-inner">
        <h3 class="ptitle"><span><?php echo empty($id)?'添加':'编辑' ; ?>文章</span></h3>
      </div>
    </div>
  </div>
  <div class="pbody">
    <form action="<?php echo __USERCP__; ?>=article" method="post" enctype="multipart/form-data" name="savearticle" id="savearticle"  target="iCMS_FRAME">
      <input type="hidden" name="action" value="save" />
      <table class="adminlist" border="0" cellspacing="0" cellpadding="0" style="margin-top:0px;width:100%;">
        <tr>
          <td class="td40" style="width:50px;">栏目：</td>
          <td colspan="3"><select name="fid" id="fid" style="width:auto;"><?php if($cata_option){?>
              <option value="0"> == 请选择所属栏目 == </option>
              <?php echo $cata_option;}else{  ?>
              <option value="0"> == 暂无栏目请联系管理员 == </option>
              <?php }  ?>
            </select></td>
        </tr>
        <tr>
          <td class="td40">标题：</td>
          <td colspan="3"><input type="text" name="title" class="txt" id="title" value="<?php echo $rs['title'] ; ?>" style="width:600px"/></td>
        </tr>
        <tr>
          <td class="td40">出处：</td>
          <td colspan="3"><input type="text" name="source" class="txt" id="source" value="<?php echo $rs['source'] ; ?>" style="width:600px" />
          		<br />转载请注明出处
          		</td>
        </tr>
        <tr>
          <td class="td40">作者：</td>
          <td><input type="text" name="author" class="txt" id="author" value="<?php echo $rs['author'] ; ?>" style="width:600px" /></td>
        </tr>
        <tr>
          <td class="td40">关键字：</td>
          <td colspan="3"><input name="keywords" class="txt" type="text" id="keywords" value="<?php echo $rs['keywords'] ; ?>" style="width:600px"/></td>
        </tr>
        <tr>
          <td class="td40">标签：</td>
          <td colspan="3"><input name="tags" class="txt" type="text" id="tags" style="width:600px" value="<?php echo $rs['tags'] ; ?>" />
            <br />
            多个标签请用,格开 <a href="javascript:void(0)" id="getTAG" />从最近使用标签中选择</a></td>
        </tr>
        <tr>
          <td class="td40">摘要：</td>
          <td colspan="3"><textarea name="description" id="description" onKeyUp="textareasize(this)" class="tarea" style="width:600px; height:120px;"><?php echo $rs['description'] ; ?></textarea></td>
        </tr>
        <tr>
          <td class="td40">缩略图：</td>
          <td colspan="3"><div id="pic1" style="display:<?php echo $rs['pic']?'none':'block'; ?>"><input id="picfile" name="picfile" type="file" style="width:600px;" /><span id="cpic1" style="display:<?php echo $rs['pic']?'':'none'; ?>">[<a href="javascript:iCMS.SH('pic2','pic1');">取消</a>]</span></div>
          		<div id="pic2" style="display:<?php echo $rs['pic']?'block':'none'; ?>"><a id="pic" href="javascript:void(0);" title="点击查看图片"><?php echo $rs['pic'] ; ?></a><input name="pic" type="text" value="<?php echo $rs['pic'] ; ?>" class="txt" style="display:none;"/> [<a href="javascript:iCMS.SH('pic1','pic2');">重新上传</a>] [<a href="<?php echo __USERCP__; ?>=article&do=delpic&id=<?php echo $id ; ?>&fp=<?php echo $rs['pic'] ; ?>" target="iCMS_FRAME">删除</a>]</div>
          </td>
        </tr>
        <tr>
          <td colspan="4" style="margin:0; padding-top:10px;">
              <textarea id="icms_editor" class="icms_editor" name="body" style="display:none"><?php echo $rs['body'];?></textarea>
              <input type="hidden" id="icms_editor___Config" value="" style="display:none" />
              <iframe id="iEditor___Frame" src="<?php echo $this->iCMS->config['publicURL'];?>/editor/fckeditor.html?InstanceName=icms_editor&Toolbar=User" width="100%" height="500" frameborder="0" scrolling="no"></iframe>
            </td>
        </tr>
        <tbody id="tabs-attr" style="display:none;">
        <?php if($contentAttr)foreach((array)$contentAttr AS $caKey=>$ca){?>
        <tr>
          <td class="td80"><?php echo $ca['name'] ?>:</td>
          <td class="rowform" colspan="3"><textarea  rows="6" onkeyup="textareasize(this)" name="metadata[<?php echo $ca['key'] ?>]" cols="50" class="tarea"><?php echo $rs['metadata'][$ca['key']];?></textarea></td>
        </tr>
        <?php }?>
        </tbody>
        <tr>
          <td colspan="4" align="center"><input name="aid" type="hidden" id="aid" value="<?php echo $id ; ?>" />
            <input name="REFERER" type="hidden" id="REFERER" value="<?php echo $REFERER ; ?>" />
            <input name="do" type="hidden" id="do" value="save" />
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
