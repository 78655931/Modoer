<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

class mxml {
    function from_array($arr, $level = 1) {
        $s = $level == 1 ? "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>\r\n<root>\r\n" : '';
        $space = str_repeat("\t", $level);
        foreach($arr as $k => $v) {
            if(!is_array($v)) {
                $htmlon = preg_match("/<[a-z]+\s+.+\\>/i", $v);
                $s .= $space."<item id=\"$k\">".($htmlon ? '<![CDATA[' : '').$v.($htmlon ? ']]>' : '')."</item>\r\n";
            } else {
                $s .= $space."<item id=\"$k\">\r\n".mxml::from_array($v, $level + 1).$space."</item>\r\n";
            }
        }
        $s = preg_replace("/([\x01-\x08\x0b-\x0c\x0e-\x1f])+/", ' ', $s);
        return $level == 1 ? $s."</root>" : $s;
    }

    function to_array($xmlfile,$is_file=true) {
        if($is_file) {
            $content = file_get_contents($xmlfile);
        } else {
            $content = $xmlfile;
        }
        $xml_parser = new mxml_parser();
        $data = $xml_parser->parse($content);
        $xml_parser->destruct();
        return $data;
    }
}

class mxml_parser {

	var $parser;
	var $document;
	var $stack;
	var $data;
	var $last_opened_tag;
	var $attrs = array();
	var $failed = FALSE;

	function __construct() {
		$this->XMLparse();
	}

	function XMLparse() {
		$this->parser = xml_parser_create('ISO-8859-1');
		xml_parser_set_option($this->parser, XML_OPTION_CASE_FOLDING, false);
		xml_set_object($this->parser, $this);
		xml_set_element_handler($this->parser, 'open','close');
		xml_set_character_data_handler($this->parser, 'data');
	}

	function destruct() {
		xml_parser_free($this->parser);
	}

	function parse(&$data) {
		$this->document = array();
		$this->stack = array();
		return xml_parse($this->parser, $data, true) && !$this->failed ? $this->document : '';
	}

	function data(&$parser, $data) {
		if($this->last_opened_tag != NULL) {
			$this->data .= $data;
		}
	}

	function open(&$parser, $tag, $attributes) {
		$this->data = '';
		$this->failed = FALSE;
		if(!$this->isnormal) {
			if(isset($attributes['id']) && !is_string($this->document[$attributes['id']])) {
				$this->document = &$this->document[$attributes['id']];
			} else {
				$this->failed = TRUE;
			}
		} else {
			if(!isset($this->document[$tag]) || !is_string($this->document[$tag])) {
				$this->document  = &$this->document[$tag];
			} else {
				$this->failed = TRUE;
			}
		}
		$this->stack[] = &$this->document;
		$this->last_opened_tag = $tag;
		$this->attrs = $attributes;
	}

	function close(&$parser, $tag) {
		if($this->last_opened_tag == $tag) {
			$this->document = $this->data;
			$this->last_opened_tag = NULL;
		}
		array_pop($this->stack);
		if($this->stack) {
			$this->document = &$this->stack[count($this->stack)-1];
		}
	}

}

?>