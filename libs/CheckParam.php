<?php
namespace Libs;

/**
 * 接口参数检查类
 * @author 志杨
 *
 */
class CheckParam
{
	/**
	 * 存放当前实例化类
	 */
	private static $instanse = null;
	/**
	 * 参数
	 * @var array
	 */
	private static $param = array();
	/**
	 * 是否必须
	 * @var boolean
	 */
	private static $require = false;
	/**
	 * 默认值
	 * @var mixed
	 */
	private static $default;
	
	final protected function __construct($param)
	{
		self::$param = $param;
	}
	
	public static function instance($param)
	{
		if (!is_array($param)) {
			throw new \Exception('参数只能是数组');
		}
		if (self::$instance == null) {
			self::$instance = new self($param);
		}
		return self::$instance;
	}
	/**
	 * 是否必须
	 * @param boolean $require
	 */
	public function isRequire($require)
	{
		if ($require) {
			self::$require=true;
		}
	}
	/**
	 * 默认值
	 * @param mixed $value
	 */
	public function defaultValue($value)
	{
		self::$default = $value;
	}
	/**
	 * 要检查的某参数
	 * @param string $key
	 */
	public function check($key)
	{
		if (self::$require) {
			if (!isset(self::$param[$key])) {
				throw new \Exceptions\CheckParamException(\Config\ParamsRule::$rules[$k]['desc']);
			} else {
				return self::$param[$key];
			}
		}else{
			if (!isset(self::$param[$key])) {
				return self::$default;
			} else {
				return self::$param[$key];
			}
		}
	}
}