@extends('layouts.app')

@section('title', 'Profile')

@section('styles')
<link rel="stylesheet" href="{{ asset('assets/css/bootstrap-clockpicker.min.css') }}" type="text/css">
@endsection

@section('scripts')

<script src="{{ asset('assets/js/bootstrap-clockpicker.min.js') }}"></script>
<script>
$(document).ready(function() {

    $('.time-input').each(function() {
        var value = $(this).val();
        value = value.replace(/\s+(AM|PM)/i, ''); // Remove "AM" or "PM" (case-insensitive)
        $(this).val(value);
    });


    $('.clockpicker-example').clockpicker({
        donetext: 'Done'
    });
});
</script>

@endsection

@section('content')
<h1 class="h3 mb-3">Profile</h1>

@if(session('success'))
<x-alert type="success">{{ session('success') }}</x-alert>
@elseif(session('account'))
<x-alert type="success">{{ session('account') }}</x-alert>
@endif

<div class="content ">

    <div class="row flex-column-reverse flex-md-row">
        <div class="col-md-8">
            <div class="tab-content" id="myTabContent">
                <div id="profile" role="tabpanel" aria-labelledby="profile-tab">
                    <div class="mb-4">
                        <div class="card mb-4">
                            <div class="card-body">
                                <h6 class="card-title mb-4">Basic Information</h6>
                                <form method="post" action="{{ route('users.update', $user->id) }}">
                                    @csrf
                                    @method('PATCH')
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Name</label>
                                                <input type="text" class="form-control" name="name"
                                                    value="{{ $user->name }}">
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Shift Start</label>
                                                <div class="input-group clockpicker-example" data-autoclose="true">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">
                                                            <i class="bi bi-clock"></i>
                                                        </span>
                                                    </div>
                                                    <input type="text" name="time_in" class="form-control time-input"
                                                        placeholder="Select Time" value=" {{ $user->time_in }}">
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
                                                    <input type="text" name="break_in" class="form-control time-input"
                                                        placeholder="Select Time" value=" {{ $user->break_in }}">
                                                </div>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label">Positions</label>
                                                <select class="form-select" name="positions[]" multiple
                                                    aria-label="multiple select example" style="height: 200px;">
                                                    <option selected disabled>Select positions</option>
                                                    @foreach($positions as $position)
                                                    <!-- <option value="{{ $position->id }}">{{ $position->name }}</option> -->

                                                    @if(in_array($position->id, $user_positions))
                                                    <option value="{{ $position->id }}" selected>{{ $position->name }}
                                                    </option>
                                                    @else
                                                    <option value="{{ $position->id }}">{{ $position->name }}</option>
                                                    @endif
                                                    @endforeach

                                                </select>
                                            </div>

                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Age</label>
                                                <input type="number" name="age" class="form-control"
                                                    value="{{ $user->age }}">
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Shift End</label>
                                                <div class="input-group clockpicker-example" data-autoclose="true">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">
                                                            <i class="bi bi-clock"></i>
                                                        </span>
                                                    </div>
                                                    <input type="text" name="time_out" class="form-control time-input"
                                                        placeholder="Select Time" value=" {{ $user->time_out }}">
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
                                                    <input type="text" name="break_out" class="form-control time-input"
                                                        placeholder="Select Time" value=" {{ $user->break_out }}">
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