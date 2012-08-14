<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<?=form_begin(cpurl($module,$act))?>
    <div class="space">
        <div class="subtitle"><?=$MOD['name']?>&nbsp;模块配置</div>
        <ul class="cptab">
            <li class="selected" id="btn_config1"><a href="#" onclick="tabSelect(1,'config');" onfocus="this.blur()">功能配置</a></li>
			<li id="btn_config2"><a href="#" onclick="tabSelect(2,'config');" onfocus="this.blur()">淘宝客设置</a></li>
            <li id="btn_config3"><a href="#" onclick="tabSelect(3,'config');" onfocus="this.blur()">显示配置</a></li>
            <li id="btn_config4"><a href="#" onclick="tabSelect(4,'config');" onfocus="this.blur()">SEO设置</a></li>
        </ul>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" id="config1">
            <tr>
                <td width="45%" class="altbg1"><strong>默认主分类:</strong>在首页或未指名主分类的情况下，默认显示哪个分类的内容；没有可分类时，请再网站管理页面，增加点评主分类</td>
                <td width="*"><select name="modcfg[pid]">
                    <option value="">==选择主分类==</option>
					<?=form_item_category_main($modcfg['pid'])?>
                </select></td>
            </tr>
            <tr>
                <td class="altbg1" valign="top"><strong>启用主题二/三级域名或个性目录:</strong>本功能只能在二级，三级域名中使用，不能在二级目录里使用；同时您的服务器需要多域名的绑定（或泛解析）；</td>
                <td><?=form_radio('modcfg[sldomain]', array(0=>'关闭',1=>'主题二/三级域名',2=>'个性目录',3=>'二者都需要'), $modcfg['sldomain'])?><br /><span class="font_1">打开本功能后，请确定data/config.php文件里$_G['cookiedomain']的值为一级域名（不包括www)，例如modoer.com，否则会员登录会失败；启用个性目录需要开启目录形式的URL改写功能，同时要配置data/rewrite_pathinfo.inc文件，具体设置请到官网查看。</span></td>
            </tr>
            <tr>
                <td class="altbg1" valign="top"><div style="margin-left:20px;"><strong>主题二/三级域名基准:</strong>设置二/三级域名的基准域名，例如想实现shopname.abc.com的二级域名，则基准为abc.com，如果想实现三级域名shopname.shop.abc.com，则基准为shop.abc.com</div></td>
                <td><input type="text" name="modcfg[base_sldomain]" value="<?=$modcfg['base_sldomain']?>" class="txtbox3" /><br /><span class="font_1">本功能只在开启了主题二/三级域名有效，如果开启多城市二级域名功能，请关闭本功能</span></td>
            </tr>
            <tr>
                <td class="altbg1" valign="top"><div style="margin-left:20px;"><strong>主题二/三级域名/个性目录保留项:</strong>可以设置一些预留的名称，以免自己在今后需要使用时候造成访问冲突等问题,多个请用逗号","分隔，系统会禁止使用模块标识名作为域名/个性目录名称。</div></td>
                <td><input type="text" name="modcfg[reserve_sldomain]" value="<?=$modcfg['reserve_sldomain']?>" class="txtbox" /></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>主题风格销售的积分类型:</strong>设置购买主题风格的积分类型，积分类型在会员模块中设置</td>
                <td>
					<select name="modcfg[selltpl_pointtype]">
						<option value="">选择积分类型</option>
						<?=form_member_pointgroup($modcfg['selltpl_pointtype'])?>
					</select>
				</td>
            </tr>
            <tr>
                <td class="altbg1"><div style="margin-left:20px;"><strong>主题风格购买使用期限:</strong>设置主题风格的使用期限，默认为 180 天</td>
                <td>
					<input type="text" name="modcfg[selltpl_useday]" value="<?=$modcfg['selltpl_useday']>0?$modcfg['selltpl_useday']:180?>" class="txtbox4" />&nbsp;天
				</td>
            </tr>
            <tr>
                <td class="altbg1" valign="top"><strong>表单验证码:</strong>开启验证码可减少广告机提交信息，但是也会让会员感到繁琐</td>
                <td>
                    <div>发布主题:<?=form_bool('modcfg[seccode_subject]', $modcfg['seccode_subject'])?></div>
                    <div>发布点评(会员):<?=form_bool('modcfg[seccode_review]', $modcfg['seccode_review'])?></div>
                    <div>发布点评(游客):<?=form_bool('modcfg[seccode_review_guest]', $modcfg['seccode_review_guest'])?></div>
                    <div>发布留言:<?=form_bool('modcfg[seccode_guestbook]', $modcfg['seccode_guestbook'])?></div>
                </td>
            </tr>
            <tr>
                <td class="altbg1"><strong>启用批量上传图片:</strong>打开批量上传后，会员可以一次性最多上传20张图片</td>
                <td><?=form_bool('modcfg[multi_upload_pic]',$modcfg['multi_upload_pic']);?></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>批量上传图片限制:</strong>一次提交最多上传多少张图片，至多20张，至少2张，默认5张</td>
                <td><input type="text" name="modcfg[multi_upload_pic_num]" value="<?=$modcfg['multi_upload_pic_num']?>" class="txtbox5" /></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>点评内容字数限制:</strong>定义点评内容的字符限制</td>
                <td><input type="text" name="modcfg[review_min]" value="<?=$modcfg['review_min']?>" class="txtbox5" /> - <input type="text" name="modcfg[review_max]" value="<?=$modcfg['review_max']?>" class="txtbox5" /></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>留言内容字数限制:</strong>定义留言内容的字符限制</td>
                <td><input type="text" name="modcfg[guestbook_min]" value="<?=$modcfg['guestbook_min']?>" class="txtbox5" /> - <input type="text" name="modcfg[guestbook_max]" value="<?=$modcfg['guestbook_max']?>" class="txtbox5" /></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>回应内容字数限制:</strong>定义回应内容字符限制</td>
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
                    <strong>启用非默认头像点评:</strong>用户必须设置一个非默认头像后才能进行点评
                </td>
                <td><?=form_bool('modcfg[avatar_review]', $modcfg['avatar_review'])?></td>
            </tr>
            <tr>
                <td class="altbg1">
                    <strong>兼容空格标签分隔符:</strong>兼容1.x中使用空格分类符号，开启后可以使用空格来实现分隔标签. 注意:空格会切断英文短语的标签。
                </td>
                <td><?=form_bool('modcfg[tag_split_sp]', $modcfg['tag_split_sp'])?></td>
            </tr>
            <tr>
                <td class="altbg1">
                    <strong>关闭搜索独条结果的跳转功能</strong>当使用搜索主题功能时，如遇到只有1条结果时，程序自动会跳转到该条主题内容页，如果设置关闭，程序将不再跳转。
                </td>
                <td><?=form_bool('modcfg[search_location]', $modcfg['search_location'])?></td>
            </tr>
            <tr>
                <td class="altbg1">
                    <strong>开启相册评论功能:</strong>游客在浏览主题相册内容时，可以对相册进行评论留言。
                </td>
                <td><?=form_bool('modcfg[album_comment]', $modcfg['album_comment'])?></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>异步加载淘宝客数据:</strong>淘宝客数据为即时通过淘宝客API获取，网络问题会延长整个页面的加载速度或页面加载失败，影响用户体验，打开本功能后，将在页面加载完毕后再加载淘宝客数据，建议打开此功能；本功能能仅在使用淘宝客功能后有效。</td>
                <td><?=form_bool('modcfg[ajax_taoke]', $modcfg['ajax_taoke'])?></td>
            </tr>
        </table>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" id="config2" style="display:none;">
            <tr>
                <td width="45%" class="altbg1"><strong>淘宝开发平台应用App Key:</strong>注册淘宝开放平台(open.taobao.com)，进入合作伙伴后台，创建一个应用，获取App Key</td>
                <td width="*"><input type="text" name="modcfg[taoke_appkey]" id="taoke_appkey" value="<?=$modcfg['taoke_appkey']?>" class="txtbox2" /></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>淘宝开发平台应用App Secret:</strong>与App Key同时获得</td>
                <td><input type="text" name="modcfg[taoke_appsecret]" value="<?=$modcfg['taoke_appsecret']?>" class="txtbox2" /></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>淘宝开发平台应用App SessionKey:</strong>请先填写App key；如果您修改了上面的App Key的值后，请重新获取SessionKey。</td>
                <td>
						<input type="text" name="modcfg[taoke_sessionkey]" value="<?=$modcfg['taoke_sessionkey']?>" class="txtbox2" />
						<a href="javascript:get_sessionkey();" target="_blank">请点击这里获取sessionkey</a>
				</td>
            </tr>
            <tr>
                <td class="altbg1"><strong>淘宝用户昵称：</strong>指的是淘宝的会员登录名.如果昵称错误,那么客户就收不到佣金；当推广的商品成功后，佣金会打入此输入的淘宝昵称的账户。具体的信息可以登入阿里妈妈(www.alimama.com)查看</td>
                <td><input type="text" name="modcfg[taoke_nick]" value="<?=$modcfg['taoke_nick']?>" class="txtbox2" /></td>
            </tr>
		<table class="maintable" border="0" cellspacing="0" cellpadding="0" id="config3" style="display:none;">
            <tr>
                <td width="45%" class="altbg1"><strong>图片尺寸:</strong>上传点评对象的图片时，限制期最大尺寸，格式为：宽 x 高；默认：200 x 150</td>
                <td width="*"><input type="text" name="modcfg[pic_width]" value="<?=$modcfg['pic_width']?>" class="txtbox5" />&nbsp;x&nbsp;<input type="text" name="modcfg[pic_height]" value="<?=$modcfg['pic_height']?>" class="txtbox5" /></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>视频播放器尺寸:</strong>在商铺页面显示视频的尺寸，格式为：宽 x 高；默认：250 x 200</td>
                <td><input type="text" name="modcfg[video_width]" value="<?=$modcfg['video_width']?>" class="txtbox5" />&nbsp;x&nbsp;<input type="text" name="modcfg[video_height]" value="<?=$modcfg['video_height']?>" class="txtbox5" /></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>列表页显示主题量:</strong>列表页，搜索页面中每页显示点评对象数量</td>
                <td><?=form_input('modcfg[list_num]', $modcfg['list_num'], 'txtbox4')?></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>内容页面显示点评数:</strong>内容页面中每页显示点评数目</td>
                <td><?=form_input('modcfg[review_num]', $modcfg['review_num'], 'txtbox4')?></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>内容页面显示最有用好差评:</strong>在内容页里显示出用户鲜花最多的好差评信息</td>
                <td><?=form_bool('modcfg[show_detail_vs_review]', (bool)$modcfg['show_detail_vs_review'])?></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>分类排序:</strong>调整分类排列顺序方式</td>
                <td><?=form_radio('modcfg[classorder]',array('total'=>'按分类中的数量','order'=>'按分类顺序'),$modcfg['classorder'])?></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>主题封面设置:</strong>点评对象的封面显示</td>
                <td><?=form_select('modcfg[thumb]',array('1'=>'最新上传的图片','2'=>'自适应(无图时选最新)','3'=>'手动选择图片'),$modcfg['thumb'])?></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>内容页显示缩略图:</strong>在内容页面显示主题的缩略图列表</td>
                <td><?=form_bool('modcfg[show_thumb]', $modcfg['show_thumb'])?></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>关闭内容页的数据统计功能:</strong>在主题内容页面，会显示当前主题的具体一些统计信息，使用功能后将关闭显示统计信息。</td>
                <td><?=form_bool('modcfg[close_detail_total]', $modcfg['close_detail_total'])?></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>列表页筛选项内容折叠:</strong>设置列表页筛选项内筛选内容多少数量进行折叠隐藏，留空或0为不进行折叠隐藏</td>
                <td><?=form_input('modcfg[list_filter_li_collapse_num]',$modcfg['list_filter_li_collapse_num'], 'txtbox5')?></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>相册聚合页面缺省显示模式:</strong>设置主题相册聚合页面默认的显示模式，默认为图文模式</td>
                <td><?=form_select('modcfg[item_album_mode]',array('normal'=>'图文模式','waterfall'=>'瀑布流'),$modcfg['item_album_mode'])?></td>
            </tr>
            
        </table>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" id="config4" style="display:none;">
            <tr>
                <td width="40%" class="altbg1" valign="top">
                    <strong>主题分类页面:</strong>
                    设置主题分类页面
                    <p>可用标签：
                    <span title="网站名称" class="font_1">{site_name}</span>
                    <span title="网站全局keywords" class="font_1">{site_keywords}</span>
                    <span title="网站全局description" class="font_1">{site_description}</span>
                    <span title="当前城市名称" class="font_1">{city_name}</span>
                    <span title="模块名称" class="font_1">{module_name}</span>
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
                    <strong>主题列表页:</strong>
                    <p>可用标签：
                    <span title="网站名称" class="font_1">{site_name}</span>
                    <span title="网站全局keywords" class="font_1">{site_keywords}</span>
                    <span title="网站全局description" class="font_1">{site_description}</span>
                    <span title="当前城市名称" class="font_1">{city_name}</span>
                    <span title="模块名称" class="font_1">{module_name}</span>
                    <span title="主分类名称" class="font_1">{root_category_name}</span>
                    <span title="当前分类名称" class="font_1">{current_category_name}</span>
                    <span title="上级地区名称" class="font_1">{root_area_name}</span>
                    <span title="当前地区名称" class="font_1">{area_name}</span>
                    <span title="当前分页编号" class="font_1">{page}</span>
                    <span title="主分类参数设置里提供的 Meta Keywords" class="font_1">{root_category_keywords}</span>
                    <span title="主分类参数设置里提供的 Meta Description" class="font_1">{root_category_description}</span>
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
                    <strong>主题内容页:</strong>
                    <p>可用标签：
                    <span title="网站名称" class="font_1">{site_name}</span>
                    <span title="网站全局keywords" class="font_1">{site_keywords}</span>
                    <span title="网站全局description" class="font_1">{site_description}</span>
                    <span title="当前城市名称" class="font_1">{city_name}</span>
                    <span title="模块名称" class="font_1">{module_name}</span>
                    <span title="主分类名称" class="font_1">{root_category_name}</span>
                    <span title="当前分类名称" class="font_1">{current_category_name}</span>
                    <span title="主题名称" class="font_1">{name}</span>
                    <span title="主题简介" class="font_1">{description}</span>
                    <span title="主题详细介绍前100个文字" class="font_1">{content}</span>
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
                    <strong>相册列表页:</strong>
                    <p>可用标签：
                    <span title="网站名称" class="font_1">{site_name}</span>
                    <span title="网站全局keywords" class="font_1">{site_keywords}</span>
                    <span title="网站全局description" class="font_1">{site_description}</span>
                    <span title="当前城市名称" class="font_1">{city_name}</span>
                    <span title="模块名称" class="font_1">{module_name}</span>
                    <span title="主分类名称" class="font_1">{root_category_name}</span>
                    <span title="当前分类名称" class="font_1">{current_category_name}</span>
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
                    <strong>主题排行榜页面:</strong>
                    <p>可用标签：
                    <span title="网站名称" class="font_1">{site_name}</span>
                    <span title="网站全局keywords" class="font_1">{site_keywords}</span>
                    <span title="网站全局description" class="font_1">{site_description}</span>
                    <span title="当前城市名称" class="font_1">{city_name}</span>
                    <span title="模块名称" class="font_1">{module_name}</span>
                    <span title="当前分类名称" class="font_1">{current_category_name}</span>
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
                    <strong>主题地图页面:</strong>
                    <p>可用标签：
                    <span title="网站名称" class="font_1">{site_name}</span>
                    <span title="网站全局keywords" class="font_1">{site_keywords}</span>
                    <span title="网站全局description" class="font_1">{site_description}</span>
                    <span title="当前城市名称" class="font_1">{city_name}</span>
                    <span title="模块名称" class="font_1">{module_name}</span>
                    <span title="当前地区" class="font_1">{area_name}</span>
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
		alert('对不起，您未设置App Key！请先设置！');
		return;
	}
	window.open("http://container.api.taobao.com/container?appkey="+appkey);
}
</script>