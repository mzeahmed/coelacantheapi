<?php

declare(strict_types=1);

namespace App\Core\Helpers;

class Strings
{
    /**
     * Extracts an ID from a URL.
     *
     * @param string $search The string to search for.
     * @param string $stringToMatch The string to search in.
     * @param bool $after If the ID is after the search string.
     */
    public static function extractIdFromUrl(string $search, string $stringToMatch, bool $after = true): ?int
    {
        if ($after) {
            $pattern = '#' . $search . '/(\d+)#';
        } else {
            $pattern = '#(\d+)/' . $search . '#';
        }

        preg_match($pattern, $stringToMatch, $matches);

        return (int) $matches[1];
    }
}
