<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<form method="post" name="myform" action="<?=cpurl($module,$act,'')?>">
    <div class="space">
        <div class="subtitle"><?=$subtitle?></div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" trmouse="Y">
            <tr class="altbg1">
                <td width="25">[<a href="javascript:checkbox_checked('files[]');">ѡ</a>]</td>
                <td width="250">�ļ���</td>
                <td width="250">�ļ�˵��</td>
                <td width="130">�ϴ��޸�ʱ��</td>
                <td width="*">����</td>
            </tr>
            <? if($files):?>
            <? foreach($files as $k => $val):?>
            <tr>
                <input type="hidden" name="fielnames[<?=$k?>][filename]" value="<?=pathinfo($val,PATHINFO_BASENAME)?>" />
                <td><input type="checkbox" name="files[]" value="<?=str_replace(MUDDER_ROOT,'',$val)?>" /></td>
                <td><input type="text" class="txtbox2" name="fielnames[<?=$k?>][newfilename]" value="<?=pathinfo($val,PATHINFO_BASENAME)?>" style="width:100%" /></td>
                <td><input type="text" class="txtbox2" name="fielnames[<?=$k?>][des]" value="<?=$template_des[pathinfo($val,PATHINFO_BASENAME)]?>" style="width:100%" /></td>
                <td><?=date('Y-m-d H:i:s', filemtime($val))?></td>
                <td>
                    <?if($_G['modify_template']):?>
                    [<a href="<?=cpurl($module,$act,'edit',array('filename'=>str_replace(MUDDER_ROOT,'',$val)))?>">�༭</a>]
                    [<a href="<?=cpurl($module,$act,'add',array('filedir'=>$dir,'filename'=>str_replace(MUDDER_ROOT,'',$val)))?>">����</a>]
                    <?else:?>
                    -
                    <?endif;?>
                </td>
            </tr>
            <?endforeach;?>
            <?else:?>
            <tr><td colspan="6">û����Ϣ��</td></tr>
            <?endif;?>
        </table>
    </div>
    <center>
        <input type="hidden" name="root_dir" value="<?=$dir?>" />
        <input type="hidden" name="op" value="<?=$op?>" />
        <input type="hidden" name="dosubmit" value="yes" />
        <?php if($files):?>
        <input type="button" value="���²���" class="btn" onclick="easy_submit('myform', 'update', null);" />
        <input type="button" value="ɾ����ѡ" class="btn" onclick="easy_submit('myform', 'delete', 'files[]');" />
        <?php endif;?>
        <input type="button" value="������ģ��" class="btn" onclick="location.href='<?=cpurl($model,$act,'add',array('filedir'=>$dir))?>';" />&nbsp;
        <input type="button" class="btn" value="<?=lang('global_return')?>" onclick="location.href='<?=cpurl($module,$act)?>';" />
    </center>
</form>
</div>