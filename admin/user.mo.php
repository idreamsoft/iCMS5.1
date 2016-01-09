<?php
/**
 * @package iCMS
 * @copyright 2007-2010, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */
!defined('iPATH') && exit('What are you doing?');
class user extends AdminCP {
	function dodefault(){
		$this->domanage();
	}
    function domanage() {
        member::MP("menu_user_manage");
        $maxperpage =20;
        $total=($page==1||empty($_GET['rowNum']))?iCMS_DB::getValue("SELECT count(*) FROM `#iCMS@__members`"):(int)$_GET['rowNum'];
        page($total,$maxperpage,"位会员");
        $rs=iCMS_DB::getArray("SELECT * FROM `#iCMS@__members` where `type`='0' order by uid DESC LIMIT {$this->firstcount},{$maxperpage}");
        $_count=count($rs);
        include admincp::tpl('user.manage');
    }
    function doedit() {
        $rs=iCMS_DB::getRow("SELECT * FROM `#iCMS@__members` WHERE `uid`='".(int)$_GET['userid']."'");
        $rs->info=unserialize($rs->info);
        include admincp::tpl('user.add');
    }
    function dodel() {
        $uid=(int)$_GET['userid'];
        $uid&&iCMS_DB::query("DELETE FROM `#iCMS@__members` WHERE `uid`='$uid'");
        javascript::dialog('成功删除!','js:parent.$("#uid'.$uid.'").remove();parent.iCMS.closeDialog();');
    }
    function dodels(){
    	empty($_POST['id']) && javascript::alert('请选择要操作的用户');
        foreach($_POST['id'] as $k=>$uid) {
            iCMS_DB::query("DELETE FROM `#iCMS@__members` WHERE `uid` ='$uid'");
            $js[]='#uid'.$uid;
        }
        javascript::dialog('全部成功删除!','js:parent.$("'.implode(',', $js).'").remove();parent.iCMS.closeDialog();');
    }
    function doSave() {
        $uid=(int)$_POST['uid'];
        $info=array();
        if($_POST['pwd1'] && $_POST['pwd2']) {
            $pwd1=md5(trim($_POST['pwd1']));
            $pwd2=md5(trim($_POST['pwd2']));
            (!$_POST['pwd1']||!$_POST['pwd2']) && javascript::alert("新密码,确认密码不能为空");
            $pwd1!=$pwd2 && javascript::alert("新密码与确认密码不一致!");
            iCMS_DB::query("UPDATE `#iCMS@__members` SET `password` = '$pwd2' WHERE `uid` ='$uid' LIMIT 1");
        }
        //$_POST['username']&&!preg_match("/^([_\.0-9a-z-]+)@([0-9a-z][0-9a-z-]+)\.([a-z]{2,6})$/i",$_POST['username'])&&javascript::alert("E-mail格式错误!!");
        $gender			= intval($_POST['gender']);
        $nickname		= dhtmlspecialchars($_POST['nickname']);
        $info['icq']	= intval($_POST['icq']);
        $info['home']	= dhtmlspecialchars(stripslashes($_POST['home']));
        $info['year']	= intval($_POST['year']);
        $info['month']	= intval($_POST['month']);
        $info['day']	= intval($_POST['day']);
        $info['from']	= dhtmlspecialchars(stripslashes($_POST['from']));
        $info['signature']=dhtmlspecialchars(stripslashes($_POST['signature']));
        iCMS_DB::query("UPDATE `#iCMS@__members` SET `info` = '".addslashes(serialize($info))."',`nickname`='$nickname',`gender`='$gender' WHERE `uid` ='$uid' LIMIT 1");
        javascript::dialog('用户编辑完成!','url:'.__SELF__.'?mo=user&do=manage');
    }
}

