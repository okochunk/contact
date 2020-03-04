<?php

namespace App\Traits;

trait FulltextSearch
{
    public function fullTextWildcards($term)
    {
        $reservedSymbols = ['-', '+', '<', '>', '@', '(', ')', '~'];
        $term = str_replace($reservedSymbols, '', $term);

        $words = explode(' ', $term);

        foreach ($words as $key => $word) {

            if (strlen($word) >= 3) {
                $words[$key] = '*' . $word . '*';
            }
        }

        $searchTerm = implode(' ', $words);

        return $searchTerm;
    }
}