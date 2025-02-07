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
                            <label>Clients</label>
                            <select class="form-control" name="client_id">
                                <option disabled selected>Select Client</option>
                                @foreach ($clients as $client)
                                    <option value="{{ $client->id }}"
                                        {{ old('client_id') == $client->id ? 'selected' : '' }}>
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
                },

                error: function(xhr) {
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

@include('layouts.footer')
