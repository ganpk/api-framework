<?php
namespace Apps\V1\apis;

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
    final public function instance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
}