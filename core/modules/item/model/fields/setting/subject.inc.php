<?php
    $_G['loader']->helper('form','item');
?>
<table class="subtable" border="0" cellspacing="0" cellpadding="0" width="100%">
    <tr>
        <td class="altbg1" width="45%"><strong>���������������ࣺ</strong>�����ĳһЩ�����������й�����ֻ֧��������</td>
        <td width="*">
            <?php
                $category = $_G['loader']->variable('category','item');
                $t_cfg['categorys'] = $t_cfg['categorys'] ? explode(',',$t_cfg['categorys']) : array();
                foreach($category as $val) {
                    $values[$val['catid']] = $val['name'];
                }
                echo form_check('t_cfg[categorys][]',$values,$t_cfg['categorys']);
            ?>
        </td>
    </tr>
    <tr>
        <td class="altbg1"><strong>�����ڵ�ǰ�ֶ��ڱ����������������</strong>������Χ�����ԡ����ࡱ������ʾ�ڶ����Ĺ����б��С�ע�⣺������Ķ�������ϵͳ�����֮ǰ���ڵ��������и��¡�</td>
        <td><input type="text" class="txtbox4" name="t_cfg[savelen]" value="<?=$t_cfg['savelen']>0?$t_cfg['savelen']:5?>" /></td>
    </tr>
    <tr>
        <td class="altbg1"><strong>ǰ̨��ʾʱ��ǩ֮��ķָ�����</strong>Ĭ��Ϊ����","</td>
        <td><input type="text" class="txtbox4" name="t_cfg[split]" value="<?=$t_cfg['split']?$t_cfg['split']:','?>" /></td>
    </tr>
    <tr>
        <td class="altbg1"><strong>����ҳ��ʾģʽ��</strong>��������ҳ���ʱ����ͼƬ��ʽ�������ַ�ʽ������ʾ</td>
        <td><?=form_radio('t_cfg[showmod]',array('pic'=>'ͼƬģʽ','word'=>'����ģʽ'),$t_cfg['showmod']?$t_cfg['showmod']:'word')?></td>
    </tr>
</table>