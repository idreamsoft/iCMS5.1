<?php /**
 * @package iCMS
 * @copyright 2007-2010, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */
!defined('iPATH') && exit('What are you doing?'); 
admincp::head();
?>
<div class="position">当前位置：管理中心&nbsp;&raquo;&nbsp;文章管理&nbsp;&raquo;&nbsp;<?php echo empty($id)?'添加':'编辑' ; ?>文章</div>
<script type="text/javascript" src="<?php echo $this->iCMS->config['publicURL'];?>/editor/fckeditor.js"></script> 
<script type="text/javascript" src="<?php echo $this->iCMS->config['publicURL'];?>/editor/editor_plugin.js"></script> 
<script type="text/javascript">
$(function(){
	$("#title").focus();
	$("#savearticle").submit(function(){
		if($("#fid option:selected").attr("value")=="0"){
			$("a[tid=base]").click();
			alert("请选择所属栏目");
			$("#fid").focus();
			return false;
		}
		if($("#title").val()==''){
			$("a[tid=base]").click();
			alert("标题不能为空!");
			$("#title").focus();
			return false;
		}

		<?php if($isNoCopy){?>
		if($("#url").val()==''){
			var n=$(".BP:eq(0) option:first").val();
			var ed = FCKeditorAPI.GetInstance('icms_editor_'+n) ;
			if(ed.GetXHTML( true )==''){
				$("a[tid=base]").click();
				alert("第"+n+"页内容不能为空!");
				$('#iBody_'+n).show();
				$(".BP").val(n);
				ed.Focus();
				return false;
			}
		}
		<?php }?>
		var rel=$("#related option");
		for (var i = 0; i < rel.length; i++) {
			$(rel[i]).attr("selected","selected");
		}
	}); 
	$("#keywordToTag").click( function(){
		$("#keywords").toggle();
	});

<?php if($this->iCMS->config['keywordToTag']=="1" && !$id){  ?>
	$("#keywordToTag").click().attr("checked","checked");
	$("#keywords").val("");
<?php }  ?>
	
	$(".selectdefault").click(function(){
		var offset 		= $(this).offset();
		var snapTop 	= offset.top+25;
		var snapLeft 	= offset.left;
//		alert(snapTop+"-"+snapLeft);
		var def			= $(".tipsdiv");
		var inid		= $(this).attr("to");
		if(inid=="pic"){
			$("#dtitle").html("选择");
			$("#defaultbody").empty().html($("#picmenu").html());
		}else{
			$.post("<?php echo __ADMINCP__; ?>=ajax",{'do':'default','param':inid},
			  function(data){
				$("#defaultbody").empty().html(data);
			  }
			); 
		}
		def.hide().addClass("selectdefaultdiv")
		.css({"top" : snapTop, "left" : snapLeft,"width":"120"})
		.slideDown("slow");
	});
	$("#getTAG").click(function(){
		var offset 		= $(this).offset();
		var snapTop 	= offset.top+25;
		var snapLeft 	= offset.left;
		var def			= $(".tipsdiv");
		$("#dtitle").html("标签 (<span onclick='javascript:getAllTag();' style='cursor:hand'>全选</span>)");
		$("#defaultbody").empty().html("标签获取中...请稍候!");
		$.post("<?php echo __ADMINCP__; ?>=ajax",{'do':'tag'},
			  function(data){
				$("#defaultbody").html(data);
			  }
		);
		def.hide().addClass("selectdefaultdiv")
		.css({"top" : snapTop, "left" : snapLeft,"width":"175"})
		.slideDown("slow");
	});
	$(".close").click(function(){
	    $(".tipsdiv").slideUp("slow");
	});
	$("#pic").dblclick(function(){
		viewPic('pic');
	});
	$(".BP").change(function(){
		$(".icmsEd").hide();
		$('#iBody_'+this.value).show();
		$(".BP").val(this.value);
		iCMS.editor_id='icms_editor_'+this.value;
		FCKeditorAPI.GetInstance(iCMS.editor_id).Focus();
	});
	$("#fid").change(function(){
		$.get("<?php echo __ADMINCP__; ?>=ajax",{'do':'contentAttr','fid':this.value},
		  function(data){
			$("#tabs-attr").html(data);
		  }
		); 
	});
	
});

function inTag(str,id){
	var val		= $("#tags").val();
	var cb		= $("#gt_"+id);
	var vArray	= val.split(',');
	var index 	= vArray.indexOf(str);

	if(cb.attr('checked')){
		vArray.push(str);
	}else{
		vArray.splice(index, 1); 
	}
	if(val=='') vArray.shift();
	$("#tags").val(vArray.join(','));
}
function getAllTag(){
	$("input[id^='gt_']") .each(function(i){
		$(this).click();
	}); 
}
function indefault(v,id){
	var val	=$("#"+id).val();
	if(val==""){
		val=v;
	}else{
		val+=" "+v;
	}
	$("#"+id).val(val);
}
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
function multiUpload(){
	iCMS_WINDOW_<?php echo iCMSKEY?> = window.open("<?php echo __ADMINCP__; ?>=dialog&do=multiUpload","iCMS_WINDOW_<?php echo iCMSKEY?>","menubar=no,location=no,resizable=no,scrollbars=yes,status=no,width=760,height=280,left=120, top=60");
}
function addPage(){
	var len	= parseInt($(".BP option:last").val()),id	= 'iBody_'+len,n	= len+1;
	$("#"+id).hide().after('<div id="iBody_'+n+'" class="icmsEd"><textarea id="icms_editor_'+n+'" class="icms_editor" name="body[]"></textarea></div>');
	$(".BP").append('<option value="'+n+'">第 '+n+' 页</option>').val(n);
	iCMS.editor_id='icms_editor_'+n;
	var ed = new FCKeditor(iCMS.editor_id);
	ed.BasePath='<?php echo $this->iCMS->config['publicURL'];?>/';
	ed.Height = '500' ;
	ed.ReplaceTextarea() ;
//	ed.Focus();
}
function delPage(){
	if($(".BP:eq(0) option").length==1) return;
	var s	= $(".BP option:selected");
	var n	= s.val(),i	= s.attr("index");
	$('#iBody_'+n).remove();
	$(".BP option:selected").remove();
	var a	= $(".BP");
	a[1].selectedIndex	= a[0].selectedIndex	= (i==0?i:i-1);
	$('#iBody_'+a.val()).show();
	iCMS.editor_id='icms_editor_'+a.val();
	FCKeditorAPI.GetInstance(iCMS.editor_id).Focus();
}
function pagebreak(){
	FCKeditorAPI.GetInstance(iCMS.editor_id).Commands.GetCommand("PageBreak").Execute() ;
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
        <li>点击ID可查看该文章</li>
        <li>清除链接功能会清除所有链接，慎用</li>
      </ul></td>
  </tr>
</table>

<div class="tabs">
	<ul>
		<li class="active" ref="tabs-base">基本信息</li>
		<li ref="tabs-publish">发布设置</li>
		<li ref="tabs-attr">附加属性</li>
	</ul>
</div>
<form action="<?php echo __ADMINCP__; ?>=article" method="post" enctype="multipart/form-data" name="savearticle" id="savearticle"  target="iCMS_FRAME">
  <input type="hidden" name="action" value="save" />
  <table class="adminlist" border="0" cellspacing="0" cellpadding="0" style="margin-top:0px;">
    <tbody id="tabs-base">
      <tr>
        <td class="td40" style="width:50px;">栏目：</td>
        <td><?php if($cata_option){  ?>
          <select name="fid" id="fid" style="width:auto;">
            <option value="0"> == 请选择所属栏目 == </option>
            <?php echo $cata_option;}else{  ?>
            <select name="forum" id="forum" onclick="window.location.replace('<?php echo __ADMINCP__; ?>=forums&do=add');">
            <option value="0"> == 暂无栏目请先添加 == </option>
            <?php }  ?>
          </select></td>
        <td class="td40" style="width:50px;">属性：</td>
        <td><select name="type" id="type">
            <option value="0">普通文章[type='0']</option>
            <?php echo contentype("article",$rs['type']) ; ?>
          </select></td>
      </tr>
      <tr>
        <td class="td40">标题：</td>
        <td colspan="3"><input type="text" name="title" class="txt" id="title" value="<?php echo $rs['title'] ; ?>" style="width:560px"/></td>
          </td>
      </tr>
      <tr>
        <td class="td40">短标题：</td>
        <td colspan="3"><input name="stitle" class="txt" id="stitle" value="<?php echo $rs['stitle'] ; ?>"  style="width:560px" type="text"/></td>
      </tr>
      <tr>
        <td class="td40">出处：</td>
        <td colspan="3"><input type="text" name="source" class="txt" id="source" value="<?php echo $rs['source'] ; ?>" style="width:560px" />
          <button type="button" class="selectdefault button" hidefocus=true to="source"><span>预 设</span></button></td>
      </tr>
      <tr>
        <td class="td40">作者：</td>
        <td><input type="text" name="author" class="txt" id="author" value="<?php echo $rs['author'] ; ?>" style="width:280px" />
          <button type="button" class="selectdefault button" hidefocus=true to="author"><span>预 设</span></button></td>
        <td class="td40">编辑：</td>
        <td><input name="editor" class="txt" type="text" id="editor" value="<?php echo $rs['editor'] ; ?>" style="width:280px"/>
          <button type="button" class="selectdefault button" hidefocus=true to="editor"><span>预 设</span></button></td>
      </tr>
      <tr>
        <td class="td40">缩略图：</td>
        <td colspan="3"><input id="pic" name="pic" type="text" value="<?php echo $rs['pic'] ; ?>" class="txt" style="width:560px"/>
          <button type="button" class="selectdefault button" hidefocus=true to="pic"><span>选 择</span></button>
          <div id="picmenu" style="display:none;">
          <ul>
          <li onClick="iCMS.showDialog('<?php echo __ADMINCP__;?>=dialog&do=Aupload','pic','本地上传',400,150);">本地上传</li>
          <li onClick="iCMS.showDialog('<?php echo __ADMINCP__;?>=dialog&do=file&click=file&type=gif,jpg,png,bmp,jpeg','pic','从网站选择');">从网站选择</li>
          <li onClick="viewPic('pic');">查看缩略图</li>
          <li onClick="crop('pic');">剪裁图片</li>
          </ul>
          </div></td>
      </tr>
      <tr>
        <td class="td40">关键字：</td>
        <td colspan="3"><input name="keywords" class="txt" type="text" id="keywords" value="<?php echo $rs['keywords'] ; ?>" style="width:560px"/>
          <br />
          <input name="keywordToTag" type="checkbox" class="checkbox" id="keywordToTag" value="1" />
          将标签转为关键字.多个关键字请用,格开</td>
      </tr>
      <tr>
        <td class="td40">标签：</td>
        <td colspan="3"><input name="tags" class="txt" type="text" id="tags" style="width:600px" value="<?php echo $rs['tags'] ; ?>" />
          <br />
          多个标签请用,格开 <a href="javascript:void(0)" id="getTAG" />从最近使用标签中选择</a></td>
      </tr>
      <tr>
        <td class="td40">副标题：</td>
        <td colspan="3"><input name="subtitle" class="txt" id="subtitle" value="<?php echo $rs['subtitle'] ; ?>"  style="width:560px" type="text"/></td>
      </tr>
      <tr>
        <td class="td40">摘要：</td>
        <td colspan="3"><textarea name="description" id="description" onKeyUp="textareasize(this)" class="tarea" style="width:560px; height:120px;"><?php echo $rs['description'] ; ?></textarea></td>
      </tr>
      <tr>
        <td colspan="4"><input type="submit" value="提交" class="submit" />
          <input type="button" name="formatbutton_img" value="自动排版/清理无用代码" onClick="iCMSed.cleanup(iCMS.editor_id)" class="button">
          <input type="button" value="批量上传" onClick="multiUpload();" class="button">
          <input type="button" value="插入图片" onClick="iCMS.showDialog('<?php echo __ADMINCP__;?>=dialog&do=file&click=file&type=gif,jpg,png,bmp,jpeg&from=editor','iCMSEDITOR','从网站选择');" class="button">
      </tr>
      <?php if($isNoCopy){?>
      <tr>
        <td colspan="4">
          <select class="BP">
            <option value="1">第 1 页</option>
          </select>
          <img src="admin/images/addpage.png" align="absmiddle" title="新增一页" onClick="addPage();"/>
          <img src="admin/images/delpage.png" align="absmiddle" title="删除当前页" onClick="delPage();"/>
          <input name="remote" type="checkbox" class="checkbox" id="remote" value="1" <?php if($this->iCMS->config['remote']=="1")echo 'checked="checked"'  ?>/>
          下载远程图片
          <input name="dellink" type="checkbox" id="dellink" value="1" class="checkbox"/>
          清除链接
          <input name="autopic" type="checkbox" class="checkbox" id="autopic" value="1" <?php if($this->iCMS->config['autopic']=="1")echo 'checked="checked"'  ?>/>
          提取第一个图片为缩略图
          <input name="draft" type="checkbox" class="checkbox" id="draft" value="1" <?php if($rs['status']=="0" || member::$Rs->groupid=="3")echo 'checked="checked"'  ?>/>
          存为草稿
          <?php if($this->iCMS->config['autoformat']=="1"){ ?>
          <input name="autoformat" type="checkbox" class="checkbox" id="autoformat" value="1" />
          关闭自动排版
          <?php }?>
          <?php if($this->iCMS->config['AutoPage']=="1"){ ?>
          <input name="AutoPage" type="checkbox" class="checkbox" id="AutoPage" value="1" />
          关闭自动分页
          <?php }?>
          </td>
      </tr>
      <tr>
        <td colspan="4" style="margin:0; padding:0;"><div id="iBody_1" class="icmsEd">
            <textarea id="icms_editor_1" class="icms_editor" name="body[]" style="display:none"><?php echo $rs['body'];?></textarea>
            <input type="hidden" id="icms_editor_1___Config" value="" style="display:none" />
            <iframe id="iEditor_1___Frame" src="<?php echo $this->iCMS->config['publicURL'];?>/editor/fckeditor.html?InstanceName=icms_editor_1&Toolbar=Default" width="100%" height="500" frameborder="0" scrolling="no"></iframe>
          </div></td>
      </tr>
      <tr>
        <td colspan="4" align="right" style="width:auto;"><select class="BP">
            <option value="1">第 1 页</option>
          </select>
          <img src="admin/images/addpage.png" align="absmiddle" title="新增一页" onClick="addPage();"/>
          <img src="admin/images/delpage.png" align="absmiddle" title="删除当前页" onClick="delPage();"/>
          <img src="admin/images/pagebreak.gif" align="absmiddle" title="插入分页符" onClick="pagebreak();"/>
          </td>
      </tr>
      <?php }?>
    </tbody>
    <tbody id="tabs-publish" style="display:none;">
      <tr>
        <td class="td40">相关文章：</td>
        <td colspan="2"><select id='related' name="related[]" size='10' style="width:100%" multiple="multiple">
            <?php 
        	if($rs['related']){
	        	$relRs=iCMS_DB::getArray("SELECT `id`, `title` FROM `#iCMS@__article` WHERE `id`IN (".$rs['related'].")");
	        	foreach((array)$relRs AS $rel){
	        		$relatedArray[$rel['id']]=$rel['title'];
	        	}
	        	$relIds=explode(',',$rs['related']);
	        	foreach($relIds AS $relId){
	        		echo '<option value="'.$relId.'">'.$relatedArray[$relId].'</option>';
	        	}
        	}
        	  ?>
          </select></td>
        <td><input type="button" onclick="del(this.form.related)" value="删除×" tabindex="13" class="button"/>
          <br/>
          <br/>
          <input type="button" onclick="moveUp(this.form.related)" value="上移∧" tabindex="13" class="button"/>
          <br/>
          <br/>
          <input type="button" onclick="moveDown(this.form.related)" value="下移∨" tabindex="13" class="button"/>
          <br/>
          <br/>
          <input type="button" onclick="iCMS.showDialog('<?php echo __ADMINCP__; ?>=dialog&do=article','related','查找相关文章',760,520);" value="查找..." tabindex="13" class="button"/></td>
      </tr>
      <tr>
        <td class="td40">排序：</td>
        <td colspan="3"><input id="orderNum" class="txt" value="<?php echo _int($rs['orderNum']) ; ?>" name="orderNum" type="text"/></td>
      </tr>
      <tr>
        <td class="td40">虚链接：</td>
        <td colspan="3"><select name="vlink[]" size="10" multiple="multiple" id="vlink">
            <?php echo $forum->select(0,0,1,'all') ; ?>
          </select>
          <script language="javascript" type="text/javascript"><?php if(strpos($rs['vlink'], ",")){  ?>var type='<?php echo $rs['vlink'] ; ?>';$('#vlink').val(type.split(','));<?php }else{  ?>$('#vlink').val(<?php echo (int)$rs['vlink'] ; ?>);<?php }  ?></script> 
          <br />
          按住Ctrl可多选 </td>
      </tr>
      <tr>
        <td class="td40">置顶权重：</td>
        <td colspan="3"><input id="top" class="txt" value="<?php echo _int($rs['top']) ; ?>" name="top" type="text"/></td>
      </tr>
      <tr>
        <td class="td40">发布时间：</td>
        <td colspan="3"><input id="pubdate" class="txt datepicker" value="<?php echo $rs['pubdate'] ; ?>" name="pubdate" type="text" style="width:230px"/></td>
      </tr>
      <tr>
        <td class="td40">模板：</td>
        <td colspan="3"><input id="template" class="txt" value="<?php echo $rs['tpl'] ; ?>" name="template" type="text" size="50" />
          <input type="button" id="selecttpl" class="button" value="浏览" onclick="iCMS.showDialog('<?php echo __SELF__ ;  ?>?mo=dialog&do=template&click=file&type=htm','template','选择模板');" hidefocus=true /></td>
      </tr>
      <tr>
        <td class="td40">自定链接：</td>
        <td colspan="3"><input name="clink" class="txt" type="text" id="clink" value="<?php echo $rs['clink'];?>" />
          <br />
          只能由英文字母、数字或_-组成(不支持中文),留空则自动以标题拼音填充</td>
      </tr>
      <tr>
        <td class="td40">外部链接：</td>
        <td colspan="3"><input name="url" class="txt" type="text" id="url" size="50" value="<?php echo $rs['url'] ; ?>" />
          不填写请留空.</td>
      </tr>
    </tbody>
    <tbody id="tabs-attr" style="display:none;">
    <?php if($contentAttr)foreach((array)$contentAttr AS $caKey=>$ca){?>
    <tr>
      <td class="td40"><?php echo $ca['name'] ?>:</td>
      <td colspan="3"><textarea  rows="6" onkeyup="textareasize(this)" name="metadata[<?php echo $ca['key'] ?>]" cols="50" class="tarea"><?php echo $rs['metadata'][$ca['key']];?></textarea></td>
   </tr>
    <?php }?>
    </tbody>
    <tr>
      <td colspan="4" align="center"><input name="aid" type="hidden" id="aid" value="<?php echo $id ; ?>" />
        <input name="userid" type="hidden" id="userid" value="<?php echo $rs['userid'] ; ?>" />
        <input name="postype" type="hidden" id="postype" value="<?php echo $rs['postype'] ; ?>" />
        <input name="iscopy" type="hidden" id="postype" value="<?php echo $isNoCopy?'0':'1'?>" />
        <input name="REFERER" type="hidden" id="REFERER" value="<?php echo $REFERER ; ?>" />
        <input name="do" type="hidden" id="do" value="save" />
        <input type="submit" value="提交" class="submit" />
        &nbsp;&nbsp;
        <input type="reset" value="重置" class="submit" /></td>
    </tr>
  </table>
</form>
</body></html>