<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<form method="get" action="<?=SELF?>">
    <input type="hidden" name="module" value="<?=$module?>" />
    <input type="hidden" name="act" value="<?=$act?>" />
    <input type="hidden" name="op" value="<?=$op?>" />
    <div class="space">
        <div class="subtitle">��Ա��ɸѡ</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td width="100" class="altbg1">�������</td>
                <td width="350">
                    <select name="pid">
                    <option value="">==ȫ��==</option>
                    <?=form_card_use_model($_GET['pid']);?>
                    </select>&nbsp;
                </td>
                <td width="100" class="altbg1">��������</td>
                <td width="*">
					<?if($admin->is_founder):?>
                    <select name="city_id">
                    <option value="">==����==</option>
                    <?=form_city($_GET['city_id'],TRUE);?>
                    </select>
					<?else:?>
					<?=$_CITY['name']?>
					<?endif;?>
                </td>
            </tr>
            <tr>
                <td class="altbg1">��Ա������</td>
                <td>
                    <select name="cardsort">
                        <option value="">==ȫ��==</option>
                        <option value="discount"<?if($_GET['type']=='discount')echo' selected="selected"';?>>�ۿ۷�ʽ</option>
                        <option value="largess"<?if($_GET['type']=='largess')echo' selected="selected"';?>>���ͷ�ʽ</option>
                        <option value="both"<?if($_GET['type']=='both')echo' selected="selected"';?>>���߶���</option>
                    </select>&nbsp;
                </td>
                <td class="altbg1">����SID</td>
                <td><input type="text" name="sid" class="txtbox3" value="<?=$_GET['sid']?>" /></td>
            </tr>
            <tr>
                <td class="altbg1">����ʱ��</td>
                <td colspan="3"><input type="text" name="starttime" class="txtbox3" value="<?=$_GET['starttime']?>" />&nbsp;~&nbsp;<input type="text" name="endtime" class="txtbox3" value="<?=$_GET['endtime']?>" />&nbsp;(YYYY-MM-DD)</td>
            </tr>
            <tr>
                <td class="altbg1">�������</td>
                <td colspan="3">
                <select name="orderby">
                    <option value="sid"<?=$_GET['orderby']=='sid'?' selected="selected"':''?>>Ĭ������</option>
                    <option value="addtime"<?=$_GET['orderby']=='addtime'?' selected="selected"':''?>>����ʱ��</option>
                </select>&nbsp;
                <select name="ordersc">
                    <option value="DESC"<?=$_GET['ordersc']=='DESC'?' selected="selected"':''?>>�ݼ�</option>
                    <option value="ASC"<?=$_GET['ordersc']=='ASC'?' selected="selected"':''?>>����</option>
                </select>&nbsp;
                <select name="offset">
                    <option value="20"<?=$_GET['offset']=='20'?' selected="selected"':''?>>ÿҳ��ʾ20��</option>
                    <option value="50"<?=$_GET['offset']=='50'?' selected="selected"':''?>>ÿҳ��ʾ50��</option>
                    <option value="100"<?=$_GET['offset']=='100'?' selected="selected"':''?>>ÿҳ��ʾ100��</option>
                </select>&nbsp;
                <button type="submit" value="yes" name="dosubmit" class="btn2">ɸѡ</button>
                </td>
            </tr>
        </table>
    </div>
</form>
<form method="post" action="<?=cpurl($module,$act)?>" name="myform">
    <div class="space">
        <div class="subtitle">ɸѡ���</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0">
            <tr class="altbg1">
                <td width="25">ɾ?</td>
                <td width="*">����</td>
                <td width="60">����</td>
                <td width="200">��Ϣ</td>
                <td width="200">��ע</td>
                <td width="110">����ʱ��</td>
                <td width="25">�Ƽ�</td>
                <td width="25">��Ч</td>
                <td width="50">����</td>
            </tr>
            <?php if($total) { ?>
            <?php while ($val=$list->fetch_array()) { ?>
            <tr>
                <input type="hidden" name="cards[<?=$val['sid']?>][sid]" value="<?=$val['sid']?>" />
                <td><input type="checkbox" name="sids[]" value="<?=$val['sid']?>" /></td>
                <td><a href="<?=url("item/detail/id/$val[sid]")?>" target="_blank"><?=trim($val['name'].$val['subname'])?></a><span class="font_2">[<?=template_print('modoer','area',array('aid'=>$val['city_id']))?>]</span></td>
                <td><span class="font_1"><?=$val['cardsort']?></span></td>
                <td>
                    <?if($val['cardsort']=='both'||$val['cardsort']=='discount'):?>
                    <?=$val['discount']?>��&nbsp;
                    <?endif;?>
                    <?if($val['cardsort']=='both'||$val['cardsort']=='largess'):?>
                    <?=$val['largess']?>
                    <?endif;?>
                </td>
                <td><?=$val['exception']?></td>
                <td><?=date('Y-m-d H:i',$val['addtime'])?></td>
                <td><input type="checkbox" name="cards[<?=$val['sid']?>][finer]" value="1" <?if($val['finer'])echo' checked="checked"';?> /></td>
                <td><input type="checkbox" name="cards[<?=$val['sid']?>][available]" value="1" <?if($val['available'])echo' checked="checked"';?> /></td>
                <td><a href="<?=cpurl($module,$act,'edit',array('sid'=>$val['sid']))?>">�༭</a></td>
            </tr>
            <? } ?>
            <tr class="altbg1"><td colspan="10">
                <button type="button" class="btn2" onclick="checkbox_checked('sids[]');">ȫѡ</button>&nbsp;
            </td></tr>
            <? } else { ?>
            <tr><td colspan="10">������Ϣ��</td></tr>
            <? } ?>
        </table>
        <?if($multipage){?><div class="multipage"><?=$multipage?></div><?}?>
        <center>
            <?php if($total) { ?>
            <input type="hidden" name="dosubmit" value="yes" />
            <input type="hidden" name="op" value="update" />
            <button type="button" class="btn" onclick="easy_submit('myform','update',null)">���²���</button>&nbsp;
            <button type="button" class="btn" onclick="easy_submit('myform','delete','sids[]')">ɾ����ѡ</button>
            <? } ?>
        </center>
    </div>
</form>
</div>