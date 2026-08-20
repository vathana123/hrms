@extends('layouts.app')

@section('content')
    @if ($message = Session::get('success'))
        <x-alert type="success" title="Success" :message="$message" />
    @endif
    
    <x-page-header title="Role List">
        <x-slot:actions>
        @can('role-create')
            <a href="{{ route('roles.create') }}" class="qbtn qbtn-blue">Add New</a>
        @endcan
        </x-slot:actions>
    </x-page-header>

    <ul class="qlist-view flex-grow-1 overflow-y-auto">
        @foreach ($data as $e)
            <li class="qlist-view-item">
                <a href="{{ auth()->user()->can('role-edit') ? route('roles.edit', $e->id) : route('roles.show', $e->id) }}"
                    class="qlist-tile">
                    <div class="qlist-tile-content">
                        <div class="qlist-tile-title">{{ $e->name }}</div>
                    </div>
                </a>
            </li>
        @endforeach
    </ul>
    <x-pagination-summary :paginator="$data" />
@endsection
