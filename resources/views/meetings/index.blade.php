@extends('layouts.app')

@section('content')
<br>
<div class="container">
    <h2>Meetings</h2>
    <link rel="stylesheet" href="{{ asset('css/Meetings/Index.css') }}">
    <div class="pull-right">
        <a href="{{ route('meetings.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i>Schedule a New Meeting</a>
    </div>
    <div class="table-container">
        <table class="table mt-3">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Photo</th>
                    <th>Group Name</th>
                    <th>Group ID</th>
                    <th>Discussion Points</th>
                    <th>No. Members Present</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($meetings as $meeting)
                <tr>
                    <td>{{ $meeting->date }}</td>
                    <td><img src="{{ asset('storage/' . $meeting->photo) }}" alt="Group Photo" style="width: 50px; height: 30px;"></td>
                    <td>{{ $meeting->group_name }}</td>
                    <td>{{ $meeting->group_id }}</td>
                    <td>{{ $meeting->discussion }}</td>
                    <td>{{ $meeting->attendance_list }}</td>
                    <td class="action-buttons">
                        <button type="button" class="show-btn" data-toggle="modal" data-target="#meetingModal{{ $meeting->id }}">View</button>
                        <a href="{{ route('meetings.edit', $meeting) }}" class="edit-btn">Edit</a>
                        <form action="{{ route('meetings.destroy', $meeting) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="delete-btn">Delete</button>
                        </form>
                    </td>
                </tr>
                <!-- Modal -->
                <div class="modal fade" id="meetingModal{{ $meeting->id }}" tabindex="-1" role="dialog" aria-labelledby="meetingModalLabel{{ $meeting->id }}" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="meetingModalLabel{{ $meeting->id }}">Meeting Details</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <p><strong>Date:</strong> {{ $meeting->date }}</p>
                                <p><strong>Group Name:</strong> {{ $meeting->group_name }}</p>
                                <p><strong>Group ID:</strong> {{ $meeting->group_id }}</p>
                                <p><strong>Discussion Points:</strong> {{ $meeting->discussion }}</p>
                                <p><strong>No. Members Present:</strong> {{ $meeting->attendance_list }}</p>
                                <img src="{{ asset('storage/' . $meeting->photo) }}" alt="Group Photo" style="width: 100%; height: auto;">
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="d-flex justify-content-center">
        {{ $meetings->links('pagination::bootstrap-4') }}
    </div>
</div>
@endsection
