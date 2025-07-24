<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(): View
    {
        return view('user.index');
    }

    public function data()
    {
        $user = User::isNotAdmin()->orderBy('id', 'desc')->get();

        return datatables()
            ->of($user)
            ->addIndexColumn()
            ->addColumn('aksi', function ($user) {
                return '
                <div class="btn-group">
                    <button type="button" onclick="editForm(`'. route('user.update', $user->id) .'`)" class="btn btn-xs btn-info btn-flat"><i class="fa fa-pencil"></i></button>
                    <button type="button" onclick="deleteData(`'. route('user.destroy', $user->id) .'`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
                </div>
                ';
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }


    public function create()
    {
        abort(404);
    }


    public function store(Request $request): JsonResponse
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'level' => 2,
            'foto' => '/img/user.jpg',
        ]);

        return response()->json('Data berhasil disimpan', 200);
    }


    public function show($id): JsonResponse
    {
        $user = User::find($id);
        return response()->json($user);
    }


    public function edit($id)
    {
        abort(404);
    }

    public function update(Request $request, $id): JsonResponse
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json('Data tidak ditemukan', 404);
        }

        $data = [
            'name' => $request->name,
            'email' => $request->email,
        ];

        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        }

        $user->update($data);

        return response()->json('Data berhasil diperbarui', 200);
    }

    public function destroy($id): Response
    {
        $user = User::find($id);
        if ($user) {
            $user->delete();
        }

        return response(null, 204);
    }

    public function profil(): View
    {
        $profil = auth()->user();
        return view('user.profil', compact('profil'));
    }

    public function updateProfil(Request $request): JsonResponse
    {
        $user = auth()->user();

        // Siapkan data dalam array
        $data = $request->except('password', 'old_password', 'password_confirmation', 'foto');

        // Validasi dan update password jika diisi
        if ($request->filled('password')) {
            if (!Hash::check($request->old_password, $user->password)) {
                return response()->json('Password lama tidak sesuai', 422);
            }
            if ($request->password != $request->password_confirmation) {
                return response()->json('Konfirmasi password tidak sesuai', 422);
            }
            $data['password'] = bcrypt($request->password);
        }

        // Handle upload file foto
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $nama = 'logo-' . date('YmdHis') . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('/img'), $nama);
            $data['foto'] = "/img/$nama";
        }

        // Update user dengan semua data yang sudah disiapkan
        $user->update($data);

        return response()->json($user, 200);
    }
}
