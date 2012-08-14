<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<?=form_begin(cpurl($module,$act))?>
    <div class="space">
        <div class="subtitle"><?=$MOD['name']?>&nbsp;模块配置</div>
        <ul class="cptab">
            <li class="selected" id="btn_config1"><a href="#" onclick="tabSelect(1,'config');" onfocus="this.blur()">功能配置</a></li>
            <li id="btn_config2"><a href="#" onclick="tabSelect(2,'config');" onfocus="this.blur()">显示配置</a></li>
            <li id="btn_config3"><a href="#" onclick="tabSelect(3,'config');" onfocus="this.blur()">点评分数设置</a></li>
            <li id="btn_config4"><a href="#" onclick="tabSelect(4,'config');" onfocus="this.blur()">精华点评设置</a></li>
        </ul>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" id="config1">
            <tr>
                <td class="altbg1"><strong>Meta Keywords:</strong>Keywords 项出现在页面头部的 Meta 标签中，用于记录本页面的关键字，多个关键字间请用半角逗号 "," 隔开</td>
                <td><input type="text" name="modcfg[meta_keywords]" value="<?=$modcfg['meta_keywords']?>" class="txtbox" /></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>Meta Description:</strong>Description 出现在页面头部的 Meta 标签中，用于记录本页面的概要与描述。</td>
                <td><input type="text" name="modcfg[meta_description]" value="<?=$modcfg['meta_description']?>" class="txtbox" /></td>
            </tr>
            <tr>
                <td width="45%" class="altbg1" valign="top"><strong>表单验证码:</strong>开启验证码可减少广告机提交信息，但是也会让会员感到繁琐</td>
                <td>
                    <div>发布点评(会员):<?=form_bool('modcfg[seccode_review]', $modcfg['seccode_review'])?></div>
                    <div>发布点评(游客):<?=form_bool('modcfg[seccode_review_guest]', $modcfg['seccode_review_guest'])?></div>
                </td>
            </tr>
            <tr>
                <td class="altbg1"><strong>点评内容字数限制 </strong>定义点评内容的字符限制</td>
                <td><input type="text" name="modcfg[review_min]" value="<?=$modcfg['review_min']?>" class="txtbox5" /> - <input type="text" name="modcfg[review_max]" value="<?=$modcfg['review_max']?>" class="txtbox5" /></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>回应内容字数限制</strong>定义回应内容字符限制</td>
                <td><input type="text" name="modcfg[respond_min]" value="<?=$modcfg['respond_min']?>" class="txtbox5" /> - <input type="text" name="modcfg[respond_max]" value="<?=$modcfg['respond_max']?>" class="txtbox5" /></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>审核回应内容:</strong>开启审核功能后，未审核的信息将暂时不在前台显示和操作。</td>
                <td>
                    <?=form_bool('modcfg[respondcheck]', $modcfg['respondcheck'])?>
                </td>
            </tr>
            <tr>
                <td class="altbg1">
                    <strong>启用非默认头像点评</strong>用户必须设置一个非默认头像后才能进行点评
                </td>
                <td><?=form_bool('modcfg[avatar_review]', $modcfg['avatar_review'])?></td>
            </tr>
            <tr>
                <td class="altbg1">
                    <strong>兼容空格标签分隔符</strong>兼容1.x中使用空格分类符号，开启后可以使用空格来实现分隔标签. 注意:空格会切断英文短语的标签。
                </td>
                <td><?=form_bool('modcfg[tag_split_sp]', $modcfg['tag_split_sp'])?></td>
            </tr>
            <tr>
                <td class="altbg1">
                    <strong>评分默认项</strong>设置点评表单里点评分数的默认选择分数
                </td>
                <td>
                    <select name="modcfg[default_grade]">
                        <option value="0">留空</option>
                        <?foreach (lang('review_grade_array') as $key => $value):?>
                        <option value="<?=$key?>"<?=($modcfg['default_grade']==$key?' selected="selected"':'') ?>><?=$value?>[<?=$key?>星]</option>
                        <?endforeach;?>
                    </select>
                </td>
            </tr>
        </table>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" id="config2" style="display:none;">
            <tr>
               <td width="45%" class="altbg1"><strong>回应显示数:</strong>回应中每页显示回应数目</td>
                <td><?=form_radio('modcfg[respond_num]',array('5'=>'5条','10'=>'10条','20'=>'20条'),$modcfg['respond_num'])?></td>
            </tr>
            <tr>
               <td class="altbg1"><strong>模块首页随机好差评数量:</strong>显示在点评模块首页的随机好差评，各自的数量，默认分别1条</td>
                <td><input type="text" name="modcfg[index_pk_rand_num]" value="<?=$modcfg['index_pk_rand_num']?>" class="txtbox5" /></td>
            </tr>
            <tr>
               <td class="altbg1"><strong>模块首页随机精华数量:</strong>显示在点评模块首页的随机精华点评,默认分别2条</td>
                <td><input type="text" name="modcfg[index_digst_rand_num]" value="<?=$modcfg['index_digst_rand_num']?>" class="txtbox5" /></td>
            </tr>
            <tr>
               <td class="altbg1"><strong>允许在模块首页显示的分类:</strong>最少1个，最多6个</td>
                <td><?=form_item_category_main_check('modcfg[index_review_pids][]',$modcfg['index_review_pids']);?></td>
            </tr>
            <tr>
               <td class="altbg1"><strong>是否允许在模块首页显示曝光台:</strong>设置是否在模块首页曝光台（差评集合）</td>
                <td><?=form_bool('modcfg[index_show_bad_review]', $modcfg['index_show_bad_review'])?></td>
            </tr>
            <tr>
               <td class="altbg1"><strong>模块首页最新点评和曝光台显示点评的数量:</strong>默认分别5条</td>
                <td><input type="text" name="modcfg[index_review_num]" value="<?=$modcfg['index_review_num']?>" class="txtbox5" /></td>
            </tr>
            <tr>
               <td class="altbg1"><strong>模块首页最新点评和曝光台数据获取方式:</strong>获取点评数据的方式<span class="font_1">点评数据量较多的用户不要使用随机获取供着，这会严重影响数据库效率</span></td>
                <td><?=form_radio('modcfg[index_review_gettype]',array('new'=>'按最新发布排序','rand'=>'随机获取'),$modcfg['index_review_gettype'])?></td>
            </tr>
            <tr>
               <td class="altbg1"><strong>允许在模块首页侧边显示的分类排行榜:</strong>最少1个，最多6个</td>
                <td><?=form_item_category_main_check('modcfg[index_top_pids][]',$modcfg['index_top_pids']);?></td>
            </tr>
        </table>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" id="config3" style="display:none;">
            <tr>
                <td width="45%"  valign="top" class="altbg1"><strong>总分形式：</strong>列表页和详细页面显示主题的各个评分项的数值形式。默认为百分制。</td>
                <td width="*"><?=form_select('modcfg[scoretype]',array('100'=>'百分制','10'=>'十分制','5'=>'五分制'),$modcfg['scoretype'])?></td>
            </tr>
            <tr>
                <td valign="top" class="altbg1"><strong>分数小数点：</strong>各项得分的显示是否显示小数点。</td>
                <td><?=form_select('modcfg[decimalpoint]',array('0'=>'不显示','1'=>'1位','2'=>'2位'),$modcfg['decimalpoint'])?></td>
            </tr>
        </table>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" id="config4" style="display:none;">
            <tr>
                <td width="45%" class="altbg1"><strong>精华点评售价:</strong>设置精华点评的价格和支付积分类型，留空或0表示不启用本功能。</td>
                <td width="*">
                    <input type="text" name="modcfg[digest_price]" value="<?=$modcfg['digest_price']?>" class="txtbox4" />
                    <select name="modcfg[digest_pointtype]">
                        <?=form_member_pointgroup($modcfg['digest_pointtype'])?>
                    </select>
                </td>
            </tr>
            <tr>
                <td class="altbg1"><strong>精华点评作者获利:</strong>设置用户购买精华点评时，点评作者相应得到报酬，填写百分比0-100，不填写或0表示不提供。</td>
                <td><input type="text" name="modcfg[digest_gain]" value="<?=$modcfg['digest_gain']?>" class="txtbox4" />%</td>
            </tr>
        </table>
    </div>
    <center><?=form_submit('dosubmit',lang('admincp_submit'),'yes','btn')?></center>
<?=form_end()?>
</div>