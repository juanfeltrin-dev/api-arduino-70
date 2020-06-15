<?php


namespace App;

use Illuminate\Database\Eloquent\Model;

class Vacancy extends Model
{
    /**
     * @var string
     */
    protected $table = 'vaga';

    /**
     * @var string
     */
    protected $primaryKey = 'idvaga';

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var array
     */
    protected $fillable = [
        'distancia',
        'tipo',
        'situacao',
        'idticket',
    ];

    public function getVacancyNearest($tipo)
    {
        return self::where([
            'situacao'  => 'LIVRE',
            'tipo'      => $tipo
        ])
        ->orderBy('distancia')
        ->first();
    }
}
