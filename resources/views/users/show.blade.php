@extends('layouts.app')

@section('content')
    <x-page-header title="Show User" back />

    <div class="flex-grow-1 overflow-auto">
        <div class="qcard">
            <div class="row">
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
                    <div class="form-group">
                        <img class="border"
                            src="{{ $user->avatar_url ?? asset('dashboard/assets/images/default_avatar.png') }}"
                            alt="Avatar" style="width: 150px; height: 150px;">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
                    <div class="form-group">
                        <strong>Employee ID:</strong>
                        {{ $user->emp_id }}
                    </div>
                </div>
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
                    <div class="form-group">
                        <strong>Name:</strong>
                        {{ $user->name }}
                    </div>
                </div>
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
                    <div class="form-group">
                        <strong>Email:</strong>
                        {{ $user->email }}
                    </div>
                </div>
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
                    <div class="form-group">
                        <strong>Branch:</strong>
                        {{ $user->branch->name ?? 'N/A' }}
                    </div>
                </div>
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
                    <div class="form-group">
                        <strong>Job Position:</strong>
                        {{ $user->jobPosition->name ?? 'N/A' }}
                    </div>
                </div>
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
                    <div class="form-group">
                        <strong>Join Date:</strong>
                        {{ $user->join_date }}
                    </div>
                </div>
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
                    <div class="form-group">
                        <strong>End Date:</strong>
                        {{ $user->end_date ?? 'N/A' }}
                    </div>
                </div>
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
                    <div class="form-group">
                        <strong>Favorite Color:</strong>
                        <div
                            style="width: 30px; height: 30px; background-color: {{ $user->color }}; border: 1px solid #000;">
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
                    <div class="form-group">
                        <strong>Roles:</strong>
                        @if (!empty($user->getRoleNames()))
                            @foreach ($user->getRoleNames() as $role)
                                <p class="badge badge-success">{{ $role }}</p>
                            @endforeach
                        @endif
                    </div>
                </div>
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
