@include('layouts.header')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow-sm m-2">
                <div class="card-header" style="background-color: rgb(249, 248, 252)">
                    <h1 class="card-title " style="font-size: 20px;">
                        {{ isset($admin) ? 'Edit User' : 'Create User' }}</h1>
                </div>
                <div class="card-body">
                    <form method="post"
                        action="{{ isset($admin) ? route('admin.update', $admin->id) : route('admin.store') }}">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Full Name</label>
                                    <input type="text" class="form-control" id="name" name="name"
                                        value="{{ old('name', isset($admin) ? $admin->name : '') }}">
                                    @error('name')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="user_name">User Name</label>
                                    <input type="text" class="form-control" id="user_name" name="user_name"
                                        value="{{ old('user_name', isset($admin) ? $admin->user_name : '') }}">
                                    @error('user_name')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="phone_no">Phone Number</label>
                                    <input type="text" class="form-control" id="phone_no" name="phone_no"
                                        value="{{ old('phone_no', isset($admin) ? $admin->phone_no : '') }}">
                                    @error('phone_no')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password">Password</label>
                                    <input type="password" class="form-control" id="password" name="password"
                                        value="{{ old('password') }}">
                                    @error('password')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="address">Address</label>
                                    <input type="text" class="form-control" id="address" name="address"
                                        value="{{ old('address', isset($admin) ? $admin->address : '') }}">
                                    @error('address')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Area Dropdown -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="area">Area</label>
                                    <div class="select2-primary">
                                        <select class="select2 form-control" name="area[]" id="area"
                                            multiple="multiple" data-placeholder="Select Area">
                                            @foreach ($areas as $area)
                                                <option value="{{ $area->id }}"
                                                    @if (isset($admin) && in_array($area->id, explode(',', $admin->area))) selected @endif>
                                                    {{ $area->area_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('area')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 m-1">
                                <div class="form-group form-check">
                                    <input type="checkbox" class="form-check-input" id="status" name="status"
                                        value="1" {{ isset($admin) && $admin->status == '1' ? 'checked' : '' }}>
                                    <label class="form-check-label ml-1" for="status" style="font-size: 18px;">Is
                                        Active</label>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary btn-md col-12 col-md-6 col-lg-2">
                                Submit
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@include('layouts.footer')

<style>
    #status {
        transform: scale(1.5);
    }

    .select2-container--default .select2-selection--multiple {
        height: 38px !important;
        border: 2px solid #ebe8e8 !important;
    }
</style>
