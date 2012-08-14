<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<script type="text/javascript" src="./data/cachefiles/article_category.js?r=<?=$MOD[jscache_flag]?>"></script>
<script type="text/javascript" src="./static/javascript/article.js"></script>
<script type="text/javascript" src="./static/javascript/item.js"></script>
<script type="text/javascript" src="./static/javascript/My97DatePicker/WdatePicker.js"></script>
<script type="text/javascript">
function article_save_succeed (data) {
    document.location = "<?=$forward?>";
}

window.onload = function() {
    <?if(!$detail['catid']):?>article_select_category(document.getElementById('pid'),'catid');<?endif;?>
}

function delete_article_thumb() {
    $('#article_thumb_input').val('N');
    $('#article_thumb').html('��ͼ');
}
</script>
<style type="text/css">
.altbg1 { text-align:right; }
#article_thumb { height:120px; width:120px; text-align:center; margin:5px auto 20px; }
#article_thumb img { max-height:120px; max-width:120px; 
    _width: expression(this.width > 120 ? 120 : true); _height: expression(this.height > 120 ? 120 : true); }
</style>
<div id="body">
<form method="post" action="<?=cpurl($module,$act,'save')?>" name="postform" id="postform">
    <div class="space">
        <div class="subtitle">����/�༭����</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" id="config1" mousemove='N'>
            <tr>
                <td class="altbg1" width="120"><span class="font_1">*</span>��������:</td>
                <td width="*"><input type="text" name="subject" class="txtbox" value="<?=$detail['subject']?>" /></td>
                <td width="150" rowspan="6" style="border-left:1px solid #BBDCF1;text-align:center;">
                    <div id="article_thumb">
                        <?if($detail['thumb']):?><a href="<?=$detail['picture']?>" target="_blank"><img src="<?=$detail['thumb']?>" /></a><?else:?>��ͼ<?endif;?>
                    </div>
                    <button type="button" class="btn2" onclick="article_upload_thumb_ui('article_thumb');">�ϴ�����</button>
                    <button type="button" class="btn2" onclick="delete_article_thumb();">ɾ��</button>
                    <input type="hidden" name="picture" id="article_thumb_input" value="">
                </td>
            </tr>
            <tr>
                <td class="altbg1"><span class="font_1">*</span>���·���:</td>
                <td>
                    <select name="pid" id="pid" style="width:auto;" onchange="article_select_category(this,'catid');">
                        <?=form_article_category(0,$detail['catid']);?>
                    </select>&nbsp;
                    <select name="catid" id="catid" style="width:200px;">
                        <?=$detail['catid']?form_article_category($detail['catid']):''?>
                    </select>
                    <span class="font_2">����ѡ�� 2 ������</span>
                </td>
            </tr>
            <tr>
                <td class="altbg1"><span class="font_1">*</span>��������:</td>
                <td>
                    <select name="city_id" id="city_id">
                        <?=form_city($detail['city_id'], TRUE, !$admin->is_founder);?>
                    </select>
                    <span class="font_2">ѡ��ȫ�֡���ʾ��ʾ�����г��з�վ��</span>
                </td>
            </tr>
            <tr>
                <td class="altbg1"><span class="font_1">*</span>�������״̬:</td>
                <td>
                    <?=form_bool('status',!$detail || $detail['status'] ? 1 : 0)?>
                </td>
            </tr>
            <tr>
                <td class="altbg1"><span class="font_1">*</span>�ر���������:</td>
                <td><?=form_bool('closed_comment',$detail['closed_comment'])?></td>
            </tr>
            <tr>
                <td class="altbg1">�Զ�������:</td>
                <td>
                    <input type="text" name="att" id="att" class="txtbox4" value="<?=$detail['att']?>" />
                    <select id="att_select" onchange="$('#att').val($('#att_select').val());">
                        <option value="0">=ѡ������=</option>
                        <?=form_article_att($detail['att'])?>
                    </select>
                    <div><span class="font_2">�� 0-255 ������ɣ������ܿ�ʵ��ѡ���Ե���ǰ̨չʾ</span></div>
                </td>
            </tr>
            <tr>
                <td class="altbg1">��������:</td>
                <td colspan="2">
					<div id="subject_search"></div>
					<script type="text/javascript">
						$('#subject_search').item_subject_search({
							input_class:'txtbox3',
							btn_class:'btn2',
							result_css:'item_search_result',
							<?if($detail['sid']):?>sid:'<?=$detail[sid]?>',<?endif;?>
							hide_keyword:true,
                            multi:true
						});
					</script>
                    <div><span class="font_2">��������ؼ��֣�ѡ����������������⣬�ɶ�ѡ</span></div>
                </td>
            </tr>
            <tr>
                <td class="altbg1"><span class="font_1">*</span>��������:</td>
                <td colspan="2"><input type="text" name="author" class="txtbox3" value="<?=$detail['author']?$detail['author']:$admin->adminname?>" /></td>
            </tr>
            <tr>
                <td class="altbg1">������Դ:</td>
                <td colspan="2"><input type="text" name="copyfrom" class="txtbox3" value="<?=$detail['copyfrom']?>" /></td>
            </tr>
            <tr>
                <td class="altbg1">�ؼ���:</td>
                <td colspan="2">
                    <input type="text" name="keywords" class="txtbox2" value="<?=$detail['keywords']?>" />
                    <span class="font_2">����ؼ��֣����ö���","�ָ�</span>
                </td>
            </tr>
            <tr>
                <td class="altbg1">����ʱ��:</td>
                <td colspan="2">
                    <input type="text" name="dateline" class="txtbox3" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm'})" value="<?=$detail['dateline']>0?date('Y-m-d H:i',$detail['dateline']):''?>" />
                    <span class="font_2">�½�ʱ���ձ�ǩ��ǰʱ��</span>
                </td>
            </tr>
            <tr>
                <td class="altbg1" valign="top">���¼��:</td>
                <td colspan="2">
                    <textarea name="introduce" style="width:600px;height:80px;"><?=$detail['introduce']?></textarea>
                    <div><span class="font_2">����������� 255 �ַ��ڣ�����ʱ��ϵͳ���Զ���ȡ���¿�ͷ�������֣���Ϊ���ݽ��ܣ���֧��HTML������ʾ</span></div>
                </td>
            </tr>
            <tr>
                <td class="altbg1" valign="top"><span class="font_1">*</span>��������:</td>
                <td colspan="2">
                    <?=$edit_html?>
                    <div><span class="font_2">���������ַ��������ƣ�<?=$MOD[content_min]?>-<?=$MOD[content_max]?>���޸��ַ�������ʾ���뵽ģ������ҳ���޸�</span></div>
                </td>
            </tr>
        </table>
    </div>
    <?if($op=='edit'):?>
    <input type="hidden" name="articleid" value="<?=$detail['articleid']?>" />
    <?endif;?>
    <input type="hidden" name="do" value="<?=$op?>" />
    <input type="hidden" name="forward" value="<?=$forward?>" />
    <center>
        <button type="button" class="btn" onclick="KE.util.setData('content');ajaxPost('postform', '', 'article_save_succeed');">�ύ</button>
        <button type="button" class="btn" onclick="document.location='<?=$forward?>';">����</button>
    </center>
</form>
</div>