<?php
/**
 * @package iCMS
 * @copyright 2007-2010, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */
!defined('iPATH') && exit('What are you doing?');
?>
<script type="text/javascript">
$(function(){
	$("form").submit(function(){
		if($("#password").val()==''){
			alert("原密码不能为空!");
			$("#password").focus();
			return false;
		}
		if($("#newpwd1").val()==''){
			alert("新密码不能为空!");
			$("#newpwd1").focus();
			return false;
		}
		if($("#newpwd1").val()!=$("#newpwd2").val()){
			alert("新密码与确认密码不一致!");
			$("#newpwd2").focus();
			return false;
		}
	});
});
</script>

<div class="pwrap">
  <div class="phead">
    <div class="phead-inner">
      <div class="phead-inner">
        <h3 class="ptitle"><span>修改密码</span></h3>
      </div>
    </div>
  </div>
  <div class="pbody">
    <form action="<?php echo __USERCP__;?>=setting" method="post" target="iCMS_FRAME">
      <input name="do" type="hidden" value="changepassword" />
      <div class="formgroup">
        <div class="formrow">
          <h3 class="label">
            <label>原密码</label>
          </h3>
          <div class="form-enter">
            <input name="password" type="password" id="password" class="txt"/>
          </div>
        </div>
        <div class="formrow">
          <h3 class="label">
            <label>新密码</label>
          </h3>
          <div class="form-enter">
            <input name="newpwd1" type="password" id="newpwd1" class="txt"/>
          </div>
        </div>
        <div class="formrow">
          <h3 class="label">
            <label>确认密码</label>
          </h3>
          <div class="form-enter">
            <input name="newpwd2" type="password" id="newpwd2" class="txt"/>
          </div>
        </div>
        <div class="formrow formrow-action"> <span class="minbtn-wrap"><span class="btn">
          <input type="submit" value="提交" class="button" name="save" />
          </span></span> </div>
      </div>
    </form>
  </div>
  <div class="pfoot">
    <p><b>-</b></p>
  </div>
</div>
