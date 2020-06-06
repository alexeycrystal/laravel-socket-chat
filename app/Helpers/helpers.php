<?php


if (!function_exists('getExcludedArrayByKeys'))
{
    function getExcludedArrayByKeys(array $main, array $skipByKeysFromThisArray): array
    {
        $result = [];

        foreach($main as $key => $value) {

            if(!isset($skipByKeysFromThisArray[$key]))
                $result[$key] = $value;
        }

        return $result;
    }
}
