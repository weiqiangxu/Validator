<?php

namespace xuweiqiang\validator;

/**
 * IntegerValidator
 * @author wytanxu@tencent.com
 */
class IntegerValidator implements BaseValidator
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
                    $error = '限定数字格式';
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
            if (!isset($this->params[$columnName]) || $this->params[$columnName] == '') {
                // 键不存在
                $this->setError($columnName, 'required');
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
        if (isset($this->params[$columnName])) {
            if (isset($this->rules[$columnName]['required']) && boolval($this->rules[$columnName]['required'])) {
                // 必填项
                if ($this->params[$columnName] != '') {
                    if (filter_var($this->params[$columnName], FILTER_VALIDATE_INT) === false) {
                        $this->setError($columnName, 'format');
                    }else{
                        $this->params[$columnName] = filter_var($this->params[$columnName], FILTER_VALIDATE_INT);
                    }
                }
            } else {
                // 非必填项
                if ($this->params[$columnName] != '') {
                    if (filter_var($this->params[$columnName], FILTER_VALIDATE_INT) === false) {
                        $this->setError($columnName, 'format');
                    } else {
                        $this->params[$columnName] = filter_var($this->params[$columnName], FILTER_VALIDATE_INT);
                    }
                }
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
    protected function max($columnName)
    {
        if ( isset($this->params[$columnName]) && !empty($this->rules[$columnName]['max']) ) {
            if(!(filter_var($this->params[$columnName], FILTER_VALIDATE_INT) === false)){
                if(filter_var($this->params[$columnName], FILTER_VALIDATE_INT) > $this->rules[$columnName]['max']){
                    $this->setError($columnName, 'max');
                }
            }
        }
        return;
    }

    /**
     * 数字最小值校验
     *
     * @param string $columnName
     * @return void
     */
    protected function min($columnName)
    {
        if ( isset($this->params[$columnName]) && !empty($this->rules[$columnName]['min']) ) {
            if(!(filter_var($this->params[$columnName], FILTER_VALIDATE_INT) === false)){
                if(filter_var($this->params[$columnName], FILTER_VALIDATE_INT) < $this->rules[$columnName]['min']){
                    $this->setError($columnName, 'min');
                }
            }
        }
        return;
    }

    /**
     * 设置默认零值
     * @return void
     */
    protected function setDefault($columnName)
    {
        if (isset($this->params[$columnName])) {
            if (in_array('default', array_keys($this->rules[$columnName]))) {
                if ( $this->params[$columnName] == '') {
                    $this->params[$columnName] = $this->rules[$columnName]['default'];
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
        // 3 默认零值
        $this->setDefault($columnName);
        // 4 最大数值校验
        $this->max($columnName);
        // 5 最小数值校验
        $this->min($columnName);
        return;
    }
}
