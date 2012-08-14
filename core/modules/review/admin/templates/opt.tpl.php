<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<form method="post" action="<?=cpurl($module,$act)?>">
    <?=form_hidden('gid',$_GET['gid'])?>
    <div class="space">
        <div class="subtitle">点评项管理</div>
        <ul class="cptab">
            <li><a href="<?=cpurl($module,'opt_group','edit',array('gid'=>$gid))?>" onfocus="this.blur()">编辑组信息</a></li>
            <li class="selected"><a href="<?=cpurl($module,'opt','',array('gid'=>$_GET['gid']))?>" onfocus="this.blur()">值管理</a></li>
        </ul>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0">
            <tr class="altbg1">
                <td width="60">排序</td>
                <td width="50">标识</td>
                <td width="210">名称</td>
                <td width="*">有效的</td>
            </tr>
            <?foreach($result as $val) :?>
            <tr>
                <td><input type="text" name="t_pot[<?=$val['id']?>][listorder]" value="<?=$val['listorder']?>" class="txtbox5" /></td>
                <td><?=$val['flag']?></td>
                <td><input type="text" name="t_pot[<?=$val['id']?>][name]" value="<?=$val['name']?>" class="txtbox3" /></td>
                <td><input type="checkbox" name="t_pot[<?=$val['id']?>][enable]" value="1"<?if($val['enable'])echo' checked="checked"';?> /></td>
            </tr>
            <?endforeach;?>
        </table>
        <center>
            <button type="submit" class="btn" name="dosubmit" value="yes" /><?=lang('global_submit')?></button>&nbsp;
            <button type="button" class="btn" onclick="location.href='<?=cpurl($module,'opt_group')?>'" /><?=lang('global_return')?></button>
        </center>
    </div>
</form>
</div>