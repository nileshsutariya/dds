@include('layouts.header')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mt-1">
            <div class="col-12 col-md-7 text-center text-md-left">
                <h1 style="font-size: 40px;">Unit</h1>
            </div>

            <div
                class="col-12 col-md-5 d-flex flex-column flex-md-row justify-content-center justify-content-md-end align-items-center align-items-md-end">
                <div class="card-tools mb-2 mb-md-0">
                    <div class="input-group input-group-lg">
                        <input type="text" class="form-control mr-2 p-4" placeholder="Search..."
                            style="border-radius: 5px;">
                    </div>
                </div>

                <div class="mt-2 mt-md-0">
                    <a href="{{ route('unit.create') }}" class="btn btn-primary btn-lg"
                        style="white-space: nowrap;">Add Unit</a>
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
                        <div id="unit-table">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead style="font-size: 20px ; background-color: rgb(241, 241, 221)">
                                        <tr>
                                            <th style="width: 4px">No.</th>
                                            <th>unit_name</th>
                                            <th>unit_symbol</th>
                                            <th>unit_type</th>
                                            <th style="width: 10px">Action</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @php
                                            $i = 1;
                                        @endphp
                                        @if (isset($units))
                                            @foreach ($units as $unit)
                                                <tr>
                                                    <td>{{ $i }}</td>
                                                    <td>{{ $unit->unit_name }}</td>
                                                    <td>{{ $unit->unit_symbol }}</td>
                                                    <td>{{ $unit->unit_type }}</td>
                                                    <td>
                                                        <a href="{{ route('unit.edit', $unit->id) }}"
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
                                    {{ $units->links('pagination::bootstrap-5') }}
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
                url: "{{ route('unit.index') }}?page=" + page,
                success: function(data) {
                    $('#unit-table').html($(data.html).find('#unit-table').html());
                    $('#pagination-links').html($(data.pagination).find('#pagination-links')
                        .html());
                }
            });
        }
    });
</script>

@include('layouts.footer')
