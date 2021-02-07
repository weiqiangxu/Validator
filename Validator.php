<?php


/**
 * 验证器
 */
class Validator
{

    // 错误信息
    public $error = array();
    // 默认零值
    public $selfZeroMap = array();
    // 默认零值
    public $systemZeroMap = array();
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
    // 默认的错误提示
    public $defaultErrorTips = array();
    // 格式化后的数据
    public $formatedParams = array();
    // 格式化函数
    public $formatedFuncMap = array();
    // 时间格式化
    public $timestampLayout = array();

    // 最大值校验
    public $columnMaxValueLimit = array();
    // 最小值校验
    public $columnMinValueLimit = array();

    // 校验
    public function CheckMap($params = array(), $rules = array(), $msgs = array())
    {
        if(empty($params) || empty($rules) || empty($msgs)){
            $this->error = array();
            return [];
        }
        $this->selfErrorTips = $rules;
        $this->originParams = $params;

        // 默认零值
        foreach ($rules as $columnName => $ruleDescription) {
            if(isset($ruleDescription["default"])){
                $this->selfZeroMap[$columnName] = $ruleDescription["default"];
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

            // $this->validItem($columnName,$validatorRuleMap);


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

    // 校验字符串
    public function validString($columnName){
        // 1 必填校验
        // 2 格式校验
        // 3 最大长度校验
        // 4 最小长度校验
        // 5 格式化函数处理
        // 6 默认空值处理
        if($this->columnMustRequire[$columnName] && !isset($this->originParams[$columnName])){
            $this->setItemErrorMsg($columnName,'required');
        }
        if(isset($this->originParams[$columnName]) 
            && (is_array($this->originParams[$columnName]) || is_object($this->originParams[$columnName]))
        ){
            $this->setItemErrorMsg($columnName,'format');
        }
        if(isset($this->originParams[$columnName]) 
            && ($this->columnMaxLengthLimit[$columnName] 
            && strlen($this->originParams[$columnName])>$this->columnMaxLengthLimit[$columnName])
        ){
            $this->setItemErrorMsg($columnName,'maxLength');
        }
        if(isset($this->originParams[$columnName]) && ($this->columnMinLengthLimit[$columnName] && strlen($this->originParams[$columnName])>$this->columnMinLengthLimit[$columnName])){
            $this->setItemErrorMsg($columnName,'minLength');
        }
        if(isset($this->originParams[$columnName]) && $this->originParams[$columnName] == ""){
            $this->setItemZero($columnName);
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
        if(isset($this->selfErrorTips[$columnName]) && !empty($this->selfErrorTips[$columnName][$errorTag])){
            $this->error[] = $this->selfErrorTips[$columnName][$errorTag];
        }else{
            $this->error[] = $this->defaultErrorTips[$columnName][$errorTag];
        }
        return true;
    }


    // 设置零值
    public function setItemZero($columnName){
        if(isset($this->selfZeroMap[$columnName]) && !empty($this->selfZeroMap[$columnName])){
            $this->formatedParams[$columnName] = $this->selfZeroMap[$columnName];
        }else{
            $this->formatedParams[$columnName] = $this->systemZeroMap[$columnName];
        }
        return true;
    }

    // 格式校验
    public function validItem($columnName,$validatorRuleMap){
        if(empty($validatorRuleMap['format'])){
            return;
        }
        $formatDescription = $validatorRuleMap['format'];//格式
        switch ($formatDescription) {
            case 'string':
                if($this->columnMustRequire[$columnName] && !isset($this->originParams[$columnName])){
                    // 必填校验
                    if(isset($this->selfErrorTips[$columnName]) && !empty($this->selfErrorTips[$columnName]['required'])){
                        $this->error[] = $this->selfErrorTips[$columnName]['required'];
                    }else{
                        $this->error[] = $columnName."不能为空";
                    }
                }
                if($this->columnMaxLengthLimit[$columnName] && strlen($this->originParams[$columnName])>$this->columnMaxLengthLimit[$columnName]){
                    // 最大长度校验
                    if(isset($this->selfErrorTips[$columnName]) && !empty($this->selfErrorTips[$columnName]['maxLength'])){
                        $this->error[] = $this->selfErrorTips[$columnName]['maxLength'];
                    }else{
                        $this->error[] = $columnName."长度超出限制";
                    }
                }
                if($this->columnMinLengthLimit[$columnName] && strlen($this->originParams[$columnName])<$this->columnMinLengthLimit[$columnName]){
                    // 最大长度校验
                    if(isset($this->selfErrorTips[$columnName]) && !empty($this->selfErrorTips[$columnName]['minLength'])){
                        $this->error[] = $this->selfErrorTips[$columnName]['minLength'];
                    }else{
                        $this->error[] = $columnName."长度小于".$this->columnMinLengthLimit[$columnName];
                    }
                }
                $this->formatedParams[$columnName] = strval($this->originParams[$columnName]);
                break;
            case 'int':
                if($this->columnMustRequire[$columnName] && !isset($this->originParams[$columnName]) ){
                    // 必填校验
                    if(isset($this->selfErrorTips[$columnName]) && !empty($this->selfErrorTips[$columnName]['required'])){
                        $this->error[] = $this->selfErrorTips[$columnName]['required'];
                    }else{
                        $this->error[] = $columnName."不能为空";
                    }
                }
                if(isset($this->originParams[$columnName])){
                    if(!is_string($this->originParams[$columnName]) && !is_int($this->originParams[$columnName])){
                        if(isset($this->selfErrorTips[$columnName]) && !empty($this->selfErrorTips[$columnName]['int'])){
                            $this->error[] = $this->selfErrorTips[$columnName]['int'];
                        }else{
                            $this->error[] = $columnName."必须数字格式";
                        }
                    }
                    if($this->columnMaxValueLimit[$columnName] && intval($this->originParams[$columnName])>$this->columnMaxValueLimit[$columnName]){
                        // 最大长度校验
                        if(isset($this->selfErrorTips[$columnName]) && !empty($this->selfErrorTips[$columnName]['max'])){
                            $this->error[] = $this->selfErrorTips[$columnName]['max'];
                        }else{
                            $this->error[] = $columnName."大小超出限制";
                        }
                    }
                    if($this->columnMinValueLimit[$columnName] && intval($this->originParams[$columnName])>$this->columnMinValueLimit[$columnName]){
                        // 最小长度校验
                        if(isset($this->selfErrorTips[$columnName]) && !empty($this->selfErrorTips[$columnName]['min'])){
                            $this->error[] = $this->selfErrorTips[$columnName]['min'];
                        }else{
                            $this->error[] = $columnName."不得小于".$this->columnMinValueLimit[$columnName];
                        }
                    }
                    if(!preg_match("/^\-{0,1}\d*$/",$this->originParams[$columnName])){
                        if(isset($this->selfErrorTips[$columnName]) && !empty($this->selfErrorTips[$columnName]['int'])){
                            $this->error[] = $this->selfErrorTips[$columnName]['int'].'preg';
                        }else{
                            $this->error[] = $columnName."必须数字格式".'preg';
                        }
                    }
                    if(isset($this->defaultZeroMap[$columnName])){
                        // 默认零值
                        $this->formatedParams[$columnName] = $this->defaultZeroMap[$columnName];
                    }else{
                        $this->formatedParams[$columnName] = 0;
                    }
                }else{
                    $this->formatedParams[$columnName] = intval($this->originParams[$columnName]);
                }
                break;
            case 'date':
                if(!strtotime($this->originParams[$columnName])){
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
                        $this->formatedParams[$columnName] = date($this->timestampLayout[$columnName],strtotime($this->originParams[$columnName]));
                    }else{
                        $this->formatedParams[$columnName] = date("Y-m-d",strtotime($this->originParams[$columnName]));
                    }
                }
                break;
            case 'datetime':
                if(!strtotime($this->originParams[$columnName])){
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
                        $this->formatedParams[$columnName] = date($this->timestampLayout[$columnName],strtotime($this->originParams[$columnName]));
                    }else{
                        $this->formatedParams[$columnName] = date("Y-m-d H:i:s",strtotime($this->originParams[$columnName]));
                    }
                }
                break;
            case 'timestamp':
                if(!preg_match("/^\d*$/",$this->originParams[$columnName])){
                    if(isset($this->defaultZeroMap[$columnName])){
                        $this->formatedParams[$columnName] = $this->defaultZeroMap[$columnName];
                    }else{
                        // 默认值为null
                        $this->formatedParams[$columnName] = 0;
                    }
                }else{
                    $this->formatedParams[$columnName] = $this->originParams[$columnName];
                }
                break;
            case 'email':
                if(!filter_var($this->originParams[$columnName], FILTER_VALIDATE_EMAIL)){
                    if(isset($this->defaultZeroMap[$columnName])){
                        // 默认零值
                        $this->formatedParams[$columnName] = $this->defaultZeroMap[$columnName];
                    }else{
                        $this->formatedParams[$columnName] = '';
                    }
                }else{
                    $this->formatedParams[$columnName] = $this->originParams[$columnName];
                }
                break;
            case 'phone':
                if(!preg_match("/^(((13[0-9]{1})|(15[0-9]{1})|(16[0-9]{1})|(17[3-8]{1})|(18[0-9]{1})|(19[0-9]{1})|(14[5-7]{1}))+\d{8})$/", $this->originParams[$columnName])){
                    if(isset($this->defaultZeroMap[$columnName])){
                        // 默认零值
                        $this->formatedParams[$columnName] = $this->defaultZeroMap[$columnName];
                    }else{
                        $this->formatedParams[$columnName] = '';
                    }
                }else{
                    $this->formatedParams[$columnName] = $this->originParams[$columnName];
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