<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

class ms_image {

    var $watermark_postion      = 0;                //ˮӡλ�ã�Ĭ��Ϊ���½ǣ�1�����Ͻǣ�2�����Ͻǣ�3�����½ǣ�4���½ǣ�5������
    var $watermark_transparence = 50;               //͸���� :δʵ��
    var $watermark_text         = 'www.modoer.com'; //����ˮӡ
    var $thumb_mod              = 0;                //����ͼ��ʽ��Ĭ��Ϊ�ȱ������ţ�1��ʾ�������ü�

    function __construct() {
    }

    //��������ˮӡ
    function set_watermark_text($text) {
        if(!$text) return;
        $this->watermark_text = $text;
        $this->_convert_text();
    }

    //����ͼ�ȼ�
    function set_thumb_level($level) {
        if($level > 0 && $level <= 100) {
            $this->thumb_level = $level;
        }
    }

    //�ж��Ƿ�ΪͼƬ
    function is_image($imgfile) {
        if(!function_exists('getimagesize')) return false;
        if(!is_file($imgfile)) return false;
        return @getimagesize($imgfile);
    }

    //ͼƬ��ˮӡ
    function watermark($srcimg, $destimg, $waterimg, $level = 80) {
        if($this->_is_anim($srcimg)) return; //��������ˮӡ
        $simg = $this->_imagecreatefromimg($srcimg);
        if(!$simg) return;
        $path_parts = pathinfo($srcimg);
        $ext_name = strtolower($path_parts['extension']);
        $sw = imagesx($simg); //Ŀ��ͼƬ��
        $sh = imagesy($simg); //Ŀ��ͼƬ��
        imagealphablending($simg, true); //�趨���ģʽ
        $wimg = $this->_imagecreatefromimg($waterimg); //��ȡˮӡ�ļ�
        if(!$wimg) return;
        $ww = imagesx($wimg); //ˮӡͼƬ��
        $wh = imagesy($wimg); //ˮӡͼƬ��

        $postion = $this->watermark_postion ? $this->watermark_postion : mt_rand(1,5);
        if($postion==1) {
            $sx = 5; //����
            $sy = 10;
        } elseif($postion==2) {
            $sx = $sw - $ww - 5; //����
            $sy = 10;
        } elseif($postion==3) {
            $sx = 5; //����
            $sy = $sh - $wh - 10;
        } elseif($postion== 4) {
            $sx = $sw - $ww - 5; //���½�
            $sy = $sh - $wh - 10;
        } elseif($postion==5) {
            $sx = round($sw/2 - $ww/2); //����
            $sy = round($sh/2 - $wh/2);
        } elseif($postion==6) { //�ײ�����
            $fontfiles = MUDDER_ROOT . 'static/images/fonts/simsun.ttc';
            if(is_file($fontfiles) && $this->watermark_text) {
                $fontinfo = imagettfbbox(10, 0, $fontfiles, $this->watermark_text);
                $font_width = max($fontinfo[2],$fontinfo[4]);
                if($font_width < $sw) {
                    $new_img = ImageCreateTrueColor ($sw , $sh + 20);
                    imagecopy($new_img, $simg, 0, 0, 0, 0, $sw, $sh);
                    //if($ext_name == 'gif') {
                    //    $color = imagecolorallocate($new_img, 0, 0, 0);
                   // } else {
                        $color = imagecolorallocate($new_img, 255, 255, 255);
                    
                    imagettftext($new_img, 10, 0, 1, $sh + abs($fontinfo[7])+2, $color, $fontfiles, $this->watermark_text);
                    $fun = $this->_imagecreatefun($ext_name);
                    if($fun == 'imagejpeg') {
                        $fun( $new_img, $destimg, $level);
                    } else {
                        if($fun == 'imagegif') {
                            //$bgcolor = ImageColorAllocate($new_img ,0, 0, 0);
                            //$bgcolor = ImageColorTransparent($new_img, $bgcolor);
                        }
                        $result = $fun($new_img, $destimg);
                    }
                    ImageDestroy($simg);
                    ImageDestroy($new_img);
                    return $result;
                } else {
                    $mark_invalid = true;
                }
            } else {
                $mark_invalid = true;
            }
        }

        if(!$mark_invalid) {
            imagecopy($simg, $wimg, $sx, $sy, 0, 0, $ww, $wh);
        }
        $fun = $this->_imagecreatefun($ext_name);
		if($fun=='imagejpeg') {
			$fun( $simg, $destimg, $level);
		} else {
			if($fun=='imagegif') {
				$bgcolor = ImageColorAllocate($new_img ,0, 0, 0);
				$bgcolor = ImageColorTransparent($new_img, $bgcolor);
			}
			$fun( $simg, $destimg);
		}
        ImageDestroy($simg);
        ImageDestroy($wimg);
        return $result;
    }

    //��ȡ����ͼ
    function thumb($source_img_file, $dest_img_file, $new_width, $new_height, $level = 80) {
        $thumbfunc = $this->thumb_mod ? '_thumb2' : '_thumb';
        return $this->$thumbfunc($source_img_file, $dest_img_file, $new_width, $new_height, $level);
    }

    //�������ü�
    function _thumb($source_img_file, $dest_img_file, $new_width, $new_height, $level = 80) {
        $path_parts = pathinfo($source_img_file);
        $ext_name = strtolower($path_parts['extension']);
        $img = null;
        $img = $this->_imagecreatefromimg($source_img_file);
        $src_x = $src_y = 0;
        if ($img) {
            $imgcreate_fun = function_exists('ImageCreateTrueColor') ? 'ImageCreateTrueColor' : 'ImageCreate';
            $source_img_width = imagesx ($img);
            $source_img_height = imagesy ($img);
            if($source_img_width < $new_width || $source_img_height < $new_height) {
                $new_img_width = min($new_width,$source_img_width);
                $new_img_height = min($new_height,$source_img_height);
                $src_x = $source_img_width > $new_width ? (int) (($source_img_width - $new_width) / 2) : 0;
                $src_y = $source_img_height > $new_height ? (int) (($source_img_height - $new_height) / 2) : 0;
                $new_img = $imgcreate_fun ($new_img_width , $new_img_height);
                imagecopyresized($new_img ,$img, 0 ,0 ,$src_x ,$src_y ,$new_img_width, $new_img_height, $new_img_width , $new_img_height);
            } else {
                $w = $source_img_width / $new_width;
                if($source_img_height/$w >= $new_height) {
                    $new_img_width = $new_width;
                    $new_img_height = (int)($source_img_height / $source_img_width * $new_width);
                } else {
                    $new_img_height = $new_height;
                    $new_img_width = (int)($source_img_width / $source_img_height * $new_height);
                }
                $new_img = $imgcreate_fun ($new_width , $new_height);
                $new_img2 = $imgcreate_fun ($new_img_width , $new_img_height);
                ImageCopyResampled($new_img2, $img, 0, 0, 0, 0, $new_img_width, $new_img_height, $source_img_width, $source_img_height);
                $src_x = $src_y = 0;
                $src_x = $new_img_width > $new_width ? (int) (($new_img_width - $new_width) / 2) : 0;
                $src_y = $new_img_height > $new_height ? (int) (($new_img_height - $new_height) / 2) : 0;
                imagecopyresized($new_img ,$new_img2, 0 ,0 ,$src_x ,$src_y , $new_width, $new_height , $new_width , $new_height);
                imagedestroy($new_img2);
            }
            $fun = $this->_imagecreatefun($ext_name);
            if($fun=='imagejpeg') {
                $fun( $new_img, $dest_img_file, $level);
            } else {
                if($fun=='imagegif') {
                    $bgcolor = ImageColorAllocate($new_img ,0, 0, 0);
                    $bgcolor = ImageColorTransparent($new_img, $bgcolor);
                }
                $fun( $new_img, $dest_img_file);
            }
            imagedestroy($img);
            imagedestroy($new_img); 
        } else {
            redirect('global_upload_image_dnot_support');
        }
    }

    /*
    ��������ͼ ���ȱ�������
    ������������ʹ�� new_width ���ɣ�
    new_height �� $source_img_width > $source_img_height ������� ʹ��
    */
    function _thumb2($source_img_file, $dest_img_file, $new_width, $new_height, $level = 80) {
        $path_parts = pathinfo($source_img_file);
        $ext_name = strtolower($path_parts['extension']);
        $img = null;
        $img = $this->_imagecreatefromimg($source_img_file);
        if ($img) {
            $source_img_width = imagesx ($img);
            $source_img_height = imagesy ($img);
            if ($source_img_width > $source_img_height) {
                $new_img_width = $new_width;
                $new_img_height = (int)($source_img_height / $source_img_width * $new_width);
            } else {
                $new_img_height = $new_height;
                $new_img_width = (int)($source_img_width / $source_img_height * $new_height);
            }
            $imgcreate_fun = function_exists('ImageCreateTrueColor') ? 'ImageCreateTrueColor' : 'ImageCreate';
            $new_img = $imgcreate_fun($new_img_width, $new_img_height);
            ImageCopyResampled($new_img, $img, 0, 0, 0, 0, $new_img_width, $new_img_height, $source_img_width, $source_img_height);
            $fun = $this->_imagecreatefun($ext_name);
            if($fun=='imagejpeg') {
                $fun( $new_img, $dest_img_file, $level);
            } else {
                if($fun=='imagegif') {
                    $bgcolor = ImageColorAllocate($new_img ,0, 0, 0);
                    $bgcolor = ImageColorTransparent($new_img, $bgcolor);
                }
                $fun( $new_img, $dest_img_file);
            }
            imagedestroy($img);
            imagedestroy($new_img);
        } else {
            redirect('global_upload_image_dnot_support');
        }
    }

    //������Ч��img��Դ���
    function _imagecreatefromimg($img) {
        $ext_name = strtolower(pathinfo($img, PATHINFO_EXTENSION));
        switch($ext_name) {
            case 'gif':
                return function_exists('imagecreatefromgif') ? imagecreatefromgif($img) : false;
                break;
            case 'jpg':
            case 'jpe':
            case 'jpeg':
                return function_exists('imagecreatefromjpeg') ? imagecreatefromjpeg($img) : false;
                break;
            case 'png':
                return function_exists('imagecreatefrompng') ? imagecreatefrompng($img) : false;
                break;
            default:
                return FALSE;
        }
    }

    //���ش���ͼƬ�ĺ���
    function _imagecreatefun($ext) {
        switch($ext) {
        case 'gif':
            return 'imagegif';
        case 'png':
            return 'imagepng';
        default:
            return 'imagejpeg';
        }
    }

    //����UTF-8�����ˮӡ����
    function _convert_text() {
        if(preg_match("/^[a-z0-9\-\_\/]+$/", $this->watermark_text)) return;
        if(_G('charset') == 'gb2312') {
            $loader =& _G('loader');
            $loader->lib('chinese',null,false);
            $CHS = new ms_chinese('gb2312','utf-8');
            $this->watermark_text = $CHS->Convert($this->watermark_text);
            unset($CHS);
        }
    }

    //�ж��Ƿ�Ϊ����
    function _is_anim($srcfile) {
        $fp = fopen($srcfile, 'rb');
        $filecontent = fread($fp, filesize($srcfile));
        fclose($fp);
        return $this->_strposex($filecontent, 'NETSCAPE2.0');
    }

    function _strposex($string, $find, $offset = 0) {
        return !(strpos($string, $find, $offset) === false);
    }

}

