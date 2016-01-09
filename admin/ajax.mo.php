<?php
/**
 * @package iCMS
 * @copyright 2007-2010, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */
!defined('iPATH') && exit('What are you doing?');
class ajax extends AdminCP {
	function dologout(){
		member::cleancookie();
	}
	function doDefault($param=NULL){
		$defArray = $this->iCMS->getCache('system/default');
		$ul="<ul>";
		foreach((array)$defArray[$param] as $val){
			$ul.="<li onclick=\"indefault('$val','$param')\">$val</li>\n";
		}
		$ul.="</ul>";
		echo $ul;
	}
	function doGetsubforum(){
		$forum =new forum();
	 	echo $forum->row($_POST["fid"]);
	}
	function docontentAttr(){
		$fid=$_GET['fid'];
		$forum =new forum();
		$contentAttr =  unserialize($forum->forum[$fid]['contentAttr']);
    	if($contentAttr)foreach((array)$contentAttr AS $caKey=>$ca){
    		echo '<tr><td class="td40">'.$ca['name'].':</td><td colspan="3"><textarea  rows="6" onkeyup="textareasize(this)" name="metadata['.$ca['key'].']" cols="50" class="tarea"></textarea></td></tr>';
    	}
	}
	function doTag(){
		include_once iPATH.'include/tag.class.php';
        $rs	= iTAG::data(0,10);
		$ul	= '<ul style="margin:0; padding:0;">';
		foreach((array)$rs as $key=> $tag){
			$ul.='<li><input onclick="inTag(\''.$tag['name'].'\','.$tag['id'].')" id="gt_'.$tag['id'].'" name="gt_'.$tag['id'].'" class="checkbox" type="checkbox" value="'.$tag['name'].'" /> <label for="gt_'.$tag['id'].'">'.$tag['name'].'</label></li>';
		}
		$ul.="</ul>";
		echo $ul;
	}
	function doforums($op='html'){
		$hasChildren=$_GET['hasChildren']?true:false;
		$forum =new forum();
	 	echo $forum->json($_GET["root"],$op,$hasChildren);
	}
}
