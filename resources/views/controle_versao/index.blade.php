@extends('layouts.layout_admin_new.layouts.main')

@section('content')
    {!! csrf_field() !!}
    @includeIf('layouts.layout_admin_new.componentes.breadcrumb',[
                      'titulo' => 'Painel',
                    'sub_titulo' => 'Versionamento',
                    'rota' => 'painel',
                    'branch_atual' => $branch_atual
    ])

    @includeIf('layouts.layout_admin_new.componentes.cards')

@endsection
