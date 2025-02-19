@include('layouts.header')

<section class="content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6 col-6">
                <h1 class="responsive-heading">Daily Entry</h1>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card m-1">
                    <div class="card-body">
                        <div id="daily-table">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="transaction_date" class="control-label"
                                            style="font-size: 18px; font-weight: bold; color: #333;">
                                            Select Date
                                        </label>
                                        <input type="date" id="transaction_date" class="form-control form-control-lg"
                                            name="transaction_date" max="" onclick="this.showPicker();"
                                            style="height: 40px">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="d-flex justify-content-end mt-4">
                                        <button class="btn btn-primary btn-md btn-save">Save</button>
                                        <button class="btn btn-primary btn-md btn-update mr-2">Update</button>
                                        <button class="btn btn-danger btn-md btn-delete">Delete</button>
                                    </div>
                                    <div class="d-flex justify-content-end mt-2">
                                        <span id="total_litter" class="text-success"
                                            style="font-size: 18px; font-weight: bold;">
                                            {{-- Total Litter: --}}
                                        </span>
                                    </div>
                                </div>


                                <div class="table-responsive">
                                    <table class="table table-bordered" style="table-layout: auto; width: 100%">
                                        <div class="m-4">
                                            <tbody id="client-data">

                                            </tbody>
                                        </div>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="client-cards m-3" id="client-cards"></div>
</section>

<script>
    $(document).ready(function() {
        $('.btn').hide();
        $('#total_litter').hide();

        $(document).on('input keyup change', '.daily-unit', function() {
            var unit = $(this).val();
            console.log("Updated unit:", unit);

            var container = $(this).closest('.client-card, tr');

            $.ajax({
                url: '{{ route('fetch.price') }}',
                method: 'GET',
                success: function(response) {
                    var price = response.price;
                    console.log("Fetched price:", price);

                    if (unit && price) {
                        var amount = unit * price;
                        container.find('.price').val(amount);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching price:', error);
                }
            });
        });

        $('#transaction_date').on('change', function() {
            var date = $(this).val();

            if (!date) {
                $('.btn').hide();
                return;
            }

            $.ajax({
                url: '{{ route('fetch.transactions') }}',
                method: 'GET',
                data: {
                    date: date
                },
                success: function(response) {
                    var tbody = $('#client-data');
                    tbody.empty();
                    var totallitter = 0;

                    if (response.length > 0) {
                        tbody.append(`
                        <tr style="font-size: 15px; background-color: rgb(241, 241, 221)">
                            <th style="width: 50px"></th>
                            <th style="width: 100px">No.</th>
                            <th>Daily Units</th>
                            <th>Amount</th>
                            <th>Full Name</th>
                            <th>Phone No</th>
                            <th>Address</th>
                            <th>Area</th>
                        </tr>
                    `);

                        response.forEach(function(transaction) {
                            totallitter += parseFloat(transaction.daily_units);
                            tbody.append(`
                            <tr>
                               <td style="width: 5px">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" 
                                         name="transaction[]">
                                    </div>
                                </td>
                                <td>
                                     <label class="form-check-label" for="transaction-${transaction.no}">
                                            ${transaction.no}
                                      </label>   
                                </td>
                               <td style="width: 120px"><input type="text" value="${transaction.daily_units}" class="form-control daily-unit" style="width: 80px"></td>
                               <td style="width: 120px"><input type="text" value="${transaction.price}" class="form-control price" style="width: 80px"></td>
                               <td>${transaction.full_name}</td>
                               <td>${transaction.phone_no}</td>
                               <td>${transaction.address}</td>
                               <td>${transaction.area}</td>
                                <input type="hidden" class="transaction-id" value="${transaction.id}">
                            </tr>
                        `);
                        });

                        // $('.btn-save').hide();
                        $('#total_litter').text(`Total Litter: ${totallitter.toFixed(2)}`)
                            .show();
                        $('.btn-save').hide();
                        $('.btn-update, .btn-delete').show();
                    } else {
                        $.ajax({
                            url: '{{ route('fetch.active.clients') }}',
                            method: 'GET',
                            success: function(activeClients) {
                                if (activeClients.length > 0) {
                                    tbody.append(`
                                    <tr style="font-size: 15px; background-color: rgb(241, 241, 221)">
                                        <th>No.</th>
                                        <th>Daily Units</th>
                                        <th>Full Name</th>
                                        <th>Phone No</th>
                                        <th>Address</th>
                                        <th>Area</th>
                                    </tr>
                                `);

                                    activeClients.forEach(function(
                                        client) {
                                        tbody.append(`
                                        <tr>
                                            <td>${client.no}</td>
                                            <td><input type="text" value="${client.daily_units}" class="form-control text-center daily-unit" style="width: 80px"></td>
                                            <td>${client.full_name}</td>
                                            <td>${client.phone_no}</td>
                                            <td>${client.address}</td>
                                            <td>${client.area}</td>
                                            <input type="hidden" class="client-id" value="${client.id}">
                                        </tr>
                                    `);
                                    });

                                    $('.btn').hide();
                                    $('#total_litter').hide();
                                    $('.btn-save').show();
                                } else {
                                    tbody.append(
                                        '<tr><td colspan="6">No active clients available.</td></tr>'
                                    );
                                }
                            },
                            error: function() {
                                console.log(
                                    'Error fetching active clients data.'
                                );
                            }
                        });
                    }
                },
                error: function() {
                    console.log('Error fetching transactions data.');
                }
            });
        });
    });

    //save data

    $('.btn-save').click(function() {
        var selectedDate = $('#transaction_date').val();
        console.log(selectedDate);

        var transactionData = [];

        $('#client-data tr').each(function() {
            var clientId = $(this).find('.client-id').val();
            console.log(clientId);
            var dailyUnits = $(this).find('.daily-unit').val();
            console.log(dailyUnits);

            if (clientId && dailyUnits) {
                transactionData.push({
                    client_id: clientId,
                    unit: dailyUnits,
                    date: selectedDate,
                });
            }
        });

        $('.client-card').each(function() {
            var clientId = $(this).find('.client-id').val();
            var dailyUnits = $(this).find('.daily-unit').val();

            if (clientId && dailyUnits) {
                transactionData.push({
                    client_id: clientId,
                    unit: dailyUnits,
                    date: selectedDate,
                });
            }
        });

        console.log('Transactions Data:', transactionData);

        $.ajax({
            url: '{{ route('save.transactions') }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                transactions: transactionData
            },
            success: function(response) {
                console.log('Response:', response);
                alert('Transactions saved successfully!');
            },
            error: function(xhr, status, error) {
                console.error('An error occurred:', status, error);
                alert('An error occurred while saving transactions.');
            }
        });
    });

    //update daily_unit 

    $('.btn-update').click(function() {
        var updatedData = [];
        $('#client-data tr').each(function() {
            var transactionId = $(this).find('.transaction-id').val();
            var dailyUnits = $(this).find('input[type="text"]').val();
            var price = $(this).find('.price').val();

            if (transactionId && dailyUnits) {
                updatedData.push({
                    id: transactionId,
                    unit: dailyUnits,
                    amount: price
                });
            }
        });

        console.log('Updated Data:', updatedData);

        $.ajax({
            url: '{{ route('update.unit') }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                transactions: updatedData
            },
            success: function(response) {
                console.log('AJAX Success Response:', response);

                if (response.success) {
                    console.log(response.message);
                } else {
                    console.log('Error: ' + response.message);
                }
            },
            error: function(xhr, status, error) {
                console.log('XHR Response:', xhr.responseText);
                console.log('Status:', status);
                console.log('Error:', error);

                var errorMessage = xhr.responseJSON && xhr.responseJSON.message ?
                    xhr.responseJSON.message :
                    'An unknown error occurred.';
                alert('Error: ' + errorMessage);
            }
        });

    });

    //delete selected transaction data

    $(document).ready(function() {
        $('.btn').hide();
        $('.btn-delete').click(function() {
            var selectedTransactionIds = [];
            $('input[type="checkbox"]:checked').each(function() {
                var transactionId = $(this).closest('tr').find('.transaction-id')
                    .val();
                selectedTransactionIds.push(transactionId);
            });

            if (selectedTransactionIds.length > 0) {
                $.ajax({
                    url: '{{ route('delete.unit') }}',
                    method: 'post',
                    data: {
                        _token: '{{ csrf_token() }}',
                        transaction_ids: selectedTransactionIds
                    },
                    success: function(response) {
                        if (response.success) {
                            $('input[type="checkbox"]:checked').each(
                                function() {
                                    $(this).closest('tr').remove();
                                });
                            console.log('Transaction deleted Successfully')
                        } else {
                            console.log('Error deleting transactions: ' +
                                response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('An error occurred:', status, error);
                        alert('An error occurred while deleting transactions.');
                    }
                });
            }
        });
        $('input[type="checkbox"]').on('change', function() {
            var anyChecked = $('input[type="checkbox"]:checked').length > 0;
            if (anyChecked) {
                $('.btn-delete').show();
            }
        });
    });

    //mobile view card for transaction and client data 

    $(document).ready(function() {
        function renderClientCards(data) {
            var clientCardsContainer = $('#client-cards');
            clientCardsContainer.empty();

            data.forEach(function(client) {
                var card = `
            <div class="client-card">
                <h4>${client.full_name}</h4>
                <p><strong>Phone:</strong> ${client.phone_no}</p>
                <p><strong>Address:</strong> ${client.address}</p>
                <p><strong>Area:</strong> ${client.area}</p>
                <p style="display: flex; align-items: center; gap: 10px;">
                    <strong>Daily Units:</strong> 
                <input type="text" value="${client.daily_units}" 
                    class="form-control text-center daily-unit" 
                    style="width: 100px;">
                </p>
                <input type="hidden" class="client-id" value="${client.id}">
            </div>
            `;
                clientCardsContainer.append(card);
            });
        }

        function renderTransactionCards(data) {
            var clientCardsContainer = $('#client-cards');
            clientCardsContainer.empty();

            data.forEach(function(transaction) {
                var card = `
            <div class="client-card update-card">
              <div class="col-md-6">
                <div class="d-flex justify-content-between">
                  <h4>${transaction.full_name}</h4>
                  <i class="bi bi-trash mobile-view" style="font-size: 25px; color:red"></i>
                </div>
              </div>
              <p><strong>Phone:</strong> ${transaction.phone_no}</p>
              <p><strong>Address:</strong> ${transaction.address}</p>
              <p><strong>Area:</strong> ${transaction.area}</p>
             <div style="display: flex; flex-wrap: wrap; gap: 10px; align-items: center; justify-content: space-between; width: 100%;">
    <div style="display: flex; align-items: center; gap: 5px;">
        <p><strong>Units:</strong></p>
        <input type="text" value="${transaction.daily_units}" 
            class="form-control text-center daily-unit" 
            style="width: 80px; min-width: 50px;">
    </div>

    <div style="display: flex; align-items: center; gap: 5px;">
        <p><strong>Amount:</strong></p>
        <input type="text" value="${transaction.price}" 
            class="form-control text-center price" 
            style="width: 80px; min-width: 50px;">
    </div>
</div>

              <input type="hidden" class="transaction-id" value="${transaction.id}">
            </div> 
      `;
                clientCardsContainer.append(card);
                $('.btn-delete').hide();
                $('.mobile-view').show();
            });
        }

        $('#transaction_date').on('change', function() {
            var date = $(this).val();

            $.ajax({
                url: '{{ route('fetch.transactions') }}',
                method: 'GET',
                data: {
                    date: date
                },
                success: function(transactions) {
                    if (transactions.length > 0) {
                        if ($(window).width() <= 768) {
                            renderTransactionCards(transactions);
                        }
                    } else {
                        $.ajax({
                            url: '{{ route('fetch.active.clients') }}',
                            method: 'GET',
                            success: function(activeClients) {
                                if ($(window).width() <= 768) {
                                    renderClientCards(activeClients);
                                }
                            },
                            error: function() {
                                console.log(
                                    'Error fetching active clients data.');
                            }
                        });
                    }
                },
                error: function() {
                    console.log('Error fetching transactions data.');
                }
            });
        });

        // Delete in mobile view
        $(document).on('click', '.mobile-view', function() {
            var card = $(this).closest('.client-card');
            var id = card.find('.transaction-id').val();
            $.ajax({
                url: '{{ route('delete.unit') }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    transaction_ids: [id]
                },
                success: function(response) {
                    console.log('Server Response:', response);
                    if (response.success) {
                        card.remove();
                        console.log('Card deleted successfully');
                    } else {
                        console.log('Error deleting the card: ' + response.message);
                        alert('Error deleting the card. Please try again.');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('An error occurred while deleting the card.');
                    alert(
                        'An error occurred while deleting the card. Please check your connection or try again later.'
                    );
                }
            });
        });

        // Update in mobile view
        $('.btn-update').click(function() {
            var updatedData = [];
            $('.client-card').each(function() {
                var card = $(this).closest('.client-card');
                var transactionId = card.find('.transaction-id').val();
                var dailyUnits = card.find('.daily-unit').val();
                var price = card.find('.price').val();

                if (transactionId && dailyUnits && price) {
                    updatedData.push({
                        id: transactionId,
                        unit: dailyUnits,
                        amount: price
                    });
                }
            });

            console.log('Updated Data:', updatedData);

            $.ajax({
                url: '{{ route('update.unit') }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    transactions: updatedData
                },
                success: function(response) {
                    if (response.success) {
                        console.log('Transactions updated successfully!');
                        updatedData.forEach(function(updatedTransaction) {
                            var card = $('.client-card').filter(function() {
                                return $(this).find('.transaction-id')
                                    .val() == updatedTransaction.id;
                            });

                            card.find('.daily-unit').val(updatedTransaction.unit);
                            card.find('.price').val(updatedTransaction.amount);
                        });
                    } else {
                        console.log('Error updating transactions:', response.message);
                        // alert('Error updating transactions: ' + response.message);
                    }
                },
                error: function(xhr, status, error) {
                    console.log('An error occurred while updating transactions.');
                    // alert('An error occurred while updating: ' + error);
                }
            });
        });

    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var today = new Date().toISOString().split('T')[0];
        document.getElementById('transaction_date').setAttribute('max', today);
    });
</script>

@include('layouts.footer')

<style>
    #transaction_date:focus {
        border-color: #0056b3;
        box-shadow: 0 0 5px rgba(0, 86, 179, 0.6);
    }

    .form-check-input {
        width: 20px;
        height: 20px;
        cursor: pointer;
    }

    .responsive-heading {
        font-size: clamp(24px, 5vw, 40px);
    }

    .table-responsive {
        overflow-x: auto;
    }

    .table th,
    .table td {
        word-wrap: break-word;
        text-align: center;
        vertical-align: middle;
    }

    @media (max-width: 768px) {

        .table th,
        .table td {
            font-size: 12px;
        }

        .btn {
            width: 100%;
            margin-bottom: 10px;
        }

    }

    @media (max-width: 768px) {
        .table-responsive {
            display: none;
        }

        .client-cards {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .client-card {
            width: 100%;
            background: #f8f9fa;
            border-radius: 10px;
            padding: 15px;
            box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.2);
        }

        .client-card h4 {
            margin-bottom: 10px;
        }

        .client-card p {
            margin: 5px 0;
        }
    }

    @media (min-width: 769px) {
        .client-cards {
            display: none;
        }
    }

    .update-card {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 15px;
        box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.2);
        width: 100%;
    }
</style>
