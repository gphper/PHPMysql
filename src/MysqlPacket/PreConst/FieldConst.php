<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/9/10
 * Time: 13:58
 */

namespace PHPMysql\MysqlPacket\PreConst;


class FieldConst
{
	const FIELD_TYPE_DECIMAL = "00";
    const FIELD_TYPE_TINY = "01";
	const FIELD_TYPE_SHORT = "02";
	const FIELD_TYPE_LONG  = "03";
	const FIELD_TYPE_FLOAT = "04";
	const FIELD_TYPE_DOUBLE = "05";
	const FIELD_TYPE_NULL = "06";
	const FIELD_TYPE_TIMESTAMP = "07";
	const FIELD_TYPE_LONGLONG = "08";
	const FIELD_TYPE_INT24 = "09";
	const FIELD_TYPE_DATE  = "0A";
	const FIELD_TYPE_TIME = "0B";
	const FIELD_TYPE_DATETIME = "0C";
	const FIELD_TYPE_YEAR = "0D";
	const FIELD_TYPE_NEWDATE = "0E";
	const FIELD_TYPE_VARCHAR = "0F";
	const FIELD_TYPE_BIT  = "10";
	const FIELD_TYPE_NEWDECIMAL = "F6";
	const FIELD_TYPE_ENUM  = "F7";
	const FIELD_TYPE_SET = "F8";
	const FIELD_TYPE_TINY_BLOB = "F9";
	const FIELD_TYPE_MEDIUM_BLOB = "FA";
	const FIELD_TYPE_LONG_BLOB  = "FB";
	const FIELD_TYPE_BLOB  = "FC";
	const FIELD_TYPE_VAR_STRING = "FD";
	const FIELD_TYPE_STRING = "FE";
	const FIELD_TYPE_GEOMETRY = "FF";
}