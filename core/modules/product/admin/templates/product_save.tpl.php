<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<script type="text/javascript" src="./static/javascript/validator.js"></script>
<script type="text/javascript" src="./static/javascript/item.js"></script>
<script type="text/javascript" src="./static/javascript/product.js"></script>
<script type="text/javascript">
function checkform(obj) {
    return true;
}
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
function product_create_category(sid) {
	if (!is_numeric(sid)) {
		alert('δѡ���Ʒ���⡣');
		return false;
	}
    var catname = prompt('���������ķ������ƣ�','');
    if(!catname) return;
	$.post("<?=cpurl($module,$act,'create_category')?>", {'sid':sid, 'catname':catname, 'in_ajax':1 }, 
		function(result) {
		if (is_message(result)) {
            myAlert(result);
        } else if(is_numeric(result)) {
			$("<option value='"+result+"'"+' selected="selected"'+">"+catname+"</option>").appendTo($('#catid'));
		} else {
		    alert('��Ʒ��ȡʧ�ܣ���������æµ�����Ժ��ԡ�');
        }
	});
    product_show_rename_button();
}
//����������
function product_rename_category() {
    var catid = $('#catid').val();
    var name = $('#catid').find("option:selected").text();
    var catname = prompt('���������ķ������ƣ�',name);
    if(!catname) return;
    $.post("<?=cpurl($module,$act,'rename_category')?>", {'catid':catid, 'catname':catname, 'in_ajax':1 }, 
        function(result) {
        if (is_message(result)) {
            myAlert(result);
        } else if(result=='OK') {
            $('#catid').find("option:selected").text(catname);
            msgOpen('���³ɹ�!');
        } else {
            alert('��Ʒ��ȡʧ�ܣ���������æµ�����Ժ��ԡ�');
        }
    });
}
//��ʾ����������İ�ť
function product_show_rename_button() {
    var catid = $('#catid').val();
    if(catid>0) {
        $('#rename_category').show();
    } else {
        $('#rename_category').hide();
    }
}
</script>
<div id="body">
<form method="post" action="<?=cpurl($module,$act,'save')?>" enctype="multipart/form-data" onsubmit="return validator(this);">
    <input type="hidden" name="sid" value="<?=$detail['sid']?>" />
    <div class="space">
        <div class="subtitle">���/�༭��Ʒ</div>
        <table width="95%" cellspacing="0" cellpadding="0" class="maintable">
            <tr id="tr_subject">
                <td width="100" class="altbg1" align="right"><span class="font_1">*</span>�������⣺</td>
                <td width="*">
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
                            location:"<?=cpurl($module,$act,'add',array('sid'=>'_SID_'))?>",
							<?if($subject):?>
								sid:<?=$subject[sid]?>,
								current_ready:true,
							<?endif;?>
							<?if($op=='edit'):?>
								change_disable:true,
							<?endif;?>
							hide_keyword:true
						});
					</script>
				</td>
            </tr>
			<?if($subject):?>
            <tr id="tr_category">
                <td width="100" class="altbg1" align="right"><span class="font_1">*</span>ѡ����ࣺ</td>
				<td><select name="catid" id="catid" validator="{'empty':'N','errmsg':'��ѡ���Ʒ���ࡣ'}" onchange="product_show_rename_button();">
						<option value="" style="color:#CC0000">==ѡ���Ʒ����==</option>
						<?=form_product_category($sid, $detail['catid']);?>
					</select>&nbsp;
					<button type="button" onclick="product_create_category(<?=$sid?>);" class="btn2">�½����</button>
                    <button type="button" onclick="product_rename_category();" class="btn2" id="rename_category"<?if(!$detail['catid']):?>style="display:none;"<?endif;?>>������</button>
				</td>
            </tr>
            <tr>
                <td width="100" class="altbg1" align="right"><span class="font_1">*</span>��Ʒ���ƣ�</td>
                <td width="*"><input type="text" name="subject" class="txtbox" size="40" value="<?=$detail['subject']?>" validator="{'empty':'N','errmsg':'����д��Ʒ���ơ�'}" /></td>
            </tr>
            <tr>
                <td class="altbg1" align="right">����ͼƬ��</td>
                <td>
                    <?if(!$detail['thumb']):?>
                    <input type="file" name="picture" size="20" />
                    <?else:?>
                    <span id="reload"><a href="<?=$detail['picture']?>" target="_blank" src="<?=$detail['thumb']?>" onmouseover="tip_start(this);"><?=$detail['thumb']?></a></span>&nbsp;&nbsp;[<a href="javascript:reload();" id="switch">�����ϴ�</a>]
                    <?endif;?>
                </td>
            </tr>
            <tr>
                <td class="altbg1" align="right" valign="top">�򵥽��ܣ�</td>
                <td><textarea name="description" style="width:500px;height:40px;"><?=$detail['description']?></textarea></td>
            </tr>
            <tr>
                <td class="altbg1" align="right">�ر����ۣ�</td>
                <td><?=form_bool('closed_comment',$detail['closed_comment'])?></td>
            </tr>
            <?=$custom_form?>
			<?endif;?>
        </table>
        <center>
            <input type="hidden" name="do" value="<?=$op?>" />
            <input type="hidden" name="pid" value="<?=$_GET['pid']?>" />
            <input type="hidden" name="forward" value="<?=get_forward()?>" />
            <button type="submit" class="btn" name="dosubmit" value="yes">�ύ</button>&nbsp;
            <button type="button" class="btn" onclick="history.go(-1);">����</button>&nbsp;
        </center>
    </div>
</form>
</div>