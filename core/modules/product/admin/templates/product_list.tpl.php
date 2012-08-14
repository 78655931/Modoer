<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<form method="get" action="<?=SELF?>">
    <input type="hidden" name="module" value="<?=$module?>" />
    <input type="hidden" name="act" value="<?=$act?>" />
    <input type="hidden" name="op" value="<?=$op?>" />
    <div class="space">
        <div class="subtitle">产品筛选</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td width="100" class="altbg1">产品模型</td>
                <td width="300">
                    <select name="modelid">
                    <option value="">全部模型</option>
                    <?=form_product_model($_GET['idtype'])?>
                    </select>
                </td>
                <td width="100" class="altbg1">产品主题SID</td>
                <td width="*"><input type="text" name="sid" class="txtbox3" value="<?=$_GET['sid']?>" /></td>
            </tr>
            <tr>
                <td class="altbg1">产品标题</td>
                <td><input type="text" name="subject" class="txtbox3" value="<?=$_GET['subject']?>" /></td>
                <td class="altbg1">添加会员</td>
                <td><input type="text" name="username" class="txtbox3" value="<?=$_GET['username']?>" /></td>
            </tr>
            <tr>
                <td class="altbg1">发布时间</td>
                <td colspan="3"><input type="text" name="starttime" class="txtbox3" value="<?=$_GET['starttime']?>" />&nbsp;~&nbsp;<input type="text" name="endtime" class="txtbox3" value="<?=$_GET['endtime']?>" />&nbsp;(YYYY-MM-DD)</td>
            </tr>
            <tr>
                <td class="altbg1">结果排序</td>
                <td colspan="3">
                <select name="orderby">
                    <option value="pid"<?=$_GET['orderby']=='cid'?' selected="selected"':''?>>默认排序</option>
                    <option value="dateline"<?=$_GET['orderby']=='dateline'?' selected="selected"':''?>>发布时间</option>
                    <option value="pageview"<?=$_GET['orderby']=='pageview'?' selected="selected"':''?>>浏览人气</option>
                    <option value="comments"<?=$_GET['orderby']=='comments'?' selected="selected"':''?>>评论数量</option>
                </select>&nbsp;
                <select name="ordersc">
                    <option value="DESC"<?=$_GET['ordersc']=='DESC'?' selected="selected"':''?>>递减</option>
                    <option value="ASC"<?=$_GET['ordersc']=='ASC'?' selected="selected"':''?>>递增</option>
                </select>&nbsp;
                <select name="offset">
                    <option value="20"<?=$_GET['offset']=='20'?' selected="selected"':''?>>每页显示20个</option>
                    <option value="50"<?=$_GET['offset']=='50'?' selected="selected"':''?>>每页显示50个</option>
                    <option value="100"<?=$_GET['offset']=='100'?' selected="selected"':''?>>每页显示100个</option>
                </select>&nbsp;
                <button type="submit" value="yes" name="dosubmit" class="btn2">筛选</button>&nbsp;
                <button type="button" onclick="window.location='<?=cpurl($module,$act,'add')?>';" class="btn2">添加产品</button>
                </td>
            </tr>
        </table>
    </div>
</form>
<?=form_begin(cpurl($module,$act))?>
    <div class="space">
        <div class="subtitle">产品管理</div>
        <?if($edit_links):?>
        <ul class="cptab">
            <?foreach($edit_links as $val):?>
            <li<?if($val['flag']=='product:list')echo' class="selected"';?>><a href="<?=$val['url']?>" onfocus="this.blur()"><?=$val['title']?></a></li>
            <?endforeach;?>
        </ul>
        <?endif;?>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" trmouse="Y">
            <tr class="altbg1">
                <td width="25">选</td>
                <td width="200">名称</td>
                <td width="200">所属主题</td>
                <td width="40">人气</td>
                <td width="40">评论</td>
                <td width="110">提交时间</td>
                <td width="60">操作</td>
            </tr>
            <?if($total):?>
            <?while($val=$list->fetch_array()):?>
            <tr>
                <td><input type="checkbox" name="pids[]" value="<?=$val['pid']?>" /></td>
                <td><a href="<?=url("product/detail/pid/$val[pid]")?>" target="_blank"><?=$val['subject']?></a></td>
                <td>[<a href="<?=url_replace(cpurl($module,$act,'list',$_GET),'sid',$val['sid'])?>">筛</a>][<a href="<?=cpurl($module,$act,'add',array('sid'=>$val['sid']))?>">加</a>] <a href="<?=url("item/detail/id/$val[sid]")?>" target="_blank"><?=$val['name'].$val['subname']?></a></td>
                <td><?=$val['pageview']?></td>
                <td><a href="<?=cpurl('comment','comment_list','list',array('idtype'=>'product','id'=>$val['pid'],'dosubmit'=>'yes'))?>"><?=$val['comments']?></a></td>
                <td><?=date('Y-m-d H:i',$val['dateline'])?></td>
                <td><a href="<?=cpurl($module,$act,'edit',array('pid'=>$val['pid']))?>">编辑</a></td>
            </tr>
            <?endwhile;?>
            <tr class="altbg1">
                <td colspan="3"><button type="button" onclick="checkbox_checked('pids[]');" class="btn2">全选</button></td>
                <td colspan="5" style="text-align:right;"><?=$multipage?></td>
            </tr>
            <?else:?>
            <tr><td colspan="8">暂无信息。</td></tr>
            <?endif?>
        </table>
    </div>
	<?if($total):?>
	<center>
		<input type="hidden" name="dosubmit" value="yes" />
		<input type="hidden" name="op" value="delete" />
		<button type="button" class="btn" onclick="easy_submit('myform','delete','pids[]')">删除所选</button>
	</center>
	<?endif;?>
<?=form_end()?>
</div>