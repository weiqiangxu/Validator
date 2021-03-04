<?php

namespace xuweiqiang\validator;

/**
 * RangeValidator
 * @author wytanxu@tencent.com
 */
class RangeValidator implements BaseValidator
{

    /**
     * 校验出的错误信息
     *
     * @var array
     */
    public $error = array();

    /**
     * 原始数组
     *
     * @var array
     */
    public $params;

    /**
     * 校验规则
     *
     * @var array
     */
    public $rules;

    /**
     * 提示信息
     *
     * @var array
     */
    public $msgs;


    public function __construct($validatorObj)
    {
        $this->params = $validatorObj->params;
        $this->rules = $validatorObj->rules;
        $this->msgs = $validatorObj->msgs;
    }

    /**
     * 设置错误信息
     *
     * @param string $columnName
     * @param string $errorTag
     * @return void
     */
    public function setError($columnName, $errorTag)
    {
        if (isset($this->msgs[$columnName]) && !empty($this->msgs[$columnName][$errorTag])) {
            $this->error[] = $this->msgs[$columnName][$errorTag];
        } else {
            $error = '';
            switch ($errorTag) {
                case 'required':
                    $error = '字段值不能为空';
                    break;
                case 'range':
                    $error = '字段值超出范围';
                    break;
                default:
                    break;
            }
            $this->error[] = $error;
        }
        return;
    }

    /**
     * 必需验证
     * @return void
     */
    protected function required($columnName)
    {
        if (
            isset($this->rules[$columnName]['required'])
            &&
            boolval($this->rules[$columnName]['required'])            
        ) {
            if(!isset($this->params[$columnName])){
                // 键值都不存在
                $this->setError($columnName, 'required');
            }else{
                if(empty($this->params[$columnName])){
                    $this->setError($columnName, 'required');
                }
            }
        }
        return;
    }



    /**
     * 格式校验
     * @return void
     */
    protected function format($columnName)
    {
        if (isset($this->params[$columnName]) ) {

            if (
                isset($this->rules[$columnName]['required'])
                &&
                boolval($this->rules[$columnName]['required'])            
            ) {
                // 必填项 - 空值等都要格式校验
                if(
                    !empty($this->rules[$columnName]['range'])
                    &&
                    is_array($this->rules[$columnName]['range'])
                ){
                    if(!in_array($this->params[$columnName],$this->rules[$columnName]['range'])){
                        $this->setError($columnName,"range");
                    }
                }
            }else{
                if($this->params[$columnName] != ''){
                    // 枚举值 - 对于空字符串 + 非必填 不抛出格式错误 
                    if(
                        !empty($this->rules[$columnName]['range'])
                        &&
                        is_array($this->rules[$columnName]['range'])
                    ){
                        if(!in_array($this->params[$columnName],$this->rules[$columnName]['range'])){
                            $this->setError($columnName,"range");
                        }
                    }
                }
            }
        }
        return;
    }


    /**
     * 获取验证后的数据
     *
     * @param string $columnName
     * @return void
     */
    public function getParam($columnName)
    {
        return $this->params[$columnName];
    }

    /**
     * 获取验证出的错误信息
     *
     * @return array
     */
    public function getError()
    {
        return $this->error;
    }


    /**
     * 校验
     *
     * @param string $columnName
     * @return void
     */
    public function validate($columnName)
    {
        // 1 必填校验
        $this->required($columnName);
        // 2 格式校验
        $this->format($columnName);

        return;
    }
}
