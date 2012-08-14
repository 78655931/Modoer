/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
loadscript('mdialog');
function get_comment(idtype,id,page,endpage) {
    if (!is_numeric(id)) {
        alert('无效的ID'); 
		return;
    }
	endpage = !endpage ? 0 : 1;
	if(!page) page = 1;
	$.get(Url('comment/list'), 
		{'idtype':idtype,'id':id,'page':page,'endpage':endpage,'in_ajax':1,'rand':getRandom()},
		function(result) {
			if(result == null) {
				alert('信息读取失败，可能网络忙碌，请稍后尝试。');
			} else if (result.match(/\{\s+caption:".*",message:".*".*\s*\}/)) {
				myAlert(result);
			} else {
				$('#commentlist').html(result);
				if(endpage) {
					window.location.hash="commentend";
				}
			}
		}
	);
	$('#comment_button').disabled = true;
	return false;
}

function reset_comment_form() {
	$('#comment_form [name=title]').val('');
	$('#comment_form [name=grade]').get(4).checked = true;
	$('#comment_form [name=content]').val('');
	$('#comment_form [name=seccode]').val('');
	$('#seccode').empty().html();
}

var comment_time = 0;
function enable_comment_button() {
	comment_time = comment_time - 1;
	if(comment_time < 1) {
		$('#comment_button').text('发表评论').attr('disabled','');
		comment_time = 0;
	} else {
		$('#comment_button').text('发表评论('+comment_time+')').attr('disabled','disabled');
		window.setTimeout('enable_comment_button()', 1000);
	}
}

function post_comment_behind(str) {
	var param = str.split('-');
	var idtype=param[0];
	var id=param[1];
	reset_comment_form();
	comment_time = $('#comment_time').val();
	$('#comment_button').attr('disabled','disabled');
	window.setTimeout('enable_comment_button()', 1000);
	get_comment(idtype, id, 1, true);
}