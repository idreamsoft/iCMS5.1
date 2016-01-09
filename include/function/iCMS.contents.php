<?php
/**
 * @package iCMS
 * @copyright 2007-2010, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */
!defined('iPATH') && exit('What are you doing?');
include_once iPATH.'include/model.class.php';
function iCMS_contents($vars,&$iCMS){
	if(isset($vars['mid'])){
		$mId	= (int)$vars['mid'];
		$model	= $iCMS->getCache('system/models.cache',$mId);
		$table	= $model['tbn'];
	}elseif(isset($vars['name'])){
		$model	= $iCMS->getCache('system/models.table',$vars['name']);
		$mId	= $model['id'];
		$table	= $model['tbn'];
	}else{
		if(isset($iCMS->metadata)){
			$mId	= $iCMS->metadata->mid;
			$table	= $iCMS->metadata->table;
			$model	= $iCMS->getCache('system/models.cache',$mId);
		}else{
			echo $iCMS->language('error:model.empty');
			return;
		}
	}
	if(empty($model)){ 
		echo $iCMS->language('error:model.exit');return;
	}
	//----------------------------
    $whereSQL    = " status='1'";
    $_cache        = $iCMS->getCache(array('system/forum.cache','system/forum.hidden'));
    $_cache['system/forum.hidden']&&  $whereSQL.=getSQL($_cache['system/forum.hidden'],'fid','not');
    $maxperpage=isset($vars['row'])?(int)$vars['row']:10;
    $cacheTime =isset($vars['time'])?(int)$vars['time']:-1;
    isset($vars['userid'])    &&     $whereSQL.=" AND `userid`='{$vars['userid']}'";
    isset($vars['author'])    &&     $whereSQL.=" AND `author`='{$vars['author']}'";
    isset($vars['top'])		&& 	$whereSQL.=" AND `top`='"._int($vars['top'])."'";
    $vars['call']=='user'    &&     $whereSQL.=" AND `postype`='0'";
    $vars['call']=='admin'    &&     $whereSQL.=" AND `postype`='1'";
    $forum = $_cache['system/forum.cache'];
    if(isset($vars['fid!'])){
        $_Nfid=getfids($vars['fid!']);
         $_Nfid && $Nfids[]=$_Nfid;
           $vars['sub']=='all' && $Nfids[]=$vars['fid!'];
        $ids=($Nfids && $vars['sub']=='all')?implode(',',$Nfids):$vars['fid!'];
        $whereSQL.= getSQL($ids,'fid','not');
    }
    if(isset($vars['fid'])){
        $_fid = getfids($vars['fid']);
        $_fid && $fids[]=$_fid;
        $vars['sub']=='all'&& $fids[]=$vars['fid'];
        $ids=($fids && $vars['sub']=='all')?implode(',',$fids):$vars['fid'];
        $whereSQL.= getSQL($ids,'fid');
    }
    isset($vars['type']) && $whereSQL.= " AND `type` ='{$vars['type']}'";
    $vars['id'] && $whereSQL.= getSQL($vars['id'],'id');
    $vars['id!'] && $whereSQL.= getSQL($vars['id!'],'id','not');
    $by=$vars['by']=="ASC"?"ASC":"DESC";
    switch ($vars['orderby']) {
        case "id":        $orderSQL=" ORDER BY `id` $by";            break;
        case "hot":        $orderSQL=" ORDER BY `hits` $by";        break;
        case "comment":$orderSQL=" ORDER BY `comments` $by";    break;
        case "pubdate":    $orderSQL=" ORDER BY `pubdate` $by";    break;
        case "disorder":$orderSQL=" ORDER BY `orderNum` $by";    break;
//        case "rand":    $orderSQL=" ORDER BY rand() $by";    break;
        case "top":        $orderSQL=" ORDER BY `top`,`orderNum` ASC";break;
        default:        $orderSQL=" ORDER BY `id` DESC";
    }
    isset($vars['date']) && list($iCMS->date['y'],$iCMS->date['m'],$iCMS->date['d'])=explode('-',$vars['date']);
    if($iCMS->date){
        $day    = empty($iCMS->date['d'])?'01':$iCMS->date['d'];
        $start    = strtotime($iCMS->date['y'].$iCMS->date['m'].$day);
        $end    = empty($iCMS->date['d'])?$start+86400*$iCMS->date['total']:$start+86400;
        $whereSQL.=" AND `pubdate`<='{$end}' AND `pubdate`>='{$start}'";
    }else{
        isset($vars['startdate'])    && $whereSQL.=" AND `pubdate`>='".strtotime($vars['startdate'])."'";
        isset($vars['enddate'])     && $whereSQL.=" AND `pubdate`<='".strtotime($vars['enddate'])."'";
    }
    isset($vars['where'])        && $whereSQL.=$vars['where'];

    if($vars['action']=='search'){
        $whereSQL.=$iCMS->actionSQL;
   }elseif($vars['action']=='tag'){
        if(empty($vars['tag'])) return false;
        if(is_array($vars['tag'])){
            $_tCache=$vars['tag'];
        }else{
            $_tCache=$iCMS->getCache($iCMS->getTagKey($vars['tag']));
        }
        if($_tCache['id']){
            $tidSQL = 'AND `tid`=\''.$_tCache['id'].'\'';
        }else{
            if($_tCache)foreach($_tCache AS $_tag){
                if($_tag)$_tids[]=$_tag['id'];
            }
            if(empty($_tids))return false;
            $tidSQL = 'AND `tid` in ('.implode(',',$_tids).')';
        }
        $countSQL    = 'SELECT count(#iCMS@__'.$table.'.id) FROM `#iCMS@__'.$table.'`,`#iCMS@__taglist` WHERE #iCMS@__'.$table.'.id = `indexId` '.$tidSQL.' AND  #iCMS@__taglist.modelId='.$mId.' AND';
        $selectSQL   = 'SELECT #iCMS@__'.$table.'.* FROM `#iCMS@__'.$table.'`,`#iCMS@__taglist` WHERE #iCMS@__'.$table.'.id = `indexId` '.$tidSQL.' AND #iCMS@__taglist.modelId='.$mId.' AND';
        $orderSQL    = " ORDER BY #iCMS@__taglist.indexId $by";
	    $vars['indexId!'] && $whereSQL.= getSQL($vars['indexId!'],'#iCMS@__'.$table.'.id','not');
    }

    $offset    = 0;
    if($vars['page']){
        empty($countSQL) && $countSQL = "SELECT count(*) FROM `#iCMS@__{$table}` WHERE";
        $total    = iCMS_DB::getValue($countSQL." {$whereSQL}");
//        echo iCMS_DB::last_query;
        $pagenav= isset($vars['pagenav'])?$vars['pagenav']:"pagenav";
        $pnstyle= isset($vars['pnstyle'])?$vars['pnstyle']:0;
        $offset    = $iCMS->multi(array('total'=>$total,'perpage'=>$maxperpage,'unit'=>$iCMS->language('page:list'),'nowindex'=>$GLOBALS['page'],'pagenav'=>$pagenav,'pnstyle'=>$pnstyle));
//        $GLOBALS['cpn'] && $iCMS->_vars['pagenav'].='<span><a class="page_more" href="more.php?fid='.$ids.'" target="_self">'.$iCMS->language('page:more').'</a></span>';
        //$iCMS->addto($pagenav,"----------------");
    }
    $iscache=true;
    if($vars['cache']==false||isset($vars['page'])){
        $iscache=false;
        $rs = array();
    }else{
        $cacheName='clist/'.md5($whereSQL.$orderSQL.$maxperpage);
        $rs=$iCMS->getCache($cacheName);
    }
	if(empty($rs)){
        empty($selectSQL) && $selectSQL="SELECT * FROM `#iCMS@__{$table}` WHERE";
        $rs=iCMS_DB::getArray($selectSQL." {$whereSQL} {$orderSQL} LIMIT {$offset} , {$maxperpage}");
//echo iCMS_DB::$last_query;
//iCMS_DB::$last_query='explain '.iCMS_DB::$last_query;
//$explain=iCMS_DB::getRow(iCMS_DB::$last_query);
//print_r($explain);
		$_count=count($rs);
        for ($i=0;$i<$_count;$i++){
            $F=$forum[$rs[$i]['fid']];
            $rs[$i]['sort']['name']=$F['name'];
            $rs[$i]['sort']['url']=$iCMS->iurl('forum',$F)->href;
            $rs[$i]['sort']['link']="<a href='{$rs[$i]['sort']['url']}'>{$rs[$i]['sort']['name']}</a>";
            $rs[$i]['url']=$iCMS->iurl('content',array($rs[$i],$F,$model))->href;
            $rs[$i]['commentUrl']=$iCMS->config['publicURL']."/comment.php?indexId=".$rs[$i]['id']."&mId=".$mId."&sortId=".$rs[$i]['fid'];

            $rs[$i]['link']="<a href='{$rs[$i]['url']}'>{$rs[$i]['title']}</a>";
            if($rs[$i]['tags'] && isset($vars['tag'])){
                $tagarray=explode(',',$rs[$i]['tags']);
                foreach($tagarray AS $tk=>$tag){
                    $t = $iCMS->getTag($tag);
                    if($t){
                        $rs[$i]['tag'][$tk]['name']=$tag;
                        $rs[$i]['tag'][$tk]['url']=$t['url']->href;
                        $rs[$i]['taglink'].='<a href="'.$rs[$i]['tag'][$tk]['url'].'" class="tag" target="_self">'.$tag.'</a> ';
                    }
                }
            }
			if($fArray	= explode(',',$model['field'])){
			    foreach($fArray AS $k=>$field){
			    	if(!model::isDefField($field)){
			    		$FV	= model::FieldValue($mId,$field,$rs[$i][$field]);
			    		$FV!==Null && $rs[$i][$field]	= $FV;
			    	}
			    }
			}
        }
        $iscache && $iCMS->SetCache($cacheName,$rs,$cacheTime);
	}
//	var_dump($rs);
	return $rs;
}
?>