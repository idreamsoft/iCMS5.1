<?php
/**
 * @package iCMS
 * @copyright 2007-2010, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */
//error_reporting(E_ERROR | E_PARSE);
require_once(dirname(__FILE__)."/../global.php");

$lockfile = iPATH.'include/update 5.0 To 5.1.lock';
if(file_exists($lockfile)) {
	show_msg('警告!您已经升级过iCMS数据库结构<br>
		为了保证数据安全，请立即手动删除 update.php 文件<br>
		如果您想再次升级iCMS，请删除 ./include/update 5.0 To 5.1.lock 文件，再次运行安装文件');
}
!$_SERVER['PHP_SELF'] && $_SERVER['PHP_SELF']=$_SERVER['SCRIPT_NAME'];
$_URI  = $_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'];
$CMSDIR=substr(dirname($_URI),0,-8);
$CMSURL= 'http://'.$_SERVER['HTTP_HOST'].$CMSDIR;
//处理开始
if(empty($_GET['step'])) {
	//开始
	$_GET['step'] = 0;

	show_msg('<a href="?step=1">升级开始</a><br>本升级程序会参照最新的SQL文,对你的数据库进行升级');

} elseif ($_GET['step'] == 1) {
	//写入新配置
	iCMS_DB::query("ALTER TABLE `#iCMS@__members` 
	CHANGE `nickname` `nickname` varchar(255)  COLLATE utf8_general_ci NOT NULL DEFAULT '' after `password`, 
	CHANGE `gender` `gender` tinyint(1) unsigned   NOT NULL DEFAULT '0' after `nickname`, 
	CHANGE `info` `info` mediumtext  COLLATE utf8_general_ci NOT NULL after `gender`, 
	CHANGE `power` `power` mediumtext  COLLATE utf8_general_ci NOT NULL after `info`, 
	CHANGE `cpower` `cpower` mediumtext  COLLATE utf8_general_ci NOT NULL after `power`, 
	ADD COLUMN `regtime` int(10) unsigned   NULL DEFAULT '0' after `cpower`, 
	CHANGE `lastip` `lastip` varchar(15)  COLLATE utf8_general_ci NOT NULL DEFAULT '' after `regtime`, 
	ADD COLUMN `type` tinyint(1)   NOT NULL DEFAULT '0' after `post`, 
	ADD COLUMN `status` tinyint(1)   NOT NULL DEFAULT '0' after `type`, 
	DROP COLUMN `email`, COMMENT='';");
	$adminRs=iCMS_DB::getArray("SELECT * FROM `#iCMS@__admin`");
	foreach($adminRs AS $admin){
		iCMS_DB::query("INSERT INTO `#iCMS@__members` (`groupid`, `username`, `password`, `nickname`, `gender`, `info`, `power`, `cpower`, `regtime`, `lastip`, `lastlogintime`, `logintimes`, `post`, `type`, `status`) VALUES ('".$admin['groupid']."', '".$admin['username']."', '".$admin['password']."', '".$admin['name']."', '".$admin['gender']."', '".$admin['info']."', '".$admin['power']."', '".$admin['cpower']."', '".time()."', '".$admin['lastip']."', '".$admin['lastlogintime']."', '".$admin['logintimes']."', '0', '1', '1');");
	}
	iCMS_DB::query("DROP TABLE `#iCMS@__admin`;");
	$_configRs=iCMS_DB::getArray("SELECT * FROM `#iCMS@__config`");
	foreach($_configRs AS $_C){
		$configRs[$_C['name']]=$_C['value'];
	}

	iCMS_DB::query("TRUNCATE TABLE `#iCMS@__config`");
	iCMS_DB::query("INSERT INTO `#iCMS@__config`(`name`,`value`) values ('name','".$configRs['name']."'),('seotitle','".$configRs['seotitle']."'),('keywords','".$configRs['keywords']."'),('description','".$configRs['description']."'),('icp','".$configRs['icp']."'),('masteremail','".$configRs['masteremail']."'),('template','".$configRs['template']."'),('indexname','".$configRs['indexname']."'),('indexTPL','".$configRs['indexTPL']."'),('debug','0'),('tpldebug','0'),('language','zh-cn'),('setupURL','".$configRs['setupURL']."'),('publicURL','".$configRs['publicURL']."'),('htmlURL','".$configRs['htmlURL']."'),('usercpURL','".$CMSURL."/usercp'),('htmldir','".$configRs['htmldir']."'),('htmlext','".$configRs['htmlext']."'),('enable_xmlrpc','".$configRs['enable_xmlrpc']."'),('tagRule','".$configRs['tagRule']."'),('iscache','".$configRs['iscache']."'),('cachedir','".$configRs['cachedir']."'),('cachelevel','".$configRs['cachelevel']."'),('cachetime','".$configRs['cachetime']."'),('iscachegzip','".$configRs['iscachegzip']."'),('cacheEngine','".$configRs['cacheEngine']."'),('cacheServers','".$configRs['cacheServers']."'),('uploadURL','".$configRs['uploadURL']."'),('remoteKey','".$configRs['remoteKey']."'),('uploadScript','".$configRs['uploadScript']."'),('uploadfiledir','".$configRs['uploadfiledir']."'),('savedir','".$configRs['savedir']."'),('fileext','".$configRs['fileext']."'),('isthumb','".$configRs['isthumb']."'),('thumbwidth','".$configRs['thumbwidth']."'),('thumbhight','".$configRs['thumbhight']."'),('iswatermark','".$configRs['iswatermark']."'),('waterwidth','".$configRs['waterwidth']."'),('waterheight','".$configRs['waterheight']."'),('waterpos','".$configRs['waterpos']."'),('waterimg','".$configRs['waterimg']."'),('watertext','".$configRs['watertext']."'),('waterfont','".$configRs['waterfont']."'),('waterfontsize','".$configRs['waterfontsize']."'),('watercolor','".$configRs['watercolor']."'),('waterpct','".$configRs['waterpct']."'),('iscomment','".$configRs['iscomment']."'),('anonymous','".$configRs['anonymous']."'),('isexamine','".$configRs['isexamine']."'),('anonymousname','".$configRs['anonymousname']."'),('autoformat','1'),('keywordToTag','".$configRs['keywordToTag']."'),('remote','".$configRs['remote']."'),('autopic','".$configRs['autopic']."'),('autodesc','".$configRs['autodesc']."'),('descLen','".$configRs['descLen']."'),('repeatitle','".$configRs['repeatitle']."'),('ServerTimeZone','".$configRs['ServerTimeZone']."'),('cvtime','".$configRs['cvtime']."'),('dateformat','".$configRs['dateformat']."'),('CLsplit','".$configRs['CLsplit']."'),('diggtime','".$configRs['diggtime']."'),('kwCount','".$configRs['kwCount']."'),('issmall','".$configRs['issmall']."'),('AutoPage','".$configRs['AutoPage']."'),('AutoPageLen','".$configRs['AutoPageLen']."'),('tagURL','".$configRs['tagURL']."'),('thumbwatermark','".$configRs['thumbwatermark']."'),('tagDir','".$configRs['tagDir']."'),('autopatch','1'),('seccode','0'),('enable_register','1'),('userseccode','0'),('agreement','当您申请用户时，表示您已经同意遵守本规章。 \r\n\r\n欢迎您加入本站点参加交流和讨论，为维护网上公共秩序和社会稳定，请您自觉遵守以下条款： \r\n\r\n一、不得利用本站危害国家安全、泄露国家秘密，不得侵犯国家社会集体的和公民的合法权益，不得利用本站制作、复制和传播下列信息：\r\n（一）煽动抗拒、破坏宪法和法律、行政法规实施的； \r\n（二）煽动颠覆国家政权，推翻社会主义制度的；\r\n（三）煽动分裂国家、破坏国家统一的； \r\n（四）煽动民族仇恨、民族歧视，破坏民族团结的； \r\n（五）捏造或者歪曲事实，散布谣言，扰乱社会秩序的； \r\n（六）宣扬封建迷信、淫秽、色情、赌博、暴力、凶杀、恐怖、教唆犯罪的； \r\n（七）公然侮辱他人或者捏造事实诽谤他人的，或者进行其他恶意攻击的； \r\n（八）损害国家机关信誉的； \r\n（九）其他违反宪法和法律行政法规的； \r\n（十）进行商业广告行为的。 \r\n\r\n二、互相尊重，对自己的言论和行为负责。\r\n三、禁止在申请用户时使用相关本站的词汇，或是带有侮辱、毁谤、造谣类的或是有其含义的各种语言进行注册用户，否则我们会将其删除。\r\n四、禁止以任何方式对本站进行各种破坏行为。\r\n五、如果您有违反国家相关法律法规的行为，本站概不负责，您的登录论坛信息均被记录无疑，必要时，我们会向相关的国家管理部门提供此类信息。');");
	iCMS_DB::query("ALTER TABLE `#iCMS@__article` 
	ADD COLUMN `metadata` text  COLLATE utf8_general_ci NOT NULL after `related`, 
	CHANGE `pubdate` `pubdate` int(10) unsigned   NOT NULL DEFAULT '0' after `metadata`, 
	CHANGE `hits` `hits` int(10) unsigned   NOT NULL DEFAULT '0' after `pubdate`, 
	CHANGE `comments` `comments` int(10) unsigned   NOT NULL DEFAULT '0' after `hits`, 
	CHANGE `good` `good` int(10) unsigned   NOT NULL DEFAULT '0' after `comments`, 
	CHANGE `bad` `bad` int(10) unsigned   NOT NULL DEFAULT '0' after `good`, 
	CHANGE `vlink` `vlink` varchar(255)  COLLATE utf8_general_ci NOT NULL DEFAULT '' after `bad`, 
	CHANGE `type` `type` smallint(6)   NOT NULL DEFAULT '0' after `vlink`, 
	CHANGE `top` `top` smallint(6)   NOT NULL DEFAULT '0' after `type`, 
	CHANGE `postype` `postype` tinyint(1) unsigned   NOT NULL DEFAULT '0' after `top`, 
	CHANGE `status` `status` tinyint(1) unsigned   NOT NULL DEFAULT '1' after `postype`, COMMENT='';");
	iCMS_DB::query("ALTER TABLE `#iCMS@__comment` 
	ADD KEY `quote`(`quote`), COMMENT='';");
	iCMS_DB::query("CREATE TABLE `#iCMS@__field`(
	`id` int(10) unsigned NOT NULL  auto_increment , 
	`name` varchar(255) COLLATE utf8_general_ci NOT NULL  DEFAULT '' , 
	`field` varchar(255) COLLATE utf8_general_ci NOT NULL  DEFAULT '' , 
	`mid` int(10) unsigned NOT NULL  DEFAULT '0' , 
	`type` varchar(255) COLLATE utf8_general_ci NOT NULL  DEFAULT '' , 
	`default` varchar(255) COLLATE utf8_general_ci NOT NULL  DEFAULT '' , 
	`validate` varchar(255) COLLATE utf8_general_ci NOT NULL  DEFAULT '' , 
	`show` tinyint(1) NOT NULL  DEFAULT '0' , 
	`hidden` enum('0','1') COLLATE utf8_general_ci NOT NULL  DEFAULT '0' , 
	`description` varchar(255) COLLATE utf8_general_ci NOT NULL  DEFAULT '' , 
	`option` text COLLATE utf8_general_ci NOT NULL  , 
	PRIMARY KEY (`id`) 
) ENGINE=MyISAM DEFAULT CHARSET='utf8';");
	iCMS_DB::query("insert  into `#iCMS@__field`(`id`,`name`,`field`,`mid`,`type`,`default`,`validate`,`show`,`hidden`,`description`,`option`) values (1,'栏目','fid',0,'number','','0',1,'0','',''),(2,'排序','orderNum',0,'number','0','N',0,'0','',''),(3,'标题','title',0,'text','','0',1,'0','',''),(4,'自定义链接','clink',0,'text','0','N',0,'0','只能由英文字母、数字或_-组成(不支持中文),留空则自动以标题拼音填充',''),(5,'标签','tags',0,'text','','N',1,'0','多个标签请用,格开',''),(6,'发布时间','pubdate',0,'calendar','0','N',0,'0','',''),(7,'点击数','hits',0,'number','0','N',0,'0','',''),(8,'回复数','comments',0,'number','0','N',0,'0','',''),(9,'属性','type',0,'number','0','N',0,'0','内容附加属性',''),(10,'虚链接','vlink',0,'text','0','N',0,'0','按住Ctrl可多选',''),(11,'置顶权重','top',0,'number','0','N',0,'0','',''),(12,'状态','status',0,'number','1','N',0,'0','1 显示 0不显示',''),(13,'编辑','editor',0,'text','','N',0,'0','',''),(14,'用户ID','userid',0,'number','','N',0,'1','用户或管理员ID',''),(15,'顶+1','good',0,'number','0','N',0,'1','',''),(16,'踩-1','bad',0,'number','0','N',0,'1','',''),(17,'发布类型','postype',0,'number','','N',0,'1','发布类型 [0:用户][1:管理员]','');");
	iCMS_DB::query("ALTER TABLE `#iCMS@__forum` 
	ADD COLUMN `metadata` mediumtext  COLLATE utf8_general_ci NOT NULL after `contentTPL`, 
	ADD COLUMN `contentAttr` mediumtext  COLLATE utf8_general_ci NOT NULL after `metadata`, 
	CHANGE `isexamine` `isexamine` tinyint(1) unsigned   NOT NULL DEFAULT '0' after `contentAttr`, 
	CHANGE `issend` `issend` tinyint(1) unsigned   NOT NULL DEFAULT '1' after `isexamine`, 
	CHANGE `status` `status` tinyint(1) unsigned   NOT NULL DEFAULT '1' after `issend`, COMMENT='';");
	iCMS_DB::query("CREATE TABLE `#iCMS@__model`(
	`id` int(10) unsigned NOT NULL  auto_increment , 
	`name` varchar(100) COLLATE utf8_general_ci NOT NULL  DEFAULT '' , 
	`table` varchar(100) COLLATE utf8_general_ci NOT NULL  DEFAULT '' , 
	`field` text COLLATE utf8_general_ci NOT NULL  , 
	`binding` tinyint(1) unsigned NOT NULL  DEFAULT '0' , 
	`description` varchar(200) COLLATE utf8_general_ci NOT NULL  DEFAULT '' , 
	`position` varchar(10) COLLATE utf8_general_ci NOT NULL  DEFAULT '' , 
	`position2` varchar(10) COLLATE utf8_general_ci NOT NULL  DEFAULT '' , 
	`form` text COLLATE utf8_general_ci NOT NULL  , 
	`show` tinyint(1) NOT NULL  DEFAULT '0' , 
	`addtime` int(10) unsigned NOT NULL  DEFAULT '0' , 
	PRIMARY KEY (`id`) 
	) ENGINE=MyISAM DEFAULT CHARSET='utf8';");
	iCMS_DB::query("ALTER TABLE `#iCMS@__tags` 
	CHANGE `uid` `uid` int(10) unsigned   NOT NULL DEFAULT '0' after `id`, 
	CHANGE `sortid` `sortid` int(10) unsigned   NOT NULL DEFAULT '0' after `uid`, 
	ADD COLUMN `type` int(10) unsigned   NOT NULL DEFAULT '0' after `sortid`, 
	CHANGE `name` `name` varchar(255)  COLLATE utf8_general_ci NOT NULL DEFAULT '' after `type`, 
	ADD COLUMN `keywords` varchar(255)  COLLATE utf8_general_ci NOT NULL DEFAULT '' after `name`, 
	ADD COLUMN `seotitle` varchar(255)  COLLATE utf8_general_ci NOT NULL DEFAULT '' after `keywords`, 
	ADD COLUMN `subtitle` varchar(255)  COLLATE utf8_general_ci NOT NULL DEFAULT '' after `seotitle`, 
	ADD COLUMN `description` text  COLLATE utf8_general_ci NOT NULL after `subtitle`, 
	CHANGE `count` `count` int(10) unsigned   NOT NULL DEFAULT '0' after `description`, 
	ADD COLUMN `weight` smallint(6)   NOT NULL DEFAULT '0' after `count`, 
	CHANGE `link` `link` varchar(255)  COLLATE utf8_general_ci NOT NULL DEFAULT '' after `weight`, 
	CHANGE `tpl` `tpl` varchar(255)  COLLATE utf8_general_ci NOT NULL DEFAULT '' after `link`, 
	CHANGE `ordernum` `ordernum` smallint(5)   NOT NULL DEFAULT '0' after `tpl`, 
	CHANGE `updatetime` `updatetime` int(10) unsigned   NOT NULL DEFAULT '0' after `ordernum`, 
	CHANGE `status` `status` tinyint(1) unsigned   NOT NULL DEFAULT '0' after `updatetime`, 
	ADD KEY `sortid_count`(`sortid`,`count`), COMMENT='';");
	iCMS_DB::query("CREATE TABLE `#iCMS@__stat` (
   `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
   `indexId` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '内容Id',
   `fid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '版块ID',
   `val` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '值',
   `d` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '日',
   `m` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '月',
   `y` smallint(4) unsigned NOT NULL DEFAULT '0' COMMENT '年',
   `ymd` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '年月日',
   `dateline` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '时间戳',
   `type` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '类型 0点击',
   PRIMARY KEY (`id`),
   KEY `ymd` (`fid`,`ymd`,`type`)
 ) ENGINE=MyISAM DEFAULT CHARSET=utf8");
	//更新网站设置
	$tmp=iCMS_DB::getArray("SELECT * FROM `#iCMS@__config`");
	$config_data="<?php\n\t\$config=array(\n";
	for ($i=0;$i<count($tmp);$i++){
		$_config.="\t\t\"".$tmp[$i]['name']."\"=>\"".$tmp[$i]['value']."\",\n";
	}
	$config_data.=substr($_config,0,-2);
	$config_data.="\t\n);?>";
	FS::write(iPATH.'include/site.config.php',$config_data);
	show_msg('网站配置更新完成，进入下一步', '?step=2');
} elseif ($_GET['step'] == 2) {
	//写log
	if(@$fp = fopen($lockfile, 'w')) {
		fwrite($fp, 'iCMS');
		fclose($fp);
	}
	show_msg('升级完成，请到后台工具->更新缓存.为了您的数据安全，避免重复升级，请登录FTP删除本文件!');
}

function getgpc($k, $var='G') {
	switch($var) {
		case 'G': $var = &$_GET; break;
		case 'P': $var = &$_POST; break;
		case 'C': $var = &$_COOKIE; break;
		case 'R': $var = &$_REQUEST; break;
	}
	return isset($var[$k]) ? $var[$k] : NULL;
}


//ob
function obclean() {
	ob_end_clean();
	if (function_exists('ob_gzhandler')) {
		ob_start('ob_gzhandler');
	} else {
		ob_start();
	}
}
//显示
function show_msg($message, $url_forward='') {
	global $_iGLOBAL;

	obclean();

	if($url_forward) {
		$_iGLOBAL['extrahead'] = '<meta http-equiv="refresh" content="100; url='.$url_forward.'">';
		$message = "<a href=\"$url_forward\">$message(跳转中...)</a>";
	} else {
		$_iGLOBAL['extrahead'] = '';
	}

	show_header();
	print<<<END
	<table>
	<tr><td>$message</td></tr>
	</table>
END;
	show_footer();
	exit();
}


//页面头部
function show_header() {
	global $_iGLOBAL;

	$nowarr = array($_GET['step'] => ' class="current"');

	if(empty($_iGLOBAL['extrahead'])) $_iGLOBAL['extrahead'] = '';
	print<<<END
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	$_iGLOBAL[extrahead]
	<title> iCMS 数据库升级程序 </title>
	<style type="text/css">
	* {font-size:12px; font-family: Verdana, Arial, Helvetica, sans-serif; line-height: 1.5em; word-break: break-all; }
	body { text-align:center; margin: 0; padding: 0; background: #EAEAEA; }
	.bodydiv { margin: 40px auto 0; width:720px; text-align:left; border: solid #cccccc; border-width: 5px 1px 1px; background: #FFF; }
	h1 { font-size: 18px; margin: 1px 0 0; line-height: 50px; height: 50px; background: #F7F7F7; padding-left: 10px; }
	#menu {width: 100%; margin: 10px auto; text-align: center; }
	#menu td { height: 30px; line-height: 30px; color: #999; border-bottom: 3px solid #EEE; }
	.current { font-weight: bold; color: #090 !important; border-bottom-color: #F90 !important; }
	.showtable { width:100%; border: solid; border-color:#86B9D6 #B2C9D3 #B2C9D3; border-width: 3px 1px 1px; margin: 10px auto; background: #F5FCFF; }
	.showtable td { padding: 3px; }
	.showtable strong { color: #5086A5; }
	.datatable { width: 100%; margin: 10px auto 25px; }
	.datatable td { padding: 5px 0; border-bottom: 1px solid #EEE; }
	input { border: 1px solid #B2C9D3; padding: 5px; background: #F5FCFF; }
	.button { margin: 10px auto 20px; width: 100%; }
	.button td { text-align: center; }
	.button input, .button button { border: solid; border-color:#F90; border-width: 1px 1px 3px; padding: 5px 10px; color: #090; background: #FFFAF0; cursor: pointer; }
	#footer { line-height: 40px; background: #F7F7F7; text-align: center; height: 38px; overflow: hidden; color: #333333; margin-top: 20px; font-family: "Courier New", Courier, monospace; }
	</style>
	</head>
	<body>
	<div class="bodydiv"><img src="http://www.idreamsoft.com/doc/iCMS.logo.gif" width="172" height="68"  style="margin:5px 0px 3px 5px"/>
	<h1>iCMS数据库升级工具</h1>
	<div style="width:90%;margin:0 auto;">
	<table id="menu">
	<tr>
	<td{$nowarr[0]}>升级开始</td>
	<td{$nowarr[1]}>升级数据库</td>
	<td{$nowarr[2]}>升级完成</td>
	</tr>
	</table>
	<br>
END;
}

//页面顶部
function show_footer() {
	print<<<END
	</div>
	<div id="footer">&copy; iDreamSoft Inc. 2007-2011 http://www.idreamsoft.com</div>
	</div>
	<br>
	</body>
	</html>
END;
}
//判断提交是否正确
function submitcheck($var) {
	if(!empty($_POST[$var]) && $_SERVER['REQUEST_METHOD'] == 'POST') {
		if((empty($_SERVER['HTTP_REFERER']) || preg_replace("/https?:\/\/([^\:\/]+).*/i", "\\1", $_SERVER['HTTP_REFERER']) == preg_replace("/([^\:]+).*/", "\\1", $_SERVER['HTTP_HOST']))) {
			return true;
		} else {
			showmessage('submit_invalid');
		}
	} else {
		return false;
	}
}
//获取到表名
function tname($name) {
	return DB_PREFIX.$name;
}
//对话框
function showmessage(){
		if(!empty($url_forward)) {
			$second = $second * 1000;
			$message .= "<script>setTimeout(\"window.location.href ='$url_forward';\", $second);</script>";
		}
}

//取消HTML代码
function shtmlspecialchars($string) {
	if(is_array($string)) {
		foreach($string as $key => $val) {
			$string[$key] = shtmlspecialchars($val);
		}
	} else {
		$string = preg_replace('/&amp;((#(\d{3,5}|x[a-fA-F0-9]{4})|[a-zA-Z][a-z0-9]{2,5});)/', '&\\1',
			str_replace(array('&', '"', '<', '>'), array('&amp;', '&quot;', '&lt;', '&gt;'), $string));
	}
	return $string;
}
?>