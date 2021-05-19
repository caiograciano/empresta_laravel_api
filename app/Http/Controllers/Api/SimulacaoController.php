<?php
namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\JsonFile;

class SimulacaoController extends Controller
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
        $dados = $request->all();
        $valida = $this->validaJson($dados);

        if(!$valida['success']){
            return response()->json(array(
                'success' => false,
                'error' => 'O parâmetro "valor_emprestimo" é Obrigatório !'
            ));
        } else {
            return $this->getInfoEmprestimo($dados);
        }
    }

    /** Valida se o $request recebeu a informação obrigatória
     * @param $dados
     * @return bool[]|false[]
     */
    private function validaJson($dados){
        if(!isset($dados['valor_emprestimo'])){
           return array('success' => false);
        } else {
            return array('success' => true);
        }
    }

    private function getInfoEmprestimo($dados){

        $jsonTaxas = $this->getJsonFile('taxas_instituicoes');

        $info = [];
        $info['instituicoes'] = $this->getInstituicoes($dados,$jsonTaxas);
        $info['convenios'] = $this->getConvenios($dados,$jsonTaxas);
        $info['parcela'] = isset($dados['parcela']) ? $dados['parcela'] : [] ;

        return $this->calculaEmprestimo($info,$dados);
//        return response()->json($result);
    }

    /**
     * Calcula os valores de parcelas
     * @param $info
     * @param $dados
     * @return array
     */
    private function calculaEmprestimo($info,$dados){
        $arrAux = [];
        foreach ($info['instituicoes'] as $key => $value){
            if(!isset($arrAux[$value['instituicao']]))
                $arrAux[$value['instituicao']] = [];

            $i['taxa'] = $value['taxaJuros'];
            $i['parcelas'] = $value['parcelas'];
            $i['valor_parcela'] = round(($dados['valor_emprestimo'] *  $value['coeficiente']),2);
            $i['convenio'] = $value['convenio'];

            array_push($arrAux[$value['instituicao']],$i);
        }

        return $arrAux;
    }

    /**
     * Retorna o Arquivo Json
     * @param $filename
     * @return mixed
     */
    private function getJsonFile($filename)
    {
        $path = storage_path() . "/json/${filename}.json";
        return json_decode(file_get_contents($path), true);
    }

    /**
     * Retorna as Instituicoes de acordo com os dados recebidos
     * @param $dados
     * @param $jsonTaxas
     * @return array
     */
    private function getInstituicoes($dados,$jsonTaxas)
    {
        $arrAux =  [];
        if($dados['instituicoes']){
            foreach ($dados['instituicoes'] as $key => $instituicao) {
                foreach ($jsonTaxas as $key => $taxa){
                    if ($taxa['instituicao'] == $instituicao['chave'])
                        array_push($arrAux,$taxa);
                }
            }
        }
        return $arrAux;
    }

    /**
     * Retorna os Convenios de acordo com os dados recebidos
     * @param $dados
     * @param $jsonTaxas
     * @return array
     */
    private function getConvenios($dados,$jsonTaxas)
    {
        $arrAux =  [];
        if($dados['convenios']){
            foreach ($dados['convenios'] as $key => $convenio) {
                foreach ($jsonTaxas as $key => $taxa){
                    if ($taxa['convenio'] == $convenio['chave'])
                        array_push($arrAux,$taxa);
                }
            }
        }
        return $arrAux;
    }






}
