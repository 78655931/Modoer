<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<?=form_begin(cpurl($module,$act,$op))?>
    <div class="space">
        <div class="subtitle">��վ����</div>
        <ul class="cptab">
            <li class="selected" id="btn_config1"><a href="#" onclick="tabSelect(1,'config');" onfocus="this.blur()">��Ҫ����</a></li>
            <li id="btn_config2"><a href="#" onclick="tabSelect(2,'config');" onfocus="this.blur()">ͼƬ�ϴ�</a></li>
            <li id="btn_config3"><a href="#" onclick="tabSelect(3,'config');" onfocus="this.blur()">JS����</a></li>
        </ul>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" id="config1">
            <tr>
                <td class="altbg1"><strong>����з�վ����ģʽ:</strong>���ö���з�վ����ģʽ</span></td>
                <td><?=form_radio('setting[city_sldomain]', array('0'=>'����','1'=>'��������','2'=>'����Ŀ¼(������Ŀ¼��ʽURL��дʱ��Ч)'),$config['city_sldomain']?$config['city_sldomain']:0)?></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>����Ҫ��վ����Ŀ¼ģʽ��ҳ��:</strong>����Ҫ���з�վĿ¼ģʽ��ҳ�棬���������ı��������Ӳ�ʹ�ó���Ŀ¼��ҳ�棻����һЩģ�������ҳ����Ϊ���Ǿ���Ψһ�ԣ�����Ҫͨ���������Ŀ¼��������������ܵ����⣬���磺��������ҳ(article/detail)����������ҳ(item/detail)�ȡ� <br />��ʽ��ģ���ʶ/ҳ�����ƣ�ÿ��һ��</td>
                <td><?=form_textarea('setting[citypath_without]', $config['citypath_without'], 5,50,'txtarea3')?></td>
            </tr>
            <tr>
                <td width="45%" class="altbg1"><strong>ҳ�� Gzip ѹ��:</strong>��ҳ�������� gzip ѹ�����䣬���Լӿ촫���ٶȡ�</td>
                <td width="*"><?=form_bool('setting[gzipcompress]', $config['gzipcompress'])?></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>��ʾҳ����Ϣ:</strong>��ҳ��ײ�����ʾ���ݿ��ѯ������ҳ��ִ��ʱ�䡣<br /><span class="font_1">һ����ҳ��������"��ҳ����"����ʱ�������ܽ�ʧЧ��</span></td>
                <td><?=form_bool('setting[scriptinfo]', $config['scriptinfo'])?></td>
            </tr>
            <?if($_G['charset']!='UTF-8'):?>
            <tr>
                <td class="altbg1"><strong>UTF-8��ʽ��URL:</strong>���������α��̬���������������������ߵ�ͼ���������Ƴ����������⣬�뿪���˹��ܣ�û������ģ��벻Ҫ�򿪡�</span></span></td>
                <td><?=form_bool('setting[utf8url]', $config['utf8url'])?></td>
            </tr>
            <?endif;?>
			<tr>
                <td class="altbg1"><strong>ģ���׺:</strong>�Զ����׺�����ɷ�ģ�������²⣬Ĭ��Ϊ.htm��������Ϻ�׺��š�<br /><span class="font_1">��ȷ��ģ��Ŀ¼�µĸ����ļ���׺��˴����ñ���һ�¡�</font></td>
                <td><input type="text" name="setting[tplext]" value="<?=$config['tplext']?>" class="txtbox4" /></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>��ͼAPI�ĵ�ַ:</strong>��д���ݲ�ͬ�ĵ�ͼ���ṩ��API���ܵ�JS��ַ��<br /><span class="font_1">��ʹ�ö���������վʱ����Щ��ͼ�ӿ���Ҫ�Ը���������������key���뽲���뵽�Ķ�������api��ַ��д�����������С�</font></td>
                <td><input type="text" name="setting[mapapi]" value="<?=$config['mapapi']?>" class="txtbox" /></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>��ͼ��ʶ:</strong>���ݲ�ͬ�ĵ�ͼ��ʶ��ϵͳ�����벻ͬ�ĵ�ͼjs��ǰ���ǵ�ͼjs���ڣ���Ĭ���Ǳ�ʾΪ51ditu</td>
                <td><input type="text" name="setting[mapflag]" value="<?=$config['mapflag']?>" class="txtbox3" /></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>��ͼAPI�ı���:</strong>Ĭ�ϵ�ͼ����Ҫ���ñ��룬Google��ͼ��Ҫ����ΪUTF-8</td>
                <td><input type="text" name="setting[mapapi_charset]" value="<?=$config['mapapi_charset']?>" class="txtbox4" /></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>��ͼĬ�����ŵȼ�:</strong>��ͬ�ĵ�ͼ���Լ������ŵȼ���һ����1-15֮ǰ����������дһ�����ֺ�ǰ̨���ԣ������Լ������ֵ��ֻ����д����</td>
                <td><input type="text" name="setting[map_view_level]" value="<?=$config['map_view_level']?>" class="txtbox5" /></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>�༭���ϴ�����ʹ�����·��:</strong>��WEB�༭���У�ʹ���ϴ�ͼƬ���ļ����ϴ�����ʱ�����ϴ���ʹ�����·������ʾͼƬ���ļ��ȡ����·���ĺô����ڿ�����Ը�������ʱ���ϴ���ͼƬ���ļ����Զ�ָ������������ʹ�����·��ʱ���������ͼƬ���ϴ��ļ���ʧЧ����Ϊȫ��·����ֱ��д��������</td>
                <td><?=form_bool('setting[editor_relativeurl]', $config['editor_relativeurl'])?><div><span class="font_2">��������վ��һ���������߶�������(����:http://demo.modoer.com)������򿪣���������վ�Ƿ��ڶ���Ŀ¼��(����:http://www.modoer.com/modoer)����رձ����ܣ���Ϊ��Ӱ��������վ��ͼƬ���ϴ��ļ�����ʾ��</span></div></td>
            </tr>
        </table>

        <table class="maintable" border="0" cellspacing="0" cellpadding="0" id="config2" style="display:none;">
            <tr>
                <td class="altbg1" width="45%"><strong>ͼƬ�ļ���ŷ�ʽ:</strong>����ͼƬ�ļ����ļ��д�ŷ�ʽ��Ĭ�ϰ��´�š�</td>
                <td width="*">
                    <?php !$config['picture_dir_mod'] && $config['picture_dir_mod']='MONTH'; ?>
                    <?=form_radio('setting[picture_dir_mod]',array('MONTH'=>'��','WEEK'=>'��','DAY'=>'��'),$config['picture_dir_mod'])?>
                </td>
            </tr>
            <tr>
                <td class="altbg1" width="45%"><strong>Ĭ���ϴ��ߴ�����:</strong>ϵͳĬ��ͼƬ����ϴ��ߴ磬��λ��KB</td>
                <td width="*"><input type="text" name="setting[picture_upload_size]" value="<?=$config['picture_upload_size']?>" class="txtbox4" /> KB</td>
            </tr>
            <tr>
                <td class="altbg1"><strong>Ĭ������ͼƬ����:</strong>��ͬ�������á��ո񡿷ָĬ��Ϊ��jpg jpeg png gif</td>
                <td><input type="text" name="setting[picture_ext]" value="<?=$config['picture_ext']?>" class="txtbox" /></td>
            </tr>
            <tr>
                <td class="altbg1" width="45%"><strong>����ͼƬ���ߴ�:</strong>�Զ���С�û��ϴ���ͼƬ���ڵ�ǰ���õ����ߴ磬Ĭ�ϣ�800*600��</td>
                <td width="*"><?=form_input('setting[picture_max_width]',$config['picture_max_width']?$config['picture_max_width']:800,'txtbox5')?>&nbsp;*&nbsp;<?=form_input('setting[picture_max_height]',$config['picture_max_height']?$config['picture_max_height']:600,'txtbox5')?></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>Ĭ��ͼƬˮӡ����:</strong>��Ĭ���ϴ�ͼƬʱ����ͼƬ����ˮӡ��֧��PNG����ˮӡ��ˮӡͼƬ�����./static/images/watermark.png�������滻ˮӡ�ļ���ʵ�ֲ�ͬ��ˮӡЧ����ˮӡ������ҪGD��֧�֡�</td>
                <td><?=form_bool('setting[watermark]',$config['watermark'])?></td>
            </tr>
            <tr>
                <td class="altbg1" width="45%"><strong>����ˮӡ�ַ�:</strong>��дˮӡ���֣�����ʹ��������ʽˮӡ��ʱ��ʹ�����ģ�����Ҫ�ϴ�֧�ֵ�����simsun.ttc�����ϴ�Modoer�ļ��� static/images/fonts��simsun.ttc���������Windowsϵͳ����������<a href="http://www.google.com.hk/search?hl=zh-CN&source=hp&q=simsun.ttc" target="_blank">Ҳ����ͨ�������������ص�</a>��<span class="font_1">���˹������֣����ֳ��ȳ���ͼƬ����ʱ�����޷�����ˮӡ</span></td>
                <td width="*"><input type="text" name="setting[watermark_text]" value="<?=$config['watermark_text']?>" class="txtbox2" /></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>Ĭ��ˮӡλ��:</strong>����ˮӡ��ͼƬ�ϵ�λ�ã�Ĭ���������½�</td>
                <td><?=form_radio('setting[watermark_postion]', array(0=>'���',1=>'���Ͻ�',2=>'���Ͻ�',3=>'���½�',4=>'���½�',5=>'����',6=>'�ײ�����'), $config['watermark_postion'],'','&nbsp;')?></td>
            </tr>
            <tr>
                <td class="altbg1" width="45%"><strong>����ͼ��������:</strong>��������ͼ������������ֵԽ������Խ�ߣ�ͬʱռ�ÿռ�Ҳ��Ĭ����80�����100��<span class="font_1">����������Ϊ100����������ͼ���ļ���С�����ԭͼ��</span></td>
                <td width="*"><input type="text" name="setting[picture_createthumb_level]" value="<?=$config['picture_createthumb_level']?>" class="txtbox4" /></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>����ͼ����ģʽ:</strong>���ߴ�ü�����ͼƬ��С���ü����������ֲü���ȷ��ͼƬ��С�����õĸ߿���ȣ��ȱ�����С����͸�ֻȡ����1�������ѡ�񡣽���(Ĭ��)ѡ�񰴳ߴ�ü���</td>
                <td><?=form_radio('setting[picture_createthumb_mod]', array('���ߴ�ü�','�ȱ�����С'), $config['picture_createthumb_mod'],'','&nbsp;')?></td>
            </tr>
        </table>

        <table class="maintable" border="0" cellspacing="0" cellpadding="0" id="config3" style="display:none;">
            <tr>
                <td class="altbg1" class="altbg1" width="45%"><strong>����JS���ù���:</strong>����ϵͳ��JSԶ�̵��ù��ܡ�</td>
                <td width="*"><?=form_bool('setting[jstransfer]', $config['jstransfer'])?></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>JS��·����:</strong>ֻ�����б��е������ſ���ʹ��JS���ù��ܣ�ÿ������һ�У�������� http:// ���������������ݣ�����Ϊ��������·�����κ���վ���ɵ���.���Ƕ���վ���û�������ķ�����������</td>
                <td><textarea name="setting[jsaccess]" rows="6" cols="40" class="txtarea"><?=$config['jsaccess']?></textarea></td>
            </tr>
        </table>
    </div>
    <center><?=form_submit('dosubmit',lang('admincp_submit'),'yes','btn')?></center>
<?=form_end()?>
</div>