<?php

namespace xuweiqiang\validator;

/**
 * 抽象接口 - 单一验证器
 */
interface BaseValidator {

    /**
     * 设置错误信息
     *
     * @param string $columnName
     * @param string $errorTag
     * @return void
     */
    public function setError($columnName, $errorTag);

    
    /**
     * 验证入口
     * 
     * @param string $columnName
     * @return void
     * 
     */
    public function validate($columnName);

    /**
     * 获取验证后的数据
     * 
     * @param string $columnName
     * @return void
     * 
     */
    public function getParam($columnName);


    /**
     * 获取错误信息
     * 
     * @return array
     * 
     */
    public function getError();
    
    
}