<?php /**
 * @package iCMS
 * @copyright 2007-2010, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */
!defined('iPATH') && exit('What are you doing?');
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title></title>
<meta name="Author" content="iDreamSoft,coolmoo" />
<meta name="Copyright" content="iDreamSoft.com" />
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo iCMS_CHARSET ;  ?>">
<?php if($isFm){?>
<link rel="stylesheet" href="admin/css/main.css?5.0" type="text/css" media="all" />
<link rel="stylesheet" href="<?php echo $this->uiBasePath;?>/smoothness/jquery.ui.css?1.8.9" type="text/css" media="all" />
<script src="<?php echo $this->uiBasePath;?>/jquery.js?1.4.4" type="text/javascript"></script>
<script src="<?php echo $this->uiBasePath;?>/jquery.ui.js?1.8.9" type="text/javascript"></script>
<script src="<?php echo $this->uiBasePath;?>/iCMS.js?ver=5.0" type="text/javascript"></script>
<script src="admin/js/common.js?5.0" type="text/javascript"></script>
<script type="text/javascript">
	if('<?php echo $this->frames ;  ?>'!= 'no' && !parent.document.getElementById('left_menu')) window.location.replace(document.URL + (document.URL.indexOf('?') != -1 ? '&' : '?') + 'frames=yes');
iCMS.publicURL	= '<?php echo $this->iCMS->config['publicURL']; ?>';
</script>
<?php } ?>
</head>
<body>
<?php if($isFm){?>
<iframe width="0" height="0" style="display:none" id="iCMS_FRAME" name="iCMS_FRAME"></iframe>
<div id="iCMS_DIALOG" title="iCMS提示" style="display:none"><img src="<?php echo $this->uiBasePath;?>/loading.gif" /></div>
<?php } ?>
