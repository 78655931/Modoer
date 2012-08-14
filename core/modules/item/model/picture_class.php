<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

$_G['loader']->model('item:itembase', FALSE);
class msm_item_picture extends msm_item_itembase {

    var $table = 'dbpre_pictures';
	var $key = 'picid';

	function __construct() {
		parent::__construct();
		$this->model_flag = 'item';
		$this->init_field();
	}

    function msm_item_picture() {
        $this->__construct();
    }

	function init_field() {
		$this->add_field('albumid,sid,title,comments,url');
		$this->add_field_fun('albumid,sid', 'intval');
		$this->add_field_fun('title,comments,url', '_T');
	}

	function find($select, $where, $orderby, $start=0, $offset=0, $total = TRUE, $join_subject = FALSE) {
		if($where['pid']) $join_subject = TRUE;
		if($join_subject) {
			$this->db->join($this->table, 'p.sid', $this->subject_table, 's.sid');
		} else {
			$this->db->from($this->table,'p');
		}
		$this->db->where($where);

        $result = array(0,'');
        if($total) {
            if(!$result[0] = $this->db->count()) {
                return $result;
            }
            $this->db->sql_roll_back('from,where');
        }

		$this->db->select($select ? $select : '*');
        $this->db->order_by($orderby);
        if($offset>0) $this->db->limit($start, $offset);
        $result[1] = $this->db->get();
        return $result;
	}

	function save($post, $multi = FALSE, $setthumb = FALSE) {

        !$this->in_admin && $this->global['user']->check_access('item_pictures', $this);
		$subject = $this->check_post_before($post['sid'], FALSE, $setthumb);
		$pid = $subject['pid'];
        if($multi && !$post['title']) {
            $post['title'] = $subject['sid'] . '_'.rand(10000,99999);
        } elseif($this->in_admin && $this->in_ajax) {
            $post['title'] = $subject['sid'] . '_'.rand(10000,99999);
        }
		if(strtolower($post['url']) == 'http://') $post['url'] = '';
		$this->check_post($post);
        if($this->in_admin) {
            $post['uid'] = 0;
            $post['username'] = $this->global['admin']->adminname;
            $post['status'] = 1;
        } else {
            $post['uid'] = $this->global['user']->uid;
            $post['username'] = $this->global['user']->username;
            $category = $this->variable('category');
            $post['status'] = $category[$pid]['config']['picturecheck'] ? 0 : 1;
        }
        $post['addtime'] = $this->global['timestamp'];
        $post['city_id'] = $subject['city_id'];
		define('RETURN_EVENT_ID', $post['status'] ? 'global_op_succeed' : 'global_op_succeed_check');
		
		if(isset($post['download_src'])) {
			$downsrc = $post['download_src'];
			unset($post['download_src']);
			$imginfo = $this->download($downsrc,false);
		} else {
			$imginfo = $this->upload();
		}
		$post = array_merge($post, $imginfo);

        $A =& $this->loader->model('item:album');
        if($post['albumid'] && !is_numeric($post['albumid'])) {
            $album_name = $post['albumid'];
            $post['albumid'] = $A->create_normal($post['sid'],$post['albumid'],$post['thumb']);
        }

		//未相册选择
		if(!$post['albumid']) {
			//存在相册
			if($alubm = $A->getlist($post['sid'],1)) {
				$post['albumid'] = $alubm['albumid'];
			} else {
				//不存在则新建
				$post['albumid'] = $A->create_normal($post['sid'],lang('item_album_name',$subject['name']),$post['thumb']);
			}
		}

		$post   = $this->convert_post($post);
		$picid  = parent::save($post);

		if($post['status']) {
            $this->loader->model('item:album')->album_total($post['albumid'], 1, $post['thumb']);
			$this->subject_total_add($post['sid'], $post['thumb'], 1, $setthumb);
			$post['uid'] && $this->add_user_point($post['uid']);
            $post['uid'] && $this->_feed($picid); //添加feed事件
		}

        if(_post('do') == 'review_upload') {
            return $this->return_review_pic($picid, $post['thumb'], $post['filename']);
        }

		$A =& $this->loader->model('item:album');
		$A->lastupdate($post['albumid']);

        return $picid;
	}

	//删除图片
    function delete($picids, $delete_point = FALSE) {
		$ids = parent::get_keyids($picids);
		$where = array('picid' => $ids);
		if(!$delete_point && !$this->in_admin) $delete_point = TRUE;
		$this->_delete($where,$delete_point,true);
	}
	//删除主题所有图片
	function delete_subject($ids,$delete_point=false) {
		$ids = parent::get_keyids($ids);
		$where = array('sid'=>$ids);
		$this->_delete($where,$delete_point,false);
	}
	//删除相册图片
	function delete_album($ids,$update_total=true,$delete_point=false) {
		$ids = parent::get_keyids($ids);
		$where = array('albumid'=>$ids);
		$this->_delete($where,$delete_point,true);
	}

	//获取数量
	function count_album($albumid) {
		$this->db->from($this->table);
		$this->db->where('albumid', $albumid);
		$this->db->where('status',1);
		return $this->db->count();
	}

	function update($post) {
		if(empty($post)) redirect('global_op_unselect');
		$albumids = array();
		foreach($post as $key => $val) {
			if(!$key || !is_numeric($key) || $key < 1) continue; 
			$post = $this->get_post($val);
			$this->check_post($post, TRUE);
			$post = $this->convert_post($post);
			$this->db->from($this->table);
			$this->db->set($post);
			$this->db->where('picid', $key);
			$this->db->update();
			//变更了相册
			if($val['albumid']>0 && !in_array($val['albumid'], $albumids)) {
				$albumids[] = $val['albumid'];
			}
		}
		if($albumids) {
			$A =& $this->loader->model('item:album');
			$A->rebuild($albumids);
		}
	}

	function upload() {
		$this->loader->lib('upload_image', NULL, FALSE);
		$img = new ms_upload_image('picture', $this->global['cfg']['picture_ext']);
		
		$config = $this->variable('config');
		$thumb_w = $config['pic_width'] ? $config['pic_width'] : 200;
		$thumb_h = $config['pic_height'] ? $config['pic_height'] : 150;

        $img->set_max_size($this->global['cfg']['picture_upload_size']);
        $img->userWatermark = $this->global['cfg']['watermark'];
        $img->watermark_postion = $this->global['cfg']['watermark_postion'];
        $img->thumb_mod = $this->global['cfg']['picture_createthumb_mod'];
        $img->set_watermark_text(lang('item_picture_wtext',array($this->global['cfg']['watermark_text'], $this->global['user']->username)));

        $img->set_thumb_level($this->global['cfg']['picture_createthumb_level']);
		//$img->limit_ext = array('jpg','png','gif');//picture_ext
        $img->set_ext($this->global['cfg']['picture_ext']);
        $img->userWatermark = (int)$this->global['cfg']['watermark'];

		$img->add_thumb('thumb', 'thumb_', $thumb_w, $thumb_h, 0);
		$dir_mod = $this->global['cfg']['picture_dir_mod'];
		$img->upload('pictures', $dir_mod);

		$post = array();
		$post['filename']   = str_replace(DS, '/', $img->path . '/' . $img->filename);
		$post['thumb']      = str_replace(DS, '/', $img->path . '/' . $img->thumb_filenames['thumb']['filename']);
		$post['width']      = $img->width;
		$post['height']     = $img->height;
		$post['size']       = $img->size;
		
		return $post;
	}

	function download($src, $show_error=true) {
		$this->loader->lib('download_file', NULL, FALSE);
		$DL = new ms_download_file($src, $this->global['cfg']['picture_ext']);
		$dir_mod = $this->global['cfg']['picture_dir_mod'];
		$DL->download('pictures',$dir_mod);

		$filename = MUDDER_ROOT . $DL->path . '/' . $DL->filename;
        if(function_exists('getimagesize') && !@getimagesize($filename)) {
            $DL->delete_file();
            redirect('global_upload_image_unkown');
        }
		
		$post = array();
        list($post['width'],$post['height'],,) = @getimagesize($filename);
		$post['filename'] = $post['thumb'] = str_replace(DS, '/', $DL->path . '/' . $DL->filename);
		$post['size'] = @filesize($filename);

		return $post;
	}
	
	function checkup($picids) {
		if(empty($picids)) redirect('global_op_unselect');
        if(!is_array($picids)) $picids = array((int)$picids);
		$this->db->select('picid,albumid,sid,status,uid,thumb');
        $this->db->from($this->table);
        $this->db->where_in('picid', $picids);
        $this->db->where('status', 0);
        if(!$row = $this->db->get()) return;
        $uids = $upids = $albums = array();
        $thumb = '';
        while ($value = $row->fetch_array()) {
			$upids[] = $value['picid'];
			//更新主题记录
            $this->subject_total_add($value['sid'], $value['thumb']);
            //累计相册添加数量
            if(isset($albums[$value['albumid']])) {
                $albums[$value['albumid']]['total']++;
            } else {
                $albums[$value['albumid']]['total'] = 1;
                $albums[$value['albumid']]['thumb'] = $value['thumb'];
            }
			//记录需要增加积分的用户和数量
			if($value['uid']) {
				if(isset($uids[$value['uid']])) {
					$uids[$value['uid']]++;
				} else {
					$uids[$value['uid']] = 1;
				}
                $this->_feed($value['picid']); //feed事件
			}
        }
        $row->free_result();
		//更新记录
        if($upids) {
            $this->db->from($this->table);
            $this->db->set('status', 1);
            $this->db->where_in('picid', $upids);
            $this->db->update();
        }
        //更新相册数据
        if($albums) {
            $A =& $this->loader->model('item:album');
            foreach ($albums as $albumid => $value) {
                $A->album_total($albumid, $value['total'], $value['thumb']);
            }
        }
		//增加用户积分
		if($uids) {
			$P =& $this->loader->model('member:point');
			foreach($uids as $uid => $num) {
				$P->update_point($uid, 'add_picture', 0, $num);
			}
		}
	}

	function check_post_before($sid, $isedit = FALSE, $setthumb = FALSE) {
		if(!$sid || !is_numeric($sid)) {
			redirect(lang('global_sql_keyid_invalid', 'sid'));
		}
		$this->db->from($this->subject_table);
		$this->db->select('sid,city_id,pid,catid,name,subname,status');
		$this->db->where('sid', $sid);
		if(!$detail = $this->db->get_one()) redirect('item_empty');
		if(!$setthumb && $detail['status'] != '1') redirect('item_picture_status_invalid');

		return $detail;
	}

	function check_post(& $post, $isedit = FALSE) {
		//sid,modelid,title,comments
		if(!$post['title']) redirect('item_picture_empty_title');
		if(strlen($post['title']) > 30) redirect(lang('item_picture_title_charlen', 30));
	}

    //返回点评上传图片需要的格式
    function return_review_pic($picid, $thumb, $picture) {
        return "{ picid:\"$picid\",thumb:\"$thumb\",picture:\"$picture\"}";
    }

    // 增加主题的图片数量统计，并尝试设置主题封面
	function subject_total_add($sid, $thumb='', $num=1, $isthumb=FALSE) {
        // 新上传的图片是否这是为封面
        $set_thumb = FALSE;
        if($thumb) {
            if($isthumb) {
                $set_thumb = TRUE;
            } else {
                $modcfg = $this->variable('config');
                if($modcfg['thumb'] == '1') $set_thumb = TRUE;
                if($modcfg['thumb'] == '2') {
                    $this->db->from($this->subject_table);
                    $this->db->select('thumb,pictures');
                    $this->db->where('sid', $sid);
                    if(!$subject = $this->db->get_one()) return;
                    // 没有设置或者图片不存在
                    if(!$subject['thumb'] || !is_file(MUDDER_ROOT . $subject['thumb'])) {
                        $set_thumb = TRUE;
                    }
                }
            }
        }
        //更新主题
		$this->db->from($this->subject_table);
		$this->db->where('sid', $sid);
        $this->db->set_add('pictures');
        $set_thumb && $this->db->set('thumb', $thumb);
		$this->db->update();
	}

	function subject_total_dec($sid, $num=1) {
        $this->db->from($this->subject_table);
        $this->db->set_dec('pictures');
        $this->db->where('sid', $sid);
        $this->db->update();
	}

	function add_user_point($uid, $num = 1) {
        if(!$uid) return;
        $P =& $this->loader->model('member:point');
        $BOOL = $P->update_point($uid, 'add_picture', FALSE, $num, FALSE, FALSE);
        if(!$BOOL) return;
        $this->db->set_add('pictures', $num);
        $this->db->update();
	}

	function dec_user_point($uid, $num = 1) {
        if(!$uid) return;
        $P =& $this->loader->model('member:point');
        $BOOL = $P->update_point($uid, 'add_picture', TRUE, $num, FALSE, FALSE);
        if(!$BOOL) return;
        $this->db->set_dec('pictures', $num);
        $this->db->update();
	}

    //检测图片上传权限
    function check_access($key,$value,$jump) {
        if($this->in_admin) return TRUE;
        if($key=='item_pictures') {
            $value = (int) $value;
            if($value && $value < 0) {
                if(!$jump) return FALSE;
                redirect('item_access_alow_picture');
            }
            if($value && $value < $this->get_user_pictures()) {
                if(!$jump) return FALSE;
                redirect('item_access_pictures');
            }
        }
        return TRUE;
    }

    function get_user_pictures() {
        $this->db->where('uid', $this->global['user']->uid);
        $this->db->from($this->table);
        return $this->db->count();
    }

	//删除图片
	function _delete($where,$delete_point,$update_total) {

		$this->db->from($this->table);
		$this->db->where($where);
		if(!$q = $this->db->get()) return;

		//主题管理员
        if(!$this->in_admin) {
            $S =& $this->loader->model('item:subject');
            $mysubjects = $S->mysubject($this->global['user']->uid);
        }

        $A =& $this->loader->model('item:album');
		while($value=$q->fetch_array()) {
            //判断权限(后台管理员,留言会员以及主题管理员)
			$access = $this->in_admin || in_array($value['sid'], $mysubjects) || $this->global['user']->uid == $value['uid'];
            if(!$access) redirect('global_op_access');
			$deleteids[] = $value['picid'];
			if($value['status']) {
				//更新主题图片统计
				if($update_total) {
					$A->album_total($value['albumid'], 1);
					$this->subject_total_dec($value['sid']);
				}
				//记录需要删除积分的用户和数量
				if($value['uid'] && $delete_point) {
					if(isset($uids[$value['uid']])) {
						$uids[$value['uid']]++;
					} else {
						$uids[$value['uid']] = 1;
					}
				}
			}
			//删除图片，防止误删目录，做了字符数量判断
			if(strlen($value['filename']) > 12) {
				@unlink(MUDDER_ROOT . $value['filename']);
			}
			if(strlen($value['thumb']) > 12) {
				@unlink(MUDDER_ROOT . $value['thumb']);
			}
		}

		//删除记录
        if($deleteids) {
            $this->db->from($this->table);
            $this->db->where_in('picid', $deleteids);
            $this->db->delete();
        }

		//删除用户的对应积分
		if($delete_point && $uids) {
			$P =& $this->loader->model('member:point');
			foreach($uids as $uid => $num) {
				$P->update_point($uid, 'add_picture', TRUE, $num);
			}
		}

	}

    //事件
    function _feed($picid) {
        //my_just_feeds
        if(!$picid) return;
        $flag = 'item:picture';

        $FEED =& $this->loader->model('member:feed');
        if(!$FEED->enabled()) return;
        $this->global['fullalways'] = TRUE;

        if(!$detail = $this->read($picid)) return;
        if(!$detail['uid']) return;

        //获取5分钟内当前用户是否进行了系统的行为
        $num = 1;
        $maxnum = 5;
        $images = array();
        $images[] = array(
           'url' => url('modoer', 0, 1) . $detail['thumb'],
           'link' => url('item/album/picid/'.$picid),
        );
        $interval = 5 * 60;
        $jestfeed = $FEED->my_just_feed($detail['uid'], $flag);
        $del = false;
        if($interval && $jestfeed['dateline'] + $interval > $this->global['timestamp']) {
            $del = true;
            if($tmp = @unserialize($jestfeed['images'])) {
                $num = min(count($tmp) + 1, $maxnum);
                foreach($tmp as $v) if($maxnum-- > 1) $images[] = $v;
            }
        }

        $feed = array();
        $feed['icon'] = lang('item_picture_feed_icon');
        $feed['title_template'] = lang('item_picture_feed_title_template');
        $feed['title_data'] = array (
            'username' => '<a href="'.url("space/index/uid/$detail[uid]").'">' . $detail['username'] . '</a>',
            'num' => $num,
        );
        $feed['body_template'] = lang('item_picture_feed_body_template');
        $feed['body_general'] = '';
        $feed['images'] = $images;

        $FEED->save($flag, $detail['city_id'], $feed['icon'], $detail['uid'], $detail['username'], $feed);
        $del && $FEED->delete($jestfeed['id']);
    }

}
?>