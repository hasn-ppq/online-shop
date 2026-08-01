<x-layouts.app>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-slate-900">{{ $category->exists ? 'Edit Category' : 'Create Category' }}</h2>
    </x-slot>

    <div class="container mx-auto p-6">
    <div class="max-w-2xl bg-white rounded-2xl p-6 shadow">
        <h2 class="text-xl font-semibold text-slate-800 mb-4">{{ $category->exists ? 'Edit Category' : 'Create Category' }}</h2>

        <form method="POST" action="{{ $category->exists ? route('admin.categories.update', $category) : route('admin.categories.store') }}">
            @csrf
            @if($category->exists)
                @method('PUT')
            @endif

            <div class="grid grid-cols-1 gap-4">
                <label class="block">
                    <span class="text-sm text-slate-600">Name</span>
                    <input name="name" value="{{ old('name', $category->name) }}" class="mt-1 block w-full rounded-md border-gray-200 shadow-sm" required>
                </label>

                <label class="block">
                    <span class="text-sm text-slate-600">Slug (optional)</span>
                    <input name="slug" value="{{ old('slug', $category->slug) }}" class="mt-1 block w-full rounded-md border-gray-200 shadow-sm">
                </label>

                <div class="flex items-center justify-end gap-3">
                    <a href="{{ route('admin.categories.index') }}" class="text-sm text-slate-600">Cancel</a>
                    <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-teal-600 px-4 py-2 text-white hover:bg-teal-700">Save Category</button>
                </div>
            </div>
            </div>
        </form>
    </div>
</div>
</x-layouts.app>
