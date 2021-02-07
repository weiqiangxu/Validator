<?php


/**
 * 验证器
 */
class Validator
{

    public $error = array();

    public $defaultErrorTips = array(
        'required'  => '不能为空',
        'maxLength' => "长度超出限制",
        'birthday'    => '必须为日期格式',
        'email'   => '邮箱格式错误',
    );

    public $formatedParams = array();

    public function CheckMap($params = array(), $rules = array(), $msgs = array())
    {

        if(empty($params) || empty($rules) || empty($msg)){
            $this->error = array();
            return [];
        }

        

        foreach ($rules as $columnName => $validatorRuleMap) {
            # 逐行校验
            foreach ($validatorRuleMap as $ruleTag => $ruleDescription) {
                switch ($ruleTag) {
                    case 'format':
                        # 格式校验
                        $this->checkFormat($columnName,$ruleDescription,$params)
                        break;
                    case 'required':
                        # 是否必填
                        break;
                    case 'maxLength':
                        # 最大长度校验
                        break;
                    case 'minLength':
                        # 最小长度校验
                        break;
                    case 'default':
                        # 默认值|零值
                        break;

                    case 'layout':
                        # 格式 | 浮点型的小数位、时间字符串的格式化
                        break;
                    default:
                        # code...
                        break;
                }
            }

        }
        return $this->formatedParams;
    }

    // 格式校验
    public function checkFormat($columnName,$ruleDescription,$params){
        if(empty($params[$columnName])){
            return false;
        }
        switch ($ruleDescription) {
            case 'string':
                $this->formatedParams[$columnName] = strval($params[$columnName]);
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