<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/9/11
 * Time: 9:00
 */

namespace PHPMysql\DBHandle;

use PHPMysql\UtiliHelper;
use PHPMysql\MysqlPacket\HandleShake;
use PHPMysql\MysqlPacket\AuthenLogin;
use PHPMysql\MysqlPacket\CurdHandle;
use PHPMysql\MysqlPacket\ResponseTrans;

class DB
{

    private $username;
    private $password;
    private $database;
    private $host;
    private $port;
    private $socket;
    private static $instance;

    public function __construct($username, $password, $database, $host, $port)
    {
        $this->username = $username;
        $this->password = $password;
        $this->database = $database;
        $this->host = $host;
        $this->port = $port;
        $this->socket = $this->newSocket();
        register_shutdown_function([$this,"close"]);
    }

    /**
     * 实例化DB类
     * @author gphper 20200911
     * @param $username
     * @param $password
     * @param $database
     * @param string $host
     * @param int $port
     * @return DB
     */
    public static function connection($username = "", $password = "", $database = "", $host = "127.0.0.1", $port = 3306)
    {
        if (!self::$instance) {
            self::$instance = new self($username, $password, $database, $host, $port);
        }
        return self::$instance;
    }

    /**
     * 创建tcp连接并完成登录验证
     * @author gphper 20200911
     * @return resource
     * @throws \Exception
     */
    private function newSocket()
    {
        //创建tcp套接字
        $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        //连接tcp
        socket_connect($socket, $this->host, $this->port);

        //初始化握手
        $msg = socket_read($socket, 8190, PHP_BINARY_READ);
        $body_arr = UtiliHelper::HexSplit(bin2hex($msg), 1);
        $handle_shark = new HandleShake($body_arr[0]);

        //认证登录
        $auth_login = new AuthenLogin();
        $auth_login->setUsername($this->username);
        $auth_login->setPassword($handle_shark->getSalt(), $this->password);
        $auth_login->setDatabase($this->database);
        $message = $auth_login->getMessage();

        $m = UtiliHelper::HexToStr($message);
        socket_write($socket, $m, strlen($m));

        $response_auth = socket_read($socket, 8190, PHP_BINARY_READ);
        $rbody = UtiliHelper::HexSplit(bin2hex($response_auth), 1)[0];
        if (UtiliHelper::HexSub($rbody, 0, 1) != "00") {
            throw new \Exception("验证失败");
        }
        return $socket;
    }

    /**
     * 普通查询方法
     * @author gphper 20200911
     * @param string $sql
     * @param int $is_utf8 结果是否包含中文 1包含 0不包含
     * @return array
     */
    public function query($sql, $is_utf8 = 0){

        //如果结果包含中文需要先发送 "set names utf8"
        if($is_utf8){
            $this->query("set names utf8");
        }

        //组装报文
        $hex_query = (new CurdHandle())->crud($sql);
        $h_query = UtiliHelper::HexToStr($hex_query);

        //发送报文
        socket_write($this->socket,$h_query,strlen($h_query));

        //处理服务器返回的相应消息
        $query_res = socket_read($this->socket,8190,PHP_BINARY_READ);
        $qres_hex = UtiliHelper::HexSplit(bin2hex($query_res),1);
        return (new ResponseTrans($qres_hex))->getData();
    }

    public function close(){
        $hex_query = (new CurdHandle())->close();
        $h_query = UtiliHelper::HexToStr($hex_query);
        socket_write($this->socket,$h_query,strlen($h_query));
    }

}