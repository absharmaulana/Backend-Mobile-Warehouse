<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Inventory\StoreItemRequest;
use App\Http\Requests\Api\Inventory\UpdateItemRequest;
use App\Models\Item;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    use ApiResponse;

    public function index(Request $request): JsonResponse
    {
        $query = Item::query()->latest('id');

        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search): void {
                $q->where('code', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%");
            });
        }

        return $this->successResponse(
            $query->paginate((int) $request->query('per_page', 15)),
            'Items loaded'
        );
    }

    public function store(StoreItemRequest $request): JsonResponse
    {
        $item = Item::query()->create($request->validated());

        return $this->successResponse($item, 'Item created', 201);
    }

    public function show(Item $item): JsonResponse
    {
        return $this->successResponse($item, 'Item loaded');
    }

    public function update(UpdateItemRequest $request, Item $item): JsonResponse
    {
        $item->update($request->validated());

        return $this->successResponse($item->fresh(), 'Item updated');
    }

    public function destroy(Item $item): JsonResponse
    {
        $item->delete();

        return $this->successResponse(null, 'Item deleted');
    }
}