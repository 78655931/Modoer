<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
return array(

    'pay_name_alipay' => '支付宝',
    'pay_name_tenpay' => '财付通',
    'pay_name_chinabank' => '网银支付',
    'pay_name_paypal' => 'PayPal支付',

    'pay_type_point' => '积分',
    'pay_type_rmb' => '现金',

    'pay_status_0' => '未支付',
    'pay_status_1' => '交易成功',
    'pay_status_2' => '订单过期',

    'pay_card_status_1' => '正常',
    'pay_card_status_2' => '已使用',
    'pay_card_status_3' => '已失效',

    'pay_export_empty' => "没有任何数据被导出。",
    'pay_export_caption' => 'recharge_card_list',
    'pay_export_title' => array(
        'number' => '卡号',
        'password' => '密码',
        'cztype' => '类型',
        'price' => '面值',
        'endtime' => '有效期',
        'dateline' => '生成日期',
        'status' => '状态',
    ),

    'pay_payment_empty' => '对不起，网站尚未设置兑换类型。',
    'pay_payment_unselect' => '对不起，您未选择支付接口，请返回选择。',

    'pay_disabled' => '对不起，网站尚未设置支付接口。',
    'pay_price_min' => '充值金额不能小于 %s，请返回重新填写。',
    'pay_price_max' => '充值金额单次不能大于 %s，请返回重新填写。',
    'pay_cztype_empty' => '对不起，您未选充值类型，请返回选择。',
    'pay_ratio_empty' => '对不起，网站未设置充值兑换比率。',
    'pay_point_empty' => '兑换后的 %s 小于1，本次交易取消。',
    'pay_orderid_invalid' => '无法取得有效的订单号，请联系管理员。',
    'pay_order_empty' => '对不起，您的订单不存在。',
    'pay_order_error_status_1' => '您的订单已经完成交易，无法再次交易，请重新建立订单。',
    'pay_order_error_status_2' => '对不起，您的订单已过期，无法再次交易，请重新建立订单。',
    'pay_order_owner_invalid' => '这不是您的订单，您无法交易，请重新建立订单。',

    'pay_order_title' => '%s_%s_%s充值',//网站名称,会员名,积分类型

    'pay_card_disabled' => '对不起，管理员未开启积分卡充值功能。',
    'pay_card_recharge_empty' => '对不起，您未填写完整充值卡信息，请返回填写。',
    'pay_card_recharge_not_exists' => '对不起，您输入的卡号不存在。',
    'pay_card_recharge_status_invalid' => '对不起，您输入的积分充值卡已失效或已充值。',
    'pay_card_recharge_time_invalid' => '对不起，您输入的积分充值卡已过期。',
    'pay_card_recharge_pw_invalid' => '对不起，您卡号或者密码错误，请返回填写。',
    'pay_card_recharge_not_nopw' => '对不起，您输入的卡号不是一个无密码类型充值卡，请返回填写密码。',
    'pay_card_recharge_cztype_invalid' => '对不起，您的充值卡不能用于充值 %s ，请返回。',
    'pay_card_recharge_point_des' => '会员使用充值卡充值积分',
    'pay_card_recharge_succeed' => '充值成功，你已经成功充值 <b>%s</b> %s。',

    'pay_card_create_num_min' => '生成数量不能小于1，请返回修改。',
    'pay_card_create_num_max' => '对不起，一次性生成不能超过 %d 个，请返回修改。',
    'pay_card_create_price_min' => '面值不能小于1，请返回修改。',
    'pay_card_create_time_invalid' => '对不起，有效期不能早于当前时间，请返回修改。',
    'pay_card_create_prefix_invalid' => '对不起，您没有配置卡号前缀，无法使用此功能，请返回取消使用卡号前缀。',
    'pay_card_create_password_min' => '对不起，您设置的密码位数不能少于 %d 位，请返回设置。',
    'pay_card_create_type_empty' => '对不起，您未选充值卡充值类型。',
    'pay_card_create_succeed' => '积分充值卡批量生成完毕！',

    'pay_succeed' => '支付成功!',
    'pay_tenpay_price_empty' => '对不起，支付接口未获得支付金额信息。',
    'pay_tenpay_title_empty' => '对不起，支付接口未回的支付标题信息。',
    'pay_tenpay_orderid_empty' => '对不起，支付接口未回的交易订单号。',
    'pay_tenpay_url_empty' => '对不起，支付接口未返回支付路径。',

    'pay_cb_title' => '网银在线支付接口',
    'pay_cb_mid_empty' => '缺少网银商户号cb_mid',
    'pay_cb_key_empty' => '缺少网银帐号的MD5密钥cb_key',
    'pay_cb_id_invalid' => '错误的商户号。',
    'pay_cb_md5_invalid' => '验证MD5签名失败。',

    'pay_tenpay_id_invalid' => '错误的商户号。',
    'pay_tenpay_md5_invalid' => '验证MD5签名失败。',
    'pay_tenpay_error' => '支付失败, 系统错误。',
);
?>