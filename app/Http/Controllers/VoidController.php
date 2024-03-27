<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Add this line
use App\Models\Supplier;
use App\Models\Agent;
use App\Models\Type;
use App\Models\Ticket;
use App\Models\Order;
use App\Models\VoidTicket;
use Illuminate\View\View;
use DateTime;
use Illuminate\Support\Facades\DB;

class VoidController extends Controller
{
    public function view()
    {
        $user = Auth::id();
        $void_ticket = VoidTicket::where([])->paginate(10);
        return view('ticket.void');
    }
    // public function void_entry(Request $request){
    //     // dd($request->all());
    //     try{
    //         DB::beginTransaction();
    //         $void = new VoidTicket();
    //         $void->ticket_no = $request->ticket;
    //         $void->date = $request->refund_date;
    //         $void->agent = $request->agent;
    //         $void->supplier = $request->supplier;
    //         $void->prev_agent_amount = $request->agent_fare;
    //         $void->prev_supply_amount = $request->supplier_fare;
    //         $void->now_agent_fere = $request->agent_refundfare;
    //         $void->now_supplier_fare = $request->supplier_refundfare;
    
    //         $agentRefundFare = $request->input('agent_refundfare');
    //         $supplierRefundFare = $request->input('supplier_refundfare');
    //         $profit = $agentRefundFare - $supplierRefundFare;
    
    //         $void->void_profit = $profit;
    
    
    //         $ticket = Ticket::where(['ticket_no' => $request->ticket])->first();
    
    //         if ($ticket) {

    //             $ticket->is_void = 1;
    //             $ticket->void_profit = $profit;
    
    //             $agent = Agent::where('id', $request->agent)->first();
    //             $agent->amount -= $request->agent_fare;
    
    //             $supplier = Supplier::where('id', $request->supplier)->first();
    //             $supplier->amount -= $request->supplier_fare;

    //             $flag = $ticket->save() && $agent->save() && $supplier->save();
    //             DB::commit();

    //         } else {
    //             // Ticket not found, handle accordingly (e.g., show an error message)
    //             $flag = false;
    //         }
    //     }
    //     catch (\Exception $e) {
    //         DB::rollBack();
        
    //         // Log the error or handle it as needed
    //         return redirect()->back()->with('error', 'Error adding tickets: ' . $e->getMessage());
    //     }
      

       
    //     if($flag){
    //         return redirect()->route('ticket.void')->with('success', 'Void Ticket Added successfully');
    //     }
    //     else{
    //         return redirect()->route('ticket.void')->with('error', 'Adding Void Ticket failed');
    //     }
    // } 

    public function void_entry(Request $request)
    {
        try {
            DB::beginTransaction();

            $flag = $this->voidTicket($request);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            // Log the error or handle it as needed
            return redirect()->back()->with('error', 'Error voiding ticket: ' . $e->getMessage());
        }

        $message = $flag ? 'Void Ticket added successfully' : 'Adding Void Ticket failed';
        $type = $flag ? 'success' : 'error';

        return redirect()->route('void.view')->with($type, $message);
    }

    private function voidTicket(Request $request)
    {
        $ticket = Ticket::where('ticket_no', $request->ticket)->first();

        if (!$ticket) {
            return false; // Ticket not found
        }
        // dd($request->all());
        $voidticket = new VoidTicket();
        $voidticket->ticket_no = $request->ticket;
        $voidticket->ticket_code = $request->ticket_code;
        $voidticket->date = now();
        $voidticket->agent = $request->agent;
        $voidticket->supplier = $request->supplier;
        $voidticket->prev_agent_amount = $request->agent_fare;
        $voidticket->prev_supply_amount = $request->supplier_fare;
        $voidticket->now_agent_fere = $request->agent_refundfare;
        $voidticket->now_supplier_fare = $request->supplier_refundfare;
        $voidticket->user = Auth::id();

        $agentRefundFare = $request->input('agent_refundfare');
        $supplierRefundFare = $request->input('supplier_refundfare');

        $profit = $agentRefundFare - $supplierRefundFare;

        $voidticket->void_profit = $profit;

        $agent = Agent::where('id', $request->agent)->first();
        $agent_prev_tk = $agent->amount;
        $agent_now_tk =   ($agent_prev_tk - $request->agent_fare)+$request->agent_refundfare;


        $supplier = Supplier::where('id', $request->supplier)->first();
        $supplier_prev_tk = $supplier->amount;
        $supplier_now_tk = $request->supplier_refundfare + ($supplier_prev_tk - $request->supplier_fare);


        $voidticket->prev_agent_tk = $agent_prev_tk;
        $voidticket->prev_supplier_tk = $supplier_prev_tk;
        $voidticket->now_agent_amount = $agent_now_tk;
        $voidticket->now_supplier_amount = $supplier_now_tk;

     
        $ticketParams = [
            'voidTicket' => $voidticket,
            'ticket' => $ticket,
            'agentFare' => $request->agent_fare,
            'supplierFare' => $request->supplier_fare,
            'agentId' => $request->agent,
            'supplierId' => $request->supplier,
            'profit' => $profit,
            'agentRefundFare' => $request->agent_refundfare,
            'supplierRefundFare' => $request->supplier_refundfare,
        ];
        
        $flag = $this->updateTicket($ticketParams);
        
        return $flag;
    }

    private function updateTicket(array $params)
    {
        $voidticket = $params['voidTicket'];
        $ticket = $params['ticket'];
        $agentFare = $params['agentFare'];
        $supplierFare = $params['supplierFare'];
        $agent = $params['agentId'];
        $supplier = $params['supplierId'];
        $profit = $params['profit'];
        $agentRefundFare = $params['agentRefundFare'];
        $supplierRefundFare = $params['supplierRefundFare'];

       
        // Your existing logic for updating ticket, agent, and supplier
        $ticket->is_void = 1;
        $ticket->void_profit = $profit;

        $agent = Agent::where('id', $agent)->first();
        $agent->amount -= $agentFare;
        $agent->amount += $agentRefundFare;
        
        $supplier = Supplier::where('id', $supplier)->first();
        $supplier->amount -= $supplierFare;
        $supplier->amount += $supplierRefundFare;

        return $ticket->save() && $agent->save() && $supplier->save() &&  $voidticket->save();
    }

}