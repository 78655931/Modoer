<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); 
$_G['loader']->helper('form', 'member');
?>
<script type="text/javascript" src="./static/javascript/My97DatePicker/WdatePicker.js"></script>
<script type="text/javascript">
loadscript('mdialog');
function access_groups() {
    var value=0;
    $("[name=access]").each(function(i){ 
        if($(this).attr('checked')) {
            value = $(this).val();
        }
    }); 
    if(value=='2') {
        $('#access_groups').show();
    } else {
        $('#access_groups').hide();
    }
}
function task_save_succeed (data) {
    document.location = "<?=cpurl($module,$act)?>";
}
function load_typecfg(obj) {
    var flag = $(obj).val();
    if(!flag) return;
    document.location="<?=cpurl($module,$act,'add')?>&flag="+flag;
}
</script>
<div id="body">
<form method="post" action="<?=cpurl($module,$act,'save',array('in_ajax'=>1))?>&" name="postform" id="postform">
    <input type="hidden" name="do" value="<?=$op?>" />
    <?if($op=='edit'):?><input type="hidden" name="taskid" value="<?=$detail['taskid']?>" /><?endif;?>
    <div class="space">
        <div class="subtitle">创建任务</div>
        <ul class="cptab">
            <li><a href="<?=cpurl($module,$act)?>" onfocus="this.blur()">任务列表</a></li>
            <li><a href="<?=cpurl($module,$act,'type')?>" onfocus="this.blur()">任务类型</a></li>
            <li  class="selected"><a href="<?=cpurl($module,$act,'add')?>" onfocus="this.blur()">创建任务</a></li>
        </ul>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td class="altbg1"><strong>任务类型:</strong>选择一种网站任务类型，每个类型都有不同的完成方式和条件。</td>
                <td><select name="taskflag" id="taskflag" style="width:200px;"<?if($op=='edit')echo'disabled'?> onchange="load_typecfg(this);">
                    <?=form_member_tasktype($taskflag)?>
                    </select>
                </td>
            </tr>
            <tr>
                <td width="45%" class="altbg1"><strong>任务名称:</strong>显示在前台任务列表中的名称。</td>
                <td width="*"><input type="text" name="title" value="<?=$detail['title']?>" class="txtbox2"></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>任务描述:</strong><span class="font_1">本文本框支持HTML代码显示</span><br />填写任务的详细表述，以便用户能详细的理解任务的整个操作流程；</td>
                <td><?=form_textarea('des',_T($detail['des']))?></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>任务开放时间:</strong><span class="font_1">留空表示任务生效后马上开始</span><br />任务的首次开始时间，用户只有在当前设置的任务时间后才能申请本任务。</td>
                <td><?=form_datetime('starttime',$detail['starttime'],'Y-m-d H:i:s','txtbox3','onfocus="WdatePicker({dateFmt:\'yyyy-MM-dd HH:mm\'})"')?></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>任务结束时间:</strong><span class="font_1">留空表示当前任务永久有效</span><br />任务的结束时间，如有用户在当前任务结束时间后尚未完成的，则自动关闭任务。</td>
                <td><?=form_datetime('endtime',$detail['endtime'],'Y-m-d H:i:s','txtbox3','onfocus="WdatePicker({dateFmt:\'yyyy-MM-dd HH:mm\'})"')?></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>任务间隔周期和周期单位:</strong>周期留空表示一次性任务，否则为周期性任务<br />小时：表示指定小时后可再次申请，间隔周期填写间隔的小时数<br />天：表示指定天后可再次申请，间隔周期填写间隔的天数<br />周：表示在每周指定时间(周一到周日)后才可再次申请，间隔周期填写数字1(周一)到7(周日)<br />月：表示在每月指定日期后才可再次申请，间隔周期填写数字1到29、30、31</td>
                <td><input type="text" name="period" value="<?=$detail['period']?$detail['period']:''?>" class="txtbox4">
                    <?=form_select('period_unit', array('小时','天','周','月'), $detail['period_unit'])?>
                </td>
            </tr>
            <tr>
                <td class="altbg1"><strong>任务奖励积分类型和数量:</strong>会员完成任务后，奖励给会员的积分类型。</td>
                <td><input type="text" name="point" value="<?=$detail['point']?>" class="txtbox4">
                    <select name="pointtype">
                    <?=form_member_pointgroup($detail['pointtype'])?>
                    </select>
                </td>
            </tr>
            <tr><td colspan="2" class="altbg2"><center><strong>申请任务条件</strong></center></td></tr>
            <tr>
                <td class="altbg1"><strong>用户组限制:</strong>限制会员组的任务的申请权限；<br />注册会员：表示全部已经注册可访问的用户；普通会员：除去特殊用户组以外的用户组；指定用户组：指定任意的几个用户组</td>
                <td><?=form_radio('access', array('注册用户','普通用户(不包含系统用户组)','指定用户组'),$detail['access'],'onclick="access_groups(this.value);"')?></td>
            </tr>
            <tr id="access_groups">
                <td class="altbg1"><strong>选择指定会员组:</strong>可多选，“Ctrl+鼠标左键”进行选择</td>
                <td>
                    <?if($detail['access_groupids']) $detail['access_groupids']=explode(',',$detail['access_groupids']);?>
                    <select name="access_groupids[]" size="6" style="width:200px;" multiple>
                        <?=form_member_usergroup($detail['access_groupids'],null,true)?>
                    </select>
                    <script type="text/javascript">access_groups();</script>
                </td>
            </tr>
            <tr>
                <td class="altbg1"><strong>注册后自动申请任务:</strong>设置在游客注册成为会员时，自动申请本任务。<br />注：如果用户组限制申请时，本功能将失效。</td>
                <td><?=form_bool('reg_automatic', (bool)$detail['reg_automatic'])?></td>
            </tr>
            <?if($form_condition):?>
            <tr id="condition"><td colspan="2" class="altbg2"><center><strong>完成任务条件</strong></center></td></tr>
            <?=$form_condition?>
            <?endif;?>
        </table>
    </div>
    <center>
        <button type="button" class="btn" onclick="ajaxPost('postform', '', 'task_save_succeed');">提交</button>
        <button type="button" class="btn" onclick="document.location=document.referrer;">返回</button>
    </center>
</form>
</div>