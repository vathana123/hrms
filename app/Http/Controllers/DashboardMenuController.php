<?php

namespace App\Http\Controllers;

use App\Models\DashboardMenu;
use Illuminate\Http\Request;

class DashboardMenuController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:dashboard-menu-list', ['only' => ['index', 'show']]);
        $this->middleware('permission:dashboard-menu-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:dashboard-menu-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:dashboard-menu-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = DashboardMenu::query();

        if (request()->filled('search')) {
            $search = request()->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%");
            });
        }

        $data = $query->latest()->paginate(15)->appends(request()->all());

        return view('dashboard-menus.index', compact('data'))
            ->with('i', (request()->input('page', 1) - 1) * 15);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard-menus.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        DashboardMenu::create($request->all());

        return redirect()->route('dashboard-menus.index')
            ->with('success', 'Dashboard Menu created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(DashboardMenu $dashboardMenu)
    {
        return view('dashboard-menus.show', compact('dashboardMenu'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DashboardMenu $dashboardMenu)
    {
        return view('dashboard-menus.edit', compact('dashboardMenu'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DashboardMenu $dashboardMenu)
    {
        $dashboardMenu->update($request->all());
        return redirect()->route('dashboard-menus.index')
            ->with('success', 'Dashboard Menu updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DashboardMenu $dashboardMenu)
    {
        $dashboardMenu->delete();
        return redirect()->route('dashboard-menus.index')
            ->with('success', 'Dashboard Menu deleted successfully.');
    }
}
