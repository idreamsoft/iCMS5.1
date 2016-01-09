<?php
/**
 * @package iCMS
 * @copyright 2007-2010, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */
require_once dirname(__FILE__).'/../global.php';
define('__ADMINCP__',__SELF__.'?mo');
error_reporting(E_ALL ^ E_NOTICE);
iCMS_DB::$show_errors=true;
require_once iPATH.'include/member.class.php';
require_once iPATH.'include/forum.class.php';
require_once iPATH.'admin/function.php';
require_once iPATH.'admin/admincp.lang.php';
require_once iPATH.'include/UI.class.php';
require_once iPATH.'admin/menu.class.php';
require_once iPATH.'admin/admincp.class.php';

//admincp_log();
if($_POST['action'] =="login"){
	ckseccode($_POST['seccode'],'B') && javascript::alert("验证码错误!",'js:parent.$("#seccodeimg").click();');
}
member::$isAdmin = true;
member::checklogin();
member::MP("ADMINCP","ADMINCP_Permission_Denied");
