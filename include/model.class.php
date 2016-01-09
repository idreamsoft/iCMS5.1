<?php
/**
 * @package iCMS
 * @copyright 2007-2010, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */

/**
 * 自定义模型model
 *
 * @author coolmoo
 */
class model {
	public static $SysField='fid,orderNum,title,clink,editor,userid,tags,pubdate,hits,comments,good,bad,vlink,type,top,postype,status';
	public static $defaultField='fid,type,title,editor,tags,orderNum,vlink,top,pubdate,clink';
    function isSysTable($table) {
        return in_array($table,array('admin','advertise','article','article_data','forum','comment','config','field','file','forum','group','keywords','links','members','model','search','taglist','tags','vlink'));
    }
    function isDefField($field) {
        return in_array($field,explode(',',self::$SysField));
    }
   function tbn($n){
   	   return $n.'_content';
   }
   function cache(){
		global $iCMS;
        $rs=iCMS_DB::getArray("SELECT * FROM `#iCMS@__model`");
        $_count=count($rs);
        for ($i=0;$i<$_count;$i++) {
        	empty($rs[$i]['binding']) && $url='content&mid='.$rs[$i]['id'].'&table=';
        	$menu[$rs[$i]['id']]=array(
        		'menu'=>array(
	        		$rs[$i]['table']=>array(
	        				'menu_'.$rs[$i]['table'].'_manage'=>$url.$rs[$i]['table'].'&do=manage',
	                        'menu_'.$rs[$i]['table'].'_add'=>$url.$rs[$i]['table'].'&do=add',
	                        'menu_'.$rs[$i]['table'].'_manage_draft'=>$url.$rs[$i]['table'].'&do=manage&type=draft',
	                        'menu_'.$rs[$i]['table'].'_manage_trash'=>$url.$rs[$i]['table'].'&do=manage&type=trash',
	                        'menu_'.$rs[$i]['table'].'_user_manage'=>$url.$rs[$i]['table'].'&do=manage&type=user',
	                        'menu_'.$rs[$i]['table'].'_user_draft'=>$url.$rs[$i]['table'].'&do=manage&type=user&act=draft',
	                        'menu_'.$rs[$i]['table'].'_comment_manage'=>'comment&mid='.$rs[$i]['id'],
	            	)
            	),
            	'pos'=>array($rs[$i]['position'],$rs[$i]['position2']),
            	'show'=>$rs[$i]['show'],
            );
            $lang['header_'.$rs[$i]['table']]			= $rs[$i]['name'];
            $lang['menu_'.$rs[$i]['table'].'_manage']	= $rs[$i]['name'].'管理';
            $lang['menu_'.$rs[$i]['table'].'_add']		= '添加'.$rs[$i]['name'];
            $lang['menu_'.$rs[$i]['table'].'_manage_draft']	= $rs[$i]['name'].'草稿箱';
            $lang['menu_'.$rs[$i]['table'].'_manage_trash']	= $rs[$i]['name'].'回收站';
            $lang['menu_'.$rs[$i]['table'].'_comment_manage']	= $rs[$i]['name'].'评论管理';
            $lang['menu_'.$rs[$i]['table'].'_user_manage']	= $rs[$i]['name'].'投稿管理';
            $lang['menu_'.$rs[$i]['table'].'_user_draft']	= '审核'.$rs[$i]['name'];
            $rs[$i]['tbn']=$rs[$i]['table'].'_content';
            $_array[$rs[$i]['id']]=$_tableA[$rs[$i]['table']]=$rs[$i];
            $FArray[$rs[$i]['id']]=self::Fdata($rs[$i]['id']);
        }
        $iCMS->setCache('system/models.menu',$menu,0)
        	->setCache('system/models.lang',$lang,0)
        	->setCache('system/models.cache',$_array,0)
        	->setCache('system/models.table',$_tableA,0)
        	->setCache('system/models.field',$FArray,0);
    }
    function data($id=0) {
    	if($id){
	        $rs	= iCMS_DB::getRow("SELECT * FROM `#iCMS@__model` where id='$id'",ARRAY_A);
	        $rs['tbn'] = self::tbn($rs['table']);
    	}else{
    		$rs	= iCMS_DB::getArray("SELECT * FROM `#iCMS@__model`");
    	}
        return $rs;
    }
	function FieldValue($mid,$field,$v/*,&$rs,$page=0*/) {
		global $iCMS;
	    $FArray	= $iCMS->getCache('system/models.field',$mid);;
	    $A		= $FArray[$field];
		$option	= unserialize($A['option']);
		$optStr	= $option[$A['type']];
		if($optStr){
			$_optArray	= explode("\n",$optStr);
			foreach($_optArray AS $k=>$optA){
				list($index,$choice)= explode("=",$optA);
				$optArray[trim($index)]=trim($choice);
			}
		}
	    $Fval 	= Null;
	    switch($A['type']) {
	        case "radio":
	            $optArray && $Fval	= $optArray[$v];
	        break;
	        case "upload":
	            $v && $Fval	= FS::fp($v,'+http');
	        break;
	//		case "editor":
	//			$body	=explode('<div style="page-break-after: always"><span style="display: none">&nbsp;</span></div>',$v);
	//			$total	=count($body);
	//			$nBody	=$body[intval($page-1)];
	//			$v		=$iCMS->keywords($nBody);
	//			$rs->page=$page;
	//			if($total>1){
	//				$CLArray=array('id'=>$rs->id,'link'=>$rs->customlink,'dir'=>$rs->forumdir,'pubdate'=>$rs->pubdate);
	//				$pagebreak=($page-1>1)?'<a href="'.$this->iurl('show',$CLArray,$page-1).'" class="pagebreak" target="_self">上一页</a> ':'<a href="'.$this->iurl('show',$CLArray).'" class="pagebreak" target="_self">'.$this->language('page:prev').'</a> ';
	//				for($i=1;$i<=$total;$i++){
	//					$cls=$i==$page?"pagebreaksel":"pagebreak";
	//					$pagebreak.=$i==1?'<a href="'.$this->iurl('show',$CLArray).'" class="'.$cls.'" target="_self">'.$i.'</a>':'<a href="'.$this->iurl('show',$CLArray,$i).'" class="'.$cls.'" target="_self">'.$i.'</a>';
	//				}
	//				$np=($total-$page>0)?$page+1:$page;
	//				$pagebreak.='<a href="'.$this->iurl('show',$CLArray,$np).'" class="pagebreak" target="_self">'.$this->language('page:next').'</a>';
	//				$rs->pagebreak=$pagebreak;
	//			}
	//		break;
	        case in_array($A['type'],array('checkbox','select','multiple')):
	            $vArray=explode(',',$v);
	            if($optArray)foreach($optArray AS $value=>$text) {
	                    $vArray=explode(',',$v);
	                    in_array($value,$vArray) && $Fval[$value]	= $text;
	             }
	            break;
	    }
	    return $Fval;
	}
	function Fdata($mid){
        $rst=iCMS_DB::getArray("SELECT * FROM `#iCMS@__field` where (mid='$mid' OR mid='0')");
        foreach($rst AS $key=>$a){
        	$FieldArray[$a['field']]=$a;
        }
        return $FieldArray;
	}
    function field($mid){
    	global $iCMS;
	    return $iCMS->getCache('system/models.field',$mid);
    }
    function upload($table,$aid=0,$title=''){
        require_once iPATH.'include/upload.class.php';
        if($_FILES){
        	foreach($_FILES AS $name=>$_FILE){
        		$field=str_replace('content_upload_','',$name);
	        	$F=iUpload::FILES($name,$aid,$title);
	        	$_sql[]="`$field` = '".$F["FilePath"]."'";
        	}
        	$sql=implode(',',$_sql);
        	$sql && iCMS_DB::query("UPDATE `#iCMS@__{$table}` SET {$sql} WHERE `id` = '$aid'");
        }
    }
    //数据类型
    function SqlType($type,$default) {//getSqlType
        switch($type) {
            case "number":
                $default=='' && $default='0';
                $sql =" int(11) unsigned NOT NULL  default '".$default."'";
                break;
            case "calendar":
                $default=='' && $default='0';
                $sql =" int(10) unsigned NOT NULL  default '".$default."'";
                break;
            case in_array($type,array('text','checkbox','radio','select','multiple','email','url','image','upload')):
                $sql =" varchar(255) NOT NULL  default '".$default."'";
                break;
            case in_array($type,array('textarea','editor')):
                $sql =" mediumtext NOT NULL";
                break;
        }
        return 	$sql;
    }
    function FieldType($type) {//getFieldType
        switch($type) {
            case "number":	$text='数字(number)';
                break;
            case "text":	$text='字符串(text)';
                break;
            case "radio":	$text='单选(radio)';
                break;
            case "checkbox":$text='多选(checkbox)';
                break;
            case "textarea":$text='文本(textarea)';
                break;
            case "editor":	$text='编辑器(editor)';
                break;
            case "select":	$text='选择(select)';
                break;
            case "multiple":$text='多选选择(multiple)';
                break;
            case "calendar":$text='日历(calendar)';
                break;
            case "email":	$text='电子邮件(email)';
                break;
            case "url":	$text='超级链接(url)';
                break;
            case "image":	$text='图片(image)';
                break;
            case "upload":	$text='上传(upload)';
                break;
        }
        return 	$text;
    }
    function Fieldvalidate($type) {//getFieldvalidate
        switch($type) {
            case "N":$text='不验证';
                break;
            case "0":$text='不能为空';
                break;
            case "1":$text='匹配字母';
                break;
            case "2":$text='匹配数字';
                break;
            case "4":$text='Email验证';
                break;
            case "5":$text='url验证';
                break;
            default: $text='自定义正则';
        }
        return 	$text;
    }
    //选项 choice
    function option($choices) {//getFieldChoices
        foreach(explode("\n",$choices) as $item) {
            list($index, $choice) = explode('=', $item);
            $option[trim($index)] = trim($choice);
        }
        return $option;
    }
    function select($id=0,$isA=false) {
    	global $iCMS;
        $_array=$iCMS->getCache('system/models.cache');
        $opt=$isA?'<option value="0"'.($id=="0" ? ' selected="selected"':'').'>文章模块 [mid:0]</option>':'';
        if($_array)foreach($_array AS $key=>$model) {
        	if(!$model['binding']||$isA){
	            $selected= ($model['id']==$id) ? ' selected="selected"':'';
	            $opt.="<option value='{$model['id']}'{$selected}>{$model['name']}模块 [mid:{$model['id']}]</option>";
        	}
        }
        return $opt;
    }
}