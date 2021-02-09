<?php

/**
 * 验证器
 */
class Validator
{

    /* 类地图 */
	private static $classmap = null;

    /**
     * @var array
     */
    protected $_validators = [
        'string'       => StringValidator::class,
        'int'       => IntegerValidator::class,
    ];

    /**
     * 错误集合
     *
     * @var array
     */
    public $error = array();

    /**
     * 验证数组
     *
     * @var array
     */
    public $params = array();

    /**
     * 校验规则
     *
     * @var array
     */
    public $rules = array();

    /**
     * 验证提示语
     *
     * @var array
     */
    public $msgs = array();

    /**
     * construct func
     */
    public function __construct()
    {
        #类库映射
		self::$classmap = array (
            'BaseValidator' 	=> './BaseValidator.php',
			'StringValidator' 	=> './StringValidator.php',
			'IntegerValidator' 	=> './IntegerValidator.php',
		);
        spl_autoload_register(array(__CLASS__,'autoload'));
    }


    /**
     * 自动加载函数
     *
     * @param string $class
     * @return void
     */
    public static function autoload($class)
	{
		if (isset(self::$classmap[$class])) 
		{
			$classfile = self::$classmap [$class];
			require_once ($classfile);
			return;
		}
	}

    /**
     * 校验数组
     *
     * @param array $params
     * @param array $rules
     * @param array $msgs
     * @author xuweiqiang <wystanxu@tencent.com>
     * @return void
     */
    public function CheckMap($params = array(), $rules = array(), $msgs = array())
    {
        if (empty($rules)) {
            return $params;
        }
        $this->params = $params;
        $this->rules = $rules;
        $this->msgs = $msgs;
        foreach ($rules as $columnName => $validatorRuleMap) {
            # 逐行校验
            if (!isset($this->_validators[$validatorRuleMap['format']])) {
                continue;
                throw new Exception("验证类型 {$validatorRuleMap['format']} 不存在");
                break;
            }
            // 调用对应类型的验证器
            $validatorClass = $this->_validators[$validatorRuleMap['format']];
            $validator = new $validatorClass($this);
            $validator->validate($columnName);
            $this->error = array_merge($this->error, $validator->getError());
            if(!empty($this->params[$columnName])){
                $this->params[$columnName] = $validator->getParam($columnName);
            }
        }
        return $this->params;
    }
}
