<?php

namespace App\Http\Controllers;

use App\Enums\LookupValueType;
use App\Models\JobPosition;
use App\Models\LookupValue;
use Illuminate\Http\Request;

class JobPositionController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:job-position-list', ['only' => ['index', 'show']]);
        $this->middleware('permission:job-position-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:job-position-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:job-position-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = JobPosition::query()->with('jobLevel');
        $jobLevels = LookupValue::where('type', LookupValueType::JOB_LEVEL)->get();

        if (request()->filled('job_level_id')) {
            $jobLevelId = request()->input('job_level_id');
            $query->where('job_level_id', $jobLevelId);
        }

        if (request()->filled('search')) {
            $search = request()->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%");
            });
        }

        $data = $query->latest()->paginate(15)->appends(request()->all());

        return view('job-positions.index', compact('data', 'jobLevels'))
            ->with('i', (request()->input('page', 1) - 1) * 15);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $jobLevels = LookupValue::where('type', LookupValueType::JOB_LEVEL)->get();
        return view('job-positions.create', compact('jobLevels'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        JobPosition::create($request->all());

        return redirect()->route('job-positions.index')
            ->with('success', 'Job Position created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(JobPosition $jobPosition)
    {
        return view('job-positions.show', compact('jobPosition'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(JobPosition $jobPosition)
    {
        $jobLevels = LookupValue::where('type', LookupValueType::JOB_LEVEL)->get();
        return view('job-positions.edit', compact('jobPosition', 'jobLevels'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, JobPosition $jobPosition)
    {
        $jobPosition->update($request->all());

        return redirect()->route('job-positions.index')
            ->with('success', 'Job Position updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(JobPosition $jobPosition)
    {
        $jobPosition->delete();

        return redirect()->route('job-positions.index')
            ->with('success', 'Job Position deleted successfully.');
    }
}
