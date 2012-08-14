<?php
    $_G['loader']->helper('form','item');
?>
<table class="subtable" border="0" cellspacing="0" cellpadding="0" width="100%">
    <tr>
        <td class="altbg1"><strong>店主昵称字段：</strong>获取店主商铺下的推广产品</td>
        <td>
            <select name="t_cfg[nick_field]">
                <option value="">==选择店主昵称字段==</option>
				<?=form_item_fields($modelid, $t_cfg['nick_field'], 'text')?>
            </select>
		</td>
    </tr>
    <tr>
        <td class="altbg1"><strong>商品标题关键字：</strong>关联字段搜索或者单独输入关键字<br />
		<span class="font_1">注意:店主昵称搜索和商铺关键字搜索只能二选一使用，两者都设置的，使用商铺标题关键字</span></td>
        <td>
			<input type="radio" name="t_cfg[q_type]" value=""<?if(!$t_cfg['q_type'])echo" checked";?> />不使用商铺标题搜索<br />
            <input type="radio" name="t_cfg[q_type]" value="q_field"<?if($t_cfg['q_type']=='q_field')echo" checked";?> />使用字段值进行搜索
			<select name="t_cfg[q_field]">
                <option value="">==选择搜索字段==</option>
				<?=form_item_fields($modelid, $t_cfg['q_field'], 'text')?>
            </select><br />
			<input type="radio" name="t_cfg[q_type]" value="q_text"<?if($t_cfg['q_type']=='q_text')echo" checked";?> />提供文本框填写关键字
		</td>
    </tr>
    <tr>
        <td class="altbg1" width="45%"><strong>选择商品类目：</strong>选择商品所在类目，仅配合“商品标题关键字”搜索</td>
        <td width="*">
            <select name="t_cfg[cid]">
                <option value="">==选择商品类目==</option>
				<?=taoke_item_root_cats($t_cfg['cid'])?>
            </select>
        </td>
    </tr>
    <tr>
        <td class="altbg1"><strong>显示数量：</strong>获取商铺的数量，默认为10条</td>
        <td><input type="text" class="txtbox4" name="t_cfg[num]" value="<?=$t_cfg['num']>0?$t_cfg['num']:10?>" /></td>
    </tr>
</table>
<?php
function taoke_item_root_cats($select='') {
    global $_G, $MOD;
	
    $TB =& $_G['loader']->lib('taobao');
    $TB->set_appkey($MOD['taoke_appkey'], $MOD['taoke_appsecret'], $MOD['taoke_sessionkey']);
    $TaobaokeData = $TB->set_method('taobao.itemcats.get')
        ->set_param('fields','cid,parent_cid,name')
        ->set_param('parent_cid', '0')
        ->get_data();

    $content = '';
    if($TaobaokeData['item_cats']['item_cat']) {
        $content = '';
        foreach($TaobaokeData['item_cats']['item_cat'] as $v) {
            $selected = $v['cid'] == $select ? ' selected' : '';
            $content .= "\t<option value=\"{$v['cid']}\"$selected>$v[name]</option>\r\n";
        }
    }

    return $content;
}
?>