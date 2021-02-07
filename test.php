<?php


/**
 * 验证器
 */
class Test
{

    public function CheckMap($params, $rules, $msgs)
    {
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

// "required|length:6,16",
$rules = [
    'chnname'  => ['format' => 'string', 'required' => true, 'maxLength' => 2, 'filter' => ['trim', 'filterSpace'], 'default' => ''],
    'birthday'   => ['format' => 'date', 'required' => false, 'layout' => 'Y-m-d', 'default' => null],
    'contracte_mail' => ['format' => 'email', 'minLength' => 3, 'maxLength' => 100], //'length' => 100, 
    'phone_number' => ['format' => 'string', 'maxLength' => 5, 'filter' => ['trim']],
];

$msgs = array(
    'chnname.required'  => '姓名不能为空.',
    'chnname.maxLength' => '姓名不得超过10个字符.',
    'birthday.format'    => '出生日期必须为日期.',
    'contracte_mail.email'   => '邮箱格式错误',
);

$params = array(
    "chnname" => "jack",
    "birthday" => "2021-01-02",
    "contracte_mail" => "123456@qq.com",
    "phone_number" => "12345678910",
);

$Test = new Test();
$result = $Test->CheckMap($params, $rules, $msgs);
if ($Test->getError()) {
    return $Test->getError();
}

// result为后面




// params := map[string]interface{} {
//     "passport"  : "",
//     "password"  : "123456",
//     "password2" : "1234567",
// }
// rules := map[string]string {
//     "passport"  : "required|length:6,16",
//     "password"  : "required|length:6,16|same:password2",
//     "password2" : "required|length:6,16",
// }
// msgs  := map[string]interface{} {
//     "passport" : "账号不能为空|账号长度应当在:min到:max之间",
//     "password" : map[string]string {
//         "required" : "密码不能为空",
//         "same"     : "两次密码输入不相等",
//     },
// }
// if e := gvalid.CheckMap(params, rules, msgs); e != nil {
//     fmt.Println(e.String())
// }    