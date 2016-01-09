<?php
/**
 * @package iCMS
 * @copyright 2007-2010, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */
!defined('iPATH') && exit('What are you doing?');
class comment extends UserCP {
	function doview(){
		$id=(int)$_GET['id'];
		$rs=iCMS_DB::getRow("SELECT * FROM `#iCMS@__comment` WHERE  `id`='$id'");
		echo '<style type="text/css">
		.comment-content{margin-top:8px;color:#000000;word-break:break-all;overflow-x:hidden;word-wrap:break-word;padding-left:10px;line-height:160%}
		.quote{border:1px solid #D1D5DB;padding:3px;background-color:#FFFEF5;margin-bottom:12px;text-align:left;}
		.quote span{color:#8D8D8D;font-size:12px}
		.quote .floor{float:right;}
		.quote p{margin:8px 4px;line-height:160%;clear:both;}
		</style>';
		if($rs->quote){
			echo $this->quote($rs->quote);
		}
		echo '<div class="comment-content">'.str_replace("\r",'<br />',$rs->contents).'</div>';
	}
	function dourl(){
		$id=(int)$_GET['id'];
		$mid=(int)$_GET['mid'];
		$__TABLE__	= 'article';
		if($mid){
			$model	= $this->iCMS->getCache('system/models.cache',$mid);
			$__TABLE__	= $model['tbn'];
		}
		$rs		=iCMS_DB::getRow("SELECT * FROM `#iCMS@__".$__TABLE__."` WHERE `id`='$id'",ARRAY_A);
		include iPATH.'include/forum.class.php';
		$forum = new forum();
		$F=$forum->forum[$rs['fid']];
		$iurl=$this->iCMS->iurl($mid?'content':'show',array($rs,$F));
		if(stristr($iurl->href, $mid?'content.php':'show.php')) {
		    $iurl->href='../'.$iurl->href;
		}
		_header($iurl->href);
	}
    function doDel() {
		$mid=(int)$_GET['mid'];
		$__TABLE__	= 'article';
		if($mid){
			$model	= $this->iCMS->getCache('system/models.cache',$mid);
			$__TABLE__	= $model['tbn'];
		}
        $id	=intval($_GET['id']);
        $indexId=intval($_GET['indexId']);
        $id && iCMS_DB::query("DELETE FROM `#iCMS@__comment` WHERE `id` ='$id'");
        $indexId && iCMS_DB::query("UPDATE `#iCMS@__".$__TABLE__."` SET `comments` = comments-1  WHERE `id` ='$indexId'");
        javascript::dialog('评论删除成功!','js:parent.$("#tr-'.$id.'").remove();parent.iCMS.closeDialog();');
    }

	function domanage(){
		$mid=(int)$_GET['mid'];
		$model		= array('id'=>0,'name'=>'文章');
		$__TABLE__	= 'article';
		if($mid){
			$model	= $this->iCMS->getCache('system/models.cache',$mid);
			$__TABLE__	= $model['tbn'];
		}
        $maxperpage =(int)$_GET['perpage']>0?$_GET['perpage']:10;
        $total=($page==1||empty($_GET['rowNum']))?iCMS_DB::getValue("SELECT count(*) FROM `#iCMS@__comment` INNER JOIN `#iCMS@__$__TABLE__` ON (`#iCMS@__$__TABLE__`.userId = '".member::$uId."' AND `#iCMS@__comment`.`mId` = '$mid' AND `#iCMS@__comment`.`indexId` = `#iCMS@__$__TABLE__`.`id`)"):(int)$_GET['rowNum'];
        page($total,$maxperpage,"条评论");
        $rs=iCMS_DB::getArray("SELECT `#iCMS@__comment`.* FROM `#iCMS@__comment` INNER JOIN `#iCMS@__$__TABLE__` ON (`#iCMS@__$__TABLE__`.userId = '".member::$uId."' AND `#iCMS@__comment`.`mId` = '$mid' AND `#iCMS@__comment`.`indexId` = `#iCMS@__$__TABLE__`.`id`) LIMIT {$this->firstcount} , {$maxperpage}");
        $_count=count($rs);
//        echo iCMS_DB::$last_query;
		include $this->tpl();
	}
    function domy(){
        $sql[]=" `userId`='".member::$uId."'";
        $_GET['keyword'] 	&& $sql[]="CONCAT(title,contents) REGEXP '{$_GET['keyword']}'";
        $mid=(int)$_GET['mid'];
        $mid && $sql[]=" `mId`='".$mid."'";
        isset($_GET['status'])&&$_GET['status']!='' 	&& $sql[]=" `status`='".$_GET['status']."'";
        $where =$sql ? ' where '.implode(' AND ',(array)$sql):'';
        $maxperpage =(int)$_GET['perpage']>0?$_GET['perpage']:20;
        $total=($page==1||empty($_GET['rowNum']))?iCMS_DB::getValue("SELECT count(*) FROM `#iCMS@__comment` $where"):(int)$_GET['rowNum'];
        page($total,$maxperpage,"条评论");
        $rs=iCMS_DB::getArray("SELECT * FROM `#iCMS@__comment` $where order by id DESC LIMIT {$this->firstcount},{$maxperpage}");
        $_count=count($rs);
        include $this->tpl();
    }
	function quote($id=0,$i=0){
		if($id){
			$i++;
			$rs=iCMS_DB::getRow("SELECT * FROM `#iCMS@__comment` WHERE  `id`='$id'");
			$text='<div class="quote">';
			$i<52 && $rs->quote && $text.=$this->quote($rs->quote,$i);
			$text.='<span class="floor">#'.($rs->floor+1).'</span>';
			$text.='<span>'.$rs->username.'的原贴：</span><p>'.str_replace("\r",'<br />',$rs->contents). '</p>';
			$text.='</div>';
			return $text;
		}
	}
}
