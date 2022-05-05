<?php

namespace Yng\Database\Query;

/**
 * 链式类，表达式
 * @class   Expression
 * @author  Yng
 * @date    2022/04/23
 * @time    17:33
 * @package Yng\Database\Query
 */
class Expression
{
    /**
     * @var string
     */
    protected string $expression;

    /**
     * @param string $expression
     */
    public function __construct(string $expression)
    {
        $this->expression = $expression;
    }

    /**
     * @return string
     */
    public function getExpression()
    {
        return $this->expression;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->expression;
    }
}
