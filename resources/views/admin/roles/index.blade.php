{{-- resources/views/admin/roles/index.blade.php --}}

@extends('layouts.admin')

@section('title', 'Roles & Permissions')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Roles & Permissions</h1>
            <p class="text-sm text-gray-500 mt-1">Manage who can access what in your store</p>
        </div>
        @can('roles.manage')
        <a href="{{ route('admin.roles.create') }}"
           class="inline-flex items-center gap-2 px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-lg transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            New Role
        </a>
        @endcan
    </div>

    {{-- Flash messages --}}
    @if(session('success'))
        <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg text-green-800 text-sm">
            {{ session('success') }}
        </div>
    @endif
    @if($errors->any())
        <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg text-red-800 text-sm">
            {{ $errors->first() }}
        </div>
    @endif

    {{-- Roles grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach($roles as $role)
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5 hover:shadow-md transition">
            <div class="flex items-start justify-between mb-3">
                <div class="flex items-center gap-3">
                    <span class="inline-block w-3 h-3 rounded-full" style="background-color: {{ $role->color }}"></span>
                    <div>
                        <h3 class="font-semibold text-gray-900">{{ $role->display_name }}</h3>
                        <code class="text-xs text-gray-400">{{ $role->name }}</code>
                    </div>
                </div>
                <span class="text-xs font-medium px-2 py-1 rounded-full bg-gray-100 text-gray-600">
                    {{ $role->users_count }} {{ Str::plural('user', $role->users_count) }}
                </span>
            </div>

            @if($role->description)
                <p class="text-sm text-gray-500 mb-3">{{ $role->description }}</p>
            @endif

            {{-- Permission badges --}}
            <div class="flex flex-wrap gap-1 mb-4">
                @foreach($role->permissions->take(6) as $perm)
                    <span class="text-xs px-2 py-0.5 rounded bg-purple-50 text-purple-700">
                        {{ $perm->display_name }}
                    </span>
                @endforeach
                @if($role->permissions->count() > 6)
                    <span class="text-xs px-2 py-0.5 rounded bg-gray-100 text-gray-500">
                        +{{ $role->permissions->count() - 6 }} more
                    </span>
                @endif
                @if($role->permissions->isEmpty())
                    <span class="text-xs text-gray-400 italic">No permissions assigned</span>
                @endif
            </div>

            {{-- Actions --}}
            @can('roles.manage')
            <div class="flex gap-2 pt-3 border-t border-gray-100">
                <a href="{{ route('admin.roles.edit', $role) }}"
                   class="flex-1 text-center text-sm px-3 py-1.5 border border-gray-200 rounded-lg hover:bg-gray-50 text-gray-700 transition">
                    Edit
                </a>
                @if(!in_array($role->name, ['super-admin']))
                <form method="POST" action="{{ route('admin.roles.destroy', $role) }}"
                      onsubmit="return confirm('Delete role \'{{ $role->display_name }}\'? Users will lose this role.')">
                    @csrf @method('DELETE')
                    <button type="submit"
                            class="text-sm px-3 py-1.5 border border-red-200 rounded-lg hover:bg-red-50 text-red-600 transition">
                        Delete
                    </button>
                </form>
                @endif
            </div>
            @endcan
        </div>
        @endforeach
    </div>

</div>
@endsection