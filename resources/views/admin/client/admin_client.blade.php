@include('layouts.header')

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card card-default m-2">
                <div class="card-header" style="background-color: rgb(249, 248, 252)">
                    <h1 class="card-title" style="font-size: 20px">
                        {{ isset($client) ? 'Edit Client' : 'Create Client' }}</h1>
                </div>
                <form method="post"
                    action="{{ isset($admin) ? route('admin_client.update', $admin->id) : route('admin_client.store') }}">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Full Name</label>
                                    <input type="text" class="form-control" name="name"
                                        value="{{ old('name', isset($admin) ? $admin->name : '') }}">
                                    @error('name')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label>Phone Number</label>
                                    <input type="text" class="form-control" name="phone_no"
                                        value="{{ old('phone_no', isset($admin) ? $admin->phone_no : '') }}">
                                    @error('phone_no')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Address</label>
                                    <input type="text-area" class="form-control" name="address"
                                        value="{{ old('address', isset($admin) ? $admin->address : '') }}">
                                    @error('address')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label>Area</label>
                                    <select class="form-control select2" name="area" id="area">
                                        <option disabled selected>Select Area</option>
                                        @foreach ($areas as $area)
                                            <option value="{{ $area->area_name }}"
                                                {{ old('area', isset($admin) ? $admin->area : '') == $area->area_name ? 'selected' : '' }}>
                                                {{ $area->area_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('area')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Daily Unit</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="unit"
                                            placeholder="Enter value"
                                            value="{{ old('unit', isset($admin) ? $admin->daily_unit : '') }}">
                                        <div class="input-group-append">
                                            <span class="input-group-text">Liter</span>
                                        </div>
                                    </div>
                                    @error('unit')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Password</label>
                                    <input type="password" class="form-control address" name="password"
                                        value="{{ old('password') }}">
                                    @error('password')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 m-1">
                                    <div class="form-group form-check">
                                        <input type="checkbox" class="form-check-input" id="status" name="status"
                                            value="1"
                                            {{ isset($admin) && $admin->status == '1' ? 'checked' : '' }}>
                                        <label class="form-check-label ml-1" for="status" style="font-size: 18px;">Is
                                            Active</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary col-12 col-md-6 col-lg-2 btn-md">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $('#area').select2({
       placeholder: "Select area",
       allowClear: false,
       width: '100%',
       theme: "bootstrap4",
   });
</script>

@include('layouts.footer')

<style>
    #status {
        transform: scale(1.5);
    }

    .select2-container .select2-selection--single {
        height: 38px !important;
        font-size: 16px;
        border: 2px solid #ebe8e8 !important;
    }
</style>
