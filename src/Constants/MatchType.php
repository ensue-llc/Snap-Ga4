<?php

namespace Ensue\GA4\Constants;

class MatchType
{
    /**
     * Unspecified
     *
     * Generated from protobuf enum <code>MATCH_TYPE_UNSPECIFIED = 0;</code>
     */
    public const MATCH_TYPE_UNSPECIFIED = 'MATCH_TYPE_UNSPECIFIED';
    /**
     * Exact match of the string value.
     *
     * Generated from protobuf enum <code>EXACT = 1;</code>
     */
    public const EXACT = 'EXACT';
    /**
     * Begins with the string value.
     *
     * Generated from protobuf enum <code>BEGINS_WITH = 2;</code>
     */
    public const BEGINS_WITH = 'BEGINS_WITH';
    /**
     * Ends with the string value.
     *
     * Generated from protobuf enum <code>ENDS_WITH = 3;</code>
     */
    public const ENDS_WITH = 'ENDS_WITH';
    /**
     * Contains the string value.
     *
     * Generated from protobuf enum <code>CONTAINS = 4;</code>
     */
    public const CONTAINS = 'CONTAINS';
    /**
     * Full regular expression match with the string value.
     *
     * Generated from protobuf enum <code>FULL_REGEXP = 5;</code>
     */
    public const FULL_REGEXP = 'FULL_REGEXP';
    /**
     * Partial regular expression match with the string value.
     *
     * Generated from protobuf enum <code>PARTIAL_REGEXP = 6;</code>
     */
    public const PARTIAL_REGEXP = 'PARTIAL_REGEXP';
}
