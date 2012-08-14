<?php
return array(

    'review_title' => '点评',
    'review_list_title' => '点评大全',
    'review_respond' => '回应',

    'review_idtype_empty' => '对不起，您指定的点评对象不是一个有效的类型。',
    'review_object_empty' => '对不起，您的点评对象不存在，请返回。',
    'review_object_status_invalid' => '对不起，您的点评对象未审核或不存在，请返回。',

	'review_opt_group_empty' => '对不起，点评项组不存在。',
	'review_opt_group_empty_name' => '对不起，您未填写点评项组名称，请返回填写。',
	'review_opt_group_used' => '对不起，您选则的点评项组正在使用，无法删除。',

	'review_empty' => '对不起，点评信息不存在或已删除。',
	'review_status_invalid' => '对不起，主题未审核或已锁定，无法提交点评。',
	'review_pot_invalid' => '对不起，您未设置设置点评项 %s ，或设置的不是一个有效的值，请返回设置。',
	'review_price_empty' => '对不起，您未填写 %s，或不是一个有效的值，请返回填写。',
	'review_best_invalid' => '对不起，您未选择总体评价，或者设置的不是一个有效的值，请返回选择。',
	'review_content_empty' => '对不起，您未填写点评内容，请返回填写。',
	'review_content_charlen' => '请将点评内容的字符数量控制在 %s - %s 之间。',
	'review_form_invalid' => '对不起，您的点评提交表单不完整，请重新填写。',
    'review_title' => '%s点评:%s',
    'review_word_check' => '对不起，',
    'review_access_-1' => '对不起，您的权限不足无法进行点评。',
    'review_access_-2' => '对不起，当前分类不允许游客点评，请先登录或注册。',
    'review_access_-3' => '对不起，当前分类不允许重复点评，您可以浏览其他主题进行点评。',
    'review_access_-4' => '对不起，您没有重复点评的权限。',
    'review_access_-5' => '对不起，您已经点评满 {S} 次，无法再次点评。',
    'review_access_-6' => '对不起，您不能在 {S} 分钟内再次点评。',

    'review_respond_empty' => '对不起，回应信息不存在或已删除。',
    'review_respond_empty_content' => '对不起，您未填写回应内容。', 
    'review_respond_content_charlen' => '对不起，请将回应内容控制在 %s - %s 个字符之内，请返回修改。',

    'review_flower_myself' => '您无法给对自己的点评信息鲜花。',
    'review_flower_submitted' => '您已经为该点评信息献过花了。',

    'review_title' => '全部点评',
    'review_type_new' => '最新点评',
    'review_type_enjoy' => '喜欢程度',
    'review_type_flower' => '鲜花数',
    'review_type_respond' => '回应数量',

    'review_day_3' => '近三天',
    'review_day_7' => '近一周',
    'review_day_30' => '近一月',
    'review_day_365' => '近一年',

    'review_filter_all' => '综合',
    'review_filter_best' => '好评',
    'review_filter_bad' => '差评',
    'review_filter_pic' => '图文',
    'review_filter_digest' => '精华',

    'review_grade_array' => array(1=>'差',2=>'中',3=>'好',4=>'很好',5=>'非常好'),

    'review_report_sort_1' => '抄袭点评',
    'review_report_sort_2' => '重复点评',
    'review_report_sort_3' => '恶意点评',
    'review_report_sort_4' => '非法点评',
    'review_report_sort_5' => '不属于点评',
    'review_report_sort_6' => '其他违规',
    'review_report_empty' => '对不起，举报信息不存在或已删除。',
    'review_report_empty_sort' => '未选择举报类型。',
    'review_report_empty_content' => '未填写举报说明。',
    'review_report_succeed' => '举报成功！感谢您对我们的支持！',

    'review_digest_invalid' => '对不起，这不是一个精华点评。',
    'review_digest_point_not_enough' => '对不起，您的 %s 不足，无法购买精华点评。',
    'review_digest_fun_invalid' => '精华点评购买功能未配置完成，请联系管理员处理。',

    'review_access_avatar' => '您没有上传头像，请先上传一个头像。',

    'review_feed_icon' => 'debate',
    'review_feed_title_template' => '{username} 点评了 {subject}',
    'review_feed_body_template' => '{title} ({respond})',

    'review_respond_feed_icon' => 'thread',
    'review_respond_feed_title_template' => '{username} 回应了一个点评',
    'review_respond_feed_body_template' => '{content}',

    'review_task_post_title' => '点评类任务',
    'review_task_flower_title' => '鲜花类任务',

    'review_notice_new_review' => '%s 点评了 %s ，<a href="%s" target="_blank">查看</a>',
    'review_notice_new_respond' => '%s 回复了你的点评，<a href="%s" target="_blank">查看</a>',
    'review_notice_new_flower' => '%s 对您的点评表示赞同，赠送一朵鲜花，<a href="%s" target="_blank">查看</a>',

    'review_share_new_review' => '%s - 点评：%s',

    'review_feed_subject_title_template' => '{item_name} {subject} 新增加了一条点评',
    'review_feed_subject_body_template' => '{subject}',

);
?>