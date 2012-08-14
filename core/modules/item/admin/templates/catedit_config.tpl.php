<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<form method="post" action="<?=cpurl($module,$act,$op)?>">
    <div class="space">
        <div class="subtitle">�������</div>
        <ul class="cptab">
            <li class="selected" id="dropdown" rel="dropdown-box"><a href="<?=cpurl($module,$act,'config',array('catid'=>$catid))?>" onfocus="this.blur()">��������</a></li>
            <li><a href="<?=cpurl($module,$act,'subcat',array('catid'=>$catid))?>" onfocus="this.blur()">�ӷ������</a></li>
        </ul>
        <?$_G['loader']->helper('query','item');?>
        <ul class="dropdown-menu" id="dropdown-box">
            <?foreach(query_item::category(array('pid'=>0)) as $cate):?>
            <li><a href="<?=cpurl($module,$act,'config',array('catid'=>$cate['catid']))?>"><?=$cate[name]?></a></li>
            <?endforeach;?>
        </ul>
        <script type="text/javascript">
        $('#dropdown').powerFloat();
        </script>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td width="45%" class="altbg1"><strong>�������ƣ�</strong></td>
                <td width="*"><input type="text" name="t_cat[name]" class="txtbox3" value="<?=$t_cat['name']?>" /></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>����ģ�ͣ�</strong></td>
                <td><select name="t_cat[modelid]" disabled="disabled">
                    <option value="">==ģ���б�==</option>
					<?=form_item_models($t_cat['modelid'])?>
                    </select>&nbsp;
                    <a href="<?=cpurl($module,'model_edit','',array('modelid'=>$t_cat['modelid']))?>">�༭ģ��</a>&nbsp;
                    <a href="<?=cpurl($module,'field_list','',array('modelid'=>$t_cat['modelid']))?>">�ֶι���</a>
                </td>
            </tr>
            <tr><td colspan="2" class="altbg2"><center><strong>��������</strong></center></td></tr>
            <tr>
                <td class="altbg1"><strong>����ǰ̨���<?=$t_mod['item_name']?></strong>�����û���ǰ̨���<?=$t_mod['item_name']?></td>
                <td><?=form_bool('t_cfg[enable_add]', $t_cfg['enable_add']?$t_cfg['enable_add']:1)?></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>����������������ࣺ</strong>�����Ա����������ʱ�������������</td>
                <td><?=form_bool('t_cfg[relate_root]', $t_cfg['relate_root']?$t_cfg['relate_root']:0)?></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>����<?=$t_mod['item_name']?>���Թ��ܣ�</strong>��Ա�ɶ����ڷ��ʵ�ĳ<?=$t_mod['item_unit']?><?=$t_mod['item_name']?>�������ԣ�<?=$t_mod['item_name']?>����Ա�ɻظ���Щ���ԡ�</td>
                <td><?=form_bool('t_cfg[gusetbook]', $t_cfg['gusetbook'])?></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>����<?=$t_mod['item_name']?>��̳���ۣ�</strong>�����Ա�ڲ鿴<?=$t_mod['item_name']?>ʱ��Ԥ������������̳������ӡ�����������ǰ������ ��������-��̳���� ����Ҫ������̳��</td>
                <td><?=form_bool('t_cfg[forum]', $t_cfg['forum'])?></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>����<?=$t_mod['item_name']?>�������칦�ܣ�</strong><?=$t_mod['item_name']?>�����ú������˿ɱ༭����<?=$t_mod['item_name']?>����Ϣ��</td>
                <td><?=form_bool('t_cfg[subject_apply]', $t_cfg['subject_apply'])?></td>
            </tr>
            <tr>
                <td class="altbg1"><div style="margin-left:20px;"><strong>��������ͼƬ�ϴ���</strong>�ڿ������칦�ܺ��Ƿ���ҪͼƬ�ϴ����ܣ������ϴ���Ƭ������ҵӪҵִ�ա�</div></td>
                <td><?=form_bool('t_cfg[subject_apply_uppic]', $t_cfg['subject_apply_uppic'])?></td>
            </tr>
            <tr>
                <td class="altbg1"><div style="margin-left:40px;"><strong>ͼƬ�ϴ������ƣ�</strong>��ͼƬ����;ȡ��������Ӫҵִ�ա�</div></td>
                <td><input type="text" name="t_cfg[subject_apply_uppic_name]" value="<?=$t_cfg['subject_apply_uppic_name']?>" class="txtbox3" /></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>���û�Ա���빦�ܣ�</strong>���������ܺ󣬿ɶ�������ж�Ӧ��ͶƱ��Ϊ������ȥ������ȥ�ȡ�</td>
                <td><?=form_bool('t_cfg[useeffect]', $t_cfg['useeffect'])?></td>
            </tr>
            <tr>
                <td class="altbg1"><div style="margin-left:20px;"><strong>��Ա�������ʣ�</strong>���û�Ա����Ϊ���ʣ����磺ȥ������ȥ��Ŀǰ֧��2����Ϊ</div></td>
                <td>
                    <input type="text" name="t_cfg[effect1]" value="<?=$t_cfg['effect1']?>" class="txtbox4" />��
                    <input type="text" name="t_cfg[effect2]" value="<?=$t_cfg['effect2']?>" class="txtbox4" /><br />
                </td>
            </tr>
            <tr>
                <td class="altbg1"><strong>�������<?=$t_mod['item_name']?>�ֵ꣺</strong>�û������Ѵ��ڵ�<?=$t_mod['item_name']?>���ӷֵꡣ</td>
                <td><?=form_bool('t_cfg[use_subbranch]', $t_cfg['use_subbranch'])?></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>�����Ա�༭�Լ��ϴ���<?=$t_mod['item_name']?>��</strong>���������ܺ󣬻�Ա�Ϳ����ڡ��ҵ����֡���༭�Լ��Ǽǵ�<?=$t_mod['item_name']?>����Ȼ��Ҫ�ڻ�Ա���￪ͨ��Ӧ���Ȩ��</td>
                <td><?=form_bool('t_cfg[allow_edit_subject]', $t_cfg['allow_edit_subject'])?></td>
            </tr>
            <?if($attcats = $_G['loader']->variable('att_cat','item',false)):?>
            <tr>
                <td class="altbg1" valign="top"><strong>����������ɸѡ��</strong>�������б�ҳ�ɽ���������ɸѡ</td>
                <td>
                    <div><input type="checkbox" name="set2subcat" id="set2subcat" /><label for="set2subcat"><span class="font_1">������������ɸѡѡ��Ӧ�õ��ӷ���</span></label></div>
                    <select id="attcat" name="t_cfg[attcat][]" multiple="true">
                        <?foreach($attcats as $key => $val):?>
                        <option value="<?=$val['catid']?>"<?if($t_cfg['attcat'] && in_array($val['catid'], $t_cfg['attcat']))echo' selected="selected"';?>><?=$val['name']?><?if($val['des']):?>&nbsp;(<?=$val['des']?>)<?endif;?></option>
                        <?endforeach;?>
                    </select>
                    <script type="text/javascript">
                    $('#attcat').mchecklist({width:'95%',height:60,line_num:2});
                    </script>
                </td>
            </tr>
            <?endif;?>
            <tr><td colspan="2" class="altbg2"><center><strong>������������</strong></center></td></tr>
            <tr>
                <td class="altbg1"><strong>ѡ��������飺</strong>ָ��һ����������ڻ�Ա������Ĵ�����ݡ�</td>
                <td><select name="t_cat[review_opt_gid]">
                    <option value="">==���������б�==</option>
					<?=form_review_opt_group($t_cat['review_opt_gid'])?>
                </select>
			<tr>
                <td class="altbg1" valign="top"><strong>���õ�����ͼ��</strong>�����û��ڵ�����ͬʱ�ϴ�ͼƬ(�ο�����)���ϴ���ͼƬ���Զ����뵽������ᡣ</td>
                <td><?=form_bool('t_cfg[use_review_upload_pic]', $t_cfg['use_review_upload_pic'])?></td>
            </tr>
            <tr>
                <td class="altbg1" valign="top"><strong>���õ�����ǩ��</strong>���û�Ա�ڵ���ʱ������д�ı�ǩ��</td>
                <td>
                    <select id="taggroup" name="t_cfg[taggroup][]" multiple="true">
                        <?foreach($t_tag as $key => $val):?>
                        <option value="<?=$val['tgid']?>"<?if($t_cfg['taggroup'] && in_array($val['tgid'], $t_cfg['taggroup']))echo' selected="selected"';?>><?=$val['name']?><?if($val['des']):?>&nbsp;(<?=$val['des']?>)<?endif;?></option>
                        <?endforeach;?>
                    </select>
                    <script type="text/javascript">
                    $('#taggroup').mchecklist({width:'95%',height:60,line_num:2});
                    </script>
                </td>
            </tr>
            <tr>
                <td class="altbg1"><strong>���õ����۸��ֶΣ�</strong>ʹ�ü۸��ܺ��ڵ���ʱ����ʾ�۸������������д���Ѽ۸�</td>
                <td><?=form_bool('t_cfg[useprice]', $t_cfg['useprice'])?></td>
            </tr>
            <tr>
                <td class="altbg1"><div style="margin-left:20px;"><strong>�۸��ֶ��Ƿ���</strong>�����������ü۸��ֶ�ʱ��Ч��</div></td>
                <td><?=form_bool('t_cfg[useprice_required]', $t_cfg['useprice_required'])?></td>
            </tr>
            <tr>
                <td class="altbg1"><div style="margin-left:20px;"><strong>�۸��ֶε���ʾ���ƣ�</strong>��ʾ�ڵ���ҳ���С������������ü۸��ֶ�ʱ��Ч��</div></td>
                <td><input type="text" name="t_cfg[useprice_title]" value="<?=$t_cfg['useprice_title']?>" class="txtbox4" /></td>
            </tr>
            <tr>
                <td class="altbg1"><div style="margin-left:20px;"><strong>�۸��ֶεĵ�λ��</strong>��ʾ�ڵ���ҳ���С����磺Ԫ/�ˡ������������ü۸��ֶ�ʱ��Ч��</div></td>
                <td><input type="text" name="t_cfg[useprice_unit]" value="<?=$t_cfg['useprice_unit']?>" class="txtbox4" /></td>
            </tr>
            <tr>
                <td class="altbg1" valign="top"><strong>�����ظ�������</strong>�����Ա�Ա������µ�<?=$t_mod['item_name']?>�ظ�����</td>
                <td><?=form_bool('t_cfg[repeat_review]', $t_cfg['repeat_review'])?></td>
            </tr>
            <tr>
                <td class="altbg1" valign="top"><div style="margin-left:20px;"><strong>�ظ������������ƣ�</strong>�������ڿ��������ظ���������Ч�����ƻ�Ա�ظ�����ĳһ<?=$t_mod['item_unit'].$t_mod['item_name']?>��������0Ϊ�����ƣ�Ĭ��Ϊ������</div></td>
                <td><?=form_input('t_cfg[repeat_review_num]', (int)$t_cfg['repeat_review_num'], 'txtbox4')?>&nbsp;��</td>
            </tr>
            <tr>
                <td class="altbg1" valign="top"><div style="margin-left:20px;"><strong>�ظ�����ʱ������</strong>�������ڿ��������ظ���������Ч������2�ε���֮���ʱ������0Ϊ�����ƣ�Ĭ��Ϊ������</div></td>
                <td><?=form_input('t_cfg[repeat_review_time]', (int)$t_cfg['repeat_review_time'], 'txtbox4')?>&nbsp;����</td>
            </tr>
            <tr>
                <td class="altbg1"><strong>�����ο͵�����</strong>�����ο͵�����ϵͳ��û�е�¼�������ƣ��ο͵��������������ˢ�֣��޷���¼��Ա��Ϣ�����⣬�����ʹ�á�</td>
                <td><?=form_bool('t_cfg[guest_review]', $t_cfg['guest_review'])?></td>
            </tr>
            <tr><td colspan="2" class="altbg2"><center><strong>�������</strong></center></td></tr>
            <tr>
                <td class="altbg1" valign="top"><strong>�����ˣ�</strong>������˹��ܺ�δ��˵���Ϣ����ʱ����ǰ̨��ʾ�Ͳ�����</td>
                <td style="line-height:20px;">
					<div>���<?=$t_mod['item_name']?>��<?=form_bool('t_cfg[itemcheck]', $t_cfg['itemcheck'])?></div>
					<div>�������ݣ�<?=form_bool('t_cfg[reviewcheck]', $t_cfg['reviewcheck'])?></div>
					<div>�ϴ�ͼƬ��<?=form_bool('t_cfg[picturecheck]', $t_cfg['picturecheck'])?></div>
                    <div>�������ԣ�<?=form_bool('t_cfg[guestbookcheck]', $t_cfg['guestbookcheck'])?></div>
				</td>
            </tr>
            <tr class="altbg2"><td colspan="2"><center><strong>��������</strong></center></td></tr>
            <tr>
                <td class="altbg1"><strong>����ҳĬ�Ϸ��:</strong>����ҳ���Ĭ�ϲ�ʹ�÷������վͳһ���</td>
                <td><select name="t_cfg[templateid]">
                    <option value="0">��ʹ�÷��</option>
					<?=form_template('item', $t_cfg['templateid'])?>
                </select></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>����ҳĬ������ͼƬ�б�:</strong>����ҳ�����ʱ���Զ���ͼƬ�б����ʾ��λ���𣨲���ʾ����</td>
                <td><?=form_bool('t_cfg[detail_picture_hide]', $t_cfg['detail_picture_hide'])?></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>����ҳĬ��������ϸ����:</strong>����ҳ�����ʱ���Զ�����ϸ���ݵ���ʾ��λ���𣨲���ʾ����</td>
                <td><?=form_bool('t_cfg[detail_content_hide]', $t_cfg['detail_content_hide'])?></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>�б�ҳĬ����ʾ:</strong>�ڷ����б�ҳ��Ĭ����ʾ��ģʽ��</td>
                <td><?=form_select('t_cfg[displaytype]', lang('item_list_displytype'), $t_cfg['displaytype'])?></td>
            </tr>
            <tr>
                <td valign="top" class="altbg1"><strong>�б�ҳĬ������</strong>�ڷ����б�ҳ��Ĭ��ʹ�õ����򷽷���</td>
                <td><?=form_select('t_cfg[listorder]', lang('item_list_orderlist'), $t_cfg['listorder'])?></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>��������ͼ��:</strong>��Ӧ����ǰ̨ģ�壬Ĭ��û�в�ʹ�á�<br />�뽫ͼ����� static/images/category �ļ����ڣ��ı�����ֻҪ��дͼƬ�ļ������ɡ�</td>
                <td><?=form_input('t_cfg[icon]',$t_cfg['icon'],'txtbox4')?>&nbsp;
                    <select onchange="$('#input_t_cfg_icon').val($(this).val());">
                        <option value='<?=$t_cfg['icon']?>'>==��ѡͼ��==</option>
                        <?=form_item_category_icons()?>
                    </select>
                </td>
            </tr>
            <?if($_G['modules']['product']):?>
            <tr class="altbg2"><td colspan="2"><center><strong>�����Ʒ</strong></center></td></tr>
            <tr>
                <td class="altbg1"><strong>�����Ʒģ�ͣ�</strong>ѡ�񱾷��������������Ĳ�Ʒ���͡�</td>
                <?php $_G['loader']->helper('form','product');?>
                <td><select name="t_cfg[product_modelid]">
                    <option value="0" selected="selected">ѡ���Ʒģ��</option>
                    <?=form_product_model($t_cfg['product_modelid']);?>
                </select>
                </td>
            </tr>
            <?if($attcats):?>
            <tr>
                <td class="altbg1" valign="top"><strong>����������ɸѡ��</strong>�������б�ҳ�ɽ���������ɸѡ</td>
                <td>
                    <select id="product_attcat" name="t_cfg[product_attcat][]" multiple="true">
                        <?foreach($attcats as $key => $val):?>
                        <option value="<?=$val['catid']?>"<?if($t_cfg['product_attcat']&&in_array($val['catid'],$t_cfg['product_attcat']))echo' selected="selected"';?>><?=$val['name']?><?if($val['des']):?>&nbsp;(<?=$val['des']?>)<?endif;?></option>
                        <?endforeach;?>
                    </select>
                    <script type="text/javascript">
                    $('#product_attcat').mchecklist({width:'95%',height:60,line_num:2});
                    </script>
                </td>
            </tr>
            <?endif;?>
            <?endif;?>
            <tr class="altbg2"><td colspan="2"><center><strong>���������Ż�</strong></center></td></tr>
            <tr>
                <td class="altbg1"><strong>Meta Keywords��</strong>Keywords �������ҳ��ͷ���� Meta ��ǩ�У����ڼ�¼��ҳ��Ĺؼ��֣�����ؼ��ּ����ð�Ƕ��� "," ������</td>
                <td><input type="text" name="t_cfg[meta_keywords]" value="<?=$t_cfg['meta_keywords']?>" class="txtbox" /></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>Meta Description��</strong>Description ������ҳ��ͷ���� Meta ��ǩ�У����ڼ�¼��ҳ��ĸ�Ҫ��������</td>
                <td><input type="text" name="t_cfg[meta_description]" value="<?=$t_cfg['meta_description']?>" class="txtbox" /></td>
            </tr>
        </table>
        <center>
            <input type="hidden" name="catid" value="<?=$_GET['catid']?>" />
            <button type="submit" name="dosubmit" value="yes" class="btn" /><?=lang('global_submit')?></button>&nbsp;
            <button type="button" class="btn" onclick="location.href='<?=cpurl($module,'category_list')?>'" /><?=lang('global_return')?></button>
        </center>
    </div>
</form>
</div>