@include('layouts.header')

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card card-default m-3">
                <div class="card-header" style="background-color: rgb(249, 248, 252)">
                    <h1 class="card-title" style="font-size: 20px">Expense</h1>
                </div>
                <form method="post" action="{{ route('payment.store', ['type' => 'd']) }}">                    
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label for="date">Date</label>
                            <input type="date" class="form-control" id="date" onclick="this.showPicker();"
                                name="date" max="">
                            @error('date')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="amount">Amount</label>
                            <input type="text" class="form-control" id="amount" placeholder="Enter Amount"
                                name="amount" value="{{old('amount')}}">
                            @error('amount')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="note">Note</label>
                            <input type="text" class="form-control" id="note" placeholder="Note" name="note" value="{{old('note')}}">
                            @error('note')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror

                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Submit</button>
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

        // $(".text-danger").remove();

        $.ajax({
            type: form.attr("method"),
            url: form.attr("action"),
            data: formData,
            dataType: "json",
            success: function(response) {
                console.log(response); 
                
                form[0].reset();
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
