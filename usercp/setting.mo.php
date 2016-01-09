<?php
/**
 * @package iCMS
 * @copyright 2007-2010, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */
!defined('iPATH') && exit('What are you doing?');
class setting extends UserCP {
    function domanage() {
    	if($_FILES['Filedata']){
			require_once iPATH.'include/upload.class.php';
        	$this->iCMS->config['iswatermark']=false;
        	$this->iCMS->config['isthumb']=false;
        	$ext=FS::getExt($_FILES['Filedata']['name']);
            $F=iUpload::FILES("Filedata",0,'','avatar','tmp_'.member::$uId.'.'.strtolower($ext),'avatar');
//            echo FS::fp($F["FilePath"],'+http');
            echo '../'.$this->iCMS->config['uploadfiledir'].'/'.$F["FilePath"];
    	}else{
        	$_dir		= ceil(member::$uId/500);
            $data		= file_get_contents("php://input");
            $length		= strlen($data);
            $lastIndex	= 0;
            $sizeIndex	= 0;
            $sizeArray	= array(120,48,24);
            //同时上传3个尺寸的头像。 分割数据
            while ($lastIndex < $length){
				$a	= unpack('L',$data{$lastIndex+3}.$data{$lastIndex+2}.$data{$lastIndex+1}.$data{$lastIndex});
				$lastIndex += 4;
				$avatarData =substr($data,$lastIndex,$a[1]);
				$lastIndex += $a[1];
				$avatar	= 'avatar/'.$_dir.'/'.member::$uId.'_'.$sizeArray[$sizeIndex].'.gif';
				$fp		= FS::fp($avatar,'+iPATH');
				@FS::mkdir(dirname($fp));
				@FS::write($fp,$avatarData);
				$sizeIndex++;
            }
	        $rootpf	= FS::fp('avatar/tmp_'.member::$uId,'+iPATH');
	        foreach (glob($rootpf."*") as $_fp) {
                file_exists($_fp) && FS::del($_fp);
	        }
    	}
    }
    function dochangepassword(){
    	$password	=md5($_POST['password']);
    	$newpwd1	=md5($_POST['newpwd1']);
    	$newpwd2	=md5($_POST['newpwd2']);
    	$password!=member::$Rs->password && javascript::dialog("原密码错误!");
    	iCMS_DB::query("UPDATE `#iCMS@__members` SET `password` = '$newpwd2' WHERE `uid` ='".member::$uId."' LIMIT 1");
    	javascript::dialog("密码修改完成!请重新登陆",'url:'.__SELF__);
    }
    function dosetting(){
        $gender			= intval($_POST['gender']);
        $nickname		= dhtmlspecialchars($_POST['nickname']);
        $info['icq']	= intval($_POST['icq']);
        $info['home']	= dhtmlspecialchars(stripslashes($_POST['home']));
        $info['year']	= intval($_POST['year']);
        $info['month']	= intval($_POST['month']);
        $info['day']	= intval($_POST['day']);
        $info['from']	= dhtmlspecialchars(stripslashes($_POST['from']));
        $info['signature']=dhtmlspecialchars(stripslashes($_POST['signature']));
        iCMS_DB::query("UPDATE `#iCMS@__members` SET `info` = '".addslashes(serialize($info))."',`nickname`='$nickname',`gender`='$gender' WHERE `uid` ='".member::$uId."' LIMIT 1");
        javascript::dialog("资料修改完成!");
    }
}