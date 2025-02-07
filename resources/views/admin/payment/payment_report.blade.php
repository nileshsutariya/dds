@include('layouts.header')

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 m-3">
            <div class="d-flex flex-wrap" style=" justify-content: space-between;">
                <div class="form-group col-md-3 col-sm-6">
                    <label for="start_date">Start Date</label>
                    <input type="date" class="form-control form-control-md" id="start_date" onclick="this.showPicker();"
                        name="date" max="" value="{{ old('date') }}" style="width: 100%;">
                </div>

                <div class="form-group col-md-3 col-sm-6">
                    <label for="end_date">End Date</label>
                    <input type="date" class="form-control form-control-md" id="end_date"
                        onclick="this.showPicker();" name="date" max="" value="{{ old('date') }}"
                        style="width: 100%;">
                </div>

                <div class="form-group col-md-3 col-sm-6">
                    <label>Clients</label>
                    <select class="form-control form-control-md" name="client_id" style="width: 100%;" id="clients">
                        <option disabled selected>Select Client</option>
                        @foreach ($clients as $client)
                            <option value="{{ $client->id }}" {{ old('client_id') == $client->id ? 'selected' : '' }}>
                                {{ $client->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group col-md-3 col-sm-6 d-flex" style="margin-top: 31px;">
                    <button type="submit" class="btn btn-primary btn-md w-auto w-sm-100">Submit</button>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-center">
        <div class="form-group" style="display: flex; justify-content: center; width: 100%;">
            <div class="col-md-2 col-sm-6">
                <label for="balance" class="ml-2">Available Balance</label>
                <input type="text" class="form-control form-control-md text-center" id="balance" name="balance"
                    style="width: 150px; font-size: 25px" value="{{ number_format($balance) }}" readonly>
            </div>
        </div>
    </div>

    <section class="content mt-2">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card m-2">
                        <div class="card-body">
                            <div id="payment-table">
                                <div class="table-responsive">
                                    <table class="table border table-hover" style="table-layout: fixed;">
                                        <thead style="font-size: 15px;">
                                            <tr>
                                                <th style="width: 16%;">No</th>
                                                <th style="width: 28%;">Client</th>
                                                <th style="width: 28%;">Date</th>
                                                <th style="width: 28%;">Amount</th>
                                                {{-- <th style="width: 28%;">Amount</th> --}}
                                            </tr>
                                        </thead>

                                        <tbody id="payment_data">
                                            @php
                                                $i = 1;
                                            @endphp
                                            @if (isset($payments))
                                                @foreach ($payments as $payment)
                                                    <tr>
                                                        <td>{{ $i }}</td>
                                                        <td>{{ $payment->client?->name ?? '-' }}</td>
                                                        <td>{{ $payment->date }}</td>
                                                        <td
                                                            style="color: {{ $payment->type == 'c' ? 'red' : 'green' }};">
                                                            {{ $payment->amount }}
                                                        </td>
                                                    </tr>
                                                    @php
                                                        $i++;
                                                    @endphp
                                                @endforeach
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

{{-- for future date  --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var today = new Date().toISOString().split('T')[0];
        document.getElementById('start_date').setAttribute('max', today);
        document.getElementById('end_date').setAttribute('max', today);
    });
</script>

{{-- amount color according to credit and debit --}}
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var balanceInput = document.getElementById("balance");
        var balance = parseFloat(balanceInput.value.replace(/,/g, ''));

        if (balance < 0) {
            balanceInput.style.color = "red";
        } else {
            balanceInput.style.color = "green";
        }
    });
</script>

{{-- filter payment data according to start and end date and selected client --}}
<script>
    $('.btn').click(function() {
        startDate = $('#start_date').val();
        // console.log(startDate);
        endDate = $('#end_date').val();
        // console.log(endDate);

        client = $('#clients').val();
        // console.log(client);

        $.ajax({
            url: "{{ route('payment.filter') }}",
            method: "post",
            data: {
                start_date: startDate,
                end_date: endDate,
                client_id: client,
                _token: '{{ csrf_token() }}',
            },
            success: function(response) {
                $('#payment_data').empty();

                let i = 1;
                response.payments.forEach(function(payment) {
                    let paymentRow = `
                        <tr>
                            <td>${i}</td>
                            <td>${payment.client ? payment.client.name : '-'}</td>
                            <td>${payment.date}</td>
                            <td style="color: ${payment.type == 'c' ? 'red' : 'green'};">${payment.amount}</td>
                        </tr>
                    `;
                    $('#payment_data').append(paymentRow);
                    i++;
                });
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', xhr.responseText);
                alert('Error while filtering payments.');
            }
        });
    });
</script>

@include('layouts.footer')

<style>
    #balance {
        background-color: transparent;
    }
</style>
