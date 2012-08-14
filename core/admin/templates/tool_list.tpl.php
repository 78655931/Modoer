<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<script type="text/javascript">
$(function(){
    $(".maintable tr").each(function(i) { this.style.backgroundColor = ['#fff','#f2fbff'][i%2]} );
})
</script>
<div id="body">
<div class="space">
    <div class="subtitle">系统工具箱</div>
    <table class="maintable" border="0" cellspacing="0" cellpadding="0" trmouse="Y">
        <?foreach($tools as $key => $tool):?>
        <tr>
            <td width="*">
                <div class="font_3"><?=$tool->get_name()?></div>
                <div class="font_2"><?=$tool->get_descrption()?></div>
            </td>
            <td width="160">
                <center>
                    <button type="button" class="btn" onclick="show_form('<?=$key?>','<?=cpurl($module,$act,'run',array('tool'=>$key))?>');">执行</button>
                </center>
            </td>
        </tr>
        <?endforeach;?>
    </table>
</div>
<script type="text/javascript">
function show_form(toolname,url) {
    $.post("<?=cpurl($module,$act,'create_form')?>", 
    { tool:toolname, in_ajax:1 },
    function(result) {
        if(result == null) {
            alert('信息读取失败，可能网络忙碌，请稍后尝试。');
        } else if(result == 'RUN') {
            jslocation(url);
        } else if (is_message(result)) {
            myAlert(result);
        } else {
            var content = '<form method="get" action="<?=SELF?>" name="myform" style="margin:10px 0;">';
            content += '<input type="hidden" name="module" value="<?=$module?>" />';
            content += '<input type="hidden" name="act" value="<?=$act?>" />';
            content += '<input type="hidden" name="op" value="run" />';
            content += '<input type="hidden" name="tool" value="'+toolname+'" />';
            content += '<table border="0" cellspacing="0" cellpadding="0" class="toolsetting-table">';
            content += result;
            content += "</table>";
            content += '<center><button type="submit" class="btn">开始执行</button>&nbsp;<button type="button" class="btn" onclick="dlgClose();">关闭</button></center>';
            content += "</form>";
            dlgOpen('系统工具箱', content, 600,0);
        }
    });
}
</script>
<style type="text/css">
.toolsetting-table { width:100%; margin-bottom:10px; background:#FFF; border:1px solid #ddd; border-bottom-width:0px; }
    .toolsetting-table td { padding:5px 10px;border-bottom:1px solid #ddd; }
        .toolsetting-table td h3 { margin:5px 0px; padding:0; font-size:12px; }
        .toolsetting-table td div { margin:0; padding:4px 0; }
        .toolsetting-table td div span { margin-left:5px; }
        .toolsetting-table td div p { color:#808080;  margin:0; padding:8px 0 0;}
</style>