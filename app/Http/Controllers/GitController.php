<?php

namespace App\Http\Controllers;

use App\Http\Models\UsuarioGithub;
use App\Http\Repositorys\GitSistemaRepository;
use App\Http\Util\Dado;
use Github\Api\Repository\Contents;
use Github\Client;
use Http\Client\Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GitController extends Controller
{

    private function funcionalidades()
    {
        return [
            'Merge & checkout',
            'Create & Delete',
            'Commit Branch',
            'Pull & Push Repository',
            'Initialization Repository'
        ];
    }

    private function rotas()
    {
        return [
            'index_merge_checkout',
            'index_create_delete',
            'index_commit_branch',
            'index_pull_push',
            'index_init'
        ];
    }

    public function index_merge_checkout()
    {
        $branch_atual = 'Em construção';
        return view('controle_versao.merge_checkout', compact('branch_atual', 'branchs'));
    }

    public function index_create_delete()
    {
        $branch_atual = 'Em construção';
        return view('controle_versao.create_delete', compact('branch_atual', 'branchs'));
    }

    public function index_commit_branch()
    {
        $branch_atual = 'Em construção';
        return view('controle_versao.commit', compact('branch_atual', 'branchs'));
    }

    public function index_pull_push()
    {
        $branch_atual = 'Em construção';
        return view('controle_versao.pull_push', compact('branch_atual', 'branchs'));
    }

    public function index_init()
    {
        $branch_atual = 'Em construção';
        return view('controle_versao.init', compact('tipo', 'branch_atual','titulos'));
    }

    public function index()
    {
        $funcionalidades = [];
        $rotas = self::rotas();
        $dados = self::funcionalidades();
        for ($indice = 0; $indice < 5; $indice++) {
            $funcionalidades[$indice] = new Dado();
            $funcionalidades[$indice]->titulo = $dados[$indice];
            $funcionalidades[$indice]->rota = $rotas[$indice];
        }
        $branch_atual = 'Em construção';

        return view('controle_versao.index', compact('branch_atual', 'funcionalidades'));
    }


    public function init(Request $request)
    {
        $branch_atual = 'Em construção';
        try{
           $repositorio =  GitSistemaRepository::create_repository($request->nome);
           $github_data = Auth::user()->github;
           $user_github = UsuarioGithub::findOrFail($github_data->codusuariogithub);
           $data = [
               'branch_atual' => $repositorio['default_branch'],
                'repositorio_atual' => $repositorio['name']
           ];
            $user_github->update($data);
            return redirect()->route('controle_versao.show',['nome_repositorio' => $request->nome]);
        }
        catch (\Exception $ex){
            flash($ex->getMessage())->error();
            return redirect()->route('index_init');
        }

    }
    public function show($nome_repositorio){
        $repositorio = GitSistemaRepository::get_repositorio('jeancarlos2015', $nome_repositorio);
        return view('controle_versao.show', compact('tipo', 'branch_atual','repositorio'));
    }
    public function delete_repository(Request $request){
       dd($request);
    }
    public function edit_repository(Request $request){
        dd($request);
    }

    public function delete(Request $request)
    {

        return redirect()->route('index_create_delete');
    }

    public function create(Request $request)
    {

        return redirect()->route('index_create_delete');
    }

    public function push(Request $request)
    {

    }

    public function pull(Request $request)
    {
    }

    private function merge(Request $request)
    {

        return redirect()->route('index_merge_checkout');
    }


    public function merge_checkout(Request $request)
    {

        return $this->checkout($request);
    }

    private function checkout(Request $request)
    {


        return redirect()->route('index_merge_checkout');
    }

    public function commit(Request $request)
    {

        return redirect()->route('index_commit_branch');
    }

    //token github
//'b67159b091d9ec2f5953de0361fc47d37efa0591'







    
    public function reset_files(Request $request)
    {
        $client = new Client();
        $client->authenticate(Client::AUTH_HTTP_TOKEN, Client::AUTH_HTTP_PASSWORD);

        return redirect()->route('index_reset_files');
    }



    public function index_reset_files()
    {
        $git = new GitSistemaRepository();
        $branch_atual = 'Não implementado';
        return view('controle_versao.teste', compact('branch_atual'));
    }

    /**
     *
     */

}
