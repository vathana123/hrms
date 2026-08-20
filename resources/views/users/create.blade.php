@extends('layouts.app')

@section('content')
    <x-page-header title="Create User" back />

    <x-validation-errors />

    <div class="flex-grow-1 overflow-auto">
        <div class="qcard">
            <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row">
                    <x-form.input name="emp_id" label="Employee ID" placeholder="XXXXXX" maxlength="6" required
                        pattern="\d*" inputmode="numeric" />
                </div>

                <hr>

                <div class="row">
                    <div class="col-6">
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
                                                class="form-check" {{ in_array($role, old('roles', [])) ? 'checked' : '' }}>
                                        </div>
                                        <div class="qlist-tile-content">
                                            <div class="qlist-tile-title">{{ $role }}</div>
                                        </div>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                        @error('roles')
                            <span class="text-danger small">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                </div>

                <hr>

                <div class="d-flex justify-content-end">
                    <button type="submit" class="qbtn qbtn-blue">
                        Create
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script type="text/javascript">
        initUserCreateForm();
    </script>
@endsection
