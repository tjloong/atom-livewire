<?php
 
namespace Jiannius\Atom\Rules;
 
use Illuminate\Contracts\Validation\Rule;
 
class MyIcNumber implements Rule
{
    public $codes = [
        'johor' => ['01', '21', '22', '23', '24'],
        'kedah' => ['02', '25', '26', '27'],
        'kelantan' => ['03', '28', '29'],
        'melaka' => ['04', '30'],
        'negeri-sembilan' => ['05', '31', '59'],
        'pahang' => ['06', '32', '33'],
        'pulau-pinang' => ['07', '34', '35'],
        'perak' => ['08', '36', '37', '38', '39'],
        'perlis' => ['09', '40'],
        'selangor' => ['10', '41', '42', '43', '44'],
        'terengganu' => ['11', '45', '46'],
        'sabah' => ['12', '47', '48', '49'],
        'sarawak' => ['13', '50', '51', '52', '53'],
        'kuala-lumpur' => ['14', '54', '55', '56', '57'],
        'labuan' => ['15', '58'],
        'putrajaya' => ['16'],
        'others' => ['82'],
    ];

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if (str($value)->is('*-*-*')) [$head, $body, $tail] = explode('-', $value);
        else {
            $head = substr($value, 0, 6);
            $body = substr($value, 6, 2);
            $tail = substr($value, 8);
        }
        
        $codes = collect($this->codes)->values()->flatten()->all();

        return $head && $body && $tail 
            && in_array($body, $codes)
            && strlen($head) === 6
            && strlen($tail) === 4;
    }
 
    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('Invalid I/C number.');
    }
}