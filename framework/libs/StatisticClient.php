<?php
namespace Framework\Libs;

/**
 * 统计客户端
 * @author workerman
 * @author xmc 2015.06.06
 */
class StatisticClient
{
	/**
	 * [module=>[interface=>time_start, interface=>time_start .
	 * ..], module=>[interface=>time_start ..], ... ]
	 * @var array
	 */
	protected static $timeMap = array();

	/**
	 * 模块接口上报消耗时间记时
	 * @param string $module        	
	 * @param string $interface        	
	 * @return void
	 */
	public static function tick($module = '', $interface = '')
	{
		return self::$timeMap[$module][$interface] = microtime(true);
	}

	/**
	 * 上报统计数据
	 * 
	 * @param string $module        	
	 * @param string $interface        	
	 * @param bool $success        	
	 * @param int $code        	
	 * @param string $msg        	
	 * @param string $report_address        	
	 * @return boolean
	 */
	public static function report($module, $interface, $success, $code, $msg, $report_address = '')
	{
		$report_address = $report_address ? $report_address : 'udp://127.0.0.1:55656';
		if (isset(self::$timeMap[$module][$interface]) && self::$timeMap[$module][$interface] > 0) {
			$time_start = self::$timeMap[$module][$interface];
			self::$timeMap[$module][$interface] = 0;
		} else 
			if (isset(self::$timeMap['']['']) && self::$timeMap[''][''] > 0) {
				$time_start = self::$timeMap[''][''];
				self::$timeMap[''][''] = 0;
			} else {
				$time_start = microtime(true);
			}
		
		$cost_time = microtime(true) - $time_start;
		$bin_data = self::protocolEncode($module, $interface, $cost_time, $success, $code, $msg);
		
		//清空准备上报的内容，释放内存
		self::$timeMap = array();
		
		return self::sendData($report_address, $bin_data);
	}

	/**
	 * 发送数据给统计系统
	 * @param string $address        	
	 * @param string $buffer        	
	 * @return boolean
	 */
	public static function sendData($address, $buffer)
	{
		$socket = stream_socket_client($address);
		if (! $socket) {
			return false;
		}
		return stream_socket_sendto($socket, $buffer) == strlen($buffer);
	}
	
	/**
	 * 编码
	 * @param string $module
	 * @param string $interface
	 * @param float $cost_time
	 * @param int $success
	 * @param int $code
	 * @param string $msg
	 * @return string
	 */
	public static function protocolEncode($module, $interface, $cost_time, $success, $code = 0, $msg = '')
	{
	    $data = array(
	        'module' => $module,
	        'interface' => $interface,
	        'cost_time' => $cost_time,
	        'success' => $success,
	        'time' => time(),
	        'code' => $code,
	        'msg' => $msg
	    );
	    $string = json_encode($data);
	    $packData = pack('N', strlen($string)).$string;
	    return $packData;
	}
}
