<?php


namespace App;

use Illuminate\Database\Eloquent\Model;

class Value extends Model
{
    /**
     * @var string
     */
    protected $table = 'valor';

    /**
     * @var string
     */
    protected $primaryKey = 'idvalor';

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var array
     */
    protected $fillable = [
        'isento',
        'phora',
        'dhora',
        'dtcriacao'
    ];

    public function payment(Ticket $ticket)
    {
        $value      = $this::orderBy('dtcriacao', 'desc')->first();
        $dhEmissao  = new \DateTime($ticket->DHEMISSAO);
        $now        = new \DateTime();
        $diff       = $dhEmissao->diff($now)->i;

        if($diff < $value->ISENTO) {
            Ticket::where('idticket', $ticket->IDTICKET)->update([
                'dhpagamento'   => date("Y-m-d H:i:s"),
                'valor'         => 0.00,
                'idformapgto'   => 1
            ]);
        }elseif($diff < 60) {
            Ticket::where('idticket', $ticket->IDTICKET)->update([
                'dhpagamento'   => date("Y-m-d H:i:s"),
                'valor'         => $value->PHORA,
                'idformapgto'   => 1
            ]);
        }else {
            $plenyOfTime    = $diff - 60;
            $total          = $value->PHORA + (round($plenyOfTime/60, 2) * $value->DHORA);
            Ticket::where('idticket', $ticket->IDTICKET)->update([
                'dhpagamento'   => date("Y-m-d H:i:s"),
                'valor'         => $total,
                'idformapgto'   => 5
            ]);
        }
    }
}
