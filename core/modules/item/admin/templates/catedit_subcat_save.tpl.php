<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<form method="post" name="myform" action="<?=cpurl($module,$act,'save')?>">
    <div class="space">
        <div class="subtitle">��������[<?=$detail['name']?>]</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td class="altbg1" valign="top"><strong>�������ƣ�</strong>���õ�ǰ���������</td>
                <td>
                    <?=form_input('name',$detail['name'],'txtbox')?>
                </td>
            </tr>
            <tr>
                <td class="altbg1" valign="top"><strong>���÷��ࣺ</strong>��ǰ̨�����Ա��ӱ����������</td>
                <td>
                    <?=form_bool('enabled',$detail['enabled'])?>
                </td>
            </tr>
            <?if($attcats = $_G['loader']->variable('att_cat','item',false)):?>
            <tr>
                <td class="altbg1" valign="top" width="45%"><strong>����������ɸѡ��</strong>�������б�ҳ��ǰ�ӷ���ʱ�ɽ���������ɸѡ</td>
                <td width="*">
					<?if($detail['level']<3):?>
					<div><input type="checkbox" name="set2subcat" id="set2subcat" /><label for="set2subcat"><span class="font_1">������������ɸѡѡ��Ӧ�õ��ӷ���</span></label></div>
					<?endif;?>
                    <select id="attcat" name="config[attcat][]" multiple="true">
                        <?foreach($attcats as $key => $val):?>
                        <option value="<?=$val['catid']?>"<<?if($detail['config'] && in_array($val['catid'],$detail['config']['attcat']))echo' selected="selected"';?>><?=$val['name']?><?if($val['des']):?>&nbsp;(<?=$val['des']?>)<?endif;?></option>
                        <?endforeach;?>
                    </select>
                    <script type="text/javascript">
                    $('#attcat').mchecklist({width:'95%',height:30,line_num:2});
                    </script>
                </td>
            </tr>
            <?endif;?>
        </table>
        <center>
            <input type="hidden" name="catid" value="<?=$_GET['catid']?>" />
			
            <button type="submit" class="btn" name="onsubmit" value="yes"> �ύ </button>
            <button type="button" class="btn" onclick="location.href='<?=cpurl($module,'category_edit','subcat',array('catid'=>$detail['pid']))?>'" /><?=lang('global_return')?></button>
        </center>
    </div>
</form>
</div>