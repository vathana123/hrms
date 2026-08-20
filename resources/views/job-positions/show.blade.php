@extends('layouts.app')

@section('content')
    <x-page-header title="Show User" back />

    <div class="flex-grow-1 overflow-auto">
        <div class="qcard">
            <div class="row">
            </div>
        </div>
    </div>
@endsection

@section('modals')
    <x-delete-modal id="delete" :action="route('users.destroy', $user->id)" :title="'Delete ' . $user->name"
        message="Do you really want to delete this record?" />

    <x-confirm-modal id="reset_password" title="Reset Password" :action="route('password.update')" :message="'Do you really want to reset ' . $user->name . '\'s password?'" submit-text="Yes">
        <input type="hidden" name="user_id" value="{{ $user->id }}">
    </x-confirm-modal>
@endsection
