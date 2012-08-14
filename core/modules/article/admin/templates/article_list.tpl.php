<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<script type="text/javascript" src="./data/cachefiles/article_category.js?r=<?=$MOD[jscache_flag]?>"></script>
<script type="text/javascript" src="./static/javascript/article.js"></script>
<script type="text/javascript">
window.onload = function() {
    <?if(!$_GET['catid']):?>article_select_category(document.getElementById('pid'),'catid',true);<?endif;?>
}
</script>
<div id="body">
<form method="get" action="<?=SELF?>">
    <input type="hidden" name="module" value="<?=$module?>" />
    <input type="hidden" name="act" value="<?=$act?>" />
    <input type="hidden" name="op" value="<?=$op?>" />
    <div class="space">
        <div class="subtitle">����ɸѡ</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td width="100" class="altbg1">��������</td>
                <td width="350">
                    <select name="pid" id="pid" onchange="article_select_category(this,'catid',true);">
                    <option value="">==ȫ��==</option>
                    <?=form_article_category(0,$_GET['pid']);?>
                    </select>&nbsp;
                    <select name="catid" id="catid">
                    <option value="">==ȫ��==</option>
                    <?=$_GET['catid']?form_article_category($_GET['catid']):''?>
                    </select>
                </td>
                <td width="100" class="altbg1">��������</td>
                <td width="*">
					<?if($admin->is_founder):?>
                    <select name="city_id">
                        <option value="">����</option>
                        <?=form_city($_GET['city_id'], TRUE)?>
                    </select>
					<?else:?>
					<?=$_CITY['name']?>
					<?endif;?>
                </td>
            </tr>
            <tr>
                <td class="altbg1">���±���</td>
                <td><input type="text" name="subject" class="txtbox2" value="<?=$_GET['subject']?>" /></td>
                <td class="altbg1">����SID</td>
                <td><input type="text" name="sid" class="txtbox3" value="<?=$_GET['sid']?>" /></td>
            </tr>
            <tr>
                <td class="altbg1">����</td>
                <td><input type="text" name="author" class="txtbox3" value="<?=$_GET['author']?>" /></td>
                <td class="altbg1">att(�Զ�������)</td>
                <td colspan="3">
                    <input type="text" name="att" id="att" class="txtbox5" value="<?=$_GET['att']?>" />
                    <select id="att_select" onchange="$('#att').val($('#att_select').val());">
                        <option value="">=ȫ��=</option>
                        <option value="0">att=0,û���Զ�������</option>
                        <?=form_article_att($_GET['att'])?>
                    </select>
                </td>
            </tr>
            <tr>
                <td class="altbg1">����ʱ��</td>
                <td colspan="3"><input type="text" name="starttime" class="txtbox3" value="<?=$_GET['starttime']?>" />&nbsp;~&nbsp;<input type="text" name="endtime" class="txtbox3" value="<?=$_GET['endtime']?>" />&nbsp;(YYYY-MM-DD)</td>
            </tr>
            <tr>
                <td class="altbg1">�������</td>
                <td colspan="3">
                <select name="orderby">
                    <option value="listorder"<?=$_GET['orderby']=='listorder'?' selected="selected"':''?>>listorder����</option>
                    <option value="articleid"<?=$_GET['orderby']=='articleid'?' selected="selected"':''?>>ID����</option>
                    <option value="dateline"<?=$_GET['orderby']=='dateline'?' selected="selected"':''?>>����ʱ��</option>
                    <option value="comments"<?=$_GET['orderby']=='comments'?' selected="selected"':''?>>��������</option>
                    <option value="digg"<?=$_GET['orderby']=='digg'?' selected="selected"':''?>>��һ������</option>
                    <option value="pageview"<?=$_GET['orderby']=='pageview'?' selected="selected"':''?>>�����</option>
                </select>&nbsp;
                <select name="ordersc">
                    <option value="DESC"<?=$_GET['ordersc']=='DESC'?' selected="selected"':''?>>�ݼ�</option>
                    <option value="ASC"<?=$_GET['ordersc']=='ASC'?' selected="selected"':''?>>����</option>
                </select>&nbsp;
                <select name="offset">
                    <option value="20"<?=$_GET['offset']=='20'?' selected="selected"':''?>>ÿҳ��ʾ20��</option>
                    <option value="50"<?=$_GET['offset']=='50'?' selected="selected"':''?>>ÿҳ��ʾ50��</option>
                    <option value="100"<?=$_GET['offset']=='100'?' selected="selected"':''?>>ÿҳ��ʾ100��</option>
                </select>&nbsp;
                <button type="submit" value="yes" name="dosubmit" class="btn2">ɸѡ</button>
                <button type="button" class="btn2" onclick="location.href='<?=cpurl($module,'article','add')?>';">��������</button>
                </td>
            </tr>
        </table>
    </div>
</form>
<form method="post" name="myform" action="<?=cpurl($module,$act)?>&">
    <div class="space">
        <div class="subtitle">���¹���</div>
        <?if($edit_links):?>
        <ul class="cptab">
            <?foreach($edit_links as $val):?>
            <li<?if($val['flag']=='article:list')echo' class="selected"';?>><a href="<?=$val['url']?>" onfocus="this.blur()"><?=$val['title']?></a></li>
            <?endforeach;?>
        </ul>
        <?endif;?>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" id="config1" trmouse="Y">
			<tr class="altbg1">
				<td width="25">ѡ</td>
                <td width="60">����</td>
                <td width="*">����/����</td>
                <td width="100">����</td>
                <td width="100">����</td>
				<td width="50" align="center">���</td>
                <td width="50" align="center">����</td>
                <td width="40" align="center">att</td>
                <td width="40" align="center">digg</td>
				<td width="110">����ʱ��</td>
                <td width="70">״̬</td>
                <td width="60">����</td>
			</tr>
            <?if($total):?>
            <?while($val = $list->fetch_array()):?>
            <tr>
                <td><input type="checkbox" name="articleids[]" value="<?=$val['articleid']?>" /></td>
                <td><input type="text" class="txtbox5" name="articles[<?=$val['articleid']?>][listorder]" value="<?=$val['listorder']?>" /></td>
                <td>
                    <div><a href="<?=url("article/detail/id/$val[articleid]")?>" target="_blank"><?=$val['subject']?></a></div>
                    <div style="color:#808080;">
                        [<?=misc_article::category_path($val['catid'])?>]
                        <?if($val['thumb']):?>[<span onmouseover="tip_start(this,1);" src="<?=$val['thumb']?>">����</span>]<?endif;?>
                    </div>
                </td>
                <td><?=template_print('modoer','area',array('aid'=>$val['city_id']))?></td>
                <td>
                    <?if($val['uid']):?>
                        <a href="<?=url("space/index/uid/$val[uid]");?>" target="_blank"><?=$val['author']?></a>
                    <?else:?>
                        <span class="font_2"><?=$val['author']?></span>
                    <?endif;?>
                </td>
                <td align="center"><?=$val['pageview']?></td>
                <td align="center"><?=$val['comments']?></td>
                <td align="center"><?=$val['att']?></td>
                <td align="center"><?=$val['digg']?></td>
                <td><?=date('Y-m-d H:i',$val['dateline'])?></td>
                <td><?=lang('global_status_'.$val['status'])?></td>
                <td><a href="<?=cpurl($module,$act,'edit',array('articleid'=>$val['articleid']))?>">�༭</a></td>
            </tr>
            <?endwhile;?>
			<tr class="altbg1">
				<td colspan="20" class="altbg1">
					<button type="button" onclick="checkbox_checked('articleids[]');" class="btn2">ȫѡ</button>&nbsp;
                    ��������att���ԣ�<select name="att_select" id="att_select" onchange="$('#att').val($('#att_select').val());">
                        <option value="0">att=0,û���Զ�������</option>
                        <?=form_article_att($_GET['att'])?>
                    </select>&nbsp;&nbsp;
                    ���������������У�<select name="city_id_select" id="city_id_select">
                        <?=form_city($detail['city_id'], TRUE, !$admin->is_founder);?>
                    </select>
				</td>
			</tr>
            <?else:?>
            <td colspan="13">������Ϣ��</td>
            <?endif;?>
        </table>
    </div>
	<div class="multipage"><?=$multipage?></div>
	<center>
        <?if($total):?>
		<input type="hidden" name="dosubmit" value="yes" />
		<input type="hidden" name="op" value="listorder" />
		<button type="button" class="btn" onclick="easy_submit('myform','listorder',null)">��������</button>&nbsp;
        <button type="button" class="btn" onclick="easy_submit('myform','upatt','articleids[]')">����att����</button>&nbsp;
        <button type="button" class="btn" onclick="easy_submit('myform','upcity','articleids[]')">������������</button>&nbsp;
        <button type="button" class="btn" onclick="easy_submit('myform','delete','articleids[]')">ɾ����ѡ</button>
        <?endif;?>
	</center>
</form>