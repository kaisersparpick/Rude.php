<?php
declare(strict_types = 1);

namespace Kaiser\Rude;

class Rude
{
    private $rules;
    private $path;
    private $useFullNames;

    /**
     * Rude constructor.
     */
    public function __construct(bool $useFullNames=true)
    {
        $this->rules = [];
        $this->path = [];
        $this->useFullNames = $useFullNames;
    }

    /**
     * Add rule. The key for the rule in the collection is the stringified version of the callable.
     * @param Rule $rule
     */
    public function addRule(Rule $rule): void
    {
        $c = $this->callableToString($rule->condition);
        $this->rules[$c] = $rule;
    }

    /**
     * Check conditions from entry point.
     * @param callable $condition
     */
    public function check(callable $condition): void
    {
        $this->path = [];

        while (true) {
            if ($condition === null) break;

            $conditionStr = $this->callableToString($condition);
            $rule = $this->rules[$conditionStr];
            $result = call_user_func($rule->condition);

            $this->path[] = [$conditionStr, $result];

            if ($result === true)
                $condition = $rule->yes;
            else if ($result === false)
                $condition = $rule->no;
            else
                break;
        }
    }

    /**
     * Return a string representation of the path taken.
     * @return string
     */
    public function getPath(): string
    {
        $str = '';
        foreach ($this->path as $p) {
            if ($p[1] === false) $str .= '!';
            $str .= $p[0];
            if ($p[1] !== null) $str .= ' > ';
        }
        return $str;
    }

    /**
     * Return a string representation of the callable.
     * @param callable $callable
     * @return string
     */
    private function callableToString(callable $callable): string
    {
        if (is_array($callable)) {
            $c = ($this->useFullNames)
                ? get_class($callable[0]) . 'Inst->' . (string)$callable[1]
                : (string)$callable[1];
        }
        else $c = (string) $callable;

        return $c;
    }
}

class Rule
{
    public $condition;
    public $yes;
    public $no;

    /**
     * Rule constructor.
     * @param callable $condition
     * @param ?callable $yes
     * @param ?callable $no
     */
    public function __construct(callable $condition, ?callable $yes, ?callable $no)
    {
        $this->condition = $condition;
        $this->yes = $yes;
        $this->no = $no;
    }
}
