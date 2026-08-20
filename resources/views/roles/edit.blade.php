@extends('layouts.app')

@section('content')
    <x-page-header :title="'Edit Role: ' . $role->name" back />

    <x-validation-errors />

    <div class="flex-grow-1 overflow-auto">
        {{-- Form --}}
        <form action="{{ route('roles.update', $role->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="qcard">

                {{-- Role Name --}}
                <div class="row">
                    <x-form.input name="name" label="Name" required :value="$role->name" />
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
                                                <input class="form-check" type="checkbox" name="permission[]"
                                                    value="{{ $value->id }}"
                                                    {{ in_array($value->id, old('permission', $rolePermissions ?? [])) ? 'checked' : '' }}>
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
                <div class="d-flex justify-content-end gap-2">
                    @can('user-delete')
                        <button type="button" class="qbtn qbtn-red" data-toggle="qmodal" data-target="#delete">
                            Delete
                        </button>
                    @endcan
                    <button type="submit" class="qbtn qbtn-blue">
                        Save Change
                    </button>
                </div>

            </div>
        </form>
    </div>
@endsection

{{-- Delete Modal --}}
@section('modals')
    <div class="qpopup" id="delete">

        <div class="qpopup-backdrop"></div>

        <div class="qpopup-content">
            <form action="{{ route('roles.destroy', $role->id) }}" method="POST">
                @csrf
                @method('DELETE')

                <div class="qpopup-header">
                    <h5 class="qpopup-title">
                        Delete
                    </h5>
                    <button type="button" class="qbtn qbtn-icon close">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>

                <div class="qpopup-body">
                    <p>Do you really want to delete role {{ $role->name }}?</p>
                </div>

                <div class="qpopup-footer justify-content-end">
                    <button type="button" class="qbtn close">No</button>
                    <button type="submit" class="qbtn qbtn-blue">Yes</button>
                </div>
            </form>
        </div>
    </div>
@endsection
