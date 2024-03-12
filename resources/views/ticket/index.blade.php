<x-app-layout>
    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <h2 class="text-4xl py-4 flex justify-center font-extrabold bg-[#01919A] text-white">
        Ticket Invoicing
    </h2>
    <div class="flex flex-col justify-center items-center p-6 rounded-lg shadow-md xl:w-3/4 lg:w-4/4 w-full mx-auto my-2">

        <form class="w-full " id="ticket_form">
            @csrf
            <div class="flex flex-wrap xl:gap-x-7 lg:gap-x-2 md:gap-x-2 sm:gap-x-0 -mx-4 mb-4">
                <div class="w-full md:w-[48%] px-4 mb-2 flex items-center">
                    <label for="invoice_no" class="block w-[50%]">Invoice No.</label>
                    <input type="text" id="invoice_no"
                        class="text-center text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                        name="invoice_no" readonly>
                </div>
                <div class="w-full md:w-[48%] px-4 mb-2 flex items-center">
                    <label for="agent_name" class="block w-[50%]">Agent
                        Name</label>

                    <select name="agent" id="agent"
                        class="bg-gray-50 border select2 border-gray-300 text-gray-900 text-sm  focus:ring-blue-500 focus:border-blue-500 block max-w-[60%] p-1">
                        <option value="">Select Agent</option>
                        @foreach ($agents as $agent)
                            <option value="{{ $agent->id }}">{{ $agent->name }}</option>
                        @endforeach
                    </select>

                </div>
            </div>

            <div class="flex flex-wrap xl:gap-x-7 lg:gap-x-2 md:gap-x-2 sm:gap-x-0 -mx-4 mb-4">
                <div class="w-full md:w-[48%] px-4 mb-2 flex items-center">
                    <label for="invoice_date" class="block w-[50%]">Invoice
                        Date</label>
                    <input type="date" id="invoice_date"
                        class=" bg-gray-50 border border-gray-300 text-gray-900 text-sm  focus:ring-blue-500 focus:border-blue-500 block w-full p-1"
                        name="invoice_date">
                </div>
                <div class="w-full md:w-[48%] px-4 mb-2 flex items-center">
                    <label for="flight_date" class="w-[50%]">Flight
                        Date</label>
                    <input type="date" id="flight_date"
                        class=" bg-gray-50 border border-gray-300 text-gray-900 text-sm  focus:ring-blue-500 focus:border-blue-500 block w-full p-1"
                        name="flight_date">
                </div>
            </div>
            <div class="flex flex-wrap xl:gap-x-7 lg:gap-x-2 md:gap-x-2 sm:gap-x-0 -mx-4 mb-4">
                <div class="w-full md:w-[48%] px-4 mb-2 flex items-center">
                    <label for="sector" class="w-[50%]">Sector</label>
                    <input type="text" id="sector"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm  focus:ring-blue-500 focus:border-blue-500 block w-full p-1"
                        name="sector">
                </div>
                <div class="w-full md:w-[48%] px-4 mb-2 flex items-center">
                    <label for="flight_no" class="w-[50%]">Flight No</label>
                    <input type="text" id="flight_no"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm  focus:ring-blue-500 focus:border-blue-500 block w-full p-1"
                        name="flight_no">
                </div>
            </div>
            <div class="flex flex-wrap xl:gap-x-7 lg:gap-x-2 md:gap-x-2 sm:gap-x-0 -mx-4 mb-4">
                <div class="w-full md:w-[48%] px-4 mb-2 flex items-center">
                    <label for="ticket_no" class="w-[50%]">Ticket No /
                        PNR</label>
                    <div class="flex w-full gap-x-4">
                        <input type="text" id="ticket_code"
                            class="bg-gray-50 w-[23%] border border-gray-300 text-gray-900 text-sm  focus:ring-blue-500 focus:border-blue-500 block p-1"
                            name="ticket_code">
                        <input type="text" id="ticket_no" maxlength="10" minlength="10"
                            class="bg-gray-50 w-[73%] border border-gray-300 text-gray-900 text-sm  focus:ring-blue-500 focus:border-blue-500 block p-1"
                            name="ticket_no">
                    </div>
                </div>
                <div class="w-full md:w-[48%] px-4 mb-2 flex items-center">
                    <label for="number_of_tickets" class="w-[50%]">Number of
                        Tickets</label>
                    <input type="number" id="number_of_tickets"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm  focus:ring-blue-500 focus:border-blue-500 block w-full p-1"
                        name="number_of_tickets">
                </div>
            </div>
            <div class="flex flex-wrap xl:gap-x-7 lg:gap-x-2 md:gap-x-2 sm:gap-x-0 -mx-4 mb-4">
                <div class="w-full md:w-[48%] px-4 mb-2 flex items-center">
                    <label for="passenger_name" class="w-[50%]">Passenger
                        Name</label>
                    <input type="text" id="passenger_name"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm  focus:ring-blue-500 focus:border-blue-500 block w-full p-1"
                        name="passenger_name">
                </div>
                <div class="w-full md:w-[48%] px-4 mb-2 flex items-center">
                    <label for="airline" class="w-[50%]">Airline</label>
                    <div class="flex w-full gap-x-3">
                        <input type="text" id="airlines_name"
                            class="bg-gray-50 w-[73%] border border-gray-300 text-gray-900 text-sm  focus:ring-blue-500 focus:border-blue-500 block p-1"
                            name="airlines_name">
                        <input type="text" id="airlines_code"
                            class="bg-gray-50 w-[23%] border border-gray-300 text-gray-900 text-sm  focus:ring-blue-500 focus:border-blue-500 block p-1"
                            name="airlines_code">
                    </div>
                </div>
            </div>
            <div class="flex flex-wrap xl:gap-x-7 lg:gap-x-2 md:gap-x-2 sm:gap-x-0 -mx-4 mb-4">
                <div class="w-full md:w-[48%] px-4 mb-2 flex items-center">
                    <label for="stuff" class="w-[50%]">Stuff</label>
                    <input type="text" id="stuff"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm  focus:ring-blue-500 focus:border-blue-500 block w-full p-1"
                        name="stuff">
                </div>
                <div class="w-full md:w-[48%] px-4 mb-2 flex items-center">
                    <label for="supplier" class="w-[50%]">Supplier</label>

                    <select name="supplier" id="supplier"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm  focus:ring-blue-500 focus:border-blue-500 block max-w-full select2 p-1">
                        <option value="">Select Supplier</option>
                        @foreach ($suppliers as $supplier)
                            <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="flex flex-wrap xl:gap-x-7 lg:gap-x-2 md:gap-x-2 sm:gap-x-0 -mx-4 mb-4">
                <div class="w-full md:w-[48%] px-4 mb-2 flex items-center">
                    <label for="agent_price" class="w-[50%]">Agent
                        Price</label>
                    <input type="text" id="agent_price_1"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm  focus:ring-blue-500 focus:border-blue-500 block w-full p-1"
                        name="agent_price">
                </div>
                <div class="w-full md:w-[48%] px-4 mb-2 flex items-center">
                    <label for="supplier_price" class="w-[50%]">Supplier
                        Price</label>
                    <input type="text" id="supplier_price"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm  focus:ring-blue-500 focus:border-blue-500 block w-full p-1"
                        name="supplier_price">
                </div>
            </div>
            <div class="flex flex-wrap xl:gap-x-7 lg:gap-x-2 md:gap-x-2 sm:gap-x-0 -mx-4 mb-4">
                <div class="w-full md:w-[48%] px-4 mb-2 flex items-center">
                    <label for="discount" class="w-[50%]">Discount</label>
                    <input type="text" id="discount"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm  focus:ring-blue-500 focus:border-blue-500 block w-full p-1"
                        name="discount">
                </div>
                <div class="w-full md:w-[48%] px-4 mb-2 flex items-center">
                    <label for="remark" class="w-[50%]">Remark</label>
                    <textarea id="remark"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm  focus:ring-blue-500 focus:border-blue-500 block w-full p-1"
                        name="remark"></textarea>
                </div>

            </div>
            <div class=" flex-wrap gap-x-10 -mx-4 mb-4 hidden">
                <div class="w-full md:w-[48%] px-4 mb-2 flex items-center">
                    <label for="discount" class="block w-full md:w-[40%]  text-gray-700 text-sm mb-2">AIT</label>
                    <input type="text" id="aitticket"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm  focus:ring-blue-500 focus:border-blue-500 block w-full p-1"
                        name="ait">
                </div>
                <div class="w-full md:w-[48%] px-4 mb-2 flex items-center">
                    <label for="remark" class="block w-full md:w-[40%]  text-gray-700 text-sm mb-2">AIT
                        Amount</label>
                    <input type="text" id="aitticket_amount"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm  focus:ring-blue-500 focus:border-blue-500 block w-full p-1"
                        name="ait_amount">
                </div>

            </div>
            <div class="col-span-2 flex justify-end">
                <button type="submit" id="add_ticket"
                    class="bg-[#922724] text-xl hover:bg-blue-700 text-white font-bold py-2 px-16 rounded">Add</button>
            </div>

        </form>

        <div class="bg-[#F4A460D1] w-full my-2 rounded-lg p-2" id="profit_show">
            Net Profit - 900
        </div>
        <div class="flex justify-center my-4 gap-x-8">
            <div class="font-semibold">
                <input type="checkbox" id="addGDS" name="addGDS" onchange="toggleGdsVisibility()" />
                <label for="addGDS">Add GDS</label>
            </div>

            <div class="font-semibold">
                <input type="checkbox" id="receivePayment" name="receivePayment"
                    onchange="toggleFormVisibility()" />
                <label for="receivePayment">Receive Payment</label>
            </div>

            <div class="font-semibold">
                <input type="checkbox" id="refundCheckbox" name="refund" onchange="toggleRefundVisibility()" />
                <label for="refundCheckbox">Refund</label>
            </div>
        </div>

        <form class="w-full my-4" id="receive_payment" method="post" action="{{ route('receive_only') }}">
            @csrf
            <div class="flex flex-wrap gap-x-6 mb-4">
                <div class="w-full md:w-[48%] px-4 mb-2 flex items-center">
                    <label for="reff_no" class="block w-full md:w-[40%] text-gray-700 text-sm mb-2">Ref No</label>
                    <input type="text" id="reff_no"
                        class=" text-gray-900 text-sm bg-gray-50 border border-gray-300 focus:ring-blue-500 focus:border-blue-500 block w-full p-1"
                        name="reff_no">
                </div>
                <div class="w-full md:w-[48%] px-4 mb-2 flex items-center">
                    <label for="amount" class="block w-full md:w-[40%]  text-gray-700 text-sm mb-2">Amount</label>
                    <input type="text" id="amount"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm  focus:ring-blue-500 focus:border-blue-500 block w-full p-1"
                        name="amount">
                </div>
            </div>

            <div class="flex flex-wrap gap-x-6 mb-4">
                <div class="w-full md:w-[48%] px-4 mb-2 flex items-center">
                    <label for="agent_supplier"
                        class="block w-full md:w-[40%] text-gray-700 text-sm mb-2">Agent/Supplier</label>
                    {{-- <input type="text" id="agent_supplier" class=" text-gray-900 text-sm bg-gray-50 border border-gray-300 focus:ring-blue-500 focus:border-blue-500 block w-full p-1" name="agent_supplier" > --}}
                    <select id="agent_supplier" name="agent_supplier"
                        class=" text-gray-900 text-sm bg-gray-50 border border-gray-300 focus:ring-blue-500 focus:border-blue-500 block w-full p-1"
                        required>
                        <option value="">Select One</option>
                        <option value="agent">Agent</option>
                        <option value="supplier">Supplier</option>
                    </select>
                </div>
                <div class="w-full md:w-[48%] px-4 mb-2 flex items-center">
                    <label for="amount" class="block w-full md:w-[40%]  text-gray-700 text-sm mb-2">Select
                        Candidate</label>
                    {{-- <input type="text" id="amount" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm  focus:ring-blue-500 focus:border-blue-500 block w-full p-1" name="amount"> --}}
                    <select id="agent_supplier_id" name="agent_supplier_id"
                        class=" text-gray-900 text-sm bg-gray-50 border border-gray-300 focus:ring-blue-500 focus:border-blue-500 block w-full p-1"
                        required>
                        <option value="">Select One</option>

                    </select>
                </div>
            </div>

            <div class="flex flex-wrap gap-x-6 -mx-4 mb-4">
                <div class="w-full md:w-[48%] px-4 mb-2 flex items-center">
                    <label for="payment_mode" class="block w-full md:w-[40%]  text-gray-700 text-sm mb-2">Mode Of
                        Payment</label>
                    <input type="text" id="payment_mode"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm  focus:ring-blue-500 focus:border-blue-500 block w-full p-1"
                        name="payment_mode">
                </div>
                <div class="w-full md:w-[48%] px-4 mb-2 flex items-center">
                    <label for="remark" class="block w-full md:w-[40%]  text-gray-700 text-sm mb-2">Remark</label>
                    <textarea id="remark"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm  focus:ring-blue-500 focus:border-blue-500 block w-full p-1"
                        name="remark"></textarea>
                </div>
            </div>
            <div class="w-full flex justify-end mx-4">
                <button type="submit"
                    class="bg-[#922724] mt-7 text-xl hover:bg-blue-700 text-white font-bold py-2 px-16 rounded">Submit</button>
            </div>
        </form>


        <div class="my-4 w-full" id="gds">
            <div class="flex flex-wrap gap-x-2 md:gap-x-6 mb-4">
                <div class="w-full md:w-[48%] px-4 mb-2 flex items-center">
                    <label for="fare" class="block w-full md:w-[40%] text-gray-700 text-sm mb-2">Fare</label>
                    <input type="text" id="fare"
                        class=" text-gray-900 text-sm bg-gray-50 border border-gray-300 focus:ring-blue-500 focus:border-blue-500 block w-full p-1"
                        name="fare">
                </div>
                <div class="w-full md:w-[48%] px-4 mb-2 flex items-center">
                    <label for="commission"
                        class="block w-full md:w-[40%]  text-gray-700 text-sm mb-2">Commission</label>
                    <input type="text" id="commission"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm  focus:ring-blue-500 focus:border-blue-500 block w-full p-1"
                        name="commission">
                </div>
            </div>

            <div class="flex flex-wrap gap-x-6 mb-4">
                <div class="w-full md:w-[48%] px-4 mb-2 flex items-center">
                    <label for="tax" class="block w-full md:w-[40%]  text-gray-700 text-sm mb-2">TAX</label>
                    <input type="text" id="tax"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm  focus:ring-blue-500 focus:border-blue-500 block w-full p-1"
                        name="tax">
                </div>
                <div class="w-full md:w-[48%] px-4 mb-2 flex items-center">
                    <label for="ait_amount" class="block w-full md:w-[40%]  text-gray-700 text-sm mb-2">AIT
                        Amount</label>
                    <input type="text" id="ait_amount"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm  focus:ring-blue-500 focus:border-blue-500 block w-full p-1"
                        name="ait_amount">
                </div>
            </div>
            <div class="flex flex-wrap gap-x-6 mb-4">
                <div class="w-full md:w-[48%] px-4 mb-2 flex items-center">
                    <label for="ait" class="block w-full md:w-[40%]  text-gray-700 text-sm mb-2">AIT</label>
                    <input type="text" id="ait"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm  focus:ring-blue-500 focus:border-blue-500 block w-full p-1"
                        name="ait">
                </div>
                <div class="w-full md:w-[48%] px-4 mb-2 flex items-center">
                    <label for="service_charge" class="block w-full md:w-[40%]  text-gray-700 text-sm mb-2">Service
                        Charge</label>
                    <input type="text" id="service_charge"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm  focus:ring-blue-500 focus:border-blue-500 block w-full p-1"
                        name="service_charge">
                </div>
            </div>
            <div class="flex flex-wrap gap-x-6 mb-4">
                <div class="w-full md:w-[48%] px-4 mb-2 flex items-center">
                    <label for="agent_price" class="block w-full md:w-[40%]  text-gray-700 text-sm mb-2">Agent
                        Price</label>
                    <input type="text" id="agent_price"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm  focus:ring-blue-500 focus:border-blue-500 block w-full p-1"
                        name="agent_price">
                </div>
                <div class="w-full md:w-[48%] px-4 mb-2 flex items-center">
                    <label for="gds_payment" class="block w-full md:w-[40%]  text-gray-700 text-sm mb-2">GDS
                        Payment</label>
                    <input type="text" id="gds_payment"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm  focus:ring-blue-500 focus:border-blue-500 block w-full p-1"
                        name="gds_payment">
                </div>
            </div>
        </div>


        <form id="refundForm" action="{{ route('refund_ticket') }}" method="post" style="display: none;">
            @csrf
        </form>

        <div class="w-full flex justify-end mx-4">
            <button type="submit"
                class="bg-[#922724] mt-7 text-xl hover:bg-blue-700 text-white font-bold py-2 px-16 rounded">Submit</button>
        </div>
    </div>


    {{-- 
    <table class="table-fixed mx-4 border rounded-lg overflow-hidden table table-striped table-hover"
        id="ticket_table">
        <thead>
            <tr class="border-b bg-gray-100">
                <th class="w-1/6 px-4 py-2 text-left text-gray-700 font-medium">Invoice</th>
                <th class="w-1/6 px-4 py-2 text-left text-gray-700 font-medium">Invoice Date</th>
                <th class="w-1/6 px-4 py-2 text-left text-gray-700 font-medium">Ticket No</th>
                <th class="w-1/6 px-4 py-2 text-left text-gray-700 font-medium">Passenger</th>
                <th class="w-1/6 px-4 py-2 text-left text-gray-700 font-medium">Flight Date</th>
                <th class="w-1/6 px-4 py-2 text-left text-gray-700 font-medium">Agent</th>

                <th class="w-1/6 px-4 py-2 text-left text-gray-700 font-medium">Supplier</th>
                <th class="w-1/6 px-4 py-2 text-left text-gray-700 font-medium">Agent Price</th>

                <th class="w-1/6 px-4 py-2 text-left text-gray-700 font-medium">Supplier Price</th>
                <th class="w-1/6 px-4 py-2 text-left text-gray-700 font-medium">Airline</th>
                <th class="w-1/6 px-4 py-2 text-left text-gray-700 font-medium">Remark</th>
                <th class="w-1/6 px-4 py-2 text-left text-gray-700 font-medium">Action</th>
            </tr>
        </thead>
        <tbody>

            @foreach ($tickets as $ticket)
                <tr class="border-b hover:bg-gray-50">
                    <td class="px-4 py-2 text-gray-700">{{ $ticket->invoice }}</td>
                    <td class="px-4 py-2 text-gray-700">{{ (new DateTime($ticket->invoice_date))->format('d/m/Y') }}
                    </td>
                    <td class="px-4 py-2 text-gray-700">{{ $ticket->ticket_no }}/{{ $ticket->ticket_code }}</td>
                    <td class="px-4 py-2 text-gray-700">{{ $ticket->passenger }}</td>

                    <td class="px-4 py-2 text-gray-700">{{ (new DateTime($ticket->flight_date))->format('d/m/Y') }}
                    </td>
                    <td class="px-4 py-2 text-gray-700">{{ $ticket->agent }}</td>

                    <td class="px-4 py-2 text-gray-700">{{ $ticket->supplier }}</td>
                    <td class="px-4 py-2 text-gray-700">{{ $ticket->agent_price }}</td>
                    <td class="px-4 py-2 text-gray-700">{{ $ticket->supplier_price }}</td>

                    <td class="px-4 py-2 text-gray-700">{{ $ticket->airline_name }}/{{ $ticket->airline_code }}</td>
                    <td class="px-4 py-2 text-gray-700">{{ $ticket->remark }}</td>
                    <td class="px-4 py-2 text-gray-700">
                        <a href="{{ route('ticket_edit', ['id' => $ticket->id]) }}"
                            class="text-blue-500 hover:text-blue-700 mr-2">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="{{ route('ticket_view', ['id' => $ticket->id]) }}"
                            class="text-green-500 hover:text-green-700 mr-2">
                            <i class="fas fa-eye"></i> View
                        </a>
                        <a href="{{ route('ticket_print', ['id' => $ticket->id]) }}"
                            class="text-red-500 hover:text-red-700">
                            <i class="fas fa-print"></i> Print
                        </a>
                    </td>

                </tr>
            @endforeach

        </tbody>
    </table> --}}
    <table class="table-fixed mx-4 border rounded-lg overflow-hidden table table-striped table-hover"
        id="ticket_table">
        <thead>
            <tr class="border-b bg-gray-100">
                <th class="w-1/6 px-4 py-2 text-left text-gray-700 font-medium">Invoice</th>
                <th class="w-1/6 px-4 py-2 text-left text-gray-700 font-medium">Invoice Date</th>
                <th class="w-1/6 px-4 py-2 text-left text-gray-700 font-medium">Ticket No</th>
                <th class="w-1/6 px-4 py-2 text-left text-gray-700 font-medium">Passenger</th>
                <th class="w-1/6 px-4 py-2 text-left text-gray-700 font-medium">Flight Date</th>

                <th class="w-1/6 px-4 py-2 text-left text-gray-700 font-medium">Airline</th>
                <th class="w-1/6 px-4 py-2 text-left text-gray-700 font-medium">Agent</th>

                <th class="w-1/6 px-4 py-2 text-left text-gray-700 font-medium">Supplier</th>
                <th class="w-1/6 px-4 py-2 text-left text-gray-700 font-medium">Agent Price</th>

                <th class="w-1/6 px-4 py-2 text-left text-gray-700 font-medium">Supplier Price</th>
                <th class="w-1/6 px-4 py-2 text-left text-gray-700 font-medium">Remark</th>
                <th class="w-1/6 px-4 py-2 text-left text-gray-700 font-medium">Action</th>
            </tr>
        </thead>
        <tbody>

            @foreach ($tickets as $ticket)
                <tr class="border-b hover:bg-gray-50">
                    <td class="px-2 py-2 text-gray-700">{{ $ticket->invoice }}</td>
                    <td class="px-2 py-2 text-gray-700">{{ (new DateTime($ticket->invoice_date))->format('d/m/Y') }}
                    </td>
                    <td class="px-2 py-2 text-gray-700">{{ $ticket->ticket_code }}-{{ $ticket->ticket_no }}</td>
                    <td class="px-2 py-2 text-gray-700">{{ $ticket->passenger }}</td>

                    <td class="px-2 py-2 text-gray-700">{{ (new DateTime($ticket->flight_date))->format('d/m/Y') }}
                    </td>
                    <td class="px-2 py-2 text-gray-700">{{ $ticket->airline_code }}-{{ $ticket->airline_name }}</td>
                    <td class="px-2 py-2 text-gray-700">{{ $ticket->agent }}</td>

                    <td class="px-2 py-2 text-gray-700">{{ $ticket->supplier }}</td>
                    <td class="px-2 py-2 text-gray-700">{{ $ticket->agent_price }}</td>
                    <td class="px-2 py-2 text-gray-700">{{ $ticket->supplier_price }}</td>


                    <td class="px-2 py-2 text-gray-700">{{ $ticket->remark }}</td>
                    <td class="px-2 py-2 text-gray-700 flex items-center justify-around">
                        <a href="{{ route('ticket_edit', ['id' => $ticket->id]) }}"
                            class="text-blue-500 hover:text-blue-700 mr-1">
                            <i class="fa fa-pencil fa-fw text-xl"></i>
                        </a>
                        <a href="{{ route('ticket_view', ['id' => $ticket->id]) }}"
                            class="text-green-500 hover:text-green-700 mr-1">
                            <i class="fa fa-eye fa-fw text-xl"></i>
                        </a>
                        <a href="#" onclick="confirmDelete('{{ route('ticket.delete', ['id' => $ticket->id]) }}')"
                          class="text-red-800 hover:text-red-900 mr-1">
                          <i class="fa fa-trash fa-fw text-xl"></i>
                       </a>
                        {{-- <a href="{{ route('ticket_print', ['id' => $ticket->id]) }}" class="text-red-500 hover:text-red-700">
                    <i class="fas fa-print"></i> Print
                </a> --}}
                    </td>

                </tr>
            @endforeach

        </tbody>
    </table>
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog " role="document" style="max-width: 1200px;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Modal Title</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="tableContainer"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>


    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <script>
      function confirmDelete(deleteUrl) {
        // Display a confirmation dialog
        const isConfirmed = window.confirm("Are you sure you want to delete?");

        // If the user confirms, proceed with the delete action
        if (isConfirmed) {
            window.location.href = deleteUrl;
        }
    }
        $(document).ready(function() {

    
            $("#agent_supplier").change(function() {
                var selectedValue = $(this).val();
                if (selectedValue) {
                    $.ajax({
                        url: "/get_agent_supplier",
                        method: "GET",
                        data: {
                            who: selectedValue
                        },
                        success: function(response) {
                            updateOptions(response);
                        },
                        error: function(error) {
                            alert(error);
                        }
                    });
                }
            });

            function updateOptions(options) {
                var selectElement = $("#agent_supplier_id");
                selectElement.empty();
                selectElement.append("<option value=''>Select One</option>");
                options.forEach(function(option) {
                    selectElement.append("<option value='" + option.id + "'>" + option.name + "</option>");
                });
            }
        });

        function generateRandomInvoiceNumberReceivePayment() {
            const prefix = 'RP-';
            const length = 8;

            // Generate a random alphanumeric string of the specified length
            const randomString = Math.random().toString(36).substr(2, length).toUpperCase();

            return `${prefix}${randomString}`;
        }
        $('#reff_no').val(generateRandomInvoiceNumberReceivePayment());

        var received_payment = document.getElementById('receive_payment');
        var gds = document.getElementById('gds');
        // var refunddiv = document.getElementById('refunddiv');
        //   refunddiv.style.display = 'none';
        received_payment.style.display = 'none';
        gds.style.display = 'none';
        // Function to toggle the visibility of the received_payment
        function toggleFormVisibility() {
            var received_payment = document.getElementById('receive_payment');
            var checkbox = document.getElementById('receivePayment');
            received_payment.style.display = 'none';
            // Toggle the visibility of the received_payment based on checkbox state
            if (checkbox.checked) {
                received_payment.style.display = 'block';
            } else {
                received_payment.style.display = 'none';
            }
        }

        function toggleGdsVisibility() {
            var gds = document.getElementById('gds');
            var gds_checkbox = document.getElementById('addGDS');
            gds.style.display = 'none';
            // Toggle the visibility of the received_payment based on checkbox state
            if (gds_checkbox.checked) {
                gds.style.display = 'block';
            } else {
                gds.style.display = 'none';
            }
        }

        document.addEventListener("DOMContentLoaded", function() {
            toggleRefundVisibility();
        });

        function toggleRefundVisibility() {
            var refunddiv = document.getElementById('refunddiv');
            var refundCheckbox = document.getElementById('refundCheckbox');
            var refundForm = document.getElementById('refundForm');
            if (refundCheckbox.checked) {
                refundForm.submit();
            }
        }
    </script>



    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
        integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous">
    </script>
    <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.0/dist/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {

            $('.select2').select2();
            $('.datepicker').datepicker({
                // autoclose: true
            });
            $('#ticket_table').DataTable();

            function generateRandomString() {
                return new Promise((resolve, reject) => {
                    $.ajax({
                        url: '/get-last-id', // Replace with the actual URL to fetch the last ID
                        method: 'GET',
                        success: function(response) {
                            let lastId = response.lastId;
                            lastId = parseInt(lastId) + 1;

                            // Format the lastId with leading zeros to make it 6 digits
                            const formattedLastId = lastId.toString().padStart(6, '0');

                            const randomString = `INVT-${formattedLastId}`;

                            // Resolve the promise with the generated random string
                            resolve(randomString);
                        },
                        error: function(error) {
                            console.error('Error fetching last ID:', error);
                            // Reject the promise with the error
                            reject(error);
                        }
                    });
                });
            }

            // Example usage:
            generateRandomString()
                .then(randomString => {
                    $('#invoice_no').val(randomString);
                    // Do something with the random string here
                })
                .catch(error => {
                    console.error('Failed to generate random string:', error);
                });



            $('#ticket_code').on('change', function() {
                var ticketCodeValue = $(this).val();

                // Make an AJAX call
                $.ajax({
                    url: '/search_airline', // Replace with the actual endpoint URL
                    method: 'POST', // Specify the HTTP method (POST, GET, etc.)
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        ticketCode: ticketCodeValue
                    }, // Data to be sent to the server
                    dataType: 'json', // Expected data type of the response
                    success: function(response) {
                        if (response.message == 'Success') {
                            $('#airlines_name').val(response.airline.Full);
                            $('#airlines_code').val(response.airline.Short);
                        } else {
                            alert(response.message);
                        }
                    },
                    error: function(error) {
                        // Handle errors during the AJAX call
                        console.error('Error:', error);
                    }
                });
            });


            var fare, commission, tax, ait, gds, service_charge;
            var ait_amount = 3830;
            $('#fare').on('change', function() {
                fare = parseFloat(this.value);

                if (!isNaN(fare)) {
                    var commissionPercentage = 7;
                    commission = (fare * commissionPercentage) / 100;
                    commission = Math.floor(commission);
                    // var afterCommission = fare - commission;
                    $('#commission').val(commission);

                }
            });

            $('#tax').on('change', function() {
                tax = this.value;
                $('#ait_amount').val(ait_amount);
                fare = parseFloat(fare);
                tax = parseFloat(tax); // replace with the actual source of tax value
                ait_amount = parseFloat(ait_amount); // replace with the actual source of ait_amount value

                var total = fare + tax - ait_amount;
                ait = parseFloat((total * 0.3) / 100);
                ait = Math.floor(ait);
                total = parseFloat(total);
                var gds_payment = fare + tax + ait - commission;
                gds = gds_payment;
                $('#ait').val(ait);
                $('#aitticket').val(ait);
                $('#aitticket_amount').val(ait_amount);
                $('#gds_payment').val(gds_payment);
                $('#supplier_price').val(gds_payment);

            });

            $('#service_charge').on('change', function() {
                service_charge = this.value;
                service_charge = parseFloat(service_charge);
                // console.log(service_charge);
                if (!isNaN(service_charge)) {
                    var gds_payment = gds + service_charge;
                    $('#gds_payment').val(gds_payment);
                    $('#supplier_price').val(gds_payment);
                } else {
                    service_charge = 0;
                    var gds_payment = gds + service_charge;
                    $('#gds_payment').val(gds_payment);
                    $('#supplier_price').val(gds_payment);
                }

            });


            $('#agent_price').on('change', function() {
                $('#agent_price_1').val(this.value);
            });

            function manipulateString(inputString, variable) {
                if (variable >= 0 && variable <= 9) {
                    var lastTwoChars = inputString.slice(-2);
                    var result = inputString.slice(0, -2) + (parseInt(lastTwoChars) + variable);
                    return result;
                } else {
                    console.error('Invalid variable. It should be between 0 and 9.');
                    return null;
                }
            }

            $('#add_ticket').on('click', function(event) {
                event.preventDefault();
                var agent = $('#agent').val();
                var passenger_name = $('#passenger_name').val();
                var supplier = $('#supplier').val();
                var invoice_date = $('#invoice_date').val();
                var flight_date = $('#flight_date').val();
                var sector = $('#sector').val();
                var flight_no = $('#flight_no').val();
                var ticket_code = $('#ticket_code').val();
                var ticket_no = $('#ticket_no').val();
                var airlines_name = $('#airlines_name').val();
                var airlines_code = $('#airlines_code').val();
                var stuff = $('#stuff').val();
                var agent_price_1 = $('#agent_price_1').val();
                var supplier_price = $('#supplier_price').val();
                var discount = $('#discount').val();
                var remark = $('#remark').val();
                var number_of_tickets = $('#number_of_tickets').val();
                var invoice_no = $('#invoice_no').val();
                var aitticket = $('#aitticket').val();

                // console.log(invoice_Date)
                var agent_price_1 = parseFloat($('#agent_price_1').val());
                var supplier_price = parseFloat($('#supplier_price').val());

                if (!isNaN(agent_price_1) && !isNaN(supplier_price)) {
                    var profit = agent_price_1 - supplier_price;
                    var tkno = parseInt(number_of_tickets);
                    var total_profit = profit * tkno;
                    console.log(agent_price_1, supplier_price, profit, total_profit, tkno);

                    // Update the content of the div with the calculated total_profit
                    $('#profit_show').html('Net Profit - ' + total_profit);
                } else {
                    alert('Invalid input. Please enter valid numeric values.');
                }


                if (
                    agent &&
                    supplier &&
                    invoice_date &&
                    flight_date &&
                    flight_no &&
                    (ticket_code || ticket_no) && // either ticket code or ticket no should be available
                    (airlines_name || airlines_code) && // either airlines name or code should be available
                    number_of_tickets>1 &&
                    agent_price_1 && sector && stuff &&
                    supplier_price && invoice_no
                ) {
                    var csrfToken = "{{ csrf_token() }}";
                    var tableHtml =
                        '<form id="tickets_form" method="post" action="{{ route('addticket.store') }}">';
                    tableHtml += '<input type="hidden" name="_token" value="' + csrfToken + '">';
                    tableHtml += '<table class="table">';
                    tableHtml += '<thead>';
                    tableHtml += '<tr>';
                    tableHtml += '<th>Ticket No</th>';
                    tableHtml += '<th>Invoice Date</th>';
                    tableHtml += '<th>Flight Date</th>';
                    tableHtml += '<th>Flight No</th>';
                    tableHtml += '<th>Ticket Code/No</th>';
                    tableHtml += '<th>Airlines Name/Code</th>';
                    tableHtml += '<th>Pessanger</th>';
                    tableHtml += '<th>Agent Price</th>';
                    tableHtml += '<th>Supplier Price</th>';
                    tableHtml += '<th>Invoice No</th>';
                    // Add more headers as needed
                    tableHtml += '</tr>';
                    tableHtml += '</thead>';
                    tableHtml += '<tbody>';

                    // Populate table rows with data
                    for (var i = 0; i < parseInt(number_of_tickets); i++) {
                        tableHtml += '<tr>';
                        tableHtml += '<td>' + '<input type="text" name="ticket_no[]" id="ticket_no_' + i +
                            '" value="' + manipulateString(ticket_no, i) + '"></td>';
                        tableHtml += '<td>' + invoice_date + '</td>';
                        tableHtml += '<td>' + flight_date + '</td>';
                        tableHtml += '<td>' + flight_no + '</td>';
                        tableHtml += '<td>' + (ticket_code || ticket_no) + '</td>';
                        tableHtml += '<td>' + (airlines_name || airlines_code) + '</td>';
                        tableHtml += '<td>' +
                            '<input type="text" class="form-control" name="passenger_name[]" id="passenger_name_' +
                            i + '"></td>';
                        tableHtml += '<td>' + agent_price_1 + '</td>';
                        tableHtml += '<td>' + supplier_price + '</td>';
                        tableHtml += '<td>' + invoice_no + '</td>';
                        // Add more cells as needed
                        tableHtml += '</tr>';
                    }
                    tableHtml += '<input type="hidden" name="agent" value="' + agent + '">';
                    tableHtml += '<input type="hidden" name="supplier" value="' + supplier + '">';
                    tableHtml += '<input type="hidden" name="agent_price" value="' + agent_price_1 + '">';
                    tableHtml += '<input type="hidden" name="supplier_price" value="' + supplier_price +
                        '">';
                    tableHtml += '<input type="hidden" name="invoice_date" value="' + invoice_date + '">';
                    tableHtml += '<input type="hidden" name="flight_date" value="' + flight_date + '">';
                    tableHtml += '<input type="hidden" name="invoice_no" value="' + invoice_no + '">';
                    tableHtml += '<input type="hidden" name="flight_no" value="' + flight_no + '">';
                    tableHtml += '<input type="hidden" name="sector" value="' + sector + '">';
                    tableHtml += '<input type="hidden" name="ticket_code" value="' + ticket_code + '">';
                    // tableHtml += '<input type="hidden" name="ticket_no" value="'+ ticket_no +'">';
                    tableHtml += '<input type="hidden" name="airlines_name" value="' + airlines_name + '">';
                    tableHtml += '<input type="hidden" name="airlines_code" value="' + airlines_code + '">';
                    tableHtml += '<input type="hidden" name="remark" value="' + remark + '">';
                    tableHtml += '<input type="hidden" name="stuff" value="' + stuff + '">';
                    tableHtml += '<input type="hidden" name="ait" value="' + aitticket + '">';
                    tableHtml += '<input type="hidden" name="ait_amount" value="' + aitticket + '">';
                    tableHtml += '</tbody>';
                    tableHtml += '</table>';
                    tableHtml += '<td colspan="10" class="text-center">';
                    tableHtml +=
                        '<button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">';
                    tableHtml += 'Submit';
                    tableHtml += '</button>';
                    tableHtml += '</td>';
                    tableHtml += '</form>';
                    // Append the table to the modal or wherever you want to display it
                    $('#tableContainer').html(tableHtml);

                    $('#myModal').modal('show');
                } 
                else if(
                agent && stuff &&
                supplier &&
                invoice_date &&
                flight_date &&
                flight_no &&
                (ticket_code || ticket_no) && // either ticket code or ticket no should be available
                (airlines_name || airlines_code) && // either airlines name or code should be available
                number_of_tickets == 1 &&
                agent_price_1 && sector &&
                supplier_price && invoice_no)
                {
                  var csrfToken = "{{ csrf_token() }}";
                  var dataToSend = {
                            passenger_name,
                            agent,
                            supplier,
                            invoice_no,
                            invoice_date,
                            flight_date,
                            flight_no,
                            airlines_name,
                            ticket_code,
                            ticket_no,
                            airlines_code,
                            number_of_tickets,
                            agent_price_1,
                            supplier_price,
                            sector,
                            stuff,
                            discount,
                            csrfToken,
                            remark
                        };
                    // console.log(dataToSend);
                    $.ajax({
                            url: '/addsingleticket', // Use the correct route name
                            type: 'POST',
                            contentType: 'application/json',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            data: JSON.stringify(dataToSend),
                            success: function(data) {
                                // Handle the success response data as needed
                                alert('Ticket added successfully');
                                window.location.reload();
                            },

                            error: function(jqXHR, textStatus, errorThrown) {
                                // Handle errors here
                                console.error('Error:', errorThrown);
                            }
                    });

                }
                else {
                    console.log('Some required variables are missing.');
                }




            });


        });
    </script>
</x-app-layout>
