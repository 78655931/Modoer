<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<?=form_begin(cpurl($module,$act,$op))?>
    <div class="space">
        <div class="subtitle">���������Ż�</div>
        <ul class="cptab">
            <li class="selected" id="btn_config1"><a href="#" onclick="tabSelect(1,'config');" onfocus="this.blur()">α��̬����</a></li>
            <li id="btn_config2"><a href="#" onclick="tabSelect(2,'config');" onfocus="this.blur()">ҳ���Ż�</a></li>
        </ul>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" id="config1">
            <tr>
                <td width="50%" class="altbg1"><strong>���� URL ��д:</strong>URL ��д���������������ץȡ��������΢���ӷ�����������<br /><span class="font_1">URL ��д��������ģ����ʹ�� url ģ���ǩ��<a href="http://www.modoer.com/article.php?aid=38" target="_blank">��ϸʹ����ο���ؽ̳�</a></span></td>
                <td width="*">
                    <?=form_bool('setting[rewrite]', $config['rewrite'])?>
                </td>
            </tr>
            <tr>
                <td class="altbg1"><strong>URL ��д��ʽ:</strong>α��̬������ҳ�ļ���HTMLΪ��׺��Ŀ¼��ʽ��ģ�����ļ�Ŀ¼��ʽ��<br /><span class="font_1">����IIS6�û�ֻʹ�� α��̬ ��ʽ</span></td>
                <td>
                    <?=form_radio('setting[rewrite_mod]', array('html'=>'α��̬','pathinfo'=>'Ŀ¼��ʽ'), $config['rewrite_mod'])?>
                </td>
            </tr>
            <tr>
                <td class="altbg1"><strong>URL ��·�����õ�ģʽ��һ��ʱ�Զ� 301 ��ת:</strong>���URL��·��ģʽ�뵱ǰ�趨��URL��д��һ��ʱ�����磺������α��̬ģʽURL������·URL��Ŀ¼��ʽ�ģ����Ƿ�ʹ��301��ת�ĵ���ȷ��URL�������ڽ�վ��;����URL��д��ʽ���û�(���������Ѿ���¼֮ǰ���õ�URLʱ)<br /><span class="font_1">�˹�����Ч����Ҫrewrite�����ļ�ͬʱ����URL��д�����ֹ��򣬷���ϵͳ���޷����URL��·��������Apache�û�ʹ��</span></td>
                <td>
                    <?=form_bool('setting[rewrite_location]', $config['rewrite_location'])?>
                </td>
            </tr>
            <tr>
                <td class="altbg1"><strong>���� URL �е� index.php :</strong><span class="font_1">��������Ҫ�� Web ������֧��Rewrite���ܣ�</span>ͬʱ��Ҫα��̬������ص��������С�</td>
                <td>
                    <?=form_bool('setting[rewrite_hide_index]', $config['rewrite_hide_index'])?>
                </td>
            </tr>
            <tr>
                <td class="altbg1"><strong>���� Rewrite ���ļ���:</strong>���Web��������֧�� Rewrite ���ģ��뿪����</td>
                <td>
                    <?=form_bool('setting[rewritecompatible]', $config['rewritecompatible'])?>
                </td>
            </tr>
        </table>

        <table class="maintable" border="0" cellspacing="0" cellpadding="0" id="config2" style="display:none;">
            <tr>
                <td width="50%" class="altbg1"><strong>���⸽����:</strong>��ҳ����ͨ�������������ע���ص㣬�����������ý������ڱ�������վ���Ƶĺ��棬����ж���ؼ��֣������� "|"��","(��������) �ȷ��ŷָ�</td>
                <td width="50%"><input type="text" name="setting[subname]" value="<?=$config['subname']?>" class="txtbox" />
                <br /><span class="font_2">��վ���ⲻҪ̫������ò�Ҫ����30���ַ�����������Թ��������ް���</span></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>����ָ���:</strong>����ͱ��⸽����֮��ķָ���</td>
                <td><input type="text" name="setting[titlesplit]" value="<?=$config['titlesplit']?>" class="txtbox5" /></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>Meta Keywords:</strong>Keywords �������ҳ��ͷ���� Meta ��ǩ�У����ڼ�¼��ҳ��Ĺؼ��֣�����ؼ��ּ����ð�Ƕ��� "," ����</td>
                <td><input type="text" name="setting[meta_keywords]" value="<?=$config['meta_keywords']?>" class="txtbox" /></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>Meta Description:</strong>Description ������ҳ��ͷ���� Meta ��ǩ�У����ڼ�¼��ҳ��ĸ�Ҫ������</td>
                <td><input type="text" name="setting[meta_description]" value="<?=$config['meta_description']?>" class="txtbox" /></td>
            </tr>
            <tr>
                <td class="altbg1" valign="top"><strong>����ͷ����Ϣ:</strong>������ &lt;head&gt;&lt;/head&gt; ����������� HTML ���룬����ʹ�ñ����ã����������գ�����дHTML���룬��Ҫ��д�����֣�������ƻ���ҳ���֡�</td>
                <td><textarea name="setting[headhtml]" rows="5" cols="40" class="txtarea"><?=$config['headhtml']?></textarea></td>
            </tr>
        </table>
    </div>
    <center><?=form_submit('dosubmit',lang('admincp_submit'),'yes','btn')?></center>
<?=form_end()?>
</div>