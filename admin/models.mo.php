<?php
/**
 * @package iCMS
 * @copyright 2007-2010, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */
!defined('iPATH') && exit('What are you doing?');
include iPATH.'include/model.class.php';
class models extends AdminCP {
    function domanage() {
        member::MP("menu_models_manage");
        $rs		= model::data();
        $_count	= count($rs);
        include admincp::tpl();
    }
    function doadd(){
    	$mid	= $_GET['id'];
	    $mid && $model	= model::data($mid);
    	if(empty($model)){
    		$model['position']='tools';
    		$model['position2']='sub';
    		$model['show']='0';
    	}
        include admincp::tpl();
    }
    function dosortable(){
		$mid		= (int)$_POST['mid'];
    	$mField=implode(',',$_POST['field']);
 		iCMS_DB::query("update `#iCMS@__model` SET `field`='$mField' where id='$mid'");
   }
    function doDel(){
		$id		= (int)$_GET['id'];
		$table	= model::tbn($_GET['table']);
		iCMS_DB::query("DROP TABLE `#iCMS@__{$table}`");
		iCMS_DB::query("DELETE FROM `#iCMS@__model` WHERE `id` ='$id'");
		iCMS_DB::query("DELETE FROM `#iCMS@__field` WHERE `mid` ='$id'");
		model::cache();
		javascript::dialog("自定义模型删除成功!",'js:parent.$("#model_'.$id.'").remove();parent.iCMS.closeDialog();');
    }
	function dotruncate(){
		$table	= model::tbn($_GET['table']);
		iCMS_DB::query("TRUNCATE TABLE `#iCMS@__$table`");
		javascript::dialog('内容已清空!',"url:".__SELF__."?mo=models&do=manage");
	}
	function dodelfield(){
		$mid		= (int)$_GET['id'];
		$fid		= (int)$_GET['fid'];
		$field		= $_GET['field'];
 	    $model		= model::data($mid);
		if($model){
			$table	= $model['table'];
			$fArray	= explode(',',$model['field']);
			$fKey	= array_search($field,$fArray); 
			unset($fArray[$fKey]);
			$mField	= implode(',',$fArray);
			iCMS_DB::query("update `#iCMS@__model` SET `field`='$mField' where id='$mid'");
			//删除数据库字段
			iCMS_DB::query("DELETE FROM `#iCMS@__field` WHERE `id` = '$fid';");
			iCMS_DB::query("ALTER TABLE `#iCMS@__".model::tbn($table)."` DROP `{$field}`");
			model::cache();
			javascript::dialog("字段删除成功!",'js:parent.$("#field_'.$fid.'").remove();parent.iCMS.closeDialog();');
		}
		
	}
    function doManagefield(){
	    $mid	= (int)$_GET['id'];
        $FieldArray=model::field($mid);
 	    $model	= model::data($mid);
		$fArray	= explode(',',$model['field']);
        include admincp::tpl();
    }
    function doEditField(){
	    $fid	= (int)$_POST['fid'];
	    $mid	= (int)$_POST['mid'];
	    $name	= dhtmlspecialchars($_POST['name']);
	    $field	= dhtmlspecialchars($_POST['field']);
	    $ofield	= dhtmlspecialchars($_POST['ofield']);
        if(empty($field)) {
            include iPATH.'include/cn.class.php';
            $field=CN::pinyin($name);
        }
	    $type		= $_POST['type'];
	    $show		= $_POST['show'];
	    $default	= dhtmlspecialchars($_POST['default']);
	    $validate	= $_POST['validate'];
	    $description= dhtmlspecialchars($_POST['description']);
	    $option	= addslashes(serialize($_POST['option']));
 	    $hidden	= isset($_POST['hidden'])?1:0;
	    !preg_match("/[a-zA-Z]/",$field{0}) && javascript::alert('字段只能以英文字母开头');
		!preg_match("/[a-zA-Z0-9_\-~]/",$field) && javascript::alert('字段只能由英文字母或数字组成');
 	    model::isDefField($field) && javascript::alert('您所填写的字段是默认字段!请重新填写.');
 	    $model	= model::data($mid);
 	    $oFieldA= explode(',',$model['field']);
		$sql	= "ALTER TABLE `#iCMS@__".model::tbn($model['table'])."`";
 	    if($fid){
	    	iCMS_DB::getValue("SELECT `id` FROM `#iCMS@__field` where `field` = '$field' and `mid`='$mid' and `id`!='$fid'") && javascript::alert('该字段已经存在!请检查是否重复');
 	    	iCMS_DB::query("UPDATE `#iCMS@__field` SET `name` = '$name', `field` = '$field', `mid` = '$mid', `type` = '$type',`show` = '$show', `default` = '$default', `validate` = '$validate', `hidden` = '$hidden', `description` = '$description', `option` = '$option' WHERE `id` = '$fid';");
			$sql.= " CHANGE COLUMN `$ofield` `$field`";
			if($field!=$ofield){
				$fKey	= array_search($ofield,$oFieldA); 
				unset($oFieldA[$fKey]);
			}
 	    }else{
	    	iCMS_DB::getValue("SELECT `id` FROM `#iCMS@__field` where `field` = '$field' and `mid`='$mid'") && javascript::alert('该字段已经存在!请检查是否重复');
 	    	iCMS_DB::query("INSERT INTO `#iCMS@__field` (`name`, `field`, `mid`, `type`,`show`, `default`, `validate`, `hidden`, `description`, `option`) VALUES ('$name', '$field', '$mid', '$type', '$show', '$default', '$validate', '$hidden', '$description', '$option');");
			$sql.= " ADD COLUMN `$field`";//新增
			$col = iCMS_DB::getCol("describe `#iCMS@__".model::tbn($model['table'])."`");
			$AfterSql= ' after `'.end($col).'`';
 	    }
		$SqlType	= model::SqlType($type,$default);
		iCMS_DB::query($sql.$SqlType.$AfterSql);
		if($field!=$ofield){
			array_push($oFieldA,$field);
			$mField	= implode(',',array_unique($oFieldA));
			iCMS_DB::query("update `#iCMS@__model` SET `field`='$mField' where id='$mid'");
		}
		model::cache();
	    javascript::dialog('字段添加完成!<br />10秒后返回字段管理',"url:".__SELF__."?mo=models&do=managefield&id=".$mid);
    }
    function doSave(){
	    $id			= (int)$_POST['id'];
	    $name		= dhtmlspecialchars($_POST['name']);
	    $table		= dhtmlspecialchars($_POST['table']);
	    $description= dhtmlspecialchars($_POST['desc']);
	    $show	 	= (int)$_POST['show'];
	    $position 	= $_POST['position'];
	    $position2	= $_POST['pos'];
	    $form= dhtmlspecialchars($_POST['form']);
        $binding	= isset($_POST['binding'])?1:0;
	    empty($name) && javascript::alert('模块名称不能为空！');
		empty($table) && $binding && javascript::alert('模块名不能为空！');
	    if(!$binding && empty($id)){
	        if(empty($table)) {
	            include iPATH.'include/cn.class.php';
	            $table=CN::pinyin($name);
	        }
	    }
	    !preg_match("/[a-zA-Z]/",$table{0}) && javascript::alert('模型表名只能以英文字母开头');
		!preg_match("/[a-zA-Z0-9_\-~]/",$table) && javascript::alert('模型表名只能由英文字母或数字组成');
		//model::isSysTable($table) && javascript::alert('您所填写的模块表名是系统表!请重新填写.');
	    if($id){
	    	iCMS_DB::getValue("SELECT `id` FROM `#iCMS@__model` where `table` = '$table' and `id`!='$id'") && javascript::alert('该模块已经存在!请检查是否重复');
	    	iCMS_DB::query("UPDATE `#iCMS@__model` SET `name` = '$name', `table` = '$table', `binding` = '$binding', `description` = '$description', `show` = '$show', `position` = '$position', `position2` = '$position2', `form` = '$form' WHERE `id` = '$id';");
			if(!$binding){
				$oTable=iCMS_DB::getValue("SELECT `table` FROM `#iCMS@__model` where `id` ='$id'");
				if($oTable!=$table){
					iCMS_DB::query("RENAME TABLE `#iCMS@__".model::tbn($oTable)."` TO `#iCMS@__".model::tbn($table)."`");
				}
			}
	    }else{
	    	iCMS_DB::getValue("SELECT `id` FROM `#iCMS@__model` where `table` = '$table'") && javascript::alert('该模块已经存在!请检查是否重复');
	    	$field	=$binding?'':model::$defaultField;
	    	iCMS_DB::query("INSERT INTO `#iCMS@__model`(`name`, `table`, `field`, `binding`, `description`,`show`, `position`,`position2`,`form`, `addtime`)VALUES ('$name', '$table','$field', '$binding', '$description','$show', '$position','$position2','$form', '".time()."');");
	    	$id		= iCMS_DB::$insert_id;
	    	if(!$binding){//创建模块基础表
		    	$tableSQL="CREATE TABLE `#iCMS@__".model::tbn($table)."` (
					   `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
					   `fid` int(10) unsigned NOT NULL DEFAULT '0',
					   `orderNum` smallint(6) NOT NULL DEFAULT '0',
					   `title` varchar(255) NOT NULL DEFAULT '',
					   `clink` varchar(255) NOT NULL DEFAULT '',
					   `editor` varchar(200) NOT NULL DEFAULT '',
					   `userid` int(10) unsigned NOT NULL DEFAULT '0',
					   `tags` varchar(255) NOT NULL DEFAULT '',
					   `pubdate` int(10) unsigned NOT NULL DEFAULT '0',
					   `hits` int(10) unsigned NOT NULL DEFAULT '0',
					   `comments` int(10) unsigned NOT NULL DEFAULT '0',
					   `good` int(10) unsigned NOT NULL DEFAULT '0',
					   `bad` int(10) unsigned NOT NULL DEFAULT '0',
					   `vlink` varchar(255) NOT NULL DEFAULT '',
					   `type` smallint(6) NOT NULL DEFAULT '0',
					   `top` smallint(6) NOT NULL DEFAULT '0',
					   `postype` tinyint(1) unsigned NOT NULL DEFAULT '0',
					   `status` tinyint(1) unsigned NOT NULL DEFAULT '1',
					   PRIMARY KEY (`id`),
					   KEY `pubdate` (`pubdate`),
					   KEY `comment` (`comments`),
					   KEY `hit` (`hits`),
					   KEY `order` (`orderNum`),
					   KEY `sortid` (`fid`,`id`),
					   KEY `topord` (`top`,`orderNum`),
					   KEY `userid` (`userid`),
					   KEY `postype` (`postype`,`id`),
					   KEY `status` (`status`,`postype`,`id`)
					 ) ENGINE=MyISAM  DEFAULT CHARSET=".DB_CHARSET;
				iCMS_DB::query($tableSQL);
			}
	    }
	    model::cache();
        $moreaction=array(
                array("text"=>"下一步添加字段","url"=>__SELF__."?mo=models&do=addfield&id=".$id),
                array("text"=>"返回模块列表","url"=>__SELF__."?mo=models&do=manage"),
        );
        javascript::dialog('模块'.($id?'编辑':'添加').'完成!<br />模块基础建表完成...<br />10秒后返回模块列表',"url:".__SELF__."?mo=models&do=manage",$moreaction,10);
    }
    function doAddfield(){
    	$mid	= (int)$_GET['id'];
    	$fid	= (int)$_GET['fid'];
    	if($fid){
	    	$rs	= iCMS_DB::getRow("SELECT * FROM `#iCMS@__field` where `id`='$fid'",ARRAY_A);
	    	$rs['option'] = unserialize($rs['option']);
		}else{
			$rs['show'] = 0;
		}
       include admincp::tpl();
    }
    function dodefault(){
	   	$this->domanage();
    }
  
}
