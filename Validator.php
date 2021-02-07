<?php


/**
 * 验证器
 */
class Validator
{

    // 错误信息
    public $error = array();
    // 默认零值
    public $defaultZeroMap = array();
    // 必填校验
    public $columnMustRequire = array();
    // 最大长度校验
    public $columnMaxLengthLimit = array();
    // 最小长度校验
    public $columnMinLengthLimit = array();
    // 原始数据
    public $originParams = array();
    // 传入的错误提示
    public $selfErrorTips = array();
    // 格式化后的数据
    public $formatedParams = array();
    // 格式化函数
    public $formatedFuncMap = array();
    // 时间格式化
    public $timestampLayout = array();

    // 校验
    public function CheckMap($params = array(), $rules = array(), $msgs = array())
    {
        if(empty($params) || empty($rules) || empty($msg)){
            $this->error = array();
            return [];
        }
        $this->selfErrorTips = $rules;
        // 默认零值
        foreach ($rules as $columnName => $ruleDescription) {
            if(isset($ruleDescription["default"])){
                $this->defaultZeroMap[$columnName] = $ruleDescription["default"];
            }
        }
        // 必填校验
        foreach ($rules as $columnName => $ruleDescription) {
            if(isset($ruleDescription["required"])){
                $this->columnMustRequire[$columnName] = $ruleDescription["required"];
            }
        }

        // 格式化函数
        foreach ($rules as $columnName => $ruleDescription) {
            if(isset($ruleDescription["filter"])){
                $this->formatedFuncMap[$columnName] = $ruleDescription["filter"];
            }
        }
        // 时间格式
        foreach ($rules as $columnName => $ruleDescription) {
            if(isset($ruleDescription["layout"])){
                $this->formatedFuncMap[$columnName] = $ruleDescription["layout"];
            }
        }
        foreach ($rules as $columnName => $validatorRuleMap) {
            # 逐行校验
            $this->validItem($columnName,$validatorRuleMap);


            // foreach ($validatorRuleMap as $ruleTag => $ruleDescription) {
            //     $this->validItem($columnName,$ruleDescription,$params);
            //     switch ($ruleTag) {
            //         case 'format':
            //             # 格式校验
            //             $this->validItem($columnName,$ruleDescription,$params);
            //             break;
            //         case 'required':
            //             # 是否必填
            //             break;
            //         case 'maxLength':
            //             # 最大长度校验
            //             break;
            //         case 'minLength':
            //             # 最小长度校验
            //             break;
            //         case 'default':
            //             # 默认值|零值
            //             break;

            //         case 'layout':
            //             # 格式 | 浮点型的小数位、时间字符串的格式化
            //             break;
            //         default:
            //             # code...
            //             break;
            //     }
            // }
        }
        return $this->formatedParams;
    }

    // 格式校验
    public function validItem($columnName,$validatorRuleMap){
        if(!isset($this->originParams[$columnName])){
            return false;
        }
        $columnValue = $this->originParams[$columnName];
        if(empty($validatorRuleMap['format'])){
            return;
        }
        $formatDescription = $validatorRuleMap['format'];//格式
        switch ($formatDescription) {
            case 'string':
                if($this->columnMustRequire[$columnName] && empty($columnValue)){
                    // 必填校验
                    if(isset($this->selfErrorTips[$columnName]) && !empty($this->selfErrorTips[$columnName]['required'])){
                        $this->error[] = $this->selfErrorTips[$columnName]['required'];
                    }else{
                        $this->error[] = $columnName."不能为空";
                    }
                }
                if($this->columnMaxLengthLimit[$columnName] && empty($columnValue)){
                    // 最大长度校验
                    if(isset($this->selfErrorTips[$columnName]) && !empty($this->selfErrorTips[$columnName]['required'])){
                        $this->error[] = $this->selfErrorTips[$columnName]['required'];
                    }else{
                        $this->error[] = $columnName."不能为空";
                    }
                }


                $this->formatedParams[$columnName] = strval($columnValue);
                break;
            case 'int':
                if(!preg_match("/^\d*$/",$columnValue)){
                    if(isset($this->defaultZeroMap[$columnName])){
                        // 默认零值
                        $this->formatedParams[$columnName] = $this->defaultZeroMap[$columnName];
                    }else{
                        $this->formatedParams[$columnName] = 0;
                    }
                }else{
                    $this->formatedParams[$columnName] = intval($columnValue);
                }
                break;
            case 'date':
                if(!strtotime($columnValue)){
                    // 日期格式赋予零值
                    if(isset($this->defaultZeroMap[$columnName])){
                        // 默认零值
                        $this->formatedParams[$columnName] = $this->defaultZeroMap[$columnName];
                    }else{
                        // 默认值为null
                        $this->formatedParams[$columnName] = null;
                    }
                }else{
                    // 非空值格式化为需要的格式
                    if(!empty($this->timestampLayout[$columnName])){
                        $this->formatedParams[$columnName] = date($this->timestampLayout[$columnName],strtotime($columnValue));
                    }else{
                        $this->formatedParams[$columnName] = date("Y-m-d",strtotime($columnValue));
                    }
                }
                break;
            case 'datetime':
                if(!strtotime($columnValue)){
                    // 时间格式赋予零值
                    if(isset($this->defaultZeroMap[$columnName])){
                        // 默认零值
                        $this->formatedParams[$columnName] = $this->defaultZeroMap[$columnName];
                    }else{
                        // 默认值为null
                        $this->formatedParams[$columnName] = null;
                    }
                }else{
                    // 非空值格式化为需要的格式
                    if(!empty($this->timestampLayout[$columnName])){
                        $this->formatedParams[$columnName] = date($this->timestampLayout[$columnName],strtotime($columnValue));
                    }else{
                        $this->formatedParams[$columnName] = date("Y-m-d H:i:s",strtotime($columnValue));
                    }
                }
                break;
            case 'timestamp':
                if(!preg_match("/^\d*$/",$columnValue)){
                    if(isset($this->defaultZeroMap[$columnName])){
                        $this->formatedParams[$columnName] = $this->defaultZeroMap[$columnName];
                    }else{
                        // 默认值为null
                        $this->formatedParams[$columnName] = 0;
                    }
                }else{
                    $this->formatedParams[$columnName] = $columnValue;
                }
                break;
            case 'email':
                if(!filter_var($columnValue, FILTER_VALIDATE_EMAIL)){
                    if(isset($this->defaultZeroMap[$columnName])){
                        // 默认零值
                        $this->formatedParams[$columnName] = $this->defaultZeroMap[$columnName];
                    }else{
                        $this->formatedParams[$columnName] = '';
                    }
                }else{
                    $this->formatedParams[$columnName] = $columnValue;
                }
                break;
            case 'phone':
                if(!preg_match("/^(((13[0-9]{1})|(15[0-9]{1})|(16[0-9]{1})|(17[3-8]{1})|(18[0-9]{1})|(19[0-9]{1})|(14[5-7]{1}))+\d{8})$/", $columnValue)){
                    if(isset($this->defaultZeroMap[$columnName])){
                        // 默认零值
                        $this->formatedParams[$columnName] = $this->defaultZeroMap[$columnName];
                    }else{
                        $this->formatedParams[$columnName] = '';
                    }
                }else{
                    $this->formatedParams[$columnName] = $columnValue;
                }
                break;
            default:
                # code...
                break;
        }

    }

    public function getError()
    {
        return [
            '姓名不能为空'
        ];
    }

    public function trim($str)
    {
        return trim($str);
    }

    public function filterSpace($str)
    {
        return str_replace(' ', '', $str);
    }
}