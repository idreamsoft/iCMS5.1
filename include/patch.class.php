<?php
/**
 * @package iCMS
 * @copyright 2007-2010, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */

/**
 * 自动更新类
 *
 * @author coolmoo
 */
define('PATCH_URL',"http://patch.idreamsoft.com");//自动更新服务器
define('PATCH_DIR',iPATH.'cache/system/patch/');//临时文件夹
class patch {
	public static $iCMSVER	= '';
    public function init($force=false){
    	$verList	= self::getVersion($force);
    	foreach((array)$verList AS $key=>$version){
    		list(self::$iCMSVER,$icmsRelease,$installFile,$changelog)=explode("||",$version);//版本||发布日期||升级文件||升级说明
    		if(self::$iCMSVER.$icmsRelease!=iCMS_VER.iCMS_RELEASE && $icmsRelease>iCMS_RELEASE){
    			return array(self::$iCMSVER,$icmsRelease,$installFile,$changelog);
    		}
    	}
    }
    function getVersion($force=false) {
    	FS::mkdir(PATCH_DIR);
	    $tFilePath		= PATCH_DIR.'version.txt';//临时文件夹
	    if(FS::exists($tFilePath) && time()-FS::mtime($tFilePath) < 3600 && !$force){
	    	$FileData	= FS::read($tFilePath);
	    }else{
	    	$FileData	= FS::remote(PATCH_URL.'/version.txt');
	    	FS::write($tFilePath,$FileData);
	    }
    	return explode("\n",$FileData);//版本列表
    }
    function download($n){
    	$zipName	= 'iCMS '.self::$iCMSVER.'.patch.'.$n.'.zip';
	    $zipFile	= PATCH_DIR.'/'.$zipName;//临时文件
		$msg		= '正在下载 ['.$n.'] 更新包 '.PATCH_URL.'/'.$zipName.'<icms>下载完成....<icms>';
	    if(file_exists($zipFile)){
	    	return $msg;
	    }
    	$FileData		= FS::remote(PATCH_URL.'/'.$zipName);
	    FS::write($zipFile,$FileData);//下载更新包
		return $msg;
    }
    function update($n){
		@set_time_limit(0);
		// Unzip uses a lot of memory
		@ini_set('memory_limit', '256M');
		require_once(iPATH.'include/pclzip.class.php');
    	$zipName	= 'iCMS '.self::$iCMSVER.'.patch.'.$n.'.zip';
	    $zipFile	= PATCH_DIR.'/'.$zipName;//临时文件
		$msg= '正在对 ['.$zipName.'] 更新包进行解压缩<icms>';
		$zip		= new PclZip($zipFile);
		if ( false == ($archive_files = $zip->extract(PCLZIP_OPT_EXTRACT_AS_STRING))) exit("ZIP包错误");

		if ( 0 == count($archive_files) ) exit("空的ZIP文件");
		
		$msg.= '解压完成开始更新程序#<icms>';
		foreach ($archive_files as $file) {
			$folder	= $file['folder'] ? $file['filename'] : dirname($file['filename']);
			$dp		= iPATH.$folder;
			if(!FS::exists($dp)){
				$msg.= '创建 ['.$dp.'] 文件夹<icms>';
				//self::mkdir($path.'/'.$folder);
			}
			if (empty($file['folder'])) {
				$fp	= iPATH.$file['filename'];
				$bfp= iPATH.$n.'bak/'.$file['filename'];
				if(FS::exists($fp)){
					$msg.= '备份 ['.$fp.'] 文件 到 ['.$bfp.']<icms>';
					FS::mkdir(dirname($bfp));
					rename($fp,$bfp);//备份旧文件
				}
				$msg.= '更新 ['.$fp.'] 文件<icms>';
				FS::write($fp, $file['content']);
				$msg.= '['.$fp.'] 更新完成!#<icms>';
			}
		}
     	$msg.= '清除临时文件!<icms>注:原文件备份在 ['.iPATH.$n.'bak/] 目录<icms>如没有特殊用处请删除此目录!#<icms>';
    	FS::rmdir(PATCH_DIR,true,'version.txt');
		return $msg;
   }
   function run($fp){
   	   $fp	= iPATH.$fp;
   	   if(FS::exists($fp)){
   	   	   include $fp;
   	   	   $msg= '执行升级程序<icms>';
   	   	   $msg.= updatePatch();
   	   	   $msg.= '升级顺利完成!<icms>删除升级程序!';
   	   	   FS::del($fp);
   	   }
   	   return $msg;
   }
}
