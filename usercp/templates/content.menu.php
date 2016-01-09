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
<?php
$mid	= (int)$_GET['mid'];
$model	= $this->iCMS->getCache('system/models.cache',$mid);
if($model['show']){
	$aText=UI::lang('header_'.$model['table']);
	$aUrl='./index.php?mo=content&mid='.$model['id'].'&table='.$model['table'];
	echo '<li><a style="background-image:url(./images/icons/article_write.gif);"  href="'.$aUrl.'&do=add">我要投稿</a></li>';
	echo '<li><a style="background-image:url(./images/icons/default.gif);" href="'.$aUrl.'">我的'.$aText.'</a></li>';
	echo '<li><a style="background-image:url(./images/icons/doing.gif);"  href="./index.php?mo=comment&mid='.$model['id'].'">'.$aText.'评论</a></li>';
}
?>
      </ul>
    </div>
  </div>
</div>
