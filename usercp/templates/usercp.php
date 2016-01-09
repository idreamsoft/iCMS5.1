<?php
/**
 * @package iCMS
 * @copyright 2007-2010, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */
!defined('iPATH') && exit('What are you doing?');
$navText='用户中心'; 
$siteportalnav='<ul>';
if(empty($this->module)) $aClass=' class="current"';
$siteportalnav.='<li class="usercenter"><a'.$aClass.' href="./index.php"><span><strong>用户中心</strong></span></a></li>';
if($this->module=='article'){
	$bClass=' class="current"';
	$navText='资讯中心'; 
}
$siteportalnav.='<li><a'.$bClass.' href="./index.php?mo=article"><span><strong>资讯中心</strong></span></a></li>';
$table		= $_GET['table'];
$menuArray	= self::load();
foreach($menuArray AS $key=>$menus) {
	$mKey='header_'.$key;
	if($this->module==$key||$table==$key){
		$cClass=' class="current"';
		$navText=UI::lang($mKey).'中心';
	}
	$siteportalnav.='<li><a'.$cClass.' href="./index.php?mo='.$menus['href'].'"><span><strong>'.UI::lang($mKey).'中心</strong></span></a></li>';
}
$siteportalnav.='</ul>';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo iCMS_CHARSET ;  ?>">
<title><?php echo $navText;?> - <?php echo $this->iCMS->config['name'];?></title>
<script src="<?php echo $this->uiBasePath;?>/jquery.js?ver=1.4.4" type="text/javascript"></script>
<script src="<?php echo $this->uiBasePath;?>/jquery.ui.js?1.8.9" type="text/javascript"></script>
<script src="<?php echo $this->uiBasePath;?>/iCMS.js?ver=5.0" type="text/javascript"></script>
<link rel="stylesheet" href="<?php echo $this->uiBasePath;?>/smoothness/jquery.ui.css?1.8.9" type="text/css" media="all" />
<link rel="stylesheet" type="text/css" href="styles/common.css" />
<script type="text/javascript">
iCMS.publicURL	= '<?php echo $this->iCMS->config['publicURL'];?>';
iCMS.logout = function () {
	$.get(this.publicURL+"/passport.php?do=logout");
	window.location.href=this.publicURL+"/passport.php?do=login";
}
</script>
</head>
<body>
<iframe width="0" height="0" style="display:none" id="iCMS_FRAME" name="iCMS_FRAME"></iframe>
<div id="iCMS_DIALOG" title="iCMS提示" style="display:none"><img src="<?php echo $this->uiBasePath;?>/loading.gif" /></div>
<div class="container">
  <div class="toolbar">
    <div class="clearfix toolbar-inner">
      <div class="quicklink">
        <ul id="website_links" class="accesslink">
          <li><a href="../"><span><?php echo $this->iCMS->config['name'];?></span></a></li>
          <?php
    		include iPATH.'include/function/iCMS.forums.php';
		    $forums = iCMS_forums(array('loop'=>'true','row'=>'12'),$this->iCMS);
		    foreach((array)$forums AS $key=>$val){
		    	echo '<li><a href="'.($val['mode']==0?'../':'').$val['url'].'"><span>'.$val['name'].'</span></a></li>';
		    };
		?>
        </ul>
      </div>
      <div class="userbar"> <a class="username item-expand" id="top_link_center" href="./index.php"><span><?php echo member::$Rs->nickname;?></span></a> <a href="javascript:iCMS.logout();">退出</a> </div>
    </div>
  </div>
  <div class="dropdownmenu-wrap menu-setting" id="centerpopup" style="display:none;">
    <div class="dropdownmenu">
      <div class="clearfix dropdownmenu-inner">
        <div class="clearfix menu-setting-account"> <a class="avatar" href="./index.php?do=avatar"><img src="<?php echo member::avatar();?>" alt="" /></a> <a class="avatar" href="./index.php?do=avatar">上传新头像</a> </div>
        <ul class="clearfix menu-setting-list">
          <li><a href="./index.php?do=setting">修改资料</a></li>
          <li><a href="./index.php?do=avatar">设置头像</a></li>
          <li><a href="./index.php?do=changepassword">修改密码</a></li>
        </ul>
      </div>
    </div>
  </div>
  <script type="text/javascript">
var timeOutID 	= null;
var hidedropDown 	= function(){
    $("#centerpopup").hide();
    $("#top_link_center").removeClass('hover');
};
$("#top_link_center").mouseover(function(){
    window.clearTimeout(timeOutID);
    $("#centerpopup").hide();
    var offset	= $(this).offset();
    $(this).addClass('hover');
    $("#centerpopup").show().css({
        position:"absolute",
        left:offset.left-1,
        top:offset.top+18
    }).mouseover(function(){
        window.clearTimeout(timeOutID);
        $("#centerpopup").show();
        $("#top_link_center").addClass('hover');
    }).mouseout(function(){
        $("#centerpopup").hide();
        $("#top_link_center").removeClass('hover');
    });
}).mouseout(function(){
    timeOutID = window.setTimeout(hidedropDown,1000);
});
</script>
  <div class="header">
    <div class="clearfix header-inner">
      <div class="brand">
        <h1><a href="<?php echo $this->iCMS->config['usercpURL'];?>/index.php" title="我的<?php echo $this->iCMS->config['name'];?> - <?php echo $this->iCMS->config['usercpURL'];?>"><?php echo $this->iCMS->config['name'];?> <?php echo $this->iCMS->config['domain'];?></a></h1>
        <h2><a href="./index.php"><?php echo $navText;?></a></h2>
      </div>
    </div>
  </div>
  <div class="clearfix siteportalnav"> <?php echo $siteportalnav;?> </div>
  <div class="subnav">
    <div class="clearfix subnav-inner">
      <div class="crumbnav"> <a href="<?php echo $this->iCMS->config['url'];?>"><span><?php echo $this->iCMS->config['name'];?></span></a> <span class="separator">&raquo;</span> <span class="current"><span><a href="<?php echo $this->iCMS->config['usercpURL'];?>/index.php" id="max_nav_root_1"><span><?php echo $navText;?></span></a> </span></span> </div>
    </div>
  </div>
  <div id="main" class="main section-default">
    <div class="clearfix main-inner">
      <div class="content">
        <div class="clearfix content-inner">
          <div class="content-main">
            <div class="content-main-inner">
              <?php include usercp::ui();?>
            </div>
          </div>
        </div>
      </div>
      <div class="sidebar">
        <div class="sidebar-inner">
          <?php usercp::menu();?>
        </div>
      </div>
    </div>
  </div>
  <div class="footer">
    <div class="clearfix footer-inner">
      <p class="extralink"> <a href="#about.html">关于<?php echo $this->iCMS->config['name'];?></a> - <a href="#contact.html">联系我们</a> - <a href="#">广告服务</a> - <a href="#">友情链接</a> - <a href="#sitemap.html">网站地图</a> - <a href="#announce.html">版权声明</a> - <a href="#job.html">人才招聘</a> - <a href="#faq.html">帮助</a> </p>
      <p class="copyright"> CopyRight &copy; 2007-2011 <a href="<?php echo $this->iCMS->config['url'];?>"><?php echo $this->iCMS->config['name'];?></a> <?php echo $this->iCMS->config['domain'];?> All Rights Reserved. <a rel="nofollow" href="http://www.miibeian.gov.cn/"><?php echo $this->iCMS->config['icp'];?></a></p>
    </div>
  </div>
</div>
</body>
</html>