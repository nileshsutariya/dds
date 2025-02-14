@include('layouts.header')

<section class="content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6 col-6">
                <h1 class="responsive-heading">Daily Report</h1>
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
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label style="font-size: 15px">Select Client</label>
                                    <select class="form-control select2" name="active_users" id="clientSelect">
                                        <option disabled selected>Select Client</option>
                                        @foreach ($clients as $client)
                                            @if ($client->status == 1)
                                                <option value="{{ $client->id }}">{{ $client->name }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 d-flex justify-content-end">
                                <div class="form-group ">
                                    <label class="text-primary" style="font-size: 20px">
                                        Total Litter :-
                                    </label>
                                    <span class="mr-3" id="totalLitter"
                                        style="font-size: 18px; font-weight: bold; color: black;">
                                        0
                                    </span>
                                </div>
                                <div class="form-group ">
                                    <label class="text-primary" style="font-size: 20px">
                                        Amount :-
                                    </label>
                                    <span id="totalAmount" style="font-size: 18px; font-weight: bold; color: black;">
                                        0
                                    </span>
                                </div>
                            </div>
                            <div class="modal fade" id="litterDetailsModal" tabindex="-1" role="dialog"
                                aria-labelledby="modalTitle" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="modalTitle">Litter Details</h5>
                                            <button type="button" class="close" data-dismiss="modal"
                                                aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <p><strong>Date:</strong> <span id="modalDate"></span></p>
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="modalLitterInput">Litter (Unit):</label>
                                                        <input type="number" class="form-control" id="modalLitterInput"
                                                            value="0">
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="modalAmountInput">Amount:</label>
                                                        <input type="number" class="form-control price"
                                                            id="modalAmountInput">
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-primary"
                                                id="saveLitterBtn">Save</button>
                                            <button type="button" class="btn btn-secondary"
                                                data-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Calendar Div -->
                        <div id="calendar" style="display: none;"></div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    $(document).ready(function() {

        $('#modalLitterInput').on('input', function() {
            var unit = parseFloat($(this).val());
            // console.log('Unit:', unit);

            if (isNaN(unit) || unit <= 0) {
                $('#modalAmountInput').val(0);
                return;
            }

            $.ajax({
                url: '{{ route('fetch.price') }}',
                method: 'GET',
                success: function(response) {
                    var price = parseFloat(response.price);
                    // console.log('Price:', price);

                    if (!isNaN(price) && price > 0) {
                        var amount = unit * price;
                        // console.log('Calculated Amount:', amount);

                        $('#modalAmountInput').val(amount.toFixed(2));
                    } else {
                        $('#modalAmountInput').val(0);
                    }
                },
                error: function() {
                    console.error('Failed to fetch price.');
                }
            });
        });

        function fetchEvents(clientId, start, end, callback) {
            if (!clientId) {
                $('#totalLitter').text(0);
                $('#totalAmount').text(0);
                callback([]);
                return;
            }

            var url = '{{ route('transaction.unit', ['client_id' => ':client_id']) }}';
            url = url.replace(':client_id', clientId);

            $.ajax({
                url: url,
                method: 'GET',
                data: {
                    start: start.format('YYYY-MM-DD'),
                    end: end.format('YYYY-MM-DD')
                },
                success: function(data) {
                    console.log("Fetched Data:", data);
                    var totalLitter = 0;
                    var totalAmount = 0;

                    var events = data.map(function(event) {
                        var eventDate = moment(event.date);
                        if (eventDate.isBetween(start, end, 'day', '[]')) {
                            totalLitter += parseFloat(event.unit) || 0;
                            totalAmount += parseFloat(event.price) || 0;
                            return {
                                title: (event.unit ? 'Litter: ' + event.unit : '') +
                                    (event.price ? '\nAmount: ' + event.price : ''),
                                start: event.date,
                                allDay: true,
                                litter: event.unit || 0,
                                amount: event.price || 0
                            };

                        }
                    }).filter(Boolean);

                    $('#totalLitter').text(totalLitter.toFixed(2));
                    $('#totalAmount').text(totalAmount.toFixed(2));
                    callback(events);
                },
                error: function(xhr, status, error) {
                    console.error("AJAX Error:", error);
                    $('#totalLitter').text(0);
                    $('#totalAmount').text(0);
                    callback([]);
                }
            });
        }

        $('#clientSelect').on('change', function() {
            var clientId = $(this).val();

            if (!clientId) {
                $('#calendar').hide();
                $('#totalLitter').text(0);
                if ($('#calendar').hasClass('fc')) {
                    $('#calendar').fullCalendar('destroy');
                }
                return;
            }

            $('#calendar').show();

            if ($('#calendar').hasClass('fc')) {
                $('#calendar').fullCalendar('destroy');
            }

            $('#calendar').fullCalendar({
                header: {
                    left: 'prev,next',
                    center: 'title',
                    right: 'today'
                },
                defaultView: 'month',
                defaultDate: moment().startOf('month'),
                editable: false,

                events: function(start, end, timezone, callback) {
                    var selectedMonth = $('#calendar').fullCalendar(
                        'getDate');
                    var currentMonthStart = moment(selectedMonth).startOf(
                        'month');
                    var currentMonthEnd = moment(selectedMonth).endOf(
                        'month');

                    fetchEvents($('#clientSelect').val(), currentMonthStart,
                        currentMonthEnd, callback);
                },

                viewRender: function(view) {
                    var clientId = $('#clientSelect').val();
                    if (!clientId) return;

                    var selectedMonth = $('#calendar').fullCalendar(
                        'getDate');
                    var viewStart = moment(selectedMonth).startOf('month');
                    var viewEnd = moment(selectedMonth).endOf('month');

                    fetchEvents(clientId, viewStart, viewEnd, function() {});
                },

                dayRender: function(date, cell) {
                    var currentMonthStart = moment().startOf('month');
                    var currentMonthEnd = moment().endOf('month');

                    if (date.isBefore(currentMonthStart, 'day') || date.isAfter(
                            currentMonthEnd, 'day')) {
                        $(cell).addClass('fc-other-month');
                    }
                },

                dayClick: function(date) {
                    var selectedDate = date.format('YYYY-MM-DD');
                    var today = moment().format('YYYY-MM-DD');

                    if (selectedDate > today) {
                        console.log('Future entries are not allowed.');
                        return;
                    }

                    var events = $('#calendar').fullCalendar('clientEvents', function(
                        event) {
                        return moment(event.start).format('YYYY-MM-DD') ===
                            selectedDate;
                    });

                    var litterSum = events.reduce((sum, event) => sum + (event.litter || 0),
                        0);
                    var price = events.reduce((sum, event) => sum + (event.amount || 0),
                        0);
                    $('#modalDate').text(selectedDate);
                    $('#modalLitterInput').val(litterSum);
                    $('#modalAmountInput').val(price);
                    $('#litterDetailsModal').modal('show');

                    $('#saveLitterBtn').off('click').on('click', function() {
                        var newUnit = $('#modalLitterInput').val();
                        console.log(newUnit);
                        var newAmount = $('#modalAmountInput').val();
                        console.log(newAmount);
                        var clientId = $('#clientSelect').val();

                        $.ajax({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]')
                                    .attr('content')
                            },
                            url: events.length > 0 ?
                                '{{ route('transaction.update') }}' :
                                '{{ route('transaction.store') }}',
                            method: 'POST',
                            data: {
                                client_id: clientId,
                                date: selectedDate,
                                unit: newUnit,
                                price: newAmount
                            },
                            success: function() {
                                $('#litterDetailsModal').modal('hide');
                                $('#calendar').fullCalendar(
                                    'refetchEvents');
                            },
                            error: function() {
                                console.error(
                                    'Failed to update/add litter!');
                            }
                        });
                    });
                }
            });
        });

        $('#clientSelect').trigger('change');
    });
   
</script>

<script>
     $('#clientSelect').select2({
        placeholder: "Select Client",
        allowClear: false
        width: '100%'
        theme: "bootstrap4"
    });
</script>

<style>
    .responsive-heading {
        font-size: clamp(24px, 5vw, 40px);
    }

    .select2-container .select2-selection--single {
        height: 40px !important;
        font-size: 16px;
        border: 2px solid #d6d6d6 !important;
    }

</style>

@include('layouts.footer')
