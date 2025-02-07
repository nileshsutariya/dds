@include('layouts.user_header')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card card-default m-3">
                <div class="card-header" style="background-color: rgb(249, 248, 252)">
                    <h1 class="card-title" style="font-size: 30px">
                        {{ isset($client) ? 'Edit Client' : 'Create Client' }}</h1>
                </div>
                <form method="post"
                    action="{{ isset($client) ? route('client.update', $client->id) : route('client.store') }}">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Full Name</label>
                                    <input type="text" class="form-control" name="name"
                                        value="{{ old('name', isset($client) ? $client->name : '') }}">
                                    @error('name')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror

                                </div>
                                <div class="form-group">
                                    <label>Phone Number</label>
                                    <input type="text" class="form-control" name="phone_no"
                                        value="{{ old('phone_no', isset($client) ? $client->phone_no : '') }}">
                                    @error('phone_no')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Address</label>
                                    <input type="text-area" class="form-control" name="address"
                                        value="{{ old('address', isset($client) ? $client->address : '') }}">
                                    @error('address')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror

                                </div>
                                <div class="form-group">
                                    <label>Area</label>
                                    <div class="select2-secondary">
                                        <select class="select2" multiple="multiple" data-placeholder="Select Area"
                                            data-dropdown-css-class="select2-secondary" style="width: 100%;"
                                            name="area[]">
                                            @foreach ($areas as $area)
                                                <option value="{{ $area->id }}"
                                                    @if (isset($client) && in_array($area->id, explode(',', $client->area))) selected @endif>
                                                    {{ $area->area_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('area')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Daily Unit</label>
                                    <input type="text" class="form-control" name="unit">
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
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary col-12 col-md-6 col-lg-2 btn-lg">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@include('layouts.footer')
