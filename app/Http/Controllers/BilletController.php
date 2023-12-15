<?php

namespace App\Http\Controllers;

use App\Models\Billet;
use App\Models\Unit;
use Illuminate\Http\Request;

class BilletController extends Controller
{
    /**
     * Retorna os boletos de acordo com a propriedade enviada
     *
     * @param Request $request
     * @return array
     */
    public function getAll(Request $request): array
    {
        $array = ['error' => ''];

        $property = $request->input('property');
        if ($property) {
            $user = auth()->user();

            $unit = Unit::where('id', $property)->where('id_owner', $user['id'])->count();

            if ($unit > 0) {
                $billets = Billet::where('id_unit', $property)->get();

                foreach ($billets as $billetKey => $billetValue) {
                    $billets[$billetKey]['fileurl'] = asset('storage/' . $billetValue['fileurl']);
                }

                $array['list'] = $billets;
            } else {
                $array['error'] = 'Esta unidade não é sua.';
            }
        } else {
            $array['error'] = 'A propriedade é necessária.';
        }

        return $array;
    }
}
