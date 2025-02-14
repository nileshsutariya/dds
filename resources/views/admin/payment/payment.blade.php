@include('layouts.header')

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card card-default m-3">
                <div class="card-header" style="background-color: rgb(249, 248, 252)">
                    <h1 class="card-title" style="font-size: 20px">Payment Receive</h1>
                </div>
                <form method="post" action="{{ route('payment.store', ['type' => 'c']) }}">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label for="date">Date</label>
                            <input type="date" class="form-control" id="date" onclick="this.showPicker();"
                                name="date" max="" value="{{ old('date') }}">
                            @error('date')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Clients<span id="totalAmount" style="font-weight: bold; color: red;"></span></label>
                            <select class="form-control select2" name="client_id" id="clients">
                                <option disabled selected>Select Client</option>
                                @foreach ($clients as $client)
                                    <option value="{{ $client->id }}" data-total="{{ $client->total_amount ?? 0 }}">
                                        {{ old('client_id') == $client->id ? 'selected' : '' }}
                                        {{ $client->name }}
                                    </option>
                                @endforeach
                            </select>

                            @error('client_id')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="amount">Amount</label>
                            <input type="text" class="form-control" id="amount" placeholder="Enter Amount"
                                name="amount" value="{{ old('amount') }}">
                            @error('amount')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="note">Note</label>
                            <input type="text" class="form-control" id="note" placeholder="Note" name="note"
                                value="{{ old('note') }}">
                            {{-- @error('note')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror --}}
                        </div>
                    </div>

                    <div class="card-footer d-flex justify-content-between ">
                        <button type="submit" class="btn btn-primary">Submit</button>
                        <div class="total_amount fw-bold" style="font-size: 20px"></div>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var today = new Date().toISOString().split('T')[0];
        document.getElementById('date').setAttribute('max', today);
    });
</script>

<script>
    $(document).ready(function() {
        $("#clients").on("change", function() {
            var clientId = $(this).val();

            if (clientId) {
                $.ajax({
                    url: "{{ route('getClientTotalAmount') }}",
                    type: "GET",
                    data: {
                        client_id: clientId
                    },
                    success: function(response) {
                        console.log("Response:", response);

                        if (response.success) {
                            let totalPaid = response.total_amount;
                            let totalDue = response.total_due;
                            let pendingAmount = response.pending_amount;
                            let advancePayment = response.advance_payment || 0;

                            if (pendingAmount > 0) {
                                $("#totalAmount").html(` (Pending: ${pendingAmount})`).css(
                                    "color", "red");
                            } else if (advancePayment > 0) {
                                $("#totalAmount").html(` (Advance: ${advancePayment})`).css(
                                    "color", "green");
                            } else {
                                $("#totalAmount").text(" (No Due)").css("color", "black");
                            }
                        } else {
                            $("#totalAmount").text(" (No Data)").css("color", "black");
                        }
                    },
                    error: function(xhr) {
                        console.error("Error fetching total amount:", xhr);
                        $("#totalAmount").text(" (Error fetching amount)");
                    }
                });
            } else {
                $("#totalAmount").text("");
            }
        });

        $("form").on("submit", function(event) {
            event.preventDefault();

            var form = $(this);
            var formData = form.serialize();
            var totalAmountDiv = $(".total_amount");

            $(".text-danger").remove();

            $.ajax({
                type: form.attr("method"),
                url: form.attr("action"),
                data: formData,
                dataType: "json",
                success: function(response) {
                    console.log(response);
                    var totalAmountDiv = $(".total_amount");

                    if (response.pending_amount > 0) {
                        totalAmountDiv.html("Pending Amount: " + response.pending_amount)
                            .css("color", "red");
                    }
                    if (response.advance_payment > 0) {
                        totalAmountDiv.append(
                            '<span style="color: green;">Advance Payment: ' + response
                            .advance_payment + '</span>');
                    }
                    // if (response.pending_amount = 0) {
                    //     totalAmountDiv.html("Payment Done successfully")
                    //         .css("color", "green");
                    // }

                    $("form")[0].reset();
                    $("#totalAmount").text("");
                },

                error: function(xhr) {
                    console.log(xhr.responseJSON);
                    if (xhr.status === 422) {
                        var errors = xhr.responseJSON.errors;
                        $.each(errors, function(key, value) {
                            var inputField = $('[name="' + key + '"]');
                            inputField.after('<div class="text-danger">' + value[
                                0] + '</div>');

                            inputField.on("input", function() {
                                $(this).next(".text-danger").remove();
                            });

                            if (inputField.is("select")) {
                                inputField.on("change", function() {
                                    $(this).next(".text-danger").remove();
                                });
                            }
                        });
                    } else {
                        totalAmountDiv.text("Error processing payment").css("color", "red");
                    }
                }
            });
        });
    });
</script>

<script>
    $('#clients').select2({
        placeholder: "Select client",
        allowClear: false,
        width: '100%',
        theme: "bootstrap4",
    });
</script>

@include('layouts.footer')

<style>
    .select2-container .select2-selection--single {
        height: 38px !important;
        font-size: 16px;
        border: 2px solid #ebe8e8 !important;
    }
</style>
