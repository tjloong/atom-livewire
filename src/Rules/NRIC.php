<?php
 
namespace Jiannius\Atom\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class NRIC implements ValidationRule
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
        'brunei' => ['60'],
        'indonesia' => ['61'],
        'cambodia,democratic-kampuchea,kampuchea' => ['62'],
        'laos' => ['63'],
        'myanmar' => ['64'],
        'philippines' => ['65'],
        'singapore' => ['66'],
        'thailand' => ['67'],
        'vietnam' => ['68'],
        'china' => ['74'],
        'india' => ['75'],
        'pakistan' => ['76'],
        'saudi-arabia' => ['77'],
        'sri-lanka' => ['78'],
        'bangladesh' => ['79'],
        'american-samoa,asia-pacific,australia,christmas-island,cocos-(keeling)-islands,cook-islands,fiji,french-polynesia,guam,heard-island-and-mcdonald-islands,marshall-islands,micronesia,new-caledonia,new-zealand,niue,norfolk-island,papua-new-guinea,timor-leste,tokelau,united-states-minor-outlying-islands,wallis-and-futuna-islands' => ['83'],
        'anguilla,argentina,aruba,bolivia,brazil,chile,colombia,ecuador,french-guinea,guadeloupe,guyana,paraguay,peru,south-america,south-georgia-and-the-south-sandwich-islands,suriname,uruguay,venezuela' => ['84'],
        'africa,algeria,angola,botswana,burundi,cameroon,central-african-republic,chad,congo-brazzaville,congo-kinshasa,djibouti,egypt,eritrea,ethiopia,gabon,gambia,ghana,guinea,kenya,liberia,malawi,mali,mauritania,mayotte,morocco,mozambique,namibia,niger,nigeria,rwanda,réunion,senegal,sierra-leone,somalia,south-africa,sudan,swaziland,tanzania,togo,tonga,tunisia,uganda,western-sahara,zaire,zambia,zimbabwe' => ['85'],
        'armenia,austria,belgium,cyprus,denmark,europe,faroe-islands,france,finland,finland,-metropolitan,germany,germany,-democratic-republic,germany,-federal-republic,greece,holy-see-(vatican-city),italy,luxembourg,macedonia,malta,mediterranean,monaco,netherlands,norway,portugal,republic-of-moldova,slovakia,slovenia,spain,sweden,switzerland,united-kingdom-dependent-territories,united-kingdom-national-overseas,united-kingdom-overseas-citizen,united-kingdom-protected-person,united-kingdom-subject' => ['86'],
        'britain,great-britain,ireland' => ['87'],
        'bahrain,iran,iraq,palestine,jordan,kuwait,lebanon,middle-east,oman,qatar,republic-of-yemen,syria,turkey,united-arab-emirates,yemen-arab-republic,yemen-people’s-democratic-republic' => ['88'],
        'far-east,japan,north-korea,south-korea,taiwan' => ['89'],
        'bahamas,barbados,belize,caribbean,costa-rica,cuba,dominica,dominican-republic,el-salvador,grenada,guatemala,haiti,honduras,jamaica,martinique,mexico,nicaragua,panama,puerto-rico,saint-kitts-and-nevis,saint-lucia,saint-vincent-and-the-grenadines,trinidad-and-tobago,turks-and-caicos-islands,virgin-islands-(usa)' => ['90'],
        'canada,greenland,netherlands-antilles,north-america,saint-pierre-and-miquelon,united-states-of-america' => ['91'],
        'albania,belarus,bosnia-and-herzegovina,bulgaria,byelorussia,croatia,czech-republic,czechoslovakia,estonia,georgia,hungary,latvia,lithuania,montenegro,poland,republic-of-kosovo,romania,russian-federation,serbia,soviet-union,u.s.s.r.,ukraine' => ['92'],
        'afghanistan,andorra,antarctica,antigua-and-barbuda,azerbaijan,benin,bermuda,bhutan,bora-bora,bouvet-island,british-indian-ocean-territory,burkina-faso,cape-verde,cayman-islands,comoros,dahomey,equatorial-guinea,falkland-islands,french-southern-territories,gibraltar,guinea-bissau,hong-kong,iceland,ivory-coast,kazakhstan,kiribati,kyrgyzstan,lesotho,libya,liechtenstein,macau,madagascar,maghribi,malagasy,maldives,mauritius,mongolia,montserrat,nauru,nepal,northern-marianas-islands,outer-mongolia,palau,palestine,pitcairn-islands,saint-helena,saint-lucia,saint-vincent-and-the-grenadines,samoa,san-marino,são-tomé-and-príncipe,seychelles,solomon-islands,svalbard-and-jan-mayen-islands,tajikistan,turkmenistan,tuvalu,upper-volta,uzbekistan,vanuatu,vatican-city,virgin-islands-(british),western-samoa,yugoslavia' => ['93'],
        'stateless' => ['98'],
        'mecca,neutral-zone,no-information,refugee,refugee-article-1/1951,united-nations-specialized-agency,united-nations-organization,unspecified-nationality' => ['99'],
        'outside-malaysia-prior-2001' => ['71', '72'],
        'others' => ['69', '70', '73', '80', '81', '82', '94', '95', '96', '97'],
    ];

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (str($value)->is('*-*-*')) [$head, $body, $tail] = explode('-', $value);
        else {
            $head = substr($value, 0, 6);
            $body = substr($value, 6, 2);
            $tail = substr($value, 8);
        }
        
        $codes = collect($this->codes)->values()->flatten()->all();
        $valid = $head && $body && $tail && in_array($body, $codes) && strlen($head) === 6 && strlen($tail) === 4;

        if (!$valid) {
            $fail('Invalid I/C number.')->translate();
        }
    }
}