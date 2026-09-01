<?php

namespace App\Http\Controllers;

use App\Models\Ambiente;
use App\Models\Categoria;
use App\Models\Color;
use App\Models\Espacio;
use App\Models\ImagenProducto;
use App\Models\Linea;
use App\Models\Marca;
use App\Models\Metadatos;
use App\Models\Modelo;
use App\Models\Producto;
use App\Models\ProductoAmbiente;
use App\Models\ProductoColor;
use App\Models\ProductoMarca;
use App\Models\ProductoModelo;
use App\Models\SubCategoria;
use App\Models\SubProducto;
use App\Models\Uso;
use Illuminate\Support\Facades\Auth;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class ProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {



        $perPage = $request->input('per_page', default: 10);

        $query = Producto::query()->with(['ambientes', 'imagenes', 'espacio', 'uso', 'linea', 'colores'])->orderBy('order', 'asc');

        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            $query->where('name', 'LIKE', '%' . $searchTerm . '%')->orWhere('code', 'LIKE', '%' . $searchTerm . '%');
        }

        $productos = $query->paginate($perPage);

        $espacios = Espacio::orderBy('order', 'asc')->get();
        $usos = Uso::orderBy('order', 'asc')->get();
        $lineas = Linea::orderBy('order', 'asc')->with('ambientes')->get();
        $ambientes = Ambiente::orderBy('order', 'asc')->get();
        $colores = Color::orderBy('name')->get();


        return Inertia::render('admin/productosAdmin', [
            'productos' => $productos,
            'espacios' => $espacios,
            'usos' => $usos,
            'lineas' => $lineas,
            'ambientes' => $ambientes,
            'colores' => $colores
        ]);
    }

    public function indexVistaPrevia(Request $request)
    {
        $metadatos = Metadatos::where('title', 'Productos')->orderBy('id')->first();

        // Construir query base para productos
        $query = Producto::query();

        // Filtro por categoría (a través de marcas)
        if ($request->filled('espacio')) {
            $query->where('espacio_id', $request->espacio);
        }

        // Filtro por modelo/subcategoría
        if ($request->filled('uso')) {
            $query->where('uso_id', $request->uso);
        }

        if ($request->filled('linea')) {
            $query->where('linea_id', $request->linea);
        }

        // Filtro por código
        if ($request->filled('code')) {
            $query->where('code', 'LIKE', '%' . $request->code . '%');
        }

        // Filtro por código OEM
        if ($request->filled('ambiente')) {
            $query->whereHas('ambientes', function ($q) use ($request) {
                $q->where('ambiente_id', $request->ambiente);
            });
        }

        // Aplicar ordenamiento por defecto
        $query->orderBy('order', 'asc');

        // Ejecutar query con paginación
        $productos = $query
            ->paginate(16)
            ->appends($request->query());

        // Cargar datos para los filtros
        $espacios = Espacio::orderBy('order', 'asc')->get();
        $lineas = Linea::orderBy('order', 'asc')->get();

        // Cargar usos según el espacio seleccionado
        if ($request->filled('espacio')) {
            $usos = Uso::where('espacio_id', $request->espacio)
                ->orderBy('order', 'asc')
                ->get();
        } else {
            $usos = Uso::orderBy('order', 'asc')->get();
        }

        // Cargar ambientes según la línea seleccionada
        if ($request->filled('linea')) {
            $linea = Linea::find($request->linea);
            $ambientes = $linea ? $linea->ambientes()->orderBy('order', 'asc')->get() : collect();
        } else {
            $ambientes = Ambiente::orderBy('order', 'asc')->get();
        }

        return view('productos', [
            'metadatos' => $metadatos,
            'productos' => $productos,
            'espacios' => $espacios,
            'espacio' => $request->espacio,
            'uso' => $request->uso,
            'linea' => $request->linea,
            'code' => $request->code,
            'ambiente' => $request->ambiente,
            'usos' => $usos,
            'lineas' => $lineas,
            'ambientes' => $ambientes,
        ]);
    }

    // Agregar estos métodos para las llamadas AJAX
    public function getUsosByEspacio(Request $request)
    {
        $espacioId = $request->get('espacio_id');

        if ($espacioId) {
            $usos = Uso::where('espacio_id', $espacioId)
                ->orderBy('order', 'asc')
                ->get();
        } else {
            $usos = Uso::orderBy('order', 'asc')->get();
        }

        return response()
            ->json($usos)
            ->header('X-Robots-Tag', 'noindex, nofollow, noarchive');
    }

    public function getAmbientesByLinea(Request $request)
    {
        $lineaId = $request->get('linea_id');

        if ($lineaId) {
            $linea = Linea::find($lineaId);
            $ambientes = $linea ? $linea->ambientes()->orderBy('ambientes.order', 'asc')->get() : collect();
        } else {
            $ambientes = Ambiente::orderBy('order', 'asc')->get();
        }

        return response()
            ->json($ambientes)
            ->header('X-Robots-Tag', 'noindex, nofollow, noarchive');
    }

    public function show($codigo, Request $request)
    {
        $producto = Producto::with(['imagenes', 'colores', 'linea'])
            ->where('code', $codigo)
            ->firstOrFail();
    
        Log::info('Mostrando producto con código: ', [$producto]);
    
        $productosRelacionados = Producto::where('id', '!=', $producto->id)
            ->where('linea_id', $producto->linea_id)
            ->orderBy('order', 'asc')
            ->limit(4)
            ->get();
    
        return view('producto', [
            'producto' => $producto,
            'productosRelacionados' => $productosRelacionados,
        ]);
    }
    
    public function indexPrivada(Request $request)
    {


        $perPage = $request->input('per_page', 10);

        $qty = $request->input('qty', 1); // Valor por defecto para qty
        $carrito = Cart::content();

        $query = Producto::with(['imagenes', 'marca', 'modelo', 'precio', 'categoria'])->orderBy('order', 'asc');

        if ($request->filled('tipo')) {
            $query->where('categoria_id', $request->tipo);
        }

        // Filtro por modelo/subcategoría
        if ($request->filled('marca')) {
            $query->where('marca_id', $request->marca);
        }

        if ($request->filled('modelo')) {
            $query->where('modelo_id', $request->modelo);
        }

        // Filtro por código
        if ($request->filled('code')) {
            $query->where('code', 'LIKE', '%' . $request->code . '%');
        }

        // Filtro por código OEM
        if ($request->filled('code_sr')) {
            $query->where('code_sr', 'LIKE', '%' . $request->code_sr . '%');
        }



        $productos = $query->paginate(perPage: $perPage);

        // Modificar los productos para agregar rowId y qty del carrito
        $productos->getCollection()->transform(function ($producto) use ($carrito, $qty) {
            // Buscar el item del carrito que corresponde a este producto
            $itemCarrito = $carrito->where('id', $producto->id)->first();

            $tieneOfertaVigente = $producto->ofertas()
                ->where('user_id', Auth::id())
                ->where('fecha_fin', '>', now())
                ->exists();

            if ($itemCarrito) {
                $producto->rowId = $itemCarrito ? $itemCarrito->rowId : null;
                $producto->qty = $itemCarrito ? $itemCarrito->qty : null;
                $producto->subtotal =  $itemCarrito ? $itemCarrito->price * ($itemCarrito->qty ?? 1) : $producto->precio->precio;
            } else {
                $producto->rowId = null;
                $producto->qty = $qty; // Asignar qty por defecto si no está en el carrito
                $producto->subtotal = $producto->precio ? $producto->precio->precio * ($producto->qty ?? 1) : 0; // Asignar precio base si no está en el carrito
            }

            if ($tieneOfertaVigente) {
                $producto->oferta = true;
            }

            // Aquí puedes agregar más lógica si es necesario, como calcular el subtotal
            // Agregar el rowId y qty al producto
            // Calcular subtotal

            return $producto;
        });
        # si el usuario es vendedor

        $categorias = Categoria::orderBy('order', 'asc')->get();
        $marcas = Marca::orderBy('order', 'asc')->get();
        $modelos = Modelo::orderBy('order', 'asc')->get();
        $userId = Auth::id();

        $productosOferta = Producto::whereHas('ofertas', function ($query) use ($userId) {
            $query->where('user_id', $userId)
                ->where('fecha_fin', '>', now());
        })
            ->with([
                'imagenes',
                'marca',
                'modelo',
                'precio',
                'ofertas' => function ($query) use ($userId) {
                    $query->where('user_id', $userId)
                        ->where('fecha_fin', '>', now());
                }
            ])
            ->orderBy('order', 'asc')
            ->get();

        return inertia('privada/productosPrivada', [
            'productos' => $productos,
            'categorias' => $categorias,
            'productosOferta' => $productosOferta,
            'tipo' => $request->tipo,
            'marca' => $request->marca,
            'modelo' => $request->modelo,
            'code' => $request->code,
            'code_sr' => $request->code_sr,
            'marcas' => $marcas,
            'modelos' => $modelos,

        ]);
    }

    /* public function indexInicio(Request $request, $id)
    {
        $marcas = Marca::select('id', 'name', 'order')->orderBy('order', 'asc')->get();

        $categorias = Categoria::select('id', 'name', 'order')
            ->orderBy('order', 'asc')
            ->get();
        $metadatos = Metadatos::where('title', 'Productos')->first();
        if ($request->has('marca') && !empty($request->marca)) {
            $productos = Producto::where('categoria_id', $id)->whereHas('subproductos')->whereHas('imagenes')->where('marca_id', $request->marca)->with('marca', 'imagenes')->orderBy('order', 'asc')->get();
        } else {
            $productos = Producto::where('categoria_id', $id)->whereHas('subproductos')->whereHas('imagenes')->with('marca', 'imagenes')->orderBy('order', 'asc')->get();
        }
        $subproductos = SubProducto::orderBy('order', 'asc')->get();

        return Inertia::render('productos', [
            'productos' => $productos,
            'categorias' => $categorias,
            'marcas' => $marcas,
            'metadatos' => $metadatos,
            'id' => $id,
            'marca_id' => $request->marca,
            'subproductos' => $subproductos,

        ]);
    } */

    public function indexInicio(Request $request, $id)
    {


        $categorias = Categoria::select('id', 'name', 'order')
            ->orderBy('order', 'asc')
            ->get();

        $metadatos = Metadatos::where('title', 'Productos')->first();

        $query = Producto::where('categoria_id', $id)

            ->orderBy('order', 'asc');



        $productos = $query->paginate(12)->withQueryString(); // 12 por página, mantiene filtros

        // Opcional: solo subproductos de productos actuales (más eficiente)
        $productoIds = $productos->pluck('id');
        $subproductos = SubProducto::whereIn('producto_id', $productoIds)
            ->orderBy('order', 'asc')
            ->get();

        return Inertia::render('productos', [
            'productos' => $productos,
            'categorias' => $categorias,

            'metadatos' => $metadatos,
            'id' => $id,

            'subproductos' => $subproductos,
        ]);
    }

    public function imagenesProducto()
    {
        $fotos = Storage::disk('public')->files('repuestos');

        foreach ($fotos as $foto) {
            $path = pathinfo(basename($foto), PATHINFO_FILENAME);

            $producto = Producto::where('code', $path)->first();
            if (!$producto) {
                continue; // Skip if the product is not found
            }
            $url = Storage::url($foto);
            ImagenProducto::create([
                'producto_id' => $producto->id,
                'image' => $url,
            ]);
        }
    }







    public function SearchProducts(Request $request)
    {
        $query = Producto::query();

        // Aplicar filtros solo si existen
        if ($request->filled('categoria')) {
            $query->where('categoria_id', $request->categoria);
        }



        if ($request->filled('codigo')) {
            $query->where('code', 'LIKE', '%' . $request->codigo . '%');
        }

        $productos = $query->with(['categoria:id,name', 'imagenes'])
            ->get();

        $categorias = Categoria::select('id', 'name', 'order')->orderBy('order', 'asc')->get();


        return Inertia::render('productos/productoSearch', [
            'productos' => $productos, // Cambié 'producto' a 'productos' (plural)
            'categorias' => $categorias,

        ]);
    }

    public function fixImagePath()
    {
        # Quitar /storage/ de las rutas de las imágenes
        $imagenes = ImagenProducto::all();
        foreach ($imagenes as $imagen) {
            if (strpos($imagen->image, '/storage/') === 0) {
                $imagen->image = str_replace('/storage/', '', $imagen->image);
                $imagen->save();
            }
        }

        return response()->json(['message' => 'Rutas de imágenes actualizadas correctamente.']);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {


        $data = $request->validate([
            // Validaciones del producto
            'order' => 'nullable|sometimes|max:255',
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255',
            'medidas' => 'nullable|string|max:255',
            'origen' => 'nullable|string|max:255',
            'lampara' => 'nullable|string|max:255',
            'certificado' => 'nullable|sometimes|file',
            'instructivo' => 'nullable|sometimes|file',
            'espacio_id' => 'nullable|exists:espacios,id',
            'uso_id' => 'nullable|exists:usos,id',
            'linea_id' => 'nullable|exists:lineas,id',
            'images' => 'nullable|array|min:1',
            'images.*' => 'required|file|image',
            'ambientes' => 'nullable|array',
            'ambientes.*' => 'integer|exists:ambientes,id',
            'colores' => 'nullable|array',
            'colores.*' => 'integer|exists:colors,id',
        ]);



        try {
            return DB::transaction(function () use ($request, $data) {
                // Crear el producto primero
                $producto = Producto::create([
                    'order' => $data['order'] ?? 'zzz',
                    'name' => $data['name'],
                    'code' => $data['code'],
                    'origen' => $data['origen'] ?? null,
                    'lampara' => $data['lampara'] ?? null,
                    'medidas' => $data['medidas'] ?? null,
                    'certificado' => $request->hasFile('certificado') ? $request->file('certificado')->store('documents', 'public') : null,
                    'instructivo' => $request->hasFile('instructivo') ? $request->file('instructivo')->store('documents', 'public') : null,
                    'espacio_id' => $data['espacio_id'] ?? null,
                    'uso_id' => $data['uso_id'] ?? null,
                    'linea_id' => $data['linea_id'] ?? null,

                ]);

                $createdImages = [];

                // Procesar imágenes si existen
                if ($request->hasFile(key: 'images')) {
                    foreach ($request->file('images') as $image) {
                        // Subir cada imagen
                        $imagePath = $image->store('images', 'public');

                        // Crear registro para cada imagen usando el ID del producto recién creado
                        $imageRecord = ImagenProducto::create([
                            'producto_id' => $producto->id,
                            'order' => $data['order'] ?? null,
                            'image' => $imagePath,
                        ]);

                        $createdImages[] = $imageRecord;
                    }
                }

                if ($request->has('colores')) {
                    foreach ($data['colores'] as $colorId) {
                        ProductoColor::create([
                            'producto_id' => $producto->id,
                            'color_id' => $colorId,
                        ]);
                    }
                }

                // guardar certificado e instructivo si existen

                if ($request->hasFile('certificado')) {
                    $producto->certificado = $request->file('certificado')->store('images', 'public');
                    $producto->save();
                }

                if ($request->hasFile('instructivo')) {
                    $producto->instructivo = $request->file('instructivo')->store('images', 'public');
                    $producto->save();
                }

                if ($request->has('ambientes')) {
                    foreach ($data['ambientes'] as $ambienteId) {
                        ProductoAmbiente::create([
                            'producto_id' => $producto->id,
                            'ambiente_id' => $ambienteId,
                        ]);
                    }
                }
            });
        } catch (\Exception $e) {
            return back()->withErrors(['message' => 'Error al crear el producto: ' . $e->getMessage()])->withInput();
        }
    }



    public function update(Request $request)
    {
        $data = $request->validate([
            // Validaciones del producto

            'order' => 'nullable|sometimes|max:255',
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255',
            'medidas' => 'nullable|string|max:255',
            'origen' => 'nullable|string|max:255',
            'lampara' => 'nullable|string|max:255',
            'certificado' => 'nullable|sometimes|file',
            'instructivo' => 'nullable|sometimes|file',
            'espacio_id' => 'nullable|exists:espacios,id',
            'uso_id' => 'nullable|exists:usos,id',
            'linea_id' => 'nullable|exists:lineas,id',
            'ambientes' => 'nullable|array',
            'ambientes.*' => 'integer|exists:ambientes,id',
            'colores' => 'nullable|array',
            'colores.*' => 'integer|exists:colors,id',

            // Validaciones de las imágenes (opcionales)
            'images' => 'nullable|array|min:1',
            'images.*' => 'required|file|image',
            // Para eliminar imágenes existentes
            'delete_images' => 'nullable|array',
            'delete_images.*' => 'integer|exists:imagen_productos,id',
        ]);

        try {
            return DB::transaction(function () use ($request, $data) {
                // Buscar el producto
                $producto = Producto::findOrFail($request->id);

                // Actualizar los datos del producto
                $producto->update([
                    'order' => $data['order'] ?? 'zzz',
                    'name' => $data['name'],
                    'code' => $data['code'],
                    'medidas' => $data['medidas'] ?? null,
                    'origen' => $data['origen'] ?? null,
                    'lampara' => $data['lampara'] ?? null,
                    'certificado' => $request->hasFile('certificado') ? $request->file('certificado')->store('documents', 'public') : $producto->certificado,
                    'instructivo' => $request->hasFile('instructivo') ? $request->file('instructivo')->store('documents', 'public') : $producto->instructivo,
                    'espacio_id' => $data['espacio_id'] ?? null,
                    'uso_id' => $data['uso_id'] ?? null,
                    'linea_id' => $data['linea_id'] ?? null,
                    'ambiente_id' => $data['ambiente_id'] ?? null,
                ]);

                if ($request->hasFile('certificado')) {
                    // Eliminar el certificado anterior si existe
                    if ($producto->certificado && Storage::disk('public')->exists($producto->certificado)) {
                        Storage::disk('public')->delete($producto->certificado);
                    }
                    $producto->certificado = $request->file('certificado')->store('images', 'public');
                    $producto->save();
                }

                if ($request->hasFile('instructivo')) {
                    // Eliminar el instructivo anterior si existe
                    if ($producto->instructivo && Storage::disk('public')->exists($producto->instructivo)) {
                        Storage::disk('public')->delete($producto->instructivo);
                    }
                    $producto->instructivo = $request->file('instructivo')->store('images', 'public');
                    $producto->save();
                }

                if ($request->has('ambientes')) {
                    // Eliminar relaciones existentes

                    foreach ($data['ambientes'] as $ambienteId) {
                        ProductoAmbiente::updateOrCreate([
                            'producto_id' => $producto->id,
                            'ambiente_id' => $ambienteId,
                        ], [
                            'producto_id' => $producto->id,
                            'ambiente_id' => $ambienteId,
                        ]);
                    }
                }

                if ($request->has('colores')) {
                    // Eliminar relaciones existentes

                    foreach ($data['colores'] as $colorId) {
                        ProductoColor::updateOrCreate([
                            'producto_id' => $producto->id,
                            'color_id' => $colorId,
                        ], [
                            'producto_id' => $producto->id,
                            'color_id' => $colorId,
                        ]);
                    }
                }



                if ($request->has('images_to_delete')) {
                    foreach ($request->images_to_delete as $imageId) {
                        $image = ImagenProducto::find($imageId);
                        if ($image) {
                            // Eliminar archivo del storage
                            Storage::delete($image->image);
                            // Eliminar registro de la base de datos
                            $image->delete();
                        }
                    }
                }

                // Agregar nuevas imágenes
                if ($request->hasFile('new_images')) {
                    foreach ($request->file('new_images') as $image) {
                        $path = $image->store('images', 'public');

                        ImagenProducto::create([
                            'producto_id' => $producto->id,
                            'image' => $path,
                        ]);
                    }
                }

                // Actualizar otros campos del producto


                // Eliminar imágenes seleccionadas si se especificaron
                if ($request->has('delete_images')) {
                    $imagesToDelete = ImagenProducto::where('producto_id', $producto->id)
                        ->whereIn('id', $data['delete_images'])
                        ->get();

                    foreach ($imagesToDelete as $imageRecord) {
                        // Eliminar archivo físico
                        if (Storage::disk('public')->exists($imageRecord->image)) {
                            Storage::disk('public')->delete($imageRecord->image);
                        }
                        // Eliminar registro de la base de datos
                        $imageRecord->delete();
                    }
                }

                // Procesar nuevas imágenes si existen
                if ($request->hasFile('images')) {
                    foreach ($request->file('images') as $image) {
                        // Subir cada imagen
                        $imagePath = $image->store('images', 'public');

                        // Crear registro para cada imagen
                        ImagenProducto::create([
                            'producto_id' => $producto->id,
                            'order' => $data['order'] ?? null,
                            'image' => $imagePath,
                        ]);
                    }
                }

                // Actualizar relaciones con modelos

            });
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al actualizar el producto',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {

        $id = $request->id;
        try {
            return DB::transaction(function () use ($id) {
                // Buscar el producto
                $producto = Producto::findOrFail($id);

                // Eliminar todas las imágenes asociadas
                $imagenes = ImagenProducto::where('producto_id', $producto->id)->get();
                foreach ($imagenes as $imagen) {
                    // Eliminar archivo físico del storage
                    if (Storage::disk('public')->exists($imagen->image)) {
                        Storage::disk('public')->delete($imagen->image);
                    }
                    // Eliminar registro de la base de datos
                    $imagen->delete();
                }


                // Eliminar relaciones con ambientes
                ProductoAmbiente::where('producto_id', $producto->id)->delete();


                // Eliminar el producto
                $producto->delete();
            });
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al eliminar el producto',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function cambiarDestacado(Request $request)
    {
        $producto = Producto::findOrFail($request->id);
        $producto->destacado = !$producto->destacado; // Cambiar el estado de destacado
        $producto->save();
    }

    public function productoszonaprivada(Request $request)
    {
        return inertia('admin/zonaprivadaProductosAdmin');
    }

    public function cambiarOferta(Request $request)
    {
        $producto = Producto::findOrFail($request->id);
        $producto->oferta = !$producto->oferta;
        $producto->save();
    }

    public function handleQR($code)
    {

        $producto = Producto::where('code', $code)->first();

        if (!$producto && $code != "adm") {
            return redirect('/productos');
        } else if ($code == "adm") {
            if (Auth::guard('admin')->check()) {
                return redirect('/admin/dashboard');
            }
            return Inertia::render('admin/login');
        }




        if (Auth::check()) {

            $tieneOfertaVigente = $producto->ofertas()
                ->where('user_id', Auth::id())
                ->where('fecha_fin', '>', now())
                ->exists();

            Cart::add(
                $producto->id,
                $producto->name,
                $producto->unidad_pack ?? 1,
                $tieneOfertaVigente
                    ? $producto->precio->precio * (1 - $producto->descuento_oferta / 100)
                    : $producto->precio->precio,
                0
            );

            // Guardar en base de datos si hay usuario logueado
            if (Auth::check() && !session('cliente_seleccionado')) {
                if (Cart::count() < 0) {
                    Cart::store(Auth::id());
                }
            } else {
                Cart::store(session('cliente_seleccionado')->id);
            }

            return redirect('/privada/carrito');
        } else {
            return redirect('/p/' . $producto->code);
        }
    }
}
