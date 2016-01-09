<?php
/**
 * @package iCMS
 * @copyright 2007-2010, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */
!defined('iPATH') && exit('What are you doing?');
class account extends AdminCP { 
    function domanage() {
        member::MP("menu_account_manage");
        include iPATH.'include/group.class.php';
        $group =new group('a');
        $maxperpage =20;
        $total=($page==1||empty($_GET['rowNum']))?iCMS_DB::getValue("SELECT count(*) FROM `#iCMS@__members` where `type`='1'"):(int)$_GET['rowNum'];
        page($total,$maxperpage,"位管理员");
        $rs=iCMS_DB::getArray("SELECT * FROM `#iCMS@__members` where `type`='1' order by uid DESC LIMIT {$this->firstcount},{$maxperpage}");
        $_count=count($rs);
        include admincp::tpl("account.manage");
    }
    function doedit() {
        member::MP("menu_account_edit");
        include iPATH.'include/group.class.php';
        $group =new group('a');
        $rs=iCMS_DB::getRow("SELECT * FROM `#iCMS@__members` WHERE `uid`='".intval($_GET['uid'])."'");
        $info=unserialize($rs->info);
        include admincp::tpl("account.edit");
    }
    function dopower() {
        $rs=iCMS_DB::getRow("SELECT * FROM `#iCMS@__members` WHERE `uid`='".intval($_GET['uid'])."'");
        include admincp::tpl("account.power");
    }
    function dofpower() {
        $rs=iCMS_DB::getRow("SELECT * FROM `#iCMS@__members` WHERE `uid`='".intval($_GET['uid'])."'");
        $forum =new forum();
        $forum->allArray();
        include admincp::tpl("account.fpower");
    }
    function dodel() {
        $uid=(int)$_GET['uid'];
        $uid=="1" && javascript::alert('系统管理员不允许删除！');
        $uid&&iCMS_DB::query("DELETE FROM `#iCMS@__members` WHERE `uid`='$uid'");
        javascript::dialog('已删除!','js:parent.$("#uid'.$uid.'").remove();parent.iCMS.closeDialog();');
    }
    function doSetPower() {
        $uid=(int)$_POST['uid'];
        $power=@implode(",",$_POST['power']);
        iCMS_DB::query("UPDATE `#iCMS@__members` SET `power` = '{$power}' WHERE `uid` ='$uid' LIMIT 1");
        javascript::dialog("设置完成!");
    }
    function dosetfpower() {
        $uid=(int)$_POST['uid'];
        $power=@implode(",",$_POST['power']);
        iCMS_DB::query("UPDATE `#iCMS@__members` SET `cpower` = '{$power}' WHERE `uid` ='$uid' LIMIT 1");
        javascript::dialog("设置完成!");
    }
    function dodels() {
        foreach($_POST['id'] AS $k=>$uid) {
        	if($uid!="1"){
            	iCMS_DB::query("DELETE FROM `#iCMS@__members` WHERE `uid`='$uid'");
            	$js[]='#uid'.$uid;
            }
        }
        javascript::dialog('全部成功删除!','js:parent.$("'.implode(',', $js).'").remove();parent.iCMS.closeDialog();');
    }
    function doupdate() {
        foreach($_POST['groupid'] AS $uid=>$groupid) {
            iCMS_DB::query("UPDATE `#iCMS@__members` SET `groupid` = '{$groupid}' WHERE `uid` ='$uid' LIMIT 1");
        }
        javascript::dialog('操作完成!','url:0');
    }

    function doSave() {
        $uid        =(int)$_POST['uid'];
        $name  		= dhtmlspecialchars($_POST['name']);
        $nickname   = dhtmlspecialchars($_POST['nickname']);
        $groupid    = $_POST['groupid'];
        $pwd        = md5($_POST['pwd']);
        $password   = md5($_POST['pwd2']);
        if($_POST['pwd'] && $_POST['pwd2']) $pwd!=$password && javascript::alert("密码与确认密码不一致!");
        
        $email && !preg_match("/^([_\.0-9a-z-]+)@([0-9a-z][0-9a-z-]+)\.([a-z]{2,6})$/i",$email) && javascript::alert("E-mail格式错误!!");
        
        if(empty($uid)) {
	        if(!$_POST['pwd']||!$_POST['pwd2'])javascript::alert("密码不能为空");
            iCMS_DB::getValue("SELECT `uid` FROM `#iCMS@__members` WHERE `username`='{$name}'") && javascript::alert("该用户名已经存在!");
            iCMS_DB::query("INSERT INTO `#iCMS@__members` (`username`,`password`,`groupid`,`nickname`,`gender`,`info`,`power`,`cpower`,`lastip`,`lastlogintime`,`logintimes`,`post`,`type`,`status`)values('$name', '$password', '$groupid', '$name', '0', '', '', '', '', '0.0.0.0', '".time()."', '0', '0', '1', '1')");
            javascript::dialog("添加完成!","url:".__SELF__.'?mo=account&do=manage');
        }else {
            if($_POST['pwd'] && $_POST['pwd2']) iCMS_DB::query("UPDATE `#iCMS@__members` SET `password` = '$password' WHERE `uid` ='$uid' LIMIT 1");
            
            iCMS_DB::query("UPDATE `#iCMS@__members` SET `nickname`='$nickname',`groupid`='$groupid' WHERE `uid` ='$uid' LIMIT 1");
            
            javascript::dialog("编辑完成!","url:".__SELF__.'?mo=account&do=manage');
        }
    }
}


