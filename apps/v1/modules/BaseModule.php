<?php
namespace Apps\V1\Modules;

/**
 * module�Ļ���
 * Class BaseModule
 */
class BaseModule
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