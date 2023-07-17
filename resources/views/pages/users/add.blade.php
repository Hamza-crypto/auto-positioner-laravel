@extends('layouts.app')

@section('title', __('Add New Employee'))
@section('styles')
<link rel="stylesheet" href="{{ asset('assets/css/bootstrap-clockpicker.min.css') }}" type="text/css">
@endsection
@section('scripts')

<script src="{{ asset('assets/js/bootstrap-clockpicker.min.js') }}"></script>
<script>
$(document).ready(function() {
    $('.clockpicker-example').clockpicker({
        donetext: 'Done'
    });
});
</script>

@endsection
@section('content')
<div class="content ">

    <div class="row flex-column-reverse flex-md-row">
        <div class="col-md-8">
            <div class="tab-content" id="myTabContent">
                <div id="profile" role="tabpanel" aria-labelledby="profile-tab">
                    <div class="mb-4">
                        <div class="card mb-4">
                            <div class="card-body">
                                <h6 class="card-title mb-4">Basic Information</h6>
                                <form method="post" action="{{ route('users.store') }}">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Name</label>
                                                <input type="text" class="form-control" name="name">
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Shift Start</label>
                                                <div class="input-group clockpicker-example" data-autoclose="true">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">
                                                            <i class="bi bi-clock"></i>
                                                        </span>
                                                    </div>
                                                    <input type="text" name="time_in" class="form-control"
                                                        placeholder="Select Time">
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Break Start</label>
                                                <div class="input-group clockpicker-example" data-autoclose="true">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">
                                                            <i class="bi bi-clock"></i>
                                                        </span>
                                                    </div>
                                                    <input type="text" name="break_in" class="form-control"
                                                        placeholder="Select Time">
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Positions</label>
                                                <select class="form-select" name="positions[]" multiple
                                                    aria-label="multiple select example" style="height: 200px;">
                                                    <option selected disabled>Select positions</option>
                                                    @foreach($positions as $position)
                                                    <option value="{{ $position->id }}">{{ $position->name }}</option>
                                                    @endforeach

                                                </select>
                                            </div>

                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Age</label>
                                                <input type="number" name="age" class="form-control">
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Shift End</label>
                                                <div class="input-group clockpicker-example" data-autoclose="true">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">
                                                            <i class="bi bi-clock"></i>
                                                        </span>
                                                    </div>
                                                    <input type="text" name="time_out" class="form-control"
                                                        placeholder="Select Time">
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Break End</label>
                                                <div class="input-group clockpicker-example" data-autoclose="true">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">
                                                            <i class="bi bi-clock"></i>
                                                        </span>
                                                    </div>
                                                    <input type="text" name="break_out" class="form-control"
                                                        placeholder="Select Time">
                                                </div>
                                            </div>
                                        </div>

                                        <button type="submit" class="btn btn-primary">Save</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection