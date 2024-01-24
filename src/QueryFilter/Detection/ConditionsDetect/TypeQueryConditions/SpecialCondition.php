<?php

namespace eloquentFilter\QueryFilter\Detection\ConditionsDetect\TypeQueryConditions;

use eloquentFilter\QueryFilter\Detection\Contract\DefaultConditionsContract;

/**
 * Class SpecialCondition.
 */
class SpecialCondition implements DefaultConditionsContract
{
    const SPECIAL_PARAM_NAME = 'f_params';
    /**
     * @param $field
     * @param $params
     *
     * @return string|null
     */
    public static function detect($field, $params): ?string
    {
        //todo should be constant
        if ($field == self::SPECIAL_PARAM_NAME) {
            return 'Special';
        }

        return null;
    }
}
