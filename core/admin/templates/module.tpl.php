<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<form method="post" name="myform" action="<?=cpurl($module,$act)?>">
    <input type="hidden" name="op" value="list_update" />
    <input type="hidden" name="dosubmit" value="yes" />
    <div class="space">
        <div class="subtitle">已安装模块列表</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" trmouse="Y">
            <tr class="altbg1">
                <td width="25">选</td>
                <td width="70">排序</td>
                <td width="30">核心</td>
                <td width="*">名称</td>
                <td width="90">标识</td>
                <td width="100">目录</td>
                <td width="100">依存于</td>
                <td width="100">版本</td>
                <td width="110">发布时间</td>
                <td width="120">作者</td>
                <td width="120">操作</td>
            </tr>
            <?while($val=$list->fetch_array()) {?>
            <tr>
                <td><input type="checkbox" name="moduleflags[]" value="<?=$val['flag']?>" /></td>
                <td><input type="text" name="modules[<?=$val['moduleid']?>][listorder]" value="<?=$val['listorder']?>" class="txtbox5" /></td>
                <td><?=$val['iscore']?'√':'－'?></td>
                <td><?=form_input("modules[{$val['moduleid']}][name]",$val['name'],'txtbox4')?></td>
                <td><?=$val['flag']?></td>
                <td><?=$val['directory']?></td>
                <td><?=$val['reliant']?></td>
                <td>
                    <a href="<?=cpurl($module,$act,'versioncheck',array('moduleid'=>$val['moduleid']))?>" title="新版本检测"><?=$val['version']?></a>
                    <?if($v=$MM->update_check($val)):?>
                    <a href="<?=cpurl($module,$act,'versionupdate',array('moduleid'=>$val['moduleid']))?>" title="点击升级到<?=$v?>"><span class="font_1">升级!</span></a>
                    <?endif;?>
                </td>
                <td><?=$val['releasetime']?></td>
                <td><a href="<?=$val['siteurl']?>" target="_blank"><?=$val['author']?></a></td>
                <td>
                    <a href="<?=cpurl($val['flag'],'config')?>">配置</a>
                    <?if(!$val['iscore']) {?>
                    <?if($val['disable']) {?>
                    <a href="<?=cpurl($module,$act,'enable',array('moduleid'=>$val['moduleid']))?>" title="启用本模块">启用</a>
                    <?} else {?>
                    <a href="<?=cpurl($module,$act,'disable',array('moduleid'=>$val['moduleid']))?>" title="禁用本模块">禁用</a>
                    <?}?>
                    <a href="<?=cpurl($module,$act,'unstall',array('moduleid'=>$val['moduleid']))?>" onclick="return window.confirm('本操作不可逆，您确认删除本模块（删除模块文件夹和数据表）吗？');">删除</a>
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
                    <button type="button" class="btn2" onclick="checkbox_checked('moduleflags[]');">全选</button>
                    <button type="button" class="btn2" onclick="easy_submit('myform', 'cache', 'moduleflags[]');">更新所选模块配置缓存</button>
                    <button type="button" class="btn2" onclick="easy_submit('myform', 'list_update', null);">提交更新</button>
                </td>
            </tr>
        </table>
    </div>
    <?if($local_modues):?>
    <div class="space">
        <div class="subtitle">未安装模块列表</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" trmouse="Y">
            <tr class="altbg1">
                <td width="150">名称</td>
                <td width="80">目录</td>
                <td width="100">依存于</td>
                <td width="80">模块版本</td>
                <td width="110">发布时间</td>
                <td width="120">作者</td>
                <td width="*">介绍</td>
            </tr>
            <?foreach($local_modues as $val):?>
            <tr>
                <td>
                    <?=$val['name']?>
                    [<a href="<?=cpurl($module,$act,'install',array('directory'=>$val['directory']))?>">安装</a>]
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