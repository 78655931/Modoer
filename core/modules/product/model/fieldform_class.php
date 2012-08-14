<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

$_G['loader']->model('fieldform', FALSE);
class msm_product_fieldform extends msm_fieldform {

    function __construct() {
        parent::__construct();
        $this->model_flag = 'product';
    }

    function msm_product_fieldform() {
        $this->__construct();
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

}

?>