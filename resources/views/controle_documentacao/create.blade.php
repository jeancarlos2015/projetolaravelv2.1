@extends('layouts.layout_admin_new.layouts.main')

@section('content')
    {!! csrf_field() !!}
    @includeIf('layouts.layout_admin_new.componentes.breadcrumb',[
              'titulo' => 'Painel',
              'sub_titulo' => 'Nova Documentação',
    ])

    <form action="{!! route('controle_documentacoes.store') !!}" method="post">
        @method('POST')
        @includeIf('controle_documentacao.form',['acao' => 'Criar Documentação','dados' => $dados,'MAX' => 3])
    </form>

@endsection

@section('modo')
    <li class="nav-item">
        <a class="nav-link" title="Modo de Edição de Objeto de Fluxo">
            <p class="fa fa-dashboard"> Criação da documentação</p>
            <span class="sr-only"></span>
        </a>
    </li>
@endsection