<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<style type="text/css">
.review-rating { float:left; width:23%; margin-bottom:1px; }
    .review-rating .rating-name { margin-bottom:1px; }
    .review-rating .rating-start { width:90px; border:2px solid #ffc17d; padding:3px 5px; }

.review-rating-c { padding: 0; margin: 0 auto; width:83px; _width:85px; }
.review-rating-c li {line-height: 0;width: 14px;height: 15px;padding: 0;margin: 0;margin-left: 2px;list-style: none;
    float: left;cursor: pointer;}
.review-rating-c li span {display: none;}
</style>
<script type="text/javascript" src="static/javascript/review.js"></script>
<div id="body">
<form method="post" name="myform" action="<?=cpurl($module,$act,'save')?>">
    <?if($op=='report'):?>
    <input type="hidden" name="reportid" value="<?=$reportid?>" />
    <div class="space">
        <div class="subtitle">����ٱ���Ϣ</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0">
            <?if($report['disposaltime']):?>
            <tr>
                <td class="altbg1" valign="top">����ʱ�䣺</td>
                <td><span class="font_3"><?=date('Y-m-d H:i', $report['disposaltime'])?></span></td>
            </tr>
            <?endif?>
            <tr>
                <td width="150" class="altbg1">Υ�����ͣ�</td>
                <td width="*"><?=lang('review_report_sort_' . $report['sort'])?></td>
            </tr>
            <tr>
                <td class="altbg1" valign="top">�������ݣ�</td>
                <td><textarea style="width:500px;height:50px;" readonly><?=$report['reportcontent']?></textarea>
                <br /><span class="font_2">�ύ�����������޸Ĵ˴����ݡ�</span></td>
            </tr>
            <?if(!$report['update_point'] && $report['uid'] > 0):?>
            <tr>
                <td class="altbg1">����Ա�ӷ֣�</td>
                <td><?=form_bool('report[update_point]', 1)?>&nbsp;<span class="font_2">��Ա:</span><a href="<?=url("space/index/uid/$report[uid]")?>" target="_blank"><?=$report['username']?></a>(<?=$report['email']?>)</td>
            </tr>
            <?endif;?>
            <tr>
                <td class="altbg1" valign="top">����˵����</td>
                <td><textarea name="report[reportremark]" style="width:500px;height:50px;"><?=$report['reportremark']?></textarea>
                <br /><span class="font_2">����Ա��������Ϣ��˵���ͱ�ע��</span></td>
            </tr>
            <?if(!$report['disposaltime']):?>
            <tr>
                <td class="altbg1" valign="top">����ʽ��</td>
                <td><input type="checkbox" name="report[delete]" id="report_delete" value="1" />
                <label for="report_delete">ɾ������������Ϣ</label></td>
            </tr>
            <?endif;?>
        </table>
    </div>
    <?endif;?>

    <div class="space">
        <?if($detail):?>
        <div class="subtitle">�༭������Ϣ</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td align="right" class="altbg1" width="120px">�������ƣ�</td>
				<td width="*"><a href="<?=url("item/detail/id/$detail[id]")?>" target="_blank"><?=$detail['subject']?></a></td>
			</tr>
            <tr>
                <td align="right" class="altbg1">������Ա��</td>
                <td><a href="<?=url("space/index/uid/$detail[uid]")?>" target="_blank"><?=$detail['username']?></a></td></td>
            </tr>
            <tr>
                <td align="right" class="altbg1">IP��ַ��</td>
                <td><?=$detail['ip']?></td>
            </tr>
            <tr>
                <td align="right" class="altbg1"><span class="font_1">*</span>״̬��</td>
                <td><?=form_radio('review[status]', array(1=>'�����',0=>'δ���'),$detail['status'])?></td>
            </tr>
            <tr>
                <td align="right" class="altbg1"><span class="font_1">*</span>������</td>
                <td><?=form_radio('review[digest]', array(1=>'��',0=>'��'), $detail['digest'])?></td>
            </tr>
            <tr>
                <td align="right" class="altbg1"><span class="font_1">*</span>�ۺ����ۣ�</td>
                <td><?=form_radio('review[best]', array(1=>'��',0=>'����'), $detail['best'])?></td>
            </tr>
			<tr>
				<td align="right" class="altbg1"><span class="font_1">*</span>���۷�����</td>
				<td>
					<div style="width:600px;">
                        <?foreach($review_opts as $key => $val):?>
                        <div class="review-rating">
                            <div class="rating-name"><?=$val['name']?><span class="font_2" id="review_<?=$val['flag']?>_v">(��)</span></div>
                            <div class="rating-start"><input type="hidden" name="review[<?=$val['flag']?>]" id="review_<?=$val['flag']?>"></div>
                        </div>
                        <?endforeach;?>
                        <script type="text/javascript">
                            <?foreach($review_opts as $key => $val):?>
                            $("#review_<?=$val['flag']?>").review_rating({
                                rating_initial_value: '<?=$detail[$val['flag']]?>',
                                rating_function_name: 'review_rating',
                                directory: 'static/images/rating/'
                            });
                            <?endforeach;?>
                        </script>
					</div>
				</td>
			</tr>
            <tr>
                <td align="right" valign="top" class="altbg1">�������⣺</td>
                <td>
					<input type="text" name="review[title]" class="txtbox" value="<?=$detail['title']?>" />
				</td>
            </tr>
            <tr>
                <td align="right" valign="top" class="altbg1"><span class="font_1">*</span>�������ݣ�</td>
                <td>
					<textarea name="review[content]" style="width:90%;height:120px;padding:5px;"><?=$detail['content']?></textarea>
					<div class="font_1">�뽫�������������� <?=$MOD['review_min']?> - <?=$MOD['review_max']?> ���ַ����ڡ�</div>
				</td>
            </tr>
			<?if($catcfg['useprice']):?>
            <tr>
                <td align="right" class="altbg1"><?=$catcfg['useprice_title']?>��<?if($catcfg['useprice_required']):?><span class="font_1">*</span><?endif;?></td>
                <td><input type="text" name="review[price]" class="txtbox4" value="<?=$detail['price']?>" />&nbsp;&nbsp;<?=$catcfg['useprice_unit']?></td>
            </tr>
			<?endif;?>
            <?if($catcfg['taggroup'])foreach($catcfg['taggroup'] as $val):?>
            <tr>
                <td align="right" class="altbg1"><?=$taggroups[$val]['name']?>��</td>
                <?$detail_tags = $detail['taggroup'] ? @unserialize($detail['taggroup']) : array();?>
                <td>
                    <?if($taggroups[$val]['sort']==1):?>
                    <input type="text" name="review[taggroup][<?=$val?>]" id="taggroup_<?=$val?>" size="50" class="t_input" value="<?=@implode(',',$detail_tags[$val])?>" />&nbsp;�����ǩ���ö���","�ֿ�
                    <?elseif($taggroups[$val]['sort']==2):?>
                    <?$tagconfig = explode(',', $taggroups[$val]['options']);?>
                    <?foreach($tagconfig as $ky => $tgval):?>
                    <input type="checkbox" name="review[taggroup][<?=$val?>][]" value="<?=$tgval?>"<?if(@in_array($tgval,$detail_tags[$val])):?> checked<?endif;?> id="taggroup_<?=$val.'_'.$ky?>" /><label for="taggroup_<?=$val.'_'.$ky?>"><?=$tgval?></label>&nbsp;
                    <?endforeach;?>
                    <?endif;?>
                </td>
            </tr>
            <?endforeach;?>
        </table>
        <?else:?>
        <input type="hidden" name="empty_review" value="yes" />
        <p style="font-size:14px;font-weight:bold;">������Ϣ�����ڻ�����ɾ����</p>
        <?endif;?>
    </div>
	<center>
        <input type="hidden" name="forward" value="<?=get_forward()?>" />
        <input type="hidden" name="review[idtype]" value="<?=$detail['idtype']?>" />
		<input type="hidden" name="review[id]" value="<?=$detail['id']?>" />
		<input type="hidden" name="rid" value="<?=$rid?>" />
		<?=form_submit('dosubmit',lang('global_submit'),'yes','btn')?>&nbsp;<?=form_button_return(lang('global_return'),'btn')?>
	</center>
</form>
</div>