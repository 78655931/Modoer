<?php
/**
* ��������
* @author moufer<moufer@163.com>
* @copyright (C)2001-2007 Moufersoft
* @version $Id: passport.func.php 1 2007-8-30 $
*/

!defined('IN_MUDDER') && exit('Access Denied');

/**
* Passport ���ܺ���
* @param    string  �ȴ����ܵ�ԭ�ִ�
* @param    string  ˽���ܳ�(���ڽ��ܺͼ���)
* @return   string  ԭ�ִ�����˽���ܳ׼��ܺ�Ľ��
*/
function passport_encrypt($txt, $key) {

    // ʹ����������������� 0~32000 ��ֵ�� MD5()
    srand((double)microtime() * 1000000);
    $encrypt_key = md5(rand(0, 32000));

    // ������ʼ��
    $ctr = 0;
    $tmp = '';

    // for ѭ����$i Ϊ�� 0 ��ʼ����С�� $txt �ִ����ȵ�����
    for($i = 0; $i < strlen($txt); $i++) {
        // ��� $ctr = $encrypt_key �ĳ��ȣ��� $ctr ����
        $ctr = $ctr == strlen($encrypt_key) ? 0 : $ctr;
        // $tmp �ִ���ĩβ������λ�����һλ����Ϊ $encrypt_key �ĵ� $ctr λ��
        // �ڶ�λ����Ϊ $txt �ĵ� $i λ�� $encrypt_key �� $ctr λȡ���Ȼ�� $ctr = $ctr + 1
        $tmp .= $encrypt_key[$ctr].($txt[$i] ^ $encrypt_key[$ctr++]);
    }

    // ���ؽ�������Ϊ passport_key() ��������ֵ�� base64 ������
    return base64_encode(passport_key($tmp, $key));

}

/**
* Passport ���ܺ���
* @param    string  ���ܺ���ִ�
* @param    string  ˽���ܳ�(���ڽ��ܺͼ���)
* @return   string  �ִ�����˽���ܳ׽��ܺ�Ľ��
*/
function passport_decrypt($txt, $key) {

    // $txt �Ľ��Ϊ���ܺ���ִ����� base64 ���룬Ȼ����˽���ܳ�һ��
    // ���� passport_key() ���������ķ���ֵ
    $txt = passport_key(base64_decode($txt), $key);

    // ������ʼ��
    $tmp = '';

    // for ѭ����$i Ϊ�� 0 ��ʼ����С�� $txt �ִ����ȵ�����
    for ($i = 0; $i < strlen($txt); $i++) {
        // $tmp �ִ���ĩβ����һλ��������Ϊ $txt �ĵ� $i λ��
        // �� $txt �ĵ� $i + 1 λȡ���Ȼ�� $i = $i + 1
        $tmp .= $txt[$i] ^ $txt[++$i];
    }

    // ���� $tmp ��ֵ��Ϊ���
    return $tmp;

}

/**
* Passport �ܳ״�����
*
* @param    string  �����ܻ�����ܵ��ִ�
* @param    string  ˽���ܳ�(���ڽ��ܺͼ���)
*
* @return   string  �������ܳ�
*/
function passport_key($txt, $encrypt_key) {

    // �� $encrypt_key ��Ϊ $encrypt_key �� md5() ���ֵ
    $encrypt_key = md5($encrypt_key);

    // ������ʼ��
    $ctr = 0;
    $tmp = '';

    // for ѭ����$i Ϊ�� 0 ��ʼ����С�� $txt �ִ����ȵ�����
    for($i = 0; $i < strlen($txt); $i++) {
        // ��� $ctr = $encrypt_key �ĳ��ȣ��� $ctr ����
        $ctr = $ctr == strlen($encrypt_key) ? 0 : $ctr;
        // $tmp �ִ���ĩβ����һλ��������Ϊ $txt �ĵ� $i λ��
        // �� $encrypt_key �ĵ� $ctr + 1 λȡ���Ȼ�� $ctr = $ctr + 1
        $tmp .= $txt[$i] ^ $encrypt_key[$ctr++];
    }

    // ���� $tmp ��ֵ��Ϊ���
    return $tmp;

}

/**
* Passport ��Ϣ(����)���뺯��
*
* @param    array   �����������
*
* @return   string  ���龭�������ִ�
*/
function passport_encode($array) {

    // ���������ʼ��
    $arrayenc = array();

    // �������� $array������ $key Ϊ��ǰԪ�ص��±꣬$val Ϊ���Ӧ��ֵ
    foreach($array as $key => $val) {
        // $arrayenc ��������һ��Ԫ�أ�������Ϊ "$key=���� urlencode() ��� $val ֵ"
        $arrayenc[] = $key.'='.urlencode($val);
    }

    // ������ "&" ���ӵ� $arrayenc ��ֵ(implode)������ $arrayenc = array('aa', 'bb', 'cc', 'dd')��
    // �� implode('&', $arrayenc) ��Ľ��Ϊ ��aa&bb&cc&dd"
    return implode('&', $arrayenc);

}
?>
