<?php

namespace App\Http\Controllers;

use App\Brand;
use App\Chollo;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    public function poolSaveSingle(Request $request)
    {
        $word = trim($request->word);
        $words = explode(' ', $word);
        $existing = Brand::where('value', $words[0])->first();
        $chollo = Chollo::find($request->id);
        $chollo->timestamps = false;
        if (!is_null($existing)) {
            $chollo->brand_id = $existing->id;
            $chollo->save();
            $existing2 = DB::table('brands_words')->where('word', $word)->where('brand_id', $existing->id)->first();
            if (!is_null($existing2)) {
                return 'Esta palabra ya existe en el pool de la  marca '.$existing->value;
            } else {
                DB::table('brands_words')->insert(['word' => $word, 'brand_id' => $existing->id]);

                return 'Palabra '.$word.' agregada a pool de marca '.$existing->value;
            }
        } else {
            $brand = new Brand();
            $brand->value = $word;
            $brand->save();
            $chollo->brand_id = $brand->id;
            $chollo->save();

            return 'Nueva marca '.$word.' creada';
        }
    }

    public function poolSave(Request $request, $id)
    {
        $brand = Brand::findOrFail($id);
        DB::table('brands_words')->where('brand_id', $id)->delete();
        $words = explode(',', $request->words);
        $data = [];
        foreach ($words as $key => $value) {
            array_push($data, ['word' => trim($value), 'brand_id' => $id]);
        }
        DB::table('brands_words')->insert($data);

        return redirect('/panel/brands')->with(['message' => 'Pool de marca '.$brand->name.' actualizado', 'alert-type' => 'success']);
    }

    public function pool($id)
    {
        $brand = Brand::findOrFail($id);
        $words = DB::table('brands_words')->where('brand_id', $id)->get();

        return view('brands.pool', compact('brand', 'words'));
    }

    public function index()
    {
        setlocale(LC_TIME, 'Spanish');

        $brands = Brand::get();
        $brandsQuantity = Brand::get()->count();
        $letters = range('A', 'Z');
        $number = range(0, 9);
        $data = [];
        $options = [];
        foreach ($letters as $key => $value) {
            $data[$value] = [];
        }
        foreach ($number as $key => $value) {
            $data[$value] = [];
        }
        $urls = Chollo::orderBy('id', 'DESC')->where('brand_id', '!=', null)->select('brand_id', 'id')->distinct()->limit(10)->pluck('brand_id')->toArray();

        $popular1 = Brand::whereIn('id', $urls)->get();

        foreach ($brands as $key => $value) {
            $name = ucfirst($value->value);
            $first = substr($name, 0, 1);
            array_push($data[$first], $value);
            if (!in_array($value->value, $options)) {
                array_push($options, $value->value);
            }
        }

        sort($options);
        $options = json_encode($options);
        $items = json_encode(array_filter($data));
        $breadcrumbs = [['name' => 'Inicio', 'url' => '/'], ['name' => 'Marcas', 'url' => '#']];
        $filter = null;
        $cupons = 0;

        $now = Carbon::now();
        $month = ucfirst($now->formatLocalized('%B'));
        $top5 = '';

        for ($i = 0; $i <= 4; ++$i) {
            if ($i != 4) {
                $top5 = $top5.$popular1[$i]['value'].',';
            } else {
                $top5 = $top5.$popular1[$i]['value'];
            }
        }

        $title = 'Lista de marcas con ofertas y cupones disponibles - '.$month;

        $description = 'Tenemos mas de '.$brandsQuantity.' marcas con ofertas y chollos, aprovecha los grandes descuentos en: '.$top5.', actualizadas en '.$month.' de 2021';
        $random = Brand::inRandomOrder()->limit(5)->get();

        foreach ($random as $key => $value) {
            $description .= $value->name.' ';
        }

        $description = trim($description);

        return view('brands.index', compact('items', 'popular1', 'breadcrumbs', 'filter', 'options', 'cupons', 'title', 'description'));
    }
}
