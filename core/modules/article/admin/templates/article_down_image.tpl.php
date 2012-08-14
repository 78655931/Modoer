<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<script type="text/javascript" src="./data/cachefiles/article_category.js?r=<?=$MOD[jscache_flag]?>"></script>
<script type="text/javascript" src="./static/javascript/article.js"></script>
<div id="body">
<form method="post" action="<?=cpurl($module,$act,'down_image_ing')?>">
    <div class="space">
        <div class="subtitle">批量下载文章远程图片</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" id="config1" mousemove='N'>
            <tr>
                <td class="altbg1" width="30%"><strong>文章分类:</strong></td>
                <td width="*">
                    <select name="catid" id="pid">
                        <?=form_article_category(0);?>
                    </select>
                </td>
            </tr>
            <tr>
                <td class="altbg1"><strong>文章发布的时间范围:</strong></td>
                <td>
                    <select name="day">
                        <option value="1" selected="selected">1天</option>
                        <option value="3">3天</option>
                        <option value="7">近一周</option>
                        <option value="30">近一个月</option>
                        <option value="90">近三个月</option>
                        <option value="365">近1年</option>
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