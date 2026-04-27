{{-- resources/views/admin/roles/edit.blade.php --}}
{{-- Also used for create — just change the form action & title --}}

@extends('layouts.admin')

@section('title', isset($role) ? 'Edit Role' : 'New Role')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">

    <div class="mb-6">
        <a href="{{ route('admin.roles.index') }}" class="text-sm text-gray-500 hover:text-gray-700">
            ← Back to Roles
        </a>
        <h1 class="text-2xl font-bold text-gray-900 mt-2">
            {{ isset($role) ? "Edit Role: {$role->display_name}" : 'Create New Role' }}
        </h1>
    </div>

    <form method="POST"
          action="{{ isset($role) ? route('admin.roles.update', $role) : route('admin.roles.store') }}"
          class="space-y-6">
        @csrf
        @isset($role) @method('PUT') @endisset

        {{-- Role basics --}}
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <h2 class="text-base font-semibold text-gray-900 mb-4">Role Details</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Role Slug <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name"
                           value="{{ old('name', $role->name ?? '') }}"
                           placeholder="e.g. store-manager"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('name') border-red-400 @enderror"
                           {{ isset($role) && $role->name === 'super-admin' ? 'readonly' : '' }}>
                    <p class="text-xs text-gray-400 mt-1">Lowercase letters, numbers, hyphens only</p>
                    @error('name')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Display Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="display_name"
                           value="{{ old('display_name', $role->display_name ?? '') }}"
                           placeholder="e.g. Store Manager"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('display_name') border-red-400 @enderror">
                    @error('display_name')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <input type="text" name="description"
                           value="{{ old('description', $role->description ?? '') }}"
                           placeholder="What can this role do?"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Badge Color</label>
                    <div class="flex items-center gap-3">
                        <input type="color" name="color"
                               value="{{ old('color', $role->color ?? '#6366f1') }}"
                               class="h-9 w-16 rounded border border-gray-300 cursor-pointer">
                        <span class="text-xs text-gray-400">Shown in role badges</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Permissions --}}
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-base font-semibold text-gray-900">Permissions</h2>
                <div class="flex gap-2">
                    <button type="button" onclick="toggleAll(true)"
                            class="text-xs px-2 py-1 border border-gray-200 rounded hover:bg-gray-50 text-gray-600">
                        Select All
                    </button>
                    <button type="button" onclick="toggleAll(false)"
                            class="text-xs px-2 py-1 border border-gray-200 rounded hover:bg-gray-50 text-gray-600">
                        Clear All
                    </button>
                </div>
            </div>

            @if(isset($role) && $role->name === 'super-admin')
                <div class="p-3 bg-purple-50 border border-purple-200 rounded-lg text-sm text-purple-700">
                    Super Admin automatically has all permissions and bypasses all permission checks.
                </div>
            @else
                <div class="space-y-5">
                    @foreach($permissions as $group => $groupPermissions)
                    <div class="permission-group">
                        <div class="flex items-center gap-2 mb-2">
                            <button type="button"
                                    onclick="toggleGroup('{{ Str::slug($group) }}')"
                                    class="text-xs font-semibold text-purple-600 hover:text-purple-800">
                                {{ $group }}
                            </button>
                            <div class="flex-1 h-px bg-gray-200"></div>
                        </div>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-2 group-{{ Str::slug($group) }}">
                            @foreach($groupPermissions as $permission)
                            <label class="flex items-center gap-2 p-2 rounded-lg hover:bg-gray-50 cursor-pointer">
                                <input type="checkbox"
                                       name="permissions[]"
                                       value="{{ $permission['name'] }}"
                                       class="rounded border-gray-300 text-purple-600 focus:ring-purple-500 perm-checkbox"
                                       {{ in_array($permission['name'], old('permissions', $rolePermissions ?? [])) ? 'checked' : '' }}>
                                <span class="text-sm text-gray-700">{{ $permission['display_name'] }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>

        <div class="flex gap-3 justify-end">
            <a href="{{ route('admin.roles.index') }}"
               class="px-4 py-2 border border-gray-300 rounded-lg text-sm text-gray-700 hover:bg-gray-50 transition">
                Cancel
            </a>
            <button type="submit"
                    class="px-5 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-lg transition">
                {{ isset($role) ? 'Update Role' : 'Create Role' }}
            </button>
        </div>
    </form>
</div>

<script>
function toggleAll(checked) {
    document.querySelectorAll('.perm-checkbox').forEach(cb => cb.checked = checked);
}
function toggleGroup(slug) {
    const checkboxes = document.querySelectorAll(`.group-${slug} .perm-checkbox`);
    const allChecked = [...checkboxes].every(cb => cb.checked);
    checkboxes.forEach(cb => cb.checked = !allChecked);
}
</script>
@endsection