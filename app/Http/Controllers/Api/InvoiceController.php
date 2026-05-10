<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Invoice\StoreInvoiceRequest;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Item;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    use ApiResponse;

    public function index(Request $request): JsonResponse
    {
        $invoices = Invoice::query()
            ->with(['creator:id,name,email', 'items.item:id,code,name'])
            ->latest('id')
            ->paginate((int) $request->query('per_page', 15));

        return $this->successResponse($invoices, 'Invoices loaded');
    }

    public function store(StoreInvoiceRequest $request): JsonResponse
    {
        $payload = $request->validated();

        $invoice = DB::transaction(function () use ($payload, $request): Invoice {
            $subtotal = 0;

            $invoice = Invoice::query()->create([
                'number' => 'INV-'.now()->format('YmdHis').'-'.str_pad((string) random_int(1, 999), 3, '0', STR_PAD_LEFT),
                'created_by' => $request->user()->id,
                'status' => Invoice::STATUS_POSTED,
                'invoice_date' => $payload['invoice_date'] ?? now()->toDateString(),
                'notes' => $payload['notes'] ?? null,
                'subtotal' => 0,
                'tax_amount' => (float) ($payload['tax_amount'] ?? 0),
                'total_amount' => 0,
            ]);

            foreach ($payload['items'] as $line) {
                $item = Item::query()->findOrFail($line['item_id']);
                $quantity = (int) $line['quantity'];
                $unitPrice = (float) $item->unit_price;
                $lineTotal = $quantity * $unitPrice;

                InvoiceItem::query()->create([
                    'invoice_id' => $invoice->id,
                    'item_id' => $item->id,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'line_total' => $lineTotal,
                ]);

                $item->decrement('stock', $quantity);

                $subtotal += $lineTotal;
            }

            $taxAmount = (float) ($payload['tax_amount'] ?? 0);

            $invoice->update([
                'subtotal' => $subtotal,
                'tax_amount' => $taxAmount,
                'total_amount' => $subtotal + $taxAmount,
            ]);

            return $invoice;
        });

        return $this->successResponse(
            $invoice->load(['creator:id,name,email', 'items.item:id,code,name']),
            'Invoice created',
            201
        );
    }

    public function show(Invoice $invoice): JsonResponse
    {
        return $this->successResponse(
            $invoice->load(['creator:id,name,email', 'items.item:id,code,name']),
            'Invoice loaded'
        );
    }
}