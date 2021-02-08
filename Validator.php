<?php


/**
 * 验证器
 */
class Validator
{

    // 默认的错误提示
    public $defaultErrorTips = array(
        "format" => '格式错误',
        "required" => '字段不存在',
        "maxLength" => '超出长度限制',
        "minLength" => '长度低于限制',
    );
    // 系统预设零值
    public $systemZeroMap = array(
        "string" => '',
        "date" => null,
        "email" => '',
        "int" => 0,
    );

    // 用户设置的错误提示信息
    public $settionRulesMsg = array();
    // 未格式化的数据
    public $originParams = array();
    // 格式化后的数据
    public $formatedParams = array();
    // 校验到的错误信息
    public $error = array();

    // 设置规则
    public $settionRulesMap = array(
        "required" => array(),
        "maxLength" => array(),
        "filter" => array(),
        "default" => array(),
        "layout" => array(),
        "default" => array(),
        "max" => array(),
        "min" => array(),
    );

    // 校验
    public function CheckMap($params = array(), $rules = array(), $msgs = array())
    {
        if(empty($params) || empty($rules) || empty($msgs)){
            $this->error = array();
            return [];
        }
        $this->originParams = $params;
        // 默认零值
        foreach ($rules as $columnName => $validatorRuleMap) {
            foreach ($validatorRuleMap as $ruleTag => $ruleValue) {
                $this->settionRulesMap[$ruleTag][$columnName] = $ruleValue;
            }
        }
        foreach ($rules as $columnName => $validatorRuleMap) {
            # 逐行校验
            switch ($validatorRuleMap['format']) {
                case 'string':
                    # 字符串校验
                    $this->validString($columnName);
                    break;
                case 'date':
                    # 日期校验
                    $this->validDate($columnName);
                    break;
                default:
                    # code...
                    break;
            }
        }
        return $this->formatedParams;
    }

    // 校验字符串
    public function validString($columnName){
        // 1 必填校验
        // 2 格式校验
        // 3 最大长度校验
        // 4 最小长度校验
        // 5 格式化函数处理
        // 6 默认空值处理
        if(
            isset($this->settionRulesMap['required'][$columnName]) 
            && 
            $this->settionRulesMap['required'][$columnName] 
            && 
            !isset($this->originParams[$columnName])
        ){
            $this->setItemErrorMsg($columnName,'required');
        }
        if(isset($this->originParams[$columnName]) 
            && (is_array($this->originParams[$columnName]) || is_object($this->originParams[$columnName]))
        ){
            $this->setItemErrorMsg($columnName,'format');
        }
        if(isset($this->originParams[$columnName]) 
            && ($this->settionRulesMap['maxLength'][$columnName] 
            && strlen($this->originParams[$columnName])>$this->settionRulesMap['maxLength'][$columnName])
        ){
            $this->setItemErrorMsg($columnName,'maxLength');
        }
        if(isset($this->originParams[$columnName])){
            if( 
                isset($this->settionRulesMap['minLength'][$columnName]) 
                && 
                strlen($this->originParams[$columnName]) > $this->settionRulesMap['minLength'][$columnName]
            ){
                $this->setItemErrorMsg($columnName,'minLength');
            }            
        }
        if(isset($this->originParams[$columnName]) && $this->originParams[$columnName] == ""){
            $this->setItemZero($columnName,'string');
        }
        $this->formatedParams[$columnName] = strval($this->originParams[$columnName]);
        return;
    }

    // 校验日期格式
    public function validDate($columnName){
        // 1 必填校验
        // 2 格式校验
        // 3 最小日期校验
        // 4 最大日期校验
        // 5 格式化-默认空值处理
    }

    // 邮件
    public function validEmail($columnName){
        // 1 必填校验
        // 2 格式校验
        // 5 格式化-默认空值处理
    }

    // 手机号码校验
    public function validPhone($columnName){
        // 1 必填校验
        // 2 格式校验
        // 5 格式化-默认空值处理
    }


    // 设置错误信息
    public function setItemErrorMsg($columnName,$errorTag){
        if(isset($this->settionRulesMsg[$columnName]) && !empty($this->settionRulesMsg[$columnName][$errorTag])){
            $this->error[] = $this->settionRulesMsg[$columnName][$errorTag];
        }else{
            if(isset($this->defaultErrorTips[$columnName]) && !empty($this->defaultErrorTips[$columnName][$errorTag])){
                $this->error[] = $this->defaultErrorTips[$columnName][$errorTag];
            }else{
                $this->error[] = $columnName.'-'.$errorTag;
            }
        }
        return true;
    }


    // 设置零值



    public function setItemZero($columnName,$format){
        if(isset($this->settionRulesMap['default'][$columnName]) && !empty($this->settionRulesMap['default'][$columnName])){
            $this->formatedParams[$columnName] = $this->settionRulesMap['default'][$columnName];
        }else{
            $this->formatedParams[$columnName] = $this->systemZeroMap[$format];
        }
        return true;
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