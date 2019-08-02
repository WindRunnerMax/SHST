<?php
namespace copy;

/**
 * 浅拷贝与深拷贝
 */
class Copy
{
    /**
     * 浅拷贝
     */
    public static function sCopy(...$arguements){
        $aLength = count($arguements);
        $options = $arguements[0];
        for ($i=1; $i < $aLength ; ++$i) { 
            foreach ($arguements[$i] as $key => $value) {
                $options[$key] = $value;
            }
        }
        return $options;
    }
}