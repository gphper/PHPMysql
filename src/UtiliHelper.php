<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/9/8
 * Time: 9:25
 */

namespace PHPMysql;


class UtiliHelper
{

    /**
     * 将十六进制转为字符串
     * @author gphper 20200908
     * @param $hex_str
     * @return string
     */
    public static function HexToStr($hex_str){
        $send_msg = "";
        foreach (str_split($hex_str,2) as $key => $value) {
         	$send_msg .= chr(hexdec($value));
        }
        return $send_msg;
    }

    /**
     * 十六进制转整数
     * @author gphper 20200908
     * @param $hex_string
     * @return float|int
     */
    public static function HexToInt($hex_string){
        $z = implode("", array_reverse(str_split($hex_string,2)));
        return hexdec($z);
    }

    /**
     * 截取十六进制
     * @author gphper 20200908
     * @param $hex_str
     * @param $start
     * @param $length
     * @return bool|string
     */
    public static function HexSub($hex_str,$start,$length=0){
        if($length){
            return substr($hex_str, $start*2,$length*2);
        }
        return substr($hex_str, $start*2);
    }

    /**
     * 将十六进制消息体分段
     * @author gphper 20200908
     * @param $hex_str
     * @param int $is_body
     * @param array $all
     * @return array
     */
    public static function HexSplit($hex_str,$is_body=0,$all=[]){
        //先获取前三位
        $length = self::HexToInt(self::HexSub($hex_str,0,3));
        $total_length = $length + 4;
        $start = $is_body*8;
        $pre_str = substr($hex_str,$start,$total_length*2-$start);
        $sub_str = substr($hex_str,$total_length*2);
        $all = array_merge($all,array($pre_str));
        if($sub_str){
            return self::HexSplit($sub_str,$is_body,$all);
        }
        return $all;
    }

    /**
     * 十进制转为十六进制 小端存储
     * @author gphper 20200908
     * @param $length
     * @return string
     */
    public static function IntToHex($length){
        $big = str_pad(dechex($length),6,0,STR_PAD_LEFT);
        return implode("",array_reverse(str_split($big,2)));
    }

    /**
     * 字符转十六进制
     * @author gphper 20200908
     * @param $string
     * @return string
     */
    public static function StrToHex($string){
        $length = strlen($string);
        $hex = "";
        for ($i = 0; $i<$length; $i++){
            $hex .= str_pad(dechex(ord($string[$i])),2,0,STR_PAD_LEFT);
        }
        return $hex;
    }

    /**
     * 使用返回服务端返回的信息加密密码
     * $seed = "39011e567b3878441a0a560d52083e25336e3c34"
     * @author gphper 20200909
     * @param string $pass
     * @param string $seed
     * @return string
     */
    public static function encryptionPass($pass, $seed)
    {
        $pass1 = self::getBytes(sha1($pass, true));
        $pass2 = sha1(self::getString($pass1), true);
        $pass3 = self::getBytes(sha1(self::HexToStr($seed) . $pass2, true));
        $result = "";
        for ($i = 0, $count = count($pass3); $i < $count; ++$i) {
            $result .= str_pad(dechex(($pass3[$i] ^ $pass1[$i])),2,0,STR_PAD_LEFT);
        }
        return $result;
    }

    public static function getBytes($data)
    {
        $bytes = [];
        $count = strlen($data);
        for ($i = 0; $i < $count; ++$i) {
            $byte = ord($data[$i]);
            $bytes[] = $byte;
        }

        return $bytes;
    }


    public static function getString(array $bytes)
    {
        return implode(array_map('chr', $bytes));
    }




}