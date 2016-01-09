<?php
/**
 * @package iCMS
 * @copyright 2007-2010, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */
!defined('iPATH') && exit('What are you doing?');
include iPATH.'include/model.class.php';
class content extends AdminCP {
    function domanage() {
		$mid	= $_GET['mid'];
	    $model	= model::data($mid);
	    $table	= $model['tbn'];
        $forum  = new forum();

		$fid	= (int)$_GET['fid'];
        $type	= $_GET['type'];
		$sql	=" where ";
        switch($type) { //postype: [0:用户][1:管理员] status:[0:草稿][1:正常][2:回收]
        	case 'draft'://草稿
        		$sql.="`status` ='0' AND `postype`='1'";
		        $_ptxt=array(0=>'发布',1=>'转成草稿');
		        $position=UI::lang("menu_article_draft");
        	break;
         	case 'trash'://回收站
        		$sql.="`status` ='2'";
        		$_ptxt=array(0=>'还原',1=>'放入回收站');
        		$position=UI::lang("menu_article_trash");
        	break;
        	case 'user'://用户
        		if($_GET['act']=="draft"){
        			$sql.="`status` ='0'"; //用户审核
	            	$position=UI::lang("menu_article_user_draft");
        		}elseif($_GET['act']=="trash"){
        			$sql.="`status` ='2'"; //用户回收站
	            	$position=UI::lang("menu_article_user_trash");
        		}else{
        			$sql.="`status` ='1'";
        			$position=UI::lang("menu_article_user_manage");
        		}
        		$sql.=" AND `postype`='0'";
         		$_ptxt=array(0=>'通过审核',1=>'取消审核');
       		break;
       		default:
	       		$sql.=" `status` ='1' AND `postype`='1'";
	       		$position='';
		       	$fid && $position=$forum->forum[$fid]['name'];
		}
        $position && $position="&nbsp;&raquo;&nbsp;".$position;
        if($_GET['keywords']) {
            if($_GET['st']=="title") {
                $sql.=" AND `title` REGEXP '{$_GET['keywords']}'";
            }else if($_GET['st']=="top") {
                $sql.=" AND `top`='{$_GET['keywords']}'";
            }else if($_GET['st']=="id") {
                $sql.=" AND `id` REGEXP '{$_GET['keywords']}'";
            }
        }

		$_GET['title'] 			&& $sql.=" AND `title` like '%{$_GET['title']}%'";
		$_GET['tag'] 			&& $sql.=" AND `tags` REGEXP '[[:<:]]".preg_quote(rawurldecode($_GET['tag']),'/')."[[:>:]]'";
        isset($_GET['at']) && $_GET['at']!='-1' && $sql.=" AND `type` ='".$_GET['at']."'";
		isset($_GET['userid']) 	&& $sql.=" AND `userid`='".(int)$_GET['userid']."'";
        $fid=member::CP($fid)?$fid:"0";
        if($fid) {
            $fidIN=$forum->fid($fid).$fid;
            if(isset($_GET['sub']) && strstr($fidIN,',')) {
                $sql.=" AND fid IN(".$fidIN.")";
            }else {
                $sql.=" AND fid ='$fid'";
            }
            //$sql.=" OR `vlink` REGEXP '[[:<:]]".preg_quote($fid, '/')."[[:>:]]')";
        }else {
            member::$cpower && $sql.=" AND fid IN(".implode(',',(array)member::$cpower).")";
        }
		$_GET['starttime'] 	&& $sql.=" and `pubdate`>='".strtotime($_GET['starttime'])."'";
		$_GET['endtime'] 	&& $sql.=" and `pubdate`<='".strtotime($_GET['endtime'])."'";
		
        $act=='user' && $uri.='&act=user';
		$_GET['type']=='draft' && $uri.='&type=draft';
        isset($_GET['userid']) && $uri.='&userid='.(int)$_GET['userid'];
        isset($_GET['keyword']) && $uri.='&keyword='.$_GET['keyword'];
        isset($_GET['tag']) && $uri.='&tag='.$_GET['tag'];

        $orderby=$_GET['orderby']?$_GET['orderby']:"id DESC";
        $maxperpage =(int)$_GET['perpage']>0?$_GET['perpage']:20;
        $total=($page==1||empty($_GET['rowNum']))?iCMS_DB::getValue("SELECT count(*) FROM `#iCMS@__{$table}` {$sql}"):(int)$_GET['rowNum'];
        page($total,$maxperpage,"条记录");
        $rs=iCMS_DB::getArray("SELECT * FROM `#iCMS@__{$table}` {$sql} order by {$orderby} LIMIT {$this->firstcount} , {$maxperpage}");
		$_count=count($rs);
        include admincp::tpl();
    }
    function dosave(){
    	include_once iPATH.'include/tag.class.php';
		$id			= $_POST['id'];
		$mid		= $_POST['mid'];
		$FArray		= model::field($mid);
	    $model		= model::data($mid);
		$content	= array();
		if($_POST['content'])foreach($_POST['content'] as $field=>$value){
			if(model::isDefField($field)){
			    switch($field){
			    	case "userid":	$value	= intval($value);break;
			    	case "fid":
			    		$value	= $fid	= intval($value);
					    empty($value) && javascript::alert('请选择所属栏目');
			    	break;
			    	case "orderNum":	$value	= _int($value);break;
			    	case "top":			$value	= _int($value);break;
			    	case "title":	
				    	$value	= dhtmlspecialchars($value);
				    	empty($value) && javascript::alert('标题不能为空！');
			    	break;
			    	case "editor":	$value	= dhtmlspecialchars($value);break;
			    	case "tags":	$value	= iTAG::split(dhtmlspecialchars($value),true);break;
			    	case "type":	$value	= intval($value);break;
			    	case "vlink":	$value	= implode(',',$value);break;
			    	case "postype":	$value	= empty($value)?intval($value):"1";break;
			    	case "pubdate":	$value	= _strtotime($value);break;
			    	case "clink":
			    		$value	=	dhtmlspecialchars($value);
					    if($value){
				        	$clinklen=strlen($value);
				        	for($i=0;$i<$clinklen;$i++){
								!preg_match("/[a-zA-Z0-9_\-~".preg_quote($this->iCMS->config['CLsplit'],'/')."]/",$value{$i}) && javascript::alert('自定链接只能由英文字母、数字或_-~组成(不支持中文)');
				        	}
						}
			    	break;
			    }
			}elseif($F = $FArray[$field]){
				switch($F['type']){
					case "number":
						$value = intval($value);
					break;
					case "calendar":
						$value = _strtotime($value);
					break;
					case in_array($F['type'],array('text','textarea','radio','select','email','url','image','upload')):
						$value = dhtmlspecialchars($value);
					break;
					case in_array($F['type'],array('checkbox','multiple')):
						$value	= implode(',',$value);
					break;
					case 'editor':
						$this->iCMS->config['autoformat'] && $value=autoformat($value);
					break;
					default:$value = dhtmlspecialchars($value);
				}
			}
			WordFilter($value) && javascript::alert($field.'字段包含被系统屏蔽的字符，请返回重新填写。');
			$content[$field] = $value;
			$PF[]=$field;
		}
        if(empty($content['clink'])) {
            include iPATH.'include/cn.class.php';
            $content['clink']=CN::pinyin($content['title'],$this->iCMS->config['CLsplit']);
        }
        $table	= model::tbn($_POST['table']);
		$MF		= explode(',',$model['field']);
		$diff	= array_diff_values($PF,$MF);
		if($diff['-'])foreach($diff['-'] AS $field){$content[$field] = '';}//缺少的字段 填认空值
        $SELFURL=__SELF__.(empty($_POST['REFERER'])?'?mo=content&do=manage':$_POST['REFERER']);
		$forum = new forum();
		if(empty($id)){
			empty($content['userid']) && $content['userid']=member::$uId;
			$content['hits']=$content['good']=$content['bad']=$content['comments']=0;
			$content['status']="1";
		    $checkCL=iCMS_DB::getValue("SELECT `id` FROM `#iCMS@__{$table}` where `clink` ='".$content['clink']."'");
		    if($this->iCMS->config['repeatitle']){
		    	iCMS_DB::getValue("SELECT `id` FROM `#iCMS@__{$table}` where `title` = '$title'") && alert('该标题内容已经存在!请检查是否重复');
		    	$checkCL && javascript::alert('该自定链接已经存在!请另选一个');
		    }else{
				$checkCL && $clink.=$this->iCMS->config['CLsplit'].random(6,1);
		    }
		    
		    iCMS_DB::insert($table,$content);
			$id	= iCMS_DB::$insert_id;
			model::upload($table,$id,$title);
            iTAG::add($content['tags'],$content['userid'],$id,$forum->rootid($fid),$mid);
            $vlink=empty($content['vlink'])?$fid:$content['vlink'].','.$fid;
            vlinkDiff($vlink,'',$id,$mid);
            if(!strstr($forum->forum[$fid]['contentRule'],'{PHP}') &&!$forum->forum[$fid]['url']&&$forum->forum[$fid]['mode']=="1" && $content['status']) {
                include iPATH.'include/iHtml.class.php';
                iHtml::content($id,$mid,$table);
                iHtml::forum($fid,1,0,1);
            }

			iCMS_DB::query("UPDATE `#iCMS@__forum` SET `count` = count+1 WHERE `fid` ='$fid' LIMIT 1 ");
//			$moreaction=array(
//				array("text"=>"编辑该内容","url"=>__SELF__."?do=content&operation=add&table=".$table."&mid=".$mid."&id=".$id),
//				array("text"=>"继续添加内容","url"=>__SELF__."?do=content&operation=add&table=".$table."&mid=".$mid."&cid=".$cid),
//				array("text"=>"查看该内容","url"=>$iCMS->iurl('content',array('mId'=>$mid,'id'=>$id,'link'=>$clink,'pubdate'=>$pubdate,'cid'=>$cid,'dir'=>$catalog->catalog[$cid]['dir'],'domain'=>$catalog->catalog[$cid]['domain'],'htmlext'=>$catalog->catalog[$cid]['htmlext']))->href,"o"=>'target="_blank"'),
//				array("text"=>"查看网站首页","url"=>"../index.php","o"=>'target="_blank"')
//			);
			javascript::dialog("添加完成!",'url:'.__SELF__."?mo=content&do=manage&table=".$table."&mid=".$mid);
		}else{
		    $checkCL=iCMS_DB::getValue("SELECT `id` FROM `#iCMS@__{$table}` where `clink` ='$clink' AND `id` !='$id'");
		     if($this->iCMS->config['repeatitle']){
		     	$checkCL && alert('该自定链接已经存在!请另选一个');
		     }else{
		     	$checkCL && $clink.=$this->iCMS->config['CLsplit'].random(6,1);
		     }
			$art=iCMS_DB::getRow("SELECT `fid`,`tags`,`vlink` FROM `#iCMS@__{$table}` where `id` ='$id'");
            iTAG::diff($content['tags'],member::$uId,$art->tags,$id,$forum->rootid($fid));
			iCMS_DB::update($table,$content,array('id'=>$id));
			model::upload($table,$id,$title);
            $vlink=empty($content['vlink'])?$fid:$content['vlink'].','.$fid;
            vlinkDiff($vlink,$art->vlink,$id);
            if(!strstr($forum->forum[$fid]['contentRule'],'{PHP}')&&!$forum->forum[$fid]['url']&&$forum->forum[$fid]['mode']=="1" && $status) {
                include iPATH.'include/iHtml.class.php';
                iHtml::content($id,$mid,$table);
                iHtml::forum($fid,1,0,1);
            }
            if($art->fid!=$fid) {
                iCMS_DB::query("UPDATE `#iCMS@__forum` SET `count` = count-1 WHERE `fid` ='{$art->fid}' LIMIT 1 ");
                iCMS_DB::query("UPDATE `#iCMS@__forum` SET `count` = count+1 WHERE `fid` ='$fid' LIMIT 1 ");
            }
            javascript::dialog('编辑完成!<br />3秒后返回项目列表','url:'.$SELFURL);
		}
    }

    function doadd(){
		include iPATH.'include/from.fun.php';
    	$mid			= $_GET['mid'];
		$table			= model::tbn($_GET['table']);
	    $model			= model::data($mid);
		$fArray			= explode(',',$model['field']);
		$_count			= count($fArray);
		$rs				= array();
    	$id				= $_GET['id'];
    	$id && $rs		= iCMS_DB::getRow("SELECT * FROM `#iCMS@__{$table}` where `id`='$id'",ARRAY_A);
		$rs['mName'] 	= $_GET['table'];
		$rs['mid'] 		= $mid;
		$rs['fid']		= empty($rs['fid'])?intval($_GET['fid']):$rs['fid'];
        $rs['pubdate']	= empty($id)?get_date('',"Y-m-d H:i:s"):get_date($rs['pubdate'],'Y-m-d H:i:s');
        empty($rs['editor']) && $rs['editor']=empty(member::$Rs->name)?member::$Rs->username:member::$Rs->name;
        empty($rs['userid']) && $rs['userid']=member::$uId;
		$rs['orderNum'] = _int($rs['orderNum']);
		$rs['top'] = _int($rs['top']);
		$formArray		= FormArray($mid,$fArray,$rs);
        $strpos = strpos(__REF__,'?');
        $REFERER = $strpos===false?'':substr(__REF__,$strpos);
        include admincp::tpl();
    }
    function dodelpic(){
		$id		= (int)$_GET['id'];
     	$mid	= (int)$_GET['mid'];
		$table	= model::tbn($_GET['table']);
     	$field	= $_GET['field'];
        $fp		= $_GET['fp'];
		if(empty($fp)) return;
        !$id && javascript::alert("请选择要删除图片的内容");
        
		$thumbfilepath=gethumb($fp,'','',false,true,true);
        FS::del(FS::fp($fp,'+iPATH'));
        if($thumbfilepath)foreach($thumbfilepath as $wh=>$tfp) {
                FS::del(FS::fp($tfp,'+iPATH'));
            }
        $filename=FS::info($fp)->filename;
        
        iCMS_DB::query("DELETE FROM `#iCMS@__file` WHERE `filename` = '{$filename}'");
		iCMS_DB::query("UPDATE `#iCMS@__{$table}` SET `$field` = '' WHERE `id` ='$id'");
        echo '<script type="text/javascript">
	        parent.$("#'.$field.'2").hide();
	        parent.$("#c'.$field.'1").hide();
	        parent.$("#'.$field.'1").show();
	        parent.$("[name='.$field.']").val("");</script>';
    }
    function doStatus() {
		$id		= (int)$_GET['id'];
     	$mid	= (int)$_GET['mid'];
		$table	= model::tbn($_GET['table']);
        $s		= (int)$_GET['s'];
        iCMS_DB::query("UPDATE `#iCMS@__{$table}` SET `status` = '".($s=='1'?0:1)."' WHERE `id` ='$id'");
		javascript::js('js:parent.$("#aid'.$id.'").remove();');
    }
    function dorecover() {
		$id		= (int)$_GET['id'];
     	$mid	= (int)$_GET['mid'];
		$table	= model::tbn($_GET['table']);
        iCMS_DB::query("UPDATE `#iCMS@__{$table}` SET `status` = '1' WHERE `id` ='$id'");
		javascript::dialog('此项目已经还原!','js:parent.$("#aid'.$id.'").remove();parent.iCMS.closeDialog();');
    }
    function dotrash() {
		$id		= (int)$_GET['id'];
     	$mid	= (int)$_GET['mid'];
		$table	= model::tbn($_GET['table']);
        iCMS_DB::query("UPDATE `#iCMS@__{$table}` SET `status` = '2' WHERE `id` ='$id'");
		javascript::dialog('此项目已经移动到回收站!','js:parent.$("#aid'.$id.'").remove();parent.iCMS.closeDialog();');
    }
    function doupdateHTML() {
        !$_GET['id'] && javascript::alert("请选择要更新的项目");
     	$mid	= (int)$_GET['mid'];
		$table	= model::tbn($_GET['table']);
        include iPATH.'include/iHtml.class.php';
        iHtml::content($_GET['id'],$mid,$table) && javascript::dialog('更新完成!',"url:1");
    }
	function dodel(){
		$id		= $_GET['id'];
		$mid	= $_GET['mid'];
        !$id && javascript::alert("请选择要删除的内容");
        $msg	= delContent($id,$mid);
        javascript::dialog($msg.'<br />成功删除!','js:parent.$("#aid'.$id.'").remove();parent.iCMS.closeDialog();');
	}
    function dodefault(){
	   	$this->domanage();
    }
  
}
