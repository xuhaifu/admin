<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 对二维数组进行分组
 * 
 * @param array $data 需要分组的数组
 * @param string $group_by 分组的字段
 * @return array
 */
function array_field_group($data, $group_by)
{
    if(! is_array($data)) {
        return false;
    }
    $group_data = array();
    foreach($data as $val) {
        if(! isset($val[$group_by])) {
            continue;
        }
        $group_data[$val[$group_by]][] = $val;
    }
    return $group_data;
}

/**
 * 对二维数组进行排序
 * 
 * @param array $data 需要排序的字段
 * @param array $sort_field 按哪个键进行排序，如果不是所有键中都含有该字段则返回原数组
 * @param array $sort_type 排序方式 SORT_ASC 升序 SORT_DESC 降序
 * @return array
 */
function array_field_sort($data, $sort_field, $sort_type = SORT_ASC)
{
    if(! is_array($data)) {
        return false;
    }
    $sort_arr = array();
    foreach($data as $key => $val) {
        if(isset($val[$sort_field])) {
            $sort_arr[$key] = $val[$sort_field];
        }
    }
    if(count($sort_arr) == count($data)) {
        array_multisort($sort_arr, $sort_type, $data);
    }
    return $data;
}

/**
 * 选择二维数组中某个key的所有值
 * 
 * @param array $data
 * @param string $field
 * @param bool $unique
 * @return array
 */
function array_field_select($data, $field, $unique = false)
{
    if(! is_array($data)) {
        return array();
    }
    $select_arr = array();
    foreach ($data as $key => $val) {
        if(! isset($val[$field])) {
            continue;
        }
        $select_arr[] = $val[$field];
    }
    if($unique && count($select_arr) > 0) {
        $select_arr = array_unique($select_arr);
    }
    return $select_arr;
}
/* End of file MY_array_helper.php */
/* Location: ./application/helpers/MY_array_helper.php */