<?php
/**
* �Զ���ҳ
* @author moufer<moufer@163.com>
* @copyright modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

class ms_cutpage {
    var $pagestr;       //���зֵ�����    
    var $pagearr;       //���з����ֵ������ʽ    
    var $sum_word;      //������(UTF-8��ʽ�������ַ�Ҳ����)    
    var $sum_page;      //��ҳ��    
    var $page_word;     //һҳ������    
    var $cut_tag;       //�Զ���ҳ��    
    var $cut_custom;    //�ֶ���ҳ��    
    var $ipage;         //��ǰ�зֵ�ҳ�����ڼ�ҳ    
    var $url;
    
    function __construct() {
        $this->page_word = 1000;    
        $this->cut_tag = array("</table>", "</div>", "</p>", "<br/>", "����", "��", ".", "��", "����", "��", ",");    
        $this->cut_custom = "[page]number[/page]";
        $tmp_page = intval(trim($_GET["page"]));
        $this->ipage = $tmp_page > 1 ? $tmp_page : 1;    
    }

    //ͳ��������    
    function get_page_word(){
        $this->sum_word = $this->strlen_utf8($this->pagestr);    
        return $this->sum_word;    
    }

    //ͳ��UTF-8������ַ�����(һ�����ģ�һ��Ӣ�Ķ�Ϊһ����)
    function strlen_utf8($str){
       $i = 0;    
       $count = 0;    
       $len = strlen ($str);    
       while ($i < $len){
           $chr = ord ($str[$i]);    
           $count++;    
           $i++;    
           if ($i >= $len)    
               break;    
           if ($chr & 0x80){
               $chr <<= 1;    
               while ($chr & 0x80) {
                   $i++;    
                   $chr <<= 1;    
               }
           }
       }
       return $count;    
    }

    //�����Զ���ҳ����    
    function set_cut_tag($tag_arr=array()){
        $this->cut_tag = $tag_arr;    
    }

    //�����ֶ���ҳ��    
    function set_cut_custom($cut_str){
        $this->cut_custom = $cut_str;    
    }

    function show_cpage($ipage=0){
        $this->cut_str();    
        $ipage = $ipage ? $ipage:$this->ipage;    
        return $this->pagearr[$ipage];    
    }

    function manual_cut() {
        $page_arr = explode($this->cut_custom, $this->pagestr);    
        $this->sum_page = count($page_arr);
        $this->pagearr = $page_arr;  
    }

    function cut_str(){
        $str_len_word = strlen($this->pagestr); //��ȡʹ��strlen�õ����ַ�����    
        $i = 0;    
        if ($str_len_word<=$this->page_word){ //���������С��һҳ��ʾ����    
            $page_arr[$i] = $this->pagestr;    
        } else {
            if (strpos($this->pagestr, $this->cut_custom)) {
                $page_arr = explode($this->cut_custom, $this->pagestr);    
            } else {
                $str_first = substr($this->pagestr, 0, $this->page_word); //0-page_word������ 
                foreach ($this->cut_tag as $v){
                    $cut_start = strrpos($str_first, $v); //������ҵ�һ����ҳ����λ��    
                    if ($cut_start){
                        $page_arr[$i++] = substr($this->pagestr, 0, $cut_start).$v;    
                        $cut_start = $cut_start + strlen($v);    
                        break;    
                    }
                }
                if (($cut_start+$this->page_word) >= $str_len_word) {  //�������������    
                    $page_arr[$i++] = substr($this->pagestr, $cut_start, $this->page_word);    
                } else {
                    while (($cut_start+$this->page_word)<$str_len_word) {
                        foreach ($this->cut_tag as $v){
                            $str_tmp = csubstr($this->pagestr, $cut_start, $this->page_word); //ȡ��cut_start���ֺ��page_word���ַ�    
                            $cut_tmp = strrpos($str_tmp, $v); //�ҳ��ӵ�cut_start����֮��page_word����֮�䣬������ҵ�һ����ҳ����λ��    
                            if ($cut_tmp) {
                                $page_arr[$i++] = substr($str_tmp, 0, $cut_tmp).$v;    
                                $cut_start = $cut_start + $cut_tmp + strlen($v);    
                                break;    
                            }
                        }  
                    }
                    if (($cut_start+$this->page_word)>$str_len_word) {
                        $page_arr[$i++] = substr($this->pagestr, $cut_start, $this->page_word);    
                    }
                }
            }
        }
        $this->sum_page = count($page_arr);//total
        $this->pagearr = $page_arr;    
    }
}
?>
