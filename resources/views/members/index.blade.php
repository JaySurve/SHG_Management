@extends('adminlte::page')

@section('title', 'Member List')

@section('content_header')
    <h1>Member List</h1>
    <div class="flex justify-end">
        <form action="{{ route('members.index') }}" method="GET" class="flex space-x-2">
            <input type="text" name="search" placeholder="Search members..." class="py-2 px-4 rounded-lg border border-gray-300" value="{{ request('search') }}">
            <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded-lg hover:bg-blue-700">Search</button>
        </form>
    </div>
@stop

@section('content')
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

<div class="container mx-auto p-4">
    <div class="flex justify-start items-center mb-4">
        <a href="{{ route('members.create') }}" class="bg-green-500 text-white py-2 px-4 rounded-lg hover:bg-green-700"><i class="fa fa-plus"></i> Add Member</a>
        <form action="{{ route('members.index') }}" method="GET" class="ml-4">
            <label for="view" class="mr-2">View:</label>
            <select name="view" onchange="this.form.submit()" class="py-2 px-4 rounded-lg border border-gray-300">
                <option value="cards" {{ request('view') == 'cards' ? 'selected' : '' }}>Cards</option>
                <option value="table" {{ request('view') == 'table' ? 'selected' : '' }}>Table</option>
            </select>
        </form>
        <form action="{{ route('members.index') }}" method="GET" class="ml-4">
            <label for="sort" class="mr-2">Sort by Status:</label>
            <select name="sort" onchange="this.form.submit()" class="py-2 px-4 rounded-lg border border-gray-300">
                <option value="">All</option>
                <option value="active" {{ request('sort') == 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ request('sort') == 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
        </form>
    </div>
    @if(request('view') == 'table')
        <table class="min-w-full bg-white">
            <thead>
                <tr>
                    <th class="py-2">Photo</th>
                    <th class="py-2">Name</th>
                    <th class="py-2">Member UID</th>
                    <th class="py-2">Status</th>
                    <th class="py-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($members as $member)
                    <tr class="bg-gray-100 border-b">
                        <td class="py-2"><img src="{{ asset('storage/' . $member->photo) }}" class="w-10 h-10 object-cover rounded-full mx-auto"></td>
                        <td class="py-2">{{ $member->name }}</td>
                        <td class="py-2">{{ $member->member_id }}</td>
                        <td class="py-2">{{ $member->status }}</td>
                        <td class="py-2">
                            <div class="flex justify-center space-x-2">
                                <button onclick="showMemberDetails({{ $member->id }})" class="bg-blue-500 text-white py-1 px-2 rounded hover:bg-blue-700">View</button>
                                <a href="{{ route('members.edit', $member->id) }}" class="bg-blue-600 text-white py-1 px-2 rounded hover:bg-blue-800">Edit</a>
                                <form action="{{ route('members.destroy', $member->id) }}" method="POST" class="inline" onsubmit="return confirmDelete(event, this)">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-500 text-white py-1 px-2 rounded hover:bg-red-700">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-8">
            @foreach ($members as $member)
                <div class="bg-blue-100 p-4 rounded-lg border border-gray-800 shadow-md hover:bg-gradient-to-b from-blue-100 to-teal-500 transform hover:scale-105 transition duration-150">
                    <div class="text-center">
                        <img src="{{ asset('storage/' . $member->photo) }}" class="w-20 h-20 object-cover rounded-full mx-auto mb-4">
                        <h3 class="text-l font-bold mb-2">{{ $member->name }}</h3>
                        <p class="text-gray-600 mb-2"><strong>Member UID:</strong> {{ $member->member_id }}</p>
                        <p class="text-gray-600 mb-2"><strong>Status:</strong> {{ $member->status }}</p>
                        <div class="flex justify-center space-x-2 mt-4">
                            <button onclick="showMemberDetails({{ $member->id }})" class="bg-blue-500 text-white py-1 px-2 rounded hover:bg-blue-700">View</button>
                            <a href="{{ route('members.edit', $member->id) }}" class="bg-blue-600 text-white py-1 px-2 rounded hover:bg-blue-800">Edit</a>
                            <form action="{{ route('members.destroy', $member->id) }}" method="POST" class="inline" onsubmit="return confirmDelete(event, this)">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 text-white py-1 px-2 rounded hover:bg-red-700">Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
    <div class="mt-4">
        {{ $members->links('pagination::bootstrap-4') }}
    </div>
</div>

<!-- Modal -->
<div id="memberModal" class="fixed inset-0 flex items-center justify-center bg-blue-200 bg-opacity-50 hidden">
    <div class="bg-pink-100 p-6 rounded-lg shadow-lg w-1/4 max-w-2xl relative">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold text-black">Member Details</h2>
        </div>
        <div id="modalContent" class="space-y-4">
            <!-- Member details will be loaded here -->
        </div>
        <div class="flex justify-end mt-4">
            <button onclick="closeModal()" class="bg-blue-500 text-white py-2 px-4 rounded-lg hover:bg-blue-700">Close</button>
        </div>
    </div>
</div>

<script>
    let formToSubmit;

    function confirmDelete(event, form) {
        event.preventDefault();
        formToSubmit = form;
        const confirmationBox = document.createElement('div');
        confirmationBox.classList.add('fixed', 'inset-0', 'flex', 'items-center', 'justify-center', 'bg-black', 'bg-opacity-50');
        confirmationBox.innerHTML = `
            <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-sm">
                <h2 class="text-xl font-bold mb-4">Confirm Deletion</h2>
                <p class="mb-4">Are you sure you want to delete this member?</p>
                <div class="flex justify-end space-x-4">
                    <button onclick="closeConfirmationBox()" class="bg-gray-500 text-white py-2 px-4 rounded-lg hover:bg-gray-700">No</button>
                    <button onclick="submitDeleteForm()" class="bg-red-500 text-white py-2 px-4 rounded-lg hover:bg-red-700">Yes</button>
                </div>
            </div>
        `;
        confirmationBox.id = 'confirmationBox';
        document.body.appendChild(confirmationBox);
    }

    function closeConfirmationBox() {
        const confirmationBox = document.getElementById('confirmationBox');
        if (confirmationBox) {
            confirmationBox.remove();
        }
    }

    function submitDeleteForm() {
        closeConfirmationBox();
        formToSubmit.submit();
    }

    function showMemberDetails(memberId) {
        fetch(`/members/${memberId}`)
            .then(response => response.json())
            .then(data => {
                const modalContent = `
                    <div class="text-center">
                        <img src="${data.photo ? '{{ asset('storage/') }}' + '/' + data.photo : ''}" class="w-32 h-32 object-cover rounded-full mx-auto mb-4">
                    </div>
                    <table class="table-auto w-full border-2 border-black">
                        <tbody>
                            <tr class="border border-black text-black">
                                <td class="font-bold border-2 border-black">Name:</td>
                                <td class="border-2 border-black">${data.name}</td>
                            </tr>
                            <tr class="border border-black text-black">
                                <td class="font-bold border-2 border-black">ID:</td>
                                <td class="border-2 border-black">${data.member_id}</td>
                            </tr>
                            <tr class="border border-black text-black">
                                <td class="font-bold border-2 border-black">Number:</td>
                                <td class="border-2 border-black">${data.number}</td>
                            </tr>
                            <tr class="border border-black text-black">
                                <td class="font-bold border-2 border-black">Village:</td>
                                <td class="border-2 border-black">${data.village}</td>
                            </tr>
                            <tr class="border border-black text-black">
                                <td class="font-bold border-2 border-black">Group:</td>
                                <td class="border-2 border-black">${data.group}</td>
                            </tr>
                            <tr class="border border-black text-black">
                                <td class="font-bold borde-2 border-black">Caste:</td>
                                <td class="border-2 border-black">${data.caste}</td>
                            </tr>
                            <tr class="border border-black text-black">
                                <td class="font-bold border-2 border-black">Share Price:</td>
                                <td class="border-2 border-black">${data.share_price}</td>
                            </tr>
                            <tr class="border border-black text-black">
                                <td class="font-bold border-2 border-black">Member Type:</td>
                                <td class="border-2 border-black">${data.member_type}</td>
                            </tr>
                            <tr class="border border-black text-black">
                                <td class="font-bold border-2 border-black">Status:</td>
                                <td class="border-2 border-black">${data.status}</td>
                            </tr>
                        </tbody>
                    </table>
                `;
                document.getElementById('modalContent').innerHTML = modalContent;
                document.getElementById('memberModal').classList.remove('hidden');
            });
    }

    function closeModal() {
        document.getElementById('memberModal').classList.add('hidden');
    }
</script>
@stop