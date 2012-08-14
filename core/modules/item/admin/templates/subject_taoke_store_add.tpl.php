<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<script type="text/javascript" src="./static/javascript/item.js"></script>
<script type="text/javascript" src="./data/cachefiles/item_category.js?r=<?=$MOD['jscache_flag']?>"></script>
<script type="text/javascript">
loadscript('mdialog');

var current_id = 0;
var save_stop = false;
var save_complete = false;
function show_shop_detail(user_id) {
	if(!user_id) { alert('对不起，未知的用户ID'); return; }
	$.post("<?=cpurl($module,$act,'store_detail')?>", { "user_id":user_id, in_ajax:1 }, function(result) {
		dlgOpen('店铺详情', result, 550, 280);
	});
}
function post_select_catid(ids) {
	if(!checkbox_check(ids)) return;
	var items = new Array();
	for(var i=1;i<=3;i++) {
		var v = $('#category_'+i).val();
		if(v>0) items[i-1] = v;
	}
	if(items.length==0) {
		alert('对不起，您未选择添加主题分类。');
		return false;
	}
	$.post("<?=cpurl($module,$act,'store_linkfield')?>",{ "catid":items.join(','), in_ajax:1 }, function(result) {
		dlgOpen('字段关联', result, 550, 400);
		var x = $('#store_linkfield_id').height();
		//dlgSize(550, x+50);
	});
}
function taoke_save_start() {
	var content = $('<div></div>').attr('id','taoke_store_save');
	var textarea = $('<textarea></textarea>').attr('id','taoke_store_status').
		css({width:'100%', height:'200px', 'overflow-x':'visible'});
	dlgOpen('数据录入',content, 500, 300);
	var bttton1 = $('<button id="btn_start_save" type="button" class="btn">开始导入</button>').click(function() {
		taoke_saveing();
	});
	var bttton2 = $('<button type="button" class="btn">关闭</button>').click(function() {
		dlgClose();
	});
	content.append(textarea).append($('<center style="margin-top:10px;"></center>').
		append(bttton1).append('&nbsp;').append(bttton2));
	current_id = 0;
	textarea.val("请点击开始导入...\r\n");
}

function taoke_saveing() {
	$('#btn_start_save').hide();
	current_id++;
	var checkbox = $('#user_id_'+current_id);
	if(checkbox[0]) {
		if(checkbox.attr('checked')) {
			var info = taoke_get_info(current_id);
			info.in_ajax = 1;
			$.post("<?=cpurl($module,$act,'store_start')?>",
			info, function(result) {
				var s = taoke_parse_result(result);
				taoke_add_status(s.message,s.succeed,$('#title_'+current_id).val());
				if(s.succeed) {
					$('#tr_'+current_id).attr('complete',1);
				} else {
					//$('#tr_'+current_id).addClass('altbg2');
				}
				taoke_saveing();
			});
		} else {
			taoke_saveing();
		}
	} else {
		taoke_save_stop();
	}
}

function taoke_save_stop() {
	taoke_add_status('本次导入完毕...',true);
}

function taoke_get_info(id) {
	var info = {
		'user_id':$('#user_id_'+id).val(),
		'seller_credit':$('#seller_credit_'+id).val(),
		'click_url':$('#click_url_'+id).val(),
		'commission_rate':$('#commission_rate_'+id).val(),
		'total_auction':$('#total_auction_'+id).val(),
		'auction_count':$('#auction_count_'+id).val()
	}
	return info;
}

function taoke_parse_result(result) {
	var s = {succeed:false, message:''}
	data = result.match(/(\{\s+caption:".*",message:".*".*\s*\})/);
	if (result) {
		var mymsg = eval('('+data[0]+')');
		result = mymsg.message;
	}
	if(result.indexOf('ERROR:') > -1) {
		s.succeed = false;
		s.message = result.replace('ERROR:','');
	} else if(mymsg) {
		s.succeed = true;
		s.message = result.replace('SUCCEED:','');
	} else {
		s.succeed = false;
		s.message = result;
	}
	return s;
}

function taoke_add_status(msg,status,title) {
	var textarea = $('#taoke_store_status');
	var message = (status?'√ ':'× ') + msg + (title?('('+title+')'):'');
	textarea.val(textarea.val() + message + "\r\n");
	document.getElementById('taoke_store_status').scrollTop = document.getElementById('taoke_store_status').scrollHeight;
}
</script>
<div id="body">
<form method="get" action="<?=SELF?>">
    <input type="hidden" name="module" value="<?=$module?>" />
    <input type="hidden" name="act" value="<?=$act?>" />
    <input type="hidden" name="op" value="<?=$op?>" />
    <div class="space">
        <div class="subtitle">淘宝客商铺筛选</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td width="100" class="altbg1">店铺类型</td>
                <td width="350">
                    <select name="cid">
                        <option value="0">全部分类</option>
                        <?=taoke_item_root_cats($_GET['cid'])?>
                    </select>
                </td>
                <td width="100" class="altbg1">商铺类型</td>
                <td width="*">
                    <?=form_select('only_mall',array('全部商铺','商城'),$_GET['only_mall'])?>
                </td>
            </tr>
            <tr>
                <td class="altbg1">关键字</td>
                <td>
                    <input type="text" name="keyword" class="txtbox2" value="<?=$_GET['keyword']?>" />
                </td>
                <td class="altbg1">商铺等级</td>
                <td>
                    <input type="text" name="start_credit" class="txtbox4" value="<?=$_GET['start_credit']?>" />
                    -
                    <input type="text" name="end_credit" class="txtbox4" value="<?=$_GET['end_credit']?>" />
                    (0-20)
                </td>
            </tr>
            <tr>
                <td class="altbg1">佣金比率</td>
                <td>
                    <input type="text" name="start_commissionrate" class="txtbox4" value="<?=$_GET['start_commissionrate']?>" />
                    -
                    <input type="text" name="end_commissionrate" class="txtbox4" value="<?=$_GET['end_commissionrate']?>" />
                    %
                </td>
                <td class="altbg1">商品数量</td>
                <td>
                    <input type="text" name="start_auctioncount" class="txtbox4" value="<?=$_GET['start_auctioncount']?>" />
                    -
                    <input type="text" name="end_auctioncount" class="txtbox4" value="<?=$_GET['end_auctioncount']?>" />
                    个
                </td>
            </tr>
            <tr>
                <td class="altbg1">累计推广</td>
                <td>
                    <input type="text" name="start_totalaction" class="txtbox4" value="<?=$_GET['start_totalaction']?>" />
                    -
                    <input type="text" name="end_totalaction" class="txtbox4" value="<?=$_GET['end_totalaction']?>" />
                    个
                </td>
                <td class="altbg1">结果排序</td>
                <td>
                    <select name="offset">
                        <option value="20"<?=$_GET['offset']=='20'?' selected="selected"':''?>>每页显示20个</option>
                        <option value="40"<?=$_GET['offset']=='40'?' selected="selected"':''?>>每页显示40个</option>
                    </select>&nbsp;
                    <button type="submit" value="yes" name="dosubmit" class="btn2">筛选</button>
                </td>
            </tr>
        </table>
    </div>
</form>
<?if($_GET['dosubmit']):?>
<form method="post" name="myform" action="<?=cpurl($module,$act)?>&">
    <div class="space">
        <div class="subtitle">商铺列表</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" id="taoke_result" trmouse="Y">
			<tr class="altbg1">
				<td width="25">选</td>
                <td width="*">标题</td>
                <td width="150">信用等级</td>
                <td width="100">佣金比率</td>
                <td width="100">累计推广量</td>
                <td width="100">商品总数</td>
                <td width="100">编辑</td>
			</tr>
            <?if($data):?>
            <?foreach($data as $k=>$val):?>
			<?$vc=$k+1;?>
            <tr id="tr_<?=$vc?>" complete="0">
                <td><input type="checkbox" name="user_ids[]" id="user_id_<?=$vc?>" value="<?=$val['user_id']?>" /></td>
                <td><a href="<?=$val['click_url']?>" target="_blank"><?=$val['shop_title']?></a></td>
                <td><img src="<?=taoke_item_credit_img($val['seller_credit'])?>" alt="<?=$val['seller_credit']?>" /></td>
                <td><?=$val['commission_rate']?>%</td>
                <td><?=$val['total_auction']?></td>
                <td><?=$val['auction_count']?></td>
                <td><a href="javascript:show_shop_detail(<?=$val['user_id']?>);">详细</a></td>
				<input type="hidden" id="title_<?=$vc?>" value="<?=$val['shop_title']?>" />
				<input type="hidden" id="seller_credit_<?=$vc?>" value="<?=$val['seller_credit']?>" />
				<input type="hidden" id="click_url_<?=$vc?>" value="<?=$val['click_url']?>" />
				<input type="hidden" id="commission_rate_<?=$vc?>" value="<?=$val['commission_rate']?>" />
				<input type="hidden" id="total_auction_<?=$vc?>" value="<?=$val['total_auction']?>" />
				<input type="hidden" id="auction_count_<?=$vc?>" value="<?=$val['auction_count']?>" />
            </tr>
            <?endforeach;?>
			<tr class="altbg1">
				<td colspan="3" class="altbg1">
					<button type="button" onclick="checkbox_checked('user_ids[]');" class="btn2">全选</button>&nbsp;
                    添加到主题分类：
                    <select name="catid[0]" id="category_1" onchange="item_category_select_link(1)">
                        <?=form_item_category_main()?>
                    </select>
                    <select name="catid[1]" id="category_2" onchange="item_category_select_link(2)"></select>
                    <select name="catid[2]" id="category_3"></select>
                    <script type="text/javascript">item_category_select_link(1);</script>
					<button type="button" class="btn2" onclick="post_select_catid('user_ids[]');">添加所选到主题</button>
				</td>
				<td colspan="4" align="right">
					<?=$multipage?>
				</td>
			</tr>
            <?else:?>
            <td colspan="13">暂无信息。(<a href="javascript:location.reload();">如果确定存在，可能是获取内容超时，请点击这里重新加载。</a>)</td>
            <?endif;?>
        </table>
    </div>
</form>
</div>
<?endif;?>