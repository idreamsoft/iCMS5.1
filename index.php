<?php
/**
 * @package iCMS
 * @copyright 2007-2010, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */
require (dirname(__file__) . '/global.php');
/** 首页 伪静态 模式 **/
if( INDEX_HTML_MODE ) {
	$iCMS->htmlConf	= array('enable'=>true,'ext'=>$iCMS->config['htmlext']);
	$iCMS->pageurl	= './index_';//翻页连接
}

$iCMS->index();