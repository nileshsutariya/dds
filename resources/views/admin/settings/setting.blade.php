@include('layouts.header')

<div class="container-fluid">
    <div class="row">
        <div class="col-md-6">
            <div class="card card-default m-3">
                <div class="card-header" style="background-color: rgb(249, 248, 252)">
                    <h1 class="card-title" style="font-size: 20px">Price</h1>
                </div>
                <form method="post" action="{{route('settings.store')}}">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Enter Price</label>
                                    <input type="text" class="form-control " name="price" placeholder="Enter Your Price"  value="{{ old('price', $price) }}">
                                </div>
                                @error('price')
                                    <div class="text-danger">{{$message}}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary col-12 col-md-2">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@include('layouts.footer')
