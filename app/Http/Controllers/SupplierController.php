<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class SupplierController extends Controller
{
    public function index()
    {
        return view('supplier.index');
    }

    public function data()
    {
        $supplier = Supplier::orderBy('id_supplier', 'desc')->get();

        return datatables()
            ->of($supplier)
            ->addIndexColumn()
            ->addColumn('aksi', function ($supplier) {
                return '
                <div class="btn-group">
                    <button type="button" onclick="editForm(`'. route('supplier.update', $supplier->id_supplier) .'`)" class="btn btn-xs btn-info btn-flat"><i class="fa fa-pencil"></i></button>
                    <button type="button" onclick="deleteData(`'. route('supplier.destroy', $supplier->id_supplier) .'`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
                </div>
                ';
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function store(Request $request): JsonResponse
    {
        $supplier = Supplier::create($request->all());

        return response()->json('Data berhasil disimpan', 200);
    }

    public function show($id): JsonResponse
    {
        $supplier = Supplier::find($id);

        return response()->json($supplier);
    }

    public function edit($id)
    {
        // Sama seperti create(), fungsi ini mungkin tidak terpakai di alur AJAX Anda.
        // Kita tambahkan return untuk menghilangkan warning.
        abort(404); // Atau return response('Not Found', 404);
    }

    public function update(Request $request, $id): JsonResponse
    {
        $supplier = Supplier::find($id);
        $supplier->update($request->all());

        return response()->json('Data berhasil diperbarui', 200);
    }

    public function destroy($id): Response
    {
        $supplier = Supplier::find($id);
        $supplier->delete();

        return response(null, 204);
    }
}