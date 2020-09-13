<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/9/9
 * Time: 16:50
 */

namespace PHPMysql\MysqlPacket;


use PHPMysql\UtiliHelper;

class AuthenLogin
{
    private $tags;


    public function __construct()
    {
        $this->tags = [
            "power_tag" => "",
            "power_ext" => "",
            "max_length" => "",
            "charset" => "",
            "fill_pad" => "",
            "username" => "",
            "password" => "",
            "database" => "",
            "client_auth_plugin" => "",
            "payload" => ""
        ];
        $this->tags['power_tag'] = "8da2";
        $this->tags['power_ext'] = "0b00";
        $this->tags['max_length'] = "000000c0";
        $this->tags['charset'] = "08";
        $this->tags['fill_pad'] = "0000000000000000000000000000000000000000000000";
        $this->tags['client_auth_plugin'] = "6d7973716c5f6e61746976655f70617373776f726400";
        $this->tags["payload"] = "150c5f636c69656e745f6e616d65076d7973716c6e64";
    }

    public function setUsername($username){
        $this->tags['username'] = UtiliHelper::StrToHex($username)."0014";
    }

    public function setPassword($salt,$pass){
        $this->tags['password'] = UtiliHelper::encryptionPass($pass,$salt);
    }

    public function setDatabase($database){
        $this->tags['database'] = UtiliHelper::StrToHex($database)."00";
    }

    public function getMessage(){
        $message = "";
        foreach ($this->tags as $tagv){
            $message .= $tagv;
        }
        return UtiliHelper::IntToHex(strlen($message)/2)."01".$message;
    }

}