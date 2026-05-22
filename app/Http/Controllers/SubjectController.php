<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SubjectController extends Controller
{
    public function index()
    {
        $subjects = Subject::where('archived', 0)
            ->latest()
            ->get();

        return view('subject.subject-index', compact('subjects'));
    }

    public function archived()
    {
        $subjects = Subject::where('archived', 1)
            ->latest()
            ->get();

        return view('subject.subject-archived', compact('subjects'));
    }

    public function create()
    {
        return view('subject.subject-add');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'code' => 'required|unique:tbl_subjects,code',
            'description' => 'nullable'
        ]);

        $slug = Str::slug($request->name);

        if (Subject::where('slug', $slug)->exists()) {
            $slug .= '-' . time();
        }

        $subject = Subject::create([
            'name' => $request->name,
            'code' => $request->code,
            'kkm' => 75,
            'slug' => $slug,
            'description' => $request->description,
            'archived' => 0
        ]);

        return $request->ajax()
            ? response()->json([
                'success' => true,
                'message' => 'Subject berhasil ditambahkan',
                'data' => $subject
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
            'name' => 'required',
            'code' => 'required|unique:tbl_subjects,code,' . $subject->id,
            'description' => 'nullable'
        ]);

        $newSlug = Str::slug($request->name);

        if (
            Subject::where('slug', $newSlug)
                ->where('id', '!=', $subject->id)
                ->exists()
        ) {
            $newSlug .= '-' . time();
        }

        $subject->update([
            'name' => $request->name,
            'code' => $request->code,
            'slug' => $newSlug,
            'description' => $request->description
        ]);

        return $request->ajax()
            ? response()->json([
                'success' => true,
                'message' => 'Subject berhasil diupdate',
                'data' => $subject
            ])
            : redirect()->route('subjects.index')
                ->with('success', 'Subject berhasil diupdate');
    }

    public function destroy(Request $request, string $slug)
    {
        $subject = Subject::where('slug', $slug)->firstOrFail();

        if ($subject->teachers()->count() > 0) {
            return $request->ajax()
                ? response()->json([
                    'success' => false,
                    'message' => 'Subject tidak bisa diarsipkan karena masih digunakan guru'
                ], 422)
                : back()->with('error', 'Subject masih digunakan guru');
        }

        $subject->update([
            'archived' => 1
        ]);

        return $request->ajax()
            ? response()->json([
                'success' => true,
                'message' => 'Subject berhasil diarsipkan'
            ])
            : redirect()->route('subjects.index')
                ->with('success', 'Subject berhasil diarsipkan');
    }

    public function restore(Request $request, string $slug)
    {
        $subject = Subject::where('slug', $slug)->firstOrFail();

        $subject->update([
            'archived' => 0
        ]);

        return $request->ajax()
            ? response()->json([
                'success' => true,
                'message' => 'Subject berhasil direstore'
            ])
            : redirect()->route('subjects.archived')
                ->with('success', 'Subject berhasil direstore');
    }

    public function delete(Request $request, string $slug)
    {
        $subject = Subject::where('slug', $slug)->firstOrFail();

        if ($subject->teachers()->exists()) {
            return $request->ajax()
                ? response()->json([
                    'success' => false,
                    'message' => 'Tidak bisa dihapus, masih digunakan oleh guru'
                ], 422)
                : back()->with('error', 'Tidak bisa dihapus, masih digunakan oleh guru');
        }

        $subject->delete();

        return $request->ajax()
            ? response()->json([
                'success' => true,
                'message' => 'Subject berhasil dihapus permanen'
            ])
            : redirect()->route('subjects.archived')
                ->with('success', 'Subject berhasil dihapus permanen');
    }
}