<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2011 Moufersoft
* @website www.modoer.com
*/
interface ISeccode {
    public function display();
}

abstract class ASCeccode implements ISeccode {
    protected $seccode = '';
    protected $width = 75;
    protected $height = 22;
    public function setSeccode($seccode) {
        $this->seccode = _T($seccode);
        return $this;
    }
    public function setWidth($width) {
        $this->width = (int)$width;
        return $this;
    }
    public function setHeight($height) {
        $this->height = (int)$height;
        return $this;
    }
}

class ms_seccode_factory {

    public static function create() {
        if(self::supperGD()) {
            return new ms_gd_seccode();
        } else {
            return new ms_bmp_seccode();
        }
    }

    protected static function supperGD() {
        return function_exists('imagecreate') && function_exists('imagecolorallocate') && function_exists('imagesetpixel') && function_exists('imageString') && function_exists('imagedestroy') && function_exists('imagefilledrectangle') && function_exists('imagerectangle') && (function_exists('imagepng') || function_exists('imagejpeg'));
    }

}

class ms_gd_seccode extends ASCeccode {

    public function display() {
        $x_size = $this->width;
        $y_size = $this->height;
        $aimg = imagecreate($x_size, $y_size);
        $black = imagecolorallocate($aimg, 0xFF, 0xFF, 0xFF);
        $border = imagecolorallocate($aimg, 0xCC, 0xCC, 0xCC);
        imagefilledrectangle($aimg, 0, 0, $x_size - 1, $y_size - 1, $black);
        imagerectangle($aimg, 0, 0, $x_size - 1, $y_size - 1, $border);

        for($i=1; $i<=50;$i++){
            $dot = imagecolorallocate($aimg, mt_rand(50, 255), mt_rand(0, 120), mt_rand(50, 255));
            imagesetpixel($aimg,mt_rand(2,$x_size-2), mt_rand(2,$y_size-2),$dot);
        }

        $dot = imagecolorallocate($aimg, mt_rand(50, 255), mt_rand(0, 120), mt_rand(50, 255));
        imageline($aimg,mt_rand(1,5),mt_rand(1,$y_size),mt_rand(5,$x_size/2),mt_rand(1, $y_size),$dot);
        imageline($aimg,mt_rand($x_size/2,($x_size/2)+5),mt_rand(1, $y_size),mt_rand(($x_size/2+5),$x_size),mt_rand(1, $y_size),$dot);

        $fontfiles = MUDDER_ROOT . 'static/images/fonts/arial.ttf';
        if(is_file($fontfiles)) {
            imagealphablending($aimg, true);
            for($i = 0; $i < strlen($this->seccode); $i++) {
                $color = imagecolorallocate($aimg, mt_rand(50, 255), mt_rand(0, 120), mt_rand(50, 255));
                $angle = (mt_rand(1,10)%2==0?'-':'') . mt_rand(10, 30);
                imagettftext($aimg, mt_rand(12,14), $angle, $i * $x_size / 4.5 + 5, mt_rand(16,22), $color, $fontfiles, $this->seccode[$i]);
            }
        } else {
            for($i = 0; $i < strlen($this->seccode); $i++) {
                imageString($aimg, 5, $i * $x_size / 4 + mt_rand(2, 5), mt_rand(1, 8), $this->seccode[$i], imagecolorallocate($aimg, mt_rand(50, 255), mt_rand(0, 120), mt_rand(50, 255)));
            }
        }

        header("Pragma:no-cache");
        header("Cache-control:no-cache");

        if(function_exists('imagepng') && imagepng($aimg)) {
            header("Content-type: image/png");
            imagepng($aimg);
        } else {
            header("Content-type: image/jpeg");
            imagejpeg($aimg);
        }

        imagedestroy($aimg);
        exit;
    }

}

class ms_bmp_seccode extends ASCeccode {

    public function display() {

        header("Pragma:no-cache");
        header("Cache-control:no-cache");
        header("ContentType: Image/BMP");
        
        $Color[0] = chr(0).chr(0).chr(0);
        $Color[1] = chr(255).chr(255).chr(255);
        $_Num[0]  = "1110000111110111101111011110111101001011110100101111010010111101001011110111101111011110111110000111";
        $_Num[1]  = "1111011111110001111111110111111111011111111101111111110111111111011111111101111111110111111100000111";
        $_Num[2]  = "1110000111110111101111011110111111111011111111011111111011111111011111111011111111011110111100000011";
        $_Num[3]  = "1110000111110111101111011110111111110111111100111111111101111111111011110111101111011110111110000111";
        $_Num[4]  = "1111101111111110111111110011111110101111110110111111011011111100000011111110111111111011111111000011";
        $_Num[5]  = "1100000011110111111111011111111101000111110011101111111110111111111011110111101111011110111110000111";
        $_Num[6]  = "1111000111111011101111011111111101111111110100011111001110111101111011110111101111011110111110000111";
        $_Num[7]  = "1100000011110111011111011101111111101111111110111111110111111111011111111101111111110111111111011111";
        $_Num[8]  = "1110000111110111101111011110111101111011111000011111101101111101111011110111101111011110111110000111";
        $_Num[9]  = "1110001111110111011111011110111101111011110111001111100010111111111011111111101111011101111110001111";

        echo chr(66).chr(77).chr(230).chr(4).chr(0).chr(0).chr(0).chr(0).chr(0).chr(0).chr(54).chr(0).chr(0).chr(0).chr(40).chr(0).chr(0).chr(0).chr(40).chr(0).chr(0).chr(0).chr(10).chr(0).chr(0).chr(0).chr(1).chr(0);
        echo chr(24).chr(0).chr(0).chr(0).chr(0).chr(0).chr(176).chr(4).chr(0).chr(0).chr(18).chr(11).chr(0).chr(0).chr(18).chr(11).chr(0).chr(0).chr(0).chr(0).chr(0).chr(0).chr(0).chr(0).chr(0).chr(0);

        for ($i=9;$i>=0;$i--){
            for ($j=0;$j<=3;$j++){
                for ($k=1;$k<=10;$k++){
                    if(mt_rand(0,7)<1){
                        echo $Color[mt_rand(0,1)];
                    }else{
                        echo $Color[substr($_Num[$this->seccode[$j]], $i * 10 + $k, 1)];
                    }
                }
            }
        }
        exit;
    }

}
?>