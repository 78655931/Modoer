<?php
/**
* 自动分页
* @author moufer<moufer@163.com>
* @copyright modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

class ms_cutpage {
    var $pagestr;       //被切分的内容    
    var $pagearr;       //被切分文字的数组格式    
    var $sum_word;      //总字数(UTF-8格式的中文字符也包括)    
    var $sum_page;      //总页数    
    var $page_word;     //一页多少字    
    var $cut_tag;       //自动分页符    
    var $cut_custom;    //手动分页符    
    var $ipage;         //当前切分的页数，第几页    
    var $url;
    
    function __construct() {
        $this->page_word = 1000;    
        $this->cut_tag = array("</table>", "</div>", "</p>", "<br/>", "”。", "。", ".", "！", "……", "？", ",");    
        $this->cut_custom = "[page]number[/page]";
        $tmp_page = intval(trim($_GET["page"]));
        $this->ipage = $tmp_page > 1 ? $tmp_page : 1;    
    }

    //统计总字数    
    function get_page_word(){
        $this->sum_word = $this->strlen_utf8($this->pagestr);    
        return $this->sum_word;    
    }

    //统计UTF-8编码的字符长度(一个中文，一个英文都为一个字)
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

    //设置自动分页符号    
    function set_cut_tag($tag_arr=array()){
        $this->cut_tag = $tag_arr;    
    }

    //设置手动分页符    
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
        $str_len_word = strlen($this->pagestr); //获取使用strlen得到的字符总数    
        $i = 0;    
        if ($str_len_word<=$this->page_word){ //如果总字数小于一页显示字数    
            $page_arr[$i] = $this->pagestr;    
        } else {
            if (strpos($this->pagestr, $this->cut_custom)) {
                $page_arr = explode($this->cut_custom, $this->pagestr);    
            } else {
                $str_first = substr($this->pagestr, 0, $this->page_word); //0-page_word个文字 
                foreach ($this->cut_tag as $v){
                    $cut_start = strrpos($str_first, $v); //逆向查找第一个分页符的位置    
                    if ($cut_start){
                        $page_arr[$i++] = substr($this->pagestr, 0, $cut_start).$v;    
                        $cut_start = $cut_start + strlen($v);    
                        break;    
                    }
                }
                if (($cut_start+$this->page_word) >= $str_len_word) {  //如果超过总字数    
                    $page_arr[$i++] = substr($this->pagestr, $cut_start, $this->page_word);    
                } else {
                    while (($cut_start+$this->page_word)<$str_len_word) {
                        foreach ($this->cut_tag as $v){
                            $str_tmp = csubstr($this->pagestr, $cut_start, $this->page_word); //取第cut_start个字后的page_word个字符    
                            $cut_tmp = strrpos($str_tmp, $v); //找出从第cut_start个字之后，page_word个字之间，逆向查找第一个分页符的位置    
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
