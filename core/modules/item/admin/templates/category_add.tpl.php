<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<form method="post" action="<?=cpurl($module,$act)?>">
    <div class="space">
        <div class="subtitle">添加分类</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td width="45%" class="altbg1"><strong>分类名称：</strong></td>
                <td width="*"><input type="text" name="t_cat[name]" class="txtbox" value="" /></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>选择主题模型：</strong>指定一个已存在的模型，模型包含数据字段和点评项。<br /><span class="font_1">本设置选择后将无法修改。</span></td>
                <td><select name="t_cat[modelid]">
                    <option value="">==模型列表==</option>
                    <?foreach($models as $val){?>
                    <option value="<?=$val['modelid']?>"><?=$val['name']?></option>
                    <?}?>
                </select>&nbsp;<a href="<?=cpurl($module,'model_add')?>">新建主题模型</a><br /><span id="des"></span></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>选择点评项组：</strong>指定一个点评项，用于会员对主题的打分依据。</td>
                <td><select name="t_cat[review_opt_gid]">
                    <option value="">==点评项组列表==</option>
					<?=form_review_opt_group()?>
                </select>&nbsp;<a href="<?=cpurl('review','opt_group')?>">新建点评项组</a><br /><span id="des"></span></td>
            </tr>
        </table>
        <center>
            <button type="submit" name="dosubmit" value="yes" class="btn" /> 提 交 </button>&nbsp;
            <button type="button" class="btn" onclick="history.go(-1);" /> 返 回 </button>
        </center>
    </div>
</form>
</div>