<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<form method="post" action="<?=cpurl($module,$act)?>" name="myform">
    <div class="space">
        <div class="subtitle">��Ա������</div>
        <ul class="cptab">
            <?foreach($status_array as $k => $v) :?>
            <li<?if($_GET['status']==$k)echo' class="selected"';?>><a href="<?=cpurl($module,$act,'list',array('status'=>$k))?>"><?=$v?>(<?=(int)$status_group[$k]?>)</a></li>
            <?endforeach;?>
        </ul>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0">
            <tr class="altbg1">
                <td width="20">ɾ?</td>
                <td width="120">��Ա</td>
                <td width="120">��ϵ��</td>
                <td width="100">��ϵ�绰</td>
                <td width="80">����</td>
                <td width="80"><?if($MOD['pointgroup'])echo" {$point_group[$MOD['pointgroup']][name]}";?></td>
                <td width="110">����ʱ��</td>
                <td width="70">״̬</td>
                <td width="70">����</td>
            </tr>
            <?if($total) { while($val=$list->fetch_array()) { ?>
            <tr>
                <td><input type="checkbox" name="applyids[]" value="<?=$val['applyid']?>" /></td>
                <td><a href="<?=url('space/index/uid/'.$val['uid'])?>"><?=$val['username']?></a></td>
                <td><?=$val['linkman']?></td>
                <td><?=$val['mobile']?></td>
                <td><?=$val['num']?></td>
                <td><?=$val['coin']?></td>
                <td><?=date('Y-m-d H:i',$val['dateline'])?></td>
                <td><?=$status_array[$val['status']]?></td>
                <td><a href="<?=cpurl($module,$act,'checkup',array('applyid'=>$val['applyid']))?>">����</a></td>
            </tr>
            <? } ?>
            <tr>
                <td colspan="9" class="altbg1">
                    <button type="button" class="btn2" onclick="checkbox_checked('applyids[]');">ȫѡ</button>&nbsp;
                    <?if(!$_GET['status']):?><span class="font_1">ɾ���ȴ��������Ϣ�������᷵����Ҹ������Ա</span><?endif;?>
                </td>
            </tr>
            <? } else { ?>
            <tr><td colspan="10">������Ϣ��</td></tr>
            <? } ?>
        </table>
        <?if($multipage){?><div class="multipage"><?=$multipage?></div><?}?>
        <center>
            <?php if($total) { ?>
            <input type="hidden" name="dosubmit" value="yes" />
            <input type="hidden" name="op" value="" />
            <button type="button" class="btn" onclick="easy_submit('myform','delete','applyids[]')">ɾ����ѡ</button>
            <? } ?>
        </center>
    </div>
</form>
</div>