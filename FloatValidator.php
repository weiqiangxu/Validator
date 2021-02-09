<?php

/**
 * FloatValidator
 * @author wytanxu@tencent.com
 */
class FloatValidator implements BaseValidator
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
                case 'max':
                    $error = '数字大小超出限制';
                    break;
                case 'min':
                    $error = '数字不得超出最小限制';
                    break;
                case 'format':
                    $error = '格式不是小数点格式';
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
            if(!preg_match('/^[-]{0,1}[0-9]+[.][0-9]+$|^[-]{0,1}[0-9]$/i', $this->params[$columnName])){
                $this->setError($columnName,'format');
            }
        }
        return;
    }

    /**
     * 数字最大值校验
     *
     * @param string $columnName
     * @return void
     */
    protected function max($columnName){
        if(isset($this->params[$columnName])
            && 
            (
                !empty($this->rules[$columnName]['max'])
                && 
                intval($this->params[$columnName]) > $this->rules[$columnName]['max']
            )
        ){
            $this->setError($columnName,'max');
        }
        return;
    }

    /**
     * 数字最小值校验
     *
     * @param string $columnName
     * @return void
     */
    protected function min($columnName){
        if(isset($this->params[$columnName])){
            if( 
                isset($this->rules[$columnName]['min']) 
                && 
                intval($this->params[$columnName]) < intval($this->rules[$columnName]['min'])
            ){
                $this->setError($columnName,'min');
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
        // 3 最大数值校验
        $this->max($columnName);
        // 4 最小数值校验
        $this->min($columnName);
        return;
    }
}
