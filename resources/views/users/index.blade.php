@extends('layouts.app')

@section('content')
    @if ($message = Session::get('success'))
        <x-alert type="success" title="Success" :message="$message" />
    @endif

    <x-page-header title="User List">
        <x-slot:actions>
            <button type="button" class="qbtn qbtn-blue" data-toggle="qmodal" data-target="#filterModal">
                <i class="fa-solid fa-filter"></i>
            </button>

            @can('user-create')
                <a href="{{ route('users.create') }}" class="qbtn qbtn-blue">
                    Add New
                </a>
            @endcan
        </x-slot:actions>
    </x-page-header>

    <ul class="qlist-view flex-grow-1 overflow-y-auto">
        @foreach ($data as $user)
            <li class="qlist-view-item">
                <a href="{{ auth()->user()->can('user-edit') ? route('users.edit', $user->id) : route('users.show', $user->id) }}"
                    class="qlist-tile">

                    <div class="qlist-tile-content">
                        <div>
                            <div class="qlist-tile-title">
                                {{ $user->username }}
                            </div>
                        </div>
                    </div>
                </a>
            </li>
        @endforeach
    </ul>

    <x-pagination-summary :paginator="$data" />
@endsection

@section('modals')
    <x-filter-modal id="filterModal" :action="route('users.index')">

        <x-form.input name="search" label="Search" col="col-12" placeholder="Username" :value="request('search')" />

    </x-filter-modal>
@endsection
