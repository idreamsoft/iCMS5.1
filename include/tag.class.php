<?php
/**
 * @package iCMS
 * @copyright 2007-2010, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */

class iTAG {
	function cache($id=0){
	    global $iCMS;
	    set_time_limit(0);
	    $forum = new forum();
	    $rs	= self::data($id);
	    $_count= count($rs);
	    for($i=0;$i<$_count;$i++) {
	        $F=$forum->forum[$rs[$i]['sortid']];
	        $iurl=$iCMS->iurl('tag',array($rs[$i],$F));
	        $rs[$i]['url']= $iurl;
	        $key=$iCMS->getTagKey($rs[$i]['name']);
	        $iCMS->setCache($key,$rs[$i],0);
	    }
	}
    function delCache($id) {
    	global $iCMS;
        $id=implode(',',(array)$id);
        $rs=iCMS_DB::getRow("SELECT `name` FROM `#iCMS@__tags` WHERE `id` in ($id) ");
        $iCMS->iCache->delete($iCMS->getTagKey($rs->name));
        iCMS_DB::query("DELETE FROM `#iCMS@__tags` WHERE `id` in ($id) ");
    }

	function add($tags,$uId="0",$indexId="0",$sortid='0',$modelId='0') {
	    $a	= explode(',',$tags);
	    $c	= count($a);
	    for($i=0;$i<$c;$i++) {
	        self::update($a[$i],$uId,$indexId,$sortid,$modelId);
	    }
	}
	function update($tag,$uId="0",$indexId="0",$sortid='0',$modelId='0') {
	    global $iCMS;
	    if(empty($tag)) return;
	    $tid	= iCMS_DB::getValue("SELECT `id` FROM `#iCMS@__tags` WHERE `name`='$tag'");
	    if(empty($tid) && $tag!="") {
	        include_once iPATH.'include/cn.class.php';
	        $link=CN::pinyin($tag,$iCMS->config['CLsplit']);
	        iCMS_DB::query("INSERT INTO `#iCMS@__tags`(`uid`,`sortid`,`name`,`type`,`keywords`,`seotitle`,`subtitle`,`description`,`link`,`count`,`weight`,`ordernum`,`tpl`,`updatetime`,`status`)VALUES ('$uId','$sortid','$tag','0','','','','','$link','1',0,0,'','".time()."','1')");
	        $tid = iCMS_DB::$insert_id;
	        self::cache($tid);
	        iCMS_DB::query("INSERT INTO `#iCMS@__taglist` (`indexId`, `tid`, `modelId`) VALUES ('$indexId', '$tid', '$modelId')");
	    }else {
	        $taglist=iCMS_DB::getValue("SELECT * FROM `#iCMS@__taglist` WHERE `indexId`='$indexId' and `tid`='$tid' and `modelId`='$modelId'");
	        if(empty($taglist)) {
	            iCMS_DB::query("INSERT INTO `#iCMS@__taglist` (`indexId`, `tid`, `modelId`) VALUES ('$indexId', '$tid', '$modelId')");
	            iCMS_DB::query("UPDATE `#iCMS@__tags` SET  `count`=count+1,`updatetime`='".time()."'  WHERE `id`='$tid'");
	        }
	    }
	}
	function diff($Ntags,$Otags,$uId="0",$indexId="0",$sortid='0',$modelId='0') {
	    $N		= self::_array($Ntags);
	    $O		= self::_array($Otags);
	    $diff	= array_diff_values($N,$O);
	    if($diff['+'])foreach($diff['+'] AS $tag) {//新增
	            self::update($tag,$uId,$indexId,$sortid,$modelId);
	        }
	    if($diff['-'])foreach($diff['-'] AS $tid=>$tag) {//减少
	            $_count	= iCMS_DB::getValue("SELECT `count` FROM `#iCMS@__tags` WHERE `id`='$tid'");
	            if($_count==1) {
	                iCMS_DB::query("DELETE FROM `#iCMS@__tags`  WHERE `id`='$tid'");
	                iCMS_DB::query("DELETE FROM `#iCMS@__taglist` WHERE `tid`='$tid'");
	            }else {
	                iCMS_DB::query("UPDATE `#iCMS@__tags` SET  `count`=count-1,`updatetime`='".time()."'  WHERE `id`='$tid'");
	                iCMS_DB::query("DELETE FROM `#iCMS@__taglist` WHERE `indexId`='$indexId' and `tid`='$tid' and `modelId`='$modelId'");
	            }
	        }
	}
	function _array($tags) {//TagsArray
	    $a	= explode(',',$tags);
	    $c	= count($a);
	    $tagRs=iCMS_DB::getArray("SELECT `id`, `name` FROM `#iCMS@__tags` WHERE `name`IN ('".str_replace(',',"','",$tags)."')");
	    if($tagRs)foreach($tagRs AS $t) {
	            $tagArray[$t['name']]=$t['id'];
	        }
	    for($i=0;$i<$c;$i++) {
	        empty($tagArray[$a[$i]])?$tag[]=$a[$i]:$tag[$tagArray[$a[$i]]]=$a[$i];
	    }
	    return $tag;
	}
	function split($string,$isString=false){
		$string=trim($string);
		if(empty($string)) return;
		$a=explode(',', $string);
		foreach($a as $key=>$value){
			$b=explode(' ', $value);
			foreach($b as $k=>$v){
				$ps=$b[$k-1];
				$ps && $ls=substr($ps, -1);
				$ns=$v{0};
				if(preg_match("/[a-z0-9]/i",$ls) && preg_match("/[a-z0-9]/i",$ns)) {
					unset($c[$key][$k-1]);
					$c[$key][$k]=$ps?$ps.' '.$v:$v;
				}else{
					$c[$key][$k]=$v;
				}
			}
		}
		$d='';
		if($c)foreach($c as $key=>$value){
			$d[]=implode(',',$value);
		}
		$e=explode(',', implode(',',$d));
		$e=array_unique($e);
		if($e)foreach($e as $key=>$value){
			$value && $f[]=$value;
		}
		if($isString){
			return implode(',',(array)$f);
		}
		return (array)$f;
	}
	function del($tags){
		global $iCMS;
	    $tagArray=explode(",",$tags);
	    foreach($tagArray AS $k=>$v) {
	        if(iCMS_DB::getValue("SELECT `count` FROM `#iCMS@__tags` WHERE `name`='$v'")=="1") {
	            iCMS_DB::query("DELETE FROM `#iCMS@__tags`  WHERE `name`='$v'");
	            $iCMS->iCache->delete($iCMS->getTagKey($v));
	        }else {
	            iCMS_DB::query("UPDATE `#iCMS@__tags` SET  `count`=count-1 ,`updatetime`='".time()."' WHERE `name`='$v'");
	        }
	    }
	    iCMS_DB::query("DELETE FROM `#iCMS@__taglist` WHERE indexId='$id' AND modelId='0'");
	    return '标签更新…<span style=\'color:green;\'>√</span><br />';
	}
	function data($id=0,$limit=0){
	    $sql= $id ? "where `id`='$id'":'';
	    $limitSQL= $limit ? "LIMIT $limit ":'';
	    return iCMS_DB::getArray("SELECT * FROM `#iCMS@__tags` {$sql} order by id DESC {$limitSQL}");
	}
}