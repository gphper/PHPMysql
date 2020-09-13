<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/9/10
 * Time: 11:38
 */

namespace PHPMysql\MysqlPacket;

use PHPMysql\UtiliHelper;

/**
 * 查询结果解析类
 * Class ResultSet
 *
 * 解析查询结果时接收到的数据转为十六进制后
 * 全部数据都按字符串处理包括字段是证书类型的字段值
 * 例如 03 32 32 35  代表字符串 225
 * 其中 03 代表该字符串占用三个字节的意思
 * 32 32 35 则为实际值所对应的十六进制ASCII
 *
 * @author gphper 20200911
 * @package PHPMysql\MysqlPacket
 */
class ResultSet
{
    private $columns = [];
    private $fields_count;
    private $data = [];

    public function __construct($data_string)
    {
        $count = 0;
        array_pop($data_string);
        foreach ($data_string as $k => $v){
            if($k == 0){
                //获取字段数
                $this->setFilesCount($v);
                continue;
            }

            //解析字段名称
            if($count < $this->fields_count){
                $this->setColumns($v);
                $count++;
                continue;
            }

            if($this->isEofPack($v)){
               continue;
            }

            $this->setColumnsData($v);
        }

    }

    /**
     * 解析查询字段总数
     * @author gphper 20200911
     * @param $data
     */
    private function setFilesCount($data){
        $this->fields_count = UtiliHelper::HexToInt($data);
    }

    /**
     * 解析字段信息
     * @author gphper 20200911
     * @param $data
     */
    private function setColumns($data){
        $data_arr = str_split($data,2);
        $split_arr = [];
        while(count($data_arr)){
            $length_tag = array_shift($data_arr);
            if($length_tag == "0c"){
                break;
            }
            $length = UtiliHelper::HexToInt($length_tag);
            $ceil = [];
            for ($i=0;$i<$length;$i++){
                array_push($ceil,array_shift($data_arr));
            }
            $split_arr[] = UtiliHelper::HexToStr(implode("",$ceil));
        }

        $split_arr[] = implode("",array_slice($data_arr,0,2));
        $split_arr[] = UtiliHelper::HexToInt(implode("",array_slice($data_arr,2,4)));
        $split_arr[] = implode("",array_slice($data_arr,6,1));
        $this->columns = array_merge($this->columns,array($split_arr[5]));
    }

    /**
     * 解析字段所对应的值
     * @author gphper 20200911
     * @param $data
     */
    private function setColumnsData($data){
        $split_arr = [];
        $data_arr = str_split($data,2);
        while(count($data_arr)){
            $length_tag = array_shift($data_arr);
            if($length_tag == "fb"){
                $split_arr[] = "NULL";
                continue;
            }
            $length = UtiliHelper::HexToInt($length_tag);
            $ceil = [];
            for ($i=0;$i<$length;$i++){
                array_push($ceil,array_shift($data_arr));
            }
            $split_arr[] = UtiliHelper::HexToStr(implode("",$ceil));
        }

        $result_data = array_combine($this->columns,$split_arr);
        array_push($this->data,$result_data);
    }

    /**
     * 解析是否是EOF
     * @author gphper 20200911
     * @param $data
     * @return bool
     */
    private function isEofPack($data){
        if(UtiliHelper::HexSub($data,0,1) == "fe"){
            return true;
        }
        return false;
    }

    public function getData(){
        return $this->data;
    }


}