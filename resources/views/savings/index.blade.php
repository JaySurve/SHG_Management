@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12"><br>
        <h2 class="text-2xl font-bold mb-4">Savings</h2>
        <link rel="stylesheet" href="{{ asset(path: 'css/table.css') }}">
        @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            <a href="{{ route('savings.create') }}" class="btn btn-primary mb-3">Add New Saving</a>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Member ID</th>
                        <th>Member Name</th>
                        <th>Amount</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($savings as $saving)
                        <tr>
                            <td>{{ $saving->id }}</td>
                            <td>{{ $saving->member_name }}</td>
                            <td>{{ $saving->amount }}</td>
                            <td>{{ $saving->date_of_deposit }}</td>
                            <td>
                                <a href="{{ route('savings.show', $saving->id) }}" class="btn btn-info">View</a>
                                <a href="{{ route('savings.edit', $saving->id) }}" class="btn btn-warning">Edit</a>
                                <form action="{{ route('savings.destroy', $saving->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this saving?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
