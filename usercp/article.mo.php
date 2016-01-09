<?php
/**
 * @package iCMS
 * @copyright 2007-2010, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */
!defined('iPATH') && exit('What are you doing?');
include iPATH.'include/forum.class.php';
class article extends UserCP {
    function doadd() {
        $forum = new forum();
        $id=(int)$_GET['id'];
        $rs=array();
        $id && $rs=iCMS_DB::getRow("SELECT a.*,ad.tpl,ad.body,ad.subtitle FROM `#iCMS@__article` a LEFT JOIN `#iCMS@__article_data` ad ON a.id=ad.aid WHERE a.id='$id' AND a.userid='".member::$uId."' AND a.postype='0'",ARRAY_A);
        $rs['pubdate']=empty($id)?get_date('',"Y-m-d H:i:s"):get_date($rs['pubdate'],'Y-m-d H:i:s');
        $fid=empty($rs['fid'])?intval($_GET['fid']):$rs['fid'];
        $cata_option=$forum->user_select($fid,0,1,1);
        $fid && $contentAttr =  unserialize($forum->forum[$fid]['contentAttr']);
        empty($rs['author']) && $rs['author']=member::$nickname;
        empty($rs['userid']) && $rs['userid']=member::$uId;
        $rs['metadata'] && $rs['metadata']=unserialize($rs['metadata']);
        $rs['body']	= dhtmlspecialchars(str_replace('<!--iCMS.PageBreak-->','<div style="page-break-after: always"><span style="display: none">&nbsp;</span></div>', $rs['body']));
        $strpos = strpos(__REF__,'?');
        $REFERER = $strpos===false?'':substr(__REF__,$strpos);
        include $this->tpl();
    }
    function doSave() {
    	include_once iPATH.'include/tag.class.php';
//    	print_r($_POST);
//    	exit;
        set_time_limit(0);
        $aid		= (int)$_POST['aid'];
        $fid		= (int)$_POST['fid'];
        $title		= dhtmlspecialchars($_POST['title']);
        $source		= dhtmlspecialchars($_POST['source']);
        $author		= dhtmlspecialchars($_POST['author']);
        $description= dhtmlspecialchars($_POST['description']);
        $keywords	= dhtmlspecialchars($_POST['keywords']);
        $tags		= dhtmlspecialchars($_POST['tags']);
        $pic		= dhtmlspecialchars($_POST['pic']);
        $metadata	= dhtmlspecialchars($_POST['metadata']);
        $metadata	= $metadata?addslashes(serialize($metadata)):'';
        $userid		= member::$uId;
        $pubdate	= _strtotime();
        $type		= 0;
        $orderNum	= 0;
        $subtitle	= '';
        $stitle		= '';
        $editor		= '';
        $clink		= '';
        $url		= '';
        $tpl		= '';
        $top		= 0;
        $vlink		= "";
        $related	= "";
        $postype	= 0;
        $body		= str_replace(array("\n","\r","\t"),"",$_POST['body']);

        empty($title) && javascript::alert('标题不能为空！');
        empty($fid) && javascript::alert('请选择所属栏目');
        empty($body) && javascript::alert('文章内容不能为空！');
        WordFilter($title) && javascript::alert('标题包含被系统屏蔽的字符，请返回重新填写。');
        WordFilter($source) && javascript::alert('出处包含被系统屏蔽的字符，请返回重新填写。');
        WordFilter($author) && javascript::alert('作者包含被系统屏蔽的字符，请返回重新填写。');
        WordFilter($description) && javascript::alert('摘要包含被系统屏蔽的字符，请返回重新填写。');
        WordFilter($keywords) && javascript::alert('关键字包含被系统屏蔽的字符，请返回重新填写。');
        WordFilter($tags) && javascript::alert('标签包含被系统屏蔽的字符，请返回重新填写。');
        WordFilter($metadata) && javascript::alert('自定义内容包含被系统屏蔽的字符，请返回重新填写。');
        WordFilter($body) && javascript::alert('文章内容包含被系统屏蔽的字符，请返回重新填写。');
        
		if($this->iCMS->config['AutoPage']){
			if($this->iCMS->config['AutoPageLen'] && !preg_match('/<div\s+style=\\\"page-break-after:.*?<\/div>/is',$body)){
				$html	= autoformat($body,false);
				AutoPageBreak::page($html,$this->iCMS->config['AutoPageLen']);
				$body	= implode('<!--iCMS.PageBreak-->',AutoPageBreak::$Rs);
				AutoPageBreak::$Rs='';unset($html);
				$this->iCMS->config['autoformat']=false;
				
			}
		}
        $body 		= preg_replace(array('/<script.+?<\/script>/is','/<form.+?<\/form>/is','/<div\s+style=\\\"page-break-after:.*?<\/div>/is'),array('','','<!--iCMS.PageBreak-->'),$body);
        $this->iCMS->config['autoformat'] && $body=autoformat($body);
        if($this->iCMS->config['autodesc']=="1" && !empty($this->iCMS->config['descLen']) && empty($description)) {
			$_body	= preg_replace (array('/<p[^>]*>/is','/<[\/\!]*?[^<>]*?>/is',"/\n+/","/　+/","/^\n/"),array("\n\n",'',"\n",'',''), $this->iCMS->config['autoformat']?$body:autoformat($body));
            $description=csubstr($_body,$this->iCMS->config['descLen']);
        }
        $tags=iTAG::split($tags,true);
        include iPATH.'include/cn.class.php';
        $clink=CN::pinyin($title,$this->iCMS->config['CLsplit']);

        $isPic=empty($pic)?0:1;
        $SELFURL=__SELF__.(empty($_POST['REFERER'])?'?mo=article&do=manage':$_POST['REFERER']);
        $forum = new forum();
        $status=$forum->forum[$fid]['isexamine']?'0':'1';//审核投稿
        if(empty($aid)) {
            $hits=$good=$bad=$comments=0;
            iCMS_DB::insert('article',compact('fid','title','stitle','clink','orderNum','url','source','author','editor','userid','postype','keywords','tags','description','related','metadata','isPic','pic','pubdate','hits' ,'good','bad','comments','type','vlink','top','status'));
            $aid	= iCMS_DB::$insert_id;
            iCMS_DB::insert('article_data',compact('aid','subtitle','tpl','body'));
            if($_FILES['picfile']){
            	require_once iPATH.'include/upload.class.php';
            	$F=iUpload::FILES("picfile",$aid,$title);
            	iCMS_DB::query("UPDATE `#iCMS@__article` SET `isPic`='1',`pic` = '".$F["FilePath"]."' WHERE `id` = '$aid'");
            }
            //$this->insert_db_remote($body,$aid);
            iTAG::add($tags,$userid,$aid,$forum->rootid($fid));
            vlinkDiff($fid,'',$aid);
            if(!strstr($forum->forum[$fid]['contentRule'],'{PHP}') &&!$forum->forum[$fid]['url']&&$forum->forum[$fid]['mode']=="1" && $status) {
                include iPATH.'include/iHtml.class.php';
                iHtml::Article($aid);
                iHtml::forum($fid,1,0,1);
            }
            if($status) {
                iCMS_DB::query("UPDATE `#iCMS@__forum` SET `count` = count+1 WHERE `fid` ='$fid' LIMIT 1 ");
                $moreaction=array(
                        array("text"=>"查看该文章","url"=>$this->iCMS->iurl('show',array(array('id'=>$aid,'link'=>$clink,'url'=>$url,'fid'=>$fid,'pubdate'=>$pubdate),$forum->forum[$fid]))->href,"o"=>'target="_blank"'),
                        array("text"=>"编辑该文章","url"=>__SELF__."?mo=article&do=add&id=".$aid),
                        array("text"=>"继续添加文章","url"=>__SELF__."?mo=article&do=add&fid=".$fid),
                        array("text"=>"返回文章列表","url"=>$SELFURL),
                        array("text"=>"查看网站首页","url"=>"../index.php","o"=>'target="_blank"')
                );
                javascript::dialog('文章发布成功!<br />10秒后返回文章列表','url:'.$SELFURL,$moreaction,10);
            }else {
                javascript::dialog('您的投稿文章发布成功!<br />该版块文章需要经过管理员审核才能显示!<br />请耐心等待,我们会尽快审核您的稿件!','url:'.$SELFURL,'ok',10);
            }
        }else {
            $art=iCMS_DB::getRow("SELECT `fid`,`tags`,`vlink` FROM `#iCMS@__article` where `id` ='$aid'");
            iTAG::diff($tags,$art->tags,member::$uId,$aid,$forum->rootid($fid));
            iCMS_DB::update('article',compact('fid','title','stitle','orderNum','clink','url','source','author','editor','userid','postype','keywords','tags','description','related','metadata','isPic','pic','pubdate','type','vlink','top','status'),array('id'=>$aid));
            vlinkDiff($fid,$art->vlink,$aid);
            iCMS_DB::update('article_data',compact('tpl','subtitle','body'),compact('aid'));
            if($_FILES['picfile']){
            	require_once iPATH.'include/upload.class.php';
            	$F=iUpload::FILES("picfile",$aid,$title);
            	iCMS_DB::query("UPDATE `#iCMS@__article` SET `isPic`='1',`pic` = '".$F["FilePath"]."' WHERE `id` = '$aid'");
            }
            //$this->insert_db_remote($body,$aid);
            if(!strstr($forum->forum[$fid]['contentRule'],'{PHP}')&&!$forum->forum[$fid]['url']&&$forum->forum[$fid]['mode']=="1" && $status) {
                include iPATH.'include/iHtml.class.php';
                iHtml::Article($aid);
                iHtml::forum($fid,1,0,1);
            }
            if($status) {
                if($art->fid!=$fid) {
                    iCMS_DB::query("UPDATE `#iCMS@__forum` SET `count` = count-1 WHERE `fid` ='{$art->fid}' LIMIT 1 ");
                    iCMS_DB::query("UPDATE `#iCMS@__forum` SET `count` = count+1 WHERE `fid` ='$fid' LIMIT 1 ");
                }
                javascript::dialog('文章编辑完成!<br />3秒后返回文章列表','url:'.$SELFURL);
            }else {
                javascript::dialog('您的文章编辑完成!<br />该版块文章需要经过管理员审核才能显示!<br />请耐心等待,我们会尽快审核您的稿件!','url:'.$SELFURL,'ok',10);
            }
        }
    }

    function domanage() {
        $forum  = new forum();
        $fid	= (int)$_GET['fid'];
        $sql	= " where `userid`='".(int)member::$uId."' AND `postype`='0'";
        //postype: [0:用户][1:管理员] status:[0:草稿][1:正常][2:回收]
        $_GET['keyword']		&& $sql.=" AND CONCAT(title,keywords,description) REGEXP '{$_GET['keyword']}'";
        $_GET['status']!=""		&& $sql.=" AND `status`='".$_GET['status']."'";
//        $_GET['title'] 			&& $sql.=" AND `title` like '%{$_GET['title']}%'";
        //       $_GET['tag'] 			&& $sql.=" AND `tags` REGEXP '[[:<:]]".preg_quote(rawurldecode($_GET['tag']),'/')."[[:>:]]'";
        if($fid) {
            $fidIN=$forum->fid($fid).$fid;
            if(isset($_GET['sub']) && strstr($fidIN,',')) {
                $sql.=" AND fid IN(".$fidIN.")";
            }else {
                $sql.=" AND fid ='$fid'";
            }
        }
//        isset($_GET['nopic'])   && $sql.=" AND `isPic` ='0'";
//        $_GET['starttime'] 	&& $sql.=" and `pubdate`>='".strtotime($_GET['starttime'])."'";
//        $_GET['endtime'] 	&& $sql.=" and `pubdate`<='".strtotime($_GET['endtime'])."'";

//        $type=='draft' && $uri.='&type=draft';
        isset($_GET['keyword']) && $uri.='&keyword='.$_GET['keyword'];
//       isset($_GET['tag']) && $uri.='&tag='.$_GET['tag'];

        $orderby=$_GET['orderby']?$_GET['orderby']:"id DESC";
        $maxperpage =(int)$_GET['perpage']>0?$_GET['perpage']:10;
        $total=($page==1||empty($_GET['rowNum']))?iCMS_DB::getValue("SELECT count(*) FROM `#iCMS@__article` {$sql}"):(int)$_GET['rowNum'];
        page($total,$maxperpage,"篇文章");
        $rs=iCMS_DB::getArray("SELECT * FROM `#iCMS@__article` {$sql} order by {$orderby} LIMIT {$this->firstcount} , {$maxperpage}");
//echo iCMS_DB::$last_query;
//iCMS_DB::$last_query='explain '.iCMS_DB::$last_query;
//$explain=iCMS_DB::getRow(iCMS_DB::$last_query);
//print_r($explain);
        $_count=count($rs);
        include $this->tpl();
    }
    function doDel() {
        $id	= (int)$_GET['id'];
        !$id && javascript::alert("请选择要删除的文章");
        $msg=delArticle($id,member::$uId,0);
        javascript::dialog($msg.'<br />成功删除!','js:parent.$("#tr-'.$id.'").remove();parent.iCMS.closeDialog();');
    }
    function doDelpic() {
        $id	= (int)$_GET['id'];
        $fp	= $_GET['fp'];
        if(empty($fp)) return;
        !$id && javascript::alert("请选择要删除图片的文章");
        
        $thumbfilepath=gethumb($fp,'','',false,true,true);
        FS::del(FS::fp($fp,'+iPATH'));
        if($thumbfilepath)foreach($thumbfilepath as $wh=>$tfp) {
                FS::del(FS::fp($tfp,'+iPATH'));
            }
        $filename=FS::info($fp)->filename;
        
        iCMS_DB::query("DELETE FROM `#iCMS@__file` WHERE `filename` = '{$filename}'");
        iCMS_DB::query("UPDATE `#iCMS@__article` SET `isPic`='0',`pic` = '' WHERE `id` = '$id'");
        echo '<script type="text/javascript">
	        parent.$("#pic2").hide();
	        parent.$("#cpic1").hide();
	        parent.$("#pic1").show();
	        parent.$("[name=pic]").val("");</script>';
    }
	function dopreview(){
        $id=(int)$_GET['id'];
        !$id && javascript::alert("请选择要删除的文章");
        $rs=iCMS_DB::getRow("SELECT a.*,ad.tpl,ad.body,ad.subtitle FROM `#iCMS@__article` a LEFT JOIN `#iCMS@__article_data` ad ON a.id=ad.aid WHERE a.id='$id' AND a.userid='".member::$uId."' AND a.postype='0'",ARRAY_A);
        echo '<style type="text/css">
.preview .title { height:53px; line-height:53px; margin:0px auto 0px; text-align: center; }
.preview .title h1 { font-size:24px; font-weight: bold; }
.preview .content { overflow:hidden; margin:10px auto; color:#444; font-size:14px; line-height:160%; padding:5px; }
.preview .content p { font-size: 14px; margin-top: 10px; margin-right: auto; margin-bottom: 0px; margin-left: auto; line-height: 25px; text-indent: 2em; padding: 0px; }
.preview .content img { clear: both; display: block; margin: 0 auto; }
.preview .content a { border-bottom:1px dotted #0B3B8C; color:#0B3B8C; text-decoration:none; }
        </style>';
        echo '<div class="preview"><div class="title"><h1>'.$rs['title'].'</h1></div>';
        echo '<div class="content">'.$rs['body'].'</div></div>';
	}
    function insert_db_remote($content,$aid) {
        $content = stripslashes($content);
        preg_match_all("/<img.*?src\s*=[\"|'|\s]*((http|file):\/\/.*?\.(gif|jpg|jpeg|bmp|png)).*?>/is",$content,$match);
        $_array = array_unique($match[1]);
        set_time_limit(0);
        foreach($_array as $key =>$value) {
            $value = FS::fp($value,'-http');
            $filename = basename($value);
            $pic=iCMS_DB::getValue("SELECT `pic` FROM `#iCMS@__article` WHERE `id` = '$aid'");
            ($this->autopic && $key==0 && empty($pic)) && iCMS_DB::query("UPDATE `#iCMS@__article` SET `isPic`='1',`pic` = '$value' WHERE `id` = '$aid'");
            $faid=iCMS_DB::getValue("SELECT `aid` FROM `#iCMS@__file` WHERE `filename` ='$filename'");
            empty($faid) && iCMS_DB::query("UPDATE `#iCMS@__file` SET `aid` = '$aid' WHERE `filename` ='$filename'");
        }
    }

}