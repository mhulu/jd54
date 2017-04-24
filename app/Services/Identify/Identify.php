<?php 
namespace App\Services\Identify;
use Cache;
use Star\utils\StarJson;

class Identify
{
	use Data;
	public function __construct()
	{
		// 如果没有缓存，立马缓存
		if (empty(Cache::tags(['ID', 'province'])->get('37'))) {
			self::pushToRedis();
		}
	}

	public static function parse($pid)
	{
		if (!self::validateIDCard($pid)) {
			return StarJson::create(200, '身份证格式不正确');
		}
			$area = self::getArea($pid);
			$gender = self::getGender($pid);
			$birthday = self::getBirth($pid);
			return StarJson::create(200, [
					'area' => $area,
					'gender' => $gender,
					'birthday' => $birthday
			]);
	}

	private static function pushToRedis () {
		foreach (self::$provinces as $key => $value) {
			Cache::tags(['ID', 'province'])->forever($key, $value);
		}
		foreach (self::$divisions as $key => $value) {
			Cache::tags(['ID', 'division'])->forever($key, $value);
		}
	}
	
	private static function validateIDCard($pid)
	{
		$pid = strtoupper($pid);
		if (self::checkFirst($pid)) {
	           //第一步正则检测
			$iYear = substr($pid, 6, 4);
			$iMonth = substr($pid, 10, 2);
			$iDay = substr($pid, 12, 2);
			if (checkdate($iMonth, $iDay, $iYear)) {
	               //第二步检测身份证日期
	               $idcard_base = substr($pid, 0, 17);  //身份证证号前17位
	               if (self::getIDCardVerifyNumber($idcard_base) != substr($pid, 17, 1)) {
	                   //第三步校验和
	               	return false;
	               } else {
	               	return true;
	               }
	             }
	           } else {
	           	return false;
	           }
	         }
	   /**
	       * 通过正则表达式初步检测身份证证号非法性.
	       * @param string $pid 传入的个人身份证证号
	       * @return bool 通过返回true，否则返回false
	       */
	   private static function checkFirst($pid)
	   {
	   	return preg_match('/^\d{6}(18|19|20)\d{2}(0[1-9]|1[012])(0[1-9]|[12]\d|3[01])\d{3}(\d|X)$/', $pid);
	   }
	      /**
	       * 验证身份证是否合法.
	       * @param string $pid 个人身份证证号
	       * @return bool 合法，则返回true，失败则返回false
	       */
	   /**
	    * 根据身份证前17位计算身份证最后一位校验码
	    * @param string $idcard_base
	    * @return string
	    */
	   private static function getIDCardVerifyNumber($idcard_base)
	   {
	       // 加权因子
	   	$factor = array(7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2);
	       // 校验码对应值
	   	$verify_number_list = array('1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2');
	   	$checksum = 0;
	   	for ($i = 0; $i < strlen($idcard_base); $i++) {
	   		$checksum += substr($idcard_base, $i, 1) * $factor[$i];
	   	}
	   	$mod = $checksum % 11;
	   	$verify_number = $verify_number_list[$mod];
	   	return $verify_number;
	   }
	   /**
	    * 根据身份证证号获取所在地区.
	    * @param string $pid 个人身份证证号
	    * @return array 结果数组
	    */
	   private static function getArea($pid)
	   {
	   	$province = substr($pid, 0, 2);
	       $sufix_province = $province.'0000';  //获取省级行政区划代码
	       $sufix_city = substr($pid, 0, 4).'00';  //获取地级行政区划代码
	       $county = substr($pid, 0, 6);  //获取县级行政区划代码
	       $result = '';
	       if (empty(Cache::tags(['ID', 'province'])->get($province))) {
	       			return '未能获取身份证户籍，请核对';
		       }
	       	if (!empty(Cache::tags(['ID', 'division'])->get($county))) {
		       		$result = Cache::tags(['ID', 'province'])
		       					->get($province).Cache::tags(['ID', 'division'])
		       					->get($sufix_city).Cache::tags(['ID', 'division'])
		       					->get($county);
		       		return $result;
	       		}
	       }
	   /**
	    * 获取性别.
	    * @param string $pid 个人身份证证号
	    * @return string|bool 返回0表示女，返回1表示男，身份证未校验通过则返回false
	    */
	   private static function getGender($pid)
	   {
	           $gender = substr($pid, 16, 1);  //倒数第2位
	           return ($gender % 2 == 0) ? 0 : 1;
	       }
	   /**
	    * 获取出生年月日信息.
	    * @param string $pid    个人身份证证号
	    * @param string $format 日期格式 默认为'Y-m-d'
	    * @return string|bool 返回特定日期格式的数据，如'1990-01-01'，身份证或出生年月日未校验通过则返回false
	    */
	   private static function getBirth($pid, $format = 'Y-m-d')
	   {
	   		$iYear = substr($pid, 6, 4);
	   		$iMonth = substr($pid, 10, 2);
	   		$iDay = substr($pid, 12, 2);
	   		$str = date($format, mktime(0, 0, 0, $iMonth, $iDay, $iYear));
	   		return $str;
	   }
	 }