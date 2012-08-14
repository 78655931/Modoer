<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<script type="text/javascript" src="./static/javascript/item.js"></script>
<script type="text/javascript">
function check_submit() {
    if($('[name=sid]').val()=="") {
        alert('选择加盟商铺。');
        return false;
    } else if ($('[name=discount]').val() == "" && $('[name=largess]').val() == "") {
        alert('未填写优惠折扣信息。');
        return false;
    }
    return true;
}

function selectdiscount(sort) {
    if(sort=='both'||sort=='') {
        $('#discount').css('display','');
        $('#largess').css('display','');
    } else if (sort=='discount') {
        $('#discount').css('display','');
        $('#largess').css('display','none');
    } else if (sort=='largess') {
        $('#discount').css('display','none');
        $('#largess').css('display','');
    }
}

$(document).ready(function() {
    selectdiscount('<?=$detail['cardsort']?>');
});
</script>
<div id="body">
<form method="post" action="<?=cpurl($module,$act,'save')?>" name="myform" onsubmit="return check_submit();">
    <div class="space">
        <div class="subtitle">添加/编辑加盟商家</div>
        <?if($edit_links):?>
        <ul class="cptab">
            <?foreach($edit_links as $val):?>
            <li<?if($val['flag']=='card:discount')echo' class="selected"';?>><a href="<?=$val['url']?>" onfocus="this.blur()"><?=$val['title']?></a></li>
            <?endforeach;?>
        </ul>
        <?endif;?>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td class="altbg1" width="20%">加盟商家:</td>
                <td>
                    <?if($subject):?>
                    <input type="hidden" name="sid" value="<?=$subject['sid']?>">
                    <?=$subject['name'].($subject['subname']?"($subject[subname])":'')?>
                    <?else:?>
                    <div id="subject_search">
					<?if($subject):?>
					<a href="<?=url("item/detail/id/$sid")?>" target="_blank"><?=$subject['name'].($subject['subname']?"($subject[subname])":'')?></a>
					<?endif;?>
					</div>
					<script type="text/javascript">
						var categorys = "<?=_JStr(form_card_use_model($subject['pid']))?>";
						$('#subject_search').item_subject_search({
							input_class:'txtbox2',
							btn_class:'btn2',
							result_css:'item_search_result',
							<?if($subject):?>
								sid:<?=$subject[sid]?>,
								current_ready:true,
								pid:<?=$subject['pid']?>,
							<?endif;?>
							hide_keyword:true,
							categorys:categorys
						});
					</script>
                    <?endif;?>
                </td>
            </tr>
            <tr>
                <td class="altbg1" width="20%">优惠形式:</td>
                <td width="80%">
                    <input type="radio" name="cardsort" value='discount' onclick="selectdiscount(this.value);" <?if($detail['cardsort']=='discount')echo' checked="checked"';?> /> 打折&nbsp;&nbsp;
                    <input type="radio" name="cardsort" value='largess' onclick="selectdiscount(this.value);" <?if($detail['cardsort']=='largess')echo' checked="checked"';?>/> 赠送&nbsp;&nbsp;
                    <input type="radio" name="cardsort" value='both' onclick="selectdiscount(this.value);" <?if($detail['cardsort']=='both'||!$detail['cardsort'])echo' checked="checked"';?>/> 两者都有
                </td>
            </tr>
            <tr id='discount'>
                <td class="altbg1">折扣度:</td>
                <td><input type="text" name="discount" value="<?=$detail['discount']?>" class="txtbox4" /> 折</td>
            </tr>
            <tr id='largess'>
                <td class="altbg1">赠送说明:</td>
                <td><input type="text" name="largess" value="<?=$detail['largess']?>" class="txtbox" /></td>
            </tr>
            <tr>
                <td class="altbg1">优惠说明(不优惠的物品):</td>
                <td><input type="text" name="exception" value="<?=$detail['exception']?>" class="txtbox" /> <span class="font_2">例如：烟酒、特价菜除外</span></td>
            </tr>
        </table>
        <center>
            <?if($op=='edit'):?>
            <input type="hidden" name="sid" value="<?=$sid?>" />
            <?endif;?>
            <input type="hidden" name="forward" value="<?=get_forward(cpurl($module,$act,'list'))?>" />
            <input type="hidden" name="do" value="<?=$op?>" />
            <button type="submit" name="dosubmit" value="yes" class="btn" /> 提交 </button>&nbsp;
            <button type="button" class="btn" value="yes" onclick="document.location='<?=cpurl($module,$act,'list')?>';"> 返回列表 </button>
        </center>
    </div>
</form>
</div>