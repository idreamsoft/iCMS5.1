<?php
/**
 * @package iCMS
 * @copyright 2007-2011, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */
error_reporting(E_ALL ^ E_NOTICE);
define('iCMS', TRUE);
define('iPATH',substr(dirname(strtr(__FILE__,'\\','/')), 0,-7)."/");

// 防止 PHP 5.1.x 使用时间函数报错
function_exists('date_default_timezone_set') && date_default_timezone_set('Etc/GMT+0');
unset($_ENV,$HTTP_ENV_VARS,$_REQUEST,$HTTP_POST_VARS,$HTTP_GET_VARS,$HTTP_POST_FILES,$HTTP_COOKIE_VARS,$HTTP_SESSION_VARS,$HTTP_SERVER_VARS);
unset($GLOBALS['_ENV'],$GLOBALS['HTTP_ENV_VARS'],$GLOBALS['_REQUEST'],$GLOBALS['HTTP_POST_VARS'],$GLOBALS['HTTP_GET_VARS'],$GLOBALS['HTTP_POST_FILES'],$GLOBALS['HTTP_COOKIE_VARS'],$GLOBALS['HTTP_SESSION_VARS'],$GLOBALS['HTTP_SERVER_VARS']);

if (ini_get('register_globals')) {
    isset($_REQUEST['GLOBALS']) && die('发现试图覆盖 GLOBALS 的操作');
    // Variables that shouldn't be unset
    $noUnset = array('GLOBALS', '_GET', '_POST', '_COOKIE','_SERVER', '_ENV', '_FILES');
    $input = array_merge($_GET, $_POST, $_COOKIE, $_SERVER, $_FILES, isset($_SESSION) && is_array($_SESSION) ? $_SESSION : array());
    foreach ( $input as $k => $v ) {
        if ( !in_array($k, $noUnset) && isset($GLOBALS[$k]) ) {
            $GLOBALS[$k] = NULL;
            unset($GLOBALS[$k]);
        }
    }
}
// Fix for IIS, which doesn't set REQUEST_URI
if ( empty( $_SERVER['REQUEST_URI'] ) ) {

    // IIS Mod-Rewrite
    if (isset($_SERVER['HTTP_X_ORIGINAL_URL'])) {
        $_SERVER['REQUEST_URI'] = $_SERVER['HTTP_X_ORIGINAL_URL'];
    }
    // IIS Isapi_Rewrite
    else if (isset($_SERVER['HTTP_X_REWRITE_URL'])) {
        $_SERVER['REQUEST_URI'] = $_SERVER['HTTP_X_REWRITE_URL'];
    }else {
        // Some IIS + PHP configurations puts the script-name in the path-info (No need to append it twice)
        if ( $_SERVER['PATH_INFO'] == $_SERVER['SCRIPT_NAME'] )
            $_SERVER['REQUEST_URI'] = $_SERVER['PATH_INFO'];
        else
            $_SERVER['REQUEST_URI'] = $_SERVER['SCRIPT_NAME'] . $_SERVER['PATH_INFO'];

        // Append the query string if it exists and isn't null
        if (isset($_SERVER['QUERY_STRING']) && !empty($_SERVER['QUERY_STRING'])) {
            $_SERVER['REQUEST_URI'] .= '?' . $_SERVER['QUERY_STRING'];
        }
    }
}

// Fix for PHP as CGI hosts that set SCRIPT_FILENAME to something ending in php.cgi for all requests
if ( isset($_SERVER['SCRIPT_FILENAME']) && ( strpos($_SERVER['SCRIPT_FILENAME'], 'php.cgi') == strlen($_SERVER['SCRIPT_FILENAME']) - 7 ) )
    $_SERVER['SCRIPT_FILENAME'] = $_SERVER['PATH_TRANSLATED'];

// Fix for Dreamhost and other PHP as CGI hosts
if (strpos($_SERVER['SCRIPT_NAME'], 'php.cgi') !== false)
    unset($_SERVER['PATH_INFO']);

// Fix empty PHP_SELF
$PHP_SELF = $_SERVER['PHP_SELF'];
if ( empty($PHP_SELF) ) {
    $_SERVER['PHP_SELF'] = $PHP_SELF = preg_replace("/(\?.*)?$/",'',$_SERVER["REQUEST_URI"]);
}

if ( version_compare( '5', phpversion(), '>' ) ) {
    die('您的服务器运行的 PHP 版本是' . phpversion() . ' 但 iCMS plus 版要求至少PHP5。');
}

if ( !extension_loaded('mysql')) {
    die('您的 PHP 安装看起来缺少 MySQL 数据库部分，这对 iCMS 来说是必须的。');
}

require_once(iPATH.'include/compat.php');
require_once(iPATH.'include/version.php');
require_once(iPATH.'include/cookie.php');
require_once(iPATH.'include/common.php');
require_once(iPATH.'include/version.php');
require_once(iPATH.'include/FileSystem.class.php');

if ( get_magic_quotes_gpc() ) {
    $_GET    = stripslashes_deep($_GET);
    $_POST   = stripslashes_deep($_POST);
    $_COOKIE = stripslashes_deep($_COOKIE);
}
$_GET    = add_magic_quotes($_GET);
$_POST   = add_magic_quotes($_POST);
$_COOKIE = add_magic_quotes($_COOKIE);
$_SERVER = add_magic_quotes($_SERVER);

!$_SERVER['PHP_SELF'] && $_SERVER['PHP_SELF']=$_SERVER['SCRIPT_NAME'];
$_URI  = $_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'];
$CMSDIR=substr(dirname($_URI),0,-8);
$CMSURL= 'http://'.$_SERVER['HTTP_HOST'].$CMSDIR;

$step = isset($_GET['step']) ? $_GET['step'] : $_POST['step'];

$lockfile = iPATH.'include/install.lock';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>iCMS<?php echo iCMS_VER;?>- 安装向导</title>
<link href="install.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div id="main"><img src="http://www.idreamsoft.com/doc/iCMS.logo.gif"/>
  <form method="post" action="<?php echo $PHP_SELF;?>">
    <p class="title" style="font-size:24px">iCMS <?php echo iCMS_VER;?> 安装向导</p>
    <hr size="1" noshade="noshade" />
    <?php file_exists($lockfile) && exit('警告!您已经安装过iCMS<br />
		为了保证数据安全，请立即手动删除 install.php 文件<br />
		如果您想再次安装iCMS，请删除 ./include/install.lock 文件，再次运行安装文件</form></div></body></html>');
                if (empty($step)) {
                    ?>
    <p class="title" align="center">iCMS使用许可协议</p>
    <div class="licenseblock">
      <div class="license" id="license">
        <p>版权所有 &copy; 2007-2010，<a href="http://www.idreamsoft.com" target="_blank">iDreamSoft</a> 保留所有权利。 </p>
        <p>感谢您选择iCMS <?php echo iCMS_VER ;?>。希望我们的努力能为您提供一个高效快速和强大的内容管理解决方案。</p>
        <p>本软件是自由软件，遵循 Apache License 2.0 许可协议 &lt;http://www.apache.org/licenses/LICENSE-2.0&gt; </p>
        <p>本软件版权归 iCMS 官方所有，且受《中华人民共和国计算机软件保护条例》等知识产权法律及国际条约与惯例的保护。</p>
        <p>本协议适用于 iCMS 任何版本，iCMS官方拥有对本协议的最终解释权。 </p>
        <p>无论个人或组织、盈利与否、用途如何（包括以学习和研究为目的），均需仔细阅读本协议，在理解、同意、并遵守本协议的全部条款后，方可开始使用本软件。 </p>
        <h3>I.协议许可和限制</h3>
        <ol>
          <li>未经作者书面许可，不得衍生出私有软件。</li>
          <li>所有用户均可查看 iCMS 的全部源代码,也可以根据自己的需要对其进行修改及优化。</li>
          <li>使用本软件所生成的网站，必须保留页脚处的 iCMS 名称和 http://www.idreamsoft.com 的链接地址。</li>
          <li>若因网站性质等因素所限，不适合保留页脚处的 iCMS 名称和 http://www.idreamsoft.com 的链接地址等软件信息，请与作者联系取得移除软件信息授权。</li>
          <li>严禁利用本软件从事销售等盈利业务，若有OEM需求，请与作者联系。</li>
        </ol>
        <h3>II.有限担保和免责声明</h3>
        <ol>
          <li>本软件及所附带的文件是作为不提供任何明确的或隐含的赔偿或担保的形式提供的。</li>
          <li>您拥有使用本软件构建的网站全部内容所有权，并独立承担与这些内容的相关法律义务。 </li>
          <li>用户出于自愿而使用本软件，您必须了解使用本软件的风险，在尚未购买产品技术服务之前，我们不承诺提供任何形式的技术支持、使用担保，也不承担任何因使用本软件而产生问题的相关责任。</li>
          <li>iCMS不对使用本软件构建的网站中的文章或信息承担责任。</li>
        </ol>
        <p>电子文本形式的授权协议如同双方书面签署的协议一样，具有完全的和等同的法律效力。</p>
        <p>您一旦开始安装 iCMS，即被视为完全理解并接受本协议的各项条款，在享有上述条款授予的权力的同时，受到相关的约束和限制。协议许可范围以外的行为，将直接违反本授权协议并构成侵权，我们有权随时终止授权，责令停止损害，并保留追究相关责任的权力。</p>
      </div>
    </div>
    <hr size="1" noshade="noshade" />
    <p align="center">
      <input type="hidden" name="step" value="1" />
      <input id="submit" class="formbutton" type="submit" value="接受协议，继续安装" />
      <input type="button" class="formbutton" value="不接受" onClick="window.close();"/>
    </p>
    <?php
                }elseif ($step == '1') {
                    $check=1;
                    $no_write=$CMSDIR."程序根目录无法书写,请速将根目录属性设置为777";
                    $correct='    ......<font style="color:green;">√</font>';
                    $incorrect='<font style="color:red;">× 777属性检测不通过</font>';
                    $uncorrect='<font style="color:red;">× 文件不存在请上传此文件</font>';
                    $w_check=array(
                            'config.php',
                            'cache',
                            'cache/templates',
                            'cache/system',
                            'uploadfiles',
                            'admin',
                            'admin/backup',
                            'admin/logs',
                            'include',
                            'include/site.config.php'
                    );
                    if($fp=@fopen(iPATH.'test.txt',"wb")) {
                        $state=$correct;
                        fclose($fp);
                    } else {
                        $state=$incorrect.$no_write;
                    }
                    $_count=count($w_check);
                    for($i=0; $i<$_count; $i++) {
                        if(!file_exists(iPATH.$w_check[$i])) {
                            $w_check[$i].= $uncorrect;
                            $check=0;
                        } elseif(is_writable(iPATH.$w_check[$i])) {
                            $w_check[$i].= $correct;
                        } else {
                            $w_check[$i].=$incorrect;
                            $check=0;
                        }
                    }
                    $check && @unlink(iPATH.'test.txt');
                    ?>
    <p class="title">第一步:安装须知</p>
    <p>欢迎使用 iCMS <?php echo iCMS_VER ;?>，本向导将帮助您将程序完整地安装在您的服务器内。<br />
      请您先确认以下安装配置: </p>
    <ul>
      <li>MySQL 主机名称/IP 地址 </li>
      <li>MySQL 用户名和密码 </li>
      <li>MySQL 数据库名称 </li>
    </ul>
    <p>如果您无法确认以上的配置信息, 请与您的服务商联系, 我们无法为您提供任何帮助.</p>
    <p class="title">第二步:检查必要目录和文件是否可写，如果发生错误，请更改文件/目录属性 777 </p>
    <ul>
      <?php foreach($w_check as $key=>$value) { ?>
      <li><?php echo $value;?></li>
      <?php }?>
    </ul>
    <hr size="1" noshade="noshade" />
    <p align="right">
      <input type="hidden" name="step" value="2" />
      <?php
                        if(!$check) {
                            ?>
      <input class="formbutton" onclick='window.location="<?php echo $PHP_SELF;?>?step=1"' type='button' value='重新检查' />
      <?php }else {?>
      <input class="formbutton" type="submit" value="下一步" />
      <?php }?>
    </p>
    <?php
                }elseif ($step == '2') {
                    ?>
    <p class="title">第三步:配置数据库信息</p>
    <table width="100%" border="0" cellspacing="0" cellpadding="4">
      <tr>
        <td colspan="2"><span class="title">数据库信息</span></td>
      </tr>
      <tr>
        <td width="30%"><label for="dbhost" hidefocus="true">服务器地址:</label></td>
        <td><input type="text" value="localhost" name="dbhost" id="dbhost" class="formfield" />
          一般是 localhost</td>
      </tr>
      <tr>
        <td width="30%"><label for="dbname" hidefocus="true">数据库名:</label></td>
        <td><input name="dbname" id="dbname" type="text" class="formfield" value="icms" />
          <input name="create" type="checkbox" id="create" value="1"/>
          创建新数据库</td>
      </tr>
      <tr>
        <td width="30%">数据库用户名:</td>
        <td><input name="dbuser" type="text" class="formfield" value="root" /></td>
      </tr>
      <tr>
        <td width="30%">数据库用户密码:</td>
        <td><input type="text" value="" name="dbpw" class="formfield" /></td>
      </tr>
      <tr>
        <td width="30%">数据表前缀:</td>
        <td><input type="text" value="icms_" name="tablepre" class="formfield"/>
          不填则默认为 icms_</td>
      </tr>
      <tr>
        <td colspan="2">数据表编码:UTF-8</td>
      </tr>
    </table>
    <hr size="1" noshade="noshade" />
    <table width="100%" border="0" cellspacing="0" cellpadding="4">
      <tr>
        <td colspan="2"><span class="title">程序配置</span></td>
      </tr>
      <tr>
        <td colspan="2">不般不用修改，向导自动获取</td>
      </tr>
      <tr>
        <td width="30%">网站地址:</td>
        <td><input name="setupURL" type="text" class="formfield" id="setupURL" style="width:400px" value="<?php echo $CMSURL ;?>" /></td>
      </tr>
      <tr>
        <td width="30%">公共程序URL:</td>
        <td><input name="publicURL" type="text" class="formfield" id="publicURL" style="width:400px" value="<?php echo $CMSURL ;?>/public" /></td>
      </tr>
      <tr>
        <td width="30%">静态页面URL:</td>
        <td><input name="htmlURL" type="text" class="formfield" id="htmlURL" style="width:400px" value="<?php echo $CMSURL ;?>/html" /></td>
      </tr>
      <tr>
        <td width="30%">附件URL:</td>
        <td><input name="uploadURL" type="text" class="formfield" id="uploadURL" style="width:400px" value="<?php echo $CMSURL ;?>/uploadfiles" /></td>
      </tr>
      <tr>
        <td width="30%">用户中心URL:</td>
        <td><input name="usercpURL" type="text" class="formfield" id="usercpURL" style="width:400px" value="<?php echo $CMSURL ;?>/usercp" /></td>
      </tr>
    </table>
    <hr size="1" noshade="noshade" />
    <table width="100%" border="0" cellspacing="0" cellpadding="4">
      <tr>
        <td colspan="2"><span class="title">管理配置</span></td>
      </tr>
      <tr>
        <td width="30%">管理员账号:</td>
        <td><input type="text" value="admin" name="admin" class="formfield" /></td>
      </tr>
      <tr>
        <td width="30%">管理员密码:</td>
        <td><input type="password" name="password" class="formfield" />
          请设置密码</td>
      </tr>
    </table>
    <br />
    <hr size="1" noshade="noshade" />
    <p align="right">
      <input type="hidden" name="step" value="3" />
      <input class="formbutton" type="button" value="上一步" onclick="history.back(1)" />
      <input class="formbutton" type="submit" value="下一步" />
    </p>
    <?php
                } elseif ($step == '3') {
                    if(trim($_POST['dbname']) == "" || trim($_POST['dbhost']) == "" || trim($_POST['dbuser']) == "" ) {
                        ?>
    <p>请返回并确认所有选项均已填写.</p>
    <hr size="1" noshade="noshade" />
    <p align="right">
      <input class="formbutton" type="button" value="上一步" onclick="history.back(1)" />
    </p>
    <?php
                    } elseif(!@mysql_connect($_POST['dbhost'],$_POST['dbuser'],$_POST['dbpw'])) {
                        ?>
    <p>数据库不能连接.</p>
    <hr size="1" noshade="noshade" />
    <p align="right">
      <input class="formbutton" type="button" value="上一步" onclick="history.back(1)" />
    </p>
    <?php
                    } elseif(!@mysql_select_db($_POST['dbname'])&&!$_POST['create']) {
                        ?>
    <p>数据库<?php echo $_POST['dbname'];?>不存在.</p>
    <hr size="1" noshade="noshade" />
    <p align="right">
      <input class="formbutton" type="button" value="上一步" onclick="history.back(1)" />
    </p>
    <?php
                    } elseif(strstr($_POST['tablepre'], '.')) {
                        ?>
    <p>您指定的数据表前缀包含点字符，请返回修改.</p>
    <hr size="1" noshade="noshade" />
    <p align="right">
      <input class="formbutton" type="button" value="上一步" onclick="history.back(1)" />
    </p>
    <?php
                    } else {
                        empty($_POST['password']) && alert('管理员密码不能为空！');
                        ?>
    <p class="title">第四步:导入数据</p>
    <p>
<?php
	$configfile=iPATH.'config.php';
	if(is_writeable($configfile)) {
		$dbhost 	= trim($_POST['dbhost']);
		$dbuser 	= trim($_POST['dbuser']);
		$dbpw 		= trim($_POST['dbpw']);
		$dbname 	= trim($_POST['dbname']);
		$dbprefix	= trim($_POST['tablepre']);
		$dbcharset	= trim($_POST['dbcharset']);

		$setupURL	= trim($_POST['setupURL']);
		$publicURL	= trim($_POST['publicURL']);
		$htmlURL	= trim($_POST['htmlURL']);
		$uploadURL	= trim($_POST['uploadURL']);
		$usercpURL	= trim($_POST['usercpURL']);
		$admin		= trim($_POST['admin']);
		$password	= trim($_POST['password']);
		$db_prefix  = $db_prefix ? $db_prefix : '#iCMS@__';

		$filecontent = FS::read($configfile);
		$filecontent = preg_replace("/define\(\'DB_NAME\',\s*\'.*?\'\)/is", "define('DB_NAME', 		'$dbname')", $filecontent);
		$filecontent = preg_replace("/define\(\'DB_USER\',\s*\'.*?\'\)/is", "define('DB_USER', 		'$dbuser')", $filecontent);
		$filecontent = preg_replace("/define\(\'DB_PASSWORD\',\s*\'.*?\'\)/is", "define('DB_PASSWORD', 		'$dbpw')", $filecontent);
		$filecontent = preg_replace("/define\(\'DB_HOST\',\s*\'.*?\'\)/is", "define('DB_HOST', 		'$dbhost')", $filecontent);
		//			$filecontent = preg_replace("/define\(\'DB_CHARSET\',\s*\'.*?\'\)/is", "define('DB_CHARSET', 		'$dbcharset')", $filecontent);
		$filecontent = preg_replace("/define\(\'DB_PREFIX\',\s*\'.*?\'\)/is", "define('DB_PREFIX', 		'$dbprefix')", $filecontent);
		$filecontent = preg_replace("/define\(\'iCMSKEY\',\s*\'.*?\'\)/is", "define('iCMSKEY', 		'".random(32)."')", $filecontent);
		FS::write($configfile,$filecontent);
	}
	if(!@mysql_select_db($dbname)&&$_POST['create']) {
//mysql_query("DROP DATABASE `$database`;");
		$database=addslashes($dbname);
		if(version_compare(mysql_get_server_info(), '4.1.0', '>=')) {
		//	$DATABASESQL=$dbcharset=='gbk'?"DEFAULT CHARACTER SET gbk COLLATE gbk_chinese_ci":"DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci";
		    $DATABASESQL="DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci";
		}
		mysql_query("CREATE DATABASE `$database` ".$DATABASESQL);
	}
	require_once ($configfile);
	$installSQL='iCMS_SQL.sql';
	!is_readable($installSQL)&&exit('数据库文件不存在或者读取失败');
	require_once(iPATH.'include/mysql.class.php');
//iCMS_DB::$show_errors=true;
	runquery(FS::read($installSQL));
	iCMS_DB::query("INSERT INTO `#iCMS@__members` (`groupid`, `username`, `password`, `nickname`, `gender`, `info`, `power`, `cpower`, `regtime`, `lastip`, `lastlogintime`, `logintimes`, `post`, `type`, `status`) VALUES ('1', '$admin', '".md5($password)."', '管理员', '0', '', '', '', '".time()."', '', '0', '0', '0', '1', '1');");
	iCMS_DB::query("UPDATE `#iCMS@__config` SET `value` = '{$setupURL}' WHERE `name` ='setupURL'");
	iCMS_DB::query("UPDATE `#iCMS@__config` SET `value` = '{$publicURL}' WHERE `name` ='publicURL'");
	iCMS_DB::query("UPDATE `#iCMS@__config` SET `value` = '{$htmlURL}' WHERE `name` ='htmlURL'");
	iCMS_DB::query("UPDATE `#iCMS@__config` SET `value` = '{$uploadURL}' WHERE `name` ='uploadURL'");
	iCMS_DB::query("UPDATE `#iCMS@__config` SET `value` = '{$setupURL}' WHERE `name` ='tagURL'");
	iCMS_DB::query("UPDATE `#iCMS@__config` SET `value` = '{$usercpURL}' WHERE `name` ='usercpURL'");
	$tmp=iCMS_DB::getArray("SELECT * FROM `#iCMS@__config`");
	$config_data="<?php\n\t\$config=array(\n";
	for ($i=0;$i<count($tmp);$i++) {
		$_config.="\t\t\"".$tmp[$i]['name']."\"=>\"".$tmp[$i]['value']."\",\n";
	}
	$config_data.=substr($_config,0,-2);
	$config_data.="\t\n);?>";
	FS::write(iPATH.'include/site.config.php',$config_data);
	FS::write($lockfile,'iCMS');
?>
    </p>
    <p>共创建了<?php echo $tablenum;?>个数据表.</p>
    <hr size="1" noshade="noshade" />
    <p>安装程序已经顺利执行完毕，请尽快删除整个 install 目录，以免被他人恶意利用。</p>
    <p>&nbsp;</p>
    <p><a href="../">点击这里进入首页</a><br />
      <a href="../admincp.php">点击这里进入后台</a></p>
    <hr size="1" noshade="noshade" />
    <p align="right"><a href="http://www.idreamsoft.com" target="_blank">Welcome to iDreamSoft</a></p>
    <?php
                    }
                }
                ?>
  </form>
</div>
<strong>Powered by <a href="http://www.idreamsoft.com" target="_blank">iCMS</a> <?php echo iCMS_VER ;?> &copy; 2007-2010 iDreamSoft Inc</strong>
</body>
</html>
<?php
function runquery($sql) {
    global  $db, $tablenum;
    $sql = str_replace("\r", "\n", str_replace('#iCMS@__',DB_PREFIX,$sql));
    $ret = array();
    $num = 0;
    foreach(explode(";\n", trim($sql)) as $query) {
        $queries = explode("\n", trim($query));
        foreach($queries as $query) {
            $ret[$num] .= $query[0] == '#' ? '' : $query;
        }
        $num++;
    }
    unset($sql);
    foreach($ret as $query) {
        $query = trim($query);
        if($query) {
            if(substr($query, 0, 12) == 'CREATE TABLE') {
                preg_match("|CREATE TABLE (.*) \(  |i",$query, $name);
                flush();
                echo '创建表 '.$name[1].' ... <font color="#0000EE">成功</font><br />';
                flush();
                iCMS_DB::query(createtable($query, DB_CHARSET));
                $tablenum++;
            } else {
                iCMS_DB::query($query);
            }
        }
    }
}

function createtable($sql, $dbcharset) {
    $type = strtoupper(preg_replace("/^\s*CREATE TABLE\s+.+\s+\(.+?\).*(ENGINE|TYPE)\s*=\s*([a-z]+?).*$/isU", "\\2", $sql));
    $type = in_array($type, array('MYISAM', 'HEAP')) ? $type : 'MYISAM';
    $sql =preg_replace("/^\s*(CREATE TABLE\s+.+\s+\(.+?\)).*$/isU", "\\1", $sql);
    if ( !empty($dbcharset) && version_compare(mysql_get_server_info(), '4.1.0', '>=') ) {
        $sql.=" ENGINE=$type DEFAULT CHARSET=$dbcharset";
    }else {
        $sql.=" TYPE=$type";
    }
    return $sql;
}
?>