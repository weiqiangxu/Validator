<?php

/**
 * RegexValidator类
 * @author wytanxu@tencent.com
 */
class RegexValidator implements BaseValidator
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
                case 'format':
                    $error = '正则校验不通过';
                    break;
                case 'regex':
                    $error = '正则表达式缺失';
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
            $this->setError($columnName,'required');
        }
        return;
    }



    /**
     * 格式校验
     * @return void
     */
    protected function format($columnName)
    {
        if(isset($this->params[$columnName])){
            if(empty($this->rules[$columnName]['regex'])){
                $this->setError($columnName,'regex');
            }
            if(
                !empty($this->rules[$columnName]['regex'])
                &&
                !preg_match($this->rules[$columnName]['regex'],$this->params[$columnName])
            ){
                $this->setError($columnName,'format');
            }
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
     * 获取验证后的数据
     *
     * @param string $columnName
     * @return void
     */
    public function getParam($columnName){
        return $this->params[$columnName];
    }

    /**
     * 获取验证出的错误信息
     *
     * @return array
     */
    public function getError(){
        return $this->error;
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
        $this->format($columnName);
        // 3 最大长度校验
        $this->maxLength($columnName);
        // 4 最小长度校验
        $this->minLength($columnName);
        // 5 格式化函数处理
        $this->filter($columnName);
        
        return;
    }
}
