<?php
namespace copy;

/**
 * 浅拷贝与深拷贝
 */
class Copy
{
    /**
     * 浅拷贝
     * 数组是通过引用传递的，除非它在你调用的方法/函数中被修改。
     * 如果尝试在方法/函数中修改数组，则首先创建它的副本，然后仅修改副本。
     */
    public static function sCopy(&...$arguements){
        $aLength = count($arguements);
        $options = &$arguements[0];
        for ($i = 1; $i < $aLength ; ++$i) { 
            foreach ($arguements[$i] as $key => $value) {
                $options[$key] = $value;
            }
        }
        return $options;
    }

    /**
     * 深拷贝
     */
    public static function dCopy(...$arguements){
        $options = [];
        foreach ($arguements as $arguement) {
            foreach ($arguement as $key => $value) {
                if(gettype($value) === 'array') $options[$key] = self::dCopy($value);
                else $options[$key] = $value;
            }
        }
        return $options;
    }

}