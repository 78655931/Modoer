<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<form method="post" action="<?=cpurl($module,$act,'',array('type'=>$tpltype))?>">
    <div class="space">
        <div class="subtitle"><?=$subtitle?></div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" trmouse="Y">
            <tr class="altbg1">
                <td width="35">ɾ?</td>
                <td width="110">ģ������</td>
                <td width="215">����Ŀ¼</td>
                <?if($use_price):?>
                <td width="180">�ۼ�</td>
                <?endif;?>
                <td width="180">��Ȩ��Ϣ</td>
                <td width="*">����</td>
            </tr>
            <? if($list):?>
            <? foreach($list as $val):?>
            <tr>
                <td><input type="checkbox" <?if($val['templateid']==='1'){?>disabled<?}else{?>name="templateids[]" value="<?=$val['templateid']?>"<?}?> /></td>
                <td><input type="text" name="templates[<?=$val['templateid']?>][name]" value="<?=$val['name']?>" class="txtbox4" /></td>
                <td>./templates/<?=$tpltype?>/<input type="text" name="templates[<?=$val['templateid']?>][directory]" value="<?=$val['directory']?>" class="txtbox4" /></td>
                <?if($use_price):?>
                <td><input type="text" name="templates[<?=$val['templateid']?>][price]" class="txtbox4" value="<?=$val['price']?>" />&nbsp;<?=display('member:point',"point/$selltype_pointtype")?></td>
                <?endif;?>
                <td><?=$val['copyright']?></td>
                <td>
                    [<a href="<?=cpurl($module,$act,'manage',array('type'=>$tpltype,'templateid'=>$val['templateid']))?>">ģ���ļ�����</a>]&nbsp;
                    <?if($tpltype=='main'):?>[<a href="<?=cpurl($module,$act,'manage',array('type'=>'datacall','templateid'=>$val['templateid']))?>">����ģ�����</a>]<?endif;?>
                </td>
            </tr>
            <?endforeach;?>
            <?else:?>
            <tr><td colspan="5">������Ϣ</td></tr>
            <?endif;?>
            <tr class="altbg1">
                <td>����:</td>
                <input type="hidden" name="newtemplate[tpltype]" value="<?=$tpltype?>" />
                <td><input type="text" name="newtemplate[name]" class="txtbox4" /></td>
                <td>./templates/<?=$tpltype?>/<input type="text" name="newtemplate[directory]" class="txtbox4" /></td>
                <?if($use_price):?>
                <td><input type="text" name="price" class="txtbox4" />&nbsp;<?=display('member:point',"point/$selltype_pointtype")?></td>
                <?endif;?>
                <td colspan="2"><input type="text" name="newtemplate[copyright]" class="txtbox4" /></td>
            </tr>
        </table>
        <center><input type="submit" name="dosubmit" value="<?=lang('global_submit')?>" class="btn" /></center>
    </div>
</form>
</div>