@extends('layouts.app')

@section('content')
    <x-page-header title="Create Role" back />

    <x-validation-errors />

    <div class="flex-grow-1 overflow-auto">
        {{-- Form --}}
        <form action="{{ route('roles.store') }}" method="POST">
            @csrf

            <div class="qcard">

                {{-- Role Name --}}
                <div class="row">
                    <x-form.input name="name" label="Name" required />
                    <div class="col-lg-4 col-md-6 col-sm-12 m-0 p-0">
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12 m-0 p-0">
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <div class="form-group">
                            <label>Permissions</label>
                            <ul class="qlist-view multi-selectable qborder qrounded-md"
                                style="max-height: 350px; overflow-y: auto;">

                                @foreach ($permission as $value)
                                    <li class="qlist-view-item">
                                        <a href="#" class="qlist-tile">
                                            <div class="qlist-tile-leading">
                                                <input type="checkbox" name="permission[]" value="{{ $value->id }}"
                                                    class="form-check"
                                                    {{ in_array($value->id, old('permission', [])) ? 'checked' : '' }}>
                                            </div>

                                            <div class="qlist-tile-content">
                                                <div class="qlist-tile-title">
                                                    {{ $value->name }}
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                @endforeach

                            </ul>
                        </div>
                    </div>

                </div>

                <hr>

                {{-- Submit --}}
                <div class="d-flex justify-content-end">
                    <button type="submit" class="qbtn qbtn-blue">
                        Create
                    </button>
                </div>

            </div>
        </form>
    </div>
@endsection
