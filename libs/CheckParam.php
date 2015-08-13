<?php
namespace Libs;

/**
 * 接口参数检查类
 * @author 志杨
 *
 */
class CheckParam
{
	public static function instance($param)
	{
		if (!is_array($param)) {
			throw new \Exception('参数只能是数组');
		}
		$obj = new self();
		$obj->param = $param;
		$obj->require = false;
		return $obj;
	}
	/**
	 * 是否必须
	 * @param boolean $require
	 */
	public function isRequire($require)
	{
		if ($require==true) {
			$this->require=true;
		}
		return $this;
	}
	/**
	 * 默认值
	 * @param mixed $value
	 */
	public function defaultValue($value)
	{
		$this->default = $value;
		return $this;
	}
	/**
	 * 要检查的某参数
	 * @param string $key
	 */
	public function check($key)
	{
		if (!isset($this->param[$key])) {
			if ($this->require) {
				throw new \Exceptions\CheckParamException(\Config\ParamsRule::$rules[$key]['desc']);
			}else{
				return $this->default;
			}
		} else {
			return $this->param[$key];
		}
	}
}