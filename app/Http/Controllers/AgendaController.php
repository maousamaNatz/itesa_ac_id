<?php

namespace App\Http\Controllers;

use App\Models\Agenda;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AgendaController extends Controller
{
    /**
     * Menampilkan daftar agenda
     */
    public function index()
    {
        try {
            $agendas = Agenda::orderBy('start_date', 'desc')->paginate(10);
            return view('itesa_ac_id.dashboard.agenda', compact('agendas'));
        } catch (\Exception $e) {
            Log::error('Error in AgendaController@index: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat memuat data agenda');
        }
    }

    /**
     * Menampilkan form tambah agenda
     */
    public function create()
    {
        return view('itesa_ac_id.dashboard.agenda.create');
    }

    /**
     * Menyimpan agenda baru
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
                'location' => 'required|string|max:255',
                'status' => 'required|in:active,inactive'
            ]);

            Agenda::create($validated);

            return redirect()
                ->route('admin.agenda.index')
                ->with('success', 'Agenda berhasil ditambahkan');
        } catch (\Exception $e) {
            Log::error('Error in AgendaController@store: ' . $e->getMessage());
            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat menyimpan agenda');
        }
    }

    /**
     * Menampilkan form edit agenda
     */
    public function edit($id)
    {
        try {
            $agenda = Agenda::findOrFail($id);
            return view('itesa_ac_id.dashboard.agenda.edit', compact('agenda'));
        } catch (\Exception $e) {
            Log::error('Error in AgendaController@edit: ' . $e->getMessage());
            return back()->with('error', 'Agenda tidak ditemukan');
        }
    }

    /**
     * Mengupdate agenda
     */
    public function update(Request $request, $id)
    {
        try {
            $agenda = Agenda::findOrFail($id);

            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
                'location' => 'required|string|max:255',
                'status' => 'required|in:active,inactive'
            ]);

            $agenda->update($validated);

            return redirect()
                ->route('admin.agenda.index')
                ->with('success', 'Agenda berhasil diperbarui');
        } catch (\Exception $e) {
            Log::error('Error in AgendaController@update: ' . $e->getMessage());
            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat memperbarui agenda');
        }
    }

    /**
     * Menghapus agenda
     */
    public function destroy($id)
    {
        try {
            $agenda = Agenda::findOrFail($id);
            $agenda->delete();

            return redirect()
                ->route('admin.agenda.index')
                ->with('success', 'Agenda berhasil dihapus');
        } catch (\Exception $e) {
            Log::error('Error in AgendaController@destroy: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat menghapus agenda');
        }
    }

    /**
     * Menampilkan detail agenda
     */
    public function show($id)
    {
        try {
            $agenda = Agenda::findOrFail($id);
            return view('itesa_ac_id.dashboard.agenda.show', compact('agenda'));
        } catch (\Exception $e) {
            Log::error('Error in AgendaController@show: ' . $e->getMessage());
            return back()->with('error', 'Agenda tidak ditemukan');
        }
    }
}
