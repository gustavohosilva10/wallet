<?php

namespace App\Domain\Bracket\Business;

use Exception;

class BracketBusiness 
{
    public function isValidBracketSequence(string $brackets): bool
    {
        $stack = [];

        for ($i = 0; $i < strlen($brackets); $i++) {
            $char = $brackets[$i];

            if ($this->isOpeningBracket($char)) {
                $stack[] = $char;
            } elseif ($this->isClosingBracket($char)) {
                $topElement = array_pop($stack);
                if (!$this->isMatchingBracket($topElement, $char)) {
                    return false;
                }
            }
        }

        return empty($stack);
    }

    private function isOpeningBracket(string $char): bool
    {
        return in_array($char, ['(', '{', '[']);
    }

    private function isClosingBracket(string $char): bool
    {
        return in_array($char, [')', '}', ']']);
    }

    private function isMatchingBracket(?string $opening, string $closing): bool
    {
        $matchingBrackets = [
            ')' => '(',
            '}' => '{',
            ']' => '['
        ];

        return isset($matchingBrackets[$closing]) && $matchingBrackets[$closing] === $opening;
    }
}
