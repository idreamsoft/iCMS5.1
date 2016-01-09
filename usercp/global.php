<?php
/**
 * @package iCMS
 * @copyright 2007-2010, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */
/*************设置public目录路径**************/
/**  ../ 表示global.php文件位于上层目录	 */
define('iCMSPATH', '../');
/*********************************************/
/*********************************************/
require_once(dirname(__FILE__).'/'.iCMSPATH.'global.php');
define('uPATH',dirname(strtr(__FILE__,'\\','/'))."/");
define('__USERCP__',__SELF__.'?mo');
require_once iPATH.'include/member.class.php';
require_once iPATH.'admin/function.php';
require_once iPATH.'include/UI.class.php';

if ($_POST['action'] =="login") {
	ckseccode($_POST['seccode'],'U') && javascript::alert('验证码错误！');
}
member::checklogin();

class UserCP{
    function __construct() {
        global $iCMS,$firstcount,$pagenav;
        $this->iCMS 	= & $iCMS;
        $this->pagenav 	= & $pagenav;
        $this->firstcount = & $firstcount;
        $this->uiBasePath = $iCMS->config['publicURL'].'/ui';
        $this->module	= ($_GET['mo']) ? $_GET['mo']:'';
        $this->action	= isset($_GET['do'])?$_GET['do']:$_POST['do'];
        $this->param	= isset($_GET['param'])?$_GET['param']:$_POST['param'];
        empty($this->action) && $this->action	= 'manage';
        !isset($this->param) && $this->param	= NULL;
        if(empty($this->module)){
			include uPATH.'templates/usercp.php';
			exit();
        }
    }
    /**
     * 运行应用程序
     * @param string $do 动作名称
     * @param string $mo 模块名称
     * @return AdminCP
     */
    function run($do = NULL,$moduleClass = NULL) {
        ($do === NULL) && $do = $this->action;
        ($moduleClass === NULL) && $moduleClass = $this->module;
        //$mo == 'Logout' && Admin::logout(__SELF__);
        $moduleFile = uPATH.$moduleClass. '.mo.php';
        if (is_file($moduleFile) || empty($mo)) {
            if (!class_exists($moduleClass)) {
                if (is_file($moduleFile)) {
                    include_once($moduleFile);
                } else {
                    $moduleClass = 'iAction';
                }
            }
            if (class_exists($moduleClass)) {
                $module = new $moduleClass($this);
                $method = 'do'.$do;
                if (method_exists($module, $method)) {
                    if($this->param===NULL) {
                        $module->$method();
                    }else {
                        $module->$method($this->param);
                    }
                    //include iPATH.'usercp/templates/usercp.php';
                } else {
                    $this->throwException('应用程序运行出错.类 ' . $moduleClass. ' 中找不到方法定义:' . $method, 1003);
                }
            } else {
                $this->throwException('应用程序运行出错.文件 ' . $moduleFile. ' 中找不到类定义:' . $moduleClass, 1002);
            }
        } else {
            $this->throwException('应用程序运行出错.找不到文件:' . $moduleFile, 1001);
        }
        return $this;
    }
    function throwException($msg, $code){
	    iCMS_BUG && exit('iCMS Notice: '.$msg . '(' . $code . ')');
    }
    function tpl($p=NULL) {
    	$p===NULL && $p='usercp';
    	return uPATH.'templates/'.$p.'.php';
    }
    function menu(){
    	empty($this->module) && $this->module='usercp';
    	$this->action='menu';
        include $this->ui();
    }
    function ui() {
    	empty($this->module) && $this->module='usercp';
    	if($p===NULL && $this->module && $this->action){
    		$p=$this->module.'.'.$this->action;
    	}
        return uPATH.'templates/'.$p.'.php';
    }
    function doEmpty() {
        javascript::alert("请选择操作项",'url:0');
    }
    function load(){
		$plugins_menu	= $this->iCMS->getCache('system/plugins.menu');
		if($plugins_menu)foreach($plugins_menu AS $key=>$submenu){
			if($submenu['show'])foreach($submenu AS $k=>$val){
				$k!=='show' && $mArray[$k]=$val;
			}
		}
		
		$models_menu	= $this->iCMS->getCache('system/models.menu');
		if($models_menu)foreach($models_menu AS $mkey=>$mVal){
			if($mVal['show'])foreach($mVal['menu'] AS $k=>$val){
				$mArray[$k]=array('href'=>'content&mid='.$mkey.'&table='.$k,'menu'=>$val);
			}
		}
		return (array)$mArray;
    }
}
