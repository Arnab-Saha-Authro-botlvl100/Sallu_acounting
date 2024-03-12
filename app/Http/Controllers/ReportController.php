<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Supplier;
use App\Models\Agent;
use App\Models\AIT;
use App\Models\Type;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Receiver;
use App\Models\Ticket;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth; // Add this line
use DateTime;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        $user = Auth::id();
        $suppliers = Supplier::where([['is_delete', 0], ['is_active', 1], ['user', $user]])->get();
        $agents = Agent::where([['is_delete', 0], ['is_active', 1], ['user', $user]])->get();
        $types = Type::where([['is_delete', 0], ['is_active', 1], ['user', $user]])->get();
        $orders = Order::where([['is_delete', 0], ['is_active', 1], ['user', $user]])->get();
        // dd($suppliers);
        return view('report.index', compact('suppliers', 'agents', 'types'));
    }
    public function generate(Request $request)
    {
        // dd($request->all());
        $type = $request->input('type') ?? null;
        $agent = $request->input('agent') ?? null;
        $supplier = $request->input('supplier') ?? null;
        $start_date = $request->input('start_date') ?? null;
        $end_date = $request->input('end_date') ?? null;

        $show_profit = $request->input('show_profit') ?? null;
        $show_supplier = $request->input('show_supplier') ?? null;

        // dd($type, $agent, $supplier, $start_date, $end_date, $show_profit, $show_supplier);

        if ($start_date) {
            $start_date = (new DateTime($start_date))->format('Y-m-d');
        }
        if ($end_date) {
            $end_date = (new DateTime($end_date))->format('Y-m-d');
        }
        $user = Auth::id();

        $query = DB::table('order')
            ->where([
                ['is_active', 1],
                ['is_delete', 0],
                ['user', $user],
            ]);
        if ($type !== null) {
            $query->where('type', $type);
        }

        if ($agent !== null) {
            $query->where('agent', $agent);
        }

        if ($supplier !== null) {
            $query->where('supplier', $supplier);
        }

        if ($start_date !== null && $end_date !== null) {
            $query->whereBetween('date', [$start_date, $end_date]);
        }
        $alldata = $query->get();

        // dd($alldata, $supplier, $agent);
        $htmlTable = '';
        if ($show_profit != null || $show_supplier != null) {


            if ($show_profit != null && $show_supplier == null) {
                $htmlTable = '<table border="1">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Type</th>
                        <th>Agent</th>
                        <th>Date</th>
                        <th>Seller</th>
                        <th>Passport</th>
                        <th>Country</th>
                        <th>Remark</th>
                        <th>Profit</th>
                        <!-- Add more columns as needed -->
                    </tr>
                </thead>
                <tbody>';

                // Loop through each record in $alldata and add a row to the table
                foreach ($alldata as $data) {
                    $htmlTable .= '<tr>
                        <td>' . $data->invoice . '</td>
                        <td>' . Type::where('id', $data->type)->value('name') . '</td>
                        <td>' . Agent::where('id', $data->agent)->value('name') . '</td>
                        <td>' . (new DateTime($data->date))->format('d-m-Y') . '</td>
                        <td>' . $data->seller . '</td>
                        <td>' . $data->passport_no . '</td>
                        <td>' . $data->country . '</td>
                        <td>' . $data->remark . '</td>
                        <td>' . $data->profit . '</td>
                        <!-- Add more cells as needed -->
                    </tr>';
                }

                // Close the HTML table
                $htmlTable .= '</tbody></table>';
            } elseif ($show_supplier != null && $show_profit == null) {
                $htmlTable = '<table border="1">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Type</th>
                        <th>Agent</th>
                        <th>Supplier</th>
                        <th>Date</th>
                        <th>Seller</th>
                        <th>Passport</th>
                        <th>Country</th>
                        <th>Remark</th>
                        <!-- Add more columns as needed -->
                    </tr>
                </thead>
                <tbody>';

                // Loop through each record in $alldata and add a row to the table
                foreach ($alldata as $data) {
                    $htmlTable .= '<tr>
                        <td>' . $data->invoice . '</td>
                        <td>' . Type::where('id', $data->type)->value('name') . '</td>
                        <td>' . Agent::where('id', $data->agent)->value('name') . '</td>
                        <td>' . Supplier::where('id', $data->supplier)->value('name') . '</td>
                        <td>' . (new DateTime($data->date))->format('d-m-Y') . '</td>
                        <td>' . $data->seller . '</td>
                        <td>' . $data->passport_no . '</td>
                        <td>' . $data->country . '</td>
                        <td>' . $data->remark . '</td>
                        <!-- Add more cells as needed -->
                    </tr>';
                }

                // Close the HTML table
                $htmlTable .= '</tbody></table>';
            } elseif ($show_supplier != null && $show_profit != null) {
                $htmlTable = '<table border="1">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Type</th>
                        <th>Agent</th>
                        <th>Supplier</th>
                        <th>Date</th>
                        <th>Seller</th>
                        <th>Passport</th>
                        <th>Country</th>
                        <th>Remark</th>
                        <th>Profit</th>
                        <!-- Add more columns as needed -->
                    </tr>
                </thead>
                <tbody>';

                // Loop through each record in $alldata and add a row to the table
                foreach ($alldata as $data) {
                    $htmlTable .= '<tr>
                        <td>' . $data->invoice . '</td>
                        <td>' . Type::where('id', $data->type)->value('name') . '</td>
                        <td>' . Agent::where('id', $data->agent)->value('name') . '</td>
                        <td>' . Supplier::where('id', $data->supplier)->value('name') . '</td>
                        <td>' . (new DateTime($data->date))->format('d-m-Y') . '</td>
                        <td>' . $data->seller . '</td>
                        <td>' . $data->passport_no . '</td>
                        <td>' . $data->country . '</td>
                        <td>' . $data->remark . '</td>
                        <td>' . $data->profit . '</td>
                        <!-- Add more cells as needed -->
                    </tr>';
                }

                // Close the HTML table
                $htmlTable .= '</tbody></table>';
            }
        } else {
            $htmlTable = '<table border="1">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Type</th>
                    <th>Agent</th>
                    <th>Date</th>
                    <th>Seller</th>
                    <th>Passport</th>
                    <th>Country</th>
                    <th>Remark</th>
                    <!-- Add more columns as needed -->
                </tr>
            </thead>
            <tbody>';

            // Loop through each record in $alldata and add a row to the table
            foreach ($alldata as $data) {
                $htmlTable .= '<tr>
                    <td>' . $data->invoice . '</td>
                    <td>' . Type::where('id', $data->type)->value('name') . '</td>
                    <td>' . Agent::where('id', $data->agent)->value('name') . '</td>
                    <td>' . (new DateTime($data->date))->format('d-m-Y') . '</td>
                    <td>' . $data->seller . '</td>
                    <td>' . $data->passport_no . '</td>
                    <td>' . $data->country . '</td>
                    <td>' . $data->remark . '</td>
                    <!-- Add more cells as needed -->
                </tr>';
            }

            // Close the HTML table
            $htmlTable .= '</tbody></table>';
        }

        return $htmlTable;
    }

    public function general_ledger()
    {
        $user = Auth::id();
        $suppliers = Supplier::where([['is_delete', 0], ['is_active', 1], ['user', $user]])->get();
        $agents = Agent::where([['is_delete', 0], ['is_active', 1], ['user', $user]])->get();
        return view('report.general_ledger.index', compact('agents', 'suppliers'));
    }


    public function general_ledger_report(Request $request)
    {
        $who = $request->agent_supplier;
        $html = '';

        if ($who == 'agent') {
            $start_date = $request->start_date;
            $end_date = $request->end_date;
            $id = $request->agent_supplier_id;

            $receive = Ticket::where('agent', $id);

            if (!is_null($start_date) || !is_null($end_date)) {
                $start_date = (new DateTime($start_date))->format('Y-m-d');
                $end_date = (new DateTime($end_date))->format('Y-m-d');

                $receive->where(function ($query) use ($start_date, $end_date) {
                    if (!is_null($start_date)) {
                        $query->where('invoice_date', '>=', $start_date);
                    }

                    if (!is_null($end_date)) {
                        $query->where('invoice_date', '<=', $end_date);
                    }
                });
            }

            $receive = $receive->get();

            $receiver = Receiver::where([
                ['receive_from', '=', 'agent'],
                ['agent_supplier_id', '=', $id]
            ]);

            if (!is_null($start_date) || !is_null($end_date)) {
                $start_date = (new DateTime($start_date))->format('Y-m-d');
                $end_date = (new DateTime($end_date))->format('Y-m-d');

                $receiver->where(function ($query) use ($start_date, $end_date) {
                    if (!is_null($start_date)) {
                        $query->where('date', '>=', $start_date);
                    }

                    if (!is_null($end_date)) {
                        $query->where('date', '<=', $end_date);
                    }
                });
            }

            $receiver = $receiver->get();

            $mergedCollection = $receive->merge($receiver);
            $sortedCollection = $mergedCollection->sortBy('created_at');

            $html = '
                        
                            
                            <main class="flex-1 mx-auto max-w-7xl px-10">
                            <div class="buttons justify-end flex gap-3 shadow-lg p-5 ">
                                <button class="text-white bg-pink-600 font-bold text-md py-2 px-4">Send</button>
                                <button id="printBtn" class="text-white bg-blue-700 font-bold text-md py-2 px-4">Print</button>
                                <button class="text-white bg-green-600 font-bold text-md py-2 px-4 ">ADD NEW INVOICE</button>
                                <button class="text-white bg-black font-bold text-md py-2 px-4">GO BACK</button>
                            </div>
                            <div id="printSection" class="shadow-lg p-3">
                                    <h2 class="text-center font-semibold text-2xl my-2">General Ledger</h2>
                                    <div class="flex items-center justify-between mb-2">
                                        <div class="text-lg">
                                            <h2 class="font-semibold">Account Name : Jane Alam</h2>
                                            <p><span class="font-semibold">Period Date :</span> 14-09-2023 to 15-09-2023 </p>
                                        </div>
                                        <div class="flex items-center">
                                        
                                            <div class="mb-8">
                                                <h2 class="font-bold text-xl">STS International</h2>
                                                <p>Motijheel, Dhaka</p>
                                            </div>
                                        </div>
                                    </div>
                                    <table class="table-auto w-full border-y-2 table-stripe devide-2 text-sm my-1">
                                        <thead>
                                        <tr class="border-y-2 border-black">
                                            <th class="">Date</th>
                                            <th class="">Invoice No</th>
                                            <th class="">Ticket No</th>
                                            <th class="">Details</th>
                                            <th class="">Debit</th>
                                            <th class="">Credit</th>
                                            
                                            <th class="">Balance</th>
                                        </tr>
                                        </thead>
                                        <tbody class="divide-y-2">
                                ';

            foreach ($sortedCollection as $index => $item) {
                // dd($item->getTable());
                if ($item->getTable() == "tickets") {
                    // Handle logic specific to Ticket model
                    $html .= <<<HTML
                                                <tr>
                                                    <td class="w-[10%]"> $item->invoice_date </td>
                                                    <td class="w-[11%]"> $item->invoice </td>
                                                    <td class="w-[15%]"> $item->ticket_no </td>
                                                    <td class="w-[28%] pr-3">
                                                        PAX NAME: <span class="font-semibold"> $item->passenger </span><br>
                                                        PNR:  $item->ticket_code ,  $item->sector <br>
                                                        FLIGHT DATE:  $item->flight_date <br>
                                                        $item->airline_code -  $item->airline_name <br>
                                                        Remarks:  $item->remark 
                                                    </td>
                                                    <td class="w-[12%] "> $item->agent_price </td>
                                                    <td class="w-[12%] "></td>
                                                    <!-- <td class="w-[12%] text-center"> $item->previous_amount  Dr</td> -->
                                                    <td class="w-[12%] "> $item->agent_new_amount  Dr</td>
                                                </tr>
                                            HTML;
                } elseif ($item->getTable() == "receive") {
                    $currentAmount = $item->current_amount >= 0 ? $item->current_amount . ' DR' : $item->current_amount . ' CR';

                    $html .= <<<HTML
                                            <tr style="font-weight: bold;  color: green">
                                                <td class="w-[10%]"> {$item->date} </td>
                                                <td class="w-[11%]"> {$item->invoice} </td>
                                                <td class="w-[15%]"> {$item->ticket_no} </td>
                                                <td class="w-[28%]">
                                                    Remarks:  {$item->remark} 
                                                </td>
                                                <td class="w-[12%]  "></td>
                                                <td class="w-[12%]">{$item->amount}</td>
                                                <td class="w-[12%] ">{$currentAmount}</td>
                                            </tr>
                                            HTML;
                }

                // if($index%2 == 0){

                // }
            }

            $html .= '
                                        
                                        </tbody>
                                    </table>
                            </div>
                            
                            
                            </main>
                           
    
                            <style>
                                @media print {
                                    body * {
                                        visibility: hidden;
                                    }
                            
                                    #printSection, #printSection * {
                                        visibility: visible;
                                    }
                            
                                    #printSection {
                                        position: absolute;
                                        left: 0;
                                        top: 0;
                                        width: 100%;
                                        max-width: 100%;
                                        box-sizing: border-box;
                                        padding: 10px; /* Adjust padding as needed */
                                    }
                                }
                            </style>
                      
                    ';
        } elseif ($who == 'supplier') {
            // dd($who);
            $start_date = $request->start_date;
            $end_date = $request->end_date;
            $id = $request->agent_supplier_id;

            $receive = Ticket::where('supplier', $id);
            // dd($receive);

            if (!is_null($start_date) || !is_null($end_date)) {
                $start_date = (new DateTime($start_date))->format('Y-m-d');
                $end_date = (new DateTime($end_date))->format('Y-m-d');

                $receive->where(function ($query) use ($start_date, $end_date) {
                    if (!is_null($start_date)) {
                        $query->where('invoice_date', '>=', $start_date);
                    }

                    if (!is_null($end_date)) {
                        $query->where('invoice_date', '<=', $end_date);
                    }
                });
            }

            $receive = $receive->get();
            // dd($receive);

            $receiver = Payment::where([
                ['receive_from', '=', 'supplier'],
                ['agent_supplier_id', '=', $id]
            ]);

            if (!is_null($start_date) || !is_null($end_date)) {
                $start_date = (new DateTime($start_date))->format('Y-m-d');
                $end_date = (new DateTime($end_date))->format('Y-m-d');

                $receiver->where(function ($query) use ($start_date, $end_date) {
                    if (!is_null($start_date)) {
                        $query->where('date', '>=', $start_date);
                    }

                    if (!is_null($end_date)) {
                        $query->where('date', '<=', $end_date);
                    }
                });
            }

            $receiver = $receiver->get();

            // dd($receiver);
            $mergedCollection = $receive->merge($receiver);
            $sortedCollection = $mergedCollection->sortBy('created_at');
            // dd($sortedCollection);

            $html = ' 
                            
                        <main class="flex-1 mx-auto max-w-7xl px-10">
                            <div class="buttons justify-end flex gap-3 shadow-lg p-5 ">
                                <button class="text-white bg-pink-600 font-bold text-md py-2 px-4">Send</button>
                                <button class="text-white bg-blue-700 font-bold text-md py-2 px-4" id="printBtn">Print</button>
                                <button class="text-white bg-green-600 font-bold text-md py-2 px-4 ">ADD NEW INVOICE</button>
                                <button class="text-white bg-black font-bold text-md py-2 px-4">GO BACK</button>
                            </div>
                            <div id="printSection" class="shadow-lg p-3">
                                    <h2 class="text-center font-semibold text-2xl my-2">General Ledger</h2>
                                    <div class="flex items-center justify-between mb-2">
                                        <div class="text-lg">
                                            <h2 class="font-semibold">Account Name : Jane Alam</h2>
                                            <p><span class="font-semibold">Period Date :</span> 14-09-2023 to 15-09-2023 </p>
                                        </div>
                                        <div class="flex items-center">
                                        
                                            <div class="mb-8">
                                                <h2 class="font-bold text-xl">STS International</h2>
                                                <p>Motijheel, Dhaka</p>
                                            </div>
                                        </div>
                                    </div>
                                    <table class="table-auto w-full border-y-2 table-stripe devide-2 text-sm my-1">
                                        <thead>
                                        <tr class="border-y-2 border-black">
                                            <th class="">Date</th>
                                            <th class="">Invoice No</th>
                                            <th class="">Ticket No</th>
                                            <th class=" pl-6">Details</th>
                                            <th class="">Debit</th>
                                            <th class="">Credit</th>
                                            
                                            <th class="">Balance</th>
                                        </tr>
                                        </thead>
                                        <tbody class="divide-y-2">
                                ';

            foreach ($sortedCollection as $index => $item) {
                // dd($item->getTable());
                if ($item->getTable() == "tickets") {
                    // Handle logic specific to Ticket model
                    $html .= <<<HTML
                                                <tr>
                                                    <td class="w-[10%]"> $item->invoice_date </td>
                                                    <td class="w-[11%]"> $item->invoice </td>
                                                    <td class="w-[15%]"> $item->ticket_no </td>
                                                    <td class="w-[28%] pr-2">
                                                        PAX NAME: <span class="font-semibold"> $item->passenger </span><br>
                                                        PNR:  $item->ticket_code ,  $item->sector <br>
                                                        FLIGHT DATE:  $item->flight_date <br>
                                                        $item->airline_code -  $item->airline_name <br>
                                                        Remarks:  $item->remark 
                                                    </td>
                                                    
                                                    <td class="w-[12%] "></td>
                                                    <td class="w-[12%]  "> $item->supplier_price </td>
                                                    <!-- <td class="w-[12%] "> $item->previous_amount  Dr</td> -->
                                                    <td class="w-[12%] "> $item->supplier_new_amount  Dr</td>
                                                </tr>
                                            HTML;
                } elseif ($item->getTable() == "payment") {
                    $currentAmount = $item->current_amount >= 0 ? $item->current_amount . ' DR' : $item->current_amount . ' CR';

                    $html .= <<<HTML
                                            <tr style="">
                                                <td class="w-[10%]"> {$item->date} </td>
                                                <td class="w-[11%]"> {$item->invoice} </td>
                                                <td class="w-[15%]"> {$item->ticket_no} </td>
                                                <td class="w-[28%]">
                                                    Remarks:  {$item->remark}<br> 
                                                    Method:   {$item->method}
                                                </td>
                                                <td class="w-[12%]">{$item->amount}</td>
                                                <td class="w-[12%]"></td>
                                                <td class="w-[12%]">{$currentAmount}</td>
                                            </tr>
                                            HTML;
                }

                // if($index%2 == 0){

                // }
            }

            $html .= ' 
                                        <tr class="py-2 text-xl">
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td class="text-lg font-semibold">Total Closing Balance</td>
                                            <td class="w-[12%]  font-bold">409393</td>
                                            <td class="w-[12%]  font-bold">70000</td>
                                            <td class="w-[12%]  font-bold">5000.00 Dr</td>
                                        </tr>
                                        </tbody>
                                    </table>
                            </div>
                            
                            
                        </main>
                        <style>
                        @media print {
                            body * {
                                visibility: hidden;
                            }
                    
                            #printSection, #printSection * {
                                visibility: visible;
                            }
                    
                            #printSection {
                                position: absolute;
                                left: 0;
                                top: 0;
                                width: 100%;
                                max-width: 100%;
                                box-sizing: border-box;
                                padding: 10px; /* Adjust padding as needed */
                            }
                        }
                    </style>
                            
    
                        
                    ';
        } else {
            // Your logic for other cases goes here
        }

        return $html;
    }

    public function ticket_seles_report()
    {
        $user = Auth::id();
        $suppliers = Supplier::where([['is_delete', 0], ['is_active', 1], ['user', $user]])->get();
        $agents = Agent::where([['is_delete', 0], ['is_active', 1], ['user', $user]])->get();
        $types = Type::where([['is_delete', 0], ['is_active', 1], ['user', $user]])->get();
        $orders = Order::where([['is_delete', 0], ['is_active', 1], ['user', $user]])->get();
        // dd($suppliers);
        return view('report.seles.ticketseles_index', compact('suppliers', 'agents', 'types'));
    }
    public function receive_report_index()
    {
        $methods = Transaction::where('is_delete', 0)->where('user', Auth::id())->get();
        $agents = Agent::where('is_delete', 0)->where('user', Auth::id())->get();
        $suppliers = Supplier::where('is_delete', 0)->where('user', Auth::id())->get();
        return view('report.receive.index', compact('methods', 'agents', 'suppliers'));
    }

    // public function receive_report_info(Request $request)
    // {
    //     $start_date = $request->input('start_date') ?? null;
    //     $end_date = $request->input('end_date') ?? null;

    //     if ($start_date) {
    //         $start_date = (new DateTime($start_date))->format('Y-m-d');
    //     }
    //     $tableName = $customerid = null;
    //     if($request->customer){
    //         list($tableName, $customerid) = explode('_', $request->customer);
    //     }
    //     if ($end_date) {
    //         $end_date = (new DateTime($end_date))->format('Y-m-d');
    //     }

    //     $user = Auth::id();
    //     $query1 = Receiver::leftJoin('transaction as transaction_receive', 'transaction_receive.id', '=', 'receive.method')
    //             ->join('transaction as transaction_left', 'transaction_left.id', '=', 'receive.method');

    //     if ($tableName !== null) {
    //         $query1->join(DB::raw("$tableName as dynamicTable"), 'receive.agent_supplier_id', '=', 'dynamicTable.id')
    //             ->where('dynamicTable.user', $user)
    //             ->select('dynamicTable.name', 'receive.*', 'transaction_receive.name as method_name');
    //     } else {
    //         $query1->select('receive.*', 'transaction_receive.name as method_name');
    //     }

    //     $result = $query1->get();


    //     // dd($result);
    //     $html = '
    //         <!doctype html>
    //         <html>
            
    //         <head>
    //         <meta charset="UTF-8">
    //         <meta name="viewport" content="width=device-width, initial-scale=1.0">
    //         <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.0/font/bootstrap-icons.css" />
    //         <script src="https://cdn.tailwindcss.com"></script>
    //         <script>
    //             tailwind.config = {
    //             theme: {
    //                 extend: {
    //                 colors: {
    //                     clifford: "#da373d",
    //                 }
    //                 }
    //             }
    //             }
    //         </script>
    //         <style>
            
    //         </style>
    //         </head>
            
    //         <body class="flex ">
            
    //         <main class=" mx-auto w-[95%] ">
            
    //             <div class="bg-[#23CFD3] px-7 py-3 flex justify-center gap-y-4 mb-3 shadow-2xl">
    //                 <h2 class="text-center font-bold text-2xl">Received Report</h2>
    //                 <!-- <button type="button" class="text-md bg-white px-3 font-medium rounded">Print</button> -->
    //             </div>
    //             <table class="table-auto table-striped w-full shadow-2xl">
    //                 <thead>
    //                 <tr class="bg-[#0E7490] text-white">
    //                     <th class="px-2 py-2 text-left">Date</th>
    //                     <th class="px-2 py-2 text-left">Voucher No</th>
    //                     <th class="px-2 py-2 text-left">Receive From</th>
    //                     <th class="px-2 py-2 text-left">Receive Mode</th>
    //                     <th class="px-2 py-2 text-left">Narration</th>
    //                     <th class="px-2 py-2 text-left">Amount</th>
    //                     <th class="px-2 py-2 text-center">Actions</th>
    //                 </tr>
    //                 </thead>
    //                 <tbody id="data">';
        
    //             foreach ($result as $key => $item) :
    //                 $printUrl = url('/receive_voucher', ['id' => $item]);
    //                 $html .=
    //                     '<tr class="">
    //                                 <td class="w-[10%] px-2 py-2 text-left">' . $item->date . '</td>
    //                                 <td class="w-[11%] px-2 py-2 text-left">' . $item->invoice . '</td>
    //                                 <td class="w-[15%] px-2 py-2 text-left">' . $item->name . '</td>
    //                                 <td class="w-[28%] px-4 py-2 text-left">' .
    //                     $item->method_name .
    //                     '</td>
    //                                 <td class="w-[12%]  px-2 py-2 text-left">' . $item->remark . '</td>
    //                                 <td class="w-[12%]  px-2 py-2 text-left amount">' . $item->amount . '</td>
    //                                 <td class="px-2 py-1 text-center flex justify-center gap-2"><a href=' . $printUrl . ' class="bg-green-700 text-white px-3 rounded-md text-sm">Print</a><button type="button" class="bg-stone-700 text-white px-3 rounded-md text-sm">Edit</button></td>
    //                             </tr>
    //                             ';
    //             endforeach;

    //                     $html .= '

    //                             <tr><td class="px-4 py-2 text-left" colspan="5">Total Amount </td>
    //                             <td class="ml-5 font-bold text-xl px-2 " id="total_amount"></td>
    //                             </tr>
    //                         </tbody>
    //                     </table>
    //                 </main>
    //                 <script type="text/javascript">
    //                 function calculateTotalAmount() {
    //                     const amountElements = document.querySelectorAll(".amount");

    //                     let totalAmount = 0;

    //                     amountElements.forEach(element => {
    //                         // Parse the text content of the element to get the numeric value
    //                         const amount = parseFloat(element.textContent.replace(/,/g, ""));
    //                         // Add the amount to the total
    //                         totalAmount += amount;
    //                     });

    //                     var formattedAmount = totalAmount.toLocaleString("en-US", { minimumFractionDigits: 0, maximumFractionDigits: 0 });
    //                     document.getElementById("total_amount").innerHTML = formattedAmount;

    //                     console.log("Total amount:", totalAmount);
    //                 }

    //                 calculateTotalAmount();
    //             </script>
    //         <script src="https://unpkg.com/flowbite@1.4.0/dist/flowbite.js"></script>
    //         </body>
            
    //         </html>
    //     ';

    //             return $html;
    // }
    public function receive_report_info(Request $request){
        // dd($request->all());
        $start_date = $request->input('start_date') ?? null;
        $end_date = $request->input('end_date') ?? null;

        $tableName = $customerid = null;
        if($request->customer){
            list($tableName, $customerid) = explode('_', $request->customer);
        }
       
        if ($start_date) {
            $start_date = (new DateTime($start_date))->format('Y-m-d');
        }
        
        if ($end_date) {
            $end_date = (new DateTime($end_date))->format('Y-m-d');
        }
        
        $user = Auth::id();

        $query1 = Receiver::where('receive.user', $user);

        if ($tableName !== null) {
            $query1->where('receive_from', $tableName);
        }
        
        if ($customerid !== null) {
            $query1->where('agent_supplier_id', $customerid);
        }
        
        if ($request->method !== null) {
            $query1->where('method', $request->method);
        }
        
        if ($start_date !== null) {
            $query1->whereDate('date', '>=', $start_date);
        }
        
        if ($end_date !== null) {
            $query1->whereDate('date', '<=', $end_date);
        }
        
               
        $query1->leftJoin('transaction as transaction_left', 'transaction_left.id', '=', 'receive.method');

        
        if ($tableName !== null) {
            $query1->join(DB::raw("$tableName as dynamicTable"), 'receive.agent_supplier_id', '=', 'dynamicTable.id')
                   ->where('dynamicTable.user', $user)
                   ->select('dynamicTable.name', 'receive.*', 'transaction_left.name as method_name');
        } else {
            $query1->select('receive.*', 'transaction_left.name as method_name');
        }
        
        $result = $query1->get();
        
        if ($tableName === null && $customerid === null) {
            // dd('mk');
            foreach ($result as $row) {
                if ($row->receive_from == 'agent' || $row->receive_from == 'Agent') {
                    $row->name = Agent::where('id', $row->agent_supplier_id)->value('name');
                } else {
                    $row->name = Supplier::where('id', $row->agent_supplier_id)->value('name');
                }
            }
        }
       
        $html = '
            
            
            <main class=" mx-auto w-[95%] ">
            
                <div class="bg-[#23CFD3] px-7 py-3 flex justify-center gap-y-4 mb-3 shadow-2xl">
                    <h2 class="text-center font-bold text-2xl">Received Report</h2>
                    <!-- <button type="button" class="text-md bg-white px-3 font-medium rounded">Print</button> -->
                </div>
                <table class="table-auto table-striped w-full shadow-2xl">
                    <thead>
                    <tr class="bg-[#0E7490] text-white">
                        <th class="px-4 py-2 text-left">Date</th>
                        <th class="px-4 py-2 text-left">Voucher No</th>
                        <th class="px-4 py-2 text-left">Receive From</th>
                        <th class="px-4 py-2 text-left">Receive Mode</th>
                        <th class="px-4 py-2 text-left">Narration</th>
                        <th class="px-4 py-2 text-left">Amount</th>
                        <th class="px-4 py-2 text-center">Actions</th>
                    </tr>
                    </thead>
                    <tbody id="data">';
                    foreach ($result as $key => $item):
                        $printUrl = url('/receive_voucher', ['id' => $item]);
                            $html .= <<<HTML
                            <tr class="">
                                <td class="w-[10%] px-4 py-2 text-left"> $item->date </td>
                                <td class="w-[11%] px-4 py-2 text-left"> $item->invoice </td>
                                <td class="w-[15%] px-4 py-2 text-left"> $item->name </td>
                                <td class="w-[28%] px-4 py-2 text-left">
                                  $item->method_name
                                </td>
                                <td class="w-[12%] px-4 py-2 text-left"> $item->remark </td>
                                <td class="w-[12%] px-4 py-2 text-left amount">$item->amount</td>
                                <!-- <td class="w-[12%] text-center"> $item->previous_amount  Dr</td> -->
                                <td class="px-2 py-1 text-center flex justify-center gap-2 items-center"><a href='$printUrl' class="bg-green-700 text-white px-3 rounded-md text-sm">Print</a><button type="button" class="bg-stone-700 text-white px-3 rounded-md text-sm">Edit</button></td>
                            </tr>
                            HTML;
                    endforeach;
                    
                        $html .=' 
                        <tr>
                            <td class="px-4 py-2 text-left" colspan="5">Total Amount </td>
                            <td class="ml-5 font-bold text-xl px-2 " id="total_amount"></td>
                        </tr>
                    </tbody>
                </table>
            </main>
            <script type="text/javascript">
                function calculateTotalAmount() {
                    const amountElements = document.querySelectorAll(".amount");
                
                    let totalAmount = 0;
                    
                    amountElements.forEach(element => {
                        // Parse the text content of the element to get the numeric value
                        const amount = parseFloat(element.textContent);
                        // Add the amount to the total
                        totalAmount += amount;
                    });
                    document.getElementById("total_amount").innerHTML = totalAmount;
                    
                    console.log("Total amount:", totalAmount);
                }
                calculateTotalAmount();
                
                
            </script>
        ';

        return $html;
    }
    public function payment_report_index()
    {
        $methods = Transaction::where('is_delete', 0)->where('user', Auth::id())->get();
        $agents = Agent::where('is_delete', 0)->where('user', Auth::id())->get();
        $suppliers = Supplier::where('is_delete', 0)->where('user', Auth::id())->get();
        return view('report.payment.index', compact('methods', 'agents', 'suppliers'));
    }
    public function payment_voucher(Request $request, $id)
    {
        $user = Auth::id();
        $payment_voucher = Payment::findOrFail($id);
        if ($payment_voucher->receive_from == 'agent'){
            $supplier = Agent::where([
                ['id', $payment_voucher->agent_supplier_id]
            ])->first();
        }else{
            $supplier = Supplier::where([
                ['id', $payment_voucher->agent_supplier_id]
            ])->first();
        }
        return view('report.payment.voucher', compact('payment_voucher', 'supplier'));
    }
    public function receive_voucher(Request $request, $id)
    {
        
        $user = Auth::id();
        $receive_voucher = Receiver::findOrFail($id);
        // dd($receive_voucher);
        if ($receive_voucher->receive_from == 'agent'){
            $agent = Agent::where([
                ['id', $receive_voucher->agent_supplier_id]
            ])->first();
        }else{
            $agent = Supplier::where([
                ['id', $receive_voucher->agent_supplier_id]
            ])->first();
        }
        // dd($agent);
       
        return view('report.receive.voucher', compact('receive_voucher', 'agent'));
    }
    public function payment_report_info1(Request $request)
    {

        $start_date = $request->input('start_date') ?? null;
        $end_date = $request->input('end_date') ?? null;

        if ($start_date) {
            $start_date = (new DateTime($start_date))->format('Y-m-d');
        }
        $tableName = $customerid = null;
        if($request->customer != null){
            list($tableName, $customerid) = explode('_', $request->customer);
        }

        if ($end_date) {
            $end_date = (new DateTime($end_date))->format('Y-m-d');
        }

        $user = Auth::id();
        $query1 = Payment::leftjoin(DB::raw("$tableName as dynamicTable"), 'payment.agent_supplier_id', '=', 'dynamicTable.id')
                ->leftJoin('transaction as transaction_left', 'transaction_left.id', '=', 'payment.method');

        if ($tableName !== null) {
            $query1->where('dynamicTable.user', $user)
                ->select('dynamicTable.name', 'payment.*', 'transaction_left.name as method_name');
        } else {
            $query1->select('payment.*', 'transaction_left.name as method_name');
        }

        $result = $query1->get();

        // dd($result);
        $html = '
            <!doctype html>
            <html>
            
            <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.0/font/bootstrap-icons.css" />
            <script src="https://cdn.tailwindcss.com"></script>
            <script>
                tailwind.config = {
                theme: {
                    extend: {
                    colors: {
                        clifford: "#da373d",
                    }
                    }
                }
                }
            </script>
            <style>
            
            </style>
            </head>
            
            <body class="flex ">
            
            <main class=" mx-auto w-[95%] ">
            
                <div class="bg-[#23CFD3] px-7 py-3 flex justify-center gap-y-4 mb-3 shadow-2xl">
                    <h2 class="text-center font-bold text-2xl">Payment Report</h2>
                    <!-- <button type="button" class="text-md bg-white px-3 font-medium rounded">Print</button> -->
                </div>
                <table class="table-auto table-striped w-full shadow-2xl">
                    <thead>
                    <tr class="bg-[#0E7490] text-white">
                    
                        <th class="px-2 py-2 text-left">Date</th>
                        <th class="px-2 py-2 text-left">Voucher No</th>
                        <th class="px-2 py-2 text-left">Payment From</th>
                        <th class="px-2 py-2 text-left">Payment Mode</th>
                        <th class="px-2 py-2 text-left">Narration</th>
                        <th class="px-2 py-2 text-left">Amount</th>
                        <th class="px-2 py-2 text-center">Actions</th>
                    </tr>
                   
                    </thead>
                    <tbody id="data">';
        foreach ($result as $key => $item) :
            $printUrl = url('/payment_voucher', ['id' => $item->id]);
            $html .=
                '<tr class="">
                                <td class="w-[10%] px-2 py-2 text-left">' . $item->date . '</td>
                                <td class="w-[11%] px-2 py-2 text-left">' . $item->invoice . '</td>
                                <td class="w-[15%] px-2 py-2 text-left">' . $item->name . '</td>
                                <td class="w-[28%] px-4 py-2 text-left">' .
                $item->method .
                '</td>
                                <td class="w-[12%]  px-2 py-2 text-left">' . $item->remark . '</td>
                                <td class="w-[12%]  px-2 py-2 text-left amount">' . $item->amount . '</td>
                                <td class="px-2 py-1 text-center flex justify-center gap-2"><a href=' . $printUrl . ' class="bg-green-700 text-white px-3 rounded-md text-sm">Print</a><button type="button" class="bg-stone-700 text-white px-3 rounded-md text-sm">Edit</button></td>
                            </tr>
                            ';
        endforeach;

        $html .= '

                        <tr><td class="px-4 py-2 text-left" colspan="5">Total Amount </td>
                        <td class="ml-5 font-bold text-xl px-2 " id="total_amount"></td>
                        </tr>
                    </tbody>
                </table>
            </main>
            <script type="text/javascript">
            function calculateTotalAmount() {
                const amountElements = document.querySelectorAll(".amount");
        
                let totalAmount = 0;
        
                amountElements.forEach(element => {
                    // Parse the text content of the element to get the numeric value
                    const amount = parseFloat(element.textContent);
                    // Add the amount to the total
                    totalAmount += amount;
                });
        
                var formattedAmount = totalAmount.toLocaleString("en-US", { minimumFractionDigits: 0, maximumFractionDigits: 0 });
                document.getElementById("total_amount").innerHTML = formattedAmount;
        
                console.log("Total amount:", totalAmount);
            }
        
            calculateTotalAmount();
        </script>
            </body>
            
            </html>
        ';

        return $html;
    }
    public function payment_report_info(Request $request){

        // dd($request->all());
       
        $start_date = $request->input('start_date') ?? null;
        $end_date = $request->input('end_date') ?? null;

        $tableName = $customerid = null;
        if($request->customer != null){
            list($tableName, $customerid) = explode('_', $request->customer);
        }

        if ($start_date) {
            $start_date = (new DateTime($start_date))->format('Y-m-d');
        }
        
        if ($end_date) {
            $end_date = (new DateTime($end_date))->format('Y-m-d');
        }
        
        $user = Auth::id();

        $query1 = Payment::where('payment.user', $user); 

        if ($tableName !== null) {
            $query1->where('receive_from', $tableName);
        }
        
        if ($customerid !== null) {
            $query1->where('agent_supplier_id', $customerid);
        }
        
        if ($request->method !== null) {
            $query1->where('method', $request->method);
        }
        
        if ($start_date !== null && $end_date !== null) {
            $query1->whereBetween('date', [$start_date, $end_date]);
        } elseif ($start_date !== null) {
            $query1->whereDate('date', '>=', $start_date);
        } elseif ($end_date !== null) {
            $query1->whereDate('date', '<=', $end_date);
        }
        
        $query1->leftJoin('transaction as transaction_left', 'transaction_left.id', '=', 'payment.method');
        
        if ($tableName !== null) {
            $query1->leftJoin(DB::raw("$tableName as dynamicTable"), 'payment.agent_supplier_id', '=', 'dynamicTable.id')
                ->where('dynamicTable.user', $user)
                ->select('dynamicTable.name', 'payment.*', 'transaction_left.name as method_name');
        } else {
            $query1->select('payment.*', 'transaction_left.name as method_name');
        }
        
        $result = $query1->get();

        if ($tableName === null && $customerid === null) {
            // dd('mk');
            foreach ($result as $row) {
                if ($row->receive_from == 'agent' || $row->receive_from == 'Agent') {
                    $row->name = Agent::where('id', $row->agent_supplier_id)->value('name');
                } else {
                    $row->name = Supplier::where('id', $row->agent_supplier_id)->value('name');
                }
            }
        }
        

        // dd($result);
        $html = '
            
            
            <main class=" mx-auto w-[95%] ">
            
                <div class="bg-[#23CFD3] px-7 py-3 flex justify-center gap-y-4 mb-3 shadow-2xl">
                    <h2 class="text-center font-bold text-2xl">Payment Report</h2>
                    <!-- <button type="button" class="text-md bg-white px-3 font-medium rounded">Print</button> -->
                </div>
                <table class="table-auto w-full shadow-2xl table-striped">
                    <thead>
                    <tr class="bg-[#0E7490] text-white">
                    
                        <th class="px-4 py-2 text-left">Date</th>
                        <th class="px-4 py-2 text-left">Voucher No</th>
                        <th class="px-4 py-2 text-left">Payment From</th>
                        <th class="px-4 py-2 text-left">Payment Mode</th>
                        <th class="px-4 py-2 text-left">Narration</th>
                        <th class="px-4 py-2 text-left">Amount</th>
                        <th class="px-4 py-2 text-center">Print</th>
                    </tr>
                   
                    </thead>
                    <tbody id="data">';
                    foreach ($result as $key => $item):
                        $printUrl = url('/payment_voucher', ['id' => $item->id]);
                            $html .= <<<HTML
                            <tr class="">
                                <td class="w-[10%] px-4 py-2 text-left"> $item->date </td>
                                <td class="w-[11%] px-4 py-2 text-left"> $item->invoice </td>
                                <td class="w-[15%] px-4 py-2 text-left"> $item->name </td>
                                <td class="w-[28%] px-4 py-2 text-left">
                                $item->method_name
                                </td>
                                <td class="w-[12%] px-4 py-2 text-left"> $item->remark </td>
                                <td class="w-[12%] px-4 py-2 text-left amount">$item->amount</td>
                                <!-- <td class="w-[12%] text-center"> $item->previous_amount  Dr</td> -->
                                <td class="px-2 py-1 text-center flex justify-center gap-2"><a href='$printUrl' class="bg-green-700 text-white px-3 rounded-md text-sm">Print</a><button type="button" class="bg-stone-700 text-white px-3 rounded-md text-sm">Edit</button></td>
                            </tr>
                            HTML;
                    endforeach;
                    
                        $html .=' 

                        <tr><td class="px-4 py-2 text-left" colspan="5">Total Amount </td>
                        <td class="ml-5 font-bold text-xl px-2 " id="total_amount"></td>
                        </tr>
                    </tbody>
                </table>
            </main>
            <script type="text/javascript">
                function calculateTotalAmount() {
                    const amountElements = document.querySelectorAll(".amount");
                
                    let totalAmount = 0;
                    
                    amountElements.forEach(element => {
                        // Parse the text content of the element to get the numeric value
                        const amount = parseFloat(element.textContent);
                        // Add the amount to the total
                        totalAmount += amount;
                    });
                    document.getElementById("total_amount").innerHTML = totalAmount;
                    
                    console.log("Total amount:", totalAmount);
                }
                calculateTotalAmount();
                
                
            </script>
        ';

        return $html;
    }
    public function ait_report_index()
    {
        // dd('na');
        return view('report.ait.index');
    }

    public function ait_report_info(Request $request)
    {

        $start_date = $request->input('start_date') ?? null;
        $end_date = $request->input('end_date') ?? null;

        if ($start_date) {
            $start_date = (new DateTime($start_date))->format('Y-m-d');
        }

        if ($end_date) {
            $end_date = (new DateTime($end_date))->format('Y-m-d');
        }

        $user = Auth::id();
        $query1 = AIT::where('ait.user', $user); // Specify 'receive.user'

        if ($start_date) {
            $query1->whereDate('created_at', '>=', $start_date);
        }

        if ($end_date) {
            $query1->whereDate('created_at', '<=', $end_date);
        }

        $result = $query1->get();

        // dd($result);
        $html = '
            <!doctype html>
            <html>
            
            <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.0/font/bootstrap-icons.css" />
            <script src="https://cdn.tailwindcss.com"></script>
            <script>
                tailwind.config = {
                theme: {
                    extend: {
                    colors: {
                        clifford: "#da373d",
                    }
                    }
                }
                }
            </script>
            <style>
            
            </style>
            </head>
            
            <body class="flex ">
            
            <main class="flex-1 mx-auto max-w-7xl px-10">
           
            <div class="">
                 <h2 class="text-center font-light text-3xl my-2">SALLU AIR SERVICE</h2>
                 <h2 class="text-center font-bold text-xl my-2 underline">AIT Report</h2>
                 <div class="flex items-center w-[35%] mx-auto justify-between mb-2">
                     <div class="text-md">
                         <p><span class="font-semibold">Period Date :</span>';
        $start_date;
        $html .= '';
        $end_date;
        $html .= '</p> 
                         <p>From Date : <span class="font-semibold">';
        $html .= $start_date;
        $html .= '</span></p>
                     </div>
                     <div class="text-md">
                         <p>To Date : <span class="font-semibold">';
        $html .= $end_date;
        $html .= '</span></p>
                         
                     </div>
                 </div>
                 <!-- <p class="">From Date : 14-09-2024 </p> -->
                 <table class=" table-auto w-[100%] mx-auto border-2 border-gray-400 devide-2 text-sm my-1">
                     <thead>
                       <tr class="border-y-2 border-black bg-cyan-700 text-white">
                         <th class="text-start">SL</th>
                         <th class="text-start">Air Name</th>
                         <th class="text-start">AIT AMOUNT</th>
                         <th class="text-start">Sector</th>
                         <th class="text-start">Actions</th>
                       </tr>
                     </thead>
                     <tbody class="divide-y-2">';
        foreach ($result as $index => $item) :

            $html .= <<<HTML
                        
                       <tr class="">
                        <td>$index</td>
                        <td>$item->airline_name</td>
                        <td class="amount">$item->total_amount</td>
                        <td>$item->sector</td>
                        <td class="px-2 py-1 flex gap-2"><button type="button" class="bg-green-700 text-white px-3 rounded-md text-sm">Print</button><button type="button" class="bg-red-700 text-white px-3 rounded-md text-sm">Download</button></td>
                       </tr>
                       HTML;
        endforeach;

        $html .= '
                       <tr class="bg-neutral-400 text-black font-bold">
                        <td></td>
                        
                       
                        <td>Total Amount</td>
                        <td id="total_amount"></td>
                        <td></td>
                        <td></td>
                        
                       </tr>
                     
                     </tbody>
                   </table>
            </div>
         
         
           </main>
            
           <script type="text/javascript">
                function calculateTotalAmount() {
                    const amountElements = document.querySelectorAll(".amount");
                
                    let totalAmount = 0;
                    
                    amountElements.forEach(element => {
                        // Parse the text content of the element to get the numeric value
                        const amount = parseFloat(element.textContent);
                        // Add the amount to the total
                        totalAmount += amount;
                    });
                    document.getElementById("total_amount").innerHTML = totalAmount;
                    
                    console.log("Total amount:", totalAmount);
                }
                calculateTotalAmount();
                
                
            </script>
            <script src="https://unpkg.com/flowbite@1.4.0/dist/flowbite.js"></script>
            </body>
            
            </html>
        ';

        return $html;
    }
    public function due_reminder(){

        $agentIds = Agent::where([
            ['is_delete', 0], 
            ['is_active', 1], 
            ['user', Auth::id()]
        ])->pluck('id');

        $supplierIds = Supplier::where([
            ['is_delete', 0], 
            ['is_active', 1], 
            ['user', Auth::id()]
        ])->pluck('id');

        $agentIds = $agentIds->toArray(); // Convert the collection to an array
        $supplierIds = $supplierIds->toArray(); // Convert the collection to an array

        $latestReceives = [];
        $latestPayments = [];

        foreach ($agentIds as $agentId) {

            $latestReceive = Receiver::where('agent_supplier_id', $agentId)
                ->where('receive_from', 'agent')
                ->orderBy('created_at', 'desc')
                ->first(); // Retrieve the latest receive record for this agent

            $latestPayment = Payment::where('agent_supplier_id', $agentId)
                ->where('receive_from', 'agent')
                ->orderBy('created_at', 'desc')
                ->first(); // Retrieve the latest receive record for this agent

            $latestReceives[] = $latestReceive; // Add the latest receive record to the array
            $latestPayments[] = $latestPayment; // Add the latest receive record to the array
        }

        foreach ($supplierIds as $supplierId) {

            $latestReceive = Receiver::where('agent_supplier_id', $supplierId)
                ->where('receive_from', 'supplier')
                ->orderBy('created_at', 'desc')
                ->first(); // Retrieve the latest receive record for this supplier

            $latestPayment = Payment::where('agent_supplier_id', $supplierId)
                ->where('receive_from', 'supplier')
                ->orderBy('created_at', 'desc')
                ->first(); // Retrieve the latest receive record for this agent

            $latestReceives[] = $latestReceive; // Add the latest receive record to the array
            $latestPayments[] = $latestPayment; // Add the latest receive record to the array
        }


        // dd($latestReceives, $agentIds, $latestPayments);
        // Merge the $latestReceives and $latestPayments collections
        // Convert $latestReceives and $latestPayments arrays to collections
        $latestReceivesCollection = collect($latestReceives);
        $latestPaymentsCollection = collect($latestPayments);

        // Merge the collections
        $allTransactions = $latestReceivesCollection->merge($latestPaymentsCollection);

        // Sort the merged collection by created_at timestamp in descending order
        $latestTransaction = $allTransactions->sortByDesc('created_at');

        // Output the result
        $filteredTransactions = [];

            foreach ($latestTransaction as $transaction) {
                // Check if the transaction is not empty
                if (!empty($transaction)) {
                    // Check if there's already a transaction with the same receive_from and agent_supplier_id
                    $existingTransaction = collect($filteredTransactions)->first(function ($filteredTransaction) use ($transaction) {
                        return $filteredTransaction['receive_from'] == $transaction['receive_from']
                            && $filteredTransaction['agent_supplier_id'] == $transaction['agent_supplier_id'];
                    });

                    // If no existing transaction found, add the current transaction to the filtered transactions
                    if (!$existingTransaction) {
                        $filteredTransactions[] = $transaction;
                    }
                }
            }

            // Output the filtered transactions
            // dd($filteredTransactions);
            $filteredTransactionsWithNames = [];

            foreach ($filteredTransactions as $transaction) {
                // Determine the model based on the value of receive_from
                $modelName = ($transaction['receive_from'] === 'agent') ? 'App\Models\Agent' : 'App\Models\Supplier';

                // Retrieve the model instance
                $model = $modelName::find($transaction['agent_supplier_id']);

                // If the model instance is found, add its name to the transaction
                if ($model) {
                    $transaction['agent_supplier_name'] = $model->name;
                    $transaction['agent_supplier_email'] = $model->email;
                    $transaction['agent_supplier_phone'] = $model->phone;
                    $transaction['agent_supplier_company'] = $model->company;
                    $filteredTransactionsWithNames[] = $transaction;
                }
            }
            $agents = Agent::where('is_delete', 0)->where('user', Auth::id())->get();
            $suppliers = Supplier::where('is_delete', 0)->where('user', Auth::id())->get();

            // Output the filtered transactions with names
            // dd($filteredTransactionsWithNames);
            return view('report.due_reminder.DueReminder', compact('filteredTransactions', 'agents', 'suppliers'));
    }
    public function due_reminder_specific(Request $request){

        $supplierName = $request->supplierName;

        // Split the string at the underscore character
        list($tableName, $clientID) = explode('_', $supplierName);
        // dd($tableName, $clientID);

         // Build the model class name dynamically
         $modelClassName = ucfirst($tableName);

         // Create an instance of the model
         $model = app("App\\Models\\$modelClassName");

        $ssid = $model::where([
            ['is_delete', 0], 
            ['is_active', 1], 
            ['user', Auth::id()]
        ])->pluck('id');


        $ssid = $ssid->toArray(); // Convert the collection to an array
      
        $latestReceives = [];
        $latestPayments = [];

    
        $latestReceive = Receiver::where('agent_supplier_id', $clientID)
            ->where('receive_from', $tableName)
            ->orderBy('created_at', 'desc')
            ->first(); // Retrieve the latest receive record for this agent

        $latestPayment = Payment::where('agent_supplier_id', $clientID)
            ->where('receive_from', $tableName)
            ->orderBy('created_at', 'desc')
            ->first(); // Retrieve the latest receive record for this agent

        $latestReceives[] = $latestReceive; // Add the latest receive record to the array
        $latestPayments[] = $latestPayment; // Add the latest receive record to the array


        $latestReceivesCollection = collect($latestReceives);
        $latestPaymentsCollection = collect($latestPayments);

        // Merge the collections
        $allTransactions = $latestReceivesCollection->merge($latestPaymentsCollection);

        // Sort the merged collection by created_at timestamp in descending order
        $latestTransaction = $allTransactions->sortByDesc('created_at');

        // Output the result
        $filteredTransactions = [];

            foreach ($latestTransaction as $transaction) {
                // Check if the transaction is not empty
                if (!empty($transaction)) {
                    // Check if there's already a transaction with the same receive_from and agent_supplier_id
                    $existingTransaction = collect($filteredTransactions)->first(function ($filteredTransaction) use ($transaction) {
                        return $filteredTransaction['receive_from'] == $transaction['receive_from']
                            && $filteredTransaction['agent_supplier_id'] == $transaction['agent_supplier_id'];
                    });

                    // If no existing transaction found, add the current transaction to the filtered transactions
                    if (!$existingTransaction) {
                        $filteredTransactions[] = $transaction;
                    }
                }
            }

            // Output the filtered transactions
            // dd($filteredTransactions);
            $filteredTransactionsWithNames = [];

            foreach ($filteredTransactions as $transaction) {
                // Determine the model based on the value of receive_from
                $modelName = ($transaction['receive_from'] === 'agent') ? 'App\Models\Agent' : 'App\Models\Supplier';

                // Retrieve the model instance
                $model = $modelName::find($transaction['agent_supplier_id']);

                // If the model instance is found, add its name to the transaction
                if ($model) {
                    $transaction['agent_supplier_name'] = $model->name;
                    $transaction['agent_supplier_email'] = $model->email;
                    $transaction['agent_supplier_phone'] = $model->phone;
                    $transaction['agent_supplier_company'] = $model->company;
                    $filteredTransactionsWithNames[] = $transaction;
                }
            }
            $agents = Agent::where('is_delete', 0)->where('user', Auth::id())->get();
            $suppliers = Supplier::where('is_delete', 0)->where('user', Auth::id())->get();

            // Output the filtered transactions with names
            // dd($filteredTransactionsWithNames);
            return view('report.due_reminder.DueReminder', compact('filteredTransactions', 'agents', 'suppliers'));
    }
    public function sales_ticket(){
        $user = Auth::id();
        $suppliers = Supplier::where([['is_delete',0],['is_active',1],['user',$user]])->get();
        $agents = Agent::where([['is_delete',0],['is_active',1],['user',$user]])->get();
        $types = Type::where([['is_delete',0],['is_active',1],['user',$user]])->get();
        $orders = Order::where([['is_delete',0],['is_active',1],['user', $user]])->get();
        return view('report.sales_ticket.index', compact('suppliers', 'agents', 'types'));
    }


    public function segment_view(){
        return view('report.segment.index');
    }

    public function sector_city_view(){
    
        $cities = Ticket::where([
            ['is_delete', 0],
            ['is_active', 1]
        ])
        ->distinct()
        ->pluck('s_from');

        $cities2 = Ticket::where([
            ['is_delete', 0],
            ['is_active', 1]
        ])
        ->distinct()
        ->pluck('e_to');

        // dd($cities, $cities2);
        return view('report.sector_city.index', compact('cities', 'cities2'));
    }

    public function segment_report(Request $request){

     
        $start_date = $request->input('start_date') ?? null;
        $end_date = $request->input('end_date') ?? null;

        if ($start_date) {
            $start_date = (new DateTime($start_date))->format('Y-m-d');
        }
        
        if ($end_date) {
            $end_date = (new DateTime($end_date))->format('Y-m-d');
        }
        
        $user = Auth::id();
        
       $query = DB::table('tickets')
            ->where([
                ['is_active', 1],
                ['is_delete', 0],
                ['user', $user],
            ]);
         

            if ($start_date !== null && $end_date !== null) {
                $query->whereBetween('invoice_date', [$start_date, $end_date]);
            }
            $alldata = $query->get();

            $totalCount = 0;
            $totalSupplierPrice = 0;
            
            foreach ($alldata as $data) {
                $sector = $data->sector;
                $parts = explode('-', $sector);
                $count = count($parts) - 1;
                $data->count = $count;
                $totalCount += $count;
            
                // Assuming $supplier_price is a property of the $data object
                $totalSupplierPrice += $data->supplier_price;
            }
            
        // dd($alldata);
        $htmlTable = '';
       
        $htmlTable = '
        <h2 class="text-center font-bold text-3xl my-2">Segment Report </h2>
        <div class="flex items-center justify-between mb-2">
            <div class="text-lg">
                <h2 class="font-semibold">Company Name : Sallu Air Service</h2>
                <p><span class="font-semibold">Period Date :</span>' . $start_date . ' to ' . $end_date . '</p> 
            </div>
            <div class="flex items-center">
                
                
            </div>
        </div>
        <table class="table-auto w-full border-2 border-gray-400 devide-2 text-sm my-1">
        <thead>
            <tr class="border-y-2 border-black bg-cyan-700 text-white">
                <th class="text-start">SL</th>
                <th class="text-start">Ticket No</th>
                <th class="text-start">Passenger Name</th>
                <th class="text-start">Sector</th>
                <th class="text-start">GDS</th>
                <th class="text-start">Airlines</th>
                
                
                <th class="text-start">Sector No</th>
                <th class="text-start">Payable Amount</th>
                
            </tr>
        </thead>
        <tbody class="divide-y-2">';

        // Loop through each record in $alldata and add a row to the table
        foreach ($alldata as $index=> $data) {
            $htmlTable .= '<tr>
                <td>' . $index + 1 . '</td>
               
                <td>' . $data->ticket_code.'-'.$data->ticket_no . '</td>
                <td>' . $data->passenger . '</td>
               
                <td>' . $data->sector . '</td>
                <td>' . '1G' . '</td>
                <td>' . $data->airline_name . '</td>
                
                
                <td>' . $data->count . '</td>
                <td>' . $data->supplier_price . '</td>
               
            </tr>';
        }

        $htmlTable .= '<tr class="bg-green"> 
                        <td></td> 
                        <td></td> 
                        <td></td> 
                        <td></td> 
                        
                        <td colspan="2">Total Segment: </td>
                        <td>' . $totalCount . '</td>
                        <td>' . $totalSupplierPrice . '</td>
                        </tr>';

        // Close the HTML table
        $htmlTable .= '</tbody></table>';
            

           
          
      
        return $htmlTable;
    }

    public function sector_city_report(Request $request){

     
        $start_date = $request->input('start_date') ?? null;
        $end_date = $request->input('end_date') ?? null;
        $start = $request->input('start_from') ?? null;
        $end = $request->input('end_to') ?? null;

        if ($start_date) {
            $start_date = (new DateTime($start_date))->format('Y-m-d');
        }
        
        if ($end_date) {
            $end_date = (new DateTime($end_date))->format('Y-m-d');
        }
        
        $user = Auth::id();
        
       $query = DB::table('tickets')
            ->where([
                ['is_active', 1],
                ['is_delete', 0],
                ['user', $user],
            ]);

            if($start){
                $query->where('s_from', $start);
            }
         
            if($end){
                $query->where('e_to', $end);
            }
         

            if ($start_date !== null && $end_date !== null) {
                $query->whereBetween('invoice_date', [$start_date, $end_date]);
            }
            $alldata = $query->get();

            $totalCount = 0;
            $totalSupplierPrice = 0;
            
            foreach ($alldata as $data) {
                $sector = $data->sector;
                $parts = explode('-', $sector);
                $count = count($parts) - 1;
                $data->count = $count;
                $totalCount += $count;
            
                // Assuming $supplier_price is a property of the $data object
                $totalSupplierPrice += $data->supplier_price;
            }
            
        // dd($alldata);
        $htmlTable = '';
       
        $htmlTable = '
        <h2 class="text-center font-bold text-3xl my-2">Segment Report </h2>
        <div class="flex items-center justify-between mb-2">
            <div class="text-lg">
                <h2 class="font-semibold">Company Name : Sallu Air Service</h2>
                <p><span class="font-semibold">Period Date :</span>' . $start_date . ' to ' . $end_date . '</p> 
            </div>
            <div class="flex items-center">
                
                
            </div>
        </div>
        <table class="table-auto w-full border-2 border-gray-400 devide-2 text-sm my-1">
        <thead>
            <tr class="border-y-2 border-black bg-cyan-700 text-white">
                <th class="text-start">SL</th>
                <th class="text-start">Ticket No</th>
                <th class="text-start">Passenger Name</th>
                <th class="text-start">Sector</th>
                <th class="text-start">GDS</th>
                <th class="text-start">Airlines</th>
                
                
                <th class="text-start">Sector No</th>
                <th class="text-start">Payable Amount</th>
                
            </tr>
        </thead>
        <tbody class="divide-y-2">';

        // Loop through each record in $alldata and add a row to the table
        foreach ($alldata as $index=> $data) {
            $htmlTable .= '<tr>
                <td>' . $index + 1 . '</td>
               
                <td>' . $data->ticket_code.'-'.$data->ticket_no . '</td>
                <td>' . $data->passenger . '</td>
               
                <td>' . $data->sector . '</td>
                <td>' . '1G' . '</td>
                <td>' . $data->airline_name . '</td>
                
                
                <td>' . $data->count . '</td>
                <td>' . $data->supplier_price . '</td>
               
            </tr>';
        }

        $htmlTable .= '<tr class="bg-green"> 
                        <td></td> 
                        <td></td> 
                        <td></td> 
                        <td></td> 
                        
                        <td colspan="2">Total Segment: </td>
                        <td>' . $totalCount . '</td>
                        <td>' . $totalSupplierPrice . '</td>
                        </tr>';

        // Close the HTML table
        $htmlTable .= '</tbody></table>';
            

           
          
      
        return $htmlTable;
    }


    public function sales_report_ticket(Request $request){

        $agent = $request->input('agent') ?? null;
        $supplier = $request->input('supplier') ?? null;
    
        $show_profit = $request->input('show_profit') ?? null;
        $show_supplier = $request->input('show_supplier') ?? null;
        $show_agent = $request->input('show_agent') ?? null;

        $start_date = $request->input('start_date') ?? null;
        $end_date = $request->input('end_date') ?? null;

        if ($start_date) {
            $start_date = (new DateTime($start_date))->format('Y-m-d');
        }
        
        if ($end_date) {
            $end_date = (new DateTime($end_date))->format('Y-m-d');
        }
        
        $user = Auth::id();
        
       $query = DB::table('tickets')
            ->where([
                ['is_active', 1],
                ['is_delete', 0],
                ['user', $user],
            ]);
         
            if ($agent !== null) {
                $query->where('agent', $agent);
            }

            if ($supplier !== null) {
                $query->where('supplier', $supplier);
            }

            if ($start_date !== null && $end_date !== null) {
                $query->whereBetween('invoice_date', [$start_date, $end_date]);
            }
            $alldata = $query->get();

        // dd($alldata, $supplier, $agent);
        $htmlTable = '';
        if($show_profit != null && $show_supplier != null && $show_agent != null) {


            if($show_profit != null && $show_supplier == null && $show_agent == null){
                $htmlTable = '
                <h2 class="text-center font-bold text-3xl my-2">Sales Report (Ticket)</h2>
                <div class="flex items-center justify-between mb-2">
                    <div class="text-lg">
                        <h2 class="font-semibold">Company Name : Sallu Air Service</h2>
                        <p><span class="font-semibold">Period Date :</span> 14-09-2023 to 15-09-2023 </p>
                    </div>
                    <div class="flex items-center">
                       
                        
                    </div>
                </div>
                <table class="table-auto w-full border-2 border-gray-400 devide-2 text-sm my-1">
                <thead>
                <tr class="border-y-2 border-black bg-cyan-700 text-white">
                    <th class="text-start">Booking Date</th>
                    <th class="text-start">Ticket No</th>
                    <th class="text-start">Passenger Name</th>
                    
                 
                    <th class="text-start">Flight Date</th>
                    <th class="text-start">Sector</th>
                    <th class="text-start">Airlines</th>
                   
                    
                    <th class="text-start">Net Markup</th>
                    <th class="text-start">Balance Amount</th>
                    
                </tr>
                </thead>
                <tbody class="divide-y-2">';

                // Loop through each record in $alldata and add a row to the table
                foreach ($alldata as $data) {
                    $htmlTable .= '<tr>
                        <td>' . (new DateTime($data->invoice_date))->format('d-m-Y') . '</td>
                        <td>' . $data->ticket_no . '</td>
                        <td>' . $data->passenger . '</td>
                      
                        <td>' . (new DateTime($data->flight_date))->format('d-m-Y') . '</td>
                        <td>' . $data->sector . '</td>
                        <td>' . $data->airline_name . '</td>
                       
                     
                        <td>' . $data->profit . '</td>
                        <td>' . $data->agent_new_amount . '</td>
                    </tr>';
                }

                // Close the HTML table
                $htmlTable .= '</tbody></table>';
            }

            elseif($show_supplier != null && $show_profit == null && $show_agent != null){
                $htmlTable = '
                <h2 class="text-center font-bold text-3xl my-2">Sales Report (Ticket)</h2>
                <div class="flex items-center justify-between mb-2">
                    <div class="text-lg">
                        <h2 class="font-semibold">Company Name : Sallu Air Service</h2>
                        <p><span class="font-semibold">Period Date :</span> 14-09-2023 to 15-09-2023 </p>
                    </div>
                    <div class="flex items-center">
                       
                        
                    </div>
                </div>
                <table class="table-auto w-full border-2 border-gray-400 devide-2 text-sm my-1">
                <thead>
                <tr class="border-y-2 border-black bg-cyan-700 text-white">
                    <th class="text-start">Booking Date</th>
                    <th class="text-start">Ticket No</th>
                    <th class="text-start">Passenger Name</th>
                    <th class="text-start">Client</th>
                    <th class="text-start">Supplier</th>
                    <th class="text-start">Flight Date</th>
                    <th class="text-start">Sector</th>
                    <th class="text-start">Airlines</th>
                    <th class="text-start">Client Price</th>
                    <th class="text-start">Supplier Price</th>
                    
                    <th class="text-start">Balance Amount</th>
                    
                </tr>
                </thead>
                <tbody class="divide-y-2">';

                // Loop through each record in $alldata and add a row to the table
                foreach ($alldata as $data) {
                    $htmlTable .= '<tr>
                        <td>' . (new DateTime($data->invoice_date))->format('d-m-Y') . '</td>
                        <td>' . $data->ticket_no . '</td>
                        <td>' . $data->passenger . '</td>
                        <td>' . Agent::where('id',$data->agent)->value('name') . '</td>
                        <td>' . Supplier::where('id',$data->supplier)->value('name') . '</td>
                        <td>' . (new DateTime($data->flight_date))->format('d-m-Y') . '</td>
                        <td>' . $data->sector . '</td>
                        <td>' . $data->airline_name . '</td>
                        <td>' . $data->agent_price . '</td>
                        <td>' . $data->supplier_price . '</td>
                       
                        <td>' . $data->agent_new_amount . '</td>
                    </tr>';
                }

                // Close the HTML table
                $htmlTable .= '</tbody></table>';
            }

            elseif($show_supplier != null && $show_profit != null && $show_agent != null){
                $htmlTable = '
                <h2 class="text-center font-bold text-3xl my-2">Sales Report (Ticket)</h2>
                <div class="flex items-center justify-between mb-2">
                    <div class="text-lg">
                        <h2 class="font-semibold">Company Name : Sallu Air Service</h2>
                        <p><span class="font-semibold">Period Date :</span> 14-09-2023 to 15-09-2023 </p>
                    </div>
                    <div class="flex items-center">
                       
                        
                    </div>
                </div>
                <table class="table-auto w-full border-2 border-gray-400 devide-2 text-sm my-1">
                <thead>
                <tr class="border-y-2 border-black bg-cyan-700 text-white">
                    <th class="text-start">Booking Date</th>
                    <th class="text-start">Ticket No</th>
                    <th class="text-start">Passenger Name</th>
                    <th class="text-start">Client</th>
                    <th class="text-start">Supplier</th>
                  
                    <th class="text-start">Flight Date</th>
                    <th class="text-start">Sector</th>
                    <th class="text-start">Airlines</th>
                    <th class="text-start">Client Price</th>
                    <th class="text-start">Supplier Price</th>
                    <th class="text-start">Net Markup</th>
                    <th class="text-start">Balance Amount</th>
                    
                </tr>
                </thead>
                <tbody class="divide-y-2">';

                // Loop through each record in $alldata and add a row to the table
                foreach ($alldata as $data) {
                    $htmlTable .= '<tr>
                        <td>' . (new DateTime($data->invoice_date))->format('d-m-Y') . '</td>
                        <td>' . $data->ticket_no . '</td>
                        <td>' . $data->passenger . '</td>
                        <td>' . Agent::where('id',$data->agent)->value('name') . '</td>
                        <td>' . Supplier::where('id',$data->agent)->value('name') . '</td>
                      
                        <td>' . (new DateTime($data->flight_date))->format('d-m-Y') . '</td>
                        <td>' . $data->sector . '</td>
                        <td>' . $data->airline_name . '</td>
                        <td>' . $data->agent_price . '</td>
                        <td>' . $data->supplier_price . '</td>
                        <td>' . $data->profit . '</td>
                        <td>' . $data->agent_new_amount . '</td>
                    </tr>';
                }

                // Close the HTML table
                $htmlTable .= '</tbody></table>';
            }
          
        }
        else{
            $htmlTable = '
            <h2 class="text-center font-bold text-3xl my-2">Sales Report (Ticket)</h2>
            <div class="flex items-center justify-between mb-2">
                <div class="text-lg">
                    <h2 class="font-semibold">Company Name : Sallu Air Service</h2>
                    <p><span class="font-semibold">Period Date :</span> 14-09-2023 to 15-09-2023 </p>
                </div>
                <div class="flex items-center">
                   
                    
                </div>
            </div>
            <table class="table-auto w-full border-2 border-gray-400 devide-2 text-sm my-1">
            <thead>
            <tr class="border-y-2 border-black bg-cyan-700 text-white">
                <th class="text-start">Booking Date</th>
                <th class="text-start">Ticket No</th>
                <th class="text-start">Passenger Name</th>
              
                <th class="text-start">Flight Date</th>
                <th class="text-start">Sector</th>
                <th class="text-start">Airlines</th>
               
                <th class="text-start">Balance Amount</th>
                
            </tr>
            </thead>
            <tbody class="divide-y-2">';

            // Loop through each record in $alldata and add a row to the table
            foreach ($alldata as $data) {
                $htmlTable .= '<tr>
                    <td>' . (new DateTime($data->invoice_date))->format('d-m-Y') . '</td>
                    <td>' . $data->ticket_no . '</td>
                    <td>' . $data->passenger . '</td>
                 
                    <td>' . (new DateTime($data->flight_date))->format('d-m-Y') . '</td>
                    <td>' . $data->sector . '</td>
                    <td>' . $data->airline_name . '</td>
                  
                    <td>' . $data->agent_new_amount . '</td>
                </tr>';
            }

            // Close the HTML table
            $htmlTable .= '</tbody></table>';

        }

        return $htmlTable;
    }
    
    public function void_ticket(){
        $user = Auth::id();
        $suppliers = Supplier::where([['is_delete',0],['is_active',1],['user',$user]])->get();
        $agents = Agent::where([['is_delete',0],['is_active',1],['user',$user]])->get();
        // $types = Type::where([['is_delete',0],['is_active',1],['user',$user]])->get();
        // $orders = Order::where([['is_delete',0],['is_active',1],['user', $user]])->get();
        return view('report.void_ticket.index', compact('suppliers', 'agents'));
    }

    public function reissue_ticket(){
        $user = Auth::id();
        $suppliers = Supplier::where([['is_delete',0],['is_active',1],['user',$user]])->get();
        $agents = Agent::where([['is_delete',0],['is_active',1],['user',$user]])->get();
        return view('report.reissue_ticket.index', compact('suppliers', 'agents'));
    }

    public function void_ticket_report(Request $request){

        $agent = $request->input('agent') ?? null;
        $supplier = $request->input('supplier') ?? null;
    
        $show_profit = $request->input('show_profit') ?? null;
        $show_supplier = $request->input('show_supplier') ?? null;
        $show_agent = $request->input('show_agent') ?? null;

        $start_date = $request->input('start_date') ?? null;
        $end_date = $request->input('end_date') ?? null;

        if ($start_date) {
            $start_date = (new DateTime($start_date))->format('Y-m-d');
        }
        
        if ($end_date) {
            $end_date = (new DateTime($end_date))->format('Y-m-d');
        }
        
        $user = Auth::id();
        
        $query = DB::table('voidticket')
            ->leftJoin('tickets', function($join) {
                $join->on('voidticket.ticket_no', '=', 'tickets.ticket_no')
                    ->where('tickets.ticket_code', '=', DB::raw('voidticket.ticket_code'));
            })
            ->where('voidticket.user', $user);
        
        if ($agent !== null) {
            $query->where('voidticket.agent', $agent);
        }
        
        if ($supplier !== null) {
            $query->where('voidticket.supplier', $supplier);
        }
        
        if ($start_date !== null && $end_date !== null) {
            $query->whereBetween('voidticket.date', [$start_date, $end_date]);
        }
        
        $alldata = $query->get();
    
        // dd($alldata, $supplier, $agent);
        $htmlTable = '';
        if($show_profit != null || $show_supplier != null || $show_agent != null) {


            if($show_profit != null && $show_supplier == null && $show_agent == null){
                $htmlTable = '
                <h2 class="text-center font-bold text-3xl my-2">Sales Report (Ticket)</h2>
                <div class="flex items-center justify-between mb-2">
                    <div class="text-lg">
                        <h2 class="font-semibold">Company Name : Sallu Air Service</h2>
                        <p><span class="font-semibold">Period Date :</span>' . $start_date . ' to ' . $end_date . '</p> 
                    </div>
                    <div class="flex items-center">
                       
                        
                    </div>
                </div>
                <table class="table-auto w-full border-2 border-gray-400 devide-2 text-sm my-1">
                <thead>
                <tr class="border-y-2 border-black bg-cyan-700 text-white">
                    <th class="text-start">Date</th>
                    <th class="text-start">Ticket No</th>
                    <th class="text-start">Passenger Name</th>
                    
                 
                    <th class="text-start">Flight Date</th>
                    <th class="text-start">Sector</th>
                    <th class="text-start">Airlines</th>
                   
                    
                    <th class="text-start">Net Markup (Void)</th>
                 
                </tr>
                </thead>
                <tbody class="divide-y-2">';

                // Loop through each record in $alldata and add a row to the table
                foreach ($alldata as $data) {
                    $htmlTable .= '<tr>
                        <td>' . (new DateTime($data->date))->format('d-m-Y') . '</td>
                        <td>' . $data->ticket_code.'-'.$data->ticket_no . '</td>
                        <td>' . $data->passenger . '</td>
                      
                        <td>' . (new DateTime($data->flight_date))->format('d-m-Y') . '</td>
                        <td>' . $data->sector . '</td>
                        <td>' . $data->airline_name . '</td>
                       
                     
                        <td>' . $data->void_profit . '</td>
                       
                    </tr>';
                }

                // Close the HTML table
                $htmlTable .= '</tbody></table>';
            }

            elseif($show_supplier != null && $show_profit == null && $show_agent != null){
                $htmlTable = '
                <h2 class="text-center font-bold text-3xl my-2">Sales Report (Ticket)</h2>
                <div class="flex items-center justify-between mb-2">
                    <div class="text-lg">
                        <h2 class="font-semibold">Company Name : Sallu Air Service</h2>
                        <p><span class="font-semibold">Period Date :</span>' . $start_date . ' to ' . $end_date . '</p> 
                    </div>
                    <div class="flex items-center">
                       
                        
                    </div>
                </div>
                <table class="table-auto w-full border-2 border-gray-400 devide-2 text-sm my-1">
                <thead>
                <tr class="border-y-2 border-black bg-cyan-700 text-white">
                    <th class="text-start">Date</th>
                    <th class="text-start">Ticket No</th>
                    <th class="text-start">Passenger Name</th>
                    <th class="text-start">Client</th>
                    <th class="text-start">Supplier</th>
                    <th class="text-start">Flight Date</th>
                    <th class="text-start">Sector</th>
                    <th class="text-start">Airlines</th>
                    <th class="text-start">Client New Price</th>
                    <th class="text-start">Client Previous Price</th>
                    <th class="text-start">Supplier New Price</th>
                    <th class="text-start">Supplier Previous Price</th>
                 
                </tr>
                </thead>
                <tbody class="divide-y-2">';

                // Loop through each record in $alldata and add a row to the table
                foreach ($alldata as $data) {
                    $htmlTable .= '<tr>
                        <td>' . (new DateTime($data->date))->format('d-m-Y') . '</td>
                        <td>' . $data->ticket_code.'-'.$data->ticket_no . '</td>
                        <td>' . $data->passenger . '</td>
                        <td>' . Agent::where('id',$data->agent)->value('name') . '</td>
                        <td>' . Supplier::where('id',$data->supplier)->value('name') . '</td>
                        <td>' . (new DateTime($data->flight_date))->format('d-m-Y') . '</td>
                        <td>' . $data->sector . '</td>
                        <td>' . $data->airline_name . '</td>
                        <td>' . $data->now_agent_fere . '</td>
                        <td>' . $data->prev_agent_amount . '</td>
                        <td>' . $data->now_supplier_fare . '</td>
                        <td>' . $data->prev_supply_amount . '</td>
                       
                    </tr>';
                }

                // Close the HTML table
                $htmlTable .= '</tbody></table>';
            }

            elseif($show_supplier != null && $show_profit != null && $show_agent != null){
                $htmlTable = '
                <h2 class="text-center font-bold text-3xl my-2">Sales Report (Ticket)</h2>
                <div class="flex items-center justify-between mb-2">
                    <div class="text-lg">
                        <h2 class="font-semibold">Company Name : Sallu Air Service</h2>
                        <p><span class="font-semibold">Period Date :</span>' . $start_date . ' to ' . $end_date . '</p> 
                    </div>
                    <div class="flex items-center">
                       
                        
                    </div>
                </div>
                <table class="table-auto w-full border-2 border-gray-400 devide-2 text-sm my-1">
                <thead>
                <tr class="border-y-2 border-black bg-cyan-700 text-white">
                    <th class="text-start">Date</th>
                    <th class="text-start">Ticket No</th>
                    <th class="text-start">Passenger Name</th>
                    <th class="text-start">Client</th>
                    <th class="text-start">Supplier</th>
                  
                    <th class="text-start">Flight Date</th>
                    <th class="text-start">Sector</th>
                    <th class="text-start">Airlines</th>
                    <th class="text-start">Client New Price</th>
                    <th class="text-start">Client Previous Price</th>
                    <th class="text-start">Supplier New Price</th>
                    <th class="text-start">Supplier Previous Price</th>
                    <th class="text-start">Net Markup (Void)</th>
                    
                </tr>
                </thead>
                <tbody class="divide-y-2">';

                // Loop through each record in $alldata and add a row to the table
                foreach ($alldata as $data) {
                    $htmlTable .= '<tr>
                        <td>' . (new DateTime($data->date))->format('d-m-Y') . '</td>
                        <td>' . $data->ticket_code.'-'.$data->ticket_no . '</td>
                        <td>' . $data->passenger . '</td>
                        <td>' . Agent::where('id',$data->agent)->value('name') . '</td>
                        <td>' . Supplier::where('id',$data->agent)->value('name') . '</td>
                      
                        <td>' . (new DateTime($data->flight_date))->format('d-m-Y') . '</td>
                        <td>' . $data->sector . '</td>
                        <td>' . $data->airline_name . '</td>
                        <td>' . $data->now_agent_fere . '</td>
                        <td>' . $data->prev_agent_amount . '</td>
                        <td>' . $data->now_supplier_fare . '</td>
                        <td>' . $data->prev_supply_amount . '</td>
                        <td>' . $data->void_profit . '</td>
                    </tr>';
                }

                // Close the HTML table
                $htmlTable .= '</tbody></table>';
            }
          
        }
        else{
            $htmlTable = '
            <h2 class="text-center font-bold text-3xl my-2">Sales Report (Ticket)</h2>
            <div class="flex items-center justify-between mb-2">
                <div class="text-lg">
                    <h2 class="font-semibold">Company Name : Sallu Air Service</h2>
                    <p><span class="font-semibold">Period Date :</span>' . $start_date . ' to ' . $end_date . '</p> 
                    </div>
                <div class="flex items-center">
                   
                    
                </div>
            </div>
            <table class="table-auto w-full border-2 border-gray-400 devide-2 text-sm my-1">
            <thead>
            <tr class="border-y-2 border-black bg-cyan-700 text-white">
                <th class="text-start"> Date</th>
                <th class="text-start">Ticket No</th>
                <th class="text-start">Passenger Name</th>
              
                <th class="text-start">Flight Date</th>
                <th class="text-start">Sector</th>
                <th class="text-start">Airlines</th>
               
               
            </tr>
            </thead>
            <tbody class="divide-y-2">';

            // Loop through each record in $alldata and add a row to the table
            foreach ($alldata as $data) {
                $htmlTable .= '<tr>
                    <td>' . (new DateTime($data->invoice_date))->format('d-m-Y') . '</td>
                    <td>' . $data->ticket_code.'-'.$data->ticket_no . '</td>
                    <td>' . $data->passenger . '</td>
                 
                    <td>' . (new DateTime($data->flight_date))->format('d-m-Y') . '</td>
                    <td>' . $data->sector . '</td>
                    <td>' . $data->airline_name . '</td>
                </tr>';
            }

            // Close the HTML table
            $htmlTable .= '</tbody></table>';

        }

        return $htmlTable;
    }

    public function reissue_ticket_report(Request $request){

        $agent = $request->input('agent') ?? null;
        $supplier = $request->input('supplier') ?? null;
    
        $show_profit = $request->input('show_profit') ?? null;
        $show_supplier = $request->input('show_supplier') ?? null;
        $show_agent = $request->input('show_agent') ?? null;

        $start_date = $request->input('start_date') ?? null;
        $end_date = $request->input('end_date') ?? null;

        if ($start_date) {
            $start_date = (new DateTime($start_date))->format('Y-m-d');
        }
        
        if ($end_date) {
            $end_date = (new DateTime($end_date))->format('Y-m-d');
        }
        
        $user = Auth::id();
        
        $query = DB::table('reissue')
            ->leftJoin('tickets', function($join) {
                $join->on('reissue.ticket_no', '=', 'tickets.ticket_no')
                    ->where('tickets.ticket_code', '=', DB::raw('reissue.ticket_code'));
            })
            ->where('reissue.user', $user);
        
        if ($agent !== null) {
            $query->where('reissue.agent', $agent);
        }
        
        if ($supplier !== null) {
            $query->where('reissue.supplier', $supplier);
        }
        
        if ($start_date !== null && $end_date !== null) {
            $query->whereBetween('reissue.date', [$start_date, $end_date]);
        }
        
        $alldata = $query->get();
    
        // dd($alldata, $supplier, $agent);
        $htmlTable = '';
        if($show_profit != null || $show_supplier != null || $show_agent != null) {


            if($show_profit != null && $show_supplier == null && $show_agent == null){
                $htmlTable = '
                <h2 class="text-center font-bold text-3xl my-2">Sales Report (Ticket)</h2>
                <div class="flex items-center justify-between mb-2">
                    <div class="text-lg">
                        <h2 class="font-semibold">Company Name : Sallu Air Service</h2>
                        <p><span class="font-semibold">Period Date :</span>' . $start_date . ' to ' . $end_date . '</p> 
                    </div>
                    <div class="flex items-center">
                       
                        
                    </div>
                </div>
                <table class="table-auto w-full border-2 border-gray-400 devide-2 text-sm my-1">
                <thead>
                <tr class="border-y-2 border-black bg-cyan-700 text-white">
                    <th class="text-start">Date</th>
                    <th class="text-start">Ticket No</th>
                    <th class="text-start">Passenger Name</th>
                    
                 
                    <th class="text-start">Flight Date</th>
                    <th class="text-start">Sector</th>
                    <th class="text-start">Airlines</th>
                   
                    
                    <th class="text-start">Net Markup (Void)</th>
                 
                </tr>
                </thead>
                <tbody class="divide-y-2">';

                // Loop through each record in $alldata and add a row to the table
                foreach ($alldata as $data) {
                    $htmlTable .= '<tr>
                        <td>' . (new DateTime($data->date))->format('d-m-Y') . '</td>
                        <td>' . $data->ticket_code.'-'.$data->ticket_no . '</td>
                        <td>' . $data->passenger . '</td>
                      
                        <td>' . (new DateTime($data->flight_date))->format('d-m-Y') . '</td>
                        <td>' . $data->sector . '</td>
                        <td>' . $data->airline_name . '</td>
                       
                     
                        <td>' . $data->void_profit . '</td>
                       
                    </tr>';
                }

                // Close the HTML table
                $htmlTable .= '</tbody></table>';
            }

            elseif($show_supplier != null && $show_profit == null && $show_agent != null){
                $htmlTable = '
                <h2 class="text-center font-bold text-3xl my-2">Sales Report (Ticket)</h2>
                <div class="flex items-center justify-between mb-2">
                    <div class="text-lg">
                        <h2 class="font-semibold">Company Name : Sallu Air Service</h2>
                        <p><span class="font-semibold">Period Date :</span>' . $start_date . ' to ' . $end_date . '</p> 
                    </div>
                    <div class="flex items-center">
                       
                        
                    </div>
                </div>
                <table class="table-auto w-full border-2 border-gray-400 devide-2 text-sm my-1">
                <thead>
                <tr class="border-y-2 border-black bg-cyan-700 text-white">
                    <th class="text-start">Date</th>
                    <th class="text-start">Ticket No</th>
                    <th class="text-start">Passenger Name</th>
                    <th class="text-start">Client</th>
                    <th class="text-start">Supplier</th>
                    <th class="text-start">Flight Date</th>
                    <th class="text-start">Sector</th>
                    <th class="text-start">Airlines</th>
                    <th class="text-start">Client New Price</th>
                    <th class="text-start">Client Previous Price</th>
                    <th class="text-start">Supplier New Price</th>
                    <th class="text-start">Supplier Previous Price</th>
                 
                </tr>
                </thead>
                <tbody class="divide-y-2">';

                // Loop through each record in $alldata and add a row to the table
                foreach ($alldata as $data) {
                    $htmlTable .= '<tr>
                        <td>' . (new DateTime($data->date))->format('d-m-Y') . '</td>
                        <td>' . $data->ticket_code.'-'.$data->ticket_no . '</td>
                        <td>' . $data->passenger . '</td>
                        <td>' . Agent::where('id',$data->agent)->value('name') . '</td>
                        <td>' . Supplier::where('id',$data->supplier)->value('name') . '</td>
                        <td>' . (new DateTime($data->flight_date))->format('d-m-Y') . '</td>
                        <td>' . $data->sector . '</td>
                        <td>' . $data->airline_name . '</td>
                        <td>' . $data->now_agent_fere . '</td>
                        <td>' . $data->prev_agent_amount . '</td>
                        <td>' . $data->now_supplier_fare . '</td>
                        <td>' . $data->prev_supply_amount . '</td>
                       
                    </tr>';
                }

                // Close the HTML table
                $htmlTable .= '</tbody></table>';
            }

            elseif($show_supplier != null && $show_profit != null && $show_agent != null){
                $htmlTable = '
                <h2 class="text-center font-bold text-3xl my-2">Sales Report (Ticket)</h2>
                <div class="flex items-center justify-between mb-2">
                    <div class="text-lg">
                        <h2 class="font-semibold">Company Name : Sallu Air Service</h2>
                        <p><span class="font-semibold">Period Date :</span>' . $start_date . ' to ' . $end_date . '</p> 
                    </div>
                    <div class="flex items-center">
                       
                        
                    </div>
                </div>
                <table class="table-auto w-full border-2 border-gray-400 devide-2 text-sm my-1">
                <thead>
                <tr class="border-y-2 border-black bg-cyan-700 text-white">
                    <th class="text-start">Date</th>
                    <th class="text-start">Ticket No</th>
                    <th class="text-start">Passenger Name</th>
                    <th class="text-start">Client</th>
                    <th class="text-start">Supplier</th>
                  
                    <th class="text-start">Flight Date</th>
                    <th class="text-start">Sector</th>
                    <th class="text-start">Airlines</th>
                    <th class="text-start">Client New Price</th>
                    <th class="text-start">Client Previous Price</th>
                    <th class="text-start">Supplier New Price</th>
                    <th class="text-start">Supplier Previous Price</th>
                    <th class="text-start">Net Markup (Void)</th>
                    
                </tr>
                </thead>
                <tbody class="divide-y-2">';

                // Loop through each record in $alldata and add a row to the table
                foreach ($alldata as $data) {
                    $htmlTable .= '<tr>
                        <td>' . (new DateTime($data->date))->format('d-m-Y') . '</td>
                        <td>' . $data->ticket_code.'-'.$data->ticket_no . '</td>
                        <td>' . $data->passenger . '</td>
                        <td>' . Agent::where('id',$data->agent)->value('name') . '</td>
                        <td>' . Supplier::where('id',$data->agent)->value('name') . '</td>
                      
                        <td>' . (new DateTime($data->flight_date))->format('d-m-Y') . '</td>
                        <td>' . $data->sector . '</td>
                        <td>' . $data->airline_name . '</td>
                        <td>' . $data->now_agent_fere . '</td>
                        <td>' . $data->prev_agent_amount . '</td>
                        <td>' . $data->now_supplier_fare . '</td>
                        <td>' . $data->prev_supply_amount . '</td>
                        <td>' . $data->void_profit . '</td>
                    </tr>';
                }

                // Close the HTML table
                $htmlTable .= '</tbody></table>';
            }
          
        }
        else{
            $htmlTable = '
            <h2 class="text-center font-bold text-3xl my-2">Sales Report (Ticket)</h2>
            <div class="flex items-center justify-between mb-2">
                <div class="text-lg">
                    <h2 class="font-semibold">Company Name : Sallu Air Service</h2>
                    <p><span class="font-semibold">Period Date :</span>' . $start_date . ' to ' . $end_date . '</p> 
                    </div>
                <div class="flex items-center">
                   
                    
                </div>
            </div>
            <table class="table-auto w-full border-2 border-gray-400 devide-2 text-sm my-1">
            <thead>
            <tr class="border-y-2 border-black bg-cyan-700 text-white">
                <th class="text-start"> Date</th>
                <th class="text-start">Ticket No</th>
                <th class="text-start">Passenger Name</th>
              
                <th class="text-start">Flight Date</th>
                <th class="text-start">Sector</th>
                <th class="text-start">Airlines</th>
               
               
            </tr>
            </thead>
            <tbody class="divide-y-2">';

            // Loop through each record in $alldata and add a row to the table
            foreach ($alldata as $data) {
                $htmlTable .= '<tr>
                    <td>' . (new DateTime($data->invoice_date))->format('d-m-Y') . '</td>
                    <td>' . $data->ticket_code.'-'.$data->ticket_no . '</td>
                    <td>' . $data->passenger . '</td>
                 
                    <td>' . (new DateTime($data->flight_date))->format('d-m-Y') . '</td>
                    <td>' . $data->sector . '</td>
                    <td>' . $data->airline_name . '</td>
                </tr>';
            }

            // Close the HTML table
            $htmlTable .= '</tbody></table>';

        }

        return $htmlTable;
    }

    public function sales_analysis(){
        return view('report.sales_analysis.index');
    }

    public function sales_analysis_report(Request $request){

        $start_date = $request->input('start_date') ?? null;
        $end_date = $request->input('end_date') ?? null;
        if ($start_date) {
            $start_date = (new DateTime($start_date))->format('Y-m-d');
        }
        
        if ($end_date) {
            $end_date = (new DateTime($end_date))->format('Y-m-d');
        }
        
        $user = Auth::id();
        
        $query1 = Receiver::where('user', $user);
        $query2 = Payment::where('user', $user);
        $query3 = Ticket::where('user', $user);
        // $query4 = Ticket::where('user', $user);
        
        // Apply date checks if start_date and/or end_date are provided
        if ($start_date) {
            $query1->whereDate('date', '>=', $start_date);
            $query2->whereDate('date', '>=', $start_date);
            $query3->whereDate('invoice_date', '>=', $start_date);
            // $query4->whereDate('invoice_date', '>=', $start_date);
        }
        
        if ($end_date) {
            $query1->whereDate('date', '<=', $end_date);
            $query2->whereDate('date', '<=', $end_date);
            $query3->whereDate('invoice_date', '<=', $end_date);
            // $query4->whereDate('invoice_date', '<=', $end_date);
        }
        
        // Fetch results from the queries
        $results1 = $query1->get();
        $results2 = $query2->get();
        $results3 = $query3->get();
        // $results4 = $query4->get();
      

        $sumsByDay = [
            'receivetotalAmount' => [],
            'salestotalAmount' => [],
            'purchasetotalAmount' => [],
            'profittotalAmount' => [],
            'paymenttotalAmount' => []
        ];

        // Calculate sums day-wise
        foreach ($results1 as $result) {
            $createdAt = $result->date; // Assuming 'created_at' is a DateTime object
            $amount = $result->amount; // Assuming 'amount' is the attribute containing the amount value
            $type = 'receivetotalAmount'; // Assuming 'type' is the attribute indicating receive or payment

            // Initialize the sum for the day if it doesn't exist
            if (!isset($sumsByDay[$type][$createdAt])) {
                $sumsByDay[$type][$createdAt] = 0;
            }

            // Update the sum for the day
            $sumsByDay[$type][$createdAt] += $amount;
        }
        // Calculate sums day-wise
        foreach ($results2 as $result) {
            $createdAt = $result->date; // Assuming 'created_at' is a DateTime object
            $amount = $result->amount; // Assuming 'amount' is the attribute containing the amount value
            $type = 'paymenttotalAmount'; // Assuming 'type' is the attribute indicating receive or payment

            // Initialize the sum for the day if it doesn't exist
            if (!isset($sumsByDay[$type][$createdAt])) {
                $sumsByDay[$type][$createdAt] = 0;
            }

            // Update the sum for the day
            $sumsByDay[$type][$createdAt] += $amount;
        }

        foreach ($results3 as $result) {
            $createdAt = $result->invoice_date; // Assuming 'created_at' is a DateTime object
            $sale = $result->agent_price; // Assuming 'agent_price' is the attribute containing the sales price
            $purchase = $result->supplier_price; // Assuming 'supplier_price' is the attribute containing the purchase price

            // Calculate profit
            $profit = $result->profit;

            // Initialize the sum for the day if it doesn't exist
            if (!isset($sumsByDay['salestotalAmount'][$createdAt])) {
                $sumsByDay['salestotalAmount'][$createdAt] = 0;
            }
            if (!isset($sumsByDay['purchasetotalAmount'][$createdAt])) {
                $sumsByDay['purchasetotalAmount'][$createdAt] = 0;
            }
            if (!isset($sumsByDay['profittotalAmount'][$createdAt])) {
                $sumsByDay['profittotalAmount'][$createdAt] = 0;
            }

            // Update the sums for the day
            $sumsByDay['salestotalAmount'][$createdAt] += $sale;
            $sumsByDay['purchasetotalAmount'][$createdAt] += $purchase;
            $sumsByDay['profittotalAmount'][$createdAt] += $profit;
        }


        $tableData = [];

        foreach ($sumsByDay['receivetotalAmount'] as $day => $receivetotalAmount) {
            $salesAmount = isset($sumsByDay['salestotalAmount'][$day]) ? $sumsByDay['salestotalAmount'][$day] : 0;
            $purchaseAmount = isset($sumsByDay['purchasetotalAmount'][$day]) ? $sumsByDay['purchasetotalAmount'][$day] : 0;
            $profitAmount = isset($sumsByDay['profittotalAmount'][$day]) ? $sumsByDay['profittotalAmount'][$day] : 0;
            $paymentAmount = isset($sumsByDay['paymenttotalAmount'][$day]) ? $sumsByDay['paymenttotalAmount'][$day] : 0;

            $tableData[] = [
                'date' => $day,
                'receivetotalAmount' => $receivetotalAmount,
                'salestotalAmount' => $salesAmount,
                'purchasetotalAmount' => $purchaseAmount,
                'profittotalAmount' => $profitAmount,
                'paymenttotalAmount' => $paymentAmount,
            ];
        }



        return view('report.sales_analysis.index', compact('tableData', 'start_date', 'end_date'));
    }
    public function sales_exicutive_stuff(){

        $user = Auth::id();
        $stuffs = Ticket::where('is_delete', 0)
                ->where('is_active', 1)
                ->distinct()
                ->pluck('stuff');

        $suppliers = Supplier::where([['is_delete',0],['is_active',1],['user',$user]])->get();
        $agents = Agent::where([['is_delete',0],['is_active',1],['user',$user]])->get();

        // dd($stuffs);
        return view('report.sales_exicutive_stuff.index', compact('stuffs', 'agents', 'suppliers'));
    }

    public function seles_executive_report_stuff(Request $request)
    {
        // dd($request->all());
        $stuff = $request->stuff;

        $agent = $request->input('agent') ?? null;
        $supplier = $request->input('supplier') ?? null;
    
        $show_profit = $request->input('show_profit') ?? null;
        $show_supplier = $request->input('show_supplier') ?? null;
        $show_agent = $request->input('show_agent') ?? null;

        $start_date = $request->input('start_date') ?? null;
        $end_date = $request->input('end_date') ?? null;

        if ($start_date) {
            $start_date = (new DateTime($start_date))->format('Y-m-d');
        }
        
        if ($end_date) {
            $end_date = (new DateTime($end_date))->format('Y-m-d');
        }
        
        $user = Auth::id();
        
       $query = DB::table('tickets')
            ->where([
                ['is_active', 1],
                ['is_delete', 0],
                ['user', $user],
            ]);

            if($stuff !== null) {
                $query->where('stuff', $stuff);
            }
         
            if ($agent !== null) {
                $query->where('agent', $agent);
            }

            if ($supplier !== null) {
                $query->where('supplier', $supplier);
            }

            if ($start_date !== null && $end_date !== null) {
                $query->whereBetween('invoice_date', [$start_date, $end_date]);
            }
            $alldata = $query->get();
            $groupedData = $alldata->groupBy('stuff');


        // dd($groupedData, $supplier, $agent, $stuff, $show_profit, $show_supplier, $show_agent);
        $htmlTable = '';
        if($show_profit != null || $show_supplier != null || $show_agent != null) {

            // dd("asffa");
            if($show_profit != null && $show_supplier == null && $show_agent == !null){
                // dd("asda");
                $htmlTable = ''; // Initialize variable to hold HTML tables
                
                // Loop through each group
                // dd($groupedData);
                foreach ($groupedData as $stuff => $group) {
                    $count = 0;
                    // dd($group, $stuff);
                    // Add stuff name as a banner above the tickets
                    
                    $htmlTable .= '<div style="margin-bottom: 20px;">';
                    $htmlTable .= '<h2 class="text-center text-success p-5" style="font-weight:bold; color: green; background-color: #454550">' . $stuff . '</h2>';

                    // Start a new table for each group
                    $htmlTable .= '<table border="1" class="w-100">
                        <thead>
                            <tr class="border-y-2 border-black bg-cyan-700 text-white">
                                <th class="text-start">Booking Date</th>
                                <th class="text-start">Ticket No</th>
                                <th class="text-start">Passenger Name</th>
                                <th class="text-start">Flight Date</th>
                                <th class="text-start">Sector</th>
                                <th class="text-start">Airlines</th>
                                <th class="text-start">Agent</th>
                                <th class="text-start">Net Markup</th>
                                <th class="text-start">Balance Amount</th>
                            </tr>
                        </thead>
                        <tbody>';

                    // Loop through each record in the group and add a row to the table
                    foreach ($group as $data) {
                        if($data->stuff == $stuff){
                            $count++;
                        }
                        $htmlTable .= '<tr>
                            <td>' . (new DateTime($data->invoice_date))->format('d-m-Y') . '</td>
                            <td>' . $data->ticket_no . '</td>
                            <td>' . $data->passenger . '</td>
                            <td>' . (new DateTime($data->flight_date))->format('d-m-Y') . '</td>
                            <td>' . $data->sector . '</td>
                            <td>' . $data->airline_name . '</td>
                            <td>' . Agent::where('id', $data->agent)->value('name') . '</td>
                            <td>' . $data->profit . '</td>
                            <td>' . $data->agent_new_amount . '</td>
                        </tr>';
                    }
                    $htmlTable .= '<tr class="w-100" style="background:#6a8099; "><td colspan="10"><b>Total Ticket: ' . $count . '</b></td></tr>';

                    // Close the HTML table and the stuff banner
                    $htmlTable .= '</tbody></table>';
                    $htmlTable .= '</div>';
                                
                }
                // dd($htmlTable);
            }

            elseif($show_supplier != null && $show_profit == null && $show_agent != null){
                $htmlTable = ''; // Initialize variable to hold HTML tables
                
                // Loop through each group
                // dd($groupedData);
                foreach ($groupedData as $stuff => $group) {
                    $count = 0;
                    // dd($group, $stuff);
                    // Add stuff name as a banner above the tickets
                    
                    $htmlTable .= '<div style="margin-bottom: 20px;">';
                    $htmlTable .= '<h2 class="text-center text-success p-5" style="font-weight:bold; color: green; background-color: #454550">' . $stuff . '</h2>';

                    // Start a new table for each group
                    $htmlTable .= '<table border="1" class="w-100">
                        <thead>
                            <tr class="border-y-2 border-black bg-cyan-700 text-white">
                                <th class="text-start">Booking Date</th>
                                <th class="text-start">Ticket No</th>
                                <th class="text-start">Passenger Name</th>
                                <th class="text-start">Flight Date</th>
                                <th class="text-start">Sector</th>
                                <th class="text-start">Airlines</th>
                                <th class="text-start">Agent</th>
                                <th class="text-start">Agent Amount</th>
                                <th class="text-start">Supplier</th>
                                <th class="text-start">Supplier Amount</th>
                            </tr>
                        </thead>
                        <tbody>';

                    // Loop through each record in the group and add a row to the table
                    foreach ($group as $data) {
                        if($data->stuff == $stuff){
                            $count++;
                        }
                        $htmlTable .= '<tr>
                            <td>' . (new DateTime($data->invoice_date))->format('d-m-Y') . '</td>
                            <td>' . $data->ticket_no . '</td>
                            <td>' . $data->passenger . '</td>
                            <td>' . (new DateTime($data->flight_date))->format('d-m-Y') . '</td>
                            <td>' . $data->sector . '</td>
                            <td>' . $data->airline_name . '</td>
                            <td>' . Agent::where('id', $data->agent)->value('name') . '</td>
                            <td>' . $data->agent_new_amount . '</td>
                            <td>' . Supplier::where('id', $data->supplier)->value('name') . '</td>
                            <td>' . $data->supplier_new_amount . '</td>
                        </tr>';
                    }
                    $htmlTable .= '<tr class="w-100" style="background:#6a8099; "><td colspan="10"><b>Total Ticket: ' . $count . '</b></td></tr>';

                    // Close the HTML table and the stuff banner
                    $htmlTable .= '</tbody></table>';
                    $htmlTable .= '</div>';
                                
                }
            }

            elseif($show_supplier != null && $show_profit != null && $show_agent != null){
                
                $htmlTable = ''; // Initialize variable to hold HTML tables
                
                // Loop through each group
                // dd($groupedData);
                foreach ($groupedData as $stuff => $group) {
                    $count = 0;
                    // dd($group, $stuff);
                    // Add stuff name as a banner above the tickets
                    
                    $htmlTable .= '<div style="margin-bottom: 20px;">';
                    $htmlTable .= '<h2 class="text-center text-success p-5" style="font-weight:bold; color: green; background-color: #454550">' . $stuff . '</h2>';

                    // Start a new table for each group
                    $htmlTable .= '<table border="1" class="w-100">
                        <thead>
                            <tr class="border-y-2 border-black bg-cyan-700 text-white">
                                <th class="text-start">Booking Date</th>
                                <th class="text-start">Ticket No</th>
                                <th class="text-start">Passenger Name</th>
                                <th class="text-start">Flight Date</th>
                                <th class="text-start">Sector</th>
                                <th class="text-start">Airlines</th>
                                <th class="text-start">Agent</th>
                                <th class="text-start">Agent Amount</th>
                                <th class="text-start">Supplier</th>
                                <th class="text-start">Supplier Amount</th>
                                <th class="text-start">Net Markup</th>
                            </tr>
                        </thead>
                        <tbody>';

                    // Loop through each record in the group and add a row to the table
                    foreach ($group as $data) {
                        if($data->stuff == $stuff){
                            $count++;
                        }
                        $htmlTable .= '<tr>
                            <td>' . (new DateTime($data->invoice_date))->format('d-m-Y') . '</td>
                            <td>' . $data->ticket_no . '</td>
                            <td>' . $data->passenger . '</td>
                            <td>' . (new DateTime($data->flight_date))->format('d-m-Y') . '</td>
                            <td>' . $data->sector . '</td>
                            <td>' . $data->airline_name . '</td>
                            <td>' . Agent::where('id', $data->agent)->value('name') . '</td>
                            <td>' . $data->agent_new_amount . '</td>
                            <td>' . Supplier::where('id', $data->supplier)->value('name') . '</td>
                            <td>' . $data->supplier_new_amount . '</td>
                            <td>' . $data->profit . '</td>

                        </tr>';
                    }
                    $htmlTable .= '<tr class="w-100" style="background:#6a8099; "><td colspan="11"><b>Total Ticket: ' . $count . '</b></td></tr>';

                    // Close the HTML table and the stuff banner
                    $htmlTable .= '</tbody></table>';
                    $htmlTable .= '</div>';
                                
                }
            
            }
          
        }
        else{
                
            $htmlTable = ''; // Initialize variable to hold HTML tables
            
            // Loop through each group
            // dd($groupedData);
            foreach ($groupedData as $stuff => $group) {
                $count = 0;
                // dd($group, $stuff);
                // Add stuff name as a banner above the tickets
                
                $htmlTable .= '<div style="margin-bottom: 20px;">';
                $htmlTable .= '<h2 class="text-center text-success p-5" style="font-weight:bold; color: green; background-color: #454550">' . $stuff . '</h2>';

                // Start a new table for each group
                $htmlTable .= '<table border="1" class="w-100">
                    <thead>
                        <tr class="border-y-2 border-black bg-cyan-700 text-white">
                            <th class="text-start">Booking Date</th>
                            <th class="text-start">Ticket No</th>
                            <th class="text-start">Passenger Name</th>
                            <th class="text-start">Flight Date</th>
                            <th class="text-start">Sector</th>
                            <th class="text-start">Airlines</th>
                           
                        </tr>
                    </thead>
                    <tbody>';

                // Loop through each record in the group and add a row to the table
                foreach ($group as $data) {
                    if($data->stuff == $stuff){
                        $count++;
                    }
                    $htmlTable .= '<tr>
                        <td>' . (new DateTime($data->invoice_date))->format('d-m-Y') . '</td>
                        <td>' . $data->ticket_no . '</td>
                        <td>' . $data->passenger . '</td>
                        <td>' . (new DateTime($data->flight_date))->format('d-m-Y') . '</td>
                        <td>' . $data->sector . '</td>
                        <td>' . $data->airline_name . '</td>
                       

                    </tr>';
                }
                $htmlTable .= '<tr class="w-100" style="background:#6a8099; "><td colspan="11"><b>Total Ticket: ' . $count . '</b></td></tr>';

                // Close the HTML table and the stuff banner
                $htmlTable .= '</tbody></table>';
                $htmlTable .= '</div>';
                            
            }
        
        }

        return $htmlTable;
    }
}
