<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/9/9
 * Time: 19:15
 */

namespace PHPMysql\MysqlPacket;


use PHPMysql\UtiliHelper;

class CurdHandle
{
    const COM_QUIT = "01"; //关闭连接	mysql_close
    const COM_INIT_DB = "02";//切换数据库	mysql_select_db
    const COM_QUERY = "03"; //SQL查询请求	mysql_real_query
    const COM_FIELD_LIST = "04"; //获取数据表字段信息	mysql_list_fields
    const COM_CREATE_DB = "05"; //创建数据库	mysql_create_db
    const COM_DROP_DB = "06"; //删除数据库	mysql_drop_db
    const COM_REFRESH = "07";//清除缓存	mysql_refresh
    const COM_SHUTDOWN = "08";    //停止服务器	mysql_shutdown
    const COM_STATISTICS = "09";    //获取服务器统计信息	mysql_stat
    const COM_PROCESS_INFO = "0A";    //获取当前连接的列表	mysql_list_processes
    const COM_CONNECT = "0B"; //（内部线程状态）	（无）
    const COM_PROCESS_KILL = "0C";    //中断某个连接	mysql_kill
    const COM_DEBUG = "0D";    //保存服务器调试信息	mysql_dump_debug_info
    const COM_PING = "0E";    //测试连通性	mysql_ping
    const COM_TIME = "0F";    //（内部线程状态）	（无）
    const COM_DELAYED_INSERT = "10";    //（内部线程状态）	（无）
    const COM_CHANGE_USER = "11";//重新登陆（不断连接）	mysql_change_user
    const COM_BINLOG_DUMP = "12";//获取二进制日志信息	（无）
    const COM_TABLE_DUMP = "13";    //获取数据表结构信息	（无）
    const COM_CONNECT_OUT = "14";    //（内部线程状态）	（无）
    const COM_REGISTER_SLAVE = "15";    //从服务器向主服务器进行注册	（无）
    const COM_STMT_PREPARE = "16";    //预处理SQL语句	mysql_stmt_prepare
    const COM_STMT_EXECUTE = "17";    //执行预处理语句	mysql_stmt_execute
    const COM_STMT_SEND_LONG_DATA = "18";    //发送BLOB类型的数据	mysql_stmt_send_long_data
    const COM_STMT_CLOSE = "19";    //销毁预处理语句	mysql_stmt_close
    const COM_STMT_RESET = "1A";    //清除预处理语句参数缓存	mysql_stmt_reset
    const COM_SET_OPTION = "1B";    //设置语句选项	mysql_set_server_option
    const COM_STMT_FETCH = "1C";    //获取预处理语句的执行结果	mysql_stmt_fetch

    /**
     * 数据库的增删查改发送协议是一样的
     * @author gphper 20200911
     * @param $sql
     * @return string
     */
    public function crud($sql){
        $body = CurdHandle::COM_QUERY.UtiliHelper::StrToHex($sql);
        return UtiliHelper::IntToHex(strlen($body)/2)."00".$body;
    }



    /**
     * 关闭连接
     * @author gphper 20200911
     * @return string
     */
    public function close(){
        return "0100000001";
    }
}