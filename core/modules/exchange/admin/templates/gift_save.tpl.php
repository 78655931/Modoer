<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<style>
.allowtime{width:720px;list-style:none;margin:0px;padding:0px;float:left}
.allowtime li{float:left;width:90px;}
.dis_lottery{display:none;}
.dis_lottery2{}
</style>
<script type="text/javascript" src="./static/javascript/validator.js"></script>
<script type="text/javascript" src="./static/javascript/My97DatePicker/WdatePicker.js"></script>
<script type="text/javascript" src="./static/javascript/item.js"></script>
<script type="text/javascript" src="./static/javascript/exchange.js"></script>
<script type="text/javascript">
var g;
function reload() {
    var obj = document.getElementById('reload');
    var btn = document.getElementById('switch');
    if(obj.innerHTML.match(/^<.+href=.+>/)) {
        g = obj.innerHTML;
        obj.innerHTML = '<input type="file" name="picture" size="20">';
        btn.innerHTML = '取消上传';
    } else {
        obj.innerHTML = g;
        btn.innerHTML = '重新上传';
    }
}
function getval(q){
    var val = q.value;
    if (val == 2){
    	$(".dis_lottery").removeClass('dis_lottery').addClass('dis_lottery2');
    }else{
    	$(".dis_lottery2").removeClass('dis_lottery2').addClass('dis_lottery');
    }
}
$("document").ready(function(){
    $("#subbtn").click(function(){
        if($("#lottery").val()==2){
            if($("#starttime").val()=="" || $("#endtime").val()==""){
                alert("您选择了抽奖模式,但未设置抽奖开始时间与结束时间，请返回设置！");
                $("#starttime").focus();
                return false;
            }
            if($("#randomcodelen").val()==""){
                alert("您未设置随机抽奖码，请设置随机抽奖码！");
                $("#randomcodelen").focus();
                return false;
            }
         }
    });
    if($("#lottery").val()==2){
    	$(".dis_lottery").removeClass('dis_lottery').addClass('dis_lottery2');
    }
});
</script>
<div id="body">
<form method="post" action="<?=cpurl($module,$act,'save')?>" enctype="multipart/form-data" onsubmit="return validator(this);">
    <div class="space">
        <div class="subtitle">增加/编辑礼品</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" id="config1">
            <?if($op=='add'):?>
            <tr>
                <td class="altbg1">兑换类型:</td>
                <?$sort_arr = array(1=>'实物礼品',2=>'虚拟礼品');?>
                <td>
                    <select name="sort" onchange="document.location='<?=cpurl($module,$act,'add')?>&sort='+$(this).val();">
                        <?foreach ($sort_arr as $key => $value):?>
                            <option value="<?=$key?>"<?=$key==$sort?' selected="selected"':''?>><?=$value?></option>
                        <?endforeach;?>
                    </select>
                </td>
            </tr>
            <?endif;?>
            <tr>
                <td class="altbg1">兑换模式:</td>
                <td>
                    <select name="pattern" id="lottery" onchange="getval(this)">
                        <option value="1"<?=$detail['pattern']==1?' selected="selected"':''?>>自由兑换</option>
                        <option value="2"<?=$detail['pattern']==2?' selected="selected"':''?>>抽奖模式</option>
                    </select> <span class="font_2">若选择抽奖模式，库存则将作为中奖人数。</span>
                </td>
            </tr>
            <tr class="dis_lottery">
                <td class="altbg1">抽奖时间:</td>
                <td><input type="text" name="starttime" id="starttime" class="txtbox3" value="<?=$detail['starttime']?date('Y-m-d H:i',$detail['starttime']):''?>" onfocus="WdatePicker({doubleCalendar:true,dateFmt:'yyyy-MM-dd HH:mm',minDate:'%y-%M-%d'})" /> -- <input type="text" name="endtime" id="endtime" class="txtbox3" value="<?=$detail['endtime']?date('Y-m-d H:i',$detail['endtime']):''?>" onfocus="WdatePicker({doubleCalendar:true,dateFmt:'yyyy-MM-dd HH:mm',minDate:'%y-%M-%d'})" /></td>
            </tr>
            <tr class="dis_lottery">
                <td class="altbg1">随机码位数:</td>
                <td><input type="text" name="randomcodelen" class="txtbox5" value="<?=$detail['randomcodelen']?>" id="randomcodelen" onkeyup="randnum(this.value)" /> 位 <span class="font_1">不推荐随机码超过<b>2</b>位，位数越大，中奖几率越低。另外：此礼品添加成功后，该随机码位数将不可再更改，请慎重选择。</span></td>
            </tr>
            <tr class="dis_lottery">
                <td class="altbg1">抽奖随机码:</td>
                <td><span id="randcode"><?=$detail['randomcode']?></span> <span class="font_1">抽奖随机码由系统自动生成，无法更改，用作和前台会员抽奖时获得的随机兑换码做比对，若一致则中奖。</span></td>
            </tr>
            <tr>
                <td class="altbg1" width="120">礼品名称:</td>
                <td width="*"><input type="text" name="name" class="txtbox" value="<?=$detail['name']?>" validator="{'empty':'N','errmsg':'未填写礼品名称。'}" /></td>
            </tr>
            <tr>
                <td class="altbg1">城市:</td>
                <td>
                    <select name="city_id">
                        <?=form_city($detail['city_id'],true,!$admin->is_founder)?>
                    </select>
                </td>
            </tr>
            <tr>
                <td class="altbg1">所属分类:</td>
                <td>
                    <select name="catid" validator="{'empty':'N','errmsg':'未完成 分类 的设置，请返回设置。'}">
                        <option value="" selected="selected">==选择分类==</option>
                        <?=form_exchange_category($detail['catid']);?>
                    </select>
                </td>
            </tr>
            <tr>
                <td class="altbg1">关联主题礼品赞助商:</td>
                <td colspan="2">
					<div id="subject_search">
					<?if($subject):?>
					<a href="<?=url("item/detail/id/$sid")?>" target="_blank"><?=$subject['name'].($subject['subname']?"($subject[subname])":'')?></a>
					<?endif;?>
					</div>
					<script type="text/javascript">
						$('#subject_search').item_subject_search({
							input_class:'txtbox2',
							btn_class:'btn2',
							result_css:'item_search_result',
							<?if($subject):?>
								sid:<?=$subject[sid]?>,
								current_ready:true,
							<?endif;?>
							hide_keyword:true
						});
					</script>
                </td>
            </tr>
            <tr>
                <td class="altbg1">排序:</td>
                <td><input type="text" name="displayorder" class="txtbox4" value="<?=$detail['displayorder']?>" /></td>
            </tr>
            <tr>
                <td class="altbg1">可用:</td>
                <td><?=form_bool('available',$detail['available']?$detail['available']:1)?></td>
            </tr>
            <tr>
                <td class="altbg1">点评后方可兑换:</td>
                <td><?=form_bool('reviewed',$detail['reviewed'])?></td>
            </tr>
            <tr>
                <td class="altbg1">积分类型一:</td>
                <td><input type="text" name="price" class="txtbox4" value="<?=$detail['price']?>" validator="{'empty':'N','errmsg':'未填写礼品兑换所需积分。'}" />
                <select name="pointtype" validator="{'empty':'N','errmsg':'未选择价格积分类型。'}">
                    <option value="">选择积分类型</option>
                    <option value="rmb"<?=$detail['pointtype']=='rmb'?' selected="selected"':''?>>现金</option>
                    <?=form_member_pointgroup($detail['pointtype'])?>
                </select> <span class="font_2">此项必填，兑换该礼品可以使用的积分类型一。</span></td>
            </tr>
            <tr>
                <td class="altbg1">积分类型二:</td>
                <td><input type="text" name="point" class="txtbox4" value="<?=$detail['point']?>" />
                <select name="pointtype2">
                    <option value="">选择积分类型</option>
                    <option value="rmb"<?=$detail['pointtype2']=='rmb'?' selected="selected"':''?>>现金</option>
                    <?=form_member_pointgroup($detail['pointtype2'])?>
                </select> <span class="font_2">此项选填，兑换该礼品可以使用的积分类型二。</span></td>
            </tr>
            <tr>
                <td class="altbg1">积分类型三:</td>
                <td><input type="text" name="point3" class="txtbox4" value="<?=$detail['point3']?>" />
                <select name="pointtype3">
                    <option value="">选择积分类型</option>
                    <option value="rmb"<?=$detail['pointtype3']=='rmb'?' selected="selected"':''?>>现金</option>
                    <?=form_member_pointgroup($detail['pointtype3'])?>
                </select> + <input type="text" name="point4" class="txtbox4" value="<?=$detail['point4']?>" />
                <select name="pointtype4">
                    <option value="">选择积分类型</option>
                    <option value="rmb"<?=$detail['pointtype4']=='rmb'?' selected="selected"':''?>>现金</option>
                    <?=form_member_pointgroup($detail['pointtype4'])?>
                </select> <span class="font_2">此项选填，兑换该礼品可以使用的积分类型三，此为积分组合。</span></td>
            </tr>
            <tr>
                <td class="altbg1" width="120">允许兑换的用户组:</td>
                <td>
                    <?foreach($usergroup as $key => $val):?>
                    <?if($val['grouptype']=="system") continue;?>
                    <input type="checkbox" name="usergroup[]" id="usergroup_<?=$key?>" value="<?=$val['groupid']?>"<?if(strpos($detail[usergroup],",$key,")!==false)echo' checked';?> />&nbsp;<label for="usergroup_<?=$key?>"><?=$val['groupname']?></label>
                    <?endforeach;?>
                </td>
            </tr>
            <tr>
                <td class="altbg1">允许兑换的时间域:<br /><span class="font_1">不选则不做限制</span></td>
                <td>
                	<ul class="allowtime">
                        <?for ($i=0; $i<24 ; $i++):?> 
                            <li><input type="checkbox" name="allowtime[]" value="<?=$i?>"<?if(strpos($detail[allowtime],",$i,")!==false)echo' checked';?> /><?=$i?> 点</li>
                        <?endfor;?>
                	</ul>
                	<button type="button" onclick="checkbox_checked('allowtime[]');" class="btn2" style="margin-top:38px;">全选</button>
                </td>
            </tr>
            <tr>
                <td class="altbg1">可兑换的数量:</td>
                <td><input type="text" name="timenum" class="txtbox4" value="<?=$detail['timenum']?>" /><span class="font_2">每个时间段可兑换的数量。当前时间段兑换完则不可再兑换。若不选择则不限制.</span></td>
            </tr>
            <tr>
                <td class="altbg1">库存:</td>
                <td>
					<?if($sort=='2'&&$op=='add'):?>
					<textarea name="serial" rows="8" cols="40"></textarea>
					<div class="font_1">一行一条卡密信息;例如：卡号:12345678 密码:123456</div>
					<?elseif($sort=='2'&&$op=='edit'):?>
					<?=$detail['num']?>&nbsp;
					<a href="<?=cpurl($module,'serial','list',array('giftid'=>$giftid))?>">管理卡密数据</a>
					<?else:?>
					<input type="text" name="num" class="txtbox4" value="<?=$detail['num']?>" />
					<?endif;?>
				</td>
            </tr>
            <tr>
                <td class="altbg1">图片：</td>
                <td>
                    <?if(!$detail['thumb']):?>
                    <input type="file" name="picture" size="20" />
                    <?else:?>
                    <span id="reload"><a href="<?=$detail['picture']?>" target="_blank" src="<?=$detail['thumb']?>" onmouseover="tip_start(this);"><?=$detail['thumb']?></a></span>&nbsp;
                    [<a href="javascript:reload();" id="switch">重新上传</a>]
                    <?endif;?>
                </td>
            </tr>
            <tr>
                <td class="altbg1">介绍:</td>
                <td><?=$edit_html?></td>
            </tr>
        </table>
    </div>
    <?if($op=='edit'):?>
    <input type="hidden" name="giftid" value="<?=$detail['giftid']?>" />
    <?endif;?>
    <input type="hidden" name="do" value="<?=$op?>" />
    <input type="hidden" name="sort" value="<?=$sort?>" />
    <input type="hidden" name="forward" value="<?=get_forward()?>" />
    <input type="hidden" name="randomcode" id="randomcode" value="<?=$detail['randomcode']?>" />
    <center>
		<button type="submit" name="dosubmit" value="yes" class="btn" id="subbtn" /> 提交 </button>&nbsp;
    	<input type="button" class="btn" value="返回" onclick="document.location='<?=cpurl($module,$act,'list')?>';">
</center>
</form>
</div>