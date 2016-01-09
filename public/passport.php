<?php
/**
 * @package iCMS
 * @copyright 2007-2010, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */
require_once(dirname(__FILE__).'/config.php');
require_once iPATH.'include/member.class.php';
switch($_GET['do']){
	case 'register':
		$iCMS->assign('config',array('seccode'=>$iCMS->config['userseccode']));
		$iCMS->assign('forward',__REF__);
		$iCMS->iPrint("usercp/register.htm","register");
	break;
	case 'agreement':
		$iCMS->assign('config',array('agreement'=>str_replace("\n","<br />",$iCMS->config['agreement'])));
		$iCMS->iPrint("usercp/agreement.htm","agreement");
	break;
	case 'login':
		if(member::checklogin(true)){
			_header($iCMS->config['usercpURL']);
		}
		$iCMS->assign('config',array('seccode'=>$iCMS->config['userseccode']));
		$iCMS->assign('forward',__REF__);
		$iCMS->iPrint("usercp/login.htm","login");
	break;
	case 'logout':member::cleancookie();break;
	default:
		require_once iPATH.'include/UI.class.php';
		$action	= $_POST['action'];
		//$forward= $_POST['forward'];
		if($action=='register'){
			ckseccode($_POST['seccode'],'U') && javascript::json('seccode','error:seccode');
			$username		= dhtmlspecialchars($_POST['username']);
			!preg_match("/^([\w\.-]+)@([a-zA-Z0-9-]+)(\.[a-zA-Z\.]+)$/i",$username) && javascript::json('username','register:emailerror');

			iCMS_DB::getValue("SELECT uid FROM `#iCMS@__members` where `username`='$username'") && javascript::json('username','register:emailusr');
			$password	=md5(trim($_POST['password']));
			$pwdrepeat	=md5(trim($_POST['pwdrepeat']));
			$password!=$pwdrepeat && javascript::json('pwdrepeat','register:different');
			
		    $nickname	=dhtmlspecialchars($_POST['nickname']);
		    cstrlen($nickname)>12 && javascript::json(0,'register:nicknamelong');
		    
			iCMS_DB::query("INSERT INTO `#iCMS@__members` (`groupid`,`username`,`password`,`nickname`,`gender`,`info`,`power`,`cpower`,`regtime`,`lastip`,`lastlogintime`,`logintimes`,`post`,`type`,`status`) VALUES ('4','$username','$password', '$nickname','2','','','','".time()."','".getip()."', '".time()."','0','0','0','1') ");
			$uid	= iCMS_DB::$insert_id;
			//设置为登陆状态
			member::set_user_cookie($username,$password,$nickname);
			javascript::json(1,'register:finish');
		}elseif($action=="login"){
			ckseccode($_POST['seccode'],'U') && javascript::json(0,'error:seccode');
			if(member::checklogin(true)){
				javascript::json(1,'login:success');
			}else{
				javascript::json(0,'login:failed');
			}
		}
}
