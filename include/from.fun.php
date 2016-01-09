<?php
/**
 * @package iCMS
 * @copyright 2007-2010, iDreamSoft
 * @license http://www.idreamsoft.cn iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */
function FormArray($mId,$F,$rs=array(),$isUser=false){
	//字段定义
    $rst=iCMS_DB::getArray("SELECT * FROM `#iCMS@__field` where (mid='$mId' OR mid='0')");
    foreach($rst AS $key=>$a){
    	$FA[$a['field']]=$a;
    }
	//遍历传入字段数组
	if($F)foreach($F AS $key=>$field){
		$HA[$key] = form($FA[$field],$rs,$isUser);
	}
	return $HA;
}
//表单
function form($A,$rs=array(),$isUser=false){
	$id		= $A['field'];
	$val	= $rs[$id]?$rs[$id]:'';
	$mId	= $rs['mid'];
	$mName	= $rs['mName'];
	$option	= unserialize($A['option']);
	$optStr	= $option[$A['type']];
	if($optStr){
		$_optArray	= explode("\n",$optStr);
		foreach($_optArray AS $k=>$optA){
			list($index,$choice)= explode("=",$optA);
			$optArray[trim($index)]=trim($choice);
		}
	}
	if($A['hidden']){
		$FORM['hidden']='<input type="hidden" name="content['.$id.']" id="'.$id.'" value="'.$val.'" />';	
	}else{
		//判读是否为特殊字段
		if(in_array($A['field'],array('fid','type','vlink'))){
			switch($A['field']){
				case "fid":
			        $forum 			= new forum();
			        $cata_option	= $isUser?$forum->user_select($val,0,1,1,$mId):$forum->select($val,0,1,NULL,$mId);
					if($cata_option){
						$html='<select name="content[fid]" id="fid" style="width:auto;">';
						$html.='<option value="0"> == 请选择所属栏目 == </option>';
	          			$html.=$cata_option;
	          		}else{
	          			if($isUser){
		          	  		$html='<select name="content[fid]" id="fid">';
		          			$html.='<option value="0"> == 暂无栏目 == </option>';
	          			}else{
		          	  		$html='<select name="content[fid]" id="fid" onclick="window.location.replace(\''.__ADMINCP__.'=forums&do=add\');">';
		          			$html.='<option value="0"> == 暂无栏目请先添加 == </option>';
	          			}
	          		}
	        		$html.='</select>';
				break;
				case "type":
					$html='<select name="content[type]"id="type">';
		          	$html.='<option value="0">默认属性[type=\'0\']</option>';
		          	$html.=contentype($mName,$val);
		        	$html.='</select>';
	          	break;
				case "vlink":
			        $forum	= new forum();
					$html	= '<select name="content[vlink][]" size="10" multiple="multiple" id="vlink">';
	          		$html.=$isUser?$forum->user_select($val,0,1,1,$mId):$forum->select($val,0,1,'all',$mId);
	          		$html.='</select>';
	          		$html.=selected($val,'vlink','js');
	          	break;
	          //	case in_array($A['field'],array('hits','digg','comments','status','postype')):	break;
			}
		}else{
			switch($A['type']){
				case in_array($A['type'],array('number','text','email','url')):	
					$html='<input type="text" name="content['.$id.']" class="txt" id="'.$id.'" value="'.$val.'" />';
				break;
				case "radio":
					foreach((array)$optArray AS $value=>$text){
						$checked= ($value==$val) ? ' checked="checked"':'';
						$html.=' <input type="radio" name="content['.$id.']" class="radio" id="'.$id.'" value="'.$value.'" /> '.$text;
					}
				break;
				case "checkbox":
					$valArray=explode(',',$val);
					foreach((array)$optArray AS $value=>$text){
						$checked= in_array($value,$valArray) ? ' checked="checked"':'';
						$html.=' <input type="checkbox" name="content['.$id.'][]" class="checkbox" id="'.$id.'.'.$value.'" value="'.$value.'" '.$checked.'/> '.$text;
					}
				break;
				case "textarea":
					$html='<textarea name="content['.$id.']" id="'.$id.'" onKeyUp="textareasize(this)" class="tarea">'.$val.'</textarea>';
				break;
//				case "editor":
//					$html='<script type="text/javascript" src="editor/fckeditor.js"></script> 
//<script type="text/javascript" src="admin/js/plus_format_fck.js"></script><select class="BP">
//            <option value="1">第 1 页</option>
//          </select><input type="button" value="新增一页" onClick="newBody();" class="button">
//<iframe id="rtf" style="width: 0px; height: 0px;" marginwidth="0" marginheight="0" src="about:blank" scrolling="no"></iframe>
//          <label for="x_paste"></label>
//          <script>rtf.document.designMode="On";</script>
//          <input type="button" name="formatbutton" value="粘贴排版" onclick="trans(iCMS.eId);" class="button">
//          <input type="button" name="formatbutton_img" value="自动排版" onClick="FormatImages(iCMS.eId)" class="button">
//          <input type="button" value="批量上传" onClick="multiUpload();" class="button">
//          <input type="button" value="插入图片" onClick="iCMS.showDialog(\''.__ADMINCP__.'=dialog&do=file&click=file&type=gif,jpg,png,bmp,jpeg&from=editor\',\'iCMSEDITOR\',\'从网站选择\');" class="button">
//<div id="iBody_1" class="nb">
//            <textarea id="iEditor_1" name="content['.$id.'][]" cols="80" rows="20" style="display:none">'.$val.'</textarea>
//            <input type="hidden" id="iEditor_1___Config" value="" style="display:none" />
//            <iframe id="iEditor_1___Frame" src="./editor/fckeditor.html?InstanceName=iEditor_1&amp;Toolbar=Default" width="100%" height="500" frameborder="0" scrolling="no"></iframe>
//          </div>';
//				break;
				case "editor":
					global $iCMS;
					include(iPATH."include/fckeditor.php");
					$editor = new FCKeditor('content['.$id.']') ;
					$editor->BasePath= $iCMS->config['publicURL'];
					$editor->ToolbarSet	= $isUser?'User':'Default';
					$editor->Value= $val;
					//$html='<script type="text/javascript" src="'.$iCMS->config['publicURL'].'/ui/editor/fckeditor.js"></script>';
					$html=$editor->CreateHtml();
				break;
				case "select":
					$html='<select name="content['.$id.']" id="'.$id.'" style="width:auto;">';
					$html.='<option value="0"> == 不选择 == </option>';
					foreach((array)$optArray AS $value=>$text){
						$selected= ($value==$val) ? ' selected="selected"':'';
						$html.='<option value="'.$value.'"'.$selected.'>'.$text.'</option>';
					}
					$html.='</select>';
				break;
				case "multiple":
					$html='<select name="content['.$id.'][]" id="'.$id.'" style="width:auto;" size="10" multiple="multiple">';
					$html.='<option value="0"> == 不选择 == </option>';
					$valArray=explode(',',$val);
					foreach((array)$optArray AS $value=>$text){
						$selected= in_array($value,$valArray) ? ' selected="selected"':'';
						$html.='<option value="'.$value.'"'.$selected.'>'.$text.'</option>';
					}
					$html.='</select>';
				break;
				case "calendar":
					$html='<input name="content['.$id.']" class="txt datepicker" value="'.get_date($val,'Y-m-d H:i:s').'" id="'.$id.'" type="text"/>';
				break;
//				case "image":
//					$html='<input name="content['.$id.']" id="image_'.$id.'" type="text" value="'.$val.'" class="txt" style="width:450px"/>';
//					$html.='<button type="button" class="selectdefault button" hidefocus=true to="image_'.$id.'"><span>选 择</span></button>';
//					$html.='<div id="image_'.$id.'_menu" style="display:none;">';
//					$html.='<ul>';
//					$html.='<li onClick="iCMS.showDialog(\''.__ADMINCP__.'=dialog&do=Aupload\',\'image_'.$id.'\',\'本地上传\',400,150);">本地上传</li>';
//					$html.='<li onClick="iCMS.showDialog(\''.__ADMINCP__.'=dialog&do=file&click=file&type=gif,jpg,png,bmp,jpeg\',\'image_'.$id.'\',\'从网站选择\');">从网站选择</li>';
//					$html.='<li onClick="viewPic(\'image_'.$id.'\');">查看缩略图</li>';
//					$html.='<li onClick="crop(\'image_'.$id.'\');">剪裁图片</li>';
//					$html.='</ul></div>';
//				break;
				case "upload":
					$html='<div id="'.$id.'1" style="display:'.($val?'none':'block').';"><input id="'.$id.'file" name="content_upload_'.$id.'" type="file" style="width:600px;" /><span id="c'.$id.'1" style="display:'.($val?'':'none').'">[<a href="javascript:iCMS.SH(\''.$id.'2\',\''.$id.'1\');">取消</a>]</span></div>
          		<div id="'.$id.'2" style="display:'.($val?'block':'none').'"><a class="content_viewPic" ref="'.$id.'" href="javascript:void(0);" title="点击查看图片">'.$val.'</a><input name="content['.$id.']" type="text" value="'.$val.'" class="txt content_upload_'.$id.'" style="display:none;"/> [<a href="javascript:iCMS.SH(\''.$id.'1\',\''.$id.'2\');">重新上传</a>] [<a href="'.($isUser?__USERCP__:__ADMINCP__).'=content&do=delpic&mid='.$mId.'&table='.$mName.'&id='.$rs['id'].'&field='.$id.'&fp='.$val.'" target="iCMS_FRAME">删除</a>]</div><script type="text/javascript">	$(".content_viewPic").click(function(){
		var path	=$(\'.content_upload_\'+$(this).attr(\'ref\')).val();
		iCMS.showDialog("'.($isUser?__USERCP__:__ADMINCP__).'=dialog&do=viewPic",path,\'查看图片\');
	});</script>';
				break;
			}
		}
		if($A['show'] || !$isUser){
			$FORM['general']=array(
				'id'=>$id,
				'label'=>$A['name'],
				'description'=>$A['description'],
				'html'=>$html
			);
		}
	}

	if(!model::isDefField($id)){
		$valal='$("#'.$id.'").val()';
		//验证
		switch($A['validate']){
			case "0"://不能为空
				if($A['type']=="editor"){
					$js	= 'var '.$id.'_Editor = FCKeditorAPI.GetInstance(\'content['.$id.']\') ;
					if('.$id.'_Editor.GetXHTML( true )==""){
						alert("'.$A['name'].'不能为空!");
						'.$id.'_Editor.focus();
						return false;
					}';
				}else{
					$js	= 'if('.$valal.'==""){
					alert("'.$A['name'].'不能为空!");
					$("#'.$id.'").focus();
					return false;}';				
				}
			break;
			case "2":
				$js='var '.$id.'_val = '.$valal.';
					var pattern = /^\d+(\.\d+)?$/;
					chkFlag = pattern.test('.$id.'_val);
					if(!chkFlag){
						alert("'.$A['name'].'不是数字");
						$("#'.$id.'").focus();
						return false;}';
			break;
			case "4":
				$js='var '.$id.'_val = '.$valal.';
					var pattern = /^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+(\.[a-zA-Z0-9_-])+/;
					if(!pattern.test('.$id.'_val)){
						alert("邮箱地址的格式不正确!!");
						$("#'.$id.'").focus();
						return false;}';
			break;
			case "5":
				$js='var '.$id.'_val = '.$valal.';
					var pattern = /^[a-zA-z]+:\/\/[^\s]*/;
					if(!pattern.test('.$id.'_val)){
						alert("['.$A['name'].']网址格式不正确!!");
						$("#'.$id.'").focus();
						return false;}';
			break;
		}
	}
	if($A['show'] || !$isUser) $FORM['js']=$js;
//	var_dump($FORM);
//	var_dump($A);
	return $FORM;
}
function selected($val,$id,$T){
	if(empty($val)) return;
	if($T=='js'){
		$html.='<script type="text/javascript">';
		if(strpos($val, ",")){
			$html.='var type=\''.$val.'\';$(\'#'.$id.'\').val(type.split(\',\'));';
		}else{
			$html.='$(\'#'.$id.'\').val('.(int)$val.');';
		}
		$html.='</script>';
	}
	return $html;
}
