<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSupplierRequest;
use App\Http\Requests\UpdateSupplierRequest;
use App\Models\Supplier;
use App\Services\SupplierService;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function __construct(
        protected SupplierService $supplierService
    ) {}

    /**
     * Display a listing of suppliers.
     */
    public function index(Request $request)
    {
        $suppliers = $this->supplierService->getPaginatedSuppliers($request->search, 10);
        return view('suppliers.index', compact('suppliers'));
    }

    /**
     * Show the form for creating a new supplier.
     */
    public function create()
    {
        return view('suppliers.create');
    }

    /**
     * Store a newly created supplier in storage.
     */
    public function store(StoreSupplierRequest $request)
    {
        $supplier = $this->supplierService->createSupplier($request->validated());
        $name = $supplier->company_name ?? $supplier->name;

        return redirect()->route('suppliers.index')
            ->with('success', "Data supplier '{$name}' berhasil ditambahkan.");
    }

    /**
     * Show the form for editing the specified supplier.
     */
    public function edit(Supplier $supplier)
    {
        return view('suppliers.edit', compact('supplier'));
    }

    /**
     * Update the specified supplier in storage.
     */
    public function update(UpdateSupplierRequest $request, Supplier $supplier)
    {
        $this->supplierService->updateSupplier($supplier, $request->validated());
        $name = $supplier->company_name ?? $supplier->name;

        return redirect()->route('suppliers.index')
            ->with('success', "Data supplier '{$name}' berhasil diperbarui.");
    }

    /**
     * Remove the specified supplier from storage.
     */
    public function destroy(Supplier $supplier)
    {
        $result = $this->supplierService->deleteSupplier($supplier);

        if (! $result['success']) {
            return redirect()->route('suppliers.index')->with('error', $result['message']);
        }

        return redirect()->route('suppliers.index')->with('success', $result['message']);
    }
}
