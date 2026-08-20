@extends('layouts.app')

@section('content')
    <x-page-header :title="'Edit Job Position: ' . $jobPosition->name" back />

    <x-validation-errors />

    <div class="flex-grow-1 overflow-auto">
        <div class="qcard">
            <form action="{{ route('job-positions.update', $jobPosition->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row">
                    <x-form.input name="name" label="Name" placeholder="Name" required :value="$jobPosition->name" />
                    <x-form.input name="short_name" label="Short Name" placeholder="Short Name" :value="$jobPosition->short_name" />
                    <x-form.input name="local_name" label="Local Name" placeholder="Local Name" :value="$jobPosition->local_name" />
                    <x-form.select name="job_level_id" label="Job Level" :options="$jobLevels" required :value="$jobPosition->job_level_id" />
                </div>

                <hr>

                <div class="d-flex justify-content-end gap-2">
                    @can('job-position-delete')
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
    <x-delete-modal id="delete" :action="route('job-positions.destroy', $jobPosition->id)" :message="'Do you really want to delete Lookup Value ' . $jobPosition->name . '?'" />
@endsection
