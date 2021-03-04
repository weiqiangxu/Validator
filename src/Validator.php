<?php

namespace xuweiqiang\validator;

/**
 * 验证器
 */
class Validator
{
    /**
     * @var array
     */
    protected $_validators = [
        'string' => StringValidator::class,
        'int' => IntegerValidator::class,
        'date' => DateValidator::class,
        'phone' => PhoneValidator::class,
        'dateTime' => DateTimeValidator::class,
        'range' => RangeValidator::class,
        'float' => FloatValidator::class,
        'regex' => RegexValidator::class,
        'email' => EmailValidator::class,
        'time' => TimeValidator::class,
    ];

    /**
     * construct func
     */
    public function __construct()
    {
        spl_autoload_register(array(__CLASS__, 'autoload'));
    }

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
     * 自动加载函数
     *
     * @param string $className
     * @return void
     */
    public static function autoload($className)
    {
        $className = basename(str_replace('\\', '/', $className));
        $classfile = __DIR__.'/'.basename($className).'.php';
        require_once($classfile);
        return;
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
        $ruleColumnList = array_keys($rules);
        foreach ($params as $columnName => $columnValue) {
            if(!in_array($columnName,$ruleColumnList)){
                unset($params[$columnName]);
            }
        }
        $this->rules = $rules;
        $this->msgs = $msgs;
        $this->params = $params;
        foreach ($rules as $columnName => $validatorRuleMap) {
            if(empty($validatorRuleMap['format'])){
                continue;
            }
            # 逐行校验
            if (!isset($this->_validators[$validatorRuleMap['format']])) {
                throw new \Exception("验证类型 {$validatorRuleMap['format']} 不存在");
                break;
            }
            // 调用对应类型的验证器
            $validatorClass = $this->_validators[$validatorRuleMap['format']];
            $validator = new $validatorClass($this);
            $validator->validate($columnName);
            $this->error = array_merge($this->error, $validator->getError());
            if(in_array($columnName,array_keys($this->params))){
                // 必须校验数据源存在该键 - 才会拼接格式化后的值
                $this->params[$columnName] = $validator->getParam($columnName);
            }
        }
        return $this->params;
    }
}
