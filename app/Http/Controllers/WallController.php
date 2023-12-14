<?php

namespace App\Http\Controllers;

use App\Models\Wall;
use App\Models\WallLike;
use Illuminate\Http\Request;

class WallController extends Controller
{
    /**
     * Lista as informações do mural do usuário autênticado
     *
     * @return array
     */
    public function getAll(): array
    {
        $array = ['error' => '', 'list' => []];

        $user = auth()->user();

        $walls = Wall::all();

        foreach ($walls as $wallKey => $wallValue) {
            $walls[$wallKey]['likes'] = 0;
            $walls[$wallKey]['liked'] = false;

            $likes = WallLike::where('id_wall', $wallValue['id'])->count();
            $walls[$wallKey]['likes'] = $likes;

            $meLikes = WallLike::where('id_wall', $wallValue['id'])
                ->where('id_user', $user['id'])
                ->count();

            if ($meLikes > 0) {
                $walls[$wallKey]['liked'] = true;
            }
        }

        $array['list'] = $walls;

        return $array;
    }

    /**
     * O usuário autênticado descurte/curte a postagem no mural
     *
     * @param $id
     * @return array
     */
    public function like(int $id): array
    {
        $array = ['error' => ''];

        $user = auth()->user();

        $meLikes = WallLike::where('id_wall', $id)
            ->where('id_user', $user['id'])
            ->count();

        if ($meLikes > 0) {
            // Remover like
            WallLike::where('id_wall', $id)->where('id_user', $user['id'])->delete();
            $array['liked'] = false;
        } else {
            // Adicionar like
            $newLike = new WallLike();
            $newLike->id_wall = $id;
            $newLike->id_user = $user['id'];
            $newLike->save();
            $array['liked'] = true;
        }

        $array['likes'] = WallLike::where('id_wall', $id)->count();

        return $array;
    }
}
