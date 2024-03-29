<?php

declare(strict_types=1);

namespace App\Helpers;

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

    public static function slugify(string $string): string
    {
        $string = self::removeSpecialChars($string);
        $string = strtolower($string);
        $string = preg_replace('/[^A-Za-z0-9-]+/', '-', $string);

        return preg_replace('/-+/', '-', $string);
    }

    public static function removeSpecialChars(string $string): string
    {
        $replacePairs = [
            'Š' => 'S',
            'š' => 's',
            'Ž' => 'Z',
            'ž' => 'z',
            'À' => 'A',
            'Á' => 'A',
            'Â' => 'A',
            'Ã' => 'A',
            'Ä' => 'A',
            'Å' => 'A',
            'Æ' => 'A',
            'Ç' => 'C',
            'È' => 'E',
            'É' => 'E',
            'Ê' => 'E',
            'Ë' => 'E',
            'Ì' => 'I',
            'Í' => 'I',
            'Î' => 'I',
            'Ï' => 'I',
            'Ñ' => 'N',
            'Ò' => 'O',
            'Ó' => 'O',
            'Ô' => 'O',
            'Õ' => 'O',
            'Ö' => 'O',
            'Ø' => 'O',
            'Ù' => 'U',
            'Ú' => 'U',
            'Û' => 'U',
            'Ü' => 'U',
            'Ý' => 'Y',
            'Þ' => 'B',
            'ß' => 'Ss',
            'à' => 'a',
            'á' => 'a',
            'â' => 'a',
            'ã' => 'a',
            'ä' => 'a',
            'å' => 'a',
            'æ' => 'a',
            'ç' => 'c',
            'è' => 'e',
            'é' => 'e',
            'ê' => 'e',
            // Converts the letter "e" with a pre-composed diaeresis into an ordinary letter "e".
            'ë' => 'e',
            // Converts the decomposed form of the letter "e" with a diaeresis (a sequence of two characters) into an ordinary letter "e".
            'ë' => 'e',
            'ì' => 'i',
            'í' => 'i',
            'î' => 'i',
            'ï' => 'i',
            'ð' => 'o',
            'ñ' => 'n',
            'ò' => 'o',
            'ó' => 'o',
            'ô' => 'o',
            'õ' => 'o',
            'ö' => 'o',
            'ø' => 'o',
            'ù' => 'u',
            'ú' => 'u',
            'û' => 'u',
            'ý' => 'y',
            'þ' => 'b',
            'ÿ' => 'y',
            ' ' => '-',
        ];

        $string = strtr($string, $replacePairs);

        // Delete non-alphanumeric characters except (-)
        return preg_replace('/[^a-zA-Z0-9-]/', '', $string);
    }
}
