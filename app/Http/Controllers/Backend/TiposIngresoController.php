<?php
    
declare(strict_types=1);

namespace App\Http\Controllers\Backend;
    
use App\Http\Controllers\Controller;
use App\Http\Requests\TipoIngresoRequest;
use App\Models\TipoIngreso;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Spatie\Permission\Models\Role;

class TiposIngresoController extends Controller
{
    public function index(): Renderable
    {
        $this->checkAuthorization(auth()->user(), ['tipoIngreso.view']);

        return view('backend.pages.tiposIngreso.index', [
            'tiposIngreso' => TipoIngreso::all(),
        ]);
    }

    public function create(): Renderable
    {
        $this->checkAuthorization(auth()->user(), ['tipoIngreso.create']);

        return view('backend.pages.tiposIngreso.create', [
            'roles' => Role::all(),
        ]);
    }

    public function store(TipoIngresoRequest $request): RedirectResponse
    {
        $this->checkAuthorization(auth()->user(), ['tipoIngreso.create']);

        $tipoIngreso = new TipoIngreso();
        $tipoIngreso->nombre = $request->nombre;
        $tipoIngreso->save();

        session()->flash('success', __('Tipo Ingreso ha sido creada satisfactoriamente.'));
        return redirect()->route('admin.tiposIngreso.index');
    }

    public function edit(int $id): Renderable
    {
        $this->checkAuthorization(auth()->user(), ['tipoIngreso.edit']);

        $tipoIngreso = TipoIngreso::findOrFail($id);
        return view('backend.pages.tiposIngreso.edit', [
            'tipoIngreso' => $tipoIngreso,
            'roles' => Role::all(),
        ]);
    }

    public function update(TipoIngresoRequest $request, int $id): RedirectResponse
    {
        $this->checkAuthorization(auth()->user(), ['tipoIngreso.edit']);

        $tipoIngreso = TipoIngreso::findOrFail($id);
        $tipoIngreso->nombre = $request->nombre;
        $tipoIngreso->save();

        session()->flash('success', 'Tipo Ingreso ha sido actualizada satisfactoriamente.');
        return back();
    }

    public function destroy(int $id): RedirectResponse
    {
        $this->checkAuthorization(auth()->user(), ['tipoIngreso.delete']);

        $tipoIngreso = TipoIngreso::findOrFail($id);
        $tipoIngreso->delete();
        session()->flash('success', 'Tipo Ingreso ha sido borrada satisfactoriamente.');
        return back();
    }
}