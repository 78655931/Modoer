// ��js��jquery�����
var charset = document.charset;
var userAgent = navigator.userAgent.toLowerCase();
var is_opera = userAgent.indexOf('opera') != -1 && opera.version();
var is_moz = (navigator.product == 'Gecko') && userAgent.substr(userAgent.indexOf('firefox') + 8, 3);
var is_ie = (userAgent.indexOf('msie') != -1 && !is_opera) && userAgent.substr(userAgent.indexOf('msie') + 5, 3);
var is_chrome = userAgent.indexOf('chrome') != -1 && userAgent.substr(userAgent.indexOf('chrome') + 7, 3);
var is_safari = userAgent.indexOf('safari') != -1 && userAgent.substr(userAgent.indexOf('safari') + 7, 3);
var ajaxPostGlobal = 0;
var post_max_size = 13930;
//����б��
if(siteurl.substr(siteurl.length -1 , 1) != '/') siteurl += '/';
//ȫ�ֱ���
var GLOBAL = new Array();
GLOBAL['js'] = new Array();
GLOBAL['css'] = new Array();
GLOBAL['js_root'] = urlroot + '/static/javascript/';
GLOBAL['css_root'] = urlroot + '/static/images/';
GLOBAL['url'] = siteurl;
GLOBAL['mod'] = typeof(mod) == 'undefined' ? null : mod;
// ȥ�ո�
String.prototype.trim = function() {  
    return this.replace(/(^\s*)|(\s*$)/g, "");
}
// ��������
Array.prototype.indexof = function(value) {
    for (var i in this) {
        if(i==value) return this[i];
    }
    return -1;
}
// �ж��ַ����Ƿ�Ϊ����
String.prototype.is_numeric = function() {
    var patn = /^[0-9]+$/;
    return patn.test(str);
}
//��̬����JS�ļ�
function loadscript(filename) {
    if(filename == '') return;
    if(filename.indexOf(',') > 0) {
        var files = filename.split(',');
        if(typeof(files) != null && files.length > 0) {
            for (var i=0; i<files.length; i++) {
                loadscript(files[i]);
            }
        }
    } else {
        var file = filename + '.js';
        if(typeof(arguments[1]) == 'string' && arguments[1] != '') {
            var src = arguments[1] + file;
        } else {
            var src = GLOBAL['js_root'] + file;
        }
        if (GLOBAL['js'].indexof(file)>-1) return ;
        var j = document.createElement("script");
        j.setAttribute("type", "text/javascript");
        j.setAttribute("src", src);
        document.getElementsByTagName("head")[0].appendChild(j);
        GLOBAL['js'][file] = 1;
    }
}
//��̬����CSS�ļ�
function loadcss(filename) {
    if(filename=='') return;
    if(filename.indexOf(',') > 0) {
        var files = filename.split(',');
        if(typeof(files) != null && files.length > 0) {
            for (var i=0; i<files.length; i++) {
                loadcss(files[i]);
            }
        }
    } else {
        var file = filename + '.css';
        if(typeof(arguments[1]) == 'string' && arguments[1] != '') {
            var src = arguments[1] + file;
        } else {
            var src = GLOBAL['css_root'] + file;
        }
        if (GLOBAL['css'].indexof(file)>-1) return;
        var c = document.createElement("link");
        c.setAttribute("rel", "stylesheet");
        c.setAttribute("type", "text/css");
        c.setAttribute("href", src);
        document.getElementsByTagName("head")[0].appendChild(c);
        GLOBAL['css'][file] = 1;
    }
}
// url��ǩ��ʽת��������
function Url(url_format, anchor, full_url) {
    if(url_format == '') return getUrl('');
    var urlarr = url_format.split('/');
    var flag = urlarr[0];
    if(!urlarr[1]) return getUrl(flag);
    var act = urlarr[1];
    if(urlarr.length <= 2) return getUrl(flag, act);
    var param = split = '';
    for (var i=2; i<urlarr.length; i++) {
        param += split + urlarr[i] + '=' + urlarr[++i];
        split = '&';
    }
    return getUrl(flag, act, param, anchor, full_url);
}
//��ȡ���·��
function getUrl() {
    if(arguments.length == 0) {
        return typeof(mod)=='undefined' ? '' : '';
    }

    var flag = arguments[0];
    var fullurl = arguments[4];
    var crmod = modules[flag];
    var url = !fullurl ? (urlroot+'/') : siteurl;

    //if(fullalways) fullurl = true;

    if(flag == 'modoer') {
        url += 'index.php?m=index';
    } else if(typeof(crmod)=='undefined') {
        url += '';
    } else {
        url += 'index.php?m='+flag;
    }
    /*
    if(typeof(mod)=='undefined' && typeof(crmod)=='undefined') {
        url = !fullurl ? '' : siteurl;
    } else if(typeof(mod)=='undefined' && crmod) {
        url = (!fullurl ? '' : siteurl) + crmod['directory'] + '/';
    } else if(mod && typeof(crmod)=='undefined') {
        url = !fullurl ? '../' : siteurl;
    } else if(mod['flag'] != crmod['flag']) {
        url = (!fullurl ? '../' : siteurl) + crmod['directory'] + '/';
    } else if(mod['flag'] == crmod['flag']) {
        url = !fullurl ? '' : siteurl;
    }
    */

    if(typeof(arguments[1])=='string') {
        url += '&act='+arguments[1];
    } else {
        return url;
    }

    var param = arguments[2];
    var type = typeof(param);
    if(type=='object') {
        var split = '?';
        for (var i=0; i<param.length; i++) {
            url += split + param[i];
            split = '&';
        }
    } else if(type=='string') {
        url += '&' + param;
    }

    if(typeof(arguments[3])=='string') {
        url += '#' + arguments[3];
    }
    if(fullurl) url = siteurl + url;
    /*
    if(rewrite_mod == 'pathnfo') {
        url = url.replace('.php?act=','-');
        url = url.replace('=','-');
        url = url.replace('&amp;','-');
        url = url.replace('&','-');
        url = url.replace('.php','-');
        url += '.html';
    } else {
        url = url.replace('.php?act=','/');
        url = url.replace('=','/');
        url = url.replace('&amp;','/');
        url = url.replace('&','/');
        url = url.replace('.php','/');
    }*/
    return url;
}
// �ж��Ƿ�Ϊ����
function is_numeric(str) {
    var patn = /^[0-9]+$/;
    if(!patn.test(str)) return false;
    return true;
}
//�ж�E-mail��ʽ�Ƿ���ȷ
function is_email(str) {
    var patn = /^[_a-zA-Z0-9\-]+(\.[_a-zA-Z0-9\-]*)*@[a-zA-Z0-9\-]+([\.][a-zA-Z0-9\-]+)+$/;
    if(!patn.test(str)) return false;
    return true;
}
//�ж��Ƿ񷵻���Ϣ
function is_message(str) {
    return str.match(/\{\s+caption:".*",message:".*".*\s*\}/);
}
//�ж��Ƿ�Ϊjson
function is_json(str) {
    return str.match(/^\{.*:.*\}$/);
}
//�ж��Ƿ�Ϊ��Ч����
function is_domain(domain) {
    domain = domain.trim();
    if(domain=='') return false;
    if(domain.indexOf('.')<=0) return false;
    if(domain.match(/^[0-9]+\.[0-9]+\.[0-9]+\.[0-9]+$/)) return false;
    return domain.match(/^[a-z0-9][a-z0-9\-]+[a-z0-9](\.[a-z]{2,4})+$/i) != null;
}
//�Ե�
function isEqual(objid1, objid2) {
    return objid1.value == objid1.value;
}
//ȡ���ֵ
function getRandom() {
    return Math.floor(Math.random()*1000+1);
}
//�ַ�������
function mb_strlen(str) {
    var len = 0;
    for(var i = 0; i < str.length; i++) {
        len += str.charCodeAt(i) < 0 || str.charCodeAt(i) > 255 ? (document.charset == 'utf-8' ? 3 : 2) : 1;
    }
    return len;
}
//��ʾ�Ի���
function myAlert(data) {
    if(is_safari) {
        //����safari��װѸ����չ�������
        var i = data.indexOf('<embed');
        if(i>=0) data = data.substring(0,i);
    }
    var mymsg = eval('('+data+')'); //JSONת��
    mymsg.message = mymsg.message.replace('ERROR:','');
    if(mymsg.extra == 'login') {
        /*
        if(window.confirm(mymsg.caption + '����Ҫ����¼������ܼ����������ȷ��������¼��վ��')) {
            window.location.href = mymsg.url;
        }
        */
        dialog_login();
    } else if(mymsg.extra == 'dlg') {
        dlgOpen(mymsg.caption, mymsg.message.replace("{LF}","\r\n"), 400, 300, true);
    } else if(mymsg.extra == 'msg') {
        msgOpen(mymsg.caption, mymsg.message.replace("{LF}","\r\n"));
    } else if (mymsg.extra == 'location') {
        document.location = mymsg.message;
    } else {
        alert(mymsg.message);
    }
}
//��Ŀ�����TAG����лeonbell���ԣ�
function addtag(Id, tag) {
    var str = $("#"+Id).val();
    if ((","+str+",").indexOf(","+tag+",") == -1) {
        str += str ? ',' + tag : tag;
        $("#"+Id).val(str);
    }
}
//��������(textarea)
function addrows(obj, num) {
    obj.rows += num;
}
//��������(textarea)
function decrows(obj, num) {
    if (obj.rows>num) {
        obj.rows -= num;
    }
}
//����checkbox��ѡ
function allchecked() { 
    var check = document.getElementsByTagName('input');
    for (var i=0; i<check.length; i++) {
        if (check[i].type == 'checkbox' && !check[i].disabled) {
            check[i].checked = !check[i].checked;
        }
    }
}
//name��ͬ��checkbox��ѡ
function checkbox_checked(name,obj) { 
    var check = document.getElementsByTagName('input');
    for (var i=0; i<check.length; i++) {
        if (check[i].type == 'checkbox' && check[i].name == name && !check[i].disabled) {
            if(obj) {
                check[i].checked = obj.checked;
            } else {
                check[i].checked = !check[i].checked;
            }
        }
    }
}
//���checkbox�Ƿ���ѡ��
function checkbox_check() {
    var check = document.getElementsByTagName('input');
    if(typeof(arguments[0]) == 'string') {
        var checkname = arguments[0];
    } else {
        var checkname = null;
    }
    var ischecked = false;
    for (var i=0; i<check.length; i++) {
        if (check[i].type == 'checkbox' && check[i].checked && !check[i].disabled) {
            ischecked = checkname == null || check[i].name == checkname;
            if(ischecked) break;
        }
    }
    if (!ischecked)
        alert('������ѡ��һ�');
    return ischecked;
}
//���radio�Ƿ���ѡ��
function checkradio(obj) {
    if(obj) {
        var check=obj.getElementsByTagName('input');
    } else {
        var check=document.getElementsByTagName('input');
    }
    var ischecked = false;
    for (var i=0; i<check.length; i++) {
        if (check[i].type == 'radio' && check[i].checked) {
            ischecked=true;
        }
    }
    return ischecked;
}
//ȡ��ѡ��radio��ֵ
function getRadio(from,name) {
    if(from) {
        var radios = from.getElementsByTagName('input');
    } else {
        var radios = document.getElementsByTagName('input');
    }
    if(!radios) return;
    var value='';
    for (var i=0; i<radios.length; i++) {
        if (radios[i].type == 'radio' && radios[i].name == name && radios[i].checked) {
            value=radios[i].value;
            break;
        }
    }
    return value;
}
//��ʾ��֤��
function show_seccode() {
    var id= (arguments[0]) ? arguments[0] : 'seccode';
    if($('#'+id).html()!='') return;
    var sec = $('#'+id).empty();
    var img = $('<img />')
            .css({weight:"80px", height:"25px", cursor:"pointer"})
            .attr("title",'���������֤��')
            .click(function() {
                this.src= Url('modoer/seccode/x/'+getRandom());
                $('#'+id).show();
            });
    sec.append(img);
    img.click();
}
//ת��ȫ������
function tot(mobnumber) {
    while(mobnumber.indexOf("��")!=-1){
        mobnumber = mobnumber.replace("��","0");
    }
    while(mobnumber.indexOf("��")!=-1){
        mobnumber = mobnumber.replace("��","1");}
    while(mobnumber.indexOf("��")!=-1){
        mobnumber = mobnumber.replace("��","2");}
    while(mobnumber.indexOf("��")!=-1){
        mobnumber = mobnumber.replace("��","3");}
    while(mobnumber.indexOf("��")!=-1){
        mobnumber = mobnumber.replace("��","4");}
    while(mobnumber.indexOf("��")!=-1){
        mobnumber = mobnumber.replace("��","5");}
    while(mobnumber.indexOf("��")!=-1){
        mobnumber = mobnumber.replace("��","6");}
    while(mobnumber.indexOf("��")!=-1){
        mobnumber = mobnumber.replace("��","7");}
    while(mobnumber.indexOf("��")!=-1){
        mobnumber = mobnumber.replace("��","8");}
    while(mobnumber.indexOf("��")!=-1){
        mobnumber = mobnumber.replace("��","9");}
    return mobnumber;
}
//�����ж�
function checkByteLength(str,minlen,maxlen) {
    if (str == null) return false;
    var l = str.length;
    var blen = 0;
    for(i=0; i<l; i++) {
        if ((str.charCodeAt(i) & 0xff00) != 0) {
            blen ++;
        }
        blen ++;
    }
    if (blen > maxlen || blen < minlen) {
        return false;
    }
    return true;
}
//tab���л�
function tabSelect(showId,idpre) {
    for(i=1; i<=10; i++) {
        var tab = $("#" + idpre + i);
        if(!tab[0]) break;
        if (i == showId) { 
            $("#btn_"+idpre+i).attr("className","selected");
            $("#"+idpre+i).toggleClass("none");
            var isload = $("#"+idpre+i).attr('isload');
            var dn = $("#"+idpre+i).attr('datacallname');
            var pm = $("#"+idpre+i).attr('params');
            if(!isload && dn) {
                ajax_datacall(idpre+i, dn, pm);
            }
            $("#"+idpre+i).show()
         } else {
            $("#btn_"+idpre+i).attr("className","unselected");
            $("#"+idpre+i).hide();
        }
    }
}
//ajax��ʽ��ȡ��������
function ajax_datacall(id, name, params) {
    $("#"+id).html('<div style="padding:5px;">loading...</div>');
    if(params) {
        var json = eval('(' + params + ')');
        json.datacallname = name;
        if(typeof(postLevel)!='undefined') json.postLevel = postLevel;
    } else {
        var json = {'datacallname':'name','postLevel':postLevel}
    }
    $.post(Url('modoer/ajax/op/get_datacall/in_ajax/1/do/datacall'), json,
    function(result) {
        if(result == null) {
            alert('��Ϣ��ȡʧ�ܣ���������æµ�����Ժ��ԡ�');
        } else if (result.match(/\{\s+caption:".*",message:".*".*\s*\}/)) {
            myAlert(result);
        } else {
            $("#"+id).html(result);
            $("#"+id).attr('isload', '1');
        }
    });
}
//����ڲ����ַ�
function insertText(obj,str) {
    if (document.selection) {
        var sel = document.selection.createRange();
        sel.text = str;
    } else if (typeof obj.selectionStart === 'number' && typeof obj.selectionEnd === 'number') {
        var startPos = obj.selectionStart,
            endPos = obj.selectionEnd,
            cursorPos = startPos,
            tmpStr = obj.value;
        obj.value = tmpStr.substring(0, startPos) + str + tmpStr.substring(endPos, tmpStr.length);
        cursorPos += str.length;
        obj.selectionStart = obj.selectionEnd = cursorPos;
    } else {
        obj.value += str;
    }
}
//����ѡ��
function selectOperation(select) {
    var url = select.options[select.selectedIndex].value;
    if(url) {
        var cfm = select.options[select.selectedIndex].getAttribute("cfm");
        select.selectedIndex = 0;
        if(cfm && confirm(cfm) || !cfm) {
            window.location = url;
        }
    }
    select.selectedIndex = 0;
}
//�ύ����
function easy_submit(form_name,act_value,check_name) {
    submit_form(form_name,'op',act_value,null,null,check_name);
}
//��ťʽ�����Ͳ���
function submit_form(form_name, act_name, act_value, param_name, param_value, check_name) {
    var form = $("[name="+form_name+"]");
    if(check_name != null) {
        if(!checkbox_check(check_name)) return;
    }

    if(act_value == 'delete') {
        if(!confirm('ȷ��Ҫ����ɾ��������')) return;
    }

    if(form.find("[name="+act_name+"]")[0] == null) {
        form.append("<input type=\"hidden\" name=\""+act_name+"\" />");
    }
    form.find("[name="+act_name+"]").val(act_value);

    if(param_name != null || param_name != '') {
        form.find("[name="+param_name+"]").val(param_value);
    }

    form.submit();
}
//ˢ�µ�ǰҳ��
function document_reload() {
     document.location.reload();
}
//Ajax�ύ
function ajaxPost(formid,myid,func,use_data,dlgid,err_callback) {
    //if(is_domain(sitedomain)) document.domain = sitedomain;
    if(ajaxPostGlobal != 0) {
        return false;
    }
    var iframeid = 'ajaxiframe';
    if($('#'+iframeid)[0] == null) {
        $(document.body).append('<iframe name="'+iframeid+'" id="'+iframeid+'"></iframe>');
    }
    var iframe = $('#'+iframeid);
    iframe.css("display","none");

    use_data = !use_data ? 0 : 1;
    ajaxPostGlobal = [formid, iframeid, myid, func, use_data];
    // g6b0h9j9g6f4e6d8f5g
    var form = $("#" + formid);
    form.attr("target", iframeid);
    form.append('<input type="hidden" name="in_ajax" value="1">');
    form.append('<input type="hidden" name="in_iframe" value="1">');
    iframe.unbind();
    iframe.load(function() {
        ajaxPostLoad(dlgid,err_callback);
    });
    form[0].submit();
}
//ajax�ύ�󷵻�
function ajaxPostLoad(dlgid, err_callback) {
    if(is_domain(sitedomain)) document.domain = sitedomain;
    if(ajaxPostGlobal==0) return;
    var ajaxparam = ajaxPostGlobal;
    ajaxPostGlobal = 0;
    var iframe = document.getElementById(ajaxparam[1]);
    var data = $(iframe.contentWindow.document.body).html();
    //alert(data);
    if(data.indexOf('ERROR:') > 1) {
        if(err_callback) {
            err_callback(data);
        } else {
            myAlert(data.replace('ERROR:',''));
        }
        return;
    } else {
        dlgClose(dlgid);
        if(data.match(/\{\s+caption:".*",message:".*".*\s*\}/)) {
            myAlert(data);
        }
    }
    //alert(strip_tags(data));
    if(ajaxparam[3]) {
        if(ajaxparam[4])
            setTimeout(ajaxparam[3]+'(\'' + ajaxparam[2] + '\',\'' + data.replace("'","\\'") + '\');', 10);
        else
            setTimeout(ajaxparam[3]+'(\'' + ajaxparam[2] + '\');', 10);
    }
}
//����cookie
function set_cookie(name, value, expireDays) {
    name = cookiepre + name;
    var datetime = 0;
    var expires = new Date();
    if(typeof(expireDays)!='number') expireDays = 1;
    //if(!expireDays) expireDays = 1;
    if(expireDays != 0) {
        expires.setTime(expires.getTime() + expireDays * 24 * 3600 * 1000);
        datetime = expires.toGMTString();
    }
    var cookiestr = '';
    cookiestr = name + '=' + escape(value) + '; path=' + cookiepath;
    if(cookiedomain != '') {
        cookiestr += '; domain=' + cookiedomain;
    }
    if(datetime) cookiestr += '; expires=' + datetime;
    document.cookie = cookiestr;
}
//��ȡcookie�ִ�
function get_cookieval(start) {
    var end = document.cookie.indexOf(";", start);
    if(end == -1) {
        end = document.cookie.length;
    }
    return unescape(document.cookie.substring(start, end));
}
//��ȡcookie
function get_cookie(name) {
    name = cookiepre + name;
    var arg = name + "=";
    var alen = arg.length;
    var clen = document.cookie.length;
    var i = 0;
    while(i < clen) {
        var j = i + alen;
        if(document.cookie.substring(i, j) == arg) return get_cookieval(j);
        i = document.cookie.indexOf(" ", i) + 1;
        if(i == 0) break;
    }
    return null;
}
//ɾ��cookie
function del_cookie(name) {
    var expires = new Date();
    expires.setTime (expires.getTime()-1);
    var cval = '';//get_cookie(name);
    name = cookiepre + name;
    var cookiestr = '';
    cookiestr = name + '=' + cval + '; path=' + cookiepath;
    if(cookiedomain != '') {
        cookiestr += '; domain=' + cookiedomain;
    }
    cookiestr += '; expires=' + expires.toGMTString();
    alert(cookiestr);
    document.cookie = cookiestr;
}
//��ȡ���λ��
function get_mousepos(e) {
    var x, y;
    var e = e||window.event;
    return {x:e.clientX + document.body.scrollLeft + document.documentElement.scrollLeft, y:e.clientY + document.body.scrollTop + document.documentElement.scrollTop};
}
//�ƶ���
function tip_start(obj, not_move) {
    var s = $(obj);
    if($("#tipdiv")[0] == null) {
        $(document.body).append("<div id=\"tipdiv\" style=\"position:absolute;left:0;top:0;display:none;\"></div>");
    }
    var t = $("#tipdiv");
    var one = false;
    s.mousemove(function(e) {
        if(not_move==1 && one) return;
        var mouse = get_mousepos(e);
        t.css("left", mouse.x + 10 + 'px');
        t.css("top", mouse.y + 10 + 'px');
        t.html("<img src='" + s.attr("src")+"' />");
        t.css("display", '');
        one = true;
    });
    s.mouseout(function() {
        t.css("display", 'none');
    });
}
//��Ajax��֤��
function check_seccode(value) {
    if(!value) {
        $('#msg_seccode').html('<font color="red">��������֤��.</font>').show();
        return false;
    }
    $.post(Url('modoer/ajax/op/check_seccode'), {'seccode':value,'in_ajax':1}, function(data) {
        $('#msg_seccode').html(data).show();
    });
    return true;
}
//ͳ�ƺͼ����������
function record_charlen(obj,max,d_id) {
    var con = $(obj);
    var len = con.val().length;
    if(d_id) {
        $('#'+d_id).text(len);
    }
}
//�������code
function insert_smilies(cid,smid) {
    var text = "[/"+smid+"/]";
    $('#'+cid).insertAtCaret(text);
}
//����ѡ��
function area_select(level,id,value,select) {
    if(!$('#' + id)[0]) return;
    var sel = $('#' + id).empty();
    var selected = '';
    if(level>=2) sel.append('<option value='+value+'>=����=</option>');
    $.each(_area.level[level], function(i, n) {
        $.each(_area.level[level][i],function(j, m) {
            if(!value || (value && value==i)) {
                selected = select == _area.level[level][i][j] ? ' selected="selected"' : '';
                sel.append($('<option value='+_area.level[level][i][j]+selected+'>'+_area.data[_area.level[level][i][j]]+'</option>'));
            }
        });
    });
    sel.css('width','auto');
}
//��������
function area_select_link(level) {
    var sel = $('#area_' + level);
    if(sel.val()=='') return;
    var next = level + 1;
    area_select(level,'area_' + next, sel.val());
    if(next <= 2) area_select_link(next);
}
//ȡ���ϼ�����id
function area_parent_id(aid) {
    var result = 0;
    $.each(_area.level, function(i,n) {
        $.each(n, function(_i, _n) {
            $.each(_n, function(__i, __n) {
                if(__n == aid) {
                    result = _i;
                }
            });
        });
    });
    return result;
}
//����ָ����aid���Զ�����3����������
function area_auto_select(aid) {
    var link = new Array();
    link.push(aid);
    var pid = area_parent_id(aid);
    i=0;
    while (pid>0) {
        i++;
        link.push(pid);
        pid=area_parent_id(pid);
    }
    link = link.reverse();
    var value = 0;
    for (var i=0; i<3; i++) {
        area_select(i,'area_'+(i+1),value,link[i]);
        value = link[i];
        if(i>=link.length) {
            area_select_link(i);
        }
    }
}

//DIV�����滻,���������ع�����Ϊ
function replace_content(adlist) {
    if(!adlist) return;
    adlist = adlist.replace(' ','');
    adlist = adlist.split(',');
    for(i=0; i<adlist.length; i++) {
        if(!adlist[i]) continue;
        var adv = adlist[i];
        var adv = adv.split('=');
        if(document.getElementById(adv[0]) != null) {
            document.getElementById(adv[0]).innerHTML = document.getElementById(adv[1]).innerHTML;
             document.getElementById(adv[1]).innerHTML = '';
        }
    }
}
//�����б���ʾ��ʽ��˳��
function list_display(keyname, value, url) {
    set_cookie('list_display_'+keyname, value, 0);
    if(!url) {
        document.location.reload();
    } else {
        document.location = url;
    }
}
//����������
function create_form_urlparam(formname) {
    var body = split = '';
    var form=document.forms[formname];
    for(var i=0;i<form.length;i++){
        //����ǵ�ѡ��ť����ѡ�򡢵�ѡ������
        if (form.elements[i].type == "radio" || form.elements[i].type == "checkbox" || form.elements[i].type == "select" ) {
            if(form.elements[i].checked && form.elements[i].name != ""){
                body += encodeURI(form.elements[i].name) + '=' + encodeURI(form.elements[i].value) + split;
            }
        }
        //����Ƕ�ѡ������
        else if (form.elements[i].type == "select-multiple" && form.elements[i].name != "") {
            for(var sm=0;sm<form.elements[i].length;sm++){
                if(form.elements[i][sm].selected) {
                    body += encodeURI(form.elements[i].name) + '=' + encodeURI(form.elements[i][sm].value) + split;
                }
            }
        }
        //Button��Hidden��Password��Submit��Text��Textarea���ı�����
        else {
            if (form.elements[i].name != "") {
                body += encodeURI(form.elements[i].name) + '=' + encodeURI(form.elements[i].value) + split;
            }
        }
        split = '&';
    }
}

function find_pos(obj) {
    var x = y = 0;
    var obj1=obj2=obj;
    if(obj1.offsetParent) {
        while (obj1.offsetParent) {
            x += obj1.offsetLeft;
            obj1 = obj1.offsetParent;
        }
    } else if (obj1.x) {
        x += obj1.x;
    }
    if(obj2.offsetParent) {
        while (obj2.offsetParent) {
            y += obj2.offsetTop;
            obj2 = obj2.offsetParent;
        }
    } else if (obj2.y) {
        y += obj2.y;
    }
    return {x:x,y:y};
}

function jslocation (url) {
    while (url.indexOf('&amp;') > 0) {
        url = url.replace('&amp;', '&');
    }
    document.location = url;
}

function round (value, precision, mode) {
    var m, f, isHalf, sgn; // helper variables
    precision |= 0; // making sure precision is integer
    m = Math.pow(10, precision);
    value *= m;
    sgn = (value > 0) | -(value < 0); // sign of the number
    isHalf = value % 1 === 0.5 * sgn;
    f = Math.floor(value);
    if (isHalf) {
        switch (mode) {
        case 'PHP_ROUND_HALF_DOWN':
            value = f + (sgn < 0); // rounds .5 toward zero
            break;
        case 'PHP_ROUND_HALF_EVEN':
            value = f + (f % 2 * sgn); // rouds .5 towards the next even integer
            break;
        case 'PHP_ROUND_HALF_ODD':
            value = f + !(f % 2); // rounds .5 towards the next odd integer
            break;
        default:
            value = f + (sgn > 0); // rounds .5 away from zero
        }
    }
    return (isHalf ? value : Math.round(value)) / m;
}

function strip_tags (input, allowed) {
    allowed = (((allowed || "") + "").toLowerCase().match(/<[a-z][a-z0-9]*>/g) || []).join(''); 
    var tags = /<\/?([a-z][a-z0-9]*)\b[^>]*>/gi,
        commentsAndPhpTags = /<!--[\s\S]*?-->|<\?(?:php)?[\s\S]*?\?>/gi;
    return input.replace(commentsAndPhpTags, '').replace(tags, function ($0, $1) {
        return allowed.indexOf('<' + $1.toLowerCase() + '>') > -1 ? $0 : '';
    });
}

function basename (path, suffix) {
    var b = path.replace(/^.*[\/\\]/g, '');
    if (typeof(suffix) == 'string' && b.substr(b.length - suffix.length) == suffix) {
        b = b.substr(0, b.length - suffix.length);
    }
    return b;
}

(function($){$.fn.extend({insertAtCaret:function(myValue){var $t=$(this)[0];if(document.selection){this.focus();sel=document.selection.createRange();sel.text=myValue;this.focus();}else
if($t.selectionStart||$t.selectionStart=='0'){var startPos=$t.selectionStart;var endPos=$t.selectionEnd;var scrollTop=$t.scrollTop;$t.value=$t.value.substring(0,startPos)+myValue+$t.value.substring(endPos,$t.value.length);this.focus();$t.selectionStart=startPos+myValue.length;$t.selectionEnd=startPos+myValue.length;$t.scrollTop=scrollTop;}else{this.value+=myValue;this.focus();}}})})(jQuery);