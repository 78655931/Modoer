<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<script type="text/javascript" src="./data/cachefiles/article_category.js?r=<?=$MOD[jscache_flag]?>"></script>
<script type="text/javascript" src="./static/javascript/article.js"></script>
<script type="text/javascript" src="./static/javascript/item.js"></script>
<script type="text/javascript" src="./static/javascript/My97DatePicker/WdatePicker.js"></script>
<script type="text/javascript">
function article_save_succeed (data) {
    document.location = "<?=$forward?>";
}

window.onload = function() {
    <?if(!$detail['catid']):?>article_select_category(document.getElementById('pid'),'catid');<?endif;?>
}

function delete_article_thumb() {
    $('#article_thumb_input').val('N');
    $('#article_thumb').html('无图');
}
</script>
<style type="text/css">
.altbg1 { text-align:right; }
#article_thumb { height:120px; width:120px; text-align:center; margin:5px auto 20px; }
#article_thumb img { max-height:120px; max-width:120px; 
    _width: expression(this.width > 120 ? 120 : true); _height: expression(this.height > 120 ? 120 : true); }
</style>
<div id="body">
<form method="post" action="<?=cpurl($module,$act,'save')?>" name="postform" id="postform">
    <div class="space">
        <div class="subtitle">增加/编辑文章</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" id="config1" mousemove='N'>
            <tr>
                <td class="altbg1" width="120"><span class="font_1">*</span>文章名称:</td>
                <td width="*"><input type="text" name="subject" class="txtbox" value="<?=$detail['subject']?>" /></td>
                <td width="150" rowspan="6" style="border-left:1px solid #BBDCF1;text-align:center;">
                    <div id="article_thumb">
                        <?if($detail['thumb']):?><a href="<?=$detail['picture']?>" target="_blank"><img src="<?=$detail['thumb']?>" /></a><?else:?>无图<?endif;?>
                    </div>
                    <button type="button" class="btn2" onclick="article_upload_thumb_ui('article_thumb');">上传封面</button>
                    <button type="button" class="btn2" onclick="delete_article_thumb();">删除</button>
                    <input type="hidden" name="picture" id="article_thumb_input" value="">
                </td>
            </tr>
            <tr>
                <td class="altbg1"><span class="font_1">*</span>文章分类:</td>
                <td>
                    <select name="pid" id="pid" style="width:auto;" onchange="article_select_category(this,'catid');">
                        <?=form_article_category(0,$detail['catid']);?>
                    </select>&nbsp;
                    <select name="catid" id="catid" style="width:200px;">
                        <?=$detail['catid']?form_article_category($detail['catid']):''?>
                    </select>
                    <span class="font_2">必须选择 2 级分类</span>
                </td>
            </tr>
            <tr>
                <td class="altbg1"><span class="font_1">*</span>关联城市:</td>
                <td>
                    <select name="city_id" id="city_id">
                        <?=form_city($detail['city_id'], TRUE, !$admin->is_founder);?>
                    </select>
                    <span class="font_2">选择“全局”表示显示在所有城市分站内</span>
                </td>
            </tr>
            <tr>
                <td class="altbg1"><span class="font_1">*</span>审核文章状态:</td>
                <td>
                    <?=form_bool('status',!$detail || $detail['status'] ? 1 : 0)?>
                </td>
            </tr>
            <tr>
                <td class="altbg1"><span class="font_1">*</span>关闭文章评论:</td>
                <td><?=form_bool('closed_comment',$detail['closed_comment'])?></td>
            </tr>
            <tr>
                <td class="altbg1">自定义属性:</td>
                <td>
                    <input type="text" name="att" id="att" class="txtbox4" value="<?=$detail['att']?>" />
                    <select id="att_select" onchange="$('#att').val($('#att_select').val());">
                        <option value="0">=选择属性=</option>
                        <?=form_article_att($detail['att'])?>
                    </select>
                    <div><span class="font_2">由 0-255 数字组成，本功能可实现选择性得在前台展示</span></div>
                </td>
            </tr>
            <tr>
                <td class="altbg1">关联主题:</td>
                <td colspan="2">
					<div id="subject_search"></div>
					<script type="text/javascript">
						$('#subject_search').item_subject_search({
							input_class:'txtbox3',
							btn_class:'btn2',
							result_css:'item_search_result',
							<?if($detail['sid']):?>sid:'<?=$detail[sid]?>',<?endif;?>
							hide_keyword:true,
                            multi:true
						});
					</script>
                    <div><span class="font_2">搜索主题关键字，选择文章相关联的主题，可多选</span></div>
                </td>
            </tr>
            <tr>
                <td class="altbg1"><span class="font_1">*</span>文章作者:</td>
                <td colspan="2"><input type="text" name="author" class="txtbox3" value="<?=$detail['author']?$detail['author']:$admin->adminname?>" /></td>
            </tr>
            <tr>
                <td class="altbg1">文章来源:</td>
                <td colspan="2"><input type="text" name="copyfrom" class="txtbox3" value="<?=$detail['copyfrom']?>" /></td>
            </tr>
            <tr>
                <td class="altbg1">关键字:</td>
                <td colspan="2">
                    <input type="text" name="keywords" class="txtbox2" value="<?=$detail['keywords']?>" />
                    <span class="font_2">多个关键字，请用逗号","分隔</span>
                </td>
            </tr>
            <tr>
                <td class="altbg1">发布时间:</td>
                <td colspan="2">
                    <input type="text" name="dateline" class="txtbox3" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm'})" value="<?=$detail['dateline']>0?date('Y-m-d H:i',$detail['dateline']):''?>" />
                    <span class="font_2">新建时留空标签当前时间</span>
                </td>
            </tr>
            <tr>
                <td class="altbg1" valign="top">文章简介:</td>
                <td colspan="2">
                    <textarea name="introduce" style="width:600px;height:80px;"><?=$detail['introduce']?></textarea>
                    <div><span class="font_2">字数请控制在 255 字符内；留空时，系统将自动截取文章开头部分文字，作为内容介绍；不支持HTML代码显示</span></div>
                </td>
            </tr>
            <tr>
                <td class="altbg1" valign="top"><span class="font_1">*</span>文章内容:</td>
                <td colspan="2">
                    <?=$edit_html?>
                    <div><span class="font_2">文章内容字符数量限制：<?=$MOD[content_min]?>-<?=$MOD[content_max]?>，修改字符数量显示，请到模块配置页面修改</span></div>
                </td>
            </tr>
        </table>
    </div>
    <?if($op=='edit'):?>
    <input type="hidden" name="articleid" value="<?=$detail['articleid']?>" />
    <?endif;?>
    <input type="hidden" name="do" value="<?=$op?>" />
    <input type="hidden" name="forward" value="<?=$forward?>" />
    <center>
        <button type="button" class="btn" onclick="KE.util.setData('content');ajaxPost('postform', '', 'article_save_succeed');">提交</button>
        <button type="button" class="btn" onclick="document.location='<?=$forward?>';">返回</button>
    </center>
</form>
</div>