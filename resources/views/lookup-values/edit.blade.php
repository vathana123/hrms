@extends('layouts.app')

@section('content')
    <x-page-header :title="'Edit Lookup Value: ' . $lookupValue->name" back />

    <x-validation-errors />

    <div class="flex-grow-1 overflow-auto">
        <div class="qcard">
            <form action="{{ route('lookup-values.update', $lookupValue->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row">
                    <x-form.input name="name" label="Name" placeholder="Name" required :value="$lookupValue->name" />
                    <x-form.input name="short_name" label="Short Name" placeholder="Short Name" :value="$lookupValue->short_name" />
                    <x-form.input name="local_name" label="Local Name" placeholder="Local Name" :value="$lookupValue->local_name" />
                    <x-form.select name="type" label="Type" :options="\App\Enums\LookupValueType::options()" required :value="$lookupValue->type->value" />
                    <x-form.input name="display_ordering" label="Order" type="number" :value="$lookupValue->display_ordering" />
                </div>

                <hr>

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
            </form>
        </div>
    </div>
@endsection

@section('modals')
    <x-delete-modal id="delete" :action="route('lookup-values.destroy', $lookupValue->id)" :message="'Do you really want to delete Lookup Value ' . $lookupValue->name . '?'" />
@endsection
