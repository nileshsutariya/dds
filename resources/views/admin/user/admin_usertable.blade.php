@include('layouts.header')

<section class="content-header">
    <div class="container-fluid">
        <div class="row mt-1">
            <div class="col-12 col-md-7 text-center text-md-left">
                <h1>User</h1>
            </div>

            <div
                class="col-12 col-md-5 d-flex flex-column flex-md-row justify-content-center justify-content-md-end align-items-center align-items-md-end">
                <div class="card-tools mb-2 mb-md-0">
                    <div class="input-group input-group-lg">
                        <input type="text" class="form-control mr-2" placeholder="Search..." style="border-radius: 5px; height: 40px;" id="search-bar">
                    </div>
                </div>
                

                <div class="mt-2 mt-md-0">
                    <a href="{{ route('admin.create') }}" class="btn btn-primary btn-md"
                        style="white-space: nowrap;">Add User</a>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <section class="content">
                <div class="card m-2">
                    <div class="card-body">
                        <div id="admin_usertable">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead style="font-size: 15px; background-color: rgb(241, 241, 221)">
                                        <tr>
                                            <th style="width: 4px">No.</th>
                                            <th>Full Name</th>
                                            <th>Phone No </th>
                                            <th>User Name</th>
                                            <th >Address</th>
                                            <th >Area</th>
                                            <th style="width: 10px">Action</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @php
                                            $i = 1;
                                        @endphp
                                        @if (isset($admins))
                                            @foreach ($admins as $adm)
                                                <tr>
                                                    <td>{{ $i }}</td>
                                                    <td>{{ $adm->name }}</td>
                                                    <td>{{ $adm->phone_no }}</td>
                                                    <td>{{ $adm->user_name }}</td>
                                                    <td>{{ $adm->address }}</td>
                                                    <td>
                                                        {{ implode(', ', $adm->areas->toArray()) }}
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('admin.edit', $adm->id) }}"
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
                                    {{ $admins->links('pagination::bootstrap-5') }}
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {

        $(document).on('click', '.pagination a', function(event) {
            event.preventDefault();

            var page = $(this).attr('href').split('page=')[1];
            fetch_data(page);
        });

        function fetch_data(page) {
            $.ajax({
                url: "{{ route('admin.index') }}?page=" + page,
                success: function(data) {
                    var $html = $(data.html);
                    var rows = $html.find('#admin_usertable tbody tr');

                    rows.each(function(index) {
                        $(this).find('td:first').text((page - 1) * 100 + index + 1);
                    });

                    $('#admin_usertable').html($html.find('#admin_usertable').html());
                    $('#pagination-links').html($(data.pagination).find('#pagination-links')
                        .html());

                    currentPage = page;
                }
            });
        }
    });

    $(document).ready(function() {
        $('#search-bar').on('keyup', function() {
            var value = $(this).val().toLowerCase();
            
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
