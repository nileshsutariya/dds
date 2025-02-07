@include('layouts.header')

<section class="content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6 col-6">
                <h1 class="responsive-heading">Monthly Report</h1>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card m-2">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="transaction_month" class="control-label"
                                        style="font-size: 22px; font-weight: bold; color: #333;">
                                        Select Month
                                    </label>
                                    <input type="month" id="transaction_month" class="form-control form-control-lg"
                                        name="transaction_month" onclick="this.showPicker();">
                                </div>
                            </div>
                            <div class="col-md-6 d-flex justify-content-end">
                                <div class="form-group ">
                                    <label class="text-primary" style="font-size: 20px">
                                        Total Unit :-
                                    </label>
                                    <span id="totalUnit" style="font-size: 18px; font-weight: bold; color: black;">
                                        0
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-4" id="table-section">
                            <div class="col-md-12">
                                <!-- Month-Wise Data Table -->
                                <div class="card">
                                    <div class="card-body">
                                        <table id="month-data-table"
                                            class="table table-bordered table-striped table-responsive">
                                            <thead>
                                                <tr id="month-dates-header">
                                                    {{-- append header --}}
                                                </tr>
                                            </thead>
                                            <tbody id="month-dates-data">
                                                {{-- append data --}}
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var today = new Date();
        var currentMonth = today.toISOString().slice(0, 7);
        document.getElementById('transaction_month').value = currentMonth;
        document.getElementById('transaction_month').setAttribute('max', currentMonth);

        loadClientsAndMonthlyData(currentMonth);
        totalUnit(currentMonth);
    });

    document.getElementById('transaction_month').addEventListener('change', function() {
        var selectedMonth = this.value;
        if (selectedMonth) {
            document.getElementById('table-section').style.display = 'block';
            loadClientsAndMonthlyData(selectedMonth);
            totalUnit(selectedMonth);
        }

    });

    function loadClientsAndMonthlyData(selectedMonth) {
        $.ajax({
            url: '{{ route('fetch.clients') }}',
            type: 'GET',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                month: selectedMonth
            },
            success: function(response) {
                if (response && response.length > 0) {
                    populateClientTable(response, selectedMonth);
                } else {
                    console.error('No clients found in response.');
                    document.getElementById('month-dates-data').innerHTML =
                        '<tr><td colspan="32">No clients found.</td></tr>';
                }
            },
            error: function(xhr, status, error) {
                console.error('Error fetching clients:', error);
            }
        });
    }

    function populateClientTable(clients, selectedMonth) {
        var year = selectedMonth.split('-')[0];
        var month = selectedMonth.split('-')[1];

        var dates = [];
        var date = new Date(year, month - 1, 1);
        while (date.getMonth() === month - 1) {
            dates.push(new Date(date));
            date.setDate(date.getDate() + 1);
        }

        var headerRow = document.getElementById('month-dates-header');
        headerRow.innerHTML = '<th>Clients</th>';
        dates.forEach(function(date) {
            var th = document.createElement('th');
            th.textContent = date.getDate();
            headerRow.appendChild(th);
        });
        headerRow.innerHTML += '<th>Total</th><th>Amount</th>';

        var tbody = document.getElementById('month-dates-data');
        tbody.innerHTML = '';

        clients.forEach(function(client) {
            var dataRow = document.createElement('tr');

            var clientTd = document.createElement('td');
            clientTd.textContent = client.name;
            dataRow.appendChild(clientTd);

            var totalUnit = 0; 
            var totalAmount = 0; 

            dates.forEach(function(date) {
                var currentDate = date.toLocaleDateString('en-CA');

                var transactionUnit = client.transactions[currentDate] || 0;
                totalUnit += transactionUnit;

                var transactionAmount = client.amounts[currentDate] || 0;
                totalAmount += transactionAmount; 

                var td = document.createElement('td');
                td.textContent = transactionUnit; 

                if (transactionUnit === 0) {
                    td.style.color = 'red';
                }

                if (transactionUnit) {
                    td.style.color = 'blue';
                    td.style.fontWeight = 'bold';
                }

                dataRow.appendChild(td);
            });

            var totalUnitTd = document.createElement('td');
            totalUnitTd.textContent = totalUnit.toFixed(2); 
            dataRow.appendChild(totalUnitTd);

            var totalAmountTd = document.createElement('td');
            totalAmountTd.textContent = totalAmount.toFixed(2); 
            dataRow.appendChild(totalAmountTd);

            tbody.appendChild(dataRow);
        });
    }


    function totalUnit(selectedMonth) {
        console.log("totalUnit function is called for month:", selectedMonth);

        $.ajax({
            url: "{{ route('grandtotal.unit') }}",
            method: "GET",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                month: selectedMonth
            },
            success: function(data) {
                console.log("AJAX request successful", data);
                let totalUnit = data.totalUnit || 0;
                let formattedValue = totalUnit.toFixed(1);
                $('#totalUnit').text(formattedValue);
            },
            error: function(xhr, status, error) {
                console.error('Error fetching total unit:', error);
            }
        });
    }
</script>

<style>
    .responsive-heading {
        font-size: clamp(24px, 5vw, 40px);
    }
</style>

@include('layouts.footer')
