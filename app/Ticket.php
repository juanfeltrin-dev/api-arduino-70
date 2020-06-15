<?php


namespace App;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    /**
     * @var string
     */
    protected $table = 'ticket';

    /**
     * @var string
     */
    protected $primaryKey = 'idticket';

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var array
     */
    protected $fillable = [
        'idvaga',
        'dhemissao',
        'dhentrada',
        'dhpagamento',
        'dhsaida',
        'dtfinalizado',
        'tipo',
        'valor',
        'idformapgto',
        'placa',
    ];
}
