@include('layouts.header')
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
                    <form method="POST" action="{{route('admin.profile.update')}}">
                        @csrf
                        <div class="card-body">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Name</label>
                                <input type="text" class="form-control" name="name" 
                                    placeholder="Enter Name" value="{{ old('name', $profile->name) }}">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputPassword1">Phone no</label>
                                <input type="text" class="form-control" name="phone_no"
                                    placeholder="Phone no" value="{{ old('phone_no', $profile->phone_no) }}">
                                    @error('phone_no')
                                        <div class="text-danger">{{$message}}</div>
                                    @enderror
                            </div>
                            <div class="form-group">
                                <label for="exampleInputPassword1">Password</label>
                                <input type="text" class="form-control" name="password"
                                    placeholder="Password" value="{{old('password')}}">
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
