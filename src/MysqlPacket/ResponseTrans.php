<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/9/10
 * Time: 15:37
 */

namespace PHPMysql\MysqlPacket;


use PHPMysql\UtiliHelper;

class ResponseTrans
{
    private $hex_resp;
    private $handle;

    public function __construct($response)
    {
        $this->hex_resp = $response;

        if($this->isResponsePack()){
            $this->handle = new ResponsePacket($this->hex_resp);
        }else{
            $this->handle = new ResultSet($this->hex_resp);
        }
    }

    private function isResponsePack(){
        //获取第一个字节判断是不是在 0x00-0xFA 之间
        $first_byte = UtiliHelper::HexSub($this->hex_resp[0],0,1);
        if(hexdec($first_byte) > 250){
            return true;
        }
        return false;
    }

    public function getData(){
        return $this->handle->getData();
    }
}