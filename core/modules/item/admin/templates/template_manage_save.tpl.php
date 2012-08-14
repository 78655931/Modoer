<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<script type="text/javascript" src="./static/javascript/My97DatePicker/WdatePicker.js"></script>
<div id="body">
<form method="post" name="myform" action="<?=cpurl($module,$act,'manage_save')?>">
    <div class="space">
        <div class="subtitle">增加模版</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td class="altbg1" width="45%">模版名称:</td>
                <td width="*">
                    <?if(!$template):?>
                    <select name="templateid">
                    <?php foreach($tpllist['item'] as $_val):?>
                        <option value="<?=$_val[templateid]?>"><?=$_val[name]?></option>
                    <?endforeach;?>
                    </select>
                    <?else:?>
                    <?=$template['name']?>
                    <?endif;?>
                </td>
            </tr>
            <tr>
                <td class="altbg1">到期时间:</td>
                <td><input type="textbox" class="txtbox3" name="datetime" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd'})" value="<?=$style['endtime']?date('Y-m-d',$style['endtime']):''?>"></td>
            </tr>
        </table>
    </div>
    <center>
        <input type="hidden" name="sid" value="<?=$sid?>">
        <input type="hidden" name="do" value="<?=$op?>">
        <input type="hidden" name="id" value="<?=$id?>">
        <button type="submit" class="btn" value="Y">提交</button>
        <button type="button" class="btn" onclick="document.location='<?=cpurl($module,$act,'manage',array('sid'=>$sid))?>';">返回</button>
    </center>
</form>
</div>