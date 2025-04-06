<?php

namespace App\Services;
use Illuminate\Database\Eloquent\Builder;
use App\Classes\FilterParser;

class JobFilterService
{
   /**
     * Applies the filter string to the Eloquent query.
     *
     * @param Builder $query
     * @param string  $filter
     * @return Builder
     * @throws \InvalidArgumentException
     */
    public static function apply(Builder $query, string $filter): Builder
    {
        try {
            $parser = new FilterParser($filter);
            $ast = $parser->parse();
        } catch (\Exception $e) {
            throw new \InvalidArgumentException('Invalid filter format: ' . $e->getMessage());
        }

        return self::applyAst($query, $ast);
    }

    /**
     * Recursively applies the AST conditions to the query.
     *
     * @param Builder $query
     * @param array   $ast
     * @return Builder
     */
    protected static function applyAst(Builder $query, array $ast): Builder
    {
        // If the node is a group of conditions
        if (isset($ast['type']) && $ast['type'] === 'group') {
            $logical = strtoupper($ast['operator'] ?? 'AND');
            return $query->where(function ($q) use ($ast, $logical) {
                foreach ($ast['conditions'] as $condition) {
                    if ($logical === 'AND') {
                        $q->where(function ($q2) use ($condition) {
                            self::applyAst($q2, $condition);
                        });
                    } else {
                        $q->orWhere(function ($q2) use ($condition) {
                            self::applyAst($q2, $condition);
                        });
                    }
                }
            });
        }
        // Otherwise, it's a single condition node
        if (isset($ast['type']) && $ast['type'] === 'condition') {
            switch ($ast['filter_type']) {
                case 'field':
                    // Basic filtering (supports =, !=, LIKE, >, <, >=, <=)
                    return $query->where($ast['field'], $ast['operator'], $ast['value']);

                case 'relation':
                    // Relationship filtering via whereHas
                    return $query->whereHas($ast['relation'], function ($q) use ($ast) {
                        if (in_array($ast['operator'], ['HAS_ANY', 'IS_ANY'])) {
                            $q->whereIn('title', $ast['values']);
                        } elseif ($ast['operator'] === '=') {
                            // Assuming equality is for a single value
                            $q->where('title', '=', $ast['values'][0]);
                        } elseif ($ast['operator'] === 'EXISTS') {
                            // whereHas ensures the relation exists; no further condition needed.
                        }
                    });

                case 'attribute':
                    // EAV filtering: join job_attribute_values and attributes
                    return $query->whereHas('attributes', function ($q) use ($ast) {
                        $q->where('value', $ast['operator'], $ast['value'])
                          ->whereHas('values', function ($q2) use ($ast) {
                              $q2->where('name', $ast['attribute']);
                          });
                    });
            }
        }

        return $query;
    }
}
