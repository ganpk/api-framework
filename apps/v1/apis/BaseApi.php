<?php
namespace Apps\V1\Apis;

use Illuminate\Container\Container;
use Illuminate\Database\Capsule\Manager as Capsule;

/**
 * API ����
 * ����
 * Class BaseApi
 * @package Apps\v1\apis
 */
class BaseApi
{
    /** ��ǰ���ʵ��������
     * @var BaseApi
     */
    protected static $instance = null;
    /**
     * ����ģʽ��ֹ�ⲿʵ����
     */
    final protected function __construct()
    {
    }

    /**
     * ����ģʽ��ֹ�ⲿ��¡
     */
    final protected function __clone()
    {
    }

    /**
     * ��ȡBaseApiʵ��
     * @return BaseApi
     */
    final public static function instance()
    {
        if (self::$instance === null) {
           $class = get_called_class();
            self::$instance = new $class();
        }
        return self::$instance;
    }
}