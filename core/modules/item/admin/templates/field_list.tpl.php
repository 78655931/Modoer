<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<form method="post" action="<?=cpurl($module,$act,'',array('modelid'=>$_GET['modelid']))?>">
    <div class="space">
        <div class="subtitle">字段管理</div>
        <ul class="cptab">
            <li><a href="<?=cpurl($module,'model_list')?>" onfocus="this.blur()">模型管理</a></li>
            <li><a href="<?=cpurl($module,'model_edit','',array('modelid'=>$_GET['modelid']))?>" onfocus="this.blur()">编辑模型</a></li>
            <li class="selected"><a href="#" onfocus="this.blur()">自定义字段管理</a></li>
            <li><a id="add_types" rel="add_types_box" href="#" onfocus="this.blur()"><span class="font_1">新建字段</span></a></li>
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
                <td width="60">排序</td>
                <td width="200">字段标题</td>
                <td width="100">字段名</td>
                <td width="140">所属表</td>
                <td width="60">字段类型</td>
                <td width="40"><center>核心</center></td>
                <td width="40"><center>允许空</center></td>
                <td width="40"><center>列表页</center></td>
                <td width="40"><center>内容页</center></td>
                <td width="40"><center>侧边栏</center></td>
                <td width="120">操作</td>
            </tr>
            <?if($result) { foreach($result as $val) {?>
            <tr>
                <td><?=form_input("fields[$val[fieldid]][listorder]",$val['listorder'],'txtbox5')?></td>
                <td><?=form_input("fields[$val[fieldid]][title]",$val['title'],'txtbox3')?></td>
                <td><?=$val['fieldname']?></td>
                <td>[dbpre]<?=$val['tablename']?></td>
                <td><?=substr($F->fieldtypes[$val['type']],0,-2)?></td>
                <td style="text-align:center;"><?=$val['iscore']?'√':'×'?></td>
                <td style="text-align:center;"><?=form_bool_check("fields[$val[fieldid]][allownull]", $val['allownull'])?></td>
                <td style="text-align:center;"><?=form_bool_check("fields[$val[fieldid]][show_list]", $val['show_list'])?></td>
                <td style="text-align:center;"><?=form_bool_check("fields[$val[fieldid]][show_detail]", $val['show_detail'])?></td>
                <td style="text-align:center;"><?=form_bool_check("fields[$val[fieldid]][show_side]", $val['show_side'])?></td>
                <td>
                    <a href="<?=cpurl($module,'field_edit','edit',array('fieldid'=>$val['fieldid']))?>">编辑</a>
                    <?if(!$val['iscore']) { $disable = $val['disable'] ? 'enable' : 'disable'; ?>
                    <a href="<?=cpurl($module,'field_edit',$disable,array('modelid'=>$_GET['modelid'],'fieldid'=>$val['fieldid']))?>"><?=$val['disable']?'启用':'禁用'?></a>
                    <a href="<?=cpurl($module,'field_edit','delete',array('modelid'=>$_GET['modelid'],'fieldid'=>$val['fieldid']))?>" onclick="return confirm('您确定要进行删除操作吗？');">删除</a>
                    <?}?>
                </td>
            </tr>
            <?}?>
            <?} else {?>
            <tr><td colspan="9">暂无信息。</td></tr>
            <?}?>
        </table>
        <center>
            <?if($result) {?>
            <button type="submit" class="btn" name="dosubmit" value="yes">提交更新</button>&nbsp;
            <?}?>
            <button type="button" class="btn" onclick="location.href='<?=cpurl($module,'model_list')?>'" /><?=lang('global_return')?></button>
        </center>
    </div>
</form>
</div>