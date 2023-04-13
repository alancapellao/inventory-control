<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Stmt\TryCatch;

class ProductController extends Controller
{
    // Função para acessar o banco e salvar produto
    public function save(Request $request)
    {
        $data = $request->only(["item", "category", "status", "sale", "stock", "price"]);

        if (empty($data['item']) || empty($data['category']) || empty($data['status']) || empty($data['sale']) || empty($data['stock']) || empty($data['price'])) {
            return response()->json(['erro' => true, 'mensagem' => 'Campos não preenchidos.']);
        }

        $usuario_id = Auth::id();

        try {
            $product = \App\Models\Product::create([
                'item' => $data['item'],
                'category' => $data['category'],
                'status' => $data['status'],
                'sale' => $data['sale'],
                'stock' => $data['stock'],
                'price' => $data['price'],
                'usuario_id' => $usuario_id
            ]);
            return response()->json(['erro' => false]);
        } catch (\Exception $e) {
            return response()->json(['erro' => true]);
        }
    }

    // Função para acessar o banco e buscar produto
    public function getProducts()
    {
        $userId = auth()->user()->id;
        $products = \App\Models\Product::where('usuario_id', $userId)
            ->select(['id', 'item', 'category', 'status', 'sale', 'stock', 'price'])
            ->get();
        return response()->json(['erro' => false, 'produtos' => $products]);
    }

    // Função para acessar o banco e buscar produto específico
    public function getProduct($productId)
    {
        $userId = auth()->user()->id;
        $product = \App\Models\Product::where('usuario_id', $userId)
            ->where('id', $productId)
            ->first();
        return response()->json(['erro' => false, 'produto' => $product]);
    }

    // Função para atualizar registros no banco
    public function update($productId, Request $request)
    {
        $data = $request->only(["item", "category", "status", "sale", "stock", "price"]);

        try {
            $product = \App\Models\Product::find($productId);
            $product->item = $request->input('item');
            $product->category = $request->input('category');
            $product->status = $request->input('status');
            $product->sale = $request->input('sale');
            $product->stock = $request->input('stock');
            $product->price = $request->input('price');

            $product->save();

            return response()->json(['erro' => false]);
        } catch (\Exception $e) {
            return response()->json(['erro' => true]);
        }
    }

    // Função para deletar registros no banco
    public function delete($productId)
    {
        $produto = \App\Models\Product::find($productId);

        if ($produto) {
            $produto->delete();
            return response()->json(['erro' => false]);
        } else {
            return response()->json(['erro' => true]);
        }
    }

    // Função de pesquisa de produtos no banco
    public function search(Request $request)
    {
        $data = $request->only('search');
        $products = \App\Models\Product::where('item', 'LIKE', "%{$data['search']}%")
            ->orWhere('category', 'LIKE', "%{$data['search']}%")
            ->orWhere('status', 'LIKE', "%{$data['search']}%")
            ->orWhere('sale', 'LIKE', "%{$data['search']}%")
            ->orWhere('stock', 'LIKE', "%{$data['search']}%")
            ->orWhere('price', 'LIKE', "%{$data['search']}%")
            ->get();
        return response()->json(['erro' => false, 'produtos' => $products]);
    }

    // Função para obter dados dos produtos do banco
    public function getStatistics()
    {
        $usuario = Auth::user();

        $saleCategory = \App\Models\Product::select('category', DB::raw('COALESCE(SUM(sale), 0) as sales'))
            ->where('usuario_id', $usuario->id)
            ->groupBy('category')
            ->pluck('sales', 'category')
            ->toArray();

        $stockCategory = \App\Models\Product::select('category', DB::raw('COALESCE(SUM(stock), 0) as stocks'))
            ->where('usuario_id', $usuario->id)
            ->groupBy('category')
            ->pluck('stocks', 'category')
            ->toArray();

        $activeProducts = \App\Models\Product::where('usuario_id', $usuario->id)
            ->where('status', "active")
            ->count();

        $inactiveProducts = \App\Models\Product::where('usuario_id', $usuario->id)
            ->where('status', "disabled")
            ->count();

        return response()->json(['erro' => false, 'sale' => $saleCategory, 'stock' => $stockCategory, 'active' => $activeProducts, 'disabled' => $inactiveProducts]);
    }
}
