<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/3/21
 * Time: 1:04
 */

/**
 * 发送http请求
 *
 * @param $url
 * @param string $header
 * @param string $method
 * @param string $body
 * @return mixed
 */
function curl($url, $header = '', $method = 'GET', $body = '')
{
    // 初始化curl
    $curl = curl_init();
    // 设置请求方法
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
    // 设置请求url
    curl_setopt($curl, CURLOPT_URL, $url);
    if ($header) {
        // 设置请求头
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
    }
    // 设置请求发生错误时是否显示，true为不显示
    curl_setopt($curl, CURLOPT_FAILONERROR, false);
    // 请求成功只返回结果，不自动输出任何内容
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    // 是否在输出中包含头部信息
    curl_setopt($curl, CURLOPT_HEADER, false);
    if (1 == strpos("$" . $url, "https://")) {
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    }
    if ($body) {
        // 设置请求体
        curl_setopt($curl, CURLOPT_POSTFIELDS, $body);
    }
    // 执行curl
    $fileContents = curl_exec($curl);
    // 关闭curl
    curl_close($curl);
    // 返回结果
    return $fileContents;
}

/**
 * 获取指定长度的随机字符串
 *
 * @param $length
 * @return null|string
 */
function getRandChar($length)
{
    $str = null;
    $strPol = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz';
    $max = strlen($strPol) - 1;

    for ($i = 0; $i < $length; $i++) {
        $str .= $strPol[rand(0, $max)];
    }

    return $str;
}

/**
 * 生成订单号
 *
 * @return string
 */
function makeOrderNo()
{
    $yCode = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T');
    $orderSn = $yCode[intval(date('Y')) - 2018]
        . strtoupper(dechex(date('m')))
        . date('d') . substr(time(), -5)
        . substr(microtime(), 2, 5)
        . sprintf('%02d', rand(0, 99));

    return $orderSn;
}

/**
 * 获取指定年月第一天的时间戳
 *
 * @param string $year
 * @param string $month
 * @return false|string
 */
function getFirstDayOfTheMonth($year = '', $month = '')
{
    if (empty($year)) $year = date('Y');
    if (empty($month)) $month = date('m');
    $day = '01';

    //检测日期是否合法
    if (!checkdate($month, $day, $year)) return '输入的时间有误';

    //获取当年当月第一天的时间戳(时,分,秒,月,日,年)
    $timestamp = mktime(0, 0, 0, $month, $day, $year);
    $result = date('t', $timestamp);
    return $result;
}