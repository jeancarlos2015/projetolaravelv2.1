<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ModeloDeclarativo extends Model
{
    protected $connection = 'banco';
    protected $primaryKey = 'codmodelodiagramatico';
    protected $table = 'modelos_diagramaticos';
    protected $fillable = [
        'nome',
        'descricao',
        'xml_modelo',
        'codprojeto',
        'codrepositorio',
        'codusuario',
        'visibilidade',
        'publico'
    ];


    public static function titulos()
    {
        return [
            'Modelos',
            'Ações'
        ];
    }

    public static function campos()
    {
        return [
            'Nome',
            'Descrição'
        ];
    }

    public static function types()
    {
        return [
            'text',
            'text'
        ];
    }

    public static function atributos()
    {
        return [
            'nome',
            'descricao',
            'tipo',
            'codprojeto',
            'codrepositorio',
            'xml_modelo'

        ];

    }

//Instancia todas as posições de memória que serão exibidas no título
    public static function dados_objeto()
    {
        $dado = array();
        for ($indice = 0; $indice < 3; $indice++) {
            $dado[$indice] = new Dado();
        }
        return $dado;
    }

//Instancia somente os campos que serão exibidos no formulário e preenche os títulos da listagem
    public static function dados()
    {
        $campos = self::campos();
        $atributos = self::atributos();
        $dados = self::dados_objeto();
        $titulos = self::titulos();
        $types = self::types();
        //quantidade de atributos
        for ($indice = 0; $indice < 3; $indice++) {
            //quantidade do restante dos campos
            if ($indice < 2) {
                $dados[$indice]->campo = $campos[$indice];
                $dados[$indice]->type = $types[$indice];
                $dados[$indice]->titulo = $titulos[$indice];
            }
            $dados[$indice]->atributo = $atributos[$indice];


        }
        return $dados;
    }

//Relacionamentos
    public function projeto()
    {
        return $this->hasOne(Projeto::class, 'codprojeto', 'codprojeto');
    }

    public function repositorio()
    {
        return $this->hasOne(Repositorio::class, 'codrepositorio', 'codrepositorio');
    }

    public static function validacao()
    {
        return [
            'nome' => 'required',
            'descricao' => 'required',
            'tipo' => 'required',
        ];
    }


    protected static function boot()
    {
        parent::boot();


    }



    public function usuario()
    {
        return $this->hasOne(User::class, 'codusuario', 'codusuario');
    }
}