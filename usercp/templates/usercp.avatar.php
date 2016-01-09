<?php
/**
 * @package iCMS
 * @copyright 2007-2010, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */
!defined('iPATH') && exit('What are you doing?');
?>
<div class="pwrap">
  <div class="phead">
    <div class="phead-inner">
      <div class="phead-inner">
        <h3 class="ptitle"><span>设置头像</span></h3>
      </div>
    </div>
  </div>
  <div class="pbody">
      <div class="clearfix avatarlayout">
        <div class="avatarcreator">
          <div class="avatarcreator-inner">
            <div class="successmsg" style="display:none;" id="successmsg">头像制作完成, 您需要刷新页面才能看到新头像! </div>
            <div class="avatarflash" id="avatarShow">
              <object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" id="Object1" width="600" height="500" codebase="http://fpdownload.macromedia.com/get/flashplayer/current/swflash.cab">
                <param name="movie" value="images/avatar.swf" />
                <param name="quality" value="high" />
                <param name="bgcolor" value="#FFFFFF" />
                <param name="wmode" value="opaque" />
                <param name="allowScriptAccess" value="sameDomain" />
                <param name="flashVars" value="&url=<?php echo __USERCP__;?>=setting" />
                <embed src="images/avatar.swf" quality="high" bgcolor="#FFFFFF" width="600" height="500" name="KaChaMax" align="middle" play="true" loop="false" quality="high" wmode="opaque" allowScriptAccess="sameDomain" type="application/x-shockwave-flash" pluginspage="http://www.adobe.com/go/getflashplayer" flashVars="&url=<?php echo __USERCP__;?>=setting"></embed>
              </object>
            </div>
          </div>
        </div>
        <div class="avatarpreview">
          <div class="avatarpreview-inner">
            <h3 class="avatar-title">当前使用头像</h3>
            <div class="avatar-image"> <img id="mybigavatar" src="<?php echo member::avatar(120);?>?<?php echo time();?>" width="120" height="120" alt="" /> </div>
            <div class="avatar-image"> <img id="myavatar" src="<?php echo member::avatar(48);?>?<?php echo time();?>" width="48" height="48" alt="" /> </div>
            <div class="avatar-image"> <img id="mysmallAvatar" src="<?php echo member::avatar(24);?>?<?php echo time();?>" width="24" height="24" alt="" /> </div>
            <div class="avatarnote">
              <p>允许上传不超过 <strong>1mb</strong> 的 jpg, gif, png 等格式的图片.</p>
            </div>
          </div>
        </div>
      </div>
  </div>
  <div class="pfoot">
    <p><b>-</b></p>
  </div>
</div>
<script type="text/javascript">
var userID = <?php echo member::$uId;?>;
var isDefault = <?php echo strstr(member::avatar(24),'avatar_')?'true':'false';?>;
function updateAvatar() {
    if (isDefault) {
    	alert("更换头像成功!");
    	window.location.reload();
    	return;
    }else{
	    //$('#successmsg').show();
	    var a = $('#mybigavatar');
	    var a1 = $("#myavatar");
	    var a2 = $("#mysmallAvatar");
	    a1.attr('src',a1.attr('src') + "?r=" + Math.random());
	    a.attr('src',a.attr('src') + "?r=" + Math.random());
	    a2.attr('src',a2.attr('src') + "?r=" + Math.random());
    }
}
</script>
