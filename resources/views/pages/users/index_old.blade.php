@extends('layouts.app')

@section('title', __('Users'))

@section('scripts')
    <script>
        $(document).ready(function () {
            $('#users-table').DataTable();
        });
    </script>
@endsection

@section('content')
    <h1 class="h3 mb-3">{{ __('All Users') }}</h1>

    @if(session('success'))
        <x-alert type="success">{{ session('success') }}</x-alert>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="dt-buttons btn-group flex-wrap">
                        {{--                        <button class="mb-4 btn btn-secondary buttons-copy buttons-html5"--}}
                        {{--                                type="button"--}}
                        {{--                                id="btn_export"--}}
                        {{--                                value="1"--}}
                        {{--                                onclick="get_query_params()">--}}


                        {{--                            <span>Export</span>--}}
                        {{--                        </button>--}}

                    </div>

                    @if(session('delete'))
                        <x-alert type="danger">{{ session('delete') }}</x-alert>
                    @elseif(session('password_update'))
                        <x-alert type="success">{{ session('password_update') }}</x-alert>
                    @elseif(session('account'))
                        <x-alert type="success">{{ session('account') }}</x-alert>
                    @endif


                    <table id="users-table" class="table table-striped" style="width:100%">
                        <thead>
                        <tr>
                            <th>{{ 'ID' }}</th>
                            <th>{{ 'Name' }}</th>
                            <th>{{ 'Age' }}</th>
                            <th>{{ 'Shift Time' }}</th>
                            <th>{{ 'Break Time' }}</th>
                            <th>{{ 'Positions' }}</th>
                            <th>{{ 'Actions' }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($users as $user)
                            <tr>

                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->age }}</td>

                                <td>{{ $user->time_in }} - {{ $user->time_out }}</td>
                                <td>{{ $user->break_in }} - {{ $user->break_out }}</td>

                                <td>

                                    @foreach($user->positions as $position)
                                        <span class="badge bg-info">{{ $position['name'] }}</span>
                                    @endforeach

                                </td>

                                <td class="table-action">
                                    <a href="{{ route('users.edit', $user->id) }}" class="btn" style="display: inline">
                                        <i class="fa fa-edit text-info"></i>
                                    </a>

                                    <form method="post" action="{{ route('users.destroy', $user->id) }}"
                                          onsubmit="return confirmSubmission(this, 'Are you sure you want to delete user ' + '{{ "$user->name"  }}')"
                                          style="display: inline">
                                        @csrf
                                        @method('DELETE')

                                        <button class="btn text-danger"
                                                href="{{ route('users.destroy', $user->id) }}">
                                            <i class="fa fa-trash"></i>

                                        </button>
                                    </form>


                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
