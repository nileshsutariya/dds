@include('layouts.user_header')
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
                <!-- general form elements -->
                <div class="card mt-4" style="max-width: 900px">
                    <div class="card-header">
                        <h3 class="card-title">
                            <h2 class="text-primary">Profile</h2>
                        </h3>
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                    <form method="POST" action="{{ route('profile.update') }}">
                        @csrf
                        <div class="card-body">
                            <input type="hidden" value="{{ $user->id }}">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Name</label>
                                <input type="text" class="form-control" name="name" placeholder="Enter Name"
                                    value="{{ old('name', $user->name) }}">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputPhone1">Phone no</label>
                                <input type="text" class="form-control" name="phone_no" placeholder="Phone no"
                                    value="{{ old('phone_no', $user->phone_no) }}">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputPassword1">Password</label>
                                <input type="text" class="form-control" name="password" placeholder="Password"
                                    value="{{ old('password') }}">
                            </div>
                            <div class="form-group">
                                <label>User Name</label>
                                <input type="text" class="form-control" name="user_name"
                                    value="{{ $user->user_name }}">
                            </div>
                            <div class="form-group">
                                <label>adress</label>
                                <input type="text" class="form-control" name="address" value="{{ $user->address }}">
                            </div>
                            <div class="form-group">
                                <label>Area</label>
                                <div class="select2-secondary">
                                    <select class="select2" multiple="multiple" data-placeholder="Select Area"
                                        data-dropdown-css-class="select2-secondary" style="width: 100%;" name="area[]">
                                        @foreach ($areas as $area)
                                            <option value="{{ $area->id }}"
                                                @if (isset($user) && in_array($area->id, explode(',', $user->area))) selected @endif>
                                                {{ $area->area_name }}
                                            </option>         
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->

                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary btn-lg">Save changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

@include('layouts.footer')
