<?php

namespace App\Http\Controllers;

use App\Models\FoundAndLost;
use Illuminate\Http\Request;

class FoundAndLostController extends Controller
{
    /**
     * Retorna a lista dos achados e perdidos
     *
     * @return array
     */
    public function getAll(): array
    {
        $array = ['error' => ''];

        $lost = FoundAndLost::where('status', 'LOST')
            ->orderBy('datecreated', 'DESC')
            ->orderBy('id', 'DESC')
            ->get();

        $recovered = FoundAndLost::where('status', 'RECOVERED')
            ->orderBy('datecreated', 'DESC')
            ->orderBy('id', 'DESC')
            ->get();

        foreach ($lost as $lostKey => $lostValue) {
            $lost[$lostKey]['datecreated'] = date('d/m/Y', strtotime($lostValue['datecreated']));
            $lost[$lostKey]['photo'] = asset('storage/' . $lostValue['photo']);
        }

        foreach ($recovered as $recKey => $recValue) {
            $recovered[$recKey]['datecreated'] = date('d/m/Y', strtotime($recValue['datecreated']));
            $recovered[$recKey]['photo'] = asset('storage/' . $recValue['photo']);
        }

        $array['lost'] = $lost;
        $array['recovered'] = $recovered;

        return $array;
    }
}
