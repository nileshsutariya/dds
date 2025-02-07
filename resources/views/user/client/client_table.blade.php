@include('layouts.user_header')
<section class="content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6 col-6">
                <h1 style="font-size: 40px">Client</h1>
            </div>
            <div class="col-md-6 col-6  d-flex justify-content-end">
                <a href="{{ route('client.create') }}" class="btn btn-primary btn-lg">Add Client</a>
            </div>
        </div>
    </div>
</section>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card m-3">
                    <div class="card-body">
                        <div class="table-responsive">
                            <div id="client-table">
                                <table class="table table-bordered">
                                    <thead style="font-size: 20px; background-color: rgb(241, 241, 221)">
                                        <tr>
                                            <th>No.</th>
                                            <th>Full Name</th>
                                            <th>Phone No </th>
                                            <th>Address</th>
                                            <th>Area</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @php
                                            $i = 1;
                                        @endphp
                                        @if (isset($clients))

                                            @foreach ($clients as $client)
                                                <tr>
                                                    <td>{{ $i }}</td>
                                                    <td>{{ $client->name }}</td>
                                                    <td>{{ $client->phone_no }}</td>
                                                    <td>{{ $client->address }}</td>
                                                    <td>
                                                        {{ implode(', ', $client->areas->toArray()) }}
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('client.edit', $client->id) }}"
                                                            class="btn btn-primary">Edit</a>
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
                                    {{ $clients->links('pagination::bootstrap-5') }}
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
    $(document).ready(function() {

        $(document).on('click', '.pagination a', function(event) {
            event.preventDefault();

            var page = $(this).attr('href').split('page=')[1];
            fetch_data(page);
        });

        function fetch_data(page) {
            $.ajax({
                url: "{{ route('client.index') }}?page=" + page,
                success: function(data) {
                    $('#client-table').html($(data.html).find('#client-table').html());
                    $('#pagination-links').html($(data.pagination).find('#pagination-links')
                        .html());
                }
            });
        }
    });
</script>
@include('layouts.footer')
