<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/9/10
 * Time: 9:51
 */

namespace PHPMysql\MysqlPacket;


use PHPMysql\ExceptionHandle\ResponsePacketException;
use PHPMysql\UtiliHelper;

class ResponsePacket
{
    private $hex_resp;
    //Ok 响应该值恒为 0x00 Error 响应恒为0xFF
    private $status;
    private $error_code;
    private $sql_state;
    private $error_msg;

    public function __construct($response)
    {
        $this->hex_resp = $response;
        $this->setStatus();
        if($this->status){
            $this->setErrorCode();
            $this->setErrorMsg();
            $this->setSqlState();
        }
    }

    private function setStatus(){
        $this->status = UtiliHelper::HexSub($this->hex_resp[0],0,1);
    }

    private function setErrorCode(){
        $this->error_code = UtiliHelper::HexToInt(UtiliHelper::HexSub($this->hex_resp[0],1,2));
    }

    private function setSqlState(){
        $this->sql_state = UtiliHelper::HexToStr(UtiliHelper::HexSub($this->hex_resp[0],4,5));
    }

    private function setErrorMsg(){
        $this->error_msg = UtiliHelper::HexToStr(UtiliHelper::HexSub($this->hex_resp[0],9));
    }

    public function isOk(){
        return $this->status? false : true;
    }

    public function getData(){
        if(strtoupper($this->status) == "FF"){
            throw new ResponsePacketException($this->error_msg,$this->sql_state);
        }
        return [
            "code" => 0,
            "msg" => "ok"
        ];
    }


}