@include('layouts.header')

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card card-default m-3" style="max-width: 900px">
                <div class="card-header" style="background-color: rgb(249, 248, 252)">
                    <h1 class="card-title text-primary" style="font-size: 30px">
                        {{ isset($unit) ? 'Edit Unit' : 'Create Unit' }}</h1>
                </div>
                <form method="post" action="{{ isset($unit) ? route('unit.update', $unit->id) : route('unit.store') }}">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Unit Name</label>
                                    <input type="text" class="form-control" name="unit_name"
                                        value="{{ old('unit_name', isset($unit) ? $unit->unit_name : '') }}">
                                    @error('unit_name')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Unit symbol</label>
                                    <input type="text" class="form-control" name="unit_symbol"
                                        value="{{ old('unit_symbol', isset($unit) ? $unit->unit_symbol : '') }}">
                                    @error('unit_symbol')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="unit_type">Unit Type</label>
                                    <select class="form-control" name="unit_type" id="unit_type">
                                        <option value="" disabled selected>Select Unit Type</option>
                                        <option value="weight"
                                            {{ old('unit_type', isset($unit) ? $unit->unit_type : '') == 'weight' ? 'selected' : '' }}>
                                            Weight</option>
                                        <option value="volume"
                                            {{ old('unit_type', isset($unit) ? $unit->unit_type : '') == 'volume' ? 'selected' : '' }}>
                                            Volume</option>
                                        <option value="length"
                                            {{ old('unit_type', isset($unit) ? $unit->unit_type : '') == 'length' ? 'selected' : '' }}>
                                            Length</option>
                                    </select>
                                    @error('unit_type')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 m-1">
                                <div class="form-group form-check">
                                    <!-- Larger Checkbox -->
                                    <input type="checkbox" class="form-check-input" id="status" name="status"
                                        value="1" {{ isset($unit) && $unit->status == '1' ? 'checked' : '' }}>
                                    <label class="form-check-label ml-1" for="status" style="font-size: 18px;">Is
                                        Active</label>
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

<style>
    #status {
        transform: scale(1.5);
    }
</style>
