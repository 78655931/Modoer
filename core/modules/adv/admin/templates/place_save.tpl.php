<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<?=form_begin(cpurl($module,$act,'save'))?>
    <div class="space">
        <div class="subtitle">���/�༭���λ</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" id="config1">
            <tr>
                <td class="altbg1" width="45%"><strong>����:<span class="font_1">*</span></strong>��д���λΨһ���ƣ����˹���������༭���������ƣ���ͬʱҲ������ģ����ĵ��ô��� name ����������ǰ̨���޷���õ�ǰ���λ��</td>
                <td width="55%"><input type="text" name="name" class="txtbox" value="<?=$detail['name']?>" /></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>����:</strong>�����ں�̨��ʾ˵����</td>
                <td><input type="text" name="des" class="txtbox" value="<?=$detail['des']?>" /></td>
            </tr>
            <tr>
                <td class="altbg1" valign="top"><strong>ģ��:<span class="font_1">*</span></strong>��ƹ����ʾ��ģ�壬ģ���˵�����뿴ģ���ֲ᣻<a href="http://bbs.modoer.com/thread-21245-1-1.html" target="_blank">�̳���������</a>��</td>
                <td>
					<textarea name="template" style="height:120px;width:400px;font-family:'Courier New';"><?if($detail['template']):?><?=$detail['template']?><?else:?>
&lt;div&gt;
    {get:adv ad=getlist(apid/_APID_/cachetime/1000)}
    &lt;div&gt;$ad[code]&lt;/div&gt;
    {/get}
&lt;/div&gt;<?endif;?></textarea>
				</td>
            </tr>
            <tr>
                <td class="altbg1"><strong>״̬:<span class="font_1">*</span></strong>ѡ��ͣ�á�������ǰ̨��ʾ���λ������ݡ�</td>
                <td><?=form_radio('enabled',array('Y'=>'����','N'=>'ͣ��'),$detail['enabled']?$detail['enabled']:'Y')?></td>
            </tr>
        </table>
    </div>
    <center>
        <input type="hidden" name="do" value="<?=$op?>" />
        <?if($op=='edit'):?>
        <input type="hidden" name="apid" value="<?=$detail['apid']?>" />
        <?endif;?>
        <input type="hidden" name="forward" value="<?=get_forward()?>" />
        <?=form_submit('dosubmit',lang('admincp_submit'),'yes','btn')?>&nbsp;
        <input type="button" class="btn" value="<?=lang('admincp_return')?>" onclick="document.location='<?=cpurl($module,$act)?>';" />
    </center>
<?=form_end()?>
</div>