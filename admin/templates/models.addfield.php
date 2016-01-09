<?php /**
 * @package iCMS
 * @copyright 2007-2010, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */
!defined('iPATH') && exit('What are you doing?'); 
admincp::head();
?>

<div class="position">当前位置：管理中心&nbsp;&raquo;&nbsp;自定义模块管理&nbsp;&raquo;&nbsp;<?php echo empty($fid)?'添加':'编辑' ; ?>字段</div>
<form action="<?php echo __ADMINCP__; ?>=models" method="post" target="iCMS_FRAME">
  <input type="hidden" name="do" value="editField" />
  <input type="hidden" name="mid" value="<?php echo $mid ; ?>" />
  <input type="hidden" name="fid" value="<?php echo $fid ; ?>" />
  <input type="hidden" name="ofield" value="<?php echo $rs['field'] ; ?>" />
  <table class="adminlist">
    <thead>
      <tr>
        <th colspan="3"><?php echo empty($fid)?'添加':'编辑' ; ?>字段</th>
      </tr>
    </thead>
    <tr>
      <td class="td80">所属模块:</td>
      <td class="rowform"><select name="mid" id="mid" disabled="disabled">
    	 <?php echo model::select($mid);?>
        </select></td>
      <td class="tips2"></td>
    </tr>
    <tr>
      <td class="td80">名称:</td>
      <td class="rowform"><input name="name" type="text" id="name" value="<?php echo $rs['name'] ; ?>" class="txt" style="width:160px;"/>
        <input name="hidden" type="checkbox" id="hidden" value="1" class="checkbox" />
        隐藏字段</td>
      <td class="tips2">隐藏字段 将不会在表单上显示</td>
    </tr>
    <tr>
      <td class="td80">字段:</td>
      <td class="rowform"><input name="field" type="text" id="field" value="<?php echo $rs['field'] ; ?>" class="txt" /></td>
      <td class="tips2">请以字母开头,留空将按字段名称拼音</td>
    </tr>
    <tr>
      <td class="td80">类型:</td>
      <td class="rowform"><select name="type" id="type">
          <option value="text">字符串(text)</option>
          <option value="number">数字(number)</option>
          <option value="radio">单选(radio)</option>
          <option value="checkbox">多选(checkbox)</option>
          <option value="textarea">文本(textarea)</option>
          <option value="editor">编辑器(editor)</option>
          <option value="select">选择(select)</option>
          <option value="multiple">多选选择(multiple)</option>
          <option value="calendar">日历(calendar)</option>
          <option value="email">电子邮件(email)</option>
          <option value="url">超级链接(url)</option>
          <!--option value="image">图片(image)</option-->
          <option value="upload">上传(upload)</option>
        </select></td>
      <td class="tips2">如果该字段正在使用,修改类型有可能会导致数据丢失</td>
    </tr>
    <tr>
      <td class="td80">默认值:</td>
      <td class="rowform"><input name="default" type="text" value="<?php echo $rs['default'] ; ?>" class="txt" /></td>
      <td class="tips2"></td>
    </tr>
    <tr>
      <td class="td80">用户填写:</td>
      <td class="rowform"><ul onmouseover="altStyle(this);">
          <li<?php if($rs['show']=='1') echo ' class="checked"'  ?>>
            <input class="radio" type="radio" name="show" value="1" <?php if($rs['show']=='1') echo 'checked'  ?>>
            是</li>
          <li<?php if($rs['show']=='0') echo ' class="checked"'  ?>>
            <input class="radio" type="radio" name="show" value="0" <?php if($rs['show']=='0') echo 'checked'  ?>>
            否</li>
        </ul></td>
      <td class="tips2" colspan="2">是否在用户中心显示</td>
    </tr>
    <tr>
      <td class="td80">是否验证:</td>
      <td class="rowform"><select name="validate" id="validate">
          <option value="N">不验证</option>
          <option value="0">不能为空</option>
          <option value="1">匹配字母</option>
          <option value="2">匹配数字</option>
          <option value="4">Email验证</option>
          <option value="5">url验证</option>
          <option value="preg">自定义正则</option>
        </select>
        <div id="validate_preg" style="display: none;margin-top:10px;">自定义正则:
          <input name="validate" value="<?php echo $rs['validate'] ; ?>" type="text" class="txt" disabled="disabled"/>
        </div></td>
      <td class="tips2"></td>
    </tr>
    <tr>
      <td class="td80">描述(可选):</td>
      <td class="rowform"><textarea  rows="6" onkeyup="textareasize(this)" name="description" id="description" cols="50" class="tarea"><?php echo $rs['description'] ; ?></textarea></td>
      <td class="tips2"></td>
    </tr>
    <tbody id="style_select" style="display: none">
      <tr>
        <th colspan="3">选择(select)</th>
      </tr>
      <tr>
        <td class="td80">选项内容:</td>
        <td class="rowform"><textarea  rows="6" onkeyup="textareasize(this)" name="option[select]" id="option_select" cols="50" class="tarea"><?php echo $rs['option']['select'] ; ?></textarea></td>
        <td class="tips2">只在项目为可选时有效，每行一个选项，等号前面为选项索引(建议用数字)，后面为内容，例如: <br />
          <i>1 = 光电鼠标<br />
          2 = 机械鼠标<br />
          3 = 没有鼠标</i><br />
          注意: 选项确定后请勿修改索引和内容的对应关系，但仍可以新增选项。如需调换显示顺序，可以通过移动整行的上下位置来实现</td>
      </tr>
    </tbody>
    <tbody id="style_multiple" style="display: none">
      <tr>
        <th colspan="3">多选选择(multiple)</th>
      </tr>
      <tr>
        <td class="td80">选项内容:</td>
        <td class="rowform"><textarea  rows="6" onkeyup="textareasize(this)" name="option[multiple]" id="option_multiple" cols="50" class="tarea"><?php echo $rs['option']['multiple'] ; ?></textarea></td>
        <td class="tips2">只在项目为可选时有效，每行一个选项，等号前面为选项索引(建议用数字)，后面为内容，例如: <br />
          <i>1 = 光电鼠标<br />
          2 = 机械鼠标<br />
          3 = 没有鼠标</i><br />
          注意: 选项确定后请勿修改索引和内容的对应关系，但仍可以新增选项。如需调换显示顺序，可以通过移动整行的上下位置来实现</td>
      </tr>
    </tbody>
    <tbody id="style_radio" style="display: none">
      <tr>
        <th colspan="3">单选(radio)</th>
      </tr>
      <tr>
        <td class="td80">选项内容:</td>
        <td class="rowform"><textarea  rows="6" onkeyup="textareasize(this)" name="option[radio]" id="option_radio" cols="50" class="tarea"><?php echo $rs['option']['radio'] ; ?></textarea></td>
        <td class="tips2">只在项目为可选时有效，每行一个选项，等号前面为选项索引(建议用数字)，后面为内容，例如: <br />
          <i>1 = 光电鼠标<br />
          2 = 机械鼠标<br />
          3 = 没有鼠标</i><br />
          注意: 选项确定后请勿修改索引和内容的对应关系，但仍可以新增选项。如需调换显示顺序，可以通过移动整行的上下位置来实现</td>
      </tr>
    </tbody>
    <tbody id="style_checkbox" style="display: none">
      <tr>
        <th colspan="3">多选(checkbox)</th>
      </tr>
      <tr>
        <td class="td80">选项内容:</td>
        <td class="rowform"><textarea  rows="6" onkeyup="textareasize(this)" name="option[checkbox]" id="option_checkbox" cols="50" class="tarea"><?php echo $rs['option']['checkbox'] ; ?></textarea></td>
        <td class="tips2">只在项目为可选时有效，每行一个选项，等号前面为选项索引(建议用数字)，后面为内容，例如: <br />
          <i>1 = 光电鼠标<br />
          2 = 机械鼠标<br />
          3 = 没有鼠标</i><br />
          注意: 选项确定后请勿修改索引和内容的对应关系，但仍可以新增选项。如需调换显示顺序，可以通过移动整行的上下位置来实现</td>
      </tr>
    </tbody>
    <tr>
      <td colspan="3">
          <input type="submit" class="submit" name="editsubmit" value="提交"  />
       </td>
    </tr>
  </table>
</form>
<script type="text/javascript">
$(function(){
	$("#type").change(function(){
		$('tbody[id^=style]').hide(); 
		$('#style_'+this.value).show(); 
	}); 
	$("#validate").change(function(){
		if(this.value=='preg'){
			$('#validate_preg').show(); 
			$('input[name=validate]').attr("disabled","");
		}else{
			$('#validate_preg').hide(); 
			$('input[name=validate]').attr("disabled","disabled");
		}
	}); 
<?php if($rs['hidden']=="1"){ ?>
$('#hidden').attr("checked","checked");
<?php } ?>
<?php if($rs['type']){ ?>
$('#type').val("<?php echo $rs['type'] ; ?>").change();
<?php } ?>
<?php if(in_array($rs['validate'],array('N','0','1','2','4','5'))){ ?>
$('#validate').val("<?php echo $rs['validate'] ; ?>");
<?php }else if($rs['validate']=="preg"){ ?>
$('#validate').val("preg").change();
<?php } ?>		
});
</script>
</body></html>