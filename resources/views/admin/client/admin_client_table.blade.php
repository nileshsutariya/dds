@include('layouts.header')

<section class="content-header">
    <div class="container-fluid">
        <div class="row mt-1">
            <div class="col-12 col-md-7 text-center text-md-left ">
                <h1>Client</h1>
            </div>

            <div
                class="col-12 col-md-5 d-flex flex-column flex-md-row justify-content-center justify-content-md-end align-items-center align-items-md-end">
                <div class="card-tools mb-2 mb-md-0">
                    <div class="input-group input-group-lg">
                        <input type="text" class="form-control mr-2" placeholder="Search..." style="border-radius: 5px; height: 40px;" id="search-bar">
                    </div>
                </div>
                
                <div class="mt-2 mt-md-0">
                    <a href="{{ route('admin_client.create') }}" class="btn btn-primary btn-md"
                        style="white-space: nowrap;">Add Client</a>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card m-2 ">
                    <div class="card-body">
                        <div class="table-responsive">
                            <div id="client-table">
                                <table class="table table-bordered">
                                    <thead style="font-size: 15px; background-color: rgb(241, 241, 221)">
                                        <tr>
                                            <th style="width: 4px">No.</th>
                                            <th>Full Name</th>
                                            <th>Phone No </th>
                                            <th>Daily_units</th>
                                            <th>Address</th>
                                            <th>Area</th>
                                            <th>Status</th>
                                            <th style="width: 10px">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $i = 1;
                                        @endphp
                                        @if (isset($admin_client))
                                            @foreach ($admin_client as $client)
                                                <tr>
                                                    <input type="hidden" name="id[]" id="id{{ $i }}"
                                                        value="{{ $client->id }}">
                                                    <td>{{ $i }}</td>
                                                    <td>{{ $client->name }}</td>
                                                    <td>{{ $client->phone_no }}</td>
                                                    <td style="width: 130px;">
                                                        <input type="text" class="form-control" name="daily_unit"
                                                            value="{{ $client->daily_unit }}" style="width: 60px;"
                                                            id="daily_unit{{ $i }}">
                                                    </td>
                                                    <td>{{ $client->address }}</td>
                                                    <td>{{ $client->area }}</td>
                                                    <td>{{ $client->status }}</td>
                                                    <td>
                                                        <a href="{{ route('admin_client.edit', $client->id) }}"
                                                            class="btn btn-primary btn-sm">Edit</a>
                                                    </td>
                                                </tr>
                                                @php
                                                    $i++;
                                                @endphp
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>

                                <div id="pagination-links">
                                    {{ $admin_client->links('pagination::bootstrap-5') }}
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</section>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).ready(function() {

        var currentPage = 1;

        $(document).on('click', '.pagination a', function(event) {
            event.preventDefault();

            var page = $(this).attr('href').split('page=')[1];
            fetch_data(page);
        });

        function fetch_data(page) {
            $.ajax({
                url: "{{ route('admin_client.index') }}?page=" + page,
                success: function(data) {
                    var $html = $(data.html);
                    var rows = $html.find('#client-table tbody tr');

                    rows.each(function(index) {
                        $(this).find('td:first').text((page - 1) * 100 + index + 1);
                    });

                    $('#client-table').html($html.find('#client-table').html());
                    $('#pagination-links').html($(data.pagination).find('#pagination-links')
                        .html());

                    currentPage = page;
                }
            });
        }
    });

    //update daily_unit 
    $(document).ready(function() {
        $(document).on('input', '[id^="daily_unit"]', function() {

            var idu = $(this).attr('id');
            var index = idu.replace('daily_unit', '');
            var clientId = $('#id' + index).val();
            var dailyUnit = $(this).val();

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route('update.daily.unit') }}',
                type: 'POST',
                data: {
                    client_id: clientId,
                    daily_unit: dailyUnit
                },
                success: function(response) {
                    console.log('Daily Unit Updated successfully');
                },
                error: function(xhr, status, error) {
                    console.log('Error updating daily unit');
                }
            });
        });
    });

    $(document).ready(function() {
        $('#search-bar').on('keyup', function() {
            var value = $(this).val().toLowerCase();
            var rowsFound = false;

            $('.table tbody tr').each(function() {
                var name = $(this).find('td').eq(1).text().toLowerCase();
                var phone = $(this).find('td').eq(2).text().toLowerCase();

                if (name.indexOf(value) > -1 || phone.indexOf(value) > -1) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        });
    });
</script>

@include('layouts.footer')

<style>
    @media (max-width: 768px) {
        #search-bar {
            width: 350px;
            margin-top: 10px;
        }
    }
</style>
