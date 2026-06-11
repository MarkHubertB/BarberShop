<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Barber;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BarberController extends Controller
{
    public function index(): View
    {
        return view('admin.barbers.index', [
            'barbers' => Barber::orderBy('name')->paginate(12),
        ]);
    }

    public function create(): View
    {
        return view('admin.barbers.create');
    }

    public function store(Request $request): RedirectResponse
    {
        Barber::create($this->validatedData($request));

        return redirect()->route('admin.barbers.index')->with('status', 'Barber created.');
    }

    public function show(Barber $barber): RedirectResponse
    {
        return redirect()->route('admin.barbers.edit', $barber);
    }

    public function edit(Barber $barber): View
    {
        return view('admin.barbers.edit', ['barber' => $barber]);
    }

    public function update(Request $request, Barber $barber): RedirectResponse
    {
        if ($request->boolean('_toggle_active')) {
            $barber->update(['is_active' => $request->boolean('is_active')]);

            return back()->with('status', 'Barber status updated.');
        }

        $barber->update($this->validatedData($request));

        return redirect()->route('admin.barbers.index')->with('status', 'Barber updated.');
    }

    public function destroy(Barber $barber): RedirectResponse
    {
        if ($barber->bookings()->exists()) {
            $barber->update(['is_active' => false]);

            return back()->with('status', 'Barber has bookings, so he was deactivated instead.');
        }

        $barber->delete();

        return back()->with('status', 'Barber deleted.');
    }

    private function validatedData(Request $request): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'bio' => ['nullable', 'string', 'max:2000'],
            'photo_url' => ['nullable', 'url', 'max:2048'],
            'specialty' => ['nullable', 'string', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data['is_active'] = $request->boolean('is_active');

        return $data;
    }
}
