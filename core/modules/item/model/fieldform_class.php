<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

$_G['loader']->model('fieldform', FALSE);
class msm_item_fieldform extends msm_fieldform {

    function __construct() {
        parent::__construct();
        $this->model_flag = 'item';
    }

    function msm_item_fieldform() {
        $this->__construct();
    }

	function _text($val) {
		if($this->field['fieldname']!='name') {
			return parent::_text($val);
		}
        $val = $this->is_edit ? $val : $this->config['default'];
        if($this->config['html']) {
            $val = _T($val);
        }
        $notnull = $this->field['allownull'] ? '' : '<span class="font_1">*</span>';
        $validator = '';
        if($notnull) {
            $validator =" validator=\"{'empty':'N','errmsg':'".lang('item_fieldvalidator_empty_field', $this->field['title'])."'}\"";
        }
		$check_exists_btn = " <button type=\"button\" onclick=\"item_subject_check_exists('{$this->ctrid}');\">".lang('item_check_subject_title')."</button>";
        $content = "<tr>\r\n";
        $content .= "\t<td $this->style>".$notnull.$this->field['title']."：</td>\r\n";
        $content .= "\t<td><input type=\"text\" class=\"t_input\" name=\"$this->ctrname\" id=\"$this->ctrid\" size=\"{$this->config['size']}\" value=\"$val\"$validator />{$check_exists_btn}<div>".$this->field['note']."</div></td>\r\n";
        //$content .= "\t<td class=\"note\">".$this->field['note']."</td>";
        $content .= "</tr>\r\n";
        return $content;
	}

    function _category($val) {
        $this->loader->helper('form', 'item');
        $notnull = $this->field['allownull'] ? '' : '<span class="font_1">*</span>';
        $validator =" validator=\"{'empty':'N','errmsg':'".lang('item_fieldvalidator_empty_field', $this->field['title'])."'}\"";
        //js缓存更新标识
        $modcfg = $this->loader->variable('config','item');
        $jscache_flag = $modcfg['jscache_flag'];
        $content = "<script type=\"text/javascript\" src=\"".URLROOT."/data/cachefiles/item_category.js?r=$jscache_flag\"></script>";
        $content .= "<tr>\r\n";
        $content .= "\t<td $this->style>".$notnull.$this->field['title']."：</td>\r\n";
        $content .= "\t<td>\r\n";
        //设置默认的catid
        if(!$val) {
            if($cats = $this->loader->variable('category_' . ITEM_PID, 'item', FALSE)) {
                foreach($cats as $v) {
                    if($v['level']=='2') {
                        $val = $v['catid'];
                        break;
                    }
                }
            }
        }
        $content .= "\t<select id=\"category_2\" name=\"$this->ctrname\" onchange=\"item_category_level3(this,'category_3','{$this->all_data[sid]}');\">".form_item_category_sub(ITEM_PID, $val)."</select>\r\n";
        $sub_catids = $this->all_data['sub_catids'];
        if($sub_catids) $sub_catids = explode('|', $sub_catids);
        $content .= "\t<select id=\"category_3\" name=\"t_item[sub_catids][]\" multiple=\"true\">".form_item_category_sub($val, $sub_catids)."</select>\r\n";
        $content .= "\t<div>".$this->field['note']."</div>\r\n";
        $content .= "<script type=\"text/javascript\">$('#category_3').mchecklist();</script>";
        $content .= "</td>\r\n</tr>\r\n";
        return $content;
    }

    function _status($val) {
        return '';
        /*
        $notnull = $this->field['allownull'] ? '' : '<span class="font_1">*</span>';
        $content = "<tr>\r\n";
        $content .= "\t<td valign=\"top\" $this->style>".$notnull.$this->field['title']."：</td>\r\n";
        $content .= "\t<td><select name=\"$this->ctrname\" id=\"$this->ctrid\">\r\n";
        $content .= "\t<option value=\"0\"".(!$val?' selected':'').">".lang('item_fieldform_status_0')."</option>\r\n";
        $content .= "\t<option value=\"1\"".($val=='1'?' selected':'').">".lang('item_fieldform_status_1')."</option>\r\n";
        $content .= "\t</select><div>".$this->field['note']."</div></td>\r\n";
        //$content .= "\t<td class=\"note\">".$this->field['note']."</td>";
        $content .= "</tr>\r\n";
        return $content;
        */
    }

    function _level($val) {
        $notnull = $this->field['allownull'] ? '' : '<span class="font_1">*</span>';
        $content = "<tr>\r\n";
        $content .= "\t<td valign=\"top\" $this->style>".$notnull.$this->field['title']."：</td>\r\n";
        $content .= "\t<td><select name=\"$this->ctrname\" id=\"$this->ctrid\">\r\n";
        for($i=0;$i<=5;$i++) {
			$selected = $i==$val?' selected':'';
            $content .= "\t<option value=\"$i\"".$selected.">$i</option>\r\n";
        }
        $content .= "\t</select><div>".$this->field['note']."</div></td>\r\n";
        //$content .= "\t<td class=\"note\">".$this->field['note']."</td>";
        $content .= "</tr>\r\n";
        return $content;
    }

    function _template($val) {
        return '';
/*        $tpllist = $this->loader->variable('templates');
        $notnull = $this->field['allownull'] ? '' : '<span class="font_1">*</span>';
        $content = "<tr>\r\n";
        $content .= "\t<td valign=\"top\" $this->style>".$notnull.$this->field['title']."：</td>\r\n";
        $content .= "\t<td><select name=\"$this->ctrname\" id=\"$this->ctrid\">\r\n";
        $content .= "\t<option value=\"0\">".lang('item_fieldform_template_disable')."</option>\r\n";

        if(defined('EDIT_SID')) {
            $ST =& $this->loader->model('item:subjectstyle');
            $list = $ST->my(EDIT_SID,false);
            if($list) while($_val=$list->fetch_array()) {
                $selected = $_val['templateid']==$val?' selected':'';
                $content .= "\t<option value=\"$_val[templateid]\"".$selected.">$_val[name]".($_val['endtime']?('('.date('Y-m-d',$_val['endtime']).')'):'')."</option>\r\n";
            }
            $url = "&nbsp;<a href=\"javascript:item_manage_template(".EDIT_SID.");\">管理模版</a>";
            $url .= "&nbsp;<a href=\"javascript:item_template_refresh('$this->ctrid',".EDIT_SID.");\">刷新列表</a>";
        } else {
            if($tpllist['item']) foreach($tpllist['item'] as $_val) {
                $selected = $_val['templateid']==$val?' selected':'';
                $content .= "\t<option value=\"$_val[templateid]\"".$selected.">$_val[name]</option>\r\n";
            }
        }

        $content .= "\t</select>$url</div></td>\r\n";
        //$content .= "\t<td class=\"note\">".$this->field['note']."</td>";
        $content .= "</tr>\r\n";
        return $content;*/
    }

    function _tag($val) {
        $val = $val ? unserialize($val) : '';
        $groupid = $this->config['groupid'];
		$val = $val ? $val : $this->config['default'];
        $value = is_string($val) ? implode(',', $val) : (is_array($val) ? $val : '');
        $taggroups = $this->loader->variable('taggroup','item');
        $notnull = $this->field['allownull'] ? '' : '<span class="font_1">*</span>';
        $content = "<tr>\r\n";
        $content .= "\t<td $this->style>".$notnull.$this->field['title']."：</td>\r\n";
        if($taggroups[$groupid]['sort'] == 1) {
            $value = is_array($value) ? implode(',', $value) : $value;
            $content .= "\t<td><input type=\"text\" class=\"t_input\" name=\"$this->ctrname\" id=\"$this->ctrid\" value=\"$value\" size=\"50\" />"."<div class=\"usagetags\" id=\"t_item_taggroup_usetag_{$groupid}\"></div>"."<div>".$this->field['note']."</div></td>\r\n";
        } elseif($taggroups[$groupid]['sort']==2) {
            $tagconfig = explode(',', $taggroups[$groupid]['options']);
            $checkboxs = '';
            foreach($tagconfig as $ky => $tgval) {
                $checked = $value && in_array($tgval, $value) ? ' checked="checked"' : '';
                $checkboxs .= "<input type=\"checkbox\" name=\"{$this->ctrname}[]\"id=\"{$this->ctrid}_{$ky}\" value=\"$tgval\" $checked /><label for=\"{$this->ctrid}_{$ky}\">$tgval</label>&nbsp;";
            }
            $content .= "\t<td>$checkboxs"."<div>".$this->field['note']."</div></td>\r\n";
        }
        //$content .= "\t<td class=\"note\">".$this->field['note']."<span class='font_3'>多个标签，请用\",\"逗号分割</span></td>";
        $content .= "</tr>\r\n";
        return $content;
    }

    function _att($val) {
        $val = $val ? explode(',', $val) : '';
        $catid = $this->config['catid'];
        $len = $this->config['len'] > 0 ? $this->config['len'] : 1;
        $att_list = $this->loader->variable('att_list_'.$catid,'item');
        $notnull = $this->field['allownull'] ? '' : '<span class="font_1">*</span>';
        $content = "<tr>\r\n";
        $content .= "\t<td $this->style>".$notnull.$this->field['title']."：</td>\r\n\t<td>";

        $opt_count_min = 5;
        $opt_count = count($att_list);
        $option = '';
        if($opt_count > $opt_count_min) {
            $use_select = true;
            $option = "<select name=\"$this->ctrname".($len>1?"[]":'')."\" id=\"$this->ctrid\"".($len>1?"multiple=\"true\"":'').">";
        } else {
            $box_type = $len==1 ? 'radio' : 'checkbox';
        }
        if($att_list) foreach($att_list as $attid => $sv) {
            if($opt_count <= $opt_count_min) {
                $checked = is_array($val) && in_array($attid, $val) ? " checked=\"checked\"" : "";
                $option .= "<input type=\"$box_type\" name=\"{$this->ctrname}[]\" value=\"$attid\" id=\"{$this->ctrid}_$attid\"$checked /><label for=\"{$this->ctrid}_$attid\">$sv[name]</label>&nbsp;&nbsp;";
            } else {
                $selected = is_array($val) && in_array($attid, $val) ? " selected=\"selected\"" : "";
                $option .= "\r\n\t<option value=\"$attid\" id=\"{$this->field['fieldname']}_$attid\"$selected />$sv[name]</option>";
            }
        }
        $use_select && $option .= "\r\n\t</select>";
        $len>1 && $option .= "<script type=\"text/javascript\">$('#$this->ctrid').mchecklist();</script>";
        //$content .= "\t<td class=\"note\">".$this->field['note']."<span class='font_3'>多个标签，请用\",\"逗号分割</span></td>";
        $content .= $option . "</td>\r\n\t</tr>\r\n";
        return $content;
    }

    function _member($val) {
        return $this->_text($val);
    }

    function _video($val) {
        $this->config['size'] = '50';
        $result = $this->_text($val);
        $btn = "&nbsp;<button type=\"button\" onclick=\"get_video_url('$this->ctrid');\">".lang('item_fieldform_video_parse')."</button>";
        return str_replace('<!--repace-->', $btn, $result);
    }

    function _subject($val) {
        $S =& $this->loader->model('item:subject');
        if(defined('EDIT_SID')) {
            $sids = '';
            $this->db->from('dbpre_subjectrelated');
            $this->db->where('fieldid',$this->field['fieldid']);
            $this->db->where('sid',EDIT_SID);
            if($q = $this->db->get()) {
                while($v = $q->fetch_array()) {
                    $sids .= $split . $v['r_sid'];
                    $split = ',';
                }
                $q->free_result();
            }
        }
        $options = '';
        if($this->field['config']['categorys']) {
            $cats = explode(',', $this->field['config']['categorys']);
            if($cats) {
                $categorys = $this->variable('category');
                foreach($cats as $catid) {
                    $options .= "\t<option value=\"$catid\">{$categorys[$catid][name]}</option>\n";
                }
            }
        }
        $content = "<tr>\r\n";
        $content .= "\t<td $this->style valign=\"top\">".$notnull.$this->field['title']."：</td>\n";
        $content .= "\t<td><div id=\"subject_search_{$this->ctrid}\"></div>\n";
		$content .= "\t<script type=\"text/javascript\">\n";
		$content .= "\t$('#subject_search_{$this->ctrid}').item_subject_search({\n";
		$content .= "\tsid_name:'{$this->ctrname}',\n";
		$content .= "\tinput_class:'".($this->in_admin?'txtbox2':'t_input')."',\n";
		$content .= "\tbtn_class:'btn2',\n";
		$content .= "\tresult_css:'item_search_result',\n";
		if($sids) $content .= "\tsid:'$sids',\n";
		$content .= "\thide_keyword:true,\n";
        $content .= "\tmulti:true\n";
		$content .= "\t})\n";
		$content .= "\t</script>\n";
        return $content;
    }

	function _taoke_product($val) {
		if($this->config['nick_field']&&empty($this->config['q_type'])) return'';
		if($this->config['q_type']=='q_field') return'';
		return $this->_text($val);
	}
}
?>