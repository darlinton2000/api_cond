<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use App\Models\UnitPeople;
use App\Models\UnitPet;
use App\Models\UnitVehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UnitController extends Controller
{
    /**
     * Retorna as informações da unidade
     *
     * @param int $id
     * @return string[]
     */
    public function getInfo(int $id): array
    {
        $array = ['error' => ''];

        $unit = Unit::find($id);
        if ($unit) {
            $peoples = UnitPeople::where('id_unit', $id)->get();
            $vehicles = UnitVehicle::where('id_unit', $id)->get();
            $pets = UnitPet::where('id_unit', $id)->get();

            foreach ($peoples as $pKey => $pValue) {
                $peoples[$pKey]['birthdate'] = date('d/m/Y', strtotime($pValue['birthdate']));
            }

            $array['peoples'] = $peoples;
            $array['vehicles'] = $vehicles;
            $array['pets'] = $pets;
        } else {
            $array['error'] = 'Propriedade inexistente';
            return $array;
        }

        return $array;
    }

    /**
     * Adiciona pessoa
     *
     * @param int $id
     * @param Request $request
     * @return string[]
     */
    public function addPerson(int $id, Request $request): array
    {
        $array = ['error' => ''];

        $validator = Validator::make($request->all(), [
           'name' => 'required',
           'birthdate' => 'required|date'
        ]);

        if (!$validator->fails()) {
            $name = $request->input('name');
            $birthdate = $request->input('birthdate');

            $newPerson = new UnitPeople();
            $newPerson->id_unit = $id;
            $newPerson->name = $name;
            $newPerson->birthdate = $birthdate;
            $newPerson->save();
        } else {
            $array['error'] = $validator->errors()->first();
            return $array;
        }

        return $array;
    }

    /**
     * Adiciona veículo
     *
     * @param int $id
     * @param Request $request
     * @return string[]
     */
    public function addVehicle(int $id, Request $request): array
    {
        $array = ['error' => ''];

        $validator = Validator::make($request->all(), [
           'title' => 'required',
           'color' => 'required',
           'plate' => 'required'
        ]);

        if (!$validator->fails()) {
            $title = $request->input('title');
            $color = $request->input('color');
            $plate = $request->input('plate');

            $newVehicle = new UnitVehicle();
            $newVehicle->id_unit = $id;
            $newVehicle->title = $title;
            $newVehicle->color = $color;
            $newVehicle->plate = $plate;
            $newVehicle->save();
        } else {
            $array['error'] = $validator->errors()->first();
            return $array;
        }

        return $array;
    }

    /**
     * Adiciona animal de estimação
     *
     * @param int $id
     * @param Request $request
     * @return string[]
     */
    public function addPet(int $id, Request $request): array
    {
        $array = ['error' => ''];

        $validator = Validator::make($request->all(), [
           'name' => 'required',
           'race' => 'required'
        ]);

        if (!$validator->fails()) {
            $name = $request->input('name');
            $race = $request->input('race');

            $newPet = new UnitPet();
            $newPet->id_unit = $id;
            $newPet->name = $name;
            $newPet->race = $race;
            $newPet->save();
        } else {
            $array['error'] = $validator->errors()->first();
            return $array;
        }

        return $array;
    }

    /**
     * Remove pessoa
     *
     * @param int $id
     * @param Request $request
     * @return string[]
     */
    public function removePerson(int $id, Request $request): array
    {
        $array = ['error' => ''];

        $idItem = $request->input('id');

        if ($idItem) {
            UnitPeople::where('id', $idItem)->where('id_unit', $id)->delete();
        } else {
            $array['error'] = 'ID inexistente';
            return $array;
        }

        return $array;
    }

    /**
     * Remove veículo
     *
     * @param int $id
     * @param Request $request
     * @return string[]
     */
    public function removeVehicle(int $id, Request $request): array
    {
        $array = ['error' => ''];

        $idItem = $request->input('id');

        if ($idItem) {
            UnitVehicle::where('id', $idItem)->where('id_unit', $id)->delete();
        } else {
            $array['error'] = 'ID inexistente';
            return $array;
        }

        return $array;
    }

    /**
     * Remove animal de estimação
     *
     * @param int $id
     * @param Request $request
     * @return string[]
     */
    public function removePet(int $id, Request $request): array
    {
        $array = ['error' => ''];

        $idItem = $request->input('id');

        if ($idItem) {
            UnitPet::where('id', $idItem)->where('id_unit', $id)->delete();
        } else {
            $array['error'] = 'ID inexistente';
            return $array;
        }

        return $array;
    }
}
