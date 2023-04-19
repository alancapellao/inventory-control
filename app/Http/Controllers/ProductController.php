<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Product;

class ProductController extends Controller
{
    // Função para acessar o banco e salvar produto
    public function save(Request $request)
    {
        $data = $request->only(["item", "category", "status", "sale", "stock", "price"]);

        $user = Auth::user()->id;

        try {
            $product = Product::create([
                'item' => $data['item'],
                'category' => $data['category'],
                'status' => $data['status'],
                'sale' => $data['sale'],
                'stock' => $data['stock'],
                'price' => $data['price'],
                'user_id' => $user
            ]);

            return response()->json(['error' => false, 'message' => 'Successfully registered product.']);
        } catch (\Exception $e) {
            return response()->json(['error' => true, 'message' => 'Error saving product.']);
        }
    }

    // Função para acessar o banco e buscar produtos
    public function getProducts()
    {
        $user = Auth::user()->id;

        $products = Product::where('user_id', $user)
            ->select(['id', 'item', 'category', 'status', 'sale', 'stock', 'price'])
            ->get();

        return response()->json(['produtos' => $products]);
    }

    // Função para acessar o banco e buscar produto específico
    public function getProduct($productId)
    {
        $user = Auth::user()->id;

        $product = Product::where('user_id', $user)
            ->where('id', $productId)
            ->first();

        return response()->json(['produto' => $product]);
    }

    // Função para atualizar registros no banco
    public function update($productId, Request $request)
    {
        $data = $request->only(["item", "category", "status", "sale", "stock", "price"]);

        try {
            $product = Product::find($productId);
            $product->item = $request->input('item');
            $product->category = $request->input('category');
            $product->status = $request->input('status');
            $product->sale = $request->input('sale');
            $product->stock = $request->input('stock');
            $product->price = $request->input('price');
            $product->save();

            return response()->json(['error' => false, 'message' => 'Product updated successfully.']);
        } catch (\Exception $e) {
            return response()->json(['error' => true, 'message' => 'Error updating product.']);
        }
    }

    // Função para deletar registros no banco
    public function delete($productId)
    {
        $produto = Product::find($productId);

        if ($produto) {
            $produto->delete();

            return response()->json(['error' => false, 'message' => 'Successfully deleted product.']);
        } else {
            return response()->json(['error' => true, 'message' => 'Error deleting product.']);
        }
    }

    // Função de pesquisa de produtos no banco
    public function search(Request $request)
    {
        $data = $request->only('search');

        $user = Auth::user()->id;

        $products = Product::where('user_id', $user)
            ->where(function ($query) use ($data) {
                $query->orWhere('item', 'LIKE', "%{$data['search']}%")
                    ->orWhere('category', 'LIKE', "%{$data['search']}%")
                    ->orWhere('status', 'LIKE', "%{$data['search']}%")
                    ->orWhere('sale', 'LIKE', "%{$data['search']}%")
                    ->orWhere('stock', 'LIKE', "%{$data['search']}%")
                    ->orWhere('price', 'LIKE', "%{$data['search']}%");
            })
            ->get();

        return response()->json(['produtos' => $products]);
    }

    // Função para obter dados dos produtos do banco
    public function getStatistics()
    {
        $user = Auth::user()->id; // ID do usuário logado

        $saleCategory = Product::select('category', DB::raw('COALESCE(SUM(sale), 0) as sales'))
            ->where('user_id', $user) // Filtra apenas os produtos vendidos pelo usuário logado
            ->groupBy('category')
            ->pluck('sales', 'category')
            ->toArray();

        $stockCategory = Product::select('category', DB::raw('COALESCE(SUM(stock), 0) as stocks'))
            ->where('user_id', $user) // Filtra apenas os produtos vendidos pelo usuário logado
            ->groupBy('category')
            ->pluck('stocks', 'category')
            ->toArray();

        $activeProducts = Product::where('user_id', $user)
            ->where('status', "active")
            ->count();

        $inactiveProducts = Product::where('user_id', $user)
            ->where('status', "disabled")
            ->count();

        return response()->json(['sale' => $saleCategory, 'stock' => $stockCategory, 'active' => $activeProducts, 'disabled' => $inactiveProducts]);
    }
}
