<?php

include("./src/Validator.php");

use xuweiqiang\validator\Validator;

$rules = [
    'chnname'  => ['format' => 'string', 'required' => true, 'maxLength' => 10, 'filter' => ['trim', 'filterSpace'], 'default' => ''],
    'birthday'   => ['format' => 'dateTime', 'required' => false, 'layout' => 'Y-m-d', 'default' => null],
    'contracte_mail' => ['format' => 'email', 'minLength' => 3, 'maxLength' => 100], //'length' => 100, 
    'phone_number' => ['format' => 'phone', 'maxLength' => 15, 'filter' => ['trim']],
    'money' => ['format' => 'float', 'max' => 5, 'min' => 1,'layout' => '0.00'],
    'gender' => ['format' => 'range', 'required' => true, 'range' => ['W', 'M'],],
    'age' => ['format' => 'regex', 'regex' => '/\d+/', 'required' => false, 'range' => ['Y', 'N'],],
];

$msgs = array(
    'chnname'  => ['required' => '姓名不能为空.'],
    'chnname' => ['maxLength' => '姓名不得超过10个字符.'],
    'birthday'    => ['format' => '出生日期格式错误'],
    'contracte_mail'   => ['email' => '邮箱格式错误'],
    'gender' => ['range' => '性别选项值格式错误'],
    'age' => ['format' => '格式错误正则校验不通过'],
);

$params = array(
    "chnname" => "jack",
    "birthday" => "2020-12-02 10:36:01",
    "contracte_mail" => "123456@qq.com",
    "phone_number" => "1882623366",
    "money" =>'120',
    'gender' => 'Z',
    'age' => '18',
);

$Validator = new Validator();
$result = $Validator->CheckMap($params, $rules, $msgs);

print_r($result);
print_r($Validator->error);
