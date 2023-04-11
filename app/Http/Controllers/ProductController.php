<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Stmt\TryCatch;

class ProductController extends Controller
{
    public function save(Request $request)
    {
        $data = $request->only(["item", "category", "status", "sale", "stock", "price"]);

        //Vericar se campos estão preenchidos
        if (empty($data['item']) || empty($data['category']) || empty($data['status']) || empty($data['sale']) || empty($data['stock']) || empty($data['price'])) {
            return response()->json(['erro' => true, 'mensagem' => 'Campos não preenchidos.']);
        }
        //ID do usúario logado
        $usuario_id = Auth::id();

        // Salvar no banco
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

    public function usuario()
    {
        // Buscar nome do usuário logado
        $usuario = Auth::user();
        $name_user = $usuario->name;
        return response()->json(['erro' => false, 'name' => $name_user]);
    }

    public function products()
    {
        // Buscar todos os produtos
        $userId = auth()->user()->id;
        $products = \App\Models\Product::where('usuario_id', $userId)
            ->select(['id', 'item', 'category', 'status', 'sale', 'stock', 'price'])
            ->get();
        return response()->json(['erro' => false, 'produtos' => $products]);
    }

    public function product($productId)
    {
        // Retornar produto do usuário clicado
        $userId = auth()->user()->id;
        $product = \App\Models\Product::where('usuario_id', $userId)
            ->where('id', $productId)
            ->first();
        return response()->json(['erro' => false, 'produto' => $product]);
    }

    public function update($productId, Request $request)
    {
        $data = $request->only(["item", "category", "status", "sale", "stock", "price"]);

        try {
            $product = \App\Models\Product::find($productId);
            // Atualizando os campos do produto
            $product->item = $request->input('item');
            $product->category = $request->input('category');
            $product->status = $request->input('status');
            $product->sale = $request->input('sale');
            $product->stock = $request->input('stock');
            $product->price = $request->input('price');

            // Salvar o produto atualizado no banco de dados
            $product->save();

            return response()->json(['erro' => false]);
        } catch (\Exception $e) {
            return response()->json(['erro' => true]);
        }
    }

    public function delete($productId)
    {
        // Deletando produto com ID que vem da requisição
        $produto = \App\Models\Product::find($productId);

        if ($produto) {
            $produto->delete();
            return response()->json(['erro' => false]);
        } else {
            return response()->json(['erro' => true]);
        }
    }

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
}
