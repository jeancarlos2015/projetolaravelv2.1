
@extends('layouts.layout_admin_new.layouts.main')

@section('content')
    {!! csrf_field() !!}
    @includeIf('layouts.layout_admin_new.componentes.breadcrumb',['titulo' => 'Modelos'])
    {{--@includeIf('componentes.dados_exibicao')--}}
    <hr>
    <h3>Modelo Declarativo</h3>
    <hr>
    <div class="form-group">
    <a href="{!! route('show_regras',['id' => $modelo->codmodelodiagramatico]) !!}" class="btn btn-warning form-control">Visualizar Regras</a>
    </div>

    <div class="form-group">
    <a href="{!! route('show_tarefas',['id' => $modelo->codmodelodiagramatico]) !!}" class="btn btn-dark form-control">Visualizar Tarefas</a>
    </div>



@endsection