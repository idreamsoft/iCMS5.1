/*Table structure for table `#iCMS@__advertise` */

DROP TABLE IF EXISTS `#iCMS@__advertise`;

CREATE TABLE `#iCMS@__advertise` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `varname` varchar(50) NOT NULL DEFAULT '',
  `title` varchar(255) NOT NULL DEFAULT '',
  `style` enum('code','text','image','flash') NOT NULL DEFAULT 'code',
  `starttime` int(10) unsigned NOT NULL DEFAULT '0',
  `endtime` int(10) unsigned NOT NULL DEFAULT '0',
  `code` mediumtext NOT NULL,
  `load` varchar(10) NOT NULL DEFAULT '',
  `status` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `varname` (`varname`)
) ENGINE=MyISAM AUTO_INCREMENT=26 DEFAULT CHARSET=utf8;

/*Data for the table `#iCMS@__advertise` */

LOCK TABLES `#iCMS@__advertise` WRITE;

insert  into `#iCMS@__advertise`(`id`,`varname`,`title`,`style`,`starttime`,`endtime`,`code`,`load`,`status`) values (11,'全站顶部广告','全站顶部广告','code',1278259200,0,'','','0'),(14,'内容页标题下面','内容页标题下面','code',1297872000,0,'','','0'),(12,'内容页顶部通栏下面','内容页顶部通栏下面','code',1290700800,0,'','','0'),(13,'内容页标题上面','内容页标题上面','code',1297872000,0,'','','0'),(15,'内容页内容上面','内容页内容上面','code',1297872000,0,'','','0'),(16,'内容页分页上面','内容页分页上面','code',1297872000,0,'','','0'),(17,'内容页分页下面','内容页分页下面','code',1297872000,0,'','','0'),(18,'内容页右侧第一栏','内容页右侧第一栏','code',1297872000,0,'','','0'),(19,'内容页右侧第二栏','内容页右侧第二栏','code',1297872000,0,'','','0'),(20,'内容页右侧第三栏','内容页右侧第三栏','code',1297872000,0,'','','0'),(21,'内容页右侧第四栏','内容页右侧第四栏','code',1297872000,0,'','','0'),(22,'内容页右侧第五栏','内容页右侧第五栏','code',1297872000,0,'','','0'),(23,'列表FI页右侧第一栏','列表 forum.index 页右侧第一栏','code',1297872000,0,'','','0'),(24,'列表FL页右侧第一栏','列表forum.list页右侧第一栏','code',1297872000,0,'','','0'),(25,'列表BL页右侧第一栏','列表forum.bloglist页右侧第一栏','code',1297872000,0,'','','0');

UNLOCK TABLES;

/*Table structure for table `#iCMS@__article` */

DROP TABLE IF EXISTS `#iCMS@__article`;

CREATE TABLE `#iCMS@__article` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `orderNum` smallint(6) NOT NULL DEFAULT '0',
  `title` varchar(255) NOT NULL DEFAULT '',
  `stitle` varchar(255) NOT NULL DEFAULT '',
  `clink` varchar(255) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `source` varchar(100) NOT NULL DEFAULT '',
  `author` varchar(50) NOT NULL DEFAULT '',
  `editor` varchar(200) NOT NULL DEFAULT '',
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `isPic` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `pic` varchar(255) NOT NULL DEFAULT '',
  `keywords` varchar(255) NOT NULL DEFAULT '',
  `tags` varchar(255) NOT NULL DEFAULT '',
  `description` varchar(255) NOT NULL DEFAULT '',
  `related` text NOT NULL,
  `metadata` text NOT NULL,
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
  KEY `pic` (`isPic`,`id`),
  KEY `topord` (`top`,`orderNum`),
  KEY `userid` (`userid`),
  KEY `postype` (`postype`,`id`),
  KEY `status` (`status`,`postype`,`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `#iCMS@__article` */

LOCK TABLES `#iCMS@__article` WRITE;

UNLOCK TABLES;

/*Table structure for table `#iCMS@__article_data` */

DROP TABLE IF EXISTS `#iCMS@__article_data`;

CREATE TABLE `#iCMS@__article_data` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `aid` int(10) unsigned NOT NULL DEFAULT '0',
  `subtitle` varchar(255) NOT NULL DEFAULT '',
  `tpl` varchar(255) NOT NULL DEFAULT '',
  `body` mediumtext NOT NULL,
  PRIMARY KEY (`id`),
  KEY `aid` (`aid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `#iCMS@__article_data` */

LOCK TABLES `#iCMS@__article_data` WRITE;

UNLOCK TABLES;

/*Table structure for table `#iCMS@__comment` */

DROP TABLE IF EXISTS `#iCMS@__comment`;

CREATE TABLE `#iCMS@__comment` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `mId` int(10) unsigned NOT NULL DEFAULT '0',
  `sortId` int(10) unsigned NOT NULL DEFAULT '0',
  `indexId` int(10) unsigned NOT NULL DEFAULT '0',
  `userId` int(10) unsigned NOT NULL DEFAULT '0',
  `username` varchar(50) NOT NULL DEFAULT '',
  `title` varchar(100) NOT NULL DEFAULT '',
  `contents` mediumtext NOT NULL,
  `quote` int(11) unsigned NOT NULL DEFAULT '0',
  `floor` int(11) unsigned NOT NULL DEFAULT '0',
  `reply` int(11) unsigned NOT NULL DEFAULT '0',
  `up` int(10) unsigned NOT NULL DEFAULT '0',
  `down` int(10) unsigned NOT NULL DEFAULT '0',
  `ip` varchar(20) NOT NULL DEFAULT '',
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `id` (`mId`,`sortId`,`status`,`indexId`,`id`),
  KEY `addtime` (`mId`,`sortId`,`status`,`indexId`,`addtime`),
  KEY `ua` (`up`,`down`),
  KEY `quote` (`quote`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `#iCMS@__comment` */

LOCK TABLES `#iCMS@__comment` WRITE;

UNLOCK TABLES;

/*Table structure for table `#iCMS@__config` */

DROP TABLE IF EXISTS `#iCMS@__config`;

CREATE TABLE `#iCMS@__config` (
  `name` varchar(100) NOT NULL DEFAULT '',
  `value` mediumtext NOT NULL,
  PRIMARY KEY (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `#iCMS@__config` */

LOCK TABLES `#iCMS@__config` WRITE;

insert  into `#iCMS@__config`(`name`,`value`) values ('name','iCMS内容管理系统'),('seotitle','iDreamSoft'),('keywords','iCMS内容管理系统'),('description','iCMS 是一个采用 PHP 和 MySQL 数据库构建的高效内容管理系统,为中小型网站提供一个完美的解决方案。'),('icp','ICP备案号'),('masteremail','admin@domain.com'),('template','default'),('indexname','index'),('indexTPL','{TPL}/index.htm'),('debug','1'),('tpldebug','1'),('language','zh-cn'),('setupURL','http://v5.icms.com'),('publicURL','http://v5.icms.com/public'),('htmlURL','http://v5.icms.com/html'),('usercpURL','http://v5.icms.com/usercp'),('htmldir',''),('htmlext','.html'),('enable_xmlrpc','1'),('tagRule','{PHP}'),('iscache','0'),('cachedir','cache'),('cachelevel','0'),('cachetime','300'),('iscachegzip','0'),('cacheEngine','file'),('cacheServers','127.0.0.1:11211'),('uploadURL','http://v5.icms.com/uploadfiles'),('remoteKey','123213'),('uploadScript','iCMS.upload.php'),('uploadfiledir','uploadfiles'),('savedir','y-m-d'),('fileext','gif,jpg,rar,swf,jpeg,png'),('isthumb','1'),('thumbwidth','140'),('thumbhight','140'),('iswatermark','1'),('waterwidth','120'),('waterheight','120'),('waterpos','9'),('waterimg','watermark.png'),('watertext','iCMS'),('waterfont',''),('waterfontsize','12'),('watercolor','#000000'),('waterpct','80'),('iscomment','1'),('anonymous','1'),('isexamine','0'),('anonymousname','网友'),('autoformat','1'),('keywordToTag','0'),('remote','1'),('autopic','0'),('autodesc','1'),('descLen','100'),('repeatitle','0'),('ServerTimeZone','8'),('cvtime','0'),('dateformat','Y-m-d H:i:s'),('CLsplit',','),('diggtime','0'),('kwCount','-1'),('issmall','1'),('AutoPage','1'),('AutoPageLen','1000'),('tagURL','http://v5.icms.com'),('thumbwatermark','0'),('tagDir','html'),('autopatch','1'),('seccode','0'),('enable_register','1'),('userseccode','0'),('agreement','当您申请用户时，表示您已经同意遵守本规章。 \r\n\r\n欢迎您加入本站点参加交流和讨论，为维护网上公共秩序和社会稳定，请您自觉遵守以下条款： \r\n\r\n一、不得利用本站危害国家安全、泄露国家秘密，不得侵犯国家社会集体的和公民的合法权益，不得利用本站制作、复制和传播下列信息：\r\n（一）煽动抗拒、破坏宪法和法律、行政法规实施的； \r\n（二）煽动颠覆国家政权，推翻社会主义制度的；\r\n（三）煽动分裂国家、破坏国家统一的； \r\n（四）煽动民族仇恨、民族歧视，破坏民族团结的； \r\n（五）捏造或者歪曲事实，散布谣言，扰乱社会秩序的； \r\n（六）宣扬封建迷信、淫秽、色情、赌博、暴力、凶杀、恐怖、教唆犯罪的； \r\n（七）公然侮辱他人或者捏造事实诽谤他人的，或者进行其他恶意攻击的； \r\n（八）损害国家机关信誉的； \r\n（九）其他违反宪法和法律行政法规的； \r\n（十）进行商业广告行为的。 \r\n\r\n二、互相尊重，对自己的言论和行为负责。\r\n三、禁止在申请用户时使用相关本站的词汇，或是带有侮辱、毁谤、造谣类的或是有其含义的各种语言进行注册用户，否则我们会将其删除。\r\n四、禁止以任何方式对本站进行各种破坏行为。\r\n五、如果您有违反国家相关法律法规的行为，本站概不负责，您的登录论坛信息均被记录无疑，必要时，我们会向相关的国家管理部门提供此类信息。');

UNLOCK TABLES;

/*Table structure for table `#iCMS@__field` */

DROP TABLE IF EXISTS `#iCMS@__field`;

CREATE TABLE `#iCMS@__field` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `field` varchar(255) NOT NULL DEFAULT '',
  `mid` int(10) unsigned NOT NULL DEFAULT '0',
  `type` varchar(255) NOT NULL DEFAULT '',
  `default` varchar(255) NOT NULL DEFAULT '',
  `validate` varchar(255) NOT NULL DEFAULT '',
  `show` tinyint(1) NOT NULL DEFAULT '0',
  `hidden` enum('0','1') NOT NULL DEFAULT '0',
  `description` varchar(255) NOT NULL DEFAULT '',
  `option` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC;

/*Data for the table `#iCMS@__field` */

LOCK TABLES `#iCMS@__field` WRITE;

insert  into `#iCMS@__field`(`id`,`name`,`field`,`mid`,`type`,`default`,`validate`,`show`,`hidden`,`description`,`option`) values (1,'栏目','fid',0,'number','','0',1,'0','',''),(2,'排序','orderNum',0,'number','0','N',0,'0','',''),(3,'标题','title',0,'text','','0',1,'0','',''),(4,'自定义链接','clink',0,'text','0','N',0,'0','只能由英文字母、数字或_-组成(不支持中文),留空则自动以标题拼音填充',''),(5,'标签','tags',0,'text','','N',1,'0','多个标签请用,格开',''),(6,'发布时间','pubdate',0,'calendar','0','N',0,'0','',''),(7,'点击数','hits',0,'number','0','N',0,'0','',''),(8,'回复数','comments',0,'number','0','N',0,'0','',''),(9,'属性','type',0,'number','0','N',0,'0','内容附加属性',''),(10,'虚链接','vlink',0,'text','0','N',0,'0','按住Ctrl可多选',''),(11,'置顶权重','top',0,'number','0','N',0,'0','',''),(12,'状态','status',0,'number','1','N',0,'0','1 显示 0不显示',''),(13,'编辑','editor',0,'text','','N',0,'0','',''),(14,'用户ID','userid',0,'number','','N',0,'1','用户或管理员ID',''),(15,'顶+1','good',0,'number','0','N',0,'1','',''),(16,'踩-1','bad',0,'number','0','N',0,'1','',''),(17,'发布类型','postype',0,'number','','N',0,'1','发布类型 [0:用户][1:管理员]','');

UNLOCK TABLES;

/*Table structure for table `#iCMS@__file` */

DROP TABLE IF EXISTS `#iCMS@__file`;

CREATE TABLE `#iCMS@__file` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `aid` int(10) unsigned NOT NULL DEFAULT '0',
  `filename` varchar(200) NOT NULL DEFAULT '',
  `ofilename` varchar(200) NOT NULL DEFAULT '',
  `path` varchar(250) NOT NULL DEFAULT '',
  `intro` varchar(200) NOT NULL DEFAULT '',
  `ext` varchar(10) NOT NULL DEFAULT '',
  `size` int(10) unsigned NOT NULL DEFAULT '0',
  `time` int(10) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `aid` (`aid`),
  KEY `ext` (`ext`),
  KEY `path` (`path`),
  KEY `filename` (`filename`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `#iCMS@__file` */

LOCK TABLES `#iCMS@__file` WRITE;

UNLOCK TABLES;

/*Table structure for table `#iCMS@__forum` */

DROP TABLE IF EXISTS `#iCMS@__forum`;

CREATE TABLE `#iCMS@__forum` (
  `fid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL DEFAULT '',
  `subname` varchar(100) NOT NULL DEFAULT '',
  `rootid` int(10) unsigned NOT NULL DEFAULT '0',
  `modelid` int(10) NOT NULL DEFAULT '0',
  `orderNum` smallint(6) unsigned NOT NULL DEFAULT '0',
  `password` varchar(100) NOT NULL DEFAULT '',
  `title` varchar(200) DEFAULT '',
  `keywords` varchar(200) NOT NULL DEFAULT '',
  `description` varchar(200) NOT NULL DEFAULT '',
  `dir` varchar(255) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `pic` varchar(255) NOT NULL DEFAULT '',
  `count` int(10) unsigned NOT NULL DEFAULT '0',
  `attr` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `mode` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `domain` varchar(255) NOT NULL DEFAULT '',
  `htmlext` varchar(10) NOT NULL DEFAULT '',
  `forumRule` varchar(255) NOT NULL DEFAULT '',
  `contentRule` varchar(255) NOT NULL DEFAULT '',
  `indexTPL` varchar(100) NOT NULL DEFAULT '',
  `listTPL` varchar(100) NOT NULL DEFAULT '',
  `contentTPL` varchar(100) NOT NULL DEFAULT '',
  `metadata` mediumtext NOT NULL,
  `contentAttr` mediumtext NOT NULL,
  `isexamine` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `issend` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`fid`),
  KEY `status` (`status`,`orderNum`,`fid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `#iCMS@__forum` */

LOCK TABLES `#iCMS@__forum` WRITE;

UNLOCK TABLES;

/*Table structure for table `#iCMS@__group` */

DROP TABLE IF EXISTS `#iCMS@__group`;

CREATE TABLE `#iCMS@__group` (
  `gid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL DEFAULT '',
  `order` smallint(5) unsigned NOT NULL DEFAULT '0',
  `power` mediumtext NOT NULL,
  `cpower` mediumtext NOT NULL,
  `type` enum('a','u') NOT NULL DEFAULT 'a',
  PRIMARY KEY (`gid`),
  KEY `type` (`type`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC;

/*Data for the table `#iCMS@__group` */

LOCK TABLES `#iCMS@__group` WRITE;

insert  into `#iCMS@__group`(`gid`,`name`,`order`,`power`,`cpower`,`type`) values (1,'超级管理员',1,'ADMINCP,header_index,menu_index_home,menu_index_article_add,menu_index_comment,menu_index_article_user_draft,menu_index_link,menu_index_advertise,header_setting,menu_setting_all,menu_setting_config,menu_setting_url,menu_setting_cache,menu_setting_attachments,menu_setting_watermark,menu_setting_publish,menu_setting_time,menu_setting_other,header_article,menu_article_add,menu_article_manage,menu_article_draft,menu_article_trash,menu_article_user_manage,menu_article_user_draft,menu_comment,menu_contentype,menu_filter,menu_tag_manage,menu_keywords,menu_search,header_user,menu_user_manage,menu_account_manage,menu_account_edit,header_extend,menu_model_manage,menu_field_manage,header_html,menu_html_all,menu_html_index,menu_html_article,menu_html_tag,menu_html_page,menu_setting_url,header_tools,menu_link,menu_file_manage,menu_file_upload,menu_extract_pic,menu_advertise,menu_message,menu_cache,menu_template_manage,menu_database_backup,menu_database_recover,menu_database_repair,menu_database_replace,Allow_View_Article,Allow_Edit_Article','1,3,5,4,2,6,7,8','a'),(2,'管理员',2,'ADMINCP,header_index,menu_index_home,menu_index_catalog_add,menu_index_article_add,menu_index_comment,menu_index_article_user_draft,menu_index_link,menu_index_advertise,menu_setting_url,header_article,menu_catalog_add,menu_catalog_manage,menu_article_add,menu_article_manage,menu_article_draft,menu_article_trash,menu_article_user_manage,menu_article_user_draft,menu_comment,menu_contentype,menu_article_default,menu_filter,menu_tag_manage,menu_keywords,menu_search,menu_push_add,menu_push_forum,menu_push_manage,header_user,menu_user_manage,header_html,menu_html_all,menu_html_index,menu_html_catalog,menu_html_article,menu_html_tag,menu_html_page,menu_setting_url,header_tools,menu_link,menu_file_manage,menu_file_upload,menu_extract_pic,menu_advertise,menu_message,menu_cache,menu_template_manage,Allow_View_Article,Allow_Edit_Article','1,3,5,4','a'),(3,'编辑',3,'ADMINCP,header_index,menu_index_home,menu_index_article_add,menu_index_article_user_draft,header_article,menu_article_add,menu_article_manage,menu_article_draft,menu_article_trash,menu_article_user_manage,menu_article_user_draft,menu_comment','','a'),(4,'会员',1,'','','u');

UNLOCK TABLES;

/*Table structure for table `#iCMS@__keywords` */

DROP TABLE IF EXISTS `#iCMS@__keywords`;

CREATE TABLE `#iCMS@__keywords` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `keyword` varchar(200) NOT NULL DEFAULT '',
  `replace` text NOT NULL,
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `keyword` (`keyword`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `#iCMS@__keywords` */

LOCK TABLES `#iCMS@__keywords` WRITE;

UNLOCK TABLES;

/*Table structure for table `#iCMS@__links` */

DROP TABLE IF EXISTS `#iCMS@__links`;

CREATE TABLE `#iCMS@__links` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sortid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `name` varchar(100) NOT NULL DEFAULT '',
  `logo` varchar(200) NOT NULL DEFAULT '',
  `url` varchar(200) NOT NULL DEFAULT '',
  `desc` text NOT NULL,
  `orderNum` smallint(5) NOT NULL DEFAULT '0',
  UNIQUE KEY `id` (`id`),
  KEY `orderid` (`orderNum`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `#iCMS@__links` */

LOCK TABLES `#iCMS@__links` WRITE;

UNLOCK TABLES;

/*Table structure for table `#iCMS@__members` */

DROP TABLE IF EXISTS `#iCMS@__members`;

CREATE TABLE `#iCMS@__members` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `groupid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `username` varchar(50) NOT NULL DEFAULT '',
  `password` varchar(32) NOT NULL DEFAULT '',
  `nickname` varchar(255) NOT NULL DEFAULT '',
  `gender` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `info` mediumtext NOT NULL,
  `power` mediumtext NOT NULL,
  `cpower` mediumtext NOT NULL,
  `regtime` int(10) unsigned DEFAULT '0',
  `lastip` varchar(15) NOT NULL DEFAULT '',
  `lastlogintime` int(10) unsigned NOT NULL DEFAULT '0',
  `logintimes` smallint(5) unsigned NOT NULL DEFAULT '0',
  `post` int(10) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(1) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`uid`),
  KEY `username` (`username`),
  KEY `groupid` (`groupid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `#iCMS@__members` */

LOCK TABLES `#iCMS@__members` WRITE;

UNLOCK TABLES;

/*Table structure for table `#iCMS@__model` */

DROP TABLE IF EXISTS `#iCMS@__model`;

CREATE TABLE `#iCMS@__model` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL DEFAULT '',
  `table` varchar(100) NOT NULL DEFAULT '',
  `field` text NOT NULL,
  `binding` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `description` varchar(200) NOT NULL DEFAULT '',
  `position` varchar(10) NOT NULL DEFAULT '',
  `position2` varchar(10) NOT NULL DEFAULT '',
  `form` text NOT NULL,
  `show` tinyint(1) NOT NULL DEFAULT '0',
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `#iCMS@__model` */

LOCK TABLES `#iCMS@__model` WRITE;

UNLOCK TABLES;

/*Table structure for table `#iCMS@__search` */

DROP TABLE IF EXISTS `#iCMS@__search`;

CREATE TABLE `#iCMS@__search` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `search` varchar(200) NOT NULL DEFAULT '',
  `times` int(10) unsigned NOT NULL DEFAULT '0',
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `search` (`search`,`times`),
  KEY `searchid` (`search`,`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `#iCMS@__search` */

LOCK TABLES `#iCMS@__search` WRITE;

UNLOCK TABLES;

/*Table structure for table `#iCMS@__taglist` */

DROP TABLE IF EXISTS `#iCMS@__taglist`;

CREATE TABLE `#iCMS@__taglist` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `indexId` int(10) unsigned NOT NULL DEFAULT '0',
  `tid` int(10) unsigned NOT NULL DEFAULT '0',
  `modelId` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `tid` (`tid`,`indexId`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `#iCMS@__taglist` */

LOCK TABLES `#iCMS@__taglist` WRITE;

UNLOCK TABLES;

/*Table structure for table `#iCMS@__tags` */

DROP TABLE IF EXISTS `#iCMS@__tags`;

CREATE TABLE `#iCMS@__tags` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `sortid` int(10) unsigned NOT NULL DEFAULT '0',
  `type` int(10) unsigned NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL DEFAULT '',
  `keywords` varchar(255) NOT NULL DEFAULT '',
  `seotitle` varchar(255) NOT NULL DEFAULT '',
  `subtitle` varchar(255) NOT NULL DEFAULT '',
  `description` text NOT NULL,
  `count` int(10) unsigned NOT NULL DEFAULT '0',
  `weight` smallint(6) NOT NULL DEFAULT '0',
  `link` varchar(255) NOT NULL DEFAULT '',
  `tpl` varchar(255) NOT NULL DEFAULT '',
  `ordernum` smallint(5) NOT NULL DEFAULT '0',
  `updatetime` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `name` (`name`),
  KEY `sortid` (`sortid`,`ordernum`),
  KEY `sortid_2` (`sortid`,`id`),
  KEY `count` (`count`),
  KEY `link` (`link`),
  KEY `sortid_count` (`sortid`,`count`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `#iCMS@__tags` */

LOCK TABLES `#iCMS@__tags` WRITE;

UNLOCK TABLES;

/*Table structure for table `#iCMS@__vlink` */

DROP TABLE IF EXISTS `#iCMS@__vlink`;

CREATE TABLE `#iCMS@__vlink` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `indexId` int(10) unsigned NOT NULL DEFAULT '0',
  `sortId` int(10) unsigned NOT NULL DEFAULT '0',
  `modelId` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `sortid` (`sortId`,`indexId`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `#iCMS@__vlink` */

LOCK TABLES `#iCMS@__vlink` WRITE;

UNLOCK TABLES;

DROP TABLE IF EXISTS `#iCMS@__stat`;

CREATE TABLE `#iCMS@__stat` (
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
 ) ENGINE=MyISAM DEFAULT CHARSET=utf8;

LOCK TABLES `#iCMS@__stat` WRITE;

UNLOCK TABLES;