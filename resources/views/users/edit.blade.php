@extends('layouts.app')

@section('content')
    <x-page-header :title="'Edit User: ' . $user->name" back />

    <x-validation-errors />

    <div class="flex-grow-1 overflow-auto">
        <div class="qcard">
            <form action="{{ route('users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row">
                    <x-form.input name="emp_id" label="Employee ID" placeholder="XXXXXX" maxlength="6" required
                        :value="$user->emp_id" />
                </div>

                <hr>

                <div class="row">
                    <div class="col-12">
                        <label class="fw-semibold mb-2">
                            Roles
                        </label>

                        <ul class="qlist-view multi-selectable qborder qrounded-md"
                            style="max-height: 350px; overflow-y: auto;">
                            @foreach ($roles as $role)
                                <li class="qlist-view-item">
                                    <a href="#" class="qlist-tile">
                                        <div class="qlist-tile-leading">
                                            <input type="checkbox" name="roles[]" value="{{ $role }}"
                                                class="form-check" {{ in_array($role, old('roles', $userRole ?? [])) ? 'checked' : '' }}>
                                        </div>
                                        <div class="qlist-tile-content">
                                            <div class="qlist-tile-title">{{ $role }}</div>
                                        </div>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
{{--
                        <div class="qlist-view multi-selectable qborder qrounded-md"
                            style="max-height: 350px; overflow: auto;">
                            @foreach ($roles as $role)
                                <label class="qlist-view-item">
                                    <div class="qlist-tile">
                                        <div class="qlist-tile-leading">
                                            <input type="checkbox" name="roles[]" value="{{ $role }}"
                                                class="form-check"
                                                {{ in_array($role, old('roles', $userRole ?? [])) ? 'checked' : '' }}>
                                        </div>

                                        <div class="qlist-tile-content">
                                            <div class="qlist-tile-title">
                                                {{ $role }}
                                            </div>
                                        </div>
                                    </div>
                                </label>
                            @endforeach
                        </div> --}}

                        @error('roles')
                            <span class="text-danger small">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                </div>

                <hr>

                <div class="d-flex justify-content-end gap-2">
                    @can('user-delete')
                        <button type="button" class="qbtn qbtn-red" data-toggle="qmodal" data-target="#delete">
                            Delete
                        </button>
                    @endcan

                    @can('user-reset-password')
                        <button type="button" class="qbtn qbtn-yellow" data-toggle="qmodal" data-target="#reset_password">
                            Reset Password
                        </button>
                    @endcan

                    <button type="submit" class="qbtn qbtn-blue">
                        Save Change
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script type="text/javascript">
        initImagePreview();
    </script>
@endsection

@section('modals')
    <x-delete-modal id="delete" :action="route('users.destroy', $user->id)" :message="'Do you really want to delete User ' . $user->name . '?'" />

    <x-confirm-modal id="reset_password" title="Reset Password" :action="route('password.update')" method="POST" :message="'Do you really want to reset ' . $user->name . '\'s password?'"
        submitText="Yes">
        <input type="hidden" name="user_id" value="{{ $user->id }}">
    </x-confirm-modal>
@endsection
