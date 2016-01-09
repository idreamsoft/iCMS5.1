<?php /**
 * @package iCMS
 * @copyright 2007-2010, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */
!defined('iPATH') && exit('What are you doing?'); 
admincp::head();
?>
<script type="text/javascript">
function finish(){
	window.setTimeout(function(){
		<?php if($_GET['ref']){?>
			location.href="<?php echo __REF__; ?>";
		<?php }else{?>	
			location.href="<?php echo __ADMINCP__; ?>=home";
		<?php }?>	
	}, 10000);
}
</script>
<style type="text/css">
#log{width:96%; height:500px; border:1px solid #d9d9d9; padding:5px 20px 20px 10px; overflow-y:scroll; background:#fff; }
</style>
<div class="position">当前位置：管理中心&nbsp;&raquo;&nbsp;程序更新</div>
<div id="log"></div>
<script type="text/javascript">
var log = "<?php echo $msg; ?>";
var n = 0;
var timer = 0;
log = log.split('<icms>');
setIntervals();
function GoPlay(){
	if (n > log.length-1) {
		n=-1;
		clearIntervals();
	}
	if (n > -1) {
		postcheck(n);
		n++;
	}
}
function postcheck(n){
	log[n]=log[n].replace('#','<br />');
	document.getElementById('log').innerHTML += log[n] + '<br /><a name="last"></a>';
	document.getElementById('log').scrollTop = document.getElementById('log').scrollHeight;
}
function setIntervals(){
	timer = setInterval('GoPlay()',100);
}
function clearIntervals(){
	clearInterval(timer);
	finish();
}
</script>
</body></html>