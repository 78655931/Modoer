<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<?=form_begin(cpurl($module,$act))?>
    <div class="space">
        <div class="subtitle"><?=$MOD['name']?>&nbsp;ģ������</div>
        <ul class="cptab">
            <li class="selected" id="btn_config1"><a href="#" onclick="tabSelect(1,'config');" onfocus="this.blur()">��������</a></li>
			<li id="btn_config2"><a href="#" onclick="tabSelect(2,'config');" onfocus="this.blur()">�Ա�������</a></li>
            <li id="btn_config3"><a href="#" onclick="tabSelect(3,'config');" onfocus="this.blur()">��ʾ����</a></li>
            <li id="btn_config4"><a href="#" onclick="tabSelect(4,'config');" onfocus="this.blur()">SEO����</a></li>
        </ul>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" id="config1">
            <tr>
                <td width="45%" class="altbg1"><strong>Ĭ��������:</strong>����ҳ��δָ�������������£�Ĭ����ʾ�ĸ���������ݣ�û�пɷ���ʱ��������վ����ҳ�棬���ӵ���������</td>
                <td width="*"><select name="modcfg[pid]">
                    <option value="">==ѡ��������==</option>
					<?=form_item_category_main($modcfg['pid'])?>
                </select></td>
            </tr>
            <tr>
                <td class="altbg1" valign="top"><strong>���������/�������������Ŀ¼:</strong>������ֻ���ڶ���������������ʹ�ã������ڶ���Ŀ¼��ʹ�ã�ͬʱ���ķ�������Ҫ�������İ󶨣��򷺽�������</td>
                <td><?=form_radio('modcfg[sldomain]', array(0=>'�ر�',1=>'�����/��������',2=>'����Ŀ¼',3=>'���߶���Ҫ'), $modcfg['sldomain'])?><br /><span class="font_1">�򿪱����ܺ���ȷ��data/config.php�ļ���$_G['cookiedomain']��ֵΪһ��������������www)������modoer.com�������Ա��¼��ʧ�ܣ����ø���Ŀ¼��Ҫ����Ŀ¼��ʽ��URL��д���ܣ�ͬʱҪ����data/rewrite_pathinfo.inc�ļ������������뵽�����鿴��</span></td>
            </tr>
            <tr>
                <td class="altbg1" valign="top"><div style="margin-left:20px;"><strong>�����/����������׼:</strong>���ö�/���������Ļ�׼������������ʵ��shopname.abc.com�Ķ������������׼Ϊabc.com�������ʵ����������shopname.shop.abc.com�����׼Ϊshop.abc.com</div></td>
                <td><input type="text" name="modcfg[base_sldomain]" value="<?=$modcfg['base_sldomain']?>" class="txtbox3" /><br /><span class="font_1">������ֻ�ڿ����������/����������Ч�������������ж����������ܣ���رձ�����</span></td>
            </tr>
            <tr>
                <td class="altbg1" valign="top"><div style="margin-left:20px;"><strong>�����/��������/����Ŀ¼������:</strong>��������һЩԤ�������ƣ������Լ��ڽ����Ҫʹ��ʱ����ɷ��ʳ�ͻ������,������ö���","�ָ���ϵͳ���ֹʹ��ģ���ʶ����Ϊ����/����Ŀ¼���ơ�</div></td>
                <td><input type="text" name="modcfg[reserve_sldomain]" value="<?=$modcfg['reserve_sldomain']?>" class="txtbox" /></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>���������۵Ļ�������:</strong>���ù���������Ļ������ͣ����������ڻ�Աģ��������</td>
                <td>
					<select name="modcfg[selltpl_pointtype]">
						<option value="">ѡ���������</option>
						<?=form_member_pointgroup($modcfg['selltpl_pointtype'])?>
					</select>
				</td>
            </tr>
            <tr>
                <td class="altbg1"><div style="margin-left:20px;"><strong>��������ʹ������:</strong>�����������ʹ�����ޣ�Ĭ��Ϊ 180 ��</td>
                <td>
					<input type="text" name="modcfg[selltpl_useday]" value="<?=$modcfg['selltpl_useday']>0?$modcfg['selltpl_useday']:180?>" class="txtbox4" />&nbsp;��
				</td>
            </tr>
            <tr>
                <td class="altbg1" valign="top"><strong>����֤��:</strong>������֤��ɼ��ٹ����ύ��Ϣ������Ҳ���û�Ա�е�����</td>
                <td>
                    <div>��������:<?=form_bool('modcfg[seccode_subject]', $modcfg['seccode_subject'])?></div>
                    <div>��������(��Ա):<?=form_bool('modcfg[seccode_review]', $modcfg['seccode_review'])?></div>
                    <div>��������(�ο�):<?=form_bool('modcfg[seccode_review_guest]', $modcfg['seccode_review_guest'])?></div>
                    <div>��������:<?=form_bool('modcfg[seccode_guestbook]', $modcfg['seccode_guestbook'])?></div>
                </td>
            </tr>
            <tr>
                <td class="altbg1"><strong>���������ϴ�ͼƬ:</strong>�������ϴ��󣬻�Ա����һ��������ϴ�20��ͼƬ</td>
                <td><?=form_bool('modcfg[multi_upload_pic]',$modcfg['multi_upload_pic']);?></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>�����ϴ�ͼƬ����:</strong>һ���ύ����ϴ�������ͼƬ������20�ţ�����2�ţ�Ĭ��5��</td>
                <td><input type="text" name="modcfg[multi_upload_pic_num]" value="<?=$modcfg['multi_upload_pic_num']?>" class="txtbox5" /></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>����������������:</strong>����������ݵ��ַ�����</td>
                <td><input type="text" name="modcfg[review_min]" value="<?=$modcfg['review_min']?>" class="txtbox5" /> - <input type="text" name="modcfg[review_max]" value="<?=$modcfg['review_max']?>" class="txtbox5" /></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>����������������:</strong>�����������ݵ��ַ�����</td>
                <td><input type="text" name="modcfg[guestbook_min]" value="<?=$modcfg['guestbook_min']?>" class="txtbox5" /> - <input type="text" name="modcfg[guestbook_max]" value="<?=$modcfg['guestbook_max']?>" class="txtbox5" /></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>��Ӧ������������:</strong>�����Ӧ�����ַ�����</td>
                <td><input type="text" name="modcfg[respond_min]" value="<?=$modcfg['respond_min']?>" class="txtbox5" /> - <input type="text" name="modcfg[respond_max]" value="<?=$modcfg['respond_max']?>" class="txtbox5" /></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>��˻�Ӧ����:</strong>������˹��ܺ�δ��˵���Ϣ����ʱ����ǰ̨��ʾ�Ͳ�����</td>
                <td>
                    <?=form_bool('modcfg[respondcheck]', $modcfg['respondcheck'])?>
                </td>
            </tr>
            <tr>
                <td class="altbg1">
                    <strong>���÷�Ĭ��ͷ�����:</strong>�û���������һ����Ĭ��ͷ�����ܽ��е���
                </td>
                <td><?=form_bool('modcfg[avatar_review]', $modcfg['avatar_review'])?></td>
            </tr>
            <tr>
                <td class="altbg1">
                    <strong>���ݿո��ǩ�ָ���:</strong>����1.x��ʹ�ÿո������ţ����������ʹ�ÿո���ʵ�ַָ���ǩ. ע��:�ո���ж�Ӣ�Ķ���ı�ǩ��
                </td>
                <td><?=form_bool('modcfg[tag_split_sp]', $modcfg['tag_split_sp'])?></td>
            </tr>
            <tr>
                <td class="altbg1">
                    <strong>�ر����������������ת����</strong>��ʹ���������⹦��ʱ��������ֻ��1�����ʱ�������Զ�����ת��������������ҳ��������ùرգ����򽫲�����ת��
                </td>
                <td><?=form_bool('modcfg[search_location]', $modcfg['search_location'])?></td>
            </tr>
            <tr>
                <td class="altbg1">
                    <strong>����������۹���:</strong>�ο�����������������ʱ�����Զ��������������ԡ�
                </td>
                <td><?=form_bool('modcfg[album_comment]', $modcfg['album_comment'])?></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>�첽�����Ա�������:</strong>�Ա�������Ϊ��ʱͨ���Ա���API��ȡ������������ӳ�����ҳ��ļ����ٶȻ�ҳ�����ʧ�ܣ�Ӱ���û����飬�򿪱����ܺ󣬽���ҳ�������Ϻ��ټ����Ա������ݣ�����򿪴˹��ܣ��������ܽ���ʹ���Ա��͹��ܺ���Ч��</td>
                <td><?=form_bool('modcfg[ajax_taoke]', $modcfg['ajax_taoke'])?></td>
            </tr>
        </table>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" id="config2" style="display:none;">
            <tr>
                <td width="45%" class="altbg1"><strong>�Ա�����ƽ̨Ӧ��App Key:</strong>ע���Ա�����ƽ̨(open.taobao.com)�������������̨������һ��Ӧ�ã���ȡApp Key</td>
                <td width="*"><input type="text" name="modcfg[taoke_appkey]" id="taoke_appkey" value="<?=$modcfg['taoke_appkey']?>" class="txtbox2" /></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>�Ա�����ƽ̨Ӧ��App Secret:</strong>��App Keyͬʱ���</td>
                <td><input type="text" name="modcfg[taoke_appsecret]" value="<?=$modcfg['taoke_appsecret']?>" class="txtbox2" /></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>�Ա�����ƽ̨Ӧ��App SessionKey:</strong>������дApp key��������޸��������App Key��ֵ�������»�ȡSessionKey��</td>
                <td>
						<input type="text" name="modcfg[taoke_sessionkey]" value="<?=$modcfg['taoke_sessionkey']?>" class="txtbox2" />
						<a href="javascript:get_sessionkey();" target="_blank">���������ȡsessionkey</a>
				</td>
            </tr>
            <tr>
                <td class="altbg1"><strong>�Ա��û��ǳƣ�</strong>ָ�����Ա��Ļ�Ա��¼��.����ǳƴ���,��ô�ͻ����ղ���Ӷ�𣻵��ƹ����Ʒ�ɹ���Ӷ�������������Ա��ǳƵ��˻����������Ϣ���Ե��밢������(www.alimama.com)�鿴</td>
                <td><input type="text" name="modcfg[taoke_nick]" value="<?=$modcfg['taoke_nick']?>" class="txtbox2" /></td>
            </tr>
		<table class="maintable" border="0" cellspacing="0" cellpadding="0" id="config3" style="display:none;">
            <tr>
                <td width="45%" class="altbg1"><strong>ͼƬ�ߴ�:</strong>�ϴ����������ͼƬʱ�����������ߴ磬��ʽΪ���� x �ߣ�Ĭ�ϣ�200 x 150</td>
                <td width="*"><input type="text" name="modcfg[pic_width]" value="<?=$modcfg['pic_width']?>" class="txtbox5" />&nbsp;x&nbsp;<input type="text" name="modcfg[pic_height]" value="<?=$modcfg['pic_height']?>" class="txtbox5" /></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>��Ƶ�������ߴ�:</strong>������ҳ����ʾ��Ƶ�ĳߴ磬��ʽΪ���� x �ߣ�Ĭ�ϣ�250 x 200</td>
                <td><input type="text" name="modcfg[video_width]" value="<?=$modcfg['video_width']?>" class="txtbox5" />&nbsp;x&nbsp;<input type="text" name="modcfg[video_height]" value="<?=$modcfg['video_height']?>" class="txtbox5" /></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>�б�ҳ��ʾ������:</strong>�б�ҳ������ҳ����ÿҳ��ʾ������������</td>
                <td><?=form_input('modcfg[list_num]', $modcfg['list_num'], 'txtbox4')?></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>����ҳ����ʾ������:</strong>����ҳ����ÿҳ��ʾ������Ŀ</td>
                <td><?=form_input('modcfg[review_num]', $modcfg['review_num'], 'txtbox4')?></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>����ҳ����ʾ�����úò���:</strong>������ҳ����ʾ���û��ʻ����ĺò�����Ϣ</td>
                <td><?=form_bool('modcfg[show_detail_vs_review]', (bool)$modcfg['show_detail_vs_review'])?></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>��������:</strong>������������˳��ʽ</td>
                <td><?=form_radio('modcfg[classorder]',array('total'=>'�������е�����','order'=>'������˳��'),$modcfg['classorder'])?></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>�����������:</strong>��������ķ�����ʾ</td>
                <td><?=form_select('modcfg[thumb]',array('1'=>'�����ϴ���ͼƬ','2'=>'����Ӧ(��ͼʱѡ����)','3'=>'�ֶ�ѡ��ͼƬ'),$modcfg['thumb'])?></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>����ҳ��ʾ����ͼ:</strong>������ҳ����ʾ���������ͼ�б�</td>
                <td><?=form_bool('modcfg[show_thumb]', $modcfg['show_thumb'])?></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>�ر�����ҳ������ͳ�ƹ���:</strong>����������ҳ�棬����ʾ��ǰ����ľ���һЩͳ����Ϣ��ʹ�ù��ܺ󽫹ر���ʾͳ����Ϣ��</td>
                <td><?=form_bool('modcfg[close_detail_total]', $modcfg['close_detail_total'])?></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>�б�ҳɸѡ�������۵�:</strong>�����б�ҳɸѡ����ɸѡ���ݶ������������۵����أ����ջ�0Ϊ�������۵�����</td>
                <td><?=form_input('modcfg[list_filter_li_collapse_num]',$modcfg['list_filter_li_collapse_num'], 'txtbox5')?></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>���ۺ�ҳ��ȱʡ��ʾģʽ:</strong>�����������ۺ�ҳ��Ĭ�ϵ���ʾģʽ��Ĭ��Ϊͼ��ģʽ</td>
                <td><?=form_select('modcfg[item_album_mode]',array('normal'=>'ͼ��ģʽ','waterfall'=>'�ٲ���'),$modcfg['item_album_mode'])?></td>
            </tr>
            
        </table>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" id="config4" style="display:none;">
            <tr>
                <td width="40%" class="altbg1" valign="top">
                    <strong>�������ҳ��:</strong>
                    �����������ҳ��
                    <p>���ñ�ǩ��
                    <span title="��վ����" class="font_1">{site_name}</span>
                    <span title="��վȫ��keywords" class="font_1">{site_keywords}</span>
                    <span title="��վȫ��description" class="font_1">{site_description}</span>
                    <span title="��ǰ��������" class="font_1">{city_name}</span>
                    <span title="ģ������" class="font_1">{module_name}</span>
                    </p>
                </td>
                <td width="*">
                    <p><input type="text" name="modcfg[seo_category_title]" value="<?=$modcfg['seo_category_title']?>" class="txtbox" /> title </div>
                    <p><input type="text" name="modcfg[seo_category_keywords]" value="<?=$modcfg['seo_category_keywords']?>" class="txtbox" /> keywords </p>
                    <p><input type="text" name="modcfg[seo_category_description]" value="<?=$modcfg['seo_category_description']?>" class="txtbox" /> description</p>
                </td>
            </tr>
            <tr>
                <td class="altbg1" valign="top">
                    <strong>�����б�ҳ:</strong>
                    <p>���ñ�ǩ��
                    <span title="��վ����" class="font_1">{site_name}</span>
                    <span title="��վȫ��keywords" class="font_1">{site_keywords}</span>
                    <span title="��վȫ��description" class="font_1">{site_description}</span>
                    <span title="��ǰ��������" class="font_1">{city_name}</span>
                    <span title="ģ������" class="font_1">{module_name}</span>
                    <span title="����������" class="font_1">{root_category_name}</span>
                    <span title="��ǰ��������" class="font_1">{current_category_name}</span>
                    <span title="�ϼ���������" class="font_1">{root_area_name}</span>
                    <span title="��ǰ��������" class="font_1">{area_name}</span>
                    <span title="��ǰ��ҳ���" class="font_1">{page}</span>
                    <span title="����������������ṩ�� Meta Keywords" class="font_1">{root_category_keywords}</span>
                    <span title="����������������ṩ�� Meta Description" class="font_1">{root_category_description}</span>
                    </p>
                </td>
                <td>
                    <p><input type="text" name="modcfg[seo_list_title]" value="<?=$modcfg['seo_list_title']?>" class="txtbox" /> title </div>
                    <p><input type="text" name="modcfg[seo_list_keywords]" value="<?=$modcfg['seo_list_keywords']?>" class="txtbox" /> keywords </p>
                    <p><input type="text" name="modcfg[seo_list_description]" value="<?=$modcfg['seo_list_description']?>" class="txtbox" /> description</p>
                </td>
            </tr>
            <tr>
                <td class="altbg1" valign="top">
                    <strong>��������ҳ:</strong>
                    <p>���ñ�ǩ��
                    <span title="��վ����" class="font_1">{site_name}</span>
                    <span title="��վȫ��keywords" class="font_1">{site_keywords}</span>
                    <span title="��վȫ��description" class="font_1">{site_description}</span>
                    <span title="��ǰ��������" class="font_1">{city_name}</span>
                    <span title="ģ������" class="font_1">{module_name}</span>
                    <span title="����������" class="font_1">{root_category_name}</span>
                    <span title="��ǰ��������" class="font_1">{current_category_name}</span>
                    <span title="��������" class="font_1">{name}</span>
                    <span title="������" class="font_1">{description}</span>
                    <span title="������ϸ����ǰ100������" class="font_1">{content}</span>
                    </p>
                </td>
                <td>
                    <p><input type="text" name="modcfg[seo_detail_title]" value="<?=$modcfg['seo_detail_title']?>" class="txtbox" /> title </div>
                    <p><input type="text" name="modcfg[seo_detail_keywords]" value="<?=$modcfg['seo_detail_keywords']?>" class="txtbox" /> keywords </p>
                    <p><input type="text" name="modcfg[seo_detail_description]" value="<?=$modcfg['seo_detail_description']?>" class="txtbox" /> description</p>
                </td>
            </tr>
            <tr>
                <td class="altbg1" valign="top">
                    <strong>����б�ҳ:</strong>
                    <p>���ñ�ǩ��
                    <span title="��վ����" class="font_1">{site_name}</span>
                    <span title="��վȫ��keywords" class="font_1">{site_keywords}</span>
                    <span title="��վȫ��description" class="font_1">{site_description}</span>
                    <span title="��ǰ��������" class="font_1">{city_name}</span>
                    <span title="ģ������" class="font_1">{module_name}</span>
                    <span title="����������" class="font_1">{root_category_name}</span>
                    <span title="��ǰ��������" class="font_1">{current_category_name}</span>
                    </p>
                </td>
                <td>
                    <p><input type="text" name="modcfg[seo_album_title]" value="<?=$modcfg['seo_album_title']?>" class="txtbox" /> title </div>
                    <p><input type="text" name="modcfg[seo_album_keywords]" value="<?=$modcfg['seo_album_keywords']?>" class="txtbox" /> keywords </p>
                    <p><input type="text" name="modcfg[seo_album_description]" value="<?=$modcfg['seo_album_description']?>" class="txtbox" /> description</p>
                </td>
            </tr>
            <tr>
                <td class="altbg1" valign="top">
                    <strong>�������а�ҳ��:</strong>
                    <p>���ñ�ǩ��
                    <span title="��վ����" class="font_1">{site_name}</span>
                    <span title="��վȫ��keywords" class="font_1">{site_keywords}</span>
                    <span title="��վȫ��description" class="font_1">{site_description}</span>
                    <span title="��ǰ��������" class="font_1">{city_name}</span>
                    <span title="ģ������" class="font_1">{module_name}</span>
                    <span title="��ǰ��������" class="font_1">{current_category_name}</span>
                    </p>
                </td>
                <td>
                    <p><input type="text" name="modcfg[seo_tops_title]" value="<?=$modcfg['seo_tops_title']?>" class="txtbox" /> title </div>
                    <p><input type="text" name="modcfg[seo_tops_keywords]" value="<?=$modcfg['seo_tops_keywords']?>" class="txtbox" /> keywords </p>
                    <p><input type="text" name="modcfg[seo_tops_description]" value="<?=$modcfg['seo_tops_description']?>" class="txtbox" /> description</p>
                </td>
            </tr>
            <tr>
                <td class="altbg1" valign="top">
                    <strong>�����ͼҳ��:</strong>
                    <p>���ñ�ǩ��
                    <span title="��վ����" class="font_1">{site_name}</span>
                    <span title="��վȫ��keywords" class="font_1">{site_keywords}</span>
                    <span title="��վȫ��description" class="font_1">{site_description}</span>
                    <span title="��ǰ��������" class="font_1">{city_name}</span>
                    <span title="ģ������" class="font_1">{module_name}</span>
                    <span title="��ǰ����" class="font_1">{area_name}</span>
                    </p>
                </td>
                <td>
                    <p><input type="text" name="modcfg[seo_map_title]" value="<?=$modcfg['seo_map_title']?>" class="txtbox" /> title </div>
                    <p><input type="text" name="modcfg[seo_map_keywords]" value="<?=$modcfg['seo_map_keywords']?>" class="txtbox" /> keywords </p>
                    <p><input type="text" name="modcfg[seo_map_description]" value="<?=$modcfg['seo_map_description']?>" class="txtbox" /> description</p>
                </td>
            </tr>
        </table>
    </div>
    <center><?=form_submit('dosubmit',lang('admincp_submit'),'yes','btn')?></center>
<?=form_end()?>
</div>
<script type="text/javascript">
function get_sessionkey () {
	var appkey = $('#taoke_appkey').val();
	if(!appkey) {
		alert('�Բ�����δ����App Key���������ã�');
		return;
	}
	window.open("http://container.api.taobao.com/container?appkey="+appkey);
}
</script>