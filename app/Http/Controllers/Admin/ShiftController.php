<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyShiftRequest;
use App\Http\Requests\StoreShiftRequest;
use App\Http\Requests\UpdateShiftRequest;
use App\Models\Shift;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ShiftController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('shift_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $shifts = Shift::all();

        return view('admin.shifts.index', compact('shifts'));
    }

    public function create()
    {
        abort_if(Gate::denies('shift_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.shifts.create');
    }

    public function store(StoreShiftRequest $request)
    {
        $shift = Shift::create($request->all());

        return redirect()->route('admin.shifts.index');
    }

    public function edit(Shift $shift)
    {
        abort_if(Gate::denies('shift_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.shifts.edit', compact('shift'));
    }

    public function update(UpdateShiftRequest $request, Shift $shift)
    {
        $shift->update($request->all());

        return redirect()->route('admin.shifts.index');
    }

    public function show(Shift $shift)
    {
        abort_if(Gate::denies('shift_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.shifts.show', compact('shift'));
    }

    public function destroy(Shift $shift)
    {
        abort_if(Gate::denies('shift_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $shift->delete();

        return back();
    }

    public function massDestroy(MassDestroyShiftRequest $request)
    {
        Shift::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
