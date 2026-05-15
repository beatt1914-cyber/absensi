<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DosenController extends Controller
{
    public function index(Request $request)
    {
        $query = Dosen::query();

        if ($request->has('search') && $request->search) {
            $query->where('nama', 'like', '%' . $request->search . '%')
                  ->orWhere('nip', 'like', '%' . $request->search . '%')
                  ->orWhere('jurusan', 'like', '%' . $request->search . '%');
        }

        $dosen = $query->orderBy('nama')->paginate(10);

        return view('dosen.index', compact('dosen'));
    }

    public function create()
    {
        return view('dosen.create');
    }

    public function import()
    {
        return view('dosen.import');
    }

    public function processImport(Request $request)
    {
        $request->validate([
            'data_dosen' => 'required|string',
        ]);

        $lines = explode("\n", $request->data_dosen);
        $successCount = 0;
        $errorMessages = [];

        foreach ($lines as $index => $line) {
            $line = trim($line);
            if (empty($line)) continue;

            $data = str_getcsv($line, ";"); // Using semicolon as separator
            
            if (count($data) < 4) {
                $errorMessages[] = "Baris " . ($index + 1) . ": Format tidak valid (Minimal NIP;Nama;Email;Jurusan)";
                continue;
            }

            try {
                Dosen::updateOrCreate(
                    ['nip' => trim($data[0])],
                    [
                        'nama' => trim($data[1]),
                        'email' => trim($data[2]),
                        'jurusan' => trim($data[3]),
                        'no_hp' => isset($data[4]) ? trim($data[4]) : null,
                        'alamat' => isset($data[5]) ? trim($data[5]) : null,
                    ]
                );
                $successCount++;
            } catch (\Exception $e) {
                $errorMessages[] = "Baris " . ($index + 1) . ": " . $e->getMessage();
            }
        }

        $message = "Berhasil mengimpor $successCount dosen.";
        if (!empty($errorMessages)) {
            return redirect()->route('dosen.index')->with('success', $message)->with('import_errors', $errorMessages);
        }

        return redirect()->route('dosen.index')->with('success', $message);
    }

    public function store(Request $request)
    {
        // Debug: log incoming data
        \Log::info('Dosen store request:', $request->all());
        
        $validator = Validator::make($request->all(), [
            'nip' => 'required|string|max:20|unique:dosen,nip',
            'nama' => 'required|string|max:200',
            'email' => 'required|email|unique:dosen,email',
            'no_hp' => 'nullable|string|max:20',
            'jurusan' => 'required|string|max:200',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        Dosen::create($request->all());

        return redirect()->route('dosen.index')
            ->with('success', 'Dosen berhasil ditambahkan');
    }

    public function edit(Dosen $dosen)
    {
        return view('dosen.edit', compact('dosen'));
    }

    public function update(Request $request, Dosen $dosen)
    {
        $validator = Validator::make($request->all(), [
            'nip' => 'required|string|max:20|unique:dosen,nip,' . $dosen->id,
            'nama' => 'required|string|max:200',
            'email' => 'required|email|unique:dosen,email,' . $dosen->id,
            'no_hp' => 'nullable|string|max:20',
            'jurusan' => 'required|string|max:200',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $dosen->update($request->all());

        return redirect()->route('dosen.index')
            ->with('success', 'Dosen berhasil diperbarui');
    }

    public function destroy(Dosen $dosen)
    {
        $dosen->delete();

        return redirect()->route('dosen.index')
            ->with('success', 'Dosen berhasil dihapus');
    }
}