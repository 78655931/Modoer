<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<form method="post" action="<?=cpurl($module,$act,$op)?>" enctype="multipart/form-data">
    <div class="space">
        <div class="subtitle"><?=$subtitle?></div>
        <?if($act=='model_edit'):?>
        <ul class="cptab">
            <li><a href="<?=cpurl($module,'model_list')?>" onfocus="this.blur()">ģ�͹���</a></li>
            <li class="selected"><a href="#" onfocus="this.blur()">�༭ģ��</a></li>
            <li><a href="<?=cpurl($module,'field_list','',array('modelid'=>$t_model['modelid']))?>" onfocus="this.blur()">�Զ����ֶι���</a></li>
        </ul>
        <?endif;?>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td width="45%" class="altbg1"><strong>ģ�����ƣ�</strong>��дһ��ģ���ļ������Ա��̨����</td>
                <td width="55%"><input type="text" name="t_model[name]" class="txtbox2" value="<?=$t_model['name']?>" /></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>�������ݱ�:</strong>ÿһ��ģ�͵��Զ����ֶν�������һ���½��ı��У�<br />���ݱ�������Ӣ��Сд��ĸ[a-z]������[0-9]��ɡ�<br /><span class="font_1">������д���޷��ٴθĶ�</span></td>
                <td>
                    <?if($disabled){?>
                        [dbpre]<?=$t_model['tablename']?>
                    <? }else{?>
                        [dbpre]subject_<input type="text" name="t_model[tablename]" class="txtbox3" />
                    <?}?>
                </td>
            </tr>
            <tr>
                <td class="altbg1"><strong>���õ����س�:</strong>���������Ƿ��е�����������ʹ�õ���ͼ��ע�ȹ��ܡ�<br /><span class="font_1">������д���޷��ٴθĶ�</span></td>
                <td><?=form_bool('t_model[usearea]',$t_model?$t_model['usearea']:1)?></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>������������:</strong>�����������ݵ��ܳƣ��������̣���ҵ���ֻ�����Ϸ��</td>
                <td><input type="text" name="t_model[item_name]" class="txtbox2" value="<?=$t_model['item_name']?>" /></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>��������λ:</strong>�������������������λ���������̣������ң��ֻ�����</td>
                <td><input type="text" name="t_model[item_unit]" class="txtbox2" value="<?=$t_model['item_unit']?>" /></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>�б�ҳģ���ļ�:</strong>��ʾ���������б��ģ���ļ���������дģ�����Ƶĺ�׺��<br />Ĭ������дitem_subject_list</td>
                <td><input type="text" name="t_model[tplname_list]" class="txtbox2" value="<?=$t_model?$t_model['tplname_list']:'item_subject_list'?>" /><?=$_config['tplext']?></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>����ҳģ���ļ�:</strong>��ʾ����������ϸ��Ϣ��ģ���ļ��������գ����ڲ�ʹ�ö������ʱ��Ч<br />Ĭ������дitem_subject_detail</td>
                <td><input type="text" name="t_model[tplname_detail]" class="txtbox2" value="<?=$t_model?$t_model['tplname_detail']:'item_subject_detail'?>" /><?=$_config['tplext']?></td>
            </tr>
            <?if($act=='model_add'):?>
            <tr>
                <td class="altbg1"><strong>����ģ��XML�ļ���</strong>�����������Modoerϵͳվ�е�����ģ���ļ���û����������</td>
                <td><input type="file" name="model_import_file"></td>
            </tr>
            <?endif;?>
        </table>
        <center>
            <?if($modelid>0){?><input type="hidden" name="modelid" value="<?=$modelid?>" /><?}?>
            <button type="submit" name="dosubmit" value="yes" class="btn" /> �� �� </button>&nbsp;
            <button type="button" class="btn" onclick="history.go(-1);" /> �� �� </button>
        </center>
    </div>
</form>
</div>