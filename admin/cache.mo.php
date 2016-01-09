<?php
/**
 * @package iCMS
 * @copyright 2007-2010, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */
!defined('iPATH') && exit('What are you doing?');
include_once iPATH.'include/tag.class.php';
class cache extends AdminCP {
    function doDefault() {
        member::MP("menu_cache");
        include admincp::tpl("cache");
    }
    function doUpdate() {
		if($_POST['forum']) {
		    $forum =new forum();
		    $forum->cache();
		}
		if($_POST['adm']) {
		    include_once iPATH.'admin/advertise.mo.php';
		    $rs=iCMS_DB::getArray("SELECT * FROM `#iCMS@__advertise`",OBJECT);
		    $_count=count($rs);
		    for ($i=0;$i<$_count;$i++) {
		    	adm($rs[$i]);
		    }
		}
        $_POST['tpl']		&& $this->iCMS->clear_compiled_tpl();
        $_POST['iCMS_list']	&& FS::rmdir(iPATH.'cache/list');
        $_POST['iCMS_forum']	&& FS::rmdir(iPATH.'cache/forum');
        $_POST['iCMS_tag']	&& FS::rmdir(iPATH.'cache/tags');
        if($_POST['iCMS_ALL']){
            FS::rmdir(iPATH.'cache/list');
            FS::rmdir(iPATH.'cache/forum');
            FS::rmdir(iPATH.'cache/tags');
        }
        $_POST['keywords']	&& keywords_cache();
        $_POST['tags'] && iTAG::cache();
        if($_POST['model']){
        	include_once iPATH.'include/model.class.php';
			model::cache();
        }

        $_POST['config']	&& CreateConfigFile();

        if($_POST['Re-Article-Count']) {
            $rs=iCMS_DB::getArray("SELECT fid FROM `#iCMS@__forum`");
            $_count=count($rs);
            for ($i=0;$i<$_count;$i++) {
                $c=iCMS_DB::getValue("SELECT count(*) FROM #iCMS@__article where `fid`='".$rs[$i]['fid']."' LIMIT 1 ");
                iCMS_DB::query("UPDATE `#iCMS@__forum` SET `count` ='$c' WHERE `fid` ='".$rs[$i]['fid']."' LIMIT 1 ");
            }
        }
        if($_POST['Re-Tag-Count']) {
            $rs=iCMS_DB::getArray("SELECT id FROM `#iCMS@__tags`");
            $_count=count($rs);
            for ($i=0;$i<$_count;$i++) {
                $_count=iCMS_DB::getValue("SELECT count(*) FROM `#iCMS@__taglist` WHERE `tid`='".$rs[$i]['id']."'");
                iCMS_DB::query("UPDATE `#iCMS@__tags` SET `count` = '$_count'  WHERE `id` ='".$rs[$i]['id']."'");
                iTAG::cache($rs[$i]['id']);
            }
        }

        javascript::dialog("执行完毕！",'url:1');
    }
}

