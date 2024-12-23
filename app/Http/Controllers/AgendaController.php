<?php

/**
 * Controller Agenda
 *
 * @package App\Http\Controllers
 * @description Controller untuk mengelola agenda/kegiatan di ITESA
 */

namespace App\Http\Controllers;

use App\Models\Agenda;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * Class AgendaController
 * Mengelola CRUD operasi untuk agenda/kegiatan
 *
 * @package App\Http\Controllers
 */
class AgendaController extends Controller
{
    /**
     * Menampilkan daftar agenda
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     * @throws \Exception Ketika terjadi kesalahan dalam memuat data
     *
     * @response {
     *   "view": "itesa_ac_id.dashboard.agenda",
     *   "data": {
     *     "agendas": "LengthAwarePaginator"
     *   }
     * }
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
     * Menampilkan form tambah agenda baru
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('itesa_ac_id.dashboard.agenda.create');
    }

    /**
     * Menyimpan agenda baru ke database
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     *
     * @bodyParam title string required Judul agenda. Max:255
     * @bodyParam description string required Deskripsi agenda
     * @bodyParam start_date date required Tanggal mulai agenda
     * @bodyParam end_date date required Tanggal selesai agenda. After or equal:start_date
     * @bodyParam location string required Lokasi agenda. Max:255
     * @bodyParam status string required Status agenda (active/inactive)
     *
     * @response 302 {
     *   "redirect": "admin.agenda.index",
     *   "with": {
     *     "success": "Agenda berhasil ditambahkan"
     *   }
     * }
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
     *
     * @param int $id ID agenda
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     * @throws \Exception
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
     * Memperbarui data agenda
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id ID agenda
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     *
     * @bodyParam title string required Judul agenda. Max:255
     * @bodyParam description string required Deskripsi agenda
     * @bodyParam start_date date required Tanggal mulai agenda
     * @bodyParam end_date date required Tanggal selesai agenda. After or equal:start_date
     * @bodyParam location string required Lokasi agenda. Max:255
     * @bodyParam status string required Status agenda (active/inactive)
     *
     * @response 302 {
     *   "redirect": "admin.agenda.index",
     *   "with": {
     *     "success": "Agenda berhasil diperbarui"
     *   }
     * }
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
     *
     * @param int $id ID agenda
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     *
     * @response 302 {
     *   "redirect": "admin.agenda.index",
     *   "with": {
     *     "success": "Agenda berhasil dihapus"
     *   }
     * }
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
     *
     * @param int $id ID agenda
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     * @throws \Exception
     *
     * @response {
     *   "view": "itesa_ac_id.dashboard.agenda.show",
     *   "data": {
     *     "agenda": "App\Models\Agenda"
     *   }
     * }
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
