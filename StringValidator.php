<?php

/**
 * StringValidator类
 * @author wytanxu@tencent.com
 */
class StringValidator
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
                case 'require':
                    $error = '字段值不能为空';
                    break;
                case 'maxLength':
                    $error = '字段长度超出限制';
                    break;
                case 'minLength':
                    $error = '字段长度小于最小长度';
                    break;
                case 'string':
                    $error = '限定字符串格式';
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
        if(
            isset($this->rules[$columnName]['required'])
            && 
            boolval($this->rules[$columnName]['required'])
            && 
            !isset($this->params[$columnName])  
        ){
            $this->validatorObj->setError($columnName,'required');
        }
        return;
    }



    /**
     * 格式校验
     * @return void
     */
    protected function string($columnName)
    {
        if(isset($params[$columnName]) && (is_array($params[$columnName]) || is_object($params[$columnName]))){
            $this->setError($columnName,'string');
        }
        return;
    }

    /**
     * 最大长度校验
     *
     * @param string $columnName
     * @return void
     */
    protected function maxLength($columnName){
        if(isset($this->params[$columnName]) 
            && 
            (
                !empty($this->rules[$columnName]['maxLength'])
                && 
                strlen($this->params[$columnName]) > $this->rules[$columnName]['maxLength']
            )
        ){
            $this->setError($columnName,'maxLength');
        }
        return;
    }

    /**
     * 最小长度校验
     *
     * @param string $columnName
     * @return void
     */
    protected function minLength($columnName){
        if(isset($this->params[$columnName])){
            if( 
                isset($this->rules[$columnName]['minLength']) 
                && 
                strlen($this->params[$columnName]) > $this->rules[$columnName]['minLength']
            ){
                $this->setError($columnName,'minLength');
            }            
        }
        return;
    }

    /**
     * 过滤函数处理
     *
     * @param string $columnName
     * @return void
     */
    protected function filter($columnName){
        if(empty($this->rules[$columnName]["filter"])){
            return;
        }
        if(!is_array($this->rules[$columnName]["filter"])){
            return;
        }
        if(!empty($this->error)){
            return;
        }
        foreach ($this->rules[$columnName]["filter"] as $func) {
            switch ($func) {
                case 'trim':
                    $this->params[$columnName] = trim($this->params[$columnName]);
                    break;
                case 'filterSpace':
                    $this->params[$columnName] = str_replace(' ','',$this->params[$columnName]);
                    break;
                default:
                    # code...
                    break;
            }
        }
        return;
    }

    /**
     * 校验
     *
     * @param string $columnName
     * @return void
     */
    public function validate($columnName){
        // 1 必填校验
        $this->required($columnName);
        // 2 格式校验
        $this->string($columnName);
        // 3 最大长度校验
        $this->maxLength($columnName);
        // 4 最小长度校验
        $this->minLength($columnName);
        // 5 格式化函数处理
        $this->filter($columnName);
        
        return;
    }
}
