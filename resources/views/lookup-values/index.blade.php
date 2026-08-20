@extends('layouts.app')

@section('content')
    @if ($message = Session::get('success'))
        <x-alert type="success" title="Success" :message="$message" />
    @endif

    <x-page-header title="Lookup Value List">
        <x-slot:actions>
            <form action="{{ route('lookup-values.index') }}" method="GET" enctype="multipart/form-data"
                style="width: 200px;">
                @csrf

                <div class="qform-group">
                    <x-form.input name="search" col="col-12" placeholder="Search..." :value="request('search')" />
                </div>
            </form>
            <div class="qpopup-menu">
                <button class="qbtn qpopup-menu-btn">
                    {{ request()->filled('type') ? \App\Enums\LookupValueType::tryFrom(request('type'))?->label() : 'All' }}
                    <i class="qml-2 fa-solid fa-caret-down"></i>
                </button>
                <ul class="qpopup-menu-container">
                    <li class="qpopup-menu-item">
                        <a href="{{ route('lookup-values.index') }}" class="qpopup-menu-link">All</a>
                    </li>
                    @foreach (\App\Enums\LookupValueType::options() as $type => $label)
                        <li class="qpopup-menu-item">
                            <a href="{{ route('lookup-values.index', ['type' => $type]) }}"
                                class="qpopup-menu-link">{{ $label }}</a>
                        </li>
                    @endforeach
                </ul>
            </div>
            {{-- <button type="button" class="qbtn qbtn-blue" data-toggle="qmodal" data-target="#filterModal">
                <i class="fa-solid fa-filter"></i>
            </button> --}}
            @can('lookup-value-create')
                <a href="{{ route('lookup-values.create') }}" class="qbtn qbtn-blue">
                    Add New
                </a>
            @endcan
        </x-slot:actions>
    </x-page-header>

    <ul class="qlist-view flex-grow-1 overflow-y-auto">
        @foreach ($data as $item)
            <li class="qlist-view-item">
                <a href="{{ auth()->user()->can('lookup-value-edit') ? route('lookup-values.edit', $item->id) : route('lookup-values.show', $item->id) }}"
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
                    </div>
                </a>
            </li>
        @endforeach
    </ul>

    <x-pagination-summary :paginator="$data" />
@endsection

{{-- 
@section('modals')
    <x-filter-modal id="filterModal" :action="route('lookup-values.index')">

        <x-form.input name="search" label="Search" col="col-12" placeholder="Name" :value="request('search')" />

    </x-filter-modal>
@endsection --}}
