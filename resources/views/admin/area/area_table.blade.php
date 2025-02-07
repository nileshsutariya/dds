@include('layouts.header')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mt-1">
            <div class="col-12 col-md-7 text-center text-md-left">
                <h1>Area</h1>
            </div>

            <div
                class="col-12 col-md-5 d-flex flex-column flex-md-row justify-content-center justify-content-md-end align-items-center align-items-md-end">
                <div class="card-tools mb-2 mb-md-0">
                    <div class="input-group input-group-lg">
                        <input type="text" class="form-control mr-2" placeholder="Search..." style="border-radius: 5px; height: 40px;" id="search-bar">
                    </div>
                </div>

                <div class="mt-2 mt-md-0">
                    <a href="{{ route('area.create') }}" class="btn btn-primary btn-md" style="white-space: nowrap;">Add
                        Area</a>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluied">
        <div class="row">
            <div class="col-md-12">
                <div class="card m-2">
                    <div class="card-body">
                        <div id="area-table">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead style="font-size: 15px; background-color: rgb(241, 241, 221)">
                                        <tr>
                                            <th style="width: 4px">No.</th>
                                            <th>Area</th>
                                            <th>Code</th>
                                            <th style="width: 10px">Action</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @php
                                            $i = 1;
                                        @endphp
                                        @if (isset($areas))
                                            @foreach ($areas as $area)
                                                <tr>
                                                    <td>{{ $i }}</td>
                                                    <td>{{ $area->area_name }}</td>
                                                    <td>{{ $area->code }}</td>
                                                    <td>
                                                        <a href="{{ route('area.edit', $area->id) }}"
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
                                    {{ $areas->links('pagination::bootstrap-5') }}
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
                url: "{{ route('area.index') }}?page=" + page,
                success: function(data) {
                    var $html = $(data.html);
                    var rows = $html.find('#area-table tbody tr');

                    rows.each(function(index) {
                        $(this).find('td:first').text((page - 1) * 100 + index + 1);
                    });

                    $('#area-table').html($html.find('#area-table').html());
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
                var area = $(this).find('td').eq(1).text().toLowerCase();
                var pincode = $(this).find('td').eq(2).text().toLowerCase();

                if (area.indexOf(value) > -1 || pincode.indexOf(value) > -1) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        });
    });
</script>

@include('layouts.footer')
