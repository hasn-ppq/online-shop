@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-semibold text-slate-800">Manage Categories</h1>
        <a href="{{ route('admin.categories.create') }}" class="inline-flex items-center gap-2 rounded-lg bg-teal-600 px-4 py-2 text-white hover:bg-teal-700">+ Add Category</a>
    </div>

    <div class="bg-white rounded-lg p-4 shadow">
        <table class="w-full table-auto">
            <thead>
                <tr class="text-left text-slate-600">
                    <th class="py-2">Name</th>
                    <th class="py-2">Slug</th>
                    <th class="py-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($categories as $category)
                <tr class="border-t">
                    <td class="py-2">{{ $category->name }}</td>
                    <td class="py-2">{{ $category->slug }}</td>
                    <td class="py-2">
                        <a href="{{ route('admin.categories.edit', $category) }}" class="text-indigo-600 hover:underline">Edit</a>
                        <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" style="display:inline-block;" onsubmit="return confirm('Delete this category?');">
                            @csrf
                            @method('DELETE')
                            <button class="ml-3 text-red-600 hover:underline">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-4">
            {{ $categories->links() }}
        </div>
    </div>
</div>
@endsection
