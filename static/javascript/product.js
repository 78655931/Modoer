/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
//ɾ������
function delete_category(id) {
	var catid = $('#'+id).val();
    if(!is_numeric(catid) || catid < 1) return false;
    if(confirm('��ȷ��Ҫɾ�������༰�����Ĳ�Ʒ��')) {
        location.href = Url('product/member/ac/category/op/delete/catid/'+catid);
    } else {
        return false;
    }
}
//����������
function rename_category(sel_id) {
	var catid = $('#'+sel_id).val();
	var name = $('#'+sel_id).find("option:selected").text();
	var catname = prompt('���������ķ������ƣ�',name);
	if(!catname) return;
	$.post(Url('product/member/ac/category/op/rename'), {'catid':catid, 'catname':catname, 'in_ajax':1 }, 
		function(result) {
		if (result.match(/\{\s+caption:".*",message:".*".*\s*\}/)) {
            myAlert(result);
        } else if(result=='OK') {
			$('#'+sel_id).find("option:selected").text(catname);
			msgOpen('���³ɹ�!');
		} else {
		    alert('��Ʒ��ȡʧ�ܣ���������æµ�����Ժ��ԡ�');
        }
	});
}
//�½�����
function create_product_category(sid) {
	if (!is_numeric(sid)) {
		alert('δѡ���Ʒ���⡣');
		return false;
	}
    var catname = prompt('���������ķ������ƣ�','');
    if(!catname) return;
	$.post(Url('product/member/ac/category/op/create'), {'sid':sid, 'catname':catname, 'in_ajax':1 }, 
		function(result) {
		if (result.match(/\{\s+caption:".*",message:".*".*\s*\}/)) {
            myAlert(result);
        } else if(is_numeric(result)) {
			$("<option value='"+result+"'"+' selected="selected"'+">"+catname+"</option>").appendTo($('#catid'));
		} else {
		    alert('��Ʒ��ȡʧ�ܣ���������æµ�����Ժ��ԡ�');
        }
	});
}
//ȡ�÷����б�
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
            $('<option value="" selected="selected">==ѡ���Ʒ����==</option>').appendTo($('#category'));
		} else {
            $('#category').html(result);
        }
	});
}
//ȡ�������Ʒ
function get_products(sid, page) {
    if (!is_numeric(sid)) {
        alert('��Ч��ID'); 
		return;
    }
	if(!page) page = 1;
	$.post(Url('product/list/sid/'+sid+'/page/'+page), 
	{ 'in_ajax':1 },
	function(result) {
        if(result == null) {
			alert('��Ϣ��ȡʧ�ܣ���������æµ�����Ժ��ԡ�');
        } else if (result.match(/\{\s+caption:".*",message:".*".*\s*\}/)) {
            myAlert(result);
		} else {
			$('#products').html(result);
		}
	});
}