<?php
/**
 * @package iCMS
 * @copyright 2007-2010, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */
!defined('iPATH') && exit('What are you doing?');
?>

<div class="sidebarmenu">
  <div class="sidebarmenu-inner">
    <div class="sidebarmenu-list">
      <ul>
        <li><a style="background-image:url(./images/icons/article_write.gif);"  href="./index.php?mo=article&do=add">我要投稿</a></li>
        <li><a style="background-image:url(./images/icons/archives.gif);"  href="./index.php?mo=article&do=manage">我的文章</a></li>
        <!--li><a style="background-image:url(./images/icons/folder.gif);"  href="./index.php?mo=file&do=manage">文件管理</a></li-->
        <li><a style="background-image:url(./images/icons/doing.gif);"  href="./index.php?mo=comment&mid=0">文章评论</a></li>
        <li><a style="background-image:url(./images/icons/doing.gif);"  href="./index.php?mo=comment&do=my">我的评论</a></li>
      </ul>
    </div>
  </div>
</div>
<div class="mycolumnbutton"> <a title="我的专栏" target="_blank" href="<?php echo $this->iCMS->config['publicURL'];?>/member.php?uid=<?php echo member::$uId;?>">我的专栏</a> </div>
<div class="sidebarmenu memberranking">
  <div class="sidebarmenu-inner">
    <div class="sidebarmenu-list">
      <h3>会员排行</h3>
      <ol>
        <li><a target="_blank" href="<?php echo $this->iCMS->config['publicURL'];?>/member.php?uid=123">评述网</a></li>
      </ol>
    </div>
  </div>
</div>
