<?php

namespace App\Http\Controllers;

use App\Models\LookupValue;
use Illuminate\Http\Request;

class LookupValueController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:lookup-value-list', ['only' => ['index', 'show']]);
        $this->middleware('permission:lookup-value-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:lookup-value-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:lookup-value-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = LookupValue::query();

        if (request()->filled('type')) {
            $type = request()->input('type');
            $query->where('type', $type);
        }

        if (request()->filled('search')) {
            $search = request()->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%");
            });
        }

        $data = $query->latest()->paginate(15)->appends(request()->all());

        return view('lookup-values.index', compact('data'))
            ->with('i', (request()->input('page', 1) - 1) * 15);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('lookup-values.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        LookupValue::create($request->all());

        return redirect()->route('lookup-values.index')
            ->with('success', 'User created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(LookupValue $lookupValue)
    {
        return view('lookup-values.show', compact('lookupValue'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(LookupValue $lookupValue)
    {
        return view('lookup-values.edit', compact('lookupValue'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, LookupValue $lookupValue)
    {
        $lookupValue->update($request->all());

        return redirect()->route('lookup-values.index')
            ->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LookupValue $lookupValue)
    {
        $lookupValue->delete();

        return redirect()->route('lookup-values.index')
            ->with('success', 'User deleted successfully.');
    }
}
