<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<script type="text/javascript" src="./data/cachefiles/article_category.js?r=<?=$MOD[jscache_flag]?>"></script>
<script type="text/javascript" src="./static/javascript/article.js"></script>
<div id="body">
<form method="post" action="<?=cpurl($module,$act,'down_image_ing')?>">
    <div class="space">
        <div class="subtitle">������������Զ��ͼƬ</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" id="config1" mousemove='N'>
            <tr>
                <td class="altbg1" width="30%"><strong>���·���:</strong></td>
                <td width="*">
                    <select name="catid" id="pid">
                        <?=form_article_category(0);?>
                    </select>
                </td>
            </tr>
            <tr>
                <td class="altbg1"><strong>���·�����ʱ�䷶Χ:</strong></td>
                <td>
                    <select name="day">
                        <option value="1" selected="selected">1��</option>
                        <option value="3">3��</option>
                        <option value="7">��һ��</option>
                        <option value="30">��һ����</option>
                        <option value="90">��������</option>
                        <option value="365">��1��</option>
                    </select>
                </td>
            </tr>
        </table>
    </div>
    <center>
        <center><?=form_submit('dosubmit',lang('admincp_submit'),'yes','btn')?></center>
    </center>
</form>
</div>