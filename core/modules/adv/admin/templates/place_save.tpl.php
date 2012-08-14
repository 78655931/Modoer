<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<?=form_begin(cpurl($module,$act,'save'))?>
    <div class="space">
        <div class="subtitle">添加/编辑广告位</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" id="config1">
            <tr>
                <td class="altbg1" width="45%"><strong>名称:<span class="font_1">*</span></strong>填写广告位唯一名称，不宜过长，如果编辑更改了名称，请同时也更新在模板里的调用代码 name 参数，否则前台将无法获得当前广告位。</td>
                <td width="55%"><input type="text" name="name" class="txtbox" value="<?=$detail['name']?>" /></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>描述:</strong>仅用于后台提示说明。</td>
                <td><input type="text" name="des" class="txtbox" value="<?=$detail['des']?>" /></td>
            </tr>
            <tr>
                <td class="altbg1" valign="top"><strong>模板:<span class="font_1">*</span></strong>设计广告显示的模板，模板的说明，请看模板手册；<a href="http://bbs.modoer.com/thread-21245-1-1.html" target="_blank">教程请点击这里</a>。</td>
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
                <td class="altbg1"><strong>状态:<span class="font_1">*</span></strong>选择“停用”将不再前台显示广告位广告数据。</td>
                <td><?=form_radio('enabled',array('Y'=>'启用','N'=>'停用'),$detail['enabled']?$detail['enabled']:'Y')?></td>
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