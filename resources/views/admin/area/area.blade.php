@include('layouts.header')

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card card-default m-3">
                <div class="card-header"  style="background-color: rgb(249, 248, 252)">
                    <h1 class="card-title" style="font-size: 20px">{{ isset($area) ? 'Edit Area' : 'Create Area' }}</h1>
                </div>
                <form method="post" action="{{ isset($area) ? route('area.update', $area->id) : route('area.store') }}">
                    @csrf
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Area</label>
                                    <input type="text" class="form-control " name="area_name"
                                        value="{{ old('area_name', isset($area) ? $area->area_name : '') }}">
                                    @error('area_name')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Code</label>
                                    <input type="text" class="form-control" name="code"
                                        value="{{ old('code', isset($area) ? $area->code : '') }}">
                                    @error('code')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
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

@include('layouts.footer')
