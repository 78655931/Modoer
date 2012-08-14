<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<form method="get" action="<?=SELF?>">
    <input type="hidden" name="module" value="<?=$module?>" />
    <input type="hidden" name="act" value="<?=$act?>" />
    <input type="hidden" name="op" value="<?=$op?>" />
    <div class="space">
        <div class="subtitle">��Ʒɸѡ</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td width="100" class="altbg1">��Ʒģ��</td>
                <td width="300">
                    <select name="modelid">
                    <option value="">ȫ��ģ��</option>
                    <?=form_product_model($_GET['idtype'])?>
                    </select>
                </td>
                <td width="100" class="altbg1">��Ʒ����SID</td>
                <td width="*"><input type="text" name="sid" class="txtbox3" value="<?=$_GET['sid']?>" /></td>
            </tr>
            <tr>
                <td class="altbg1">��Ʒ����</td>
                <td><input type="text" name="subject" class="txtbox3" value="<?=$_GET['subject']?>" /></td>
                <td class="altbg1">��ӻ�Ա</td>
                <td><input type="text" name="username" class="txtbox3" value="<?=$_GET['username']?>" /></td>
            </tr>
            <tr>
                <td class="altbg1">����ʱ��</td>
                <td colspan="3"><input type="text" name="starttime" class="txtbox3" value="<?=$_GET['starttime']?>" />&nbsp;~&nbsp;<input type="text" name="endtime" class="txtbox3" value="<?=$_GET['endtime']?>" />&nbsp;(YYYY-MM-DD)</td>
            </tr>
            <tr>
                <td class="altbg1">�������</td>
                <td colspan="3">
                <select name="orderby">
                    <option value="pid"<?=$_GET['orderby']=='cid'?' selected="selected"':''?>>Ĭ������</option>
                    <option value="dateline"<?=$_GET['orderby']=='dateline'?' selected="selected"':''?>>����ʱ��</option>
                    <option value="pageview"<?=$_GET['orderby']=='pageview'?' selected="selected"':''?>>�������</option>
                    <option value="comments"<?=$_GET['orderby']=='comments'?' selected="selected"':''?>>��������</option>
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
                <button type="submit" value="yes" name="dosubmit" class="btn2">ɸѡ</button>&nbsp;
                <button type="button" onclick="window.location='<?=cpurl($module,$act,'add')?>';" class="btn2">��Ӳ�Ʒ</button>
                </td>
            </tr>
        </table>
    </div>
</form>
<?=form_begin(cpurl($module,$act))?>
    <div class="space">
        <div class="subtitle">��Ʒ����</div>
        <?if($edit_links):?>
        <ul class="cptab">
            <?foreach($edit_links as $val):?>
            <li<?if($val['flag']=='product:list')echo' class="selected"';?>><a href="<?=$val['url']?>" onfocus="this.blur()"><?=$val['title']?></a></li>
            <?endforeach;?>
        </ul>
        <?endif;?>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" trmouse="Y">
            <tr class="altbg1">
                <td width="25">ѡ</td>
                <td width="200">����</td>
                <td width="200">��������</td>
                <td width="40">����</td>
                <td width="40">����</td>
                <td width="110">�ύʱ��</td>
                <td width="60">����</td>
            </tr>
            <?if($total):?>
            <?while($val=$list->fetch_array()):?>
            <tr>
                <td><input type="checkbox" name="pids[]" value="<?=$val['pid']?>" /></td>
                <td><a href="<?=url("product/detail/pid/$val[pid]")?>" target="_blank"><?=$val['subject']?></a></td>
                <td>[<a href="<?=url_replace(cpurl($module,$act,'list',$_GET),'sid',$val['sid'])?>">ɸ</a>][<a href="<?=cpurl($module,$act,'add',array('sid'=>$val['sid']))?>">��</a>] <a href="<?=url("item/detail/id/$val[sid]")?>" target="_blank"><?=$val['name'].$val['subname']?></a></td>
                <td><?=$val['pageview']?></td>
                <td><a href="<?=cpurl('comment','comment_list','list',array('idtype'=>'product','id'=>$val['pid'],'dosubmit'=>'yes'))?>"><?=$val['comments']?></a></td>
                <td><?=date('Y-m-d H:i',$val['dateline'])?></td>
                <td><a href="<?=cpurl($module,$act,'edit',array('pid'=>$val['pid']))?>">�༭</a></td>
            </tr>
            <?endwhile;?>
            <tr class="altbg1">
                <td colspan="3"><button type="button" onclick="checkbox_checked('pids[]');" class="btn2">ȫѡ</button></td>
                <td colspan="5" style="text-align:right;"><?=$multipage?></td>
            </tr>
            <?else:?>
            <tr><td colspan="8">������Ϣ��</td></tr>
            <?endif?>
        </table>
    </div>
	<?if($total):?>
	<center>
		<input type="hidden" name="dosubmit" value="yes" />
		<input type="hidden" name="op" value="delete" />
		<button type="button" class="btn" onclick="easy_submit('myform','delete','pids[]')">ɾ����ѡ</button>
	</center>
	<?endif;?>
<?=form_end()?>
</div>