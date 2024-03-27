<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Add this line
use App\Models\Supplier;
use App\Models\Agent;
use App\Models\Type;
use App\Models\Order;
use Illuminate\View\View;
use DateTime;

class OrderController extends Controller
{
    public function index()
    {
        $user = Auth::id();
        $suppliers = Supplier::where([['is_delete',0],['is_active',1],['user',$user]])->get();
        $agents = Agent::where([['is_delete',0],['is_active',1],['user',$user]])->get();
        $types = Type::where([['is_delete',0],['is_active',1],['user',$user]])->get();
        $orders = Order::where([['is_delete',0],['is_active',1],['user', $user]])->get();
        // foreach($orders as $order){
        //     $order->type = Type::find($order->type)->value('name');
        //     $order->agent = Agent::find($order->agent)->value('name');
        //     $order->supplier = Supplier::find($order->supplier)->value('name');
        // }
        // dd($orders);
        // dd($suppliers);
        return view('order/index', compact('suppliers', 'agents', 'types', 'orders'));
    }

    public function store(Request $request)
    {
        // dd("d");
        dd($request->all());

       $validatedData = $request->validate([
            'date' => 'required|date',
            'type' => 'required|integer|exists:type,id',
            'agent' => 'required|integer|exists:agent,id',
            'seller' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:15',
            'name' => 'required|string|max:255',
            'passport_no' => 'required|string|max:255',
            'contact_amount' => 'required|numeric',
            'payable_amount' => 'required|numeric',
            'other_expense' => 'nullable|numeric',
            'country' => 'required|string',
            'supplier' => 'required|exists:supplier,id',
            'remark' => 'nullable|string',
            'invoice' => 'required|string|max:255',
        ]);

        if($validatedData){
            // dd($request->all());
            $order = new Order();
            $order->name = $validatedData['name'];
            $order->date = (new DateTime($validatedData['date']))->format('Y-m-d');
            $order->type = $validatedData['type'];
            $order->agent = $validatedData['agent'];
            // $order->seller = $validatedData['seller'];
            // $order->phone = $validatedData['phone'];
            $order->passport_no = $validatedData['passport_no'];
            $order->contact_amount = $validatedData['contact_amount'];
            $order->payable_amount = $validatedData['payable_amount'];
            // $order->other_expense = $validatedData['other_expense'];
            $order->country = $validatedData['country'];
            $order->supplier = $validatedData['supplier'];
            $order->remark = $validatedData['remark'];
            $order->invoice = $validatedData['invoice'];

            if (isset($validatedData['other_expense'])) {
                $profit = $validatedData['contact_amount'] - ($validatedData['payable_amount'] + $validatedData['other_expense']);
            } else {
                $profit = $validatedData['contact_amount'] - $validatedData['payable_amount'];
            }
            
            // Now $profit contains the calculated profit based on the conditions
            $order->profit = $profit;
            $order->user = Auth::id();

            $agent = Agent::where('id', $validatedData['agent'])->get();
            $agent->amount += $validatedData['contact_amount'];

            $supplier = Supplier::where('id', $validatedData['supplier'])->get();
            $supplier->amount += $validatedData['payable_amount'];

            $isdone = $order->save() && $agent->save() && $supplier->save();

            if($isdone){
                return redirect()->route('order.view')->with('success', 'Order added successfully');
            }
        }
        // $validatedData['user'] = Auth::id();
        // Order::create($validatedData);
        return redirect()->route('order.view')->with('error', 'Order is not added');
    }

    public function edit($id)
    {
        $id = decrypt($id);
        $user = Auth::id();
        $suppliers = Supplier::where([['is_delete',0],['is_active',1],['user',$user]])->get();
        $agents = Agent::where([['is_delete',0],['is_active',1],['user',$user]])->get();
        $types = Type::where([['is_delete',0],['is_active',1],['user',$user]])->get();
        $order = Order::findOrFail($id);
        return view('order.edit', compact('order','suppliers','types', 'agents'));
    }
        public function update(Request $request, $id)
        {
            // dd($request->all(), $id);
            $validatedData = $request->validate([
                'date' => 'required|date',
                'type' => 'required|integer|exists:type,id',
                'agent' => 'required|integer|exists:agent,id',
                'seller' => 'nullable|string|max:255',
                'phone' => 'nullable|string|max:15',
                'name' => 'required|string|max:255',
                'passport_no' => 'required|string|max:255',
                'contact_amount' => 'required|numeric',
                'payable_amount' => 'required|numeric',
                'other_expense' => 'nullable|numeric',
                'country' => 'required|string',
                'supplier' => 'required|exists:supplier,id',
                'remark' => 'nullable|string',
                'invoice' => 'required|string|max:255',
            ]);

            if($validatedData){
                $order = Order::findOrFail($id);
                $order->name = $validatedData['name'];
                $order->date = (new DateTime($validatedData['date']))->format('Y-m-d');
                $order->type = $validatedData['type'];
                $order->agent = $validatedData['agent'];
                // $order->seller = $validatedData['seller'];
                // $order->phone = $validatedData['phone'];
                $order->passport_no = $validatedData['passport_no'];
                $order->contact_amount = $validatedData['contact_amount'];
                $order->payable_amount = $validatedData['payable_amount'];
                // $order->other_expense = $validatedData['other_expense'];
                $order->country = $validatedData['country'];
                $order->supplier = $validatedData['supplier'];
                $order->remark = $validatedData['remark'];
                $order->invoice = $validatedData['invoice'];
    
                if (isset($validatedData['other_expense'])) {
                    $profit = $validatedData['contact_amount'] - ($validatedData['payable_amount'] + $validatedData['other_expense']);
                } else {
                    $profit = $validatedData['contact_amount'] - $validatedData['payable_amount'];
                }
                
                // Now $profit contains the calculated profit based on the conditions
                $order->profit = $profit;
                $order->user = Auth::id();
                

                if($order->save()){
                    return redirect()->route('order.view')->with('success', 'Order updated successfully');
                }
                else{
                    return redirect()->route('order.view')->with('error', 'Order updated failed');
                }
            }         

            return redirect()->route('order.view')->with('error', 'Order updated failed');
        }

    public function delete($id)
    {
        $order = Order::findOrFail($id);
        $order->is_delete = 1;
        if($order->save()){
            return redirect()->route('order.view')->with('success', 'Order deleted successfully');
        }
        else{
            return redirect()->route('order.view')->with('error', 'Order deleted failed');
        }
        return redirect()->route('order.view')->with('error', 'Order deleted failed');
        
    }
}

?>