<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $reports = [];
        if (\auth()->user()->position === 'keeper') {
            $reports = \auth()->user()->reports;
        }
        return response()->view('backend.reports.index', [
            'reports' => $reports,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $group = \auth()->user()->group;
        $center = $group->center;
        $branch = $center->branch;
        $supervisor = $branch->supervisor;
        $students = $group->students;

        /*
         * Here we'll get the keeps if we need to submit a keeping report, and test if testing report
         * */

        //
        return response()->view('backend.reports.store', [
            'group' => $group,
            'center' => $center,
            'branch' => $branch,
            'supervisor' => $supervisor,
            'students' => $students,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Report $report)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Report $report)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Report $report)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Report $report)
    {
        //
    }
}
