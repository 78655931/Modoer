<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<form method="post" action="<?=cpurl($module,$act,'post');?>">
    <input type="hidden" name="adminid" value="<?=$_GET['adminid']?>" />
    <div class="space">
        <div class="subtitle">����/�༭��̨�û�</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td class="altbg1" width="45%">�û�����</td>
                <td width="*">
                    <?if($admin->is_founder):?>
                    <input type="input" name="admin[adminname]" class="txtbox" value="<?=$detail['adminname']?>" />
                    <?else:?>
                    <?=$detail['adminname']?>
                    <?endif;?>
                </td>
            </tr>
            <tr>
                <td class="altbg1">E-mail��</td>
                <td><input type="text" name="admin[email]" value="<?=$detail['email']?>" class="txtbox"/></td>
            </tr>
            <tr><td colspan="2" class="altbg2"><strong>�޸�����</strong></td></tr>
            <tr>
                <td class="altbg1">�����룺</td>
                <td><input type="password" name="admin[password]" class="txtbox" /><?if($op=='edit') {?>&nbsp;���޸�������<? } ?></td>
            </tr>
            <tr>
                <td class="altbg1">ȷ�����룺</td>
                <td><input type="password" name="password2" class="txtbox" /></td>
            </tr>
            <?if($admin->is_founder && $detail['is_founder']!='Y'):?>
            <tr><td colspan="2" class="altbg2"><strong>Ȩ������ </strong></td></tr>
            <tr>
                <td class="altbg1"><strong>��ֹ��¼��</strong>��ֹ���ʺ��ں�̨��½Ȩ��</td>
                <td><?=form_bool('admin[closed]', $detail['closed']);?></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>��������ķ�վ���У�</strong>���ú�̨�ʺŵķ�վ����Ȩ�ޡ�</td>
                <td>
                    <?$citys=$_G['loader']->variable('area');?>
                    <select id="mycitys" name="admin[mycitys][]" multiple="true">
                        <?foreach($citys as $k => $v):?>
                        <option value="<?=$v['aid']?>"<?=$mycitys&&in_array($v['aid'],$mycitys)?' selected="selected"':''?>><?=$v['name']?></option>
                        <?endforeach;?>
                    </select>
                    <script type="text/javascript">
                    $('#mycitys').mchecklist();
                    </script>
                </td>
            </tr>
            <tr>
                <td class="altbg1"><strong>���������ģ�飺</strong>���ú�̨�ʺŵĹ���Ȩ�ޡ�</td>
                <td>
                    <select id="mymodules" name="admin[mymodules][]" multiple="true">
                        <option value="modoer"<?=$mymodules&&in_array('modoer',$mymodules)?' selected="selected"':''?>>�������</option>
                        <?foreach($_G['modules'] as $k => $v):?>
                        <option value="<?=$v['flag']?>" <?=$mymodules&&in_array($v['flag'],$mymodules)?' selected="selected"':''?>><?=$v['name']?></option>
                        <?endforeach;?>
                    </select>
                    <script type="text/javascript">
                    $('#mymodules').mchecklist();
                    </script>
                </td>
            </tr>
            <?elseif(!$admin->is_founder):?>
            <tr>
                <td class="altbg1"><strong>���������ģ�飺</strong>���ú�̨�ʺŵĹ���Ȩ�ޡ�</td>
                <td>
                    <?php foreach($mymodules as $flag) :
                    echo $split . ($flag=='modoer'?'�������':$_G['modules'][$flag]['name']);
                    $split='��';
                    endforeach;?>
                </td>
            </tr>
            <?endif;?>
        </table>
        <center>
            <input type="hidden" name="do" value="<?=$op?>" />
            <input type="hidden" name="forward" value="<?=get_forward()?>" />
            <input type="submit" name="dosubmit" value=" �ύ " class="btn" />&nbsp;
            <input type="button" value=" ���� " class="btn" onclick="history.go(-1);" />
        </center>
    </div>
</form>
</div>