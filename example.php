<?php

include("./src/Validator.php");

use xuweiqiang\validator\Validator;

$rules = [
    'chnname'  => ['format' => 'string', 'required' => true, 'maxLength' => 10, 'filter' => ['trim', 'filterSpace'], 'default' => ''],
    'birthday'   => ['format' => 'dateTime', 'required' => false, 'layout' => 'Y-m-d', 'default' => null],
    'contracte_mail' => ['format' => 'email', 'required' => true],
    'phone_number' => ['format' => 'phone', 'required' => true],
    'money' => ['format' => 'float', 'max' => 5, 'min' => 1, 'layout' => '%.2f'],
    'gender' => ['format' => 'range', 'required' => true, 'range' => ['W', 'M'],],
    'age' => ['format' => 'regex', 'regex' => '/\d+/', 'required' => false],
    'start_work'   => ['format' => 'time', 'required' => false, 'layout' => 'Y-m-d', 'default' => null],
    'graduate_date'   => ['format' => 'time', 'required' => true, 'layout' => 'Y-m-d', 'default' => null],
    'learn_time'   => ['format' => 'time', 'required' => true, 'layout' => 'Y-m-d H:i:s', 'default' => null],
    'eat_time'   => ['format' => 'time', 'required' => true, 'layout' => 'Y-m-d H:i:s', 'default' => null],
];

$msgs = array(
    'chnname'  => ['required' => '姓名不能为空.'],
    'chnname' => ['maxLength' => '姓名不得超过10个字符.'],
    'birthday'    => ['format' => '出生日期格式错误'],
    'contracte_mail'   => ['email' => '邮箱格式错误'],
    'gender' => ['range' => '性别选项值格式错误'],
    'age' => ['format' => '格式错误正则校验不通过'],
    'start_work'   => ['format' => '开始工作时间格式错误'],
    'graduate_date'   => ['format' => '毕业时间格式错误', 'required' => '毕业时间未填写'],
    'learn_time'   => ['format' => '教学时间格式错误', 'required' => '教学时间未填写'],
    'eat_time'   => ['format' => '开饭时间格式错误','required'=>'开饭时间未填写'],
);

$params = array(
    "chnname" => "jack", //必填项字符串|自动格式化|默认零值|最大长度
    "birthday" => "2020-12-02 10:36:01", //非必填项日期时间格式自动格式化
    "contracte_mail" => "123456@qq.com", //必填项邮件格式验证
    "phone_number" => "15512312312", //必填项手机号码验证
    "money" => '3', //浮点型校验格式和自动格式化和校验最大最小值
    'gender' => 'Z', //必填项枚举值
    'age' => '18', //自定义正则校验
    'start_work' => '', //非必填项空值自动转默认零值
    'graduate_date' => null, //必填项不能为null和空字符串
    'learn_time' => '2020-09-08', //必填项自动格式化
);

$Validator = new Validator();
$result = $Validator->CheckMap($params, $rules, $msgs);

print_r($result);
print_r($Validator->error);
