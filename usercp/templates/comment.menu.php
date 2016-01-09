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
        <li><a style="background-image:url(./images/icons/doing.gif);"  href="./index.php?mo=comment&do=my">我的评论</a></li>
        <li><a style="background-image:url(./images/icons/doing.gif);"  href="./index.php?mo=comment&mid=0">文章评论</a></li>
    	<?php
    	$_cache	= $this->iCMS->getCache('system/models.cache');
		foreach((array)$_cache AS $_mid=>$_m){
			echo '<li><a style="background-image:url(./images/icons/doing.gif);"  href="./index.php?mo=comment&mid='.$_m['id'].'">'.$_m['name'].'评论</a></li>';
		}
    	?>
      </ul>
    </div>
  </div>
</div>