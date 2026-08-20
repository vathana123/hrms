@extends('layouts.app')

@section('content')
    <div class="d-flex align-items-center justify-content-between">
        <div class="d-flex gap-2 align-items-center">
            <a href="{{ url()->previous() }}" class="qbtn qbtn-blue">
                <i class="fa-solid fa-chevron-left me-2"></i> <span>Back</span>
            </a>
            <h4>
                Role: {{ $role->name }}
            </h4>
        </div>
    </div>
    <hr>
    <div class="flex-grow-1 overflow-auto">
    <div class="qcard">
        <ul>
            <li><strong>Name</strong>: {{ $role->name }}</li>
        </ul>
    </div>
@endsection

@section('modals')
    <x-delete-modal id="delete" :action="route('roles.destroy', $role->id)" :message="'Do you really want to delete Role ' . $role->name . '?'" />
@endsection
