<?php

namespace Ensue\GA4\Constants;

class FilterExpression
{
    public const STRING_FILTER = 'string_filter';

    public const IN_LIST_FILTER = 'in_list_filter';

    public const NUMERIC_FILTER = 'numeric_filter';

    public const BETWEEN_FILTER = 'between_filter';

    /**
     * @return string[]
     */
    public static function options(): array
    {
        return [
            self::STRING_FILTER,
            self::IN_LIST_FILTER,
            self::NUMERIC_FILTER,
            self::BETWEEN_FILTER
        ];
    }
}
