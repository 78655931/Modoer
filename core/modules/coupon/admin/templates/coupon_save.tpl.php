<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<script type="text/javascript" src="./static/javascript/item.js"></script>
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

function check_submit() {
    if($('[name=sid]').val()=="") {
        alert('ѡ���Ż�ȯ�������̡�');
        return false;
    } else if ($('[name=subject]').val() == "" && $('[name=subject]').val() == "") {
        alert('δ��д�Ż�ȯ���ơ�');
        return false;
    } else if ($('[name=des]').val() == "" && $('[name=des]').val() == "") {
        alert('δ��д�Ż�ȯ���Ż���Ϣ��');
        return false;
    }
    return true;
}
</script>
<div id="body">
<form method="post" action="<?=cpurl($module,$act,'save')?>" name="myform" enctype="multipart/form-data" onsubmit="return check_submit();">
    <div class="space">
        <div class="subtitle">���/�༭�����̼�</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td class="altbg1" width="15%">�����̼�:</td>
                <td width="75%">
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
                <td class="altbg1">�Ż�ȯ����:</td>
                <td>
                    <input type="text" name="subject" value="<?=$detail['subject']?>" class="txtbox" />
                </td>
            </tr>
            <tr>
                <td class="altbg1">�Ż�ȯ����:</td>
                <td>
                    <select name="catid">
                        <option value="" selected="selected">==ѡ�����==</option>
                        <?=form_coupon_category($detail['catid']);?>
                    </select>
                </td>
            </tr>
            <tr>
                <td class="altbg1">�Ż�ȯͼƬ:</td>
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
                <td class="altbg1">�Ż�˵��:</td>
                <td><input type="text" name="des" value="<?=$detail['des']?>" class="txtbox" /></td>
            </tr>
            <tr>
                <td class="altbg1">��Ч��:</td>
                <td><input type="text" name="starttime" value="<?=$detail['starttime']?date('Y-m-d',$detail['starttime']):''?>" class="txtbox3" /> - <input type="text" name="endtime" value="<?=$detail['endtime']?date('Y-m-d',$detail['endtime']):''?>" class="txtbox3" />&nbsp;(YYYY-MM-DD)</td>
            </tr>
            <tr>
                <td class="altbg1">����:</td>
                <td>
                    <?=form_radio('status', array(1=>'����',2=>'ʧЧ'), $detail['status']?$detail['status']:1);?>
                </td>
            </tr>
            <tr>
                <td class="altbg1">��ϸ˵��:</td>
                <td><textarea name="content" style="height:100px; width:600px;"><?=$detail['content']?></textarea></td>
            </tr>
        </table>
        <center>
            <?if($op=='edit'):?>
            <input type="hidden" name="couponid" value="<?=$couponid?>" />
            <input type="hidden" name="forward" value="<?=get_forward()?>" />
            <?endif;?>
            <input type="hidden" name="do" value="<?=$op?>" />
            <button type="submit" name="dosubmit" value="yes" class="btn" /> �ύ </button>&nbsp;
            <button type="button" class="btn" value="yes" onclick="history.go(-1);"> ���� </button>
        </center>
    </div>
</form>
</div>