<?php
/**
 * @package iCMS
 * @copyright 2007-2010, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */
!defined('iPATH') && exit('What are you doing?');
class dialog extends UserCP {
    function doviewPic() {
		echo '<img src="'.FS::fp($_GET['callback']).'"/>';
    }
}
