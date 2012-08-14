<?php 
(!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); 
if($_GET['cy_callid']) {
    $copy=1;
    $datacall = $D->read($_GET['cy_callid']);
    $datacall['expression']['params'] = '';
    if(is_array($datacall['expression'])) {
        foreach($datacall['expression'] as $key => $val) {
            if(empty($val)) continue;
            if(in_array($key, array('cachetime','row','order'))) continue;
            $datacall['expression']['params'] .= $split.$key.'='.$val;
            $split = "\r\n";
        }
    }
    $datacall['name'] .= '_copy';
}
?>
<div id="body">
<form method="post" name="myform" action="<?=cpurl($module,$act,$op)?>">
    <input type="hidden" name="datacall[calltype]" value="sql" />
    <div class="space">
        <div class="subtitle">增加SQL调用</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td class="altbg1" width="45%"><strong>调用名称:</strong>给本次调用赋予一个名称，调用时可以在模板标签里直接使用调用名称，如果使用名称，请不要再次修改名称，否则会找不到调用信息。</td>
                <td width="*">
                    <input type="text" name="datacall[name]" class="txtbox2" value="<?=$datacall['name']?>" />&nbsp;
                    <br /><span class="font_2">使用名称调用，请不要修改名称</span>
                </td>
            </tr>
            <tr>
                <td class="altbg1"><strong>所属模块:</strong>SQL调用的应用模块，当此模块被禁用或删除时将不激活本次调用。</td>
                <td>
                    <select name="datacall[module]">
                        <option value="">==选择模块==</option>
                        <?foreach($_G['modules'] as $key => $val) {?>
                            <option value="<?=$key?>"<?=$datacall['module']==$key?' selected':''?>><?=$val['name']?></option>
                        <?}?>
                    </select>
                </td>
            </tr>
            <tr>
                <td class="altbg1"><strong>调用返回的数据标签名:</strong>调用的数据查询后将存在的标签，这个标签的用于显示数据的内容。例如：标签名为“hot”，则模板标签使用为：$_QUERY['hot']</td>
                <td><input type="text" name="datacall[var]" class="txtbox2" value="<?=$datacall['var']?>" /></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>缓存时间:</strong>调用的数据的缓存时间，缓存时间到期后，将再次查询数据库；过小的值将会对数据库造成影响，0为不使用缓存，但不建议您是设置为0，这将严重影响服务器效率；请根据数据的类型，设定一个适当的值。</td>
                <td><input type="text" name="datacall[cachetime]" class="txtbox3" value="<?=$datacall['cachetime']?>" />&nbsp;秒</td>
            </tr>
            <tr>
                <td class="altbg1"><strong>SQL表达式_FROM段:</strong>此处填写SQL表达式FROM段表达式，表示查询使用的数据表，数据库前缀可使用“{dbpre}”来标识，例如members表，则可以是{dbpre}members，同时也可以直接写出真是的前缀，例如：modoer_members</td>
                <td><textarea name="datacall[expression][from]" rows="4" cols="60"><?=$datacall['expression']['from']?></textarea></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>SQL表达式_SELECT段:</strong>此处填写SQL表达式SELECT段表达式，如果使用了数个数据表和别名，则需要别名前缀，例如：m.uid,m.username</td>
                <td><textarea name="datacall[expression][select]" rows="4" cols="60"><?=$datacall['expression']['select']?></textarea></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>SQL表达式_WHERE段:</strong>此处填写SQL表达式WHERE段表达式，数据表数据的筛选表达式</td>
                <td><textarea name="datacall[expression][where]" rows="4" cols="60"><?=$datacall['expression']['where']?></textarea></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>SQL表达式_其他段:</strong>此处填写SQL表达式WHERE段以后的表达式，可以写 GROUP BY 等，将衔接在WHERE段表达式后面，<u>此处不要填写ORDER BY，LIMIT表达式</u></td>
                <td><textarea name="datacall[expression][other]" rows="4" cols="60"><?=$datacall['expression']['other']?></textarea></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>SQL表达式_ORDER BY段:</strong>数据查询后，以某个字段作为排序的依据。例如：reviews DESC</td>
                <td><input type="text" name="datacall[expression][orderby]" class="txtbox3" value="<?=$datacall['expression']['orderby']?>" /></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>SQL表达式_LIMIT段:</strong>从数据库读取的数据量，例如：0,10</td>
                <td><input type="text" name="datacall[expression][limit]" class="txtbox3" value="<?=$datacall['expression']['limit']?>" /></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>模板文件:</strong>用于显示数据的模板，本地(系统)调用可直接在调用处设计模板，如果以JS方式调用则必须填写。数据调用模板必须存在 ./templates/datacall 目录下。</td>
                <td>
                    <select name="datacall[tplname]" id="tplname">
                        <?=form_datacall_template_files($_G['cfg']['templateid'],$datacall['tplname'])?>
                    </select>
                    <input type="button" class="btn2" style="margin-left:5px;" value="管理模板" onclick="window.open('<?=cpurl('modoer','template','manage',array('type'=>'datacall','templateid'=>$_G['cfg']['templateid']))?>')" />
                    <input type="button" class="btn2" value="更新列表" onclick="update_tpllist();" />
                </td>
            </tr>
            <tr>
                <td class="altbg1"><strong>空数据模板文件:</strong>当本次调用查询的数据为空时，调用的模板文件。数据调用模板必须存放在 ./templates/datacall 目录下。</td>
                <td>
                    <select name="datacall[empty_tplname]" id="empty_tplname">
                        <?=form_datacall_template_files($_G['cfg']['templateid'],$datacall['empty_tplname'])?>
                    </select>
                    &nbsp;操作同上
                </td>
            </tr>
        </table>
        <center>
            <?if($op=='editsql') {?>
            <input type="hidden" name="callid" value="<?=$_GET['callid']?>" />
            <button type="submit" name="dosubmit" value="yes" class="btn" />更新调用</button>&nbsp;
            <?} elseif($op=='addsql') {?>
            <button type="submit" name="dosubmit" value="yes" class="btn" />添加调用</button>&nbsp;
            <?}?>
            <input type="hidden" name="forward" value="<?=get_forward()?>" />
            <button type="reset" class="btn" />重置内容</button>&nbsp;
            <button type="button" onclick="history.go(-1);" class="btn" />返回</button>
        </center>
    </div>
</form>
</div>
<script type="text/javascript">
loadscript('mdialog');
function update_tpllist() {
	$.post("<?=cpurl($module,'datacall','tplist',array('in_ajax'=>1))?>", 
    { }, 
	function(data) {
		if(data) {
			var t1 = $('#tplname').val();
            var t2 = $('#empty_tplname').val();
            $('#tplname').empty().append(data).val(t1);
            $('#empty_tplname').empty().append(data).val(t2);
		} else {
			alert('没有可用模板！');
		}
	});
}
</script>