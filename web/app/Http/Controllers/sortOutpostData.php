<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;

class sortOutpostData extends Controller
{
    public function getFilterData(Request $request) {
        $data = json_decode(Http::get('https://gist.githubusercontent.com/Loetfi/fe38a350deeebeb6a92526f6762bd719/raw/9899cf13cc58adac0a65de91642f87c63979960d/filter-data.json'), true);
        $denom = Arr::flatten(Arr::pluck($data['data']['response']['billdetails'], 'body'));

        $flattenDenom = [];
        foreach($denom as $value) {
            $flattenDenom[] = (int)trim(explode(":", $value)[1]);
        }

        $filteredDenom = array_filter($flattenDenom, function ($value) {
            return $value >= 100000;
        });
        sort($filteredDenom);

        dd($filteredDenom);
    }
}
