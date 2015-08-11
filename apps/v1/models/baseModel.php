<?php
namespace Apps\V1\Models;

/**
 * model ����
 * Class baseModel
 * @package Apps\V1\Models
 */
class baseModel
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