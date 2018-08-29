<?php

namespace App\Http\Controllers;

use App\Http\Models\RepresentacaoDeclarativa;
use App\Http\Models\RepresentacaoDiagramatico;
use App\Http\Models\Projeto;
use App\Http\Models\Repositorio;
use App\Http\Repositorys\LogRepository;
use App\Http\Repositorys\RepresentacaoDeclarativoRepository;
use App\Http\Repositorys\ModeloDiagramaticoRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ModeloDiagramaticoController extends Controller
{

    public function index($codrepositorio, $codprojeto, $codusuario)
    {

        try {
            $projeto = Projeto::findOrFail($codprojeto);
            $repositorio = $projeto->repositorio;
            $titulos = RepresentacaoDiagramatico::titulos();
            $modelos_declarativos = $projeto->modelos_declarativos;
            $modelos_diagramaticos = $projeto->modelos_diagramaticos;
            $modelos = $modelos_declarativos->merge($modelos_diagramaticos);
        } catch (\Exception $ex) {
            $data['mensagem'] = $ex->getMessage();
            $data['tipo'] = 'error';
            $data['pagina'] = 'Painel';
            $data['acao'] = 'merge_checkout';
            $this->create_log($data);
        }
        $tipo = 'modelo_diagramatico';
        return view('controle_modelos_diagramaticos.index', compact('modelos', 'projeto', 'repositorio', 'titulos', 'tipo'));
    }

    public function todos_modelos()
    {
        try {
            $modelos_diagramaticos = ModeloDiagramaticoRepository::listar();
            $modelos = $modelos_diagramaticos->merge(RepresentacaoDeclarativoRepository::listar());
            $titulos = RepresentacaoDiagramatico::titulos();
            $tipo = 'modelo_diagramatico';
            $log = LogRepository::log();
        } catch (\Exception $ex) {
            $data['mensagem'] = $ex->getMessage();
            $data['tipo'] = 'error';
            $data['pagina'] = 'Painel';
            $data['acao'] = 'merge_checkout';
            $this->create_log($data);
        }

        return view('controle_modelos_diagramaticos.index_todos_modelos', compact('modelos', 'titulos', 'tipo', 'log'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function escolhe_modelo(Request $request)
    {
        $dado['tipo'] = $request->tipo;
        $dado['nome'] = $request->nome;
        $dado['descricao'] = $request->descricao;
        $dado['cod_projeto'] = $request->cod_projeto;
        $dado['cod_repositorio'] = $request->cod_repositorio;
        try {
            $projeto = Projeto::findOrFail($request->cod_projeto);
            $repositorio = Repositorio::findOrFail($request->cod_repositorio);
        } catch (\Exception $ex) {
            $data['mensagem'] = $ex->getMessage();
            $data['tipo'] = 'error';
            $data['pagina'] = 'Painel';
            $data['acao'] = 'merge_checkout';
            $this->create_log($data);
        }
        return view('controle_modelos_diagramaticos.create', compact('dado', 'projeto', 'repositorio'));
    }

    public function create($codrepositorio, $codprojeto)
    {
        try {
            $projeto = Projeto::findOrFail($codprojeto);
            $repositorio = Repositorio::findOrFail($codrepositorio);
            $dados = RepresentacaoDiagramatico::dados();
        } catch (\Exception $ex) {
            $data['mensagem'] = $ex->getMessage();
            $data['tipo'] = 'error';
            $data['pagina'] = 'Painel';
            $data['acao'] = 'merge_checkout';
            $this->create_log($data);
        }
        return view('controle_modelos_diagramaticos.create', compact('dados', 'repositorio', 'projeto'));
    }

    public function edicao_modelo_diagramatico($codmodelo)
    {
        $modelo = RepresentacaoDiagramatico::findOrFail($codmodelo);
        $path_modelo = public_path('novo_bpmn/');
        if (!file_exists($path_modelo)) {
            mkdir($path_modelo, 777);
        }
        $file = $path_modelo . 'novo.bpmn';
        file_put_contents($file, $modelo->xml_modelo);
        sleep(2);
        return view('controle_modelos_diagramaticos.modeler', compact('modelo'));
    }

//$codrepositorio, $codprojeto, $codmodelo
    public
    function store(Request $request)
    {
        try {
            $codprojeto = $request->cod_projeto;
            $codrepositorio = $request->cod_repositorio;
            $data['all'] = $request->all();
            $data['validacao'] = RepresentacaoDiagramatico::validacao();
            if (!$this->exists_errors($data)) {
                if (!ModeloDiagramaticoRepository::existe($request->nome)) {
                    $request->request->add([
                        'xml_modelo' => RepresentacaoDiagramatico::get_modelo_default($request->nome),
                        'cod_projeto' => $codprojeto,
                        'cod_repositorio' => $codrepositorio,
                        'cod_usuario' => Auth::user()->cod_usuario
                    ]);
                    $modelo = ModeloDiagramaticoRepository::incluir($request);
                    $data['tipo'] = 'success';
                    $this->create_log($data);
                    return redirect()->route('edicao_modelo_diagramatico',
                        ['cod_modelo_diagramatico' => $modelo->cod_modelo_diagramatico]);

                } else {
                    $dados = RepresentacaoDiagramatico::dados();
                    $modelo = ModeloDiagramaticoRepository::listar()->where('nome', $request->nome)->first();
                    $repositorio = $modelo->repositorio;
                    $projeto = $modelo->projeto;
                    return view('controle_modelos_diagramaticos.create', compact('dados', 'repositorio', 'projeto','modelo'));
                }

            }
            $erros = $this->get_errors($data);
            return redirect()->route('controle_modelos_diagramaticos_create', [
                'cod_repositorio' => $codrepositorio,
                'cod_projeto' => $codprojeto
            ])
                ->withErrors($erros)
                ->withInput();
        } catch (\Exception $ex) {
            $data['mensagem'] = $ex->getMessage();
            $data['tipo'] = 'error';
            $data['pagina'] = 'Painel';
            $data['acao'] = 'merge_checkout';
            $this->create_log($data);
        }

        return view('controle_modelos_diagramaticos.modeler', compact('modelo'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public
    function show($codmodelo)
    {
        try {
            $modelo = RepresentacaoDiagramatico::findOrFail($codmodelo);
            $path_modelo = public_path('novo_bpmn/');
            if (!file_exists($path_modelo)) {
                mkdir($path_modelo, 777);
            }
            $file = $path_modelo . 'novo.bpmn';
            file_put_contents($file, $modelo->xml_modelo);
            sleep(2);
            return view('controle_modelos_diagramaticos.visualizar_modelo', compact('modelo'));

        } catch (\Exception $ex) {
            $data['mensagem'] = $ex->getMessage();
            $data['tipo'] = 'error';
            $data['pagina'] = 'Painel';
            $data['acao'] = 'merge_checkout';
            $this->create_log($data);
        }
        return redirect()->route('painel');


    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public
    function edit($codmodelo)
    {
        try {
            $modelo = RepresentacaoDiagramatico::findOrFail($codmodelo);

            $dados = RepresentacaoDiagramatico::dados();
            $projeto = $modelo->projeto;
            $repositorio = $modelo->repositorio;

            $dados[0]->valor = $modelo->nome;
            $dados[1]->valor = $modelo->descricao;
            $dados[2]->valor = $modelo->tipo;

            return view('controle_modelos_diagramaticos.edit', compact('dados', 'modelo', 'projeto', 'repositorio'));
        } catch (\Exception $ex) {
            $data['mensagem'] = $ex->getMessage();
            $data['tipo'] = 'error';
            $data['pagina'] = 'Painel';
            $data['acao'] = 'merge_checkout';
            $this->create_log($data);
        }
        return redirect()->route('painel');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public
    function update(Request $request, $id)
    {
        try {

           $modelo = ModeloDiagramaticoRepository::atualizar($request, $id);
            if ($modelo->tipo === 'diagramatico') {
                return redirect()->route('edicao_modelo_diagramatico', [
                    'cod_modelo_diagramatico' => $modelo->cod_modelo_diagramatico
                ]);
            }

        } catch (\Exception $ex) {
            $data['mensagem'] = $ex->getMessage();
            $data['tipo'] = 'error';
            $data['pagina'] = 'Painel';
            $data['acao'] = 'merge_checkout';
            $this->create_log($data);
        }
        return redirect()->route('painel');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */

    private
    function delete($codmodelo)
    {
        try {
            $modelo = ModeloDiagramaticoRepository::excluir($codmodelo);
            return $modelo;
        } catch (\Exception $ex) {
            $data['mensagem'] = $ex->getMessage();
            $data['tipo'] = 'error';
            $data['pagina'] = 'Painel';
            $data['acao'] = 'merge_checkout';
            $this->create_log($data);
        }
    }

    public
    function destroy($codmodelo)
    {
        try {

            $modelo = ModeloDiagramaticoRepository::excluir($codmodelo);
            flash('Operação feita com sucesso!!');
            if (empty($modelo->cod_projeto) || empty($modelo->cod_repositorio)) {

                return redirect()->route('todos_modelos');
            } else {
                return redirect()->route('controle_modelos_diagramaticos_index',
                    [

                        'cod_repositorio' => $modelo->cod_repositorio,
                        'cod_projeto' => $modelo->cod_projeto,
                        'cod_usuario' => Auth::user()->cod_usuario
                    ]
                );
            }
        } catch (\Exception $ex) {
            $data['mensagem'] = $ex->getMessage();
            $data['tipo'] = 'error';
            $data['pagina'] = 'Painel';
            $data['acao'] = 'merge_checkout';
            $this->create_log($data);
        }
        return redirect()->route('painel');

    }

    public function gravar(Request $request)
    {
        $result = ModeloDiagramaticoRepository::gravar($request);
        return \Response::json($result);
    }

    public function visualizar_modelo_publico($codmodelo){
        $modelo = RepresentacaoDiagramatico::findOrFail($codmodelo);
        $path_modelo = public_path('novo_bpmn/');
        if (!file_exists($path_modelo)) {
            mkdir($path_modelo, 777);
        }
        $file = $path_modelo . 'novo.bpmn';
        file_put_contents($file, $modelo->xml_modelo);
        sleep(2);
        return view('modelos_publicos.visualizar_modelo',compact('modelo'));
    }

    public function modelos_publicos(){
        $titulos = RepresentacaoDiagramatico::titulos();
        $modelos = ModeloDiagramaticoRepository::listar_modelos_publicos();
        $tipo = 'publico';
        $contador=0;
        return view('modelos_publicos.index', compact('modelos', 'titulos', 'tipo','contador'));
    }
}
