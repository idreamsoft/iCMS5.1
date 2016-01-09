<?php
/**
 * @package iCMS
 * @copyright 2007-2010, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */
!defined('iPATH') && exit('What are you doing?');
?>

<div class="systemnotice-wrap">
  <div class="systemnotice">
    <div class="systemnotice-inner">
      <div class="forumnotice" id="forumnotice">
        <ul class="forumnotice-list" id="forumnotice_list">
          <li>没有任何公告</li>
        </ul>
      </div>
    </div>
  </div>
</div>
<div class="pwrap accountinfo">
  <div class="phead">
    <div class="phead-inner">
      <div class="phead-inner">
        <h3 class="ptitle"><span>帐号信息</span></h3>
      </div>
    </div>
  </div>
  <div class="pbody">
    <div class="clearfix accountinfo-dock">
      <div class="account-avatar"> <a href="index.php?do=avatar" title="更改头像"> <img src="<?php echo member::avatar(48);?>" alt="" width="48" height="48" /> <span class="avatar-change">更改头像</span> </a> </div>
      <div class="account-quicktools"> <a class="article-publish" href="index.php?mo=article&do=add" title="发布文章">发布文章</a> </div>
      <div class="account-uesrinfo"> <span class="account-name"><?php echo member::$Rs->nickname;?></span> <span class="account-id">ID: <?php echo member::$uId;?></span></div>
      <div class="account-baseinfo"> <span>E-mail: <?php echo member::$Rs->username;?></span> <span>注册时间: <?php echo get_date(member::$Rs->regtime,'Y-m-d');?></span> </div>
      <div class="account-track"> <span>上次访问时间: <?php echo get_date(member::$Rs->lastlogintime,'Y-m-d');?></span> <span>上次访问IP: <?php echo member::$Rs->lastip;?></span> </div>
    </div>
    <ul class="clearfix statistics-list">
      <li class="myurl"><span class="label">我的专栏地址:</span> <span class="value"><a target="_blank" href="<?php echo $this->iCMS->config['publicURL'];?>/member.php?uid=<?php echo member::$uId;?>"><?php echo $this->iCMS->config['publicURL'];?>/member.php?uid=<?php echo member::$uId;?></a></span></li>
      <li><span class="label">我发表的文章:</span> <span class="value">0</span></li>
      <li><span class="label">专栏访问量:</span> <span class="value">0</span></li>
      <li><span class="label">文章点击率:</span> <span class="value">0</span></li>
    </ul>
  </div>
  <div class="pfoot">
    <p><b>-</b></p>
  </div>
</div>
<div class="pwrap pwrap-simple articlerecent">
  <div class="phead">
    <div class="phead-inner">
      <div class="phead-inner">
        <h3 class="ptitle"><span>最新文章</span></h3>
      </div>
    </div>
  </div>
  <div class="pbody">
    <div class="articlerecent-hot">
      <h3>最新热门文章</h3>
      <ul class="articlelist">
        <?php
		include iPATH.'include/function/iCMS.list.php';
        $rs	= iCMS_list(array('loop'=>'true','orderby'=>'hot'),$this->iCMS);
     	foreach((array)$rs AS $key=>$art){
			stristr($art['url'], 'show.php') && $art['url']='../'.$art['url'];
	  ?>
        <li><a href="<?php echo $art['url'] ; ?>" class="title" target="_blank"><?php echo $art['title'] ; ?> </a> <span class="date"><?php echo get_date($art['pubdate'],'Y-m-d');?></span> </li>
        <?php }  ?>
      </ul>
    </div>
    <div class="articlerecent-my">
      <h3>最新通过审核的文章</h3>
      <ul class="articlelist">
        <?php
        $rs	= iCMS_list(array('loop'=>'true','call'=>'user'),$this->iCMS);
     	foreach((array)$rs AS $key=>$art){
			stristr($art['url'], 'show.php') && $art['url']='../'.$art['url'];
	  ?>
        <li><a href="<?php echo $art['url'] ; ?>" class="title" target="_blank"><?php echo $art['title'] ; ?> </a> <span class="date"><?php echo get_date($art['pubdate'],'Y-m-d');?></span> </li>
        <?php }  ?>
      </ul>
    </div>
  </div>
  <div class="pfoot">
    <p><b>-</b></p>
  </div>
</div>
