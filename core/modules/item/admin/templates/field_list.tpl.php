<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<form method="post" action="<?=cpurl($module,$act,'',array('modelid'=>$_GET['modelid']))?>">
    <div class="space">
        <div class="subtitle">�ֶι���</div>
        <ul class="cptab">
            <li><a href="<?=cpurl($module,'model_list')?>" onfocus="this.blur()">ģ�͹���</a></li>
            <li><a href="<?=cpurl($module,'model_edit','',array('modelid'=>$_GET['modelid']))?>" onfocus="this.blur()">�༭ģ��</a></li>
            <li class="selected"><a href="#" onfocus="this.blur()">�Զ����ֶι���</a></li>
            <li><a id="add_types" rel="add_types_box" href="#" onfocus="this.blur()"><span class="font_1">�½��ֶ�</span></a></li>
        </ul>
        <ul id="add_types_box" class="dropdown-menu">
            <?foreach($F->fieldtypes as $key => $val) { $exp = explode('|',$val); if($exp[1]=='N' && !$t_field) continue; ?>
            <li><a href="<?=cpurl($module,'field_edit','add',array('modelid'=>$_GET['modelid'],'fieldtype'=>$key))?>"><?=$exp[0]?></a></li>
            <?};?>
        </ul>
        <script type="text/javascript">
        $("#add_types").powerFloat();
        </script>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" trmouse="Y">
            <tr class="altbg1">
                <td width="60">����</td>
                <td width="200">�ֶα���</td>
                <td width="100">�ֶ���</td>
                <td width="140">������</td>
                <td width="60">�ֶ�����</td>
                <td width="40"><center>����</center></td>
                <td width="40"><center>�����</center></td>
                <td width="40"><center>�б�ҳ</center></td>
                <td width="40"><center>����ҳ</center></td>
                <td width="40"><center>�����</center></td>
                <td width="120">����</td>
            </tr>
            <?if($result) { foreach($result as $val) {?>
            <tr>
                <td><?=form_input("fields[$val[fieldid]][listorder]",$val['listorder'],'txtbox5')?></td>
                <td><?=form_input("fields[$val[fieldid]][title]",$val['title'],'txtbox3')?></td>
                <td><?=$val['fieldname']?></td>
                <td>[dbpre]<?=$val['tablename']?></td>
                <td><?=substr($F->fieldtypes[$val['type']],0,-2)?></td>
                <td style="text-align:center;"><?=$val['iscore']?'��':'��'?></td>
                <td style="text-align:center;"><?=form_bool_check("fields[$val[fieldid]][allownull]", $val['allownull'])?></td>
                <td style="text-align:center;"><?=form_bool_check("fields[$val[fieldid]][show_list]", $val['show_list'])?></td>
                <td style="text-align:center;"><?=form_bool_check("fields[$val[fieldid]][show_detail]", $val['show_detail'])?></td>
                <td style="text-align:center;"><?=form_bool_check("fields[$val[fieldid]][show_side]", $val['show_side'])?></td>
                <td>
                    <a href="<?=cpurl($module,'field_edit','edit',array('fieldid'=>$val['fieldid']))?>">�༭</a>
                    <?if(!$val['iscore']) { $disable = $val['disable'] ? 'enable' : 'disable'; ?>
                    <a href="<?=cpurl($module,'field_edit',$disable,array('modelid'=>$_GET['modelid'],'fieldid'=>$val['fieldid']))?>"><?=$val['disable']?'����':'����'?></a>
                    <a href="<?=cpurl($module,'field_edit','delete',array('modelid'=>$_GET['modelid'],'fieldid'=>$val['fieldid']))?>" onclick="return confirm('��ȷ��Ҫ����ɾ��������');">ɾ��</a>
                    <?}?>
                </td>
            </tr>
            <?}?>
            <?} else {?>
            <tr><td colspan="9">������Ϣ��</td></tr>
            <?}?>
        </table>
        <center>
            <?if($result) {?>
            <button type="submit" class="btn" name="dosubmit" value="yes">�ύ����</button>&nbsp;
            <?}?>
            <button type="button" class="btn" onclick="location.href='<?=cpurl($module,'model_list')?>'" /><?=lang('global_return')?></button>
        </center>
    </div>
</form>
</div>