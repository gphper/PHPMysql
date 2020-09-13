<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/9/9
 * Time: 15:52
 */

namespace PHPMysql\MysqlPacket;

use PHPMysql\UtiliHelper;

/**
 * 服务初始化握手
 * Class HandleShake
 *
 * @author gphper 20200909
 * @package PHPMysql\MysqlPacket
 */
class HandleShake
{
    private $hex_string;
    private $protocol_version;
    private $server_version;
    private $thread_id;
    private $salt1;
    private $salt2;

    public function __construct($hex_string)
    {
        $this->hex_string = $hex_string;
        $this->setProtocolVersion();
        $this->setServerVersion();
        $this->setThreadId();
        $this->setSalt1();
        $this->setSalt2();
    }

    public function setProtocolVersion(){
        $this->protocol_version = UtiliHelper::HexToInt(UtiliHelper::HexSub($this->hex_string,0,1));
    }

    public function setServerVersion(){
        $this->server_version = UtiliHelper::HexToStr(UtiliHelper::HexSub($this->hex_string,1,7));
    }

    public function setThreadId(){
        $this->thread_id = UtiliHelper::HexToInt(UtiliHelper::HexSub($this->hex_string,8,4));
    }

    public function setSalt1(){
        $this->salt1 = UtiliHelper::HexSub($this->hex_string,12,8);
    }

    public function setSalt2(){
        $this->salt2 = UtiliHelper::HexSub($this->hex_string,39,12);
    }

    public function getSalt(){
        return $this->salt1.$this->salt2;
    }

}