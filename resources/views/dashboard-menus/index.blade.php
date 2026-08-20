@extends('layouts.app')

@section('content')
    @if ($message = Session::get('success'))
        <x-alert type="success" title="Success" :message="$message" />
    @endif

    <x-page-header title="Dashboard Menu List">
        <x-slot:actions>
            <button type="button" class="qbtn qbtn-blue" data-toggle="qmodal" data-target="#filterModal">
                <i class="fa-solid fa-filter"></i>
            </button>

            @can('dashboard-menu-create')
                <a href="{{ route('dashboard-menus.create') }}" class="qbtn qbtn-blue">
                    Add New
                </a>
            @endcan
        </x-slot:actions>
    </x-page-header>

    <ul class="qlist-view flex-grow-1 overflow-y-auto">
        @foreach ($data as $item)
            <li class="qlist-view-item">
                <a href="{{ auth()->user()->can('dashboard-menu-edit') ? route('dashboard-menus.edit', $item->id) : route('dashboard-menus.show', $item->id) }}"
                    class="qlist-tile">
                    <div class="qlist-tile-content">
                        <div>
                            <div class="qlist-tile-title">
                                {{ $item->name }}
                            </div>
                            <div class="qlist-tile-subtitle">
                                {{ $item->type }}
                            </div>
                        </div>
                        <div class="qlist-tile-trailing">
                            Display Ordering: {{ $item->display_ordering }}
                        </div>
                    </div>
                </a>
            </li>
        @endforeach
    </ul>

    <x-pagination-summary :paginator="$data" />
@endsection

@section('modals')
    <x-filter-modal id="filterModal" :action="route('dashboard-menus.index')">

        <x-form.input name="search" label="Search" col="col-12" placeholder="Name" :value="request('search')" />

    </x-filter-modal>
@endsection
