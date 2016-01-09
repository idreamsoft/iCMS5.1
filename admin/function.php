<?php
/**
 * @package iCMS
 * @copyright 2007-2010, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */
!defined('iPATH') && exit('What are you doing?');
//翻页函数
Function page($totle,$displaypg=20,$strunit="",$url='',$target='') {
    global $page,$firstcount,$pagenav;
    $firstcount	=intval($firstcount);
    $displaypg	=intval($displaypg);
    $page	=$page?intval($page):1;
    $lastpg	=ceil($totle/$displaypg); //最后页，也是总页数
    $page	=min($lastpg,$page);
    $prepg	=(($page-1)<0)?"0":$page-1; //上一页
    $nextpg	=($page==$lastpg ? 0 : $page+1); //下一页
    $firstcount=($page-1)*$displaypg;
    $firstcount<0 && $firstcount=0;
    $REQUEST_URI=$_SERVER['QUERY_STRING']?$_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']:$_SERVER['PHP_SELF'];
    !$url && $url=$_SERVER["REQUEST_URI"]?$_SERVER["REQUEST_URI"]:$REQUEST_URI;
    $url.="&rowNum=".$totle;
    $_parse_url	= parse_url($url);
    parse_str($_parse_url["query"], $output);
    $output		= array_unique($output);
    $url_query	= http_build_query($output); //单独取出URL的查询字串
    if($url_query) {
        $url_query=preg_replace("/(^|&)page=$page/","",$url_query);
        $url=str_replace($_parse_url["query"],$url_query,$url);
        $url.=$url_query?"&page":"page";
    }else {
        $url.="?page";
    }
    $pagenav=" <a href='$url=1' target='_self'>首页</a> ";
    $pagenav.=$prepg?" <a href='$url=$prepg' target='_self'>上一页</a> ":" 上一页 ";
    $flag=0;
    for($i=$page-2;$i<=$page-1;$i++) {
        if($i<1) continue;
        $pagenav.="<a href='$url=$i' target='_self'>[$i]</a>";
    }
    $pagenav.="&nbsp;<b>$page</b>&nbsp;";
    for($i=$page+1;$i<=$lastpg;$i++) {
        $pagenav.="<a href='$url=$i' target='_self'>[$i]</a>";
        $flag++;
        if($flag==4) break;
    }
    $pagenav.=$nextpg?" <a href='$url=$nextpg' target='_self'>下一页</a> ":" 下一页 ";
    $pagenav.=" <a href='$url=$lastpg' target='_self'>末页</a> ";
    $pagenav.="共{$totle}{$strunit}，{$displaypg}{$strunit}/页 ";
    $pagenav.=" 共{$lastpg}页";
    for($i=1;$i<=$lastpg;$i=$i+5) {
        $s=$i==$page?' selected="selected"':'';
        $select.="<option value=\"$i\"{$s}>$i</option>";
    }
    if($lastpg>200) {
        $pagenav.=" 跳到 <input name=\"pageselect\" type=\"text\" id=\"pageselect\" style=\"width:36px\" />页 <input type=\"button\" onClick=\"window.location='{$url}='+$('#pageselect').val();\" value=\"跳转\" />";
    }else {
        $pagenav.=" 跳到 <select name=\"pageselect\" id=\"pageselect\" onchange=\"window.location='{$url}='+this.value\">{$select}</select>页";
    }
    (int)$lastpg<2 &&$pagenav='';
}
//-------------------------------------------
function delpic($pic){
    $thumbfilepath=gethumb($pic,'','',false,true,true);
    FS::del(FS::fp($pic,'+iPATH'));
    $msg= $pic.' 文件删除…<span style=\'color:green;\'>√</span><br />';
    if($thumbfilepath)foreach($thumbfilepath as $wh=>$fp) {
            FS::del(FS::fp($fp,'+iPATH'));
            $msg.= '缩略图 '.$wh.' 文件删除…<span style=\'color:green;\'>√</span><br />';
        }
    $filename=FS::info($pic)->filename;
    iCMS_DB::query("DELETE FROM `#iCMS@__file` WHERE `filename` = '{$filename}'");
    $msg.= $pic.' 数据删除…<span style=\'color:green;\'>√</span><br />';
    return $msg;
}

function delArticle($id,$uid='0',$postype='1') {
    global $iCMS;
    $uid && $sql="and `userid`='$uid' and `postype`='$postype'";
    $id=(int)$id;
    $art=iCMS_DB::getRow("SELECT * FROM `#iCMS@__article` WHERE id='$id' {$sql} Limit 1");
    if($art->pic) {
        $usePic=iCMS_DB::getValue("SELECT id FROM `#iCMS@__article` WHERE `pic`='{$art->pic}' and `id`<>'$id'");
       if(empty($usePic)) {
            $msg.= delpic($art->pic);
        }else {
            $msg.= $art->pic.'文件 其它文章正在使用,请到文件管理删除…<span style=\'color:green;\'>×</span><br />';
        }
    }
    $forum	= $iCMS->getCache('system/forum.cache',$art->fid);
    $body	= iCMS_DB::getValue("SELECT `body` FROM `#iCMS@__article_data` WHERE aid='$id' Limit 1");
    if($forum['mode'] && strstr($forum['contentRule'],'{PHP}')===false && empty($art->url)) {
        $bArray=explode('<!--iCMS.PageBreak-->',$body);
        $total=count($bArray);
        for($i=1;$i<=$total;$i++) {
            $iurl=$iCMS->iurl('show',array((array)$art,$forum),$i);
            FS::del($iurl->path);
            $msg.=$iurl->path.' 静态文件删除…<span style=\'color:green;\'>√</span><br />';
        }
    }
    $frs=iCMS_DB::getArray("SELECT `filename`,`path`,`ext` FROM `#iCMS@__file` WHERE `aid`='$id'");
    for($i=0;$i<count($frs);$i++) {
        if(!empty($frs[$i])) {
        	$path=$frs[$i]['path'].'/'.$frs[$i]['filename'].'.'.$frs[$i]['ext'];
            FS::del(FS::fp($frs[$i]['path'],'+iPATH'));
            $msg.=$path.' 文件删除…<span style=\'color:green;\'>√</span><br />';
        }
    }
    if($art->tags){
    	include_once iPATH.'include/tag.class.php';
    	$msg.=iTAG::del($art->tags);
    }

    iCMS_DB::query("DELETE FROM `#iCMS@__file` WHERE `aid`='$id'");
    $msg.='相关文件数据删除…<span style=\'color:green;\'>√</span><br />';
    iCMS_DB::query("DELETE FROM `#iCMS@__comment` WHERE indexId='$id' and mid='0'");
    $msg.='评论数据删除…<span style=\'color:green;\'>√</span><br />';
    iCMS_DB::query("DELETE FROM `#iCMS@__article` WHERE id='$id'");
    iCMS_DB::query("DELETE FROM `#iCMS@__article_data` WHERE `id`='$id'");
    iCMS_DB::query("DELETE FROM `#iCMS@__vlink` WHERE indexId='$id' AND modelId='0'");
    $msg.='文章数据删除…<span style=\'color:green;\'>√</span><br />';
    iCMS_DB::query("UPDATE `#iCMS@__forum` SET `count` = count-1 WHERE `fid` ='{$art->fid}' LIMIT 1");
    $msg.='栏目数据更新…<span style=\'color:green;\'>√</span><br />';
    $msg.='删除完成…<span style=\'color:green;\'>√</span><hr />';
    return $msg;
}
function delContent($id,$mid,$uid='0',$postype='1'){
	$uid && $sql="and `userid`='$uid' and `postype`='$postype'";
    $model	= model::data($mid);
    $table	= $model['tbn'];
	$FArray	= model::field($mid);
	$MF		= explode(',',$model['field']);
	$rs = iCMS_DB::getRow("SELECT * FROM `#iCMS@__{$table}` where `id`='$id' {$sql}",ARRAY_A);
	foreach($MF AS $field){
		if($FArray[$field]['type']=='upload'){
			$rs[$field] && $msg.=delpic($rs[$field]);
		}
	}
	$rs['tags'] && $msg.=deltag($rs['tags']);
    iCMS_DB::query("DELETE FROM `#iCMS@__comment` WHERE indexId='$id' and mid='$mid'");
    $msg.='评论数据删除…<span style=\'color:green;\'>√</span><br />';
    iCMS_DB::query("DELETE FROM `#iCMS@__{$table}` WHERE id='$id'");
    iCMS_DB::query("DELETE FROM `#iCMS@__vlink` WHERE indexId='$id' AND modelId='$mid'");
    $msg.='内容数据删除…<span style=\'color:green;\'>√</span><br />';
    iCMS_DB::query("UPDATE `#iCMS@__forum` SET `count` = count-1 WHERE `fid` ='".$rs['fid']."' LIMIT 1");
    $msg.='栏目数据更新…<span style=\'color:green;\'>√</span><br />';
    $msg.='删除完成…<span style=\'color:green;\'>√</span><hr />';
    return $msg;
}

function vlinkDiff ($Nsid,$Osid,$indexId="0",$modelId="0") {
    global $iCMS;
    $N		= explode(',',$Nsid);
    $O		= explode(',',$Osid);
    $diff	= array_diff_values($N,$O);
    foreach((array)$diff['+'] AS $sortid) {//新增
        if(!iCMS_DB::getValue("SELECT indexId FROM `#iCMS@__vlink` WHERE `indexId`='$indexId' and `sortId`='$sortid' and `modelId`='$modelId'")) {
            iCMS_DB::query("INSERT INTO `#iCMS@__vlink` (`indexId`, `sortId`, `modelId` ) VALUES ('$indexId', '$sortid', '$modelId' )");
        }
    }
    foreach((array)$diff['-'] AS $sortid) {//减少
        iCMS_DB::query("DELETE FROM `#iCMS@__vlink` WHERE `indexId`='$indexId' and `sortId`='$sortid' and `modelId`='$modelId'");
    }
}
//------------------------cache---------------------------
function keywords_cache() {
    global $iCMS;
    $res=iCMS_DB::getArray("SELECT `keyword`,`replace`,`status` FROM `#iCMS@__keywords` ORDER BY CHAR_LENGTH(`keyword`) ASC");
    $iCMS->setCache('system/keywords',$res,0);
}
function search_cache() {
    global $iCMS;
    $res=iCMS_DB::getArray("SELECT `search` FROM `#iCMS@__search`");
    $iCMS->setCache('system/search',$res,0);
}

//-------------------------------------------------------------
function updateConfig($v,$n) {
    global $iCMS;
    iCMS_DB::query("UPDATE `#iCMS@__config` SET `value` = '$v' WHERE `name` ='$n'");
}
function CreateConfigFile() {
    global $iCMS;
    $tmp=iCMS_DB::getArray("SELECT * FROM `#iCMS@__config`");
    $config_data="<?php\n\t\$config=array(\n";
    for ($i=0;$i<count($tmp);$i++) {
        $_config.="\t\t\"".$tmp[$i]['name']."\"=>\"".$tmp[$i]['value']."\",\n";
    }
    $config_data.=substr($_config,0,-2);
    $config_data.="\t\n);?>";
    FS::write(iPATH.'include/site.config.php',$config_data);
}

function contenType($T="article",$currentID='') {
    global $iCMS;
    $cTypeArray = $iCMS->getCache('system/contentype');
    if($cTypeArray)foreach($cTypeArray AS $id=>$CT) {
            $T==$CT['type'] && $opt.="<option value='{$CT['val']}'".($currentID==$CT['val']?" selected='selected'":'').">{$CT['name']}[type='{$CT['val']}'] </option>";
        }
    return $opt;
}
//日志
function admincp_log() {
    global $_GET, $_POST;
    if($_GET['mo']=="html") return;
    $log_message = '';
    if($_GET) {
        $log_message .= 'GET{';
        foreach ($_GET as $g_k => $g_v) {
            $g_v = is_array($g_v)?serialize($g_v):$g_v;
            $log_message .= "{$g_k}={$g_v};";
        }
        $log_message .= '}';
    }
    if($_POST) {
        $log_message .= 'POST{';
        foreach ($_POST as $g_k => $g_v) {
            $g_v = is_array($g_v)?serialize($g_v):$g_v;
            $log_message .= "{$g_k}={$g_v};";
        }
        $log_message .= '}';
    }
    runlog('admincp', $log_message);
}
function RewriteRule($rule,$b,$EXT,$HDIR){
	global $iCMS;
	$ext=empty($EXT)?$iCMS->config['htmlext']:$EXT;
	switch($b){
		case "forum":
    		$search = array('{FID}','{0xFID}','{P}','{FDIR}');
    		if(strstr($rule,'{FDIR}')===false){
    			$arg='&fid=$1';
    		}else{
    			$arg='&dir=$1';
    		}
    	break;
    	case "show":
    		$search = array('{AID}','{0xID}','{P}','{LINK}');
    		if(strstr($rule,'{LINK}')===false){
    			$arg='&id=$1';
    		}else{
    			$arg='&clink=$1';
    		}
    	break;
    	case "tag":
    	break;
	}

	$e	= str_replace($search,array('#~NUM~#','#~NUM~#','#~NUM~#','#~WORD~#'),$rule);
	$e	= str_replace(array('{FID}','{0xFID}','{P}','{AID}','{0xID}','{TID}','{MID}','{TIME}','{YY}','{YYYY}','{M}','{MM}','{D}','{DD}','{0x3ID}','{0x3,2ID}','{SID}'),'#_NUM_#',$e);
	$e	= str_replace(array('{FDIR}','{LINK}','{FPDIR}','{MD5}','{MNAME}','{ZH_CN}','{TNAME}','{TID}'),'#_WORD_#',$e);
	$e	= str_replace(array('{TID500}','{EXT}'),array('#_NUM_#/#~NUM~#',$ext),$e);
	$bits	= parse_url($iCMS->config['htmlURL']);
	$HDIR	= $bits['path']=="/"?'':substr($bits['path'],1).'/';
	$e	= $HDIR.$e;
	
    $ei	= preg_quote($e,'/');
    $ei	= str_replace(array('#~NUM~#','#_NUM_#','#~WORD~#','#_WORD_#'),array('(\d+)','\d+','(.*)','.*'),$ei);
    
	if(strstr($rule,'{P}')===false){
        $_dir    = dirname($e);
        $_file   = basename($e);
        $_name   = substr($_file,0,strrpos($_file,'.'));
        var_dump($e,$_dir,$_file,$_name);
        empty($_name) && $_name=$_file;
        $_ext    = strrchr($_file, ".");
        if(empty($_file)||substr($e,-1)=='/'||empty($_ext)) {
            $_name    = 'index';
            $_file    = $_name.'_#~NUM~#'.$ext;
            $ep    	  = $e.'/'.$_file;
        }
        
        if($b=="show"||empty($ep)){
	        $_dir    = dirname($e);
	        $fn		 = $_name.'_#~NUM~#'.$ext;
	        $ep		 = $_dir.'/'.$fn;
        }
    	$ep=preg_quote($ep,'/');
    	$ep=str_replace(array('#~NUM~#','#_NUM_#','#~WORD~#','#_WORD_#'),array('(\d+)','\d+','(.*)','.*'),$ep);
    	$rewrite="RewriteCond %{REQUEST_FILENAME} !-s\nRewriteRule ^{$ep}$\t\trewrite.php?do={$b}{$arg}&page=$2 [NC,L]\n";
	    $rewrite.="RewriteCond %{REQUEST_FILENAME} !-s\nRewriteRule ^{$ei}$\t\t\trewrite.php?do={$b}{$arg} [NC,L]\n";
    }else{
	    $rewrite="RewriteCond %{REQUEST_FILENAME} !-s\nRewriteRule ^{$ei}$\t\t\trewrite.php?do={$b}{$arg}&page=$2 [NC,L]\n";
    }
    return $rewrite;
}
function autoformat($html,$quote=true){
	$html	= stripslashes($html);
	$html	= str_replace('<!--iCMS.PageBreak-->','#--iCMS.PageBreak--#',$html);
	$html	= preg_replace (array(
		'/on(load|click|dbclick|mouseover|mousedown|mouseup)="[^"]+"/is',
		'/<script[^>]*?>.*?<\/script>/si',
		'/<style[^>]*?>.*?<\/style>/si',
//		'/<a[^>]+href=[" ]?([^"]+)[" ]?[^>]*>([^<]*)<\/a>/is',
		'/<img[^>]+src=[" ]?([^"]+)[" ]?[^>]*>/is',
		'/<embed[^>]+src=[" ]?([^"]+)[" ]\s+width=[" ]?([^"]\d+)[" ]\s+height=[" ]?([^"]\d+)[" ]?[^>]*>.*?<\/embed>/is',
		'/<embed[^>]+src=[" ]?([^"]+)[" ]?[^>]*>.*?<\/embed>/is',
		'/<b[^>]*>(.*?)<\/b>/is',
		'/<strong[^>]*>(.*?)<\/strong>/is',
		'/<p[^>]*>/is',
		'/&nbsp;/i','/&amp;/i','/&quot;/i','/&lt;/i','/&gt;/i',
		'/<[\/\!]*?[^<>]*?>/is',
		"/\n+/i"
	),array('','','',"[img]$1[/img]","[media=$2,$3]$1[/media]","[media]$1[/media]","[b]$1[/b]","[b]$1[/b]","\n\n",' ','&','"','<','>','',"[iCMS.N]"), $html);
	$html	= text_format($html);
	$html	= preg_replace (array(
	'/\[img\](.*?)\[\/img\]/is',
	'/\[media\](.*?)\[\/media\]/ise',
	'/\[media=(\d+),(\d+)\](.*?)\[\/media\]/ise',
	'/\[b\](.*?)\[\/b\]/is',
	'/\[url=([^\]|#]+)\](.*?)\[\/url\]/is',
	'/\[url=([^\]]+)\](.*?)\[\/url\]/is',
	),array('<img src="$1" />',"parsemedia('\\1')","parsemedia('\\3','\\1,\\2')",'<b>$1</b>','$2','<a target="_blank" href="$1">$2</a>'),$html);

	$html	= str_replace(array("\n","\r","\t","<p></p>","<p>,</p>"),"",$html);
	$html	= preg_replace(array("/<p>&nbsp;<\/p>+/i"),array("<p>&nbsp;</p>"),$html);
	$html	= str_replace('#--iCMS.PageBreak--#','<!--iCMS.PageBreak-->',$html);
	return $quote?addslashes($html):$html;
}

function text_format($html){
	$textArray=explode("[iCMS.N]",$html);
	foreach($textArray AS $i=> $val){
		$length=mb_strlen($val,"UTF-8");
		while (mb_substr($val,0,1,"UTF-8")==" "||mb_substr($val,0,1,"UTF-8")=="　"){
           $val	= mb_substr($val,1,$length,"UTF-8");
        }
        if ($length>0) $tempstr.="<p>".$val."</p>";
	}
	return $tempstr;
}
function parsemedia($url,$params='800,600') {
	$params = explode(',', $params);
	$width = intval($params[0]) > 800 ? 800 : intval($params[0]);
	$height = intval($params[1]) > 600 ? 600 : intval($params[1]);
//	$autostart = !empty($params[3]) ? 1 : 0;
	$autostart = 1;
//	if($flv = parseflv($url, $width, $height)) {
//		return $flv;
//	}
	$url = str_replace(array('<', '>'), '', str_replace('\\"', '\"', $url));
	$type = substr(strrchr($url, "."), 1);

	switch($type) {
		case 'mp3':
		case 'wma':
		case 'ra':
		case 'ram':
		case 'wav':
			return '<embed src="'.$url.'" width="360" height="64" autostart="'.$autostart.'" type="application/x-mplayer2"></embed></object>';
		case 'rm':
		case 'rmvb':
		case 'rtsp':
			$mediaid = 'media_'.random(3);
			return '<embed src="'.$url.'" width="'.$width.'" height="32" type="audio/x-pn-realaudio-plugin" controls="controlpanel" console="'.$mediaid.'_"'.($autostart ? ' autostart="true"' : '').'></embed>';
		case 'flv':
			return '<script type="text/javascript" reload="1">document.write(AC_FL_RunContent(\'width\', \''.$width.'\', \'height\', \''.$height.'\', \'allowNetworking\', \'internal\', \'allowScriptAccess\', \'never\', \'src\', iCMS.publicURL+\'/common/flvplayer.swf\', \'flashvars\', \'file='.rawurlencode($url).'\', \'quality\', \'high\', \'wmode\', \'transparent\', \'allowfullscreen\', \'true\'));</script>';
		case 'swf':
			return '<embed src="'.$url.'" width="'.$width.'" height="'.$height.'" autostart="'.$autostart.'" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer"></embed>';
			//return '<script type="text/javascript" reload="1">document.write(AC_FL_RunContent(\'width\', \''.$width.'\', \'height\', \''.$height.'\', \'allowNetworking\', \'internal\', \'allowScriptAccess\', \'never\', \'src\', \''.$url.'\', \'quality\', \'high\', \'bgcolor\', \'#ffffff\', \'wmode\', \'transparent\', \'allowfullscreen\', \'true\'));</script>';
		case 'asf':
		case 'asx':
		case 'wmv':
		case 'mms':
		case 'avi':
		case 'mpg':
		case 'mpeg':
			return '<embed src="'.$url.'" width="'.$width.'" height="'.$height.'" autostart="'.$autostart.'" type="application/x-mplayer2"></embed>';
		case 'mov':
			return '<embed src="'.$url.'" width="'.$width.'" height="'.$height.'" autostart="'.($autostart ? 'true' : 'false').'" type="video/quicktime" controller="true"></embed>';
		default:
			return '<a href="'.$url.'" target="_blank">'.$url.'</a>';
	}
	return;
}
class AutoPageBreak{
	public static $Rs=array();
	function page($content,$maxlen=300,$_pageNum=0) {
		$_content	= preg_replace (array('/<[\/\!]*?[^<>]*?>/is',"/\n+/","/　+/","/^\n/"),'',$content);
		$_length	= cstrlen($_content);
		$_pageCount	= floor($_length/$maxlen);
	    $contentA	= preg_split("/(<[^>]+?>)/si",$content, -1,PREG_SPLIT_NO_EMPTY| PREG_SPLIT_DELIM_CAPTURE);
	    $_caCount	= count($contentA);
	    $wordrows	= 0;
	    $outstr		= "";
	    $wordend	= false;
	    $beginTags	= 0;
	    $endTags	= 0;
	    foreach($contentA as $key=>$value) {
	        if (trim($value)=="") continue;

	        if (strpos(";$value","<")>0) {
	            if (!preg_match("/(<[^>]+?>)/si",$value) && cstrlen($value)<=$maxlen) {
	                $wordend=true;
	                $outstr.=$value;
	            }
	            if ($wordend==false) {
	                $outstr.=$value;
	                if (!preg_match("/<img([^>]+?)>/is",$value)&& !preg_match("/<param([^>]+?)>/is",$value)&& !preg_match("/<!([^>]+?)>/is",$value)&& !preg_match("/<br([^>]+?)>/is",$value)&& !preg_match("/<hr([^>]+?)>/is",$value)&&!preg_match("/<\/([^>]+?)>/is",$value)) {
	                    $beginTags++;
	                }else {
	                    if (preg_match("/<\/([^>]+?)>/is",$value,$matches)) {
	                        $endTags++;
	                    }
	                }
	            }else {
	                if (preg_match("/<\/([^>]+?)>/is",$value,$matches)) {
	                    $endTags++;
	                    $outstr.=$value;
	                    if ($beginTags==$endTags && $wordend==true) break;
	                }else {
	                    if (!preg_match("/<img([^>]+?)>/is",$value) && !preg_match("/<param([^>]+?)>/is",$value) && !preg_match("/<!([^>]+?)>/is",$value) && !preg_match("/<[br|BR]([^>]+?)>/is",$value) && !preg_match("/<hr([^>]+?)>/is",$value)&& !preg_match("/<\/([^>]+?)>/is",$value)) {
	                        $beginTags++;
	                        $outstr.=$value;
	                    }
	                }
	            }
	        }else {
	            if (is_numeric($maxlen)){
	                $curLength=cstrlen($value);
	                $maxLength=$curLength+$wordrows;
	                if ($wordend==false) {
	                    if ($maxLength>$maxlen) {
	                        //$outstr.=csubstr($value,$maxlen-$wordrows,FALSE,0);
	                        $outstr.=$value;
	                        $wordend=true;
	                        
	                    }else {
	                        $wordrows=$maxLength;
	                        $outstr.=$value;
	                    }
	                }
	            }else {
	                if ($wordend==false) $outstr.=$value;
	            }
	        }
	    }
	    while(preg_match("/<([^\/][^>]*?)><\/([^>]+?)>/is",$outstr)) {
	        $outstr=preg_replace_callback("/<([^\/][^>]*?)><\/([^>]+?)>/is","strip_empty_html",$outstr);
	    }
	    if (strpos(";".$outstr,"[html_")>0) {
	        $outstr=str_replace("[html_&lt;]","<",$outstr);
	        $outstr=str_replace("[html_&gt;]",">",$outstr);
	    }
	    self::$Rs[]=$outstr;
	    $_pageNum++;
	    $contentA=array_slice($contentA,$key+1);
	    $content =implode('',$contentA);
	    if($_pageNum<$_pageCount){
	    	self::page($content,$maxlen,$_pageNum);
	    }else{
	    	trim($content)!="" && self::$Rs[] = $content;
	    }
	}
}