<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<div class="space">
    <div class="subtitle">������ʾ</div>
    <table class="maintable" border="0" cellspacing="0" cellpadding="0">
    <tr><td>�ȼ�������ϵͳ���ù�����Ա�ȼ��Ĳ�����չ�ֶΣ�<br />�����е������¼���Ϊ���ж�Ӧ��ɾ���¼���Ϊ��ɾ���¼������ٵĻ��������ӵĻ�����ȡ�</td></tr>
    </table>
</div>
<?=form_begin(cpurl($module,$act,'post'))?>
    <div class="space">
        <div class="subtitle">��������</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0">
            <tr class="altbg1">
                <td width="70">ģ��</td>
                <td width="145">�¼�</td>
                <td width="*">point<br />(�ȼ�����)</td>
                <?foreach($groups as $key):?>
                <td width="*"><?=$key?><?if($point_group[$key]['enabled'])echo"<br />({$point_group[$key][name]})";?></td>
                <?endforeach;?>
            </tr>
            <?
            foreach($list as $flag => $rule):
                $rowspan = count($rule);
                foreach($rule as $key => $val):
            ?>
                <tr>
                    <?if($rowspan):?>
                        <td rowspan="<?=$rowspan;?>" class="altbg1" align="center" style="border-right:1px solid #BBDCF1;">
                            <?=$_G['modules'][$flag]['name']?>
                        </td>
                    <?endif;?>
                    <td class="altbg1" align="right"><?=$val?></td>
                    <td><input type="text" name="point[<?=$key?>][point]" value="<?=$point[$key]['point']?>" class="txtbox5" /></td>
                    <?foreach($groups as $_key):?>
                    <td><input type="text" name="point[<?=$key?>][<?=$_key?>]" value="<?=$point[$key][$_key]?>" class="txtbox5" /></td>
                    <?endforeach;?>
                </tr>
                <?$rowspan=0?>
                <?endforeach;?>
            <?endforeach;?>
        </table>
    </div>
    <center><?=form_submit('dosubmit',lang('admincp_submit'),'yes','btn')?></center>
<?=form_end()?>
</div>