<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<form method="get" action="<?=SELF?>">
    <input type="hidden" name="module" value="<?=$module?>" />
    <input type="hidden" name="act" value="<?=$act?>" />
    <input type="hidden" name="op" value="<?=$op?>" />
    <div class="space">
        <div class="subtitle">�û�����</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td width="100" class="altbg1">�û���</td>
                <td width="300">
                    <select name="groupid">
                        <option value="">ȫ��</option>
                        <?=form_member_usergroup($_GET['groupid'])?>
                    </select>
                </td>
                <td width="100" class="altbg1">��¼IP</td>
                <td width="*"><input type="text" name="loginip" class="txtbox3" value="<?=$_GET['loginip']?>" /></td>
            </tr>
            <tr>
                <td width="100" class="altbg1">��Ա����</td>
                <td width="300"><input type="text" name="username" class="txtbox3" value="<?=$_GET['username']?>" /></td>
                <td width="100" class="altbg1">email</td>
                <td width="*"><input type="text" name="email" class="txtbox3" value="<?=$_GET['email']?>" /></td>
            </tr>
            <tr>
                <td class="altbg1">ע��ʱ��</td>
                <td colspan="3"><input type="text" name="zreg_starttime" class="txtbox3" value="<?=$_GET['zreg_starttime']?>" />&nbsp;~&nbsp;<input type="text" name="zreg_endtime" class="txtbox3" value="<?=$_GET['zreg_endtime']?>" />&nbsp;(YYYY-MM-DD)</td>
            </tr>
            <tr>
                <td class="altbg1">��¼ʱ��</td>
                <td colspan="3"><input type="text" name="login_starttime" class="txtbox3" value="<?=$_GET['login_starttime']?>" />&nbsp;~&nbsp;<input type="text" name="login_endtime" class="txtbox3" value="<?=$_GET['login_endtime']?>" />&nbsp;(YYYY-MM-DD)</td>
            </tr>
            <tr>
                <td class="altbg1">�������</td>
                <td colspan="3">
                <select name="orderby">
                    <option value="uid"<?=$_GET['orderby']=='uid'?' selected="selected"':''?>>Ĭ������</option>
                    <option value="regdate"<?=$_GET['orderby']=='regdate'?' selected="selected"':''?>>ע��ʱ��</option>
                    <option value="logintime"<?=$_GET['orderby']=='logintime'?' selected="selected"':''?>>�Ǽ�ʱ��</option>
                    <option value="point"<?=$_GET['orderby']=='point'?' selected="selected"':''?>>����</option>
                    <option value="coin"<?=$_GET['orderby']=='coin'?' selected="selected"':''?>>���</option>
                    <option value="reviews"<?=$_GET['orderby']=='reviews'?' selected="selected"':''?>>������</option>
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
                </td>
            </tr>
        </table>
    </div>
</form>
<form method="post" name="myform" action="<?=cpurl($module,$act)?>">
    <div class="space">
        <div class="subtitle">�û��б�</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" trmouse="Y">
            <tr class="altbg1">
                <td width="25">&nbsp;ɾ?</td>
                <td width="50">UID</td>
                <td width="*">�û���</td>
                <td width="100">�û���</td>
                <td width="60">�ȼ�����</td>
                <td width="60">�ֽ�</td>
                <td width="60">������</td>
                <td width="110">����¼ʱ��</td>
                <td width="110">����½IP</td>
                <td width="110">ע��ʱ��</td>
                <td width="70">����</td>
            </tr>
            <? if($total):?>
            <? while($val=$list->fetch_array()):?>
            <tr>
                <td><input type="checkbox" name="uids[]" value="<?=$val['uid']?>" /></td>
                <td><?=$val['uid']?></td>
                <td><a href="<?=url('space/index/uid/'.$val['uid'])?>" target="_blank"><?=$val['username']?></a></td>
                <td><?=$usergroup[$val['groupid']]['groupname']?></td>
                <td><?=$val['point']?></td>
                <td><?=$val['rmb']?></td>
                <td><?=$val['reviews']?></td>
                <td><?=date('Y-m-d H:i', $val['logintime'])?></td>
                <td><?=$val['loginip']?></td>
                <td><?=date('Y-m-d H:i', $val['regdate'])?></td>
                <td>
                    <a href="<?=cpurl($module,$act,'edit',array('uid'=>$val['uid']))?>">�༭</a>
                    <a href="<?=cpurl($module,$act,'point',array('uid'=>$val['uid']))?>">����</a>
                </td>
            </tr>
            <? endwhile; ?>
            <? $list->free_result(); ?>
            <tr class="altbg1">
                <td colspan="2"><input type="button" name="select" value="ȫѡ" class="btn2" onclick="checkbox_checked('uids[]');" /></td>
                <td colspan="9" class="right"><?=$multipage?></td>
            </tr>
            <? else: ?>
            <tr>
                <td colspan="11">������Ϣ��</td>
            </tr>
            <? endif; ?>
        </table>
        <?if($total):?>
            <center>
                <input type="hidden" name="op" value="" />
                <input type="hidden" name="dosubmit" value="yes" />
                <input type="button" value=" ɾ����ѡ " class="btn" onclick="easy_submit('myform','delete','uids[]');" />
            </center>
        <?endif;?>
    </div>
</form>
</div>