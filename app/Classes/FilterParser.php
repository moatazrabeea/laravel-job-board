<?php

namespace App\Classes;

class FilterParser
{
    protected $tokens;
    protected $current = 0;
    protected $filter;

    public function __construct(string $filter)
    {
        $this->filter = $filter;
        $this->tokens = $this->tokenize($filter);
    }

    /**
     * Tokenizes the filter string into an array of tokens.
     *
     * @param string $filter
     * @return array
     */
    protected function tokenize(string $filter): array
    {
        // This regex captures parentheses, commas, operators, attribute tokens, words, and quoted strings.
        $pattern = '/\s*(\(|\)|,|AND|OR|!=|>=|<=|=|>|<|LIKE|HAS_ANY|IS_ANY|EXISTS|attribute:[a-zA-Z0-9_]+|[a-zA-Z0-9_.-]+|"(?:\\\\"|[^"])*"|\'(?:\\\\\'|[^\'])*\')\s*/i';
        preg_match_all($pattern, $filter, $matches);
        return $matches[1];
    }

    /**
     * Parses the filter string and returns the AST.
     *
     * @return array
     * @throws \Exception
     */
    public function parse(): array
    {
        $ast = $this->parseExpression();

        if ($this->current < count($this->tokens)) {
            throw new \Exception("Unexpected token: " . $this->tokens[$this->current]);
        }

        return $ast;
    }

    /**
     * Parses an expression that can contain multiple conditions.
     *
     * @return array
     * @throws \Exception
     */
    protected function parseExpression(): array
    {
        $conditions = [];
        $logical = 'AND';

        while ($this->current < count($this->tokens)) {
            $token = $this->peek();

            if ($token === ')') {
                break;
            } elseif (strtoupper($token) === 'AND' || strtoupper($token) === 'OR') {
                $logical = strtoupper($this->consume());
            } else {
                $conditions[] = $this->parseCondition();
            }
        }

        // If there's only one condition, return it directly.
        if (count($conditions) === 1) {
            return $conditions[0];
        }

        return [
            'type'       => 'group',
            'operator'   => $logical,
            'conditions' => $conditions,
        ];
    }

    /**
     * Parses a single condition (or a grouped expression).
     *
     * @return array
     * @throws \Exception
     */
    protected function parseCondition(): array
    {
        $token = $this->peek();

        // Handle grouping with parentheses
        if ($token === '(') {
            $this->consume('(');
            $expr = $this->parseExpression();
            $this->consume(')');
            return $expr;
        }

        // Handle attribute conditions with the "attribute:" prefix.
        if (stripos($token, 'attribute:') === 0) {
            $attributeToken = $this->consume();
            $attribute = substr($attributeToken, strlen('attribute:'));
            $operator = $this->consume(); // e.g., >=, =, etc.
            $value = $this->consume();
            return [
                'type'        => 'condition',
                'filter_type' => 'attribute',
                'attribute'   => $attribute,
                'operator'    => $operator,
                'value'       => $this->stripQuotes($value),
            ];
        }

        // Otherwise, assume it's a field or relation condition.
        $field = $this->consume();
        $next = $this->peek();

        // Check for relation filtering operators.
        if (in_array(strtoupper($next), ['HAS_ANY', 'IS_ANY', 'EXISTS'])) {
            $operator = $this->consume();
            if (strtoupper($operator) === 'EXISTS') {
                return [
                    'type'        => 'condition',
                    'filter_type' => 'relation',
                    'relation'    => $field,
                    'operator'    => 'EXISTS',
                    'values'      => [],
                ];
            }

            $this->consume('(');
            $values = [];
            while ($this->peek() !== ')') {
                $values[] = $this->stripQuotes($this->consume());
                if ($this->peek() === ',') {
                    $this->consume(',');
                }
            }
            $this->consume(')');
            return [
                'type'        => 'condition',
                'filter_type' => 'relation',
                'relation'    => $field,
                'operator'    => strtoupper($operator),
                'values'      => $values,
            ];
        } else {
            // It's a basic field condition: field operator value.
            $operator = $this->consume();
            $value = $this->consume();
            return [
                'type'        => 'condition',
                'filter_type' => 'field',
                'field'       => $field,
                'operator'    => $operator,
                'value'       => $this->stripQuotes($value),
            ];
        }
    }

    protected function peek()
    {
        return $this->tokens[$this->current] ?? null;
    }

    /**
     * Consumes and returns the current token.
     *
     * @param string|null $expected Optionally enforce an expected token.
     * @return string|null
     * @throws \Exception
     */
    protected function consume($expected = null)
    {
        $token = $this->tokens[$this->current] ?? null;
        if ($expected !== null && $token !== $expected) {
            throw new \Exception("Expected token '{$expected}' but got '{$token}' at position {$this->current}.");
        }
        $this->current++;
        return $token;
    }

    /**
     * Removes surrounding quotes from a token if present.
     *
     * @param string $value
     * @return string
     */
    protected function stripQuotes($value)
    {
        return trim($value, "\"'");
    }
}
