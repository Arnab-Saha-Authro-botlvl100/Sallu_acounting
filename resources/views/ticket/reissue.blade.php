<x-app-layout>
    <div class="container-fluid mx-auto mt-5">
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        
        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
        
        <h1 class="mb-4 text-3xl font-bold w-[100%] mx-auto lg:w-[75%]">Ticket Reissue Invoicing</h1>
    
        <div class="bg-white shadow-md rounded-lg w-[100%] mx-auto lg:w-[75%] p-6 mb-8">
            <form action="{{ route('ticket_reissue') }}" method="post">
                @csrf <!-- Add this line to include CSRF protection in Laravel -->
                
                <div class="grid grid-cols-2 gap-x-14">
                    <div class="mb-4 flex items-center justify-between gap-6">
                        <label for="ticket" class="block text-md font-semibold text-black ">Ticket Search:</label>
                        <input type="text" class=" mt-1 block w-[65%] border p-1" id="ticket" name="ticket" required>
                        <input type="hidden" class=" mt-1 block w-[65%] border p-1" id="ticket_code" name="ticket_code" required>
                    </div>
                    <div class="mb-4 flex items-center justify-between gap-6">
                        <label for="name" class="block text-md font-semibold text-black ">Passenger Name:</label>
                        <input type="text" class="form-input mt-1 block text-sm w-[65%] border p-1" id="name" name="name" readonly required>
                    </div>
                    <div class="mb-4 flex items-center justify-between gap-6">
                        <label for="sector" class="block text-md font-semibold text-black ">Sector</label>
                        <input type="tel" readonly class="form-input mt-1 block text-sm w-[65%] border p-1" id="sector" name="sector" required>
                    </div>
                    <div class="mb-4 flex items-center justify-between gap-6">
                        <label for="flight" class="block text-md font-semibold text-black ">Flight No</label>
                        <input type="text" readonly class="form-input mt-1 block text-sm w-[65%] border p-1" id="flight" name="flight" required>
                    </div>
                    <div class="mb-4 flex items-center justify-between gap-6">
                        <label for="flight_date" class="block text-md font-semibold text-black ">Flight Date</label>
                        <input type="date" class="form-input mt-1 block text-sm w-[65%] border p-1" id="flight_date" readonly name="flight_date" required>
                    </div>
                    <div class="mb-4 flex items-center justify-between gap-6">
                        <label for="reissue_date" class="block text-md font-semibold text-black ">Reissue Date</label>
                        <input type="date" class="form-input mt-1 block text-sm w-[65%] border p-1" id="reissue_date" name="reissue_date" required>
                    </div>
                    <div class="mb-4 flex items-center justify-between gap-6">
                        <label for="agent" class="block text-md font-semibold text-black ">Client</label>
                        <input type="text" readonly class="form-input mt-1 block text-sm w-[65%] border p-1" id="agent"  required>
                        <input type="hidden" class="form-control" name="agent" id="agent_id" required>
                    </div>
                    <div class="mb-4 flex items-center justify-between gap-6">
                        <label for="Supplier" class="block text-md font-semibold text-black ">Supplier</label>
                        <input type="text" readonly class="form-input mt-1 block text-sm w-[65%] border p-1" id="supplier" required>
                        <input type="hidden" class="form-control" name="supplier" id="supplier_id" required>

                    </div>
                    <div class="mb-4 flex items-center justify-between gap-6">
                        <label for="agent_fare" class="block text-md font-semibold text-black ">Client Fare</label>
                        <input type="text" readonly class="form-input mt-1 block text-sm w-[65%] border p-1" id="agent_fare" name="agent_fare" required>
                    </div>
                    <div class="mb-4 flex items-center justify-between gap-6">
                        <label for="supplier_fare" class="block text-md font-semibold text-black ">Supplier Fare</label>
                        <input type="text" readonly class="form-input mt-1 block text-sm w-[65%] border p-1" id="supplier_fare" name="supplier_fare" required>
                    </div>
                    <div class="mb-4 flex items-center justify-between gap-6">
                        <label for="agent_reissuefare" class="block text-md font-semibold text-black ">Client Reissue Charge</label>
                        <input type="text" class="form-input mt-1 block text-sm w-[65%] border p-1" id="agent_reissuefare" name="agent_reissuefare" required>
                    </div>
                    <div class="mb-4 flex items-center justify-between gap-6">
                        <label for="supplier_reissuefare" class="block text-md font-semibold text-black ">Supplier Reissue Charge</label>
                        <input type="text" class="form-input mt-1 block text-sm w-[65%] border p-1" id="supplier_reissuefare" name="supplier_reissuefare" required>
                    </div>
                </div>
               
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Submit</button>
            </form>
        </div>
    
        <div class="bg-white shadow-md p-6">
            {{-- <table class="table divide-y table-striped w-full divide-gray-200 table-hover no-wrap" id="suppliertable">
                <thead class="bg-[#7CB0B2]">
                    <tr>
                        <th class="px-4 py-2 ">Serial</th>
                        <th class="px-4 py-2 ">Refund Date</th>
                        <th class="px-4 py-2 ">Ticket No</th>
                        <th class="px-4 py-2 ">Agent Fare</th>
                        <th class="px-4 py-2 ">Refund Agent Fare</th>
                        <th class="px-4 py-2 ">Supplier Fair</th>
                        <th class="px-4 py-2 ">Refund Supplier Fare</th>
                        <th class="px-4 py-2 ">Refund Profit</th>

                    </tr>
                </thead>
                <tbody class="w-full">
                    @foreach($refund_ticket as $index => $refund)
                        <tr>
                            <td class="px-4 py-2 ">{{ $index + 1 }}</td>
                            <td class="px-4 py-2 ">{{ $refund->date }}</td>
                            <td class="px-4 py-2 ">{{ $refund->ticket_no }}</td>
                            <td class="px-4 py-2 ">{{ $refund->prev_agent_amount }}</td>
                            <td class="px-4 py-2 ">{{ $refund->now_agent_fere }}</td>
                            <td class="px-4 py-2 ">{{ $refund->prev_supply_amount }}</td>
                            <td class="px-4 py-2 ">{{ $refund->now_supplier_fare }}</td>
                            <td class="px-4 py-2 ">{{ $refund->refund_profit }}</td>
                            
                        </tr>
                    @endforeach
                </tbody>
            </table> --}}
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <script>
        $(document).ready(function() {
            // $('.datepicker').datepicker({
            //     autoclose: true
            // });

            // $('.select2').select2({
            //     theme: 'classic',
            // });

            // Initialize DataTable
            // new DataTable('#suppliertable', {
            //     responsive: true,
            //     rowReorder: {
            //         selector: 'td:nth-child(2)'
            //     }
            // });

            // Add onchange event handler for #ticket element
            $('#ticket').on('change', function () {
                var tckno = this.value;
                $.ajax({
                    url: '{{ route('search_ticket') }}', // Use the Laravel named route
                    method: 'POST',
                    data: {
                        ticketNumber: tckno
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Add CSRF token
                    },
                    success: function (response) {
                        if (response.status === 'success') {
                            // Ticket found
                            $('#name').val(response.ticket.passenger);
                            $('#ticket_code').val(response.ticket.ticket_code);
                            $('#flight').val(response.ticket.flight_no);
                            $('#flight_date').val(response.ticket.flight_date);
                            $('#sector').val(response.ticket.sector);
                            $('#agent').val(response.agent);
                            $('#supplier').val(response.supplier);
                            $('#agent_fare').val(response.ticket.agent_price);
                            $('#supplier_fare').val(response.ticket.supplier_price);
                            $('#agent_id').val(response.ticket.agent);
                            $('#supplier_id').val(response.ticket.supplier);
                        } else {
                            // Ticket not found
                            alert('Ticket not found. Message:', response.message);
                            // You can display a message to the user or take other actions
                        }
                    },
                    error: function (error) {
                        // Handle the error here
                        console.error('AJAX error:', error);
                        // You can display an error message or take other actions
                    }
                });

            });
        });
    </script>

    
</x-app-layout>