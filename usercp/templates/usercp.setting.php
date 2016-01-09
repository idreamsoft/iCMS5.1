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
        <h3 class="ptitle"><span>个人资料</span></h3>
      </div>
    </div>
  </div>
  <div class="pbody">
    <div class="formcaption-note">填写完善的个人信息, 可以帮助用户更容易找到你.</div>
    <form action="<?php echo __USERCP__;?>=setting" method="post" target="iCMS_FRAME">
      <input name="do" type="hidden" value="setting" />
      <div class="formgroup">
        <div class="formrow">
          <h3 class="label">
            <label>帐号</label>
          </h3>
          <div class="form-enter">
            <input type="text" disabled value="<?php echo member::$Rs->username;?>" class="txt"/>
          </div>
        </div>
        <div class="formrow">
          <h3 class="label">
            <label>昵称</label>
          </h3>
          <div class="form-enter">
            <input name="nickname" type="text" id="nickname" value="<?php echo member::$Rs->nickname;?>" class="txt"/>
          </div>
        </div>
        <div class="formrow">
          <h3 class="label">
            <label>性别</label>
          </h3>
          <div class="form-enter">
            <select name="gender" id="gender" class="txt">
              <option value="2">保密</option>
              <option value="1">男</option>
              <option value="0">女</option>
            </select>
          </div>
        </div>
        <div class="formrow">
          <h3 class="label">
            <label>QQ/MSN</label>
          </h3>
          <div class="form-enter">
            <input name="icq" type="text" id="icq" value="<?php echo member::$Rs->info['icq'] ; ?>" maxlength="12" class="txt"/>
          </div>
        </div>
        <div class="formrow">
          <h3 class="label">
            <label>主页/博客</label>
          </h3>
          <div class="form-enter">
            <input name="home" type="text" id="home" value="<?php echo member::$Rs->info['home'] ; ?>" class="txt"/>
          </div>
        </div>
        <div class="formrow">
          <h3 class="label">
            <label>生日</label>
          </h3>
          <div class="form-enter">
            <select name="year" id="year" style="width:80px;">
              <option value=""></option>
              <option value="1970">1970</option>
              <option value="1971">1971</option>
              <option value="1972">1972</option>
              <option value="1973">1973</option>
              <option value="1974">1974</option>
              <option value="1975">1975</option>
              <option value="1976">1976</option>
              <option value="1977">1977</option>
              <option value="1978">1978</option>
              <option value="1979">1979</option>
              <option value="1980">1980</option>
              <option value="1981">1981</option>
              <option value="1982">1982</option>
              <option value="1983">1983</option>
              <option value="1984">1984</option>
              <option value="1985">1985</option>
              <option value="1986">1986</option>
              <option value="1987">1987</option>
              <option value="1988">1988</option>
              <option value="1989">1989</option>
              <option value="1990">1990</option>
              <option value="1991">1991</option>
              <option value="1992">1992</option>
              <option value="1993">1993</option>
              <option value="1994">1994</option>
              <option value="1995">1995</option>
              <option value="1996">1996</option>
              <option value="1997">1997</option>
              <option value="1998">1998</option>
              <option value="1999">1999</option>
              <option value="2000">2000</option>
              <option value="2001">2001</option>
              <option value="2002">2002</option>
            </select>
            年
            <select name="month" id="month"  style="width:60px;">
              <option value=""></option>
              <option value="1">1</option>
              <option value="2">2</option>
              <option value="3">3</option>
              <option value="4">4</option>
              <option value="5">5</option>
              <option value="6">6</option>
              <option value="7">7</option>
              <option value="8">8</option>
              <option value="9">9</option>
              <option value="10">10</option>
              <option value="11">11</option>
              <option value="12">12</option>
            </select>
            月
            <select name="day" id="day"  style="width:60px;">
              <option value=""></option>
              <option value="1">1</option>
              <option value="2">2</option>
              <option value="3">3</option>
              <option value="4">4</option>
              <option value="5">5</option>
              <option value="6">6</option>
              <option value="7">7</option>
              <option value="8">8</option>
              <option value="9">9</option>
              <option value="10">10</option>
              <option value="11">11</option>
              <option value="12">12</option>
              <option value="13">13</option>
              <option value="14">14</option>
              <option value="15">15</option>
              <option value="16">16</option>
              <option value="17">17</option>
              <option value="18">18</option>
              <option value="19">19</option>
              <option value="20">20</option>
              <option value="21">21</option>
              <option value="22">22</option>
              <option value="23">23</option>
              <option value="24">24</option>
              <option value="25">25</option>
              <option value="26">26</option>
              <option value="27">27</option>
              <option value="28">28</option>
              <option value="29">29</option>
              <option value="30">30</option>
              <option value="31">31</option>
            </select>
            日</div>
        </div>
        <div class="formrow">
          <h3 class="label">
            <label>来自</label>
          </h3>
          <div class="form-enter">
            <input name="from" type="text" id="from" value="<?php echo member::$Rs->info['from'] ; ?>" class="txt"/>
          </div>
        </div>
        <div class="formrow">
          <h3 class="label">
            <label>签名</label>
          </h3>
          <div class="form-enter">
            <textarea name="signature" id="signature" cols="45" rows="5" onkeyup="textareasize(this)" class="tarea"><?php echo member::$Rs->info['signature'] ; ?></textarea>
          </div>
        </div>
      </div>
      <div class="formrow formrow-action"> <span class="minbtn-wrap"><span class="btn">
        <input type="submit" value="提交" class="button" name="save" />
        </span></span> </div>
    </form>
  </div>
  <div class="pfoot">
    <p><b>-</b></p>
  </div>
</div>
<script type="text/javascript">
$("#gender").val("<?php echo member::$Rs->gender ; ?>");
$("#year").val("<?php echo member::$Rs->info['year'] ; ?>");
$("#month").val("<?php echo member::$Rs->info['month'] ; ?>");
$("#day").val("<?php echo member::$Rs->info['day'] ; ?>");
</script>