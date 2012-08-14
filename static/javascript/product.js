/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
//删除分类
function delete_category(id) {
	var catid = $('#'+id).val();
    if(!is_numeric(catid) || catid < 1) return false;
    if(confirm('您确定要删除本分类及下属的产品吗？')) {
        location.href = Url('product/member/ac/category/op/delete/catid/'+catid);
    } else {
        return false;
    }
}
//重命名分类
function rename_category(sel_id) {
	var catid = $('#'+sel_id).val();
	var name = $('#'+sel_id).find("option:selected").text();
	var catname = prompt('请输入您的分类名称：',name);
	if(!catname) return;
	$.post(Url('product/member/ac/category/op/rename'), {'catid':catid, 'catname':catname, 'in_ajax':1 }, 
		function(result) {
		if (result.match(/\{\s+caption:".*",message:".*".*\s*\}/)) {
            myAlert(result);
        } else if(result=='OK') {
			$('#'+sel_id).find("option:selected").text(catname);
			msgOpen('更新成功!');
		} else {
		    alert('产品读取失败，可能网络忙碌，请稍后尝试。');
        }
	});
}
//新建分类
function create_product_category(sid) {
	if (!is_numeric(sid)) {
		alert('未选择产品主题。');
		return false;
	}
    var catname = prompt('请输入您的分类名称：','');
    if(!catname) return;
	$.post(Url('product/member/ac/category/op/create'), {'sid':sid, 'catname':catname, 'in_ajax':1 }, 
		function(result) {
		if (result.match(/\{\s+caption:".*",message:".*".*\s*\}/)) {
            myAlert(result);
        } else if(is_numeric(result)) {
			$("<option value='"+result+"'"+' selected="selected"'+">"+catname+"</option>").appendTo($('#catid'));
		} else {
		    alert('产品读取失败，可能网络忙碌，请稍后尝试。');
        }
	});
}
//取得分类列表
function get_product_category(sid) {
	if (!is_numeric(sid) || sid < 1) {
		$('#category').html('');
		return false;
	}
	$.post(Url('product/member/ac/category/op/list'), {'sid':sid, 'in_ajax':1 }, 
		function(result) {
		if (result.match(/\{\s+caption:".*",message:".*".*\s*\}/)) {
            myAlert(result);
        } else if(result == 'null') {
            $('#category').html('');
            $('<option value="" selected="selected">==选择产品分类==</option>').appendTo($('#category'));
		} else {
            $('#category').html(result);
        }
	});
}
//取得主题产品
function get_products(sid, page) {
    if (!is_numeric(sid)) {
        alert('无效的ID'); 
		return;
    }
	if(!page) page = 1;
	$.post(Url('product/list/sid/'+sid+'/page/'+page), 
	{ 'in_ajax':1 },
	function(result) {
        if(result == null) {
			alert('信息读取失败，可能网络忙碌，请稍后尝试。');
        } else if (result.match(/\{\s+caption:".*",message:".*".*\s*\}/)) {
            myAlert(result);
		} else {
			$('#products').html(result);
		}
	});
}