/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
function article_select_category(select,id,all) {
    var catid = $(select).val();
    var cat = $('#catid').empty();
	if(!all) all = false;
	if(all) {
		cat.append("<option value='0'>==全部==</option>");
	}
	if(!catid) return;
    $.each( article_category_sub[catid], function(i, n){
        if(typeof(n)!='undefined') cat.append("<option value='"+i+"'>"+n+"</option>");
    });
}

function article_digg(id) {
    if (!is_numeric(id)) {
        alert('无效的ID'); 
		return;
    }
	$.post(Url('article/detail/id/'+id), {op:"digg",in_ajax:1}, function(result) {
		if (result.match(/\{\s+caption:".*",message:".*".*\s*\}/)) {
            myAlert(result);
        } else if(result == 'OK') {
			$('#digg_num').text(parseInt($('#digg_num').text())+1);
			$('#digg_click').html('谢谢支持');
		} else {
			$('#digg_click').html('谢谢支持');
		}
	});
}

function article_upload_thumb_ui(id) {
	var html = '<form id="frm_thumbupload" method="post" action="'+Url('modoer/upload/in_ajax/1')+'" enctype="multipart/form-data">';
	html += '<input type="file" name="picture" />&nbsp;';
	html += '<button type="button" class="button" onclick="ajaxPost(\'frm_thumbupload\', \''+id+'\', \'article_upload_thumb_add\',1);">上传</button>';
	html += '</form>';
	dlgOpen('上传文章封面', html, 300, 100);
}

function article_upload_thumb_add(id,data) {
    if(data.length==0) {
        alert('图片未上传成功。'); return;
    }
    var img = $('<img></img>').attr('src', urlroot + '/' + data);
    var a = $('<a></a>').attr('href', urlroot + '/' + data).attr('target','_blank').html(img);
    $('#'+id).empty().append(a);
    $("[name=picture]").val(data);
}