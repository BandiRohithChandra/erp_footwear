@extends('layouts.app')

@section('content')
<div class="container mx-auto">
    <h1 class="text-2xl font-bold mb-4">Workers</h1>

    <a href="{{ route('workers.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded">Add Worker</a>

    <table class="table-auto w-full mt-4 border">
        <thead>
            <tr>
                <th class="border px-4 py-2">ID</th>
                <th class="border px-4 py-2">Name</th>
                <th class="border px-4 py-2">Email</th>
                <th class="border px-4 py-2">Phone</th>
                <th class="border px-4 py-2">Role</th>
                <th class="border px-4 py-2">Status</th>
                <th class="border px-4 py-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($workers as $worker)
            <tr>
                <td class="border px-4 py-2">{{ $worker->id }}</td>
                <td class="border px-4 py-2">{{ $worker->name }}</td>
                <td class="border px-4 py-2">{{ $worker->email }}</td>
                <td class="border px-4 py-2">{{ $worker->phone }}</td>
                <td class="border px-4 py-2">{{ $worker->role }}</td>
                <td class="border px-4 py-2">{{ $worker->status }}</td>
                <td class="border px-4 py-2">
                    <a href="{{ route('workers.edit', $worker) }}" class="text-blue-500">Edit</a> |
                    <form action="{{ route('workers.destroy', $worker) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-500">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
