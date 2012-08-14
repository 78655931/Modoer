
function discuss_topic_upimg(id) {
    var html = '<div style="margin:5px 0;"><form id="frm_topicupload" method="post" action="'+Url('modoer/upload/in_ajax/1')+'" enctype="multipart/form-data">';
    html += '<input type="file" name="picture" />&nbsp;';
    html += '<button type="button" class="button">��ʼ�ϴ�</button>';
    html += '</form></div>';
    GLOBAL['mdlg_upimg'] = new $.mdialog({id:'mdlg_upimg', title:'�ϴ�ͼƬ', body:html, width:360});
    var frm = $('#frm_topicupload');
    frm.find('button').click(function() {
        var file = frm.find('[name="picture"]').val();
        if(!file) {
            alert('δѡ׼���ϴ�����ͼƬ�ļ�.');
            return;
        }
        ajaxPost('frm_topicupload', id, 'discuss_topic_addimg', 1, 'mdlg_upimg', function(data){
            frm.show();
            $('#upload_message').remove();
            myAlert(data.replace('ERROR:',''));
        });
        frm.parent().append('<div id="upload_message" style="margin:10px 0; text-align:center;">�����ϴ�...</div>');
        frm.hide();
    });
}

function discuss_topic_addimg(id,data) {
    if(data.length==0) {
        alert('ͼƬδ�ϴ��ɹ���'); return;
    }
    var keyname = basename(data, data.substring(data.lastIndexOf(".")));
    var foo = $('#topic_images_foo');
    var imgfoo = $('<div class="upimg"></div>').attr('id','upimg_'+keyname);
    imgfoo.append($('<img></img>').attr('src', urlroot + '/' + data));
    imgfoo.append($('<a href="javascript:void(0);">����</a>').click(function(){ discuss_topic_insertimg(id, keyname) }));
    imgfoo.append(" | ");
    imgfoo.append($('<a href="javascript:void(0);">ɾ��</a>').click(function(){ discuss_topic_delimg(keyname) }));
    imgfoo.append("<input type=\"hidden\" name=\"pictures[]\" value=\""+data+"\" />");
    foo.append(imgfoo);
}

function discuss_topic_delimg(keyname) {
    $('#upimg_'+keyname).remove();
}

function discuss_topic_insertimg(cid,imgname) {
    var text = "[/img:" + imgname + "/]";
    $('#'+cid).insertAtCaret(text);
}

var discuss_dialog = null;
function discussion_topic_edit (tpid) {
    if (!is_numeric(tpid)) {
        alert('��Ч��tpid'); 
		return;
    }
	$.post(Url('discussion/member/ac/topic/op/edit/tpid/'+tpid), 
	{ in_ajax:1,empty:getRandom() },
	function(result) {
        if(result == null) {
			alert('��Ϣ��ȡʧ�ܣ���������æµ�����Ժ��ԡ�');
        } else if (result.match(/\{\s+caption:".*",message:".*".*\s*\}/)) {
            myAlert(result);
		} else {
            dlgOpen('�༭����', result, 660, 0);
		}
	});
}

function discussion_topic_save() {
    if(discuss_dialog) {
        discuss_dialog.close();
    }
    document_reload();
}

function discussion_topic_delete(tpid) {
    if (!is_numeric(tpid)) {
		alert('��Ч��tpid'); 
		return;
    }
	if(!confirm('��ȷ��Ҫɾ��������Ϣ��?')) return;
	$.post(Url('discussion/member/ac/topic/op/delete/tpid/'+tpid), 
	{ in_ajax:1,empty:getRandom() },
	function(result) {
        if(result == null) {
			alert('��Ϣ��ȡʧ�ܣ���������æµ�����Ժ��ԡ�');
        } else if (result.match(/\{\s+caption:".*",message:".*".*\s*\}/)) {
            myAlert(result);
		} else if(result=='OK') {
			msgOpen('ɾ����ϣ�', 200, 60);
		}
	});
}

function discussion_reply_edit (rpid) {
    if (!is_numeric(rpid)) {
        alert('��Ч�� rpid'); 
		return;
    }
	$.post(Url('discussion/member/ac/reply/op/edit/rpid/'+rpid), 
	{ in_ajax:1,empty:getRandom() },
	function(result) {
        if(result == null) {
			alert('��Ϣ��ȡʧ�ܣ���������æµ�����Ժ��ԡ�');
        } else if (result.match(/\{\s+caption:".*",message:".*".*\s*\}/)) {
            myAlert(result);
		} else {
			dlgOpen('�༭�ظ�', result, 550, 220);
		}
	});
}

function discussion_reply_delete(rpid) {
    if (!is_numeric(rpid)) {
		alert('��Ч�� rpid'); 
		return;
    }
	if(!confirm('��ȷ��Ҫɾ��������Ϣ��?')) return;
	$.post(Url('discussion/member/ac/reply/op/delete/rpid/'+rpid), 
	{ in_ajax:1,empty:getRandom() },
	function(result) {
        if(result == null) {
            alert('��Ϣ��ȡʧ�ܣ���������æµ�����Ժ��ԡ�');
        } else if (result.match(/\{\s+caption:".*",message:".*".*\s*\}/)) {
            myAlert(result);
		} else if(result=='OK') {
			msgOpen('ɾ����ϣ�', 200, 60);
		}
	});
}

function discussion_reply_reload(rpid) {
    if (!is_numeric(rpid)) return;
	$.post(Url('discussion/topic/op/reload/rpid/'+rpid), 
	{ in_ajax:1,empty:getRandom() },
	function(result) {
		$('#reply_'+rpid).html(result);
	});
}

function discussion_reply_at(cid,username) {
	var at = "[/@" + username + "/]";
	var str = $('#'+cid).val();
	if(str.indexOf(at) >=0) return;
	$('#'+cid).val("[/@" + username + "/]:"+$('#'+cid).val()).focus();
}