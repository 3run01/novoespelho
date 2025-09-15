<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Portaria extends Model
{
    protected $connection = 'ediario';
    protected $table = 'portaria.portarias';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [

        'fk_tipo_port',
        'fk_assunto',
        'fk_status',
        'fk_signatario',
        'id_port_vinc',
        'num_seq',
        'numero',
        'ano',
        'processo',
        'data_criacao',
        'data_publicacao',
        'texto_portaria',
        'id_categoria',
        'fk_pessoa_pj',
        'fk_pessoa_pjef',
        'fk_pessoa_pjei',
        'fk_pessoa_pjs',
        'conteudo_temp',
        'mes',
        'data_publica_diario',
        'status_temp',
        'fk_plantao',
        'status_requisicao',
        'criado_por',
        'editado_por',
        'exonera_orgao'
    ];

    public function rules()
    {
    	return [

    		'fk_tipo_port' => 'required',
            'fk_assunto1' => 'required',
            'fk_signatario' => 'required',
            'ano' => 'required',
            'data_publicacao' => 'required',
            'texto_portaria' => 'required',
    	];
    }

    public function messages()
    {
    	return [

            'fk_tipo_port.required' => 'Campo <b>Tipo Portaria</b> é obrigatório',
            'fk_assunto1.required' => 'Campo <b>Assunto</b> é obrigatório',
    		'fk_signatario.required' => 'Campo <b>Signatário</b> é obrigatório',
    		'ano.required' => 'Campo <b>Ano Documento</b> é obrigatório',
    		'data_publicacao.required' => 'Campo <b>Data Publicação</b> é obrigatório',
    		'texto_portaria.required' => 'Campo <b>Texto</b> é obrigatório',

    	];
    }

    public function rules2()
    {
        return [

            'fk_tipo_port' => 'required',
            'fk_assunto2' => 'required',
            'fk_signatario' => 'required',
            'ano' => 'required',
            'data_publicacao' => 'required',
            'texto_portaria' => 'required',
        ];
    }

    public function messages2()
    {
        return [

            'fk_tipo_port.required' => 'Campo <b>Tipo Portaria</b> é obrigatório',
            'fk_assunto2.required' => 'Campo <b>Assunto</b> é obrigatório',
            'fk_signatario.required' => 'Campo <b>Signatário</b> é obrigatório',
            'ano.required' => 'Campo <b>Ano Documento</b> é obrigatório',
            'data_publicacao.required' => 'Campo <b>Data Publicação</b> é obrigatório',
            'texto_portaria.required' => 'Campo <b>Texto</b> é obrigatório',

        ];
    }
}