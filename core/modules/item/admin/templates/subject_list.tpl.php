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
        <div class="subtitle">����ɸѡ</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td width="100" class="altbg1">�������</td>
                <td width="300">
                    <select name="pid">
                        <option value="">ȫ��</option>
                        <?=form_item_category_main($pid)?>
                    </select>
                </td>
                <td width="100" class="altbg1">��������</td>
                <td width="*">
					<?if($admin->is_founder):?>
					<select name="city_id" onchange="select_city(this,'aid');">
						<option value="">ȫ��</option>
						<?=form_city($_GET['city_id'])?>
					</select>
					<?endif;?>
					<select name="aid" id="aid">
                        <option value="">ȫ��</option>
                        <?=form_area($_GET['city_id'], $_GET['aid'])?>
                    </select>
                </td>
            </tr>
            <tr>
                <td class="altbg1">����ؼ���</td>
                <td><input type="text" name="keyword" class="txtbox3" value="<?=$_GET['keyword']?>" /></td>
                <td width="100" class="altbg1">��������ID</td>
                <td width="*"><input type="text" name="id" class="txtbox4" value="<?=$_GET['id']?>" /></td>
            </tr>
            <tr>
                <td class="altbg1">���ⴴ����</td>
                <td ><input type="text" name="creator" class="txtbox3" value="<?=$_GET['creator']?>" /></td>
                <td class="altbg1">�������Ա</td>
                <td colspan="3"><input type="text" name="owner" class="txtbox3" value="<?=$_GET['owner']?>" /></td>
            </tr>
            <tr>
                <td class="altbg1">����ʱ��</td>
                <td colspan="3"><input type="text" name="starttime" class="txtbox3" value="<?=$_GET['starttime']?>" />&nbsp;~&nbsp;<input type="text" name="endtime" class="txtbox3" value="<?=$_GET['endtime']?>" />&nbsp;(YYYY-MM-DD)</td>
            </tr>
            <tr>
                <td class="altbg1">�������</td>
                <td colspan="3">
                <select name="orderby">
                    <option value="sid"<?=$_GET['orderby']=='sid'?' selected="selected"':''?>>Ĭ������</option>
                    <option value="addtime"<?=$_GET['orderby']=='addtime'?' selected="selected"':''?>>�Ǽ�ʱ��</option>
                    <option value="finer"<?=$_GET['orderby']=='finer'?' selected="selected"':''?>>�Ƽ���</option>
                    <option value="reviews"<?=$_GET['orderby']=='reviews'?' selected="selected"':''?>>��������</option>
                    <option value="pictures"<?=$_GET['orderby']=='pictures'?' selected="selected"':''?>>ͼƬ����</option>
                    <option value="pageviews"<?=$_GET['orderby']=='pageviews'?' selected="selected"':''?>>�����</option>
                </select>&nbsp;
                <select name="ordersc">
                    <option value="DESC"<?=$_GET['ordersc']=='DESC'?' selected="selected"':''?>>�ݼ�</option>
                    <option value="ASC"<?=$_GET['ordersc']=='ASC'?' selected="selected"':''?>>����</option>
                </select>&nbsp;
                <select name="offset">
                    <option value="10"<?=$_GET['offset']=='10'?' selected="selected"':''?>>ÿҳ��ʾ10��</option>
                    <option value="20"<?=$_GET['offset']=='20'?' selected="selected"':''?>>ÿҳ��ʾ20��</option>
                    <option value="50"<?=$_GET['offset']=='50'?' selected="selected"':''?>>ÿҳ��ʾ50��</option>
                    <option value="100"<?=$_GET['offset']=='100'?' selected="selected"':''?>>ÿҳ��ʾ100��</option>
                </select>&nbsp;
                <button type="submit" value="yes" name="dosubmit" class="btn2">ɸѡ</button>
                </td>
            </tr>
        </table>
    </div>
</form>
<form method="post" name="myform" action="<?=cpurl($module,$act,'',array('pid'=>$pid))?>">
    <div class="space">
        <div class="subtitle">�������</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" trmouse="Y" >
            <tr class="altbg1">
                <td width="25">ѡ?</td>
                <td width="90">����</td>
                <td width="*">����/����/����<?=p_order('sid');?></td>
                <td width="55">�Ƽ���<?=p_order('finer');?></td>
                <td width="55">�����<?=p_order('pageviews');?></td>
                <td width="25"><center>�ȼ�<?=p_order('level');?></center></td>
                <td width="25"><center>����<?=p_order('reviews');?></center></td>
                <td width="25"><center>ͼƬ<?=p_order('pictures');?></center></td>
                <td width="25"><center>����<?=p_order('guestbooks');?></center></td>
                <td width="*">���ʱ��<?=p_order('addtime');?></td>
                <td width="40">״̬<?=p_order('finer');?></td>
                <td width="60">����</td>
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
                <td><?=$val['status']==1?'����':'δ֪'?></td>
                <td>
                    <a href="<?=cpurl($module,'subject_edit','',array('pid'=>$pid, 'sid'=>$val['sid']))?>">�༭</a><br />
                    <a href="#" class="subject_operation" rel="<?=cpurl($module,'subject_edit','link',array('sid'=>$val[sid]))?>">�������</a>
                </td>
            </tr>
            <?endwhile;?>
            <tr>
                <td colspan="12" class="altbg1">
                    <button type="button" onclick="checkbox_checked('sids[]');" class="btn2">ȫѡ</button>
                    <input type="checkbox" name="delete_point" id="delete_point" value="1" /><label for="delete_point">ɾ������ͬʱ���ٵǼ��߻���</label>
                    <?if($pid>0):?>
                    |&nbsp;ת��ͬģ�ͷ��ࣺ<select name="moveto_catid">
                        <option value="" selected="selected">==ѡ��ת��Ŀ��==</option>
                        <?=form_item_category_equal_model($pid);?>&nbsp;
                        </select>
                    <?endif;?>
                </td>
            </tr>
            <?else:?>
            <tr>
                <td colspan="15">������Ϣ��</td>
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
        <button type="button" class="btn" onclick="easy_submit('myform','update',null)">�����޸�</button>&nbsp;
        <?if($pid>0):?>
        <button type="button" class="btn" onclick="easy_submit('myform','move','sids[]')">����ת�Ʒ���</button>&nbsp;
        <?endif;?>
        <button type="button" class="btn" onclick="easy_submit('myform','rebuild','sids[]')">�ؽ�ͳ��</button>&nbsp;
        <button type="button" class="btn" onclick="easy_submit('myform','delete','sids[]')">ɾ����ѡ</button>
    </center>
    <?endif;?>
</form>
</div>