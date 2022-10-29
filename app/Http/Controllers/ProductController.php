<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ProductEditRequest;
use App\Http\Requests\ProductCreateRequest;

class ProductController extends BaseController
{
    public function index(Request $request) {
        $productsQuery = Product::select("*");

        if ($request->name) {
            $productsQuery->where('name', 'like', "%$request->name%");
        }

        if ($request->description) {
            $productsQuery->where('description', 'like', "%$request->description%");
        }

        if ($request->minPrice) {
            $productsQuery->where('price', '>=', "$request->minPrice");
        }
        if ($request->maxPrice) {
            $productsQuery->where('price', '<=', "$request->maxPrice");
        }

        if ($request->sortBy) {
            $sortOrder = ($request->sortOrder && in_array(strtolower($request->sortOrder), ['desc', 'asc'])) ? $request->sortOrder : 'desc';

            $productsQuery->orderBy($request->sortBy, $sortOrder);
        }

        return response()->json([
            'success' => true,
            'data' => $productsQuery->get()
        ], 200);
    }

    public function create(ProductCreateRequest $request) {
        $product = Product::create($request->all());
        return response()->json([
            'success' => true,
            'data' => $product
        ], 200);
    }

    public function show($id) {
        $productQuery = Product::where('id', '=', $id);

        return response()->json([
            'success' => true,
            'data' => $productQuery->get()
        ], 200);
    }

    public function edit(ProductEditRequest $request, $id) {
        $product = Product::where('id', '=', $id)->first();
        if (!$product) return response()->json([
            'success' => false,
            'message' => 'Can\'t find product with this id'
        ], 400);

        $product->update($request->all());
        return response()->json([
            'success' => true,
            'data' => $request->all()
        ], 200);
    }

    public function destroy($id) {
        Product::where('id', '=', $id)->delete();

        return response()->json([
            'success' => true
        ], 200);
    }
}
