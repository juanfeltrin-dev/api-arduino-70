<?php

namespace App\Http\Controllers;

use App\Ticket;
use App\Value;
use App\Vacancy;
use Illuminate\Http\Request;

class VacancyController extends Controller
{
    public function entering(Request $request)
    {
        $this->validate($request, [
            'tipo'  => 'required|string',
            'placa' => 'required|string'
        ]);

        $vacancy        = new Vacancy();
        $vacancyNearest = $vacancy->getVacancyNearest($request->tipo);
        
        if($request->tipo == 'ESPECIAL' && is_null($vacancyNearest)) {
            $vacancyNearest = $vacancy->getVacancyNearest('NORMAL');
        }

        if(is_null($vacancyNearest)) {
            return response()->json(["error" => "Nenhuma vaga livre"], 401);
        }

        $dataTicket = [
            'idvaga'    => $vacancyNearest->IDVAGA,
            'dhemissao' => date("Y-m-d H:i:s"),
            'tipo'      => $request->tipo,
            'placa'     => $request->placa
        ];

        $ticket = Ticket::create($dataTicket);

        Vacancy::where('idvaga', $vacancyNearest->IDVAGA)->update([
            'situacao' => 'OCUPADA',
            'idticket' => $ticket->idticket
        ]);

        Ticket::where('idticket', $ticket->idticket)->update([
            'dhentrada' => date("Y-m-d H:i:s")
        ]);

        return response()->json($ticket->idticket, 200);
    }

    public function goingOut(Request $request)
    {
        $ticket = Ticket::whereNull('dhsaida')->whereNotNull('dhentrada')->orderBy('idticket', 'desc')->first();

        if(is_null($ticket)) {
            return response()->json(["error" => "Nenhum ticket disponivel"], 401);
        }

        $value = new Value();
        $value->payment($ticket);

        Ticket::where('idticket', $ticket->IDTICKET)->update([
            'dhsaida' => date("Y-m-d H:i:s")
        ]);

        Ticket::where('idticket', $ticket->IDTICKET)->update([
            'dtfinalizado' => date("Y-m-d H:i:s")
        ]);

        Vacancy::where('idvaga', $ticket->IDVAGA)->update([
            'situacao' => 'LIVRE',
            'idticket' => 0,
        ]);


        return response()->json("", 200);
    }
}
