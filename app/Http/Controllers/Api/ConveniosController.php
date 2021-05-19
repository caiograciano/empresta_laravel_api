<?php
namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ConveniosController extends Controller
{
    /**
     * Busca todas as Instituições
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $filename = 'convenios';
        $path = storage_path() . "/json/${filename}.json";

        $json = json_decode(file_get_contents($path), true);

        if (!empty($json)) {
            return response()->json($json);
        } else {
            return response()->json(array(
                'success' => false,
                'error' => 'Não foi possível carregar as Instituições'
            ));
        }
    }

}
