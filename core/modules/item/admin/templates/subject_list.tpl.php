<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<script type="text/javascript" src="./data/cachefiles/area.js"></script>
<style type="text/css">
.img img { max-width:80px; max-height:60px; border:1px solid #ccc; padding:1px; 
    _width:expression(this.width > 80 ? 80 : true); _height:expression(this.height > 60 ? 60 : true); }
</style>
<div id="body">
<form method="get" action="<?=SELF?>">
    <input type="hidden" name="module" value="<?=$module?>" />
    <input type="hidden" name="act" value="<?=$act?>" />
    <input type="hidden" name="op" value="<?=$op?>" />
    <div class="space">
        <div class="subtitle">主题筛选</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td width="100" class="altbg1">主题分类</td>
                <td width="300">
                    <select name="pid">
                        <option value="">全部</option>
                        <?=form_item_category_main($pid)?>
                    </select>
                </td>
                <td width="100" class="altbg1">所属地区</td>
                <td width="*">
					<?if($admin->is_founder):?>
					<select name="city_id" onchange="select_city(this,'aid');">
						<option value="">全部</option>
						<?=form_city($_GET['city_id'])?>
					</select>
					<?endif;?>
					<select name="aid" id="aid">
                        <option value="">全部</option>
                        <?=form_area($_GET['city_id'], $_GET['aid'])?>
                    </select>
                </td>
            </tr>
            <tr>
                <td class="altbg1">主题关键字</td>
                <td><input type="text" name="keyword" class="txtbox3" value="<?=$_GET['keyword']?>" /></td>
                <td width="100" class="altbg1">点评对象ID</td>
                <td width="*"><input type="text" name="id" class="txtbox4" value="<?=$_GET['id']?>" /></td>
            </tr>
            <tr>
                <td class="altbg1">主题创建者</td>
                <td ><input type="text" name="creator" class="txtbox3" value="<?=$_GET['creator']?>" /></td>
                <td class="altbg1">主题管理员</td>
                <td colspan="3"><input type="text" name="owner" class="txtbox3" value="<?=$_GET['owner']?>" /></td>
            </tr>
            <tr>
                <td class="altbg1">发布时间</td>
                <td colspan="3"><input type="text" name="starttime" class="txtbox3" value="<?=$_GET['starttime']?>" />&nbsp;~&nbsp;<input type="text" name="endtime" class="txtbox3" value="<?=$_GET['endtime']?>" />&nbsp;(YYYY-MM-DD)</td>
            </tr>
            <tr>
                <td class="altbg1">结果排序</td>
                <td colspan="3">
                <select name="orderby">
                    <option value="sid"<?=$_GET['orderby']=='sid'?' selected="selected"':''?>>默认排序</option>
                    <option value="addtime"<?=$_GET['orderby']=='addtime'?' selected="selected"':''?>>登记时间</option>
                    <option value="finer"<?=$_GET['orderby']=='finer'?' selected="selected"':''?>>推荐度</option>
                    <option value="reviews"<?=$_GET['orderby']=='reviews'?' selected="selected"':''?>>点评数量</option>
                    <option value="pictures"<?=$_GET['orderby']=='pictures'?' selected="selected"':''?>>图片数量</option>
                    <option value="pageviews"<?=$_GET['orderby']=='pageviews'?' selected="selected"':''?>>浏览量</option>
                </select>&nbsp;
                <select name="ordersc">
                    <option value="DESC"<?=$_GET['ordersc']=='DESC'?' selected="selected"':''?>>递减</option>
                    <option value="ASC"<?=$_GET['ordersc']=='ASC'?' selected="selected"':''?>>递增</option>
                </select>&nbsp;
                <select name="offset">
                    <option value="10"<?=$_GET['offset']=='10'?' selected="selected"':''?>>每页显示10个</option>
                    <option value="20"<?=$_GET['offset']=='20'?' selected="selected"':''?>>每页显示20个</option>
                    <option value="50"<?=$_GET['offset']=='50'?' selected="selected"':''?>>每页显示50个</option>
                    <option value="100"<?=$_GET['offset']=='100'?' selected="selected"':''?>>每页显示100个</option>
                </select>&nbsp;
                <button type="submit" value="yes" name="dosubmit" class="btn2">筛选</button>
                </td>
            </tr>
        </table>
    </div>
</form>
<form method="post" name="myform" action="<?=cpurl($module,$act,'',array('pid'=>$pid))?>">
    <div class="space">
        <div class="subtitle">主题管理</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" trmouse="Y" >
            <tr class="altbg1">
                <td width="25">选?</td>
                <td width="90">封面</td>
                <td width="*">标题/分类/地区<?=p_order('sid');?></td>
                <td width="55">推荐度<?=p_order('finer');?></td>
                <td width="55">浏览量<?=p_order('pageviews');?></td>
                <td width="25"><center>等级<?=p_order('level');?></center></td>
                <td width="25"><center>点评<?=p_order('reviews');?></center></td>
                <td width="25"><center>图片<?=p_order('pictures');?></center></td>
                <td width="25"><center>留言<?=p_order('guestbooks');?></center></td>
                <td width="*">添加时间<?=p_order('addtime');?></td>
                <td width="40">状态<?=p_order('finer');?></td>
                <td width="60">操作</td>
            </tr>
            <?if($total):?>
            <?while($val=$list->fetch_array()):?>
            <tr>
                <td><input type="checkbox" name="sids[]" value="<?=$val['sid']?>" /></td>
                <td class="img"><img src="<?if($val['thumb']):?><?=$val['thumb']?><?else:?>static/images/s_noimg.gif<?endif;?>" /></td>
                <td>
                    <div><a href="<?=url("item/detail/id/$val[sid]")?>" target="_blank"><b><?=trim($val['name'].($val['subname']?"($val[subname])":''))?></b></a></div>
                    <div><?=display('item:category',"catid/$val[pid]")?> &raquo; <?=display('item:category',"catid/$val[catid]")?></div>
                    <?if($val['city_id']):?><div><?=display('modoer:area',"aid/$val[city_id]")?> &raquo; <?=display('modoer:area',"aid/$val[aid]")?></div><?endif;?>
                </td>
                <td><input type="text" name="subjects[<?=$val['sid']?>][finer]" value="<?=$val['finer']?>" class="txtbox5" /></td>
                <td><input type="text" name="subjects[<?=$val['sid']?>][pageviews]" value="<?=$val['pageviews']?>" class="txtbox5" /></td>
                <td><center><?=$val['level']?></center></td>
                <td><center><?=$val['reviews']?></center></td>
                <td><center><a href="<?=cpurl($module,'picture_list','',array('pid'=>$val['pid'],'sid'=>$val['sid']))?>"><?=$val['pictures']?></a></center></td>
                <td><center><a href="<?=cpurl($module,'guestbook_list','',array('pid'=>$val['pid'],'sid'=>$val['sid']))?>"><?=$val['guestbooks']?></a></center></td>
                <td><?=date('Y-m-d H:i', $val['addtime'])?></td>
                <td><?=$val['status']==1?'正常':'未知'?></td>
                <td>
                    <a href="<?=cpurl($module,'subject_edit','',array('pid'=>$pid, 'sid'=>$val['sid']))?>">编辑</a><br />
                    <a href="#" class="subject_operation" rel="<?=cpurl($module,'subject_edit','link',array('sid'=>$val[sid]))?>">更多操作</a>
                </td>
            </tr>
            <?endwhile;?>
            <tr>
                <td colspan="12" class="altbg1">
                    <button type="button" onclick="checkbox_checked('sids[]');" class="btn2">全选</button>
                    <input type="checkbox" name="delete_point" id="delete_point" value="1" /><label for="delete_point">删除主题同时减少登记者积分</label>
                    <?if($pid>0):?>
                    |&nbsp;转移同模型分类：<select name="moveto_catid">
                        <option value="" selected="selected">==选择转移目标==</option>
                        <?=form_item_category_equal_model($pid);?>&nbsp;
                        </select>
                    <?endif;?>
                </td>
            </tr>
            <?else:?>
            <tr>
                <td colspan="15">暂无信息。</td>
            </tr>
            <?endif;?>
        </table>
    </div>
    <?if($total):?>
    <script type="text/javascript">
        $('.subject_operation').powerFloat({position:'8-6',targetMode:"ajax"});
    </script>
    <div class="multipage"><?=$multipage?></div>
    <center>
        <input type="hidden" name="dosubmit" value="yes" />
        <input type="hidden" name="op" value="" />
        <button type="button" class="btn" onclick="easy_submit('myform','update',null)">更新修改</button>&nbsp;
        <?if($pid>0):?>
        <button type="button" class="btn" onclick="easy_submit('myform','move','sids[]')">批量转移分类</button>&nbsp;
        <?endif;?>
        <button type="button" class="btn" onclick="easy_submit('myform','rebuild','sids[]')">重建统计</button>&nbsp;
        <button type="button" class="btn" onclick="easy_submit('myform','delete','sids[]')">删除所选</button>
    </center>
    <?endif;?>
</form>
</div>