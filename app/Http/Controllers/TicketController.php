<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Add this line
use App\Models\Supplier;
use App\Models\Agent;
use App\Models\AIT;
use App\Models\Receiver;
use App\Models\Type;
use App\Models\Ticket;
use App\Models\Order;
use App\Models\Refund;
use Illuminate\View\View;
use DateTime;
use Illuminate\Support\Facades\DB;

class TicketController extends Controller
{
    public function index()
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
        return view('ticket/index', compact('suppliers', 'agents', 'types', 'tickets'));
    }

    public function searchAirline(Request $request)
    {
        $ticketCode = $request->input('ticketCode');
        
        $find = DB::table('airlines')->where('ID', $ticketCode)->first();
        if($find){
            return response()->json(['message' => 'Success', 'airline' => $find]);
        }
        return response()->json(['message' => 'Failed No such AirLine']);
    }
    
    private function allValuesExist($array) {
        foreach ($array as $value) {
            if (!isset($value) || empty($value)) {
                return false;
            }
        }
        return true;
    }
    public function store(Request $request)
    {
        // dd($request->all());
        $ticketNoKeys = array_keys($request['ticket_no']);
        $passengerNameKeys = array_keys($request['passenger_name']);
        $user = Auth::id();

        $flag = false;
        // Check if the keys in "passenger_name" are the same as in "ticket_no" and if they have values
        if ($ticketNoKeys === $passengerNameKeys && $this->allValuesExist($request['passenger_name'])) {
            // dd($request->all());
            $count = 0;
            
        try {
            // Start a database transaction
            DB::beginTransaction();
            foreach ($request['ticket_no'] as $index => $ticketNumber) {
                // dd($request['passenger_name'][$index], $ticketNumber);
                $count += 1;
                $ticket = new Ticket();
                $ticket->flight_date = $request['flight_date'];
                $ticket->invoice_date = $request['invoice_date'];
                $ticket->invoice = $request['invoice_no'];
                $ticket->ticket_no = $ticketNumber;
                $ticket->sector = $request['sector'];
                $ticket->stuff = $request['stuff'];
                                
                // Split the sector into parts
                $sector = $request['sector'];
                $parts = explode('-', $sector);

                // Extract the first and last parts
                $firstPart = $parts[0];
                $lastPart = end($parts);

                $ticket->s_from = $firstPart;
                $ticket->e_to = $lastPart;

                $ticket->passenger = $request['passenger_name'][$index];
                $ticket->airline_name = $request['airlines_name'];
                $ticket->airline_code = $request['airlines_code'];
                $ticket->ticket_code = $request['ticket_code'];
                $ticket->agent = $request['agent'];
                $ticket->supplier = $request['supplier'];
                $ticket->agent_price = $request['agent_price'];
                $ticket->supplier_price = $request['supplier_price'];
                $ticket->flight_no = $request['flight_no'];
                $ticket->remark = $request['remark'];
                $profit = floatval($request['agent_price']) - floatval($request['supplier_price']);
                $ticket->profit = $profit;
                $ticket->user = $user;

                
                $agent_acc = Agent::find($request['agent']);
                $agent_previous_amount = $agent_acc->amount;
                $agent_new_amount = floatval($agent_previous_amount) + floatval($request['agent_price']);
                $agent_acc->amount = $agent_new_amount;
                $agent_acc->save();

                $ticket->agent_previous_amount = $agent_previous_amount;
                $ticket->agent_new_amount = $agent_new_amount;

                $supplier = Supplier::find($request['supplier']);
                $supplier_prev_amount = $supplier->amount;
                $supplier_amount = $count * (float)$request['supplier_price'];
                $supplier_new_amount = $supplier_prev_amount + $supplier_amount;
                // $agent->amount += $agent_amount;
                // $agent->save();
        
                $supplier->amount += $supplier_amount;
                $supplier->save();

                $ticket->supplier_prev_amount = $supplier_prev_amount;
                $ticket->supplier_new_amount = $supplier_new_amount;


                $flag = $ticket->save();

                
            }
            // $agent = Agent::where('id',$request['agent'])->first();
            // $agent_amount = $count * parseFloat($request['agent_price']);

            // $supplier = Supplier::where('id',$request['supplier'])->first();
            // $supplier_amount = $count * parseFloat($request['supplier_price']);

            // $agent_prev_amount = $agent->amount;
            // $agent_new_amount = parseFloat($agent_prev_amount) + $agent_amount;
            // $agent->amount = $agent_new_amount;
            // $agent->save();

                if($flag)
                {
                
                    // $agent = Agent::find($request['agent']);
                    // $agent_amount = $count * (float)$request['agent_price'];
            
                  

                    if($request['ait']){

                        $ait = new AIT();
                        $ait->ticket_invoice = $request['invoice_no'];
                        $ait->ait_amount = $request['ait_amount'];
                        $ait->total_amount = $request['ait'] * $count;
                        $ait->sector = $request['sector'];
                        $ait->user = $user;
                        $ait->airline_name = $request['airlines_name'];

                        $ait->save();
                    }
            
                    // Commit the transaction
                    DB::commit();
                    return redirect()->route('ticket.view')->with('success', 'Tickets added successfully');
                }
                else{
                    return redirect()->route('ticket.view')->with('error', 'Something went wrong');
                }
            
            }
            catch (\Exception $e) {
                // Something went wrong, rollback the transaction
                DB::rollBack();
            
                // Log the error or handle it as needed
                return redirect()->back()->with('error', 'Error adding tickets: ' . $e->getMessage());
            }
        }
        
    }
    public function store_single(Request $request)
    {
        // dd($request->all());
        $user = Auth::id();

       
        try {
            // Start a database transaction
            // dd($request->all());

            DB::beginTransaction();
            // dd($request->all());

                
                $ticket = new Ticket();
                $ticket->flight_date = $request['flight_date'];
                $ticket->invoice_date = $request['invoice_date'];
                $ticket->invoice = $request['invoice_no'];
                $ticket->ticket_no = $request['ticket_no'];
                $ticket->sector = $request['sector'];
                // Split the sector into parts
                $sector = $request['sector'];
                $parts = explode('-', $sector);

                // Extract the first and last parts
                $firstPart = $parts[0];
                $lastPart = end($parts);

                $ticket->s_from = $firstPart;
                $ticket->e_to = $lastPart;

                $ticket->stuff = $request['stuff'];
                $ticket->passenger = $request['passenger_name'];
                $ticket->airline_name = $request['airlines_name'];
                $ticket->airline_code = $request['airlines_code'];
                $ticket->ticket_code = $request['ticket_code'];
                $ticket->agent = $request['agent'];
                $ticket->supplier = $request['supplier'];
                $ticket->agent_price = $request['agent_price_1'];
                $ticket->supplier_price = $request['supplier_price'];
                $ticket->flight_no = $request['flight_no'];
                $ticket->remark = $request['remark'];
                $ticket->discount = $request['discount'];
                $profit = floatval($request['agent_price_1']) - floatval($request['supplier_price']);
                $ticket->profit = $profit;
                $ticket->user = $user;

                // dd($ticket);

                $agent_acc = Agent::find($request['agent']);
                $agent_previous_amount = $agent_acc->amount;
                $agent_new_amount = floatval($agent_previous_amount) + floatval($request['agent_price_1']);
                $agent_acc->amount = $agent_new_amount;

                $supplier_acc = Supplier::find($request['supplier']);
                $supplier_prev_amount = $supplier_acc->amount;
                $supplier_new_amount = floatval($supplier_prev_amount) + floatval($request['supplier_price']);
                $supplier_acc->amount = $supplier_new_amount;
               

                $ticket->agent_previous_amount = $agent_previous_amount;
                $ticket->agent_new_amount = $agent_new_amount;
                $ticket->supplier_prev_amount = $supplier_prev_amount;
                $ticket->supplier_new_amount = $supplier_new_amount;

                // dd($ticket);
                $flag = false;
                $flag = $ticket->save();

                dd($flag);

                if($flag)
                {
                
                    $agent_acc->save();
                    $supplier_acc->save();

                    if($request['ait']){
                        
                        $ait = new AIT();
                        $ait->ticket_invoice = $request['invoice_no'];
                        $ait->ait_amount = $request['ait_amount'];
                        $ait->total_amount = $request['ait'];
                        $ait->sector = $request['sector'];
                        $ait->user = $user;
                        $ait->airline_name = $request['airlines_name'];

                        $ait->save();
                    }
            
                    // Commit the transaction
                    DB::commit();
                    return redirect()->route('ticket.view')->with('success', 'Tickets added successfully');
                }
                else{
                    return redirect()->route('ticket.view')->with('error', 'Something went wrong');
                }
            
            }
            catch (\Exception $e) {
                // Something went wrong, rollback the transaction
                DB::rollBack();
            
                // Log the error or handle it as needed
                return redirect()->back()->with('error', 'Error adding tickets: ' . $e->getMessage());
            }
        
        
    }

    public function edit($id)
    {
        // $id = decrypt($id);
        $user = Auth::id();
        $ticket = Ticket::findOrFail($id);
        $suppliers = Supplier::where([['is_delete',0],['is_active',1],['user',$user]])->get();
        $agents = Agent::where([['is_delete',0],['is_active',1],['user',$user]])->get();
        return view('ticket.edit', compact('ticket', 'suppliers', 'agents'));
    }
    
    public function update(Request $request)
    {
        // dd($request->all());
        if($request['ticket_id'] != null){
            $ticket = Ticket::findOrFail($request['ticket_id']); 
            $ticket->agent = $request['agent'];
            $ticket->supplier = $request['supplier'];
            $ticket->invoice_date = $request['invoice_date'];
            $ticket->stuff = $request['stuff'];
            $ticket->flight_date = $request['flight_date'];
            $ticket->sector = $request['sector'];
            $ticket->flight_no = $request['flight_no'];
            $ticket->passenger = $request['passenger_name'];
            $ticket->agent_price = $request['agent_price'];
            $ticket->supplier_price = $request['supplier_price'];
            $ticket->airline_code = $request['airlines_code'];
            $ticket->airline_name = $request['airlines_name'];
            $ticket->ticket_code = $request['ticket_code'];
            $ticket->ticket_no = $request['ticket_no'];
            $ticket->discount = $request['discount'];
            $ticket->remark = $request['remark'];

            $flag = $ticket->save();
            if($flag){
                return redirect()->route('ticket.view')->with('Success', 'Ticket updated ');
            }
        }
        
        return redirect()->route('ticket.view')->with('error', 'Ticket updated failed');
    }

    public function delete($id)
    {
        $ticket = Ticket::findOrFail($id);
        $ticket->is_delete = 1;
        if($ticket->save()){
            return redirect()->route('ticket.view')->with('success', 'Ticket deleted successfully');
        }
        else{
            return redirect()->route('ticket.view')->with('error', 'Ticket deleted failed');
        }
        return redirect()->route('ticket.view')->with('error', 'Ticket deleted failed');
        
    }

    public function view($id){
        $ticket = Ticket::findOrFail($id); 
        $agent = Agent::where('id', $ticket->agent)->value('name');
        return view('ticket.view', compact('ticket', 'agent'));
    }

    // public function refundindex(){
    //     return view('ticket.refund');
    // }
    public function refundindex()
    {
        $user = Auth::id();
        $refund_ticket = Refund::paginate(10);
        return view('ticket.refund', compact('refund_ticket'));
    }

    public function searchTicket(Request $request){
        $ticketNumber = $request->ticketNumber;
        $ticket = Ticket::where('ticket_no', $ticketNumber)->first();

        if ($ticket) {
            // Ticket found
            $agent = Agent::where('id', $ticket->agent)->value('name');
            $supplier = Supplier::where('id', $ticket->supplier)->value('name');
            return response()->json(['status' => 'success', 'ticket' => $ticket, 'agent' => $agent, 'supplier' => $supplier]);
        } else {
            // Ticket not found
            return response()->json(['status' => 'error', 'message' => 'Ticket not found']);
        }
    }

    public function receiveAmount(Request $request)
    {
        // $receiver = DB::table($request->agent_supplier)->where('id', $request->agent_supplier_id)->first();
        $tableName = $request->agent_supplier;

        $receiver = DB::table($tableName)->where('id', $request->agent_supplier_id)->first();
    
        if (!$receiver) {
            return response()->json(['error' => 'Receiver not found'], 404);
        }
    
        $receiver_previous_amount = $receiver->amount;
        $current_amount = floatval($receiver->amount) - floatval($request->amount);
    
      
        // Retrieve previous and current amounts
        $receiver_previous_amount = $receiver->amount;
        $current_amount = floatval($receiver->amount) - floatval($request->amount);

        // Create a new instance of the Receiver model
        $receiver_mdl = new Receiver();

        // Set properties for the Receiver model
        // dd($request->all());
        $receiver_mdl->receive_from = $request->agent_supplier;
        $receiver_mdl->agent_supplier_id = $request->agent_supplier_id;
        $receiver_mdl->method = $request->payment_mode;
        $receiver_mdl->remark = $request->remark;
        $receiver_mdl->invoice = $request->reff_no;
        $receiver_mdl->amount = $request->amount;
        $receiver_mdl->user = Auth::id();
        $receiver_mdl->date = now()->format('Y-m-d'); // Use Laravel's now() for date formatting
        $receiver_mdl->previous_amount = $receiver_previous_amount;
        $receiver_mdl->current_amount = $current_amount;

        // Save the new Receiver record
        $receiver_mdl->save();

        // $receiver->amount = $current_amount;
        // $receiver->save();
          // Update the record in the database
        DB::table($tableName)
          ->where('id', $request->agent_supplier_id)
          ->update(['amount' => $current_amount]);


        // Optionally, you might want to return a response indicating success or failure
        return response()->json(['message' => 'Amount received successfully']);
    }


    public function getAgentSupplier(Request $request){
        $who = $request->input('who');
        $allowedTables = ['agent', 'supplier']; // Update with your allowed table names
        if (!in_array($who, $allowedTables)) {
            return response()->json(['error' => 'Invalid table name'], 400);
        }
    
        $list_all = DB::table($who)
                        ->where('is_delete', 0)
                        ->where('is_active', 1)
                        ->where('user', Auth::id())
                        ->get();
    
        return response()->json($list_all);
    }

    public function getlastid(){
        try {
            $lastId = Ticket::latest('id')->value('id');
            return response()->json(['lastId' => $lastId]);
        } catch (\Exception $e) {
            // Handle any exceptions that might occur during the process
            return response()->json(['error' => 'Error fetching last ID'], 500);
        }     
    }
    public function deportee() {
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
        return view('deportee.index', compact('suppliers', 'agents', 'types', 'tickets'));
    }
    
    
}

?>