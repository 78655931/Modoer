<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<script type="text/javascript">
function showhide(id) {
    var obj = $('#'+id);
    if(obj.css('display') == 'none') {
        obj.show();
    } else {
        obj.hide();
    }
}

function fieldtype() {
    var obj = $('#t_field_type');
    if(obj.val()=='') return;
    $.post("<?=cpurl($module, 'field_type')?>", {fieldid:"<?=$_GET['fieldid']?>", fieldtype:obj.val(), in_ajax:1},
        function(data) {
            if(data.length == 0) {
                $('#t_fieldtype_setting_tr').hide();
            } else {
                $('#t_fieldtype_setting').html(data);
                $('#t_fieldtype_setting_tr').show();
            }
        }
    ); 
}

window.onload = function () {
    fieldtype();
}
</script>
<div id="body">
<form method="post" action="<?=cpurl($module, $act, $op)?>">
    <div class="space">
        <div class="subtitle"><?=$subtitle?></div>
        <table class="maintable" border="0" cellspacing="1" cellpadding="1">
            <tr>
                <td class="altbg1" width="45%"><strong>字段名称：</strong>此项在我们设计模板时将通过这个名称取得字段的值，由英文字母，数字和下划线组成，系统对自定义字段会默认增加前缀"c_“以防止与系统及数据库关键字重名。<span class="font_1">此项填写后将无法再次改动</span></td>
                <td width="*">
                    <?if($op=='add'):?>c_<?endif;?><input type="text" class="txtbox4" name="t_field[fieldname]" value="<?=$t_field['fieldname']?>"<?=$disabled?> />
                </td>
            </tr>
            <tr>
                <td class="altbg1"><strong>字段标题：</strong>字段的作用，例如：商铺名，地址等作用称谓。</td>
                <td><input type="text" class="txtbox3" name="t_field[title]" value="<?=$t_field['title']?>" /></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>字段单位：</strong>填写字段的单位，例如价格字段时可填写“万元”</td>
                <td><input type="text" class="txtbox3" name="t_field[unit]" value="<?=$t_field['unit']?>" /></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>字段显示模板：</strong>可设置字段在前台默认显示的效果，多可用于特殊文字效果，IM工具在线图标等，变量如下：<br />{value}：字段的值<br />{urlcode:value}：进行URL加密的字段值，用于URL链接</td>
                <td><textarea name="t_field[template]" rows="5" cols="50"><?=_T($t_field['template'])?></textarea></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>字段说明：</strong>此项在会员登记新点评对象时，用于解释当前字段的含义，一般出现在字段输入框的右侧</td>
                <td><textarea name="t_field[note]" rows="5" cols="50"><?=$t_field['note']?></textarea></td>
            </tr>
            <tr>
                <td class="altbg1">
                    <strong>字段类型：</strong>当前字段的类型，一个良好的字段，将是数据库稳定的前提<br />
                    <span class="font_1">此项填写后将无法再次改动</span>
                </td>
                <td>
                    <select name="t_field[type]" id="t_field_type" onchange="fieldtype();"<?=$disabled?>>
                        <option value=""<?if(empty($t_field))echo' selected="selected"'?>>==选择字段类型==</option>
                        <?foreach($F->fieldtypes as $key => $val) { $exp = explode('|',$val); if($exp[1]=='N' && !$t_field) continue; ?>
                        <option value="<?=$key?>"<?if($t_field['type']==$key)echo' selected="selected"'?>><?=$exp[0]?></option>
                        <?}?>
                    </select>
                </td>
            </tr>
            <tr id="t_fieldtype_setting_tr" style="display:none;">
                <td colspan="2"><div id="t_fieldtype_setting"></div></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>数据校验正则表达式：</strong>对提交的数据使用正则表达式进行校验。</td>
                <td><input type="text" class="txtbox2" name="t_field[regular]" value="<?=$t_field['regular']?>" /></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>数据校验失败提示信息：</strong>当校验的数据失败（不匹配）时，提示用户的信息。</td>
                <td><input type="text" class="txtbox" name="t_field[errmsg]" value="<?=$t_field['errmsg']?>" /></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>允许为空：</strong>允许会员不填写此项内容</td>
                <td><?=form_bool('t_field[allownull]', $t_field['allownull'])?></td>
            </tr>
            <!--
            <tr>
                <td class="altbg1"><strong>允许搜索：</strong>是否在高级搜索里作为一个搜索条件，数据量大，搜索自定义字段将消耗系统资源，尽量使用搜索类型为数字或小于50的字符串。</td>
                <td><?=form_bool('t_field[enablesearch]', $t_field['enablesearch'])?></td>
            </tr>
            -->
            <tr>
                <td class="altbg1"><strong>在列表页显示：</strong>在列表页显示该字段信息，请记录介绍在列表页显示过多数据和过大的字段数据（如：文本类型字段），过多过大字段会影响系统效率。</td>
                <td><?=form_bool('t_field[show_list]', $t_field['show_list'])?></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>在内容页表格显示：</strong>在主题内容页的信息表格中显示该字段信息，例如大型文字段，多行文本不适合显示在表格的字段，可以自行编辑模板，请不要将敏感字段信息显示。</td>
                <td><?=form_bool('t_field[show_detail]', $t_field['show_detail'])?></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>是否为后台字段：</strong>不显示在前台会员登记页面，仅供后台用户操作选择。</td>
                <td><?=form_bool('t_field[isadminfield]', $t_field['isadminfield'])?></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>字段排序：</strong>字段在模型所有字段中的排序位置。</td>
                <td><input type="text" class="txtbox4" name="t_field[listorder]" value="<?=$t_field['listorder']?$t_field['listorder']:0?>" /></td>
            </tr>
        </table>
        <center>
            <?if(!$isedit){?>
            <input type="hidden" name="modelid" value="<?=$_GET['modelid']?>" />
            <input type="hidden" name="t_field[tablename]" value="<?=$t_model['tablename']?>" />
            <?} else {?>
            <input type="hidden" name="isedit" value="yes" />
            <input type="hidden" name="modelid" value="<?=$t_field['id']?>" />
            <input type="hidden" name="fieldid" value="<?=$_GET['fieldid']?>" />
            <?}?>
            <button type="submit" name="dosubmit" value="yes" class="btn" /><?=lang('global_submit')?></button>&nbsp;
            <button type="button" class="btn" onclick="history.go(-1);" /><?=lang('global_return')?></button>
        </center>
    </div>
</form>
</div>