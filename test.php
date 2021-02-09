<?php

include("./Validator.php");

$rules = [
    'chnname'  => ['format' => 'string', 'required' => true, 'maxLength' => 2, 'filter' => ['trim', 'filterSpace'], 'default' => ''],
    'birthday'   => ['format' => 'date', 'required' => false, 'layout' => 'Y-m-d', 'default' => null],
    'contracte_mail' => ['format' => 'email', 'minLength' => 3, 'maxLength' => 100], //'length' => 100, 
    'phone_number' => ['format' => 'string', 'maxLength' => 5, 'filter' => ['trim']],
    'money' => ['format' => 'int', 'max' => 5, 'min' => 1],
];

$msgs = array(
    'chnname'  => ['required' => '姓名不能为空.'],
    'chnname' => ['maxLength' => '姓名不得超过10个字符.'],
    'birthday'    => ['format' => '出生日期必须为日期.'],
    'contracte_mail'   => ['email' => '邮箱格式错误'],
);

$params = array(
    "chnname" => "jack",
    "birthday" => "2021-01-02",
    "contracte_mail" => "123456@qq.com",
    "phone_number" => "12345678910",
);

$Validator = new Validator();
$result = $Validator->CheckMap($params, $rules, $msgs);

print_r($result);
print_r($Validator->error);
