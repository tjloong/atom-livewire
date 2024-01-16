<?php
 
namespace Jiannius\Atom\Rules;
 
use Closure;
use ConsoleTVs\Profanity\Facades\Profanity as BadWordsFilter;
use Illuminate\Contracts\Validation\ValidationRule;
 
class Profanity implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $badwords = BadWordsFilter::blocker($value)
            ->dictionary(atom_path('resources/json/bad-words.json'))
            ->badWords();

        if (count($badwords)) {
            $fail('Please remove the bad words in the content.');
        }
    }
}