<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/9/11
 * Time: 10:57
 */

namespace PHPMysql\ExceptionHandle;


use Throwable;

class ResponsePacketException extends \Exception
{

    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

}