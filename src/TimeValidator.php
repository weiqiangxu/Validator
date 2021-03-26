<?php

namespace xuweiqiang\validator;

/**
 * TimeValidator
 * @author wytanxu@tencent.com
 */
class TimeValidator implements BaseValidator
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
                    $error = '必须为日期格式';
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
        if (isset($this->rules[$columnName]['required']) && boolval($this->rules[$columnName]['required'])) {
            if (!isset($this->params[$columnName]) || $this->params[$columnName] == '') {
                $this->setError($columnName, 'required');
            }
        }
        return;
    }



    /**
     * 格式校验
     * 
     * @return void
     */
    protected function format($columnName)
    {
        if (isset($this->params[$columnName])) {
            if (isset($this->rules[$columnName]['required']) && boolval($this->rules[$columnName]['required'])) {
                if ($this->params[$columnName] != '') {
                    if (!$this->checkIsTime($this->params[$columnName])) {
                        $this->setError($columnName, 'format');
                    }
                }
            } else {
                if ($this->params[$columnName] != '') {
                    if (!$this->checkIsTime($this->params[$columnName])) {
                        $this->setError($columnName, 'format');
                    }
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
            if ($this->params[$columnName] == '') {
                if (in_array('default', array_keys($this->rules[$columnName]))) {
                    $this->params[$columnName] = $this->rules[$columnName]['default'];
                }
            }
        }
        return;
    }


    /**
     * 日期最大值校验
     *
     * @param string $columnName
     * @return void
     */
    protected function max($columnName)
    {
        if (isset($this->params[$columnName]) && strtotime($this->params[$columnName])) {
            if (!empty($this->rules[$columnName]['max']) && strtotime($this->rules[$columnName]['max'])) {
                if (strtotime($this->params[$columnName]) > strtotime($this->rules[$columnName]['max'])) {
                    $this->setError($columnName, 'max');
                }
            }
        }
        return;
    }

    /**
     * 日期最小值校验
     *
     * @param string $columnName
     * @return void
     */
    protected function min($columnName)
    {
        if (isset($this->params[$columnName]) && strtotime($this->params[$columnName])) {
            if (!empty($this->rules[$columnName]['min']) && strtotime($this->rules[$columnName]['min'])) {
                if (strtotime($this->params[$columnName]) < strtotime($this->rules[$columnName]['min'])) {
                    $this->setError($columnName, 'min');
                }
            }
        }
        return;
    }

    /**
     * 校验是时间格式
     *
     * @param string $t | Ymd | Y-m-d H:i:s | Y-m-d
     * 
     * @return bool
     */
    function checkIsTime($t)
    {
        $dateTime = date_create($t);
        if (!$dateTime) {
            return false;
        }
        if ($t == date_format($dateTime, 'Y-m-d H:i:s') || $t == date_format($dateTime, 'Ymd') || $t == date_format($dateTime, 'Y-m-d')) {
            return true;
        }
        return true;
    }

    /**
     * 格式化日期
     *
     * @param string $columnName
     * @return void
     */
    protected function layout($columnName)
    {
        if (isset($this->params[$columnName]) && $this->checkIsTime($this->params[$columnName])){
            if(!empty($this->rules[$columnName]['layout'])){
                $this->params[$columnName] = date($this->rules[$columnName]['layout'], strtotime($this->params[$columnName]));
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
        // 1 零值校验
        $this->setDefault($columnName);
        // 2 必填校验
        $this->required($columnName);
        // 3 格式校验
        $this->format($columnName);
        // 4 最大数值校验
        $this->max($columnName);
        // 5 最小数值校验
        $this->min($columnName);
        // 6 格式化
        $this->layout($columnName);
        return;
    }
}
