<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('gbk');
E_D("DROP TABLE IF EXISTS `modoer_config`;");
E_C("CREATE TABLE `modoer_config` (
  `variable` varchar(32) NOT NULL DEFAULT '',
  `value` text NOT NULL,
  `module` varchar(20) NOT NULL DEFAULT '',
  PRIMARY KEY (`variable`,`module`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk");
E_D("replace into `modoer_config` values('point','a:12:{s:11:\"add_subject\";a:7:{s:5:\"point\";i:15;s:6:\"point1\";i:15;s:6:\"point2\";i:0;s:6:\"point3\";i:0;s:6:\"point4\";i:0;s:6:\"point5\";i:0;s:6:\"point6\";i:0;}s:10:\"add_review\";a:7:{s:5:\"point\";i:10;s:6:\"point1\";i:10;s:6:\"point2\";i:0;s:6:\"point3\";i:0;s:6:\"point4\";i:0;s:6:\"point5\";i:0;s:6:\"point6\";i:0;}s:11:\"add_picture\";a:7:{s:5:\"point\";i:5;s:6:\"point1\";i:5;s:6:\"point2\";i:0;s:6:\"point3\";i:0;s:6:\"point4\";i:0;s:6:\"point5\";i:0;s:6:\"point6\";i:0;}s:13:\"add_guestbook\";a:7:{s:5:\"point\";i:5;s:6:\"point1\";i:5;s:6:\"point2\";i:0;s:6:\"point3\";i:0;s:6:\"point4\";i:0;s:6:\"point5\";i:0;s:6:\"point6\";i:0;}s:11:\"add_respond\";a:7:{s:5:\"point\";i:5;s:6:\"point1\";i:5;s:6:\"point2\";i:0;s:6:\"point3\";i:0;s:6:\"point4\";i:0;s:6:\"point5\";i:0;s:6:\"point6\";i:0;}s:14:\"update_subject\";a:7:{s:5:\"point\";i:5;s:6:\"point1\";i:5;s:6:\"point2\";i:0;s:6:\"point3\";i:0;s:6:\"point4\";i:0;s:6:\"point5\";i:0;s:6:\"point6\";i:0;}s:13:\"report_review\";a:7:{s:5:\"point\";i:5;s:6:\"point1\";i:5;s:6:\"point2\";i:0;s:6:\"point3\";i:0;s:6:\"point4\";i:0;s:6:\"point5\";i:0;s:6:\"point6\";i:0;}s:11:\"add_article\";a:7:{s:5:\"point\";i:5;s:6:\"point1\";i:5;s:6:\"point2\";i:0;s:6:\"point3\";i:0;s:6:\"point4\";i:0;s:6:\"point5\";i:0;s:6:\"point6\";i:0;}s:10:\"add_coupon\";a:7:{s:5:\"point\";i:5;s:6:\"point1\";i:5;s:6:\"point2\";i:0;s:6:\"point3\";i:0;s:6:\"point4\";i:0;s:6:\"point5\";i:0;s:6:\"point6\";i:0;}s:12:\"print_coupon\";a:7:{s:5:\"point\";i:5;s:6:\"point1\";i:5;s:6:\"point2\";i:0;s:6:\"point3\";i:0;s:6:\"point4\";i:0;s:6:\"point5\";i:0;s:6:\"point6\";i:0;}s:11:\"add_comment\";a:7:{s:5:\"point\";i:2;s:6:\"point1\";i:2;s:6:\"point2\";i:0;s:6:\"point3\";i:0;s:6:\"point4\";i:0;s:6:\"point5\";i:0;s:6:\"point6\";i:0;}s:3:\"reg\";a:7:{s:5:\"point\";i:20;s:6:\"point1\";i:20;s:6:\"point2\";i:0;s:6:\"point3\";i:0;s:6:\"point4\";i:0;s:6:\"point5\";i:0;s:6:\"point6\";i:0;}}','member');");
E_D("replace into `modoer_config` values('point_group','a:6:{s:6:\"point1\";a:5:{s:7:\"enabled\";s:1:\"1\";s:4:\"name\";s:4:\"���\";s:4:\"unit\";s:2:\"��\";s:2:\"in\";s:1:\"1\";s:4:\"rate\";s:0:\"\";}s:6:\"point2\";a:3:{s:4:\"name\";s:0:\"\";s:4:\"unit\";s:0:\"\";s:4:\"rate\";s:0:\"\";}s:6:\"point3\";a:3:{s:4:\"name\";s:0:\"\";s:4:\"unit\";s:0:\"\";s:4:\"rate\";s:0:\"\";}s:6:\"point4\";a:3:{s:4:\"name\";s:0:\"\";s:4:\"unit\";s:0:\"\";s:4:\"rate\";s:0:\"\";}s:6:\"point5\";a:3:{s:4:\"name\";s:0:\"\";s:4:\"unit\";s:0:\"\";s:4:\"rate\";s:0:\"\";}s:6:\"point6\";a:3:{s:4:\"name\";s:0:\"\";s:4:\"unit\";s:0:\"\";s:4:\"rate\";s:0:\"\";}}','member');");
E_D("replace into `modoer_config` values('siteclose','0','modoer');");
E_D("replace into `modoer_config` values('icpno','','modoer');");
E_D("replace into `modoer_config` values('sitename','Modoer����ϵͳ','modoer');");
E_D("replace into `modoer_config` values('seccode','0','modoer');");
E_D("replace into `modoer_config` values('useripaccess','','modoer');");
E_D("replace into `modoer_config` values('adminipaccess','','modoer');");
E_D("replace into `modoer_config` values('ban_ip','','modoer');");
E_D("replace into `modoer_config` values('gzipcompress','0','modoer');");
E_D("replace into `modoer_config` values('scriptinfo','1','modoer');");
E_D("replace into `modoer_config` values('picture_upload_size','2000','modoer');");
E_D("replace into `modoer_config` values('watermark','1','modoer');");
E_D("replace into `modoer_config` values('jstransfer','1','modoer');");
E_D("replace into `modoer_config` values('jsaccess','','modoer');");
E_D("replace into `modoer_config` values('googlesearch','0','modoer');");
E_D("replace into `modoer_config` values('googlesearch_website','modoer.com','modoer');");
E_D("replace into `modoer_config` values('tplext','.htm','modoer');");
E_D("replace into `modoer_config` values('mapapi','http://api.51ditu.com/js/maps.js','modoer');");
E_D("replace into `modoer_config` values('datacall_dir','./data/datacall','modoer');");
E_D("replace into `modoer_config` values('datacall_clearinterval','1','modoer');");
E_D("replace into `modoer_config` values('datacall_cleartime','1','modoer');");
E_D("replace into `modoer_config` values('search_limit','60','modoer');");
E_D("replace into `modoer_config` values('search_maxspm','20','modoer');");
E_D("replace into `modoer_config` values('search_maxresults','500','modoer');");
E_D("replace into `modoer_config` values('search_cachelife','3600','modoer');");
E_D("replace into `modoer_config` values('rewrite','0','modoer');");
E_D("replace into `modoer_config` values('rewritecompatible','1','modoer');");
E_D("replace into `modoer_config` values('subname','-���������ѧϰ���о�ʹ�ã�����������ҵ��;','modoer');");
E_D("replace into `modoer_config` values('titlesplit',',','modoer');");
E_D("replace into `modoer_config` values('meta_keywords','���������ѧϰ���о�ʹ�ã�����������ҵ��;','modoer');");
E_D("replace into `modoer_config` values('meta_description','���������ѧϰ���о�ʹ�ã�����������ҵ��;','modoer');");
E_D("replace into `modoer_config` values('headhtml','<meta name=\"author\" content=\"�ö�������www.17558.net\" />','modoer');");
E_D("replace into `modoer_config` values('templateid','0','member');");
E_D("replace into `modoer_config` values('editor_relativeurl','1','modoer');");
E_D("replace into `modoer_config` values('page_cachetime','0','modoer');");
E_D("replace into `modoer_config` values('console_menuid','3','modoer');");
E_D("replace into `modoer_config` values('closereg','0','member');");
E_D("replace into `modoer_config` values('censoruser','*admin*\r\n*����Ա*','member');");
E_D("replace into `modoer_config` values('existsemailreg','0','member');");
E_D("replace into `modoer_config` values('salutatory','1','member');");
E_D("replace into `modoer_config` values('salutatory_msg','�𾴵�\$username��\r\n\r\n��ӭ������\$sitename���ͥ��\r\nף����\$sitename������죡\r\n\r\n\$sitename��Ӫ�Ŷ�\r\n\$time','member');");
E_D("replace into `modoer_config` values('showregrule','1','member');");
E_D("replace into `modoer_config` values('regrule','������д���û���ע��Э�飡','member');");
E_D("replace into `modoer_config` values('pic_width','200','item');");
E_D("replace into `modoer_config` values('pic_height','150','item');");
E_D("replace into `modoer_config` values('video_width','250','item');");
E_D("replace into `modoer_config` values('video_height','200','item');");
E_D("replace into `modoer_config` values('review_min','10','review');");
E_D("replace into `modoer_config` values('review_max','1500','review');");
E_D("replace into `modoer_config` values('respond_min','10','review');");
E_D("replace into `modoer_config` values('respond_max','500','review');");
E_D("replace into `modoer_config` values('avatar_review','0','review');");
E_D("replace into `modoer_config` values('pcatid','9','item');");
E_D("replace into `modoer_config` values('list_num','20','item');");
E_D("replace into `modoer_config` values('review_num','5','review');");
E_D("replace into `modoer_config` values('respond_num','5','review');");
E_D("replace into `modoer_config` values('classorder','order','item');");
E_D("replace into `modoer_config` values('thumb','2','item');");
E_D("replace into `modoer_config` values('show_thumb','1','item');");
E_D("replace into `modoer_config` values('show_thumb_sort','small','item');");
E_D("replace into `modoer_config` values('mapapi_charset','','modoer');");
E_D("replace into `modoer_config` values('main_menuid','1','modoer');");
E_D("replace into `modoer_config` values('respondcheck','0','item');");
E_D("replace into `modoer_config` values('pid','1','item');");
E_D("replace into `modoer_config` values('closenote','�������������Ժ����...','modoer');");
E_D("replace into `modoer_config` values('gbook','1','space');");
E_D("replace into `modoer_config` values('gbook_guest','1','space');");
E_D("replace into `modoer_config` values('gbook_seccode','1','space');");
E_D("replace into `modoer_config` values('templateid','0','space');");
E_D("replace into `modoer_config` values('recordguest','1','space');");
E_D("replace into `modoer_config` values('spacename','{username}�ĸ��˿ռ�','space');");
E_D("replace into `modoer_config` values('spacedescribe','�������ģ��������·��','space');");
E_D("replace into `modoer_config` values('index_reviews','5','space');");
E_D("replace into `modoer_config` values('index_gbooks','5','space');");
E_D("replace into `modoer_config` values('reviews','10','space');");
E_D("replace into `modoer_config` values('gbooks','10','space');");
E_D("replace into `modoer_config` values('seccode_review','0','review');");
E_D("replace into `modoer_config` values('seccode_picupload','1','item');");
E_D("replace into `modoer_config` values('seccode_guestbook','0','item');");
E_D("replace into `modoer_config` values('seccode_respond','1','review');");
E_D("replace into `modoer_config` values('templateid','1','modoer');");
E_D("replace into `modoer_config` values('foot_menuid','66','modoer');");
E_D("replace into `modoer_config` values('scoretype','10','review');");
E_D("replace into `modoer_config` values('decimalpoint','2','review');");
E_D("replace into `modoer_config` values('seccode_review_guest','1','review');");
E_D("replace into `modoer_config` values('seccode_subject','0','item');");
E_D("replace into `modoer_config` values('tag_split_sp','1','item');");
E_D("replace into `modoer_config` values('menuid','80','space');");
E_D("replace into `modoer_config` values('space_menuid','80','space');");
E_D("replace into `modoer_config` values('multi_upload_pic','1','item');");
E_D("replace into `modoer_config` values('multi_upload_pic_num','10','item');");
E_D("replace into `modoer_config` values('console_seccode','0','modoer');");
E_D("replace into `modoer_config` values('console_total','1','modoer');");
E_D("replace into `modoer_config` values('ownernews','1','product');");
E_D("replace into `modoer_config` values('ownernews_classid','1','product');");
E_D("replace into `modoer_config` values('ownernews_check','0','product');");
E_D("replace into `modoer_config` values('seccode_product','0','product');");
E_D("replace into `modoer_config` values('check_product','1','product');");
E_D("replace into `modoer_config` values('check_comment','0','product');");
E_D("replace into `modoer_config` values('guest_post','0','comment');");
E_D("replace into `modoer_config` values('member_seccode','0','comment');");
E_D("replace into `modoer_config` values('guest_seccode','0','comment');");
E_D("replace into `modoer_config` values('disable_comment','0','comment');");
E_D("replace into `modoer_config` values('guest_comment','0','comment');");
E_D("replace into `modoer_config` values('check_comment','0','comment');");
E_D("replace into `modoer_config` values('post_comment','1','product');");
E_D("replace into `modoer_config` values('filter_word','1','comment');");
E_D("replace into `modoer_config` values('list_num','5','comment');");
E_D("replace into `modoer_config` values('hidden_comment','0','comment');");
E_D("replace into `modoer_config` values('comment_interval','5','comment');");
E_D("replace into `modoer_config` values('mapflag','51ditu','modoer');");
E_D("replace into `modoer_config` values('manage_comment','0','product');");
E_D("replace into `modoer_config` values('seccode_reg','0','member');");
E_D("replace into `modoer_config` values('seccode_login','0','member');");
E_D("replace into `modoer_config` values('mail_debug','0','modoer');");
E_D("replace into `modoer_config` values('ownernews','1','exchange');");
E_D("replace into `modoer_config` values('ownernews_classid','1','exchange');");
E_D("replace into `modoer_config` values('ownernews_check','0','exchange');");
E_D("replace into `modoer_config` values('thumb_w','160','exchange');");
E_D("replace into `modoer_config` values('thumb_h','100','exchange');");
E_D("replace into `modoer_config` values('exchange_seccode','1','exchange');");
E_D("replace into `modoer_config` values('keywords','��Ʒ�һ�,�ҽ�����','exchange');");
E_D("replace into `modoer_config` values('description','��Ʒ�һ�ģ���û���Աʹ�ý�Ҷһ���վ�ṩ����Ʒ','exchange');");
E_D("replace into `modoer_config` values('picture_createthumb_level','80','modoer');");
E_D("replace into `modoer_config` values('keywords','����ģ��','article');");
E_D("replace into `modoer_config` values('description','������Ϣ��������վ��Ϣ��������Ѷ','article');");
E_D("replace into `modoer_config` values('editor_image','1','article');");
E_D("replace into `modoer_config` values('rss','1','article');");
E_D("replace into `modoer_config` values('owner_post','1','article');");
E_D("replace into `modoer_config` values('member_post','0','article');");
E_D("replace into `modoer_config` values('post_check','1','article');");
E_D("replace into `modoer_config` values('post_filter','1','article');");
E_D("replace into `modoer_config` values('list_num','20','article');");
E_D("replace into `modoer_config` values('owner_category','0','article');");
E_D("replace into `modoer_config` values('member_category','0','article');");
E_D("replace into `modoer_config` values('post_seccode','0','article');");
E_D("replace into `modoer_config` values('member_bysubject','0','article');");
E_D("replace into `modoer_config` values('meta_keywords','����ģ��','article');");
E_D("replace into `modoer_config` values('meta_description','������Ϣ��������վ��Ϣ��������Ѷ','article');");
E_D("replace into `modoer_config` values('post_comment','1','article');");
E_D("replace into `modoer_config` values('att_custom','1|ͷ��(Ĭ����ʾ2��)\r\n2|�����Ƽ�(Ĭ����ʾ7��)\r\n3|ͼƬ�Ƽ�(Ĭ����ʾ3��)\r\n4|ģ����ҳͼƬ�ֻ�(���˹���)','article');");
E_D("replace into `modoer_config` values('meta_keywords','�ҽ�����','exchange');");
E_D("replace into `modoer_config` values('meta_description','�ҽ�����ģ�飬�������ѽ��','exchange');");
E_D("replace into `modoer_config` values('map_view_level','2','modoer');");
E_D("replace into `modoer_config` values('guestbook_min','10','item');");
E_D("replace into `modoer_config` values('guestbook_max','50','item');");
E_D("replace into `modoer_config` values('content_min','10','comment');");
E_D("replace into `modoer_config` values('content_max','200','comment');");
E_D("replace into `modoer_config` values('meta_keywords','��������','link');");
E_D("replace into `modoer_config` values('meta_description','Modoer����ϵͳ����������ģ�飡','link');");
E_D("replace into `modoer_config` values('num_logo','5','link');");
E_D("replace into `modoer_config` values('num_char','20','link');");
E_D("replace into `modoer_config` values('open_apply','1','link');");
E_D("replace into `modoer_config` values('apply','1','card');");
E_D("replace into `modoer_config` values('applyseccode','1','card');");
E_D("replace into `modoer_config` values('coin','10','card');");
E_D("replace into `modoer_config` values('applynum','2','card');");
E_D("replace into `modoer_config` values('applydes','������д�����ύʱ����ʾ����Ա��������˵����������','card');");
E_D("replace into `modoer_config` values('subtitle','���Żݵ������ۿۿ�','card');");
E_D("replace into `modoer_config` values('meta_keywords','��Ա��','card');");
E_D("replace into `modoer_config` values('meta_description','modoer����ϵͳ��Ա��ģ��','card');");
E_D("replace into `modoer_config` values('check','1','coupon');");
E_D("replace into `modoer_config` values('post_item_owner','1','coupon');");
E_D("replace into `modoer_config` values('watermark','1','coupon');");
E_D("replace into `modoer_config` values('thumb_width','160','coupon');");
E_D("replace into `modoer_config` values('thumb_height','100','coupon');");
E_D("replace into `modoer_config` values('seccode','1','coupon');");
E_D("replace into `modoer_config` values('listnum','10','coupon');");
E_D("replace into `modoer_config` values('des','�������Ż�ȯ�����ı�֤˵����','coupon');");
E_D("replace into `modoer_config` values('subtitle','�����Ż�','coupon');");
E_D("replace into `modoer_config` values('meta_keywords','�Ż�ȯģ��','coupon');");
E_D("replace into `modoer_config` values('meta_description','Modoer����ϵͳ֮�Ż�ȯģ��','coupon');");
E_D("replace into `modoer_config` values('post_comment','1','coupon');");
E_D("replace into `modoer_config` values('picture_createthumb_mod','0','modoer');");
E_D("replace into `modoer_config` values('watermark_postion','5','modoer');");
E_D("replace into `modoer_config` values('thumb_width','200','product');");
E_D("replace into `modoer_config` values('thumb_height','150','product');");
E_D("replace into `modoer_config` values('picture_ext','jpg jpeg png gif','modoer');");
E_D("replace into `modoer_config` values('select_city','1','article');");
E_D("replace into `modoer_config` values('copyright','&#169; 2007 - 2011 <a href=\"http://www.modoer.com\" target=\"_blank\">İ������</a> ��Ȩ����','modoer');");
E_D("replace into `modoer_config` values('buildinfo','1','modoer');");
E_D("replace into `modoer_config` values('statement','����������վ�ڻ�Ա���۽��������˹۵㣬����������վͬ����۵㣬��վ���е��ɴ�����ķ������Ρ�','modoer');");
E_D("replace into `modoer_config` values('feed_enable','1','member');");
E_D("replace into `modoer_config` values('watermark_text','www.17558.net','modoer');");
E_D("replace into `modoer_config` values('city_id','1','modoer');");
E_D("replace into `modoer_config` values('picture_max_width','800','modoer');");
E_D("replace into `modoer_config` values('picture_max_height','600','modoer');");
E_D("replace into `modoer_config` values('city_ip_location','0','modoer');");
E_D("replace into `modoer_config` values('index_digst_rand_num','2','review');");
E_D("replace into `modoer_config` values('index_pk_rand_num','1','review');");
E_D("replace into `modoer_config` values('index_show_bad_review','1','review');");
E_D("replace into `modoer_config` values('index_review_num','5','review');");
E_D("replace into `modoer_config` values('index_review_gettype','rand','review');");
E_D("replace into `modoer_config` values('content_min','10','article');");
E_D("replace into `modoer_config` values('content_max','50000','article');");
E_D("replace into `modoer_config` values('citypath_without','index/announcement\r\nfenlei/detail\r\nparty/detail\r\nask/detail\r\ntuan/detail\r\nproduct/detail\r\narticle/detail\r\nitem/detail\r\ncoupon/detail\r\nreview/detail\r\nexchange/gift\r\nspace/*','modoer');");
E_D("replace into `modoer_config` values('sellgroup_pointtype','point1','member');");
E_D("replace into `modoer_config` values('sellgroup_useday','30','member');");
E_D("replace into `modoer_config` values('passport_login','1','member');");
E_D("replace into `modoer_config` values('passport_pw','0','member');");
E_D("replace into `modoer_config` values('registered_again','0','member');");
E_D("replace into `modoer_config` values('email_verify','0','member');");
E_D("replace into `modoer_config` values('mobile_verify','0','member');");
E_D("replace into `modoer_config` values('mobile_verify_message','\$sitename �û��ֻ���֤��֤�룺\$serial','member');");
E_D("replace into `modoer_config` values('sldomain','0','item');");
E_D("replace into `modoer_config` values('base_sldomain','','item');");
E_D("replace into `modoer_config` values('reserve_sldomain','','item');");
E_D("replace into `modoer_config` values('selltpl_pointtype','point1','item');");
E_D("replace into `modoer_config` values('selltpl_useday','180','item');");
E_D("replace into `modoer_config` values('seccode_review','0','item');");
E_D("replace into `modoer_config` values('seccode_review_guest','0','item');");
E_D("replace into `modoer_config` values('review_min','10','item');");
E_D("replace into `modoer_config` values('review_max','2000','item');");
E_D("replace into `modoer_config` values('respond_min','10','item');");
E_D("replace into `modoer_config` values('respond_max','500','item');");
E_D("replace into `modoer_config` values('avatar_review','0','item');");
E_D("replace into `modoer_config` values('search_location','0','item');");
E_D("replace into `modoer_config` values('album_comment','1','item');");
E_D("replace into `modoer_config` values('ajax_taoke','0','item');");
E_D("replace into `modoer_config` values('review_num','','item');");
E_D("replace into `modoer_config` values('show_detail_vs_review','0','item');");
E_D("replace into `modoer_config` values('close_detail_total','0','item');");
E_D("replace into `modoer_config` values('list_filter_li_collapse_num','','item');");
E_D("replace into `modoer_config` values('pointgroup','point1','exchange');");
E_D("replace into `modoer_config` values('pointgroup','point1','card');");
E_D("replace into `modoer_config` values('topic_check','0','discussion');");
E_D("replace into `modoer_config` values('reply_check','0','discussion');");
E_D("replace into `modoer_config` values('topic_content_min','10','discussion');");
E_D("replace into `modoer_config` values('topic_content_max','5000','discussion');");
E_D("replace into `modoer_config` values('reply_content_min','10','discussion');");
E_D("replace into `modoer_config` values('reply_content_max','1000','discussion');");
E_D("replace into `modoer_config` values('topic_seccode','0','discussion');");
E_D("replace into `modoer_config` values('reply_seccode','0','discussion');");
E_D("replace into `modoer_config` values('city_sldomain','0','modoer');");
E_D("replace into `modoer_config` values('utf8url','0','modoer');");
E_D("replace into `modoer_config` values('picture_dir_mod','DAY','modoer');");
E_D("replace into `modoer_config` values('title','���⽻��','discussion');");
E_D("replace into `modoer_config` values('meta_keywords','Modoer������ģ��,���⽻��,������,������','discussion');");
E_D("replace into `modoer_config` values('meta_description','Modoer����ϵͳ��������ģ��','discussion');");
E_D("replace into `modoer_config` values('meta_keywords','','review');");
E_D("replace into `modoer_config` values('meta_description','','review');");
E_D("replace into `modoer_config` values('respondcheck','1','review');");
E_D("replace into `modoer_config` values('tag_split_sp','0','review');");
E_D("replace into `modoer_config` values('default_grade','0','review');");
E_D("replace into `modoer_config` values('digest_price','10','review');");
E_D("replace into `modoer_config` values('digest_pointtype','point1','review');");
E_D("replace into `modoer_config` values('digest_gain','','review');");
E_D("replace into `modoer_config` values('authkey','XX4PU320LF7V','modoer');");
E_D("replace into `modoer_config` values('siteurl','http://127.0.0.1:70/','modoer');");
E_D("replace into `modoer_config` values('jscache_flag_area','79','modoer');");
E_D("replace into `modoer_config` values('jscache_flag','352','item');");
E_D("replace into `modoer_config` values('jscache_flag','531','article');");
E_D("replace into `modoer_config` values('passport_list','weibo,qq,taobao','member');");
E_D("replace into `modoer_config` values('passport_weibo_name','weibo','member');");
E_D("replace into `modoer_config` values('passport_qq_name','qq','member');");
E_D("replace into `modoer_config` values('passport_taobao_name','taobao','member');");
E_D("replace into `modoer_config` values('passport_weibo_title','΢���ʺ�','member');");
E_D("replace into `modoer_config` values('passport_weibo_appkey','','member');");
E_D("replace into `modoer_config` values('passport_weibo_appsecret','','member');");
E_D("replace into `modoer_config` values('passport_qq_title','QQ�ʺ�','member');");
E_D("replace into `modoer_config` values('passport_qq_appid','','member');");
E_D("replace into `modoer_config` values('passport_qq_appkey','','member');");
E_D("replace into `modoer_config` values('passport_taobao_title','�Ա����˺�','member');");
E_D("replace into `modoer_config` values('passport_taobao_appkey','','member');");
E_D("replace into `modoer_config` values('passport_taobao_appsecret','','member');");
E_D("replace into `modoer_config` values('rewrite_location','0','modoer');");
E_D("replace into `modoer_config` values('rewrite_hide_index','0','modoer');");

require("../../inc/footer.php");
?>