<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<form method="post" name="myform" action="<?=cpurl($module,$act)?>">
    <input type="hidden" name="op" value="list_update" />
    <input type="hidden" name="dosubmit" value="yes" />
    <div class="space">
        <div class="subtitle">�Ѱ�װģ���б�</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" trmouse="Y">
            <tr class="altbg1">
                <td width="25">ѡ</td>
                <td width="70">����</td>
                <td width="30">����</td>
                <td width="*">����</td>
                <td width="90">��ʶ</td>
                <td width="100">Ŀ¼</td>
                <td width="100">������</td>
                <td width="100">�汾</td>
                <td width="110">����ʱ��</td>
                <td width="120">����</td>
                <td width="120">����</td>
            </tr>
            <?while($val=$list->fetch_array()) {?>
            <tr>
                <td><input type="checkbox" name="moduleflags[]" value="<?=$val['flag']?>" /></td>
                <td><input type="text" name="modules[<?=$val['moduleid']?>][listorder]" value="<?=$val['listorder']?>" class="txtbox5" /></td>
                <td><?=$val['iscore']?'��':'��'?></td>
                <td><?=form_input("modules[{$val['moduleid']}][name]",$val['name'],'txtbox4')?></td>
                <td><?=$val['flag']?></td>
                <td><?=$val['directory']?></td>
                <td><?=$val['reliant']?></td>
                <td>
                    <a href="<?=cpurl($module,$act,'versioncheck',array('moduleid'=>$val['moduleid']))?>" title="�°汾���"><?=$val['version']?></a>
                    <?if($v=$MM->update_check($val)):?>
                    <a href="<?=cpurl($module,$act,'versionupdate',array('moduleid'=>$val['moduleid']))?>" title="���������<?=$v?>"><span class="font_1">����!</span></a>
                    <?endif;?>
                </td>
                <td><?=$val['releasetime']?></td>
                <td><a href="<?=$val['siteurl']?>" target="_blank"><?=$val['author']?></a></td>
                <td>
                    <a href="<?=cpurl($val['flag'],'config')?>">����</a>
                    <?if(!$val['iscore']) {?>
                    <?if($val['disable']) {?>
                    <a href="<?=cpurl($module,$act,'enable',array('moduleid'=>$val['moduleid']))?>" title="���ñ�ģ��">����</a>
                    <?} else {?>
                    <a href="<?=cpurl($module,$act,'disable',array('moduleid'=>$val['moduleid']))?>" title="���ñ�ģ��">����</a>
                    <?}?>
                    <a href="<?=cpurl($module,$act,'unstall',array('moduleid'=>$val['moduleid']))?>" onclick="return window.confirm('�����������棬��ȷ��ɾ����ģ�飨ɾ��ģ���ļ��к����ݱ���');">ɾ��</a>
                    <?}?>
                </td>
            </tr>
            <?
                if(isset($local_modues[$val['flag']])) unset($local_modues[$val['flag']]);
            }
            $list->free_result();
            ?>
            <tr class="altbg1">
                <td colspan="12">
                    <button type="button" class="btn2" onclick="checkbox_checked('moduleflags[]');">ȫѡ</button>
                    <button type="button" class="btn2" onclick="easy_submit('myform', 'cache', 'moduleflags[]');">������ѡģ�����û���</button>
                    <button type="button" class="btn2" onclick="easy_submit('myform', 'list_update', null);">�ύ����</button>
                </td>
            </tr>
        </table>
    </div>
    <?if($local_modues):?>
    <div class="space">
        <div class="subtitle">δ��װģ���б�</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" trmouse="Y">
            <tr class="altbg1">
                <td width="150">����</td>
                <td width="80">Ŀ¼</td>
                <td width="100">������</td>
                <td width="80">ģ��汾</td>
                <td width="110">����ʱ��</td>
                <td width="120">����</td>
                <td width="*">����</td>
            </tr>
            <?foreach($local_modues as $val):?>
            <tr>
                <td>
                    <?=$val['name']?>
                    [<a href="<?=cpurl($module,$act,'install',array('directory'=>$val['directory']))?>">��װ</a>]
                </td>
                <td><?=$val['directory']?></td>
                <td><?=$val['reliant']?></td>
                <td><?=$val['version']?></td>
                <td><?=$val['releasetime']?></td>
                <td><a href="<?=$val['siteurl']?>" target="_blank"><?=$val['author']?></a></td>
                <td><?=$val['introduce']?></td>
            </tr>
            <?endforeach;?>
        </table>
    </div>
    <?endif;?>
</form>
</div>