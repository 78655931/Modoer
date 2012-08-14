/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/

(function($) {

    $.fn.item_subject_search = function(options) {

        $.fn.item_subject_search.defaults = {
            sid:0,
            sid_name:"sid",     // 隐藏控件name
            sid_value:"_SID_",  //sid值的现实方式，支持_SID_,_NAME_,_CATID_三种的组合
            validator:'',       // sid_name的验证
            catid:0,            // 搜索指定的主分类
            categorys:'',       // 分类的列表
            multi:false,
            multi_split:',',    //多选项的分割字符

            input_id:'',//"search_subject_ipt",
            btn_id:'',//"search_subject_btn",
            result_id:'',//"search_subject_div",

            show_event:null, //显示搜索结果的事件
            result_click:null, //选择某条数据的事件

            hide_keyword:false, //是否隐藏搜索框
            current_ready:false, //是否进入等待状态
            getfocus:false, //是否获取焦点
            change_disable:false, //是否允许更改
            mysubject:false, //显示我的主题按钮
            myreviewed:false, //显示我点评过的主题按钮
            myfavorite:false, //显示我收藏的主题按钮

            input_class:"txtbox2",
            btn_class:"btn2",
            result_css:"item_search_result",
            btn_text:'搜索',
            close_text:'关闭',
            cancel_text:'取消',
            select_text:'反选',
            ok_text:'确定',
            delete_text:'删除',
            change_text:'[更改]',
            clear_text:'[清空]',
            mysubject_text:'我的主题',
            myreviewed_text:'点评过的主题',
            myfavorite_text:'关注的主题',
            
            lng_subject_foo_legend:'已关联的主题',
            lng_keyword_empty:'您未填写关键字。',
            lng_search_wait:'正在搜索，请稍后...',
            lang_ajax_load_error:'信息读取失败，可能网络忙碌，请稍后尝试。'
        };

        var search = $(this);
        var opts = $.extend({}, $.fn.item_subject_search.defaults, options);
        var forward = search.html().trim();
        var multi_fooid = _name2id(opts.sid_name) + "_multi";

        //默认id处理,避免同一个页面多次加载时id冲突
        var tandid = Math.floor(Math.random()*10+1) + Math.floor(Math.random()*10+1);
        if(opts.input_id=='') {
            opts.input_id += 'search_subject_ipt' + tandid;
        }
        if(opts.btn_id=='') {
            opts.btn_id += 'search_subject_btn' + tandid;
        }
        if(opts.result_id=='') {
            opts.result_id += 'search_subject_div' + tandid;
        }

        if(opts.sid_name) {
            var sid = $('<input type="hidden"></input>');
            sid.attr('name',opts.sid_name);
            if(opts.validator) sid.attr('validator',opts.validator);
        }

        if(!opts.current_ready) {
            if(opts.categorys) {
                var category = $('<select></select>');
            }
            var keyword = $('<input type="text"></input>');
            var sbtn = $('<button type="button"></button>');
            if(opts.mysubject) var mysubject_btn = $('<button type="button"></button>');
            if(opts.myreviewed) var myreviewed_btn = $('<button type="button"></button>');
            if(opts.myfavorite) var myfavorite_btn = $('<button type="button"></button>');
            var result = $('<div></div>');
            if(forward) {
                var cbtn = $('<button type="button"></button>');
            }
        }

        //搜索界面初始化
        return this.each(function() {
            if(forward!='' && opts.current_ready) {
                if(!opts.change_disable) {
                    var c = $('<a href="###"></a>');
                    change_click(c.attr('id','search_change_a'));
                    search.append('&nbsp;').append(c);
                    if(sid) search.append(sid.val(opts.sid));
                    var d = $('<a href="###"></a>');
                    clear_click(d.attr('id','search_clear_a'));
                    search.append('&nbsp;').append(d);
                }
            } else {
                search.empty();
                if(category) {
                    category.html(opts.categorys).change(function(){
                        opts.catid = category.val();
                    });
                    search.append(category).append('&nbsp;');
                    category.change();
                }
                keyword.attr('id',opts.input_id).addClass(opts.input_class).keydown(function(event){
                    if(event.keyCode == 13) {
                        if(search_subject()) return false;
                        return false;
                    }
                });
                sbtn.attr('id',opts.btn_id).text(opts.btn_text).addClass(opts.btn_class).click(function(){search_subject()});
                search.append(keyword).append('&nbsp;').append(sbtn);
                if(mysubject_btn) {
                    mysubject_btn.text(opts.mysubject_text).addClass(opts.btn_class).click(function(){mysubject()});
                    search.append('&nbsp;').append(mysubject_btn);
                }
                if(myreviewed_btn) {
                    myreviewed_btn.text(opts.myreviewed_text).addClass(opts.btn_class).click(function(){myreviewed()});
                    search.append('&nbsp;').append(myreviewed_btn);
                }
                if(myfavorite_btn) {
                    myfavorite_btn.text(opts.myfavorite_text).addClass(opts.btn_class).click(function(){myfavorite()});
                    search.append('&nbsp;').append(myfavorite_btn);
                }
                result.attr('id',opts.result_id).css('display','none').addClass(opts.result_css);
                if(cbtn) {
                    cbtn.text(opts.cancel_text).addClass(opts.btn_class).click(function(){cancel_search()});
                    search.append('&nbsp;').append(cbtn);
                }
                if(sid) {
                    search.append(sid);
                }
                search.append(result);
                if(opts.getfocus) keyword.focus();
                //自动搜索
                if(opts.sid !='' && opts.multi) {
                    $(document).ready(function(){ search_sids(); });
                }
            }
            opts.current_ready = false;
        });

        //搜索
        function search_subject() {
            var kw = keyword.val();
            if(kw.trim() == '') {
                alert(opts.lng_keyword_empty); 
                return false;
            }
            pid = opts.catid;
            get_data(Url('item/ajax/do/subject/op/search/pid/'+pid), { 'in_ajax':1,'keyword':kw });
        }

        //我的主题
        function mysubject() {
            //get_data();
        }

        //我点评的主题
        function myreviewed() {
            get_data(Url('item/ajax/do/subject/op/myreviewed'), { 'in_ajax':1 });
        }

        //我收藏的主题
        function myfavorite() {
            get_data(Url('item/ajax/do/subject/op/myfavorite'), { 'in_ajax':1 });
        }

        //指定sid的主题
        function search_sids() {
            if(!opts.sid) return ;
            sid.val(opts.sid);
            get_data(Url('item/ajax/do/subject/op/sids'), { sids:opts.sid, 'in_ajax':1 }, _load_subjects);
        }

        //获取数据
        function get_data(url,params,result_event) {
            //msgOpen(opts.lng_search_wait, 180,80);
            sbtn.text(opts.lng_search_wait);
            $.post(url, params, function(data) {
                //msgClose();
                sbtn.text(opts.btn_text);
                if(data == null) {
                    alert(opts.lang_ajax_load_error);
                } else if (data.match(/\{\s+caption:".*",message:".*".*\s*\}/)) {
                    myAlert(data);
                } else {
                    if(result_event) {
                        result_event(data);
                    } else if(opts.show_event) {
                        opts.show_event(data);
                    } else {
                        select_search(data);
                    }
                }
            });
        }

        //显示搜索的结果
        function select_search(data) {
            result.empty();
            var ul = $('<ul></ul>').attr('id', 'ul_' + _name2id(opts.sid_name));
            if ($.browser.msie) {
                xml = new ActiveXObject("Microsoft.XMLDOM");
                xml.async = false;
                xml.loadXML(data);
                var offset = keyword.offset(); 
                result.css('top',offset.top + 25 + 'px');
                result.css('left',offset.left);
            } else {
                xml = data;
            }
            $(xml).find('subject').each(function() {
                var li = $("<li></li>");
                var att_sid = $(this).find('sid').text();
                var att_catid = $(this).find('catid').text();
                var att_name = $(this).find('name').text();
                if(opts.multi) {
                    var check = $("<input type=\"checkbox\" value=\""+$(this).find('sid').text()+"\">").
                        attr('attr_sid', att_sid).
                        attr('attr_catid', att_catid).
                        attr('attr_name', att_name);
                    li.append(check);
                }
                var span = $('<span></span>').html($(this).find('title').text());
                li.append(span).attr('catid', att_catid).attr('sid', att_sid).attr('name', att_name).
                    mouseover(function(){$(this).css("background","#C1EBFF")}).
                    mouseout(function(){$(this).css("background","#FFF")});
                span.click(function() {
                    if(opts.multi) {
                        check.attr('checked', !check.attr('checked'));
                    } else if(opts.location) {
                        var url = opts.location;
                        url = url.replace('_SID_', att_sid);
                        url = url.replace('_NAME_', att_name);
                        url = url.replace('_CATID_', att_catid);
                        jslocation(url);
                    } else {
                        if(opts.result_click)
                            opts.result_click($(this).parent());
                        else
                            result_click($(this).parent());
                    }
                });
                ul.append(li);
            });
            result.append(ul);
            if(opts.appendhtml) {
                if(opts.appendhtml.inlist)
                    ul.append(opts.appendhtml.html);
                else 
                    result.append(opts.appendhtml.html);
            }
            var div = $("<div></div>").css({textAlign:'right',background:'#F7F7F7'});
            var s = $('<a href="###">['+opts.select_text+']</a>').css('margin','5px').click(function() {
                ul.find('li input').each(function(i) {
                    $(this).attr('checked', !$(this).attr('checked'));
                });
            });
            var c = $('<a href="###">[<b>'+opts.ok_text+'</b>]</a>').css('margin','5px').click(function() {
                button_ok(ul);
                $('#'+opts.result_id).hide();
            });
            var a = $('<a href="###">['+opts.close_text+']</a>').css('margin','5px').click(function() {
                $('#'+opts.result_id).hide();
            });
            if(opts.multi) div.append(s).append(c);
            result.append(div.append(a)).show();
        }

        //取消搜索
        function cancel_search() {
            search.empty().append(forward);
            change_click($('#search_change_a'));
            clear_click($('#search_clear_a'));
        }

        //默认的点击行为
        function result_click(obj) {
            opts.sid = obj.attr('sid');
            var subject_name = obj.attr('name');
            if(opts.hide_keyword) {
                var a = $('<a href="'+Url("item/item/id/"+opts.sid)+'">'+subject_name+'</a>');
                a.attr('target','_blank');
                search.empty().append(a);
                if(!opts.change_disable) {
                    var c = $('<a href="###"></a>');
                    c.attr('id','search_change_a');
                    change_click(c);
                    search.append('&nbsp;').append(c);
                    var d = $('<a href="###"></a>');
                    clear_click(d.attr('id','search_clear_a'));
                    search.append('&nbsp;').append(d);
                }
                if(sid) search.append(sid.val(opts.sid));
            } else {
                if(sid[0]) sid.val(sid_value);
                if(keyword[0]) keyword.val(obj.attr('name'));
                result.hide();
            }
        }

        //多选的确定按钮
        function button_ok(obj) {
            obj.find('li input').each(function(i) {
                if($(this).attr('checked')) {
                    var add_sid = $(this).attr('attr_sid');
                    _subject_list_add(add_sid, $(this).attr('attr_name'));
                }
            });
        }

        //获取指定sid主题后，直接显示在列表内
        function _load_subjects(data) {
            sid.val('');
            if ($.browser.msie) {
                xml = new ActiveXObject("Microsoft.XMLDOM");
                xml.async = false;
                xml.loadXML(data);
            } else {
                xml = data;
            }
            $(xml).find('subject').each(function() {
                var sid = $(this).find('sid').text();
                var name = $(this).find('name').text();
                _subject_list_add(sid,name);
            });
        }

        //加入主题列表
        function _subject_list_add(sid, name) {
            if(!_sid_add(sid)) return;
            var foo = $('#' + multi_fooid);
            if(!foo[0]) {
                var fieldset = $('<fieldset></fieldset>').append($('<legend>'+opts.lng_subject_foo_legend+'</legend>')).addClass('subject_search_multi');
                if ($.browser.msie) fieldset.css('float','left');
                foo = $('<div></div>').attr('id', multi_fooid).addClass('subjectlist');
                search.append(fieldset.append(foo));
            }
            var close = $('<a href="###">[X]</a>').css('margin-right','5px').css('float','right').attr('title',opts.delete_text).
                click(function(){
                    delete_one($(this).parent());
                });
            var a = $('<a></a>').attr('href',Url("item/detail/id/"+sid)).
                    attr('target','_blank').append(name).css('padding-left','3px');
            foo.append($("<li></li>").append(close).append(a).attr('sid',sid).
                    mouseover(function(){$(this).css("background","#ffe8ff")}).
                    mouseout(function(){$(this).css("background","#FFF")}));
        }

        //删除多主题列表内也某一主题
        function delete_one(obj) {
            obj.remove();
            _sid_delete(obj.attr('sid'));
            if($('#'+multi_fooid).html()=='') {
                $('#'+multi_fooid).parent().remove();
                sid.val('');
            }
        }

        //取消按钮的行为
        function change_click(obj) {
            obj.text(opts.change_text).click(function() {
                opts.getfocus = true;
                search.item_subject_search(opts);
                return false;
            });
        }

        //清空按钮的行为
        function clear_click(obj) {
            obj.text(opts.clear_text).click(function() {
                opts.getfocus = true;
                sid.val('');
                search.item_subject_search(opts);
                return false;
            });
        }

        //多选时保存sid集合时，进行增加操作
        function _sid_add(add_sid) {
            var sval = sid.val();
            var r='';
            if(!sval) {
                r=add_sid;
            } else {
                var ss = sval.split(opts.multi_split);
                if($.inArray(add_sid,ss) > -1) return false;
                r = sval + ',' + add_sid;
            }
            sid.val(r);
            return true;
        }

        //多选时保存sid集合时，进行删除操作
        function _sid_delete(del_sid) {
            var sval = sid.val();
            if(!sval) return;
            ss = sval.split(opts.multi_split);
            var r=sp='';
            for (var i=0; i<ss.length; i++) {
                if(del_sid==ss[i]) continue;
                r += sp + ss[i];
                sp=opts.multi_split;
            }
            sid.val(r);
        }

        //name解析为id，替换[]
        function _name2id (name) {
            name = name.replace('[','_');
            name = name.replace(']','_');
            return name;
        }

    }

})(jQuery);

//取得我的主题列表
function item_subject_manage(get_url,next_url) {
    if(!get_url) get_url = Url("item/member/ac/g_subject/op/mysubject");
    if(!next_url) next_url = location.href;
    //alert(get_url+"\n"+next_url);
    $.post(get_url, { 'in_ajax':1 },
    function(result) {
        if(result == null) {
            alert('信息读取失败，可能网络忙碌，请稍后尝试。');
        } else if (result.match(/\{\s+caption:".*",message:".*".*\s*\}/)) {
            myAlert(result);
        } else {
            var select = $('<select size=10></select>').html(result)
                .css({width:'100%',height:'250px'});
            select.dblclick(function() {
                item_subject_manage_select(this.value,next_url);
                dlgClose();
            });
            dlgOpen('更换主题(双击选择)', select, 500, 300);
        }
    });
}

//选择助手里当前操作的主题
function item_subject_manage_select(sid,next_url) {
    if (!is_numeric(sid)) {
        alert('无效的SID'); 
        return;
    }
    var now_sid = get_cookie('manage_subject');
    if(now_sid==sid) { alert('未更改。');return; }
    set_cookie('manage_subject',sid);
    if(next_url) document.location=next_url;
}

function search_subject(cateid, kwyid, callback) {
    var kw = $('#'+kwyid).val();
    if(kw.trim() == '') {
        alert('未填写关键字。'); return;
    }
    if(cateid!='' && $('#'+cateid)[0]) {
        pid = $('#'+cateid).val();
    } else {
        pid = 0;
    }
    msgOpen('正在搜索，请稍后...', 180,80);
    $.post(Url('item/ajax/do/subject/op/search/pid/'+pid+'/in_ajax/1'), { 'keyword':kw },
    function(result) {
        msgClose();
        if(result == null) {
            alert('信息读取失败，可能网络忙碌，请稍后尝试。');
        } else if (result.match(/\{\s+caption:".*",message:".*".*\s*\}/)) {
            myAlert(result);
        } else {
            if(callback) {
                callback(result,kwyid);
            } else {
                dlgOpen('查找主题', result, 400, 250);
            }
        }
    });
}

function search_myfavorite(callback) {
    $.post(Url('item/ajax/do/subject/op/myfavorite'), { 'in_ajax':1 },
    function(result) {
        if(result == null) {
            alert('信息读取失败，可能网络忙碌，请稍后尝试。');
        } else if (result.match(/\{\s+caption:".*",message:".*".*\s*\}/)) {
            myAlert(result);
        } else {
            if(callback) {
                callback(result);
            } else {
                dlgOpen('查找主题', result, 400, 250);
            }
        }
    });
}

function search_myreviewed(callback) {
    $.post(Url('item/ajax/do/subject/op/myreviewed'), { 'in_ajax':1 },
    function(result) {
        if(result == null) {
            alert('信息读取失败，可能网络忙碌，请稍后尝试。');
        } else if (result.match(/\{\s+caption:".*",message:".*".*\s*\}/)) {
            myAlert(result);
        } else {
            if(callback) {
                callback(result);
            } else {
                dlgOpen('查找主题', result, 400, 250);
            }
        }
    });
}

//检测主题名称是否存在
function item_subject_check_exists(txtboxid,cityid,pid,show_list) {
    var name = $('#'+txtboxid).val();
    if(name == '') {
        alert('未填写主题名称.'); 
        return;
    }
    if(!cityid) cityid = 0;
    if(!pid) pid = 0;
    show_list = show_list ? 1 : 0;
    $.post(Url('item/ajax/do/subject/op/exists'), { 'name':name, 'pid':pid, 'cityid':cityid, 'show_list':show_list, 'in_ajax':1 },
    function(result) {
        if(result == null) {
            alert('信息读取失败，可能网络忙碌，请稍后尝试。');
        } else if (result.match(/\{\s+caption:".*",message:".*".*\s*\}/)) {
            myAlert(result);
        } else {
            var select = $('<select size=10></select>').html(result)
                .css({width:'100%',height:'250px'});
            dlgOpen('查看相关主题', select, 500, 300);
        }
    });
}

function clear_cookie_subject(pid) {
	var cookie_count=9629;
    $.post(getUrl('item','ajax','do=subject&op=clear_cookie_subject&in_ajax=1'),
    { pid:pid },
    function(result) {});
    $('#cookieitems').css('display','none');
}

function post_log(sid) {
    if (!is_numeric(sid)) {
        alert('无效的ID'); 
        return;
    }
    $.post(getUrl('item','ajax','do=subject&op=post_log&in_ajax=1'), 
    { sid:sid },
    function(result) {
        if(result == null) {
            alert('信息读取失败，可能网络忙碌，请稍后尝试。');
        } else if (result.match(/\{\s+caption:".*",message:".*".*\s*\}/)) {
            myAlert(result);
        } else {
            dlgOpen('补充信息', result, 400, 260);
        }
    });
}

function post_map(sid) {
    if (!is_numeric(sid)) {
        alert('无效的ID'); 
        return;
    }
    $.post(getUrl('item','ajax','do=subject&op=post_map&in_ajax=1'), 
    { sid:sid },
    function(result) {
        if(result == null) {
            alert('信息读取失败，可能网络忙碌，请稍后尝试。');
        } else if (result.match(/\{\s+caption:".*",message:".*".*\s*\}/)) {
            myAlert(result);
        } else {

            dlgOpen('地图报错', result, 500, 400);

            $('#mapbtn1').click(
                function() {
                    $(document.getElementById('ifupmap_map').contentWindow.document.body).find('#markbtn').click();
                }
            );

            $('#mapbtn2').click(
                function() {
                    $("#frm_map").find("[name=p1]").val($(document.getElementById('ifupmap_map').contentWindow.document.body).find('#point1').val());
                    $("#frm_map").find("[name=p2]").val($(document.getElementById('ifupmap_map').contentWindow.document.body).find('#point2').val());
                    if($("#frm_map").find("[name=p1]").val() == '' || $("#frm_map").find("[name=p2]").val() == '') {
                        alert('您尚未完成标注。');
                        return;
                    }
                    //提交
                    ajaxPost('frm_map', '', null);
                }
            );

        }
    });
}

function get_pictures(sid, page) {
    if (!is_numeric(sid)) {
        alert('prictue:无效的ID'); 
        return;
    }
    //loadscript('lightbox');
    if(!page) page = 1;
    $.post(getUrl('item','ajax','do=picture&op=get&in_ajax=1&page='+page), 
    { sid:sid },
    function(result) {
        if(result == null) {
            alert('信息读取失败，可能网络忙碌，请稍后尝试。');
        } else if (result.match(/\{\s+caption:".*",message:".*".*\s*\}/)) {
            myAlert(result);
        } else {
            $('#itempics').html(result);
        }
    });
}

function get_subject_taoke(sid) {
    if (!is_numeric(sid)) {
        alert('taoke:无效的SID'); return;
    }
    $.post(Url('item/ajax/do/subject/op/get_taoke_detail/in_ajax/1'), 
    { sid:sid },
    function(result) {
        if(result == null) {
            alert('信息读取失败，可能网络忙碌，请稍后尝试。');
        } else if (result.match(/\{\s+caption:".*",message:".*".*\s*\}/)) {
            myAlert(result);
        } else {
            $('#taoke_field_foo').html(result);
        }
    });
}

function select_subject_thumb(sid,page) {
    if (!is_numeric(sid)) {
        alert('无效的SID'); return;
    }
    $.post(Url('item/album/sid/'+sid+'/page/'+page), { op:'selectpic', in_ajax:1}, 
    function(data) {
        if(data) {
            dlgClose();
            dlgOpen('选择图片', data, 400, 280);
        } else {
            alert('没有可用图片！');
        }
    });
}

function insert_subject_thumb(src) {
    $("#thumb").val(src);
    dlgClose();
}

function get_membereffect(sid, effect, member) {
    if (!is_numeric(sid)) {
        alert('无效的ID'); 
        return;
    }

    if(!effect) effect = 'effect1';
    if(!member) member = '0';

    $.post(getUrl('item','ajax','do=subject&op=get_membereffect&in_ajax=1'), 
    { sid:sid, effect:effect, member:member },
    function(result) {
        if(result == null) {
            alert('信息读取失败，可能网络忙碌，请稍后尝试。');
        } else if (is_message(result)) {
            myAlert(result);
        } else if (member == 'Y') {
            result += '<div class="clear"></div>';
            $('#effect').html(result);
            $('#effect_floor').css('display','');
        } else {
            var v = result.split('|');
            is_numeric(v[0]) && $('#num_effect1').text(v[0]);
            is_numeric(v[1]) &&  $('#num_effect2').text(v[1]);
        }
    });
}

function post_membereffect(sid, effect) {
    if (!is_numeric(sid)) {
        alert('无效的ID'); 
        return;
    }
    $.post(getUrl('item','ajax','do=subject&op=post_membereffect&in_ajax=1'), 
    { sid:sid, effect:effect },
    function(result) {
        if(result == null) {
            alert('信息读取失败，可能网络忙碌，请稍后尝试。');
        } else if (result.match(/\{\s+caption:".*",message:".*".*\s*\}/)) {
            myAlert(result);
        } else {
            var v = result.split('|');
            $('#num_effect1').text(v[0]);
            $('#num_effect2').text(v[1]);
        }
    });
}

function picture_page(sid, page) {
    if (!is_numeric(sid)) {
        alert('无效的ID'); 
        return;
    }
    if(!page) page = 1;
    $.post(getUrl('item','pic','in_ajax=1&sid='+sid+'&page='+page), 
    { next:page },
    function(result) {
        if(result == null) {
            alert('信息读取失败，可能网络忙碌，请稍后尝试。');
        } else if (result.match(/\{\s+caption:".*",message:".*".*\s*\}/)) {
            myAlert(result);
        } else {
            $('#pic').html(result);
        }
    });
}

function add_favorite(sid) {
    if (!is_numeric(sid)) {alert('无效的ID'); return;}
    $.post(Url('item/member/ac/favorite/op/add'), 
    { sid:sid,in_ajax:1 },
    function(result) {
        if(result == null) {
            alert('信息读取失败，可能网络忙碌，请稍后尝试。');
        } else if (result.match(/\{\s+caption:".*",message:".*".*\s*\}/)) {
            myAlert(result);
        }
    });
}

function open_video(url,width,height,operation,place) {
    var content = "<div id=\"vide_top\" style=\"text-align:center;\"></div><div id=\"video_dlg\"></div><div id=\"vide_bottom\" style=\"text-align:center;\"></div>";
    if(!width) width = 630;
    if(!height) height = 550;
    if(!place) place = 'bottom';
    dlgOpen('播放视频', content, width, height);
    if(operation) {
        $('#vide_'+place).html(operation).css('margin','5px');
        var x = $('#vide_'+place).height();
        dlgSize(width, height + x);
    }
    var so = new SWFObject(url, 'video2', width-20, height-50, 7, '#FFF');
    so.addParam("allowScriptAccess", "always");
    so.addParam("allowFullScreen", "true");
    so.addParam("wmode", "transparent");
    so.write("video_dlg");
}
//添加主题3级分类ajax
function item_category_level3(obj,sub_id,sid) {
    var catid = $(obj).val();
    if(!is_numeric(catid) || catid < 1) return;
    if(!sid) sid = 0;
    $.post(Url('item/ajax/do/subject/op/get_sub_cats'), 
    { in_ajax:1,catid:catid,sid:sid },
    function(result) {
        if(result == null) {
            alert('信息读取失败，可能网络忙碌，请稍后尝试。');
        } else if (result.match(/\{\s+caption:".*",message:".*".*\s*\}/)) {
            myAlert(result);
        } else {
            $('#'+sub_id).empty().html(result);
            $('#'+sub_id).mchecklist();
            //result
        }
    });
}
//分类选择
function item_category_select(level,id,value,select) {
    var sel = $('#' + id).empty();
    var selected = '';
    if(level>=2) {
        sel.append('<option value='+value+'>=不限=</option>');
    }
    $.each(_item_cate.level[level], function(i, n) {
        $.each(_item_cate.level[level][i],function(j, m) {
            if((!value || value && value==i) && _item_cate.level[level][i][j]>0) {
                selected = select == _item_cate.level[level][i][j] ? ' selected="selected"' : '';
                sel.append($('<option value='+_item_cate.level[level][i][j]+selected+'>'+_item_cate.data[_item_cate.level[level][i][j]]+'</option>'));
            }
        });
    });
    sel.css('width','auto');
}

//分类联动
function item_category_select_link(level) {
    var sel = $('#category_' + level);
    if(!sel.val()) {
        sel.append('<option value=0>=不限=</option>');
    }
    var next = level + 1;
    item_category_select(level,'category_' + next, sel.val());
    if(next <= 2) item_category_select_link(next);
}

//取得上级分类id
function item_category_parent_id(aid) {
    var result = 0;
    $.each(_item_cate.level, function(i,n) {
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

//根据指定的catid，自定设置3级联动分类
function item_category_auto_select(aid) {
    var link = new Array();
    link.push(aid);
    var pid = item_category_parent_id(aid);
    i = 0;
    while (pid>0) {
        i++;
        link.push(pid);
        pid = item_category_parent_id(pid);
    }
    link = link.reverse();
    var value = 0;
    for (var i=0; i<3; i++) {
        item_category_select(i,'category_'+(i+1),value,link[i]);
        value = link[i];
        if(i >= link.length) {
            item_category_select_link(i);
        }
    }
}

//增加主题印象
function item_impress_add(sid) {
    if (!is_numeric(sid)) {alert('无效的SID'); return;}
    $.post(Url('item/member/ac/impress/op/add'), 
    { sid:sid,in_ajax:1 },
    function(result) {
        if(result == null) {
            alert('信息读取失败，可能网络忙碌，请稍后尝试。');
        } else if (result.match(/\{\s+caption:".*",message:".*".*\s*\}/)) {
            myAlert(result);
        } else {
            dlgOpen('添加印象', result, 560, 160);
        }
    });
}

//获取印象
function item_impress_get(sid,read_full) {
    if (!is_numeric(sid)) {alert('无效的SID'); return;}
    if(!read_full) read_full = 0;
    $.post(Url('item/member/ac/impress/op/get'), 
    { sid:sid,full:read_full,in_ajax:1 },
    function(result) {
        if(result == null) {
            alert('信息读取失败，可能网络忙碌，请稍后尝试。');
        } else if (result.match(/\{\s+caption:".*",message:".*".*\s*\}/)) {
            myAlert(result);
        } else {
            var s = result.split("\n");
            var html = $('<div id="subject_imparess_full"></div>');
            for (var i=0; i<s.length; i++) {
                if(!s[i]) continue;
                var v=s[i].split("|");
                html.append($('<span></span>').text(v[0]+' ').addClass(v[1]));
            }
            if(read_full) {
                dlgOpen('主题印象', html, 560, 200);
            } else {
                $('#subject_impress').html(html.html());
            }
        }
    });
}

//点评内置搜索结果显示
function item_subject_search_for_review(x) {
    $('#search_result').css('display','').html(x);
    $("#search_result li").each(function(i) {
        $(this).mouseover(function(){$(this).css("background","#C1EBFF")})
            .mouseout(function(){$(this).css("background","#FFF")})
            .click(function(){
            //alert(Url('item/member/ac/$ac/sid/'+$(this).attr('sid')));return;
                document.location = Url('review/member/ac/add/type/item_subject/id/'+$(this).attr('sid'));
            });
    });
    $('#search_result').append('<div><a href="'+Url("item/member/ac/subject_add")+'"><span class="font_1">以上都不是，我要添加新主题</span></a></div>');
}

//主题关联搜索
function item_subject_search_for_customfield(data,kwyid) {
    var foo_name = kwyid.replace('_keyword','_src');
    var foo = $('#'+foo_name);
    foo.empty();
    if ($.browser.msie) {
        xml = new ActiveXObject("Microsoft.XMLDOM");
        xml.async = false;
        xml.loadXML(data);
    } else {
        xml = data;
    }
    $(xml).find('subject').each(function() {
        var sid = $(this).find('sid').text();
        var name = $(this).find('name').text();
        var value = sid + "\t" + name;
        foo.append("<option value=\""+value+"\">"+name+"</option>"); 
    });
}

function item_subject_search_for_customfield_exchange(id,src,dst,savesrc) {
    var index = $("#"+src).get(0).selectedIndex;
    if(index<0) return;
    var selected = $("#"+src+" option[index='"+index+"']");
    var value = selected.attr('value');
    var breakact = false;
    $("#"+dst + " option").each(function(){
        if($(this).val() == value) {
            selected.remove();
            breakact = true;
        }
    });
    if(breakact) return;
    $("#"+dst).append(selected);
    var dstselect = id + '_dst';
    var id_value = '';
    $("#"+dstselect + " option").each(function(){
        id_value += (id_value ? "\r\n" : "") + $(this).val();
    });
    $("#"+id).val(id_value);
}

//新建相册
function item_create_album(obj) {
    var name = prompt('请输入您的相册名称：','');
    if(!name) return;
    $("[name='albumid']").each(function () {
        $(this).append($('<option value="'+name+'">'+name+'</option>'));
    });
    obj.find('option').last().attr('selected',true);
}

//获取视频
function get_video_url(id) {
    var url = $('#'+id).val();
    if(!url) { 
        alert('未填写视频页面地址。'); return;
    }
    var ext = url.substr(url.length-4);
    if(ext!='' && ext.toLowerCase()=='.swf') {
        alert('已经是一个视频地址了，不需要进行解析操作。'); return;
    }
    msgOpen('正在获取，请稍后...', 180,80);
    $.post(Url('modoer/ajax/op/getcontent'), {'url':url,'in_ajax':1}, function(result) {
        msgClose();
        if (result.match(/\{\s+caption:".*",message:".*".*\s*\}/)) {
            myAlert(result);
        } else if(result.match(/http:\/\/[\w.]+[\w\/]*[\w.]*\??[\w=&+%]*/ig)) {
            var operation = "<div><button id=\"use_vide_url\">使用这个视频</button></div>";
            open_video(result,420,350,operation,'top');
            $('#use_vide_url').click(function() {
                $('#'+id).val(result);
                dlgClose();
            });
            //$('#'+id).val(result);
        } else {
            alert('信息读取失败，可能网络忙碌，请稍后尝试。');
        }
    });
}
//退出主题的风格预览
function item_preview_exit(sid) {
    set_cookie('item_style_preview_'+sid, 0);
    document.location=Url('item/detail/id/'+sid);
}
function item_style_buycheck(templateid,pointtype) {
    if (!is_numeric(templateid)) {
        alert('无效的ID'); 
        return;
    }
    $.post(Url("item/member/ac/style/op/buy_check"), { templateid:templateid, in_ajax:1 }, function (data) {
        if(data == null) {
            alert('信息读取失败，可能网络忙碌，请稍后尝试。');
        } else if (is_message(data)) {
            myAlert(data);
        } else if(is_json(data)) {
            var obj = $.parseJSON(data);
            if(!obj.name||!obj.templateid) {
                alert('数据异常.');return;
            }
            var msg = "您确定要购买主题风格：《"+obj.name+"》吗？\n本次购买操作将花费您 "+obj.price+" "+obj.pointtype+"！";
            if(obj.purchased=='Y') {
                msg += "\n\n" + "您之前已经购买过，本次购买将会延长风格使用时间。";
            }
            if(confirm(msg)) {
                item_style_buy(templateid);
            }
        } else {
            alert('未知错误.');
        }
    });
}

function item_style_buy(templateid) {
    if (!is_numeric(templateid)) {
        alert('无效的ID'); 
        return;
    }
    $.post(Url("item/member/ac/style/op/buy"), { templateid:templateid, in_ajax:1 }, function (data) {
        if(data == null) {
            alert('信息读取失败，可能网络忙碌，请稍后尝试。');
        } else if (is_message(data)) {
            myAlert(data);
        } else if(data=='OK') {
            alert('恭喜您！购买成功！');
            document.location = Url("item/member/ac/style");
        } else {
            alert('未知错误.');
        }
    });
}

function item_use_style(sid,templateid) {
    if (!is_numeric(templateid)) {
        alert('无效的ID'); 
        return;
    }
    $.post(Url("item/member/ac/style/op/use"), { templateid:templateid, in_ajax:1 }, function (data) {
        if(data == null) {
            alert('信息读取失败，可能网络忙碌，请稍后尝试。');
        } else if (is_message(data)) {
            myAlert(data);
        } else if(data=='OK') {
            if(confirm('设置成功！是否马上访问？')) document.location = Url("item/detail/id/"+sid);
        } else {
            alert('未知错误.');
        }
    });
}
/****************************************************************************************/
/************************************* guestbook ****************************************/
/****************************************************************************************/

function get_guestbook(sid,page) {
    if(!is_numeric(sid)) { alert('无效的ID'); return false; }
    if(!page) page = 1;
    $.post(getUrl('item','ajax','do=guestbook&op=get&in_ajax=1&sid='+sid+'&page='+page), 
    { empty:getRandom() },
    function(result) {
        if(result == null) {
            alert('信息读取失败，可能网络忙碌，请稍后尝试。');
        } else if (result.match(/\{\s+caption:".*",message:".*".*\s*\}/)) {
            myAlert(result);
        } else {
            $('#display').html(result);
        }
    });
    $('#subtab li').each(function(i) {
        if(this.id=='tab_guestbook') {
            $(this).addClass('selected');
        } else {
            $(this).removeClass('selected');
        }
    });
    return false;
}

function post_guestbook(sid) {
    if(!is_numeric(sid)) { alert('无效的ID'); return; }
    $.post(Url('item/member/ac/m_guestbook/op/add/sid/'+sid), 
    { in_ajax:1, empty:getRandom() },
    function(result) {
        if(result == null) {
            alert('信息读取失败，可能网络忙碌，请稍后尝试。');
        } else if (result.match(/\{\s+caption:".*",message:".*".*\s*\}/)) {
            myAlert(result);
        } else {
            dlgOpen('留言', result, 350, 260);
        }
    });
}

function edit_guestbook(guestbookid) {
    if(!is_numeric(guestbookid)) { alert('无效的ID'); return; }
    $.post(Url('item/member/ac/m_guestbook/op/edit/guestbookid/'+guestbookid), 
    { in_ajax:1, empty:getRandom() },
    function(result) {
        if(result == null) {
            alert('信息读取失败，可能网络忙碌，请稍后尝试。');
        } else if (result.match(/\{\s+caption:".*",message:".*".*\s*\}/)) {
            myAlert(result);
        } else {
            dlgOpen('编辑留言', result, 350, 260);
        }
    });
}

function reply_guestbook(guestbookid) {
    if(!is_numeric(guestbookid)) { alert('无效的ID'); return; }
    $.post(Url('item/member/ac/g_guestbook/op/reply/guestbookid/'+guestbookid), 
    { in_ajax:1,empty:getRandom() },
    function(result) {
        if(result == null) {
            alert('信息读取失败，可能网络忙碌，请稍后尝试。');
        } else if (result.match(/\{\s+caption:".*",message:".*".*\s*\}/)) {
            myAlert(result);
        } else {
            dlgOpen('回复留言', result, 350, 260);
        }
    });
}

function insert_reply(guestbookid) {
    if(!is_numeric(guestbookid)) { alert('无效的ID'); return; }
    $.post(Url('item/member/ac/g_guestbook/op/insert/guestbookid/'+guestbookid), 
    { in_ajax:1,empty:getRandom() },
    function(result) {
        if(result == null) {
            alert('信息读取失败，可能网络忙碌，请稍后尝试。');
        } else if (result.match(/\{\s+caption:".*",message:".*".*\s*\}/)) {
            myAlert(result);
        } else {
            var reply = $('#guestbook_reply_'+guestbookid);
            result = '回复：' + result;
            if(reply[0]) {
                reply.html(result);
            } else {
                reply = $('<div>'+result+'</div>').attr('id','guestbook_reply_'+guestbookid).toggleClass('reply');
                $('#guestbook_content_'+guestbookid).after(reply);
            }
        }
    });
}

function delete_guestbook(guestbookid, no_cfm) {
    if(!is_numeric(guestbookid)) { alert('无效的ID'); return; }
    if(!no_cfm && !confirm('您确定要删除这条信息吗?')) return;
    $.post(Url('item/member/ac/m_guestbook/op/delete'), 
    { guestbookids:guestbookid,in_ajax:1,empty:getRandom() },
    function(result) {
        if(result == null) {
            alert('信息读取失败，可能网络忙碌，请稍后尝试。');
        } else if (result.match(/\{\s+caption:".*",message:".*".*\s*\}/)) {
            myAlert(result);
        } else if(result=='OK') {
            msgOpen('留言删除完毕！', 200, 60);
            $('#guestbook_'+guestbookid).remove();
        }
    });
}

/****************************************************************************************/
/************************************* forum ****************************************/
/****************************************************************************************/

function get_forum_threads(forumid,page,sid) {
    if(!is_numeric(forumid)) { alert('无效的ID'); return false; }
    if(!is_numeric(sid)) { alert('无效的SID'); return false; }
    if(!page) page = 1;
    $.post(getUrl('item','ajax','do=forum&op=threads&in_ajax=1&forumid='+forumid+'&page='+page), 
    { sid:sid, empty:getRandom() },
    function(result) {
        if(result == null) {
            alert('信息读取失败，可能网络忙碌，请稍后尝试。');
        } else if (result.match(/\{\s+caption:".*",message:".*".*\s*\}/)) {
            myAlert(result);
        } else {
            $('#display').html(result);
        }
    });
    $('#subtab li').each(function(i) {
        if(this.id=='tab_forum') {
            $(this).addClass('selected');
        } else {
            $(this).removeClass('selected');
        }
    });
    return false;
}
