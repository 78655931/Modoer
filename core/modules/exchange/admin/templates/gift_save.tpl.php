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
        btn.innerHTML = 'ȡ���ϴ�';
    } else {
        obj.innerHTML = g;
        btn.innerHTML = '�����ϴ�';
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
                alert("��ѡ���˳齱ģʽ,��δ���ó齱��ʼʱ�������ʱ�䣬�뷵�����ã�");
                $("#starttime").focus();
                return false;
            }
            if($("#randomcodelen").val()==""){
                alert("��δ��������齱�룬����������齱�룡");
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
        <div class="subtitle">����/�༭��Ʒ</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" id="config1">
            <?if($op=='add'):?>
            <tr>
                <td class="altbg1">�һ�����:</td>
                <?$sort_arr = array(1=>'ʵ����Ʒ',2=>'������Ʒ');?>
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
                <td class="altbg1">�һ�ģʽ:</td>
                <td>
                    <select name="pattern" id="lottery" onchange="getval(this)">
                        <option value="1"<?=$detail['pattern']==1?' selected="selected"':''?>>���ɶһ�</option>
                        <option value="2"<?=$detail['pattern']==2?' selected="selected"':''?>>�齱ģʽ</option>
                    </select> <span class="font_2">��ѡ��齱ģʽ���������Ϊ�н�������</span>
                </td>
            </tr>
            <tr class="dis_lottery">
                <td class="altbg1">�齱ʱ��:</td>
                <td><input type="text" name="starttime" id="starttime" class="txtbox3" value="<?=$detail['starttime']?date('Y-m-d H:i',$detail['starttime']):''?>" onfocus="WdatePicker({doubleCalendar:true,dateFmt:'yyyy-MM-dd HH:mm',minDate:'%y-%M-%d'})" /> -- <input type="text" name="endtime" id="endtime" class="txtbox3" value="<?=$detail['endtime']?date('Y-m-d H:i',$detail['endtime']):''?>" onfocus="WdatePicker({doubleCalendar:true,dateFmt:'yyyy-MM-dd HH:mm',minDate:'%y-%M-%d'})" /></td>
            </tr>
            <tr class="dis_lottery">
                <td class="altbg1">�����λ��:</td>
                <td><input type="text" name="randomcodelen" class="txtbox5" value="<?=$detail['randomcodelen']?>" id="randomcodelen" onkeyup="randnum(this.value)" /> λ <span class="font_1">���Ƽ�����볬��<b>2</b>λ��λ��Խ���н�����Խ�͡����⣺����Ʒ��ӳɹ��󣬸������λ���������ٸ��ģ�������ѡ��</span></td>
            </tr>
            <tr class="dis_lottery">
                <td class="altbg1">�齱�����:</td>
                <td><span id="randcode"><?=$detail['randomcode']?></span> <span class="font_1">�齱�������ϵͳ�Զ����ɣ��޷����ģ�������ǰ̨��Ա�齱ʱ��õ�����һ������ȶԣ���һ�����н���</span></td>
            </tr>
            <tr>
                <td class="altbg1" width="120">��Ʒ����:</td>
                <td width="*"><input type="text" name="name" class="txtbox" value="<?=$detail['name']?>" validator="{'empty':'N','errmsg':'δ��д��Ʒ���ơ�'}" /></td>
            </tr>
            <tr>
                <td class="altbg1">����:</td>
                <td>
                    <select name="city_id">
                        <?=form_city($detail['city_id'],true,!$admin->is_founder)?>
                    </select>
                </td>
            </tr>
            <tr>
                <td class="altbg1">��������:</td>
                <td>
                    <select name="catid" validator="{'empty':'N','errmsg':'δ��� ���� �����ã��뷵�����á�'}">
                        <option value="" selected="selected">==ѡ�����==</option>
                        <?=form_exchange_category($detail['catid']);?>
                    </select>
                </td>
            </tr>
            <tr>
                <td class="altbg1">����������Ʒ������:</td>
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
                <td class="altbg1">����:</td>
                <td><input type="text" name="displayorder" class="txtbox4" value="<?=$detail['displayorder']?>" /></td>
            </tr>
            <tr>
                <td class="altbg1">����:</td>
                <td><?=form_bool('available',$detail['available']?$detail['available']:1)?></td>
            </tr>
            <tr>
                <td class="altbg1">�����󷽿ɶһ�:</td>
                <td><?=form_bool('reviewed',$detail['reviewed'])?></td>
            </tr>
            <tr>
                <td class="altbg1">��������һ:</td>
                <td><input type="text" name="price" class="txtbox4" value="<?=$detail['price']?>" validator="{'empty':'N','errmsg':'δ��д��Ʒ�һ�������֡�'}" />
                <select name="pointtype" validator="{'empty':'N','errmsg':'δѡ��۸�������͡�'}">
                    <option value="">ѡ���������</option>
                    <option value="rmb"<?=$detail['pointtype']=='rmb'?' selected="selected"':''?>>�ֽ�</option>
                    <?=form_member_pointgroup($detail['pointtype'])?>
                </select> <span class="font_2">�������һ�����Ʒ����ʹ�õĻ�������һ��</span></td>
            </tr>
            <tr>
                <td class="altbg1">�������Ͷ�:</td>
                <td><input type="text" name="point" class="txtbox4" value="<?=$detail['point']?>" />
                <select name="pointtype2">
                    <option value="">ѡ���������</option>
                    <option value="rmb"<?=$detail['pointtype2']=='rmb'?' selected="selected"':''?>>�ֽ�</option>
                    <?=form_member_pointgroup($detail['pointtype2'])?>
                </select> <span class="font_2">����ѡ��һ�����Ʒ����ʹ�õĻ������Ͷ���</span></td>
            </tr>
            <tr>
                <td class="altbg1">����������:</td>
                <td><input type="text" name="point3" class="txtbox4" value="<?=$detail['point3']?>" />
                <select name="pointtype3">
                    <option value="">ѡ���������</option>
                    <option value="rmb"<?=$detail['pointtype3']=='rmb'?' selected="selected"':''?>>�ֽ�</option>
                    <?=form_member_pointgroup($detail['pointtype3'])?>
                </select> + <input type="text" name="point4" class="txtbox4" value="<?=$detail['point4']?>" />
                <select name="pointtype4">
                    <option value="">ѡ���������</option>
                    <option value="rmb"<?=$detail['pointtype4']=='rmb'?' selected="selected"':''?>>�ֽ�</option>
                    <?=form_member_pointgroup($detail['pointtype4'])?>
                </select> <span class="font_2">����ѡ��һ�����Ʒ����ʹ�õĻ�������������Ϊ������ϡ�</span></td>
            </tr>
            <tr>
                <td class="altbg1" width="120">����һ����û���:</td>
                <td>
                    <?foreach($usergroup as $key => $val):?>
                    <?if($val['grouptype']=="system") continue;?>
                    <input type="checkbox" name="usergroup[]" id="usergroup_<?=$key?>" value="<?=$val['groupid']?>"<?if(strpos($detail[usergroup],",$key,")!==false)echo' checked';?> />&nbsp;<label for="usergroup_<?=$key?>"><?=$val['groupname']?></label>
                    <?endforeach;?>
                </td>
            </tr>
            <tr>
                <td class="altbg1">����һ���ʱ����:<br /><span class="font_1">��ѡ��������</span></td>
                <td>
                	<ul class="allowtime">
                        <?for ($i=0; $i<24 ; $i++):?> 
                            <li><input type="checkbox" name="allowtime[]" value="<?=$i?>"<?if(strpos($detail[allowtime],",$i,")!==false)echo' checked';?> /><?=$i?> ��</li>
                        <?endfor;?>
                	</ul>
                	<button type="button" onclick="checkbox_checked('allowtime[]');" class="btn2" style="margin-top:38px;">ȫѡ</button>
                </td>
            </tr>
            <tr>
                <td class="altbg1">�ɶһ�������:</td>
                <td><input type="text" name="timenum" class="txtbox4" value="<?=$detail['timenum']?>" /><span class="font_2">ÿ��ʱ��οɶһ�����������ǰʱ��ζһ����򲻿��ٶһ�������ѡ��������.</span></td>
            </tr>
            <tr>
                <td class="altbg1">���:</td>
                <td>
					<?if($sort=='2'&&$op=='add'):?>
					<textarea name="serial" rows="8" cols="40"></textarea>
					<div class="font_1">һ��һ��������Ϣ;���磺����:12345678 ����:123456</div>
					<?elseif($sort=='2'&&$op=='edit'):?>
					<?=$detail['num']?>&nbsp;
					<a href="<?=cpurl($module,'serial','list',array('giftid'=>$giftid))?>">����������</a>
					<?else:?>
					<input type="text" name="num" class="txtbox4" value="<?=$detail['num']?>" />
					<?endif;?>
				</td>
            </tr>
            <tr>
                <td class="altbg1">ͼƬ��</td>
                <td>
                    <?if(!$detail['thumb']):?>
                    <input type="file" name="picture" size="20" />
                    <?else:?>
                    <span id="reload"><a href="<?=$detail['picture']?>" target="_blank" src="<?=$detail['thumb']?>" onmouseover="tip_start(this);"><?=$detail['thumb']?></a></span>&nbsp;
                    [<a href="javascript:reload();" id="switch">�����ϴ�</a>]
                    <?endif;?>
                </td>
            </tr>
            <tr>
                <td class="altbg1">����:</td>
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
		<button type="submit" name="dosubmit" value="yes" class="btn" id="subbtn" /> �ύ </button>&nbsp;
    	<input type="button" class="btn" value="����" onclick="document.location='<?=cpurl($module,$act,'list')?>';">
</center>
</form>
</div>