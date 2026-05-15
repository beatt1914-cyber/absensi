@extends('layouts.master')

@section('title', 'Profile')

@section('content')

<div class="row">
    <div class="col-md-8 mx-auto">

        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h4>Profile User</h4>
            </div>

            <div class="card-body">

                <table class="table">
                    <tr>
                        <th width="30%">Nama</th>
                        <td>{{ $user['name'] }}</td>
                    </tr>

                    <tr>
                        <th>Role</th>
                        <td>{{ $user['role'] }}</td>
                    </tr>

                    <tr>
                        <th>Bergabung</th>
                        <td>{{ $user['joined'] }}</td>
                    </tr>

                    <tr>
                        <th>Status</th>
                        <td>
                            <span class="badge bg-success">
                                {{ $user['status'] }}
                            </span>
                        </td>
                    </tr>
                </table>

                <a href="/" class="btn btn-secondary">
                    Kembali ke Home
                </a>

            </div>
        </div>

    </div>
</div>

@endsection