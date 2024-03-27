<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Add this line
use App\Models\Supplier;
use App\Models\Agent;
use App\Models\Type;
use App\Models\Ticket;
use App\Models\Order;
use App\Models\Refund;
use App\Models\ReissueTicket;
use Illuminate\View\View;
use DateTime;
use Illuminate\Support\Facades\DB;

class ReissueController extends Controller
{
    public function view()
    {
        $user = Auth::id();
        $suppliers = Supplier::where([['is_delete',0],['is_active',1],['user',$user]])->get();
        $agents = Agent::where([['is_delete',0],['is_active',1],['user',$user]])->get();
        $types = Type::where([['is_delete',0],['is_active',1],['user',$user]])->get();
        $tickets = Ticket::where([['is_delete',0],['is_active',1],['user', $user]])->get();
        foreach($tickets as $order){
           
            $order->agent = Agent::where('id', $order->agent)->value('name');
            $order->supplier = Supplier::where('id', $order->supplier)->value('name');
        }
        // dd($orders);
        // dd($suppliers);
        return view('ticket.reissue', compact('suppliers', 'agents', 'types', 'tickets'));
    }
    public function reissue_entry(Request $request)
    {
        try {
            DB::beginTransaction();

            $flag = $this->reissueTicket($request);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            // Log the error or handle it as needed
            return redirect()->back()->with('error', 'Error voiding ticket: ' . $e->getMessage());
        }

        $message = $flag ? 'Reissue Ticket added successfully' : 'Adding Reissue Ticket failed';
        $type = $flag ? 'success' : 'error';

        return redirect()->route('reissue.view')->with($type, $message);
    }

    private function reissueTicket(Request $request)
    {
        $ticket = Ticket::where('ticket_no', $request->ticket)->first();

        if (!$ticket) {
            return false; // Ticket not found
        }
        // dd($request->all());
        $reissueticket = new ReissueTicket();
        $reissueticket->ticket_no = $request->ticket;
        $reissueticket->ticket_code = $request->ticket_code;
        $reissueticket->date = $request->reissue_date;
        $reissueticket->agent = $request->agent;
        $reissueticket->supplier = $request->supplier;
        $reissueticket->prev_agent_amount = $request->agent_fare;
        $reissueticket->prev_supply_amount = $request->supplier_fare;
        $reissueticket->now_agent_fere = $request->agent_reissuefare;
        $reissueticket->now_supplier_fare = $request->supplier_reissuefare;
        $reissueticket->user = Auth::id();

        $agent = Agent::where('id', $request->agent)->first();
        // $agent->amount -= $agentFare;
        $agent_prev_tk = $agent->amount;
        $agent_now_tk = $request->agent_reissuefare + $agent_prev_tk;


        $supplier = Supplier::where('id', $request->supplier)->first();
        // $supplier->amount -= $supplierFare;
        $supplier_prev_tk = $supplier->amount;
        $supplier_now_tk = $request->supplier_reissuefar + $supplier_prev_tk;


        $reissueticket->prev_agent_tk = $agent_prev_tk;
        $reissueticket->prev_supplier_tk = $supplier_prev_tk;
        $reissueticket->now_agent_amount = $agent_now_tk;
        $reissueticket->now_supplier_amount = $supplier_now_tk;

        $agentReissueFare = $request->input('agent_reissuefare');
        $supplierReissueFare = $request->input('supplier_reissuefare');
        $profit = $agentReissueFare - $supplierReissueFare;

        $reissueticket->reissue_profit = $profit;

     
        $ticketParams = [
            'reissueTicket' => $reissueticket,
            'ticket' => $ticket,
            'agentFare' => $request->agent_fare,
            'supplierFare' => $request->supplier_fare,
            'agentId' => $request->agent,
            'supplierId' => $request->supplier,
            'profit' => $profit,
            'agentReissueFare' => $request->agent_reissuefare,
            'supplierReissueFare' => $request->supplier_reissuefare,
        ];
        
        $flag = $this->updateTicket($ticketParams);
        
        return $flag;
    }

    private function updateTicket(array $params)
    {
        $reissueticket = $params['reissueTicket'];
        $ticket = $params['ticket'];
        $agentFare = $params['agentFare'];
        $supplierFare = $params['supplierFare'];
        $agent = $params['agentId'];
        $supplier = $params['supplierId'];
        $profit = $params['profit'];
        $agentReissueFare = $params['agentReissueFare'];
        $supplierReissueFare = $params['supplierReissueFare'];

        $reissueticket->save();
        // Your existing logic for updating ticket, agent, and supplier
        $ticket->is_reissue = 1;
        $ticket->reissue_profit = $profit;

        $agent = Agent::where('id', $agent)->first();
        // $agent->amount -= $agentFare;
        $agent->amount += $agentReissueFare;

        $supplier = Supplier::where('id', $supplier)->first();
        // $supplier->amount -= $supplierFare;
        $supplier->amount += $supplierReissueFare;

        return $ticket->save() && $agent->save() && $supplier->save();
    }

}