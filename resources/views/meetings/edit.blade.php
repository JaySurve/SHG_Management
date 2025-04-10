@extends('layouts.app')

@section('content')
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Meeting</title>
    <link rel="stylesheet" href="{{ asset('css/Create.css') }}">
    <script src="{{ asset('js/Meeting.js') }}" defer></script>
</head>
<body>
<br>
    <div class="pull-right">
        <a class="btn btn-primary btn-sm mb-2" href="{{ route('meetings.index') }}"><i class="fa fa-arrow-left"></i> Back</a>
    </div>
    <div class="container">
        <form action="{{ route('meetings.update', $meeting->id) }}" method="post" enctype="multipart/form-data" onsubmit="return validateForm()">
            @csrf
            @method('PUT')

            <h1>Edit Meeting</h1>

            <label for="date">Date:</label>
            <input type="date" id="date" name="date" value="{{ old('date', $meeting->date) }}" required><br><br>

            <label for="group_uid">Group:</label>
            <div style="position: relative;">
                <input type="text" id="group_search" placeholder="Search or Select Group..." onfocus="showDropdown()" onkeyup="filterGroups()" value="{{ $groups->firstWhere('id', $meeting->group_uid)->name ?? '' }}" style="width: 100%; margin-bottom: 5px;">
                <div id="group_dropdown" style="position: absolute; width: 100%; max-height: 150px; overflow-y: auto; border: 1px solid #ccc; background: #fff; display: none; z-index: 1000;">
                    @foreach($groups as $group)
                        <div class="dropdown-item" onclick="selectGroup('{{ $group->id }}', '{{ $group->name }}')" style="padding: 5px; cursor: pointer;">
                            {{ $group->name }}
                        </div>
                    @endforeach
                </div>
                <input type="hidden" id="group_uid" name="group_uid" value="{{ $meeting->group_uid }}" required>
            </div>
            <br><br>

            <label for="attendance_list">Attendance List:</label>
            <div id="attendance_list">
                <div id="members_list">
                    <!-- Members will be populated here based on the selected group -->
                </div>
            </div>
            <script>
                async function fetchMembers(groupId) {
                    const membersList = document.getElementById('members_list');
                    membersList.innerHTML = 'Loading members...';

                    try {
                        const response = await fetch(`/api/groups/${groupId}/members`);
                        if (!response.ok) {
                            const errorText = await response.text();
                            throw new Error(`Failed to fetch members: ${response.status} ${response.statusText} - ${errorText}`);
                        }

                        const members = await response.json();
                        if (!Array.isArray(members)) {
                            throw new Error('Invalid response format: Expected an array of members');
                        }

                        membersList.innerHTML = members.map(member => `
                            <div>
                                <input type="checkbox" name="attendance[]" value="${member.id}">
                                ${member.name}
                            </div>
                        `).join('');
                    } catch (error) {
                        membersList.innerHTML = 'Error loading members. Please try again later.';
                        console.error('Error fetching members:', error);
                    }
                }

                function selectGroup(id, name) {
                    document.getElementById('group_search').value = name;
                    document.getElementById('group_uid').value = id;
                    document.getElementById('group_dropdown').style.display = 'none';
                    fetchMembers(id); // Fetch members when a group is selected
                }
            </script>

            <label for="discussion">Discussion Points:</label>
            <textarea id="discussion" name="discussion" required style="height: 100%; width: 100%;">{{ old('discussion', $meeting->discussion) }}</textarea><br>

           
            <input type="submit" value="Update Meeting">
            
        </form>
    </div>
    <script>
        function filterGroups() {
            const searchInput = document.getElementById('group_search').value.toLowerCase();
            const dropdown = document.getElementById('group_dropdown');
            const items = dropdown.getElementsByClassName('dropdown-item');
            let hasVisibleItems = false;

            for (let i = 0; i < items.length; i++) {
                const itemText = items[i].textContent.toLowerCase();
                if (itemText.includes(searchInput)) {
                    items[i].style.display = '';
                    hasVisibleItems = true;
                } else {
                    items[i].style.display = 'none';
                }
            }

            dropdown.style.display = hasVisibleItems ? 'block' : 'none';
        }

        function showDropdown() {
            const dropdown = document.getElementById('group_dropdown');
            dropdown.style.display = 'block';
        }

        document.addEventListener('click', function(event) {
            const dropdown = document.getElementById('group_dropdown');
            if (!dropdown.contains(event.target) && event.target.id !== 'group_search') {
                dropdown.style.display = 'none';
            }
        });
    </script>
</body>
@endsection
