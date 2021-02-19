# Validator, PHP Extension

一个我喜欢的简单的验证器.

## 快速使用.

```
use xuweiqiang\validator\Validator;

$rules = [
    'chnname'  => [
        'format' => 'string',
        'required' => true,
        'maxLength' => 10,
        'filter' => ['trim', 'filterSpace'],
        'default' => ''
    ],
    'birthday'   => [
        'format' => 'dateTime',
        'required' => false,
        'layout' => 'Y-m-d',
        'default' => null
    ],
    'contracte_mail' => [
        'format' => 'email',
        'minLength' => 3,
        'maxLength' => 100
    ],
    'phone_number' => [
        'format' => 'phone',
        'maxLength' => 15,
        'filter' => ['trim']
    ],
    'money' => [
        'format' => 'float',
        'max' => 5,
        'min' => 1,
        'layout' => '0.00'
    ],
    'gender' => [
        'format' => 'range',
        'required' => true,
        'range' => ['M', 'W'],
    ],
    'age' => [
        'format' => 'regex',
        'regex' => '/\d+/',
        'required' => false,
    ],
];
$msgs = array(
    'chnname'  => [
        'required' => '姓名不能为空.',
        'maxLength' => '姓名不得超过10个字符.',
    ],
    'birthday'    => [
        'format' => '出生日期格式错误',
    ],
    'contracte_mail'   => [
        'email' => '邮箱格式错误',
    ],
    'gender' => [
        'format' => '性别选项值格式错误',
    ],
    'age' => [
        'format' => '年龄格式错误',
    ],
);
$params = array(
    "chnname" => "jack",
    "birthday" => "2020-12-02 10:36:01",
    "contracte_mail" => "123456@qq.com",
    "phone_number" => "1882623366",
    "money" =>'120',
    'gender' => 'W',
    'age' => '18',
);

$Validator = new Validator();
$result = $Validator->CheckMap($params, $rules, $msgs);
print_r($result);
print_r($Validator->error);


```
## 验证规则参数说明

<table cellspacing=0 cellpadding=0 style="border-collapse:collapse;">
    <tr>
        <th>校验格式</th>
		<th>是否必填</th>
		<th>最大长度</th>
        <th>最小长度</th>
        <th>最大值</th>
        <th>最小值</th>
        <th>格式化函数</th>
        <th>特殊格式</th>
        <th>默认零值</th>
    </tr>
    <tr>
        <td>format</td>
		<td>required</td>
		<td>maxLength</td>
        <td>minLength</td>
        <td>max</td>
        <td>min</td>
        <td>filter</td>
        <td>layout</td>
        <td>default</td>
    </tr>
</table>

## 支持的验证格式

string | dateTime | email | phone | float | range | regex

## 默认错误提示

<table cellspacing=0 cellpadding=0 style="border-collapse:collapse;">
    <tr>
        <td>格式</td>
        <td>format</td>
		<td>required</td>
		<td>maxLength</td>
        <td>minLength</td>
    </tr>
    <tr>
        <td>string</td>
        <td>限定字符串格式</td>
		<td>字段值不能为空</td>
		<td>字段长度超出限制</td>
        <td>字段长度小于最小长度</td>
    </tr>
</table>

---

<table cellspacing=0 cellpadding=0 style="border-collapse:collapse;">
    <tr>
        <td>格式</td>
        <td>format</td>
		<td>required</td>
    </tr>
    <tr>
        <td>range</td>
        <td>字段值超出范围</td>
		<td>字段值不能为空</td>
    </tr>
</table>

---




## License

Validator is licensed under MIT.