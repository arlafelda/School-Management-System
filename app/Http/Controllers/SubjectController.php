<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SubjectController extends Controller
{
    public function index()
    {
        $subjects = Subject::latest()->get();

        return view('subject.subject-index', compact('subjects'));
    }

    public function archived()
    {
        $subjects = Subject::onlyTrashed()->latest()->get();

        return view('subject.subject-archived', compact('subjects'));
    }

    public function create()
    {
        return view('subject.subject-add');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required',
            'code'        => 'required|unique:tbl_subjects,code',
            'description' => 'nullable',
        ]);

        $slug = Str::slug($request->name);

        if (Subject::where('slug', $slug)->exists()) {
            $slug .= '-' . time();
        }

        $subject = Subject::create([
            'name'        => $request->name,
            'code'        => $request->code,
            'kkm'         => 75,
            'slug'        => $slug,
            'description' => $request->description,
        ]);

        // 📝 Catat aktivitas
        ActivityLogService::create(
            'Subject',
            "Menambahkan mata pelajaran baru: {$subject->name} (Kode: {$subject->code})",
            $subject->name,
            $subject->only(['name', 'code', 'kkm', 'description'])
        );

        return $request->ajax()
            ? response()->json([
                'success' => true,
                'message' => 'Subject berhasil ditambahkan',
                'data'    => $subject,
            ])
            : redirect()->route('subjects.index')
                ->with('success', 'Subject berhasil ditambahkan');
    }

    public function edit(string $slug)
    {
        $subject = Subject::where('slug', $slug)->firstOrFail();

        return view('subject.subject-edit', compact('subject'));
    }

    public function update(Request $request, string $slug)
    {
        $subject = Subject::where('slug', $slug)->firstOrFail();

        $request->validate([
            'name'        => 'required',
            'code'        => 'required|unique:tbl_subjects,code,' . $subject->id,
            'description' => 'nullable',
        ]);

        // Simpan data lama sebelum diupdate
        $oldData = $subject->only(['name', 'code', 'description', 'slug']);

        $newSlug = Str::slug($request->name);

        if (
            Subject::where('slug', $newSlug)
                ->where('id', '!=', $subject->id)
                ->exists()
        ) {
            $newSlug .= '-' . time();
        }

        $subject->update([
            'name'        => $request->name,
            'code'        => $request->code,
            'slug'        => $newSlug,
            'description' => $request->description,
        ]);

        // 📝 Catat aktivitas
        ActivityLogService::update(
            'Subject',
            "Mengupdate mata pelajaran: {$subject->name} (Kode: {$subject->code})",
            $subject->name,
            $oldData,
            $subject->only(['name', 'code', 'description', 'slug'])
        );

        return $request->ajax()
            ? response()->json([
                'success' => true,
                'message' => 'Subject berhasil diupdate',
                'data'    => $subject,
            ])
            : redirect()->route('subjects.index')
                ->with('success', 'Subject berhasil diupdate');
    }

    // SOFT DELETE (arsip)
    public function destroy(Request $request, string $slug)
    {
        $subject = Subject::where('slug', $slug)->firstOrFail();

        if ($subject->teachers()->count() > 0) {
            return $request->ajax()
                ? response()->json([
                    'success' => false,
                    'message' => 'Subject tidak bisa diarsipkan karena masih digunakan guru',
                ], 422)
                : back()->with('error', 'Subject masih digunakan guru');
        }

        // 📝 Catat aktivitas sebelum dihapus
        ActivityLogService::delete(
            'Subject',
            "Mengarsipkan mata pelajaran: {$subject->name} (Kode: {$subject->code})",
            $subject->name,
            $subject->only(['name', 'code', 'description', 'slug'])
        );

        $subject->delete();

        return $request->ajax()
            ? response()->json([
                'success' => true,
                'message' => 'Subject berhasil diarsipkan',
            ])
            : redirect()->route('subjects.index')
                ->with('success', 'Subject berhasil diarsipkan');
    }

    // RESTORE
    public function restore(Request $request, string $slug)
    {
        $subject = Subject::onlyTrashed()
            ->where('slug', $slug)
            ->firstOrFail();

        $subject->restore();

        // 📝 Catat aktivitas
        ActivityLogService::restore(
            'Subject',
            "Merestore mata pelajaran: {$subject->name} (Kode: {$subject->code})",
            $subject->name
        );

        return $request->ajax()
            ? response()->json([
                'success' => true,
                'message' => 'Subject berhasil direstore',
            ])
            : redirect()->route('subjects.archived')
                ->with('success', 'Subject berhasil direstore');
    }

    // FORCE DELETE (PERMANENT)
    public function delete(Request $request, string $slug)
    {
        $subject = Subject::onlyTrashed()
            ->where('slug', $slug)
            ->firstOrFail();

        if ($subject->teachers()->exists()) {
            return $request->ajax()
                ? response()->json([
                    'success' => false,
                    'message' => 'Tidak bisa dihapus, masih digunakan oleh guru',
                ], 422)
                : back()->with('error', 'Tidak bisa dihapus, masih digunakan oleh guru');
        }

        // 📝 Catat aktivitas sebelum dihapus permanen
        ActivityLogService::forceDelete(
            'Subject',
            "Menghapus permanen mata pelajaran: {$subject->name} (Kode: {$subject->code})",
            $subject->name,
            $subject->only(['name', 'code', 'description', 'slug'])
        );

        $subject->forceDelete();

        return $request->ajax()
            ? response()->json([
                'success' => true,
                'message' => 'Subject berhasil dihapus permanen',
            ])
            : redirect()->route('subjects.archived')
                ->with('success', 'Subject berhasil dihapus permanen');
    }
}