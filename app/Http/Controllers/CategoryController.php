<?php

namespace App\Http\Controllers;

use App\Category;
use App\Chollo;
use Auth;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
ini_set('max_execution_time', 1800);

class CategoryController extends Controller
{
    public $breadcrumbs = [];

    public function getCategory(Request $request)
    {
        $name = $request->name;
        $cat = $this->catItem($name);
        if (!is_null($cat)) {
            $this->getMailCat($cat);
            $bread = $this->breadcrumbs;
            $maincat = end($bread);
            $seccat = $cat->id;

            return [$maincat['id'], $seccat];
        }

        return null;
    }

    private function getMailCat($category)
    {
        if (!is_null($category)) {
            array_push($this->breadcrumbs, ['id' => $category->id, 'name' => $category->name, 'url' => '/categoria/'.$category->slug]);
            if (!is_null($category->parent_id)) {
                $category = Category::where('id', $category->parent_id)->first();
                $this->getMailCat($category);
            }
        }
    }

    private function catItem($name)
    {
        $ignore = DB::table('ignore_words')->pluck('word')->toArray();
        $pool = DB::table('category_words')->pluck('word')->toArray();
        $find = ['í', 'á', 'ó', 'é', 'ú'];
        $replace = ['i', 'a', 'o', 'e', 'u'];
        $name = strtolower(str_replace($find, $replace, $name));
        $name = preg_replace('/[^A-Za-z0-9ñ ]/', '', $name);
        $words = explode(' ', $name);
        $cleaned = [];
        foreach ($words as $key => $value) {
            if ((!is_numeric($value) && strlen($value) > 3 && !in_array($value, $ignore)) || (in_array($value, $pool) && !in_array($value, $ignore))) {
                array_push($cleaned, $value);
            }
        }
        if (sizeof($cleaned) > 0) {
            foreach ($cleaned as $key => $value) {
                $cat = Category::where('name', 'like', '%'.$value.'%')->first();
                if (!is_null($cat)) {
                    $next_index = $key + 1;
                    if (isset($cleaned[$next_index])) {
                        $next_word = $cleaned[$next_index];
                        $find = '%'.$value.' '.$next_word.'%';
                        $better = Category::where('name', 'like', '%'.$find.'%')->first();
                        if (!is_null($better)) {
                            return $better;
                        }
                    }

                    return $cat;
                }
            }
            foreach ($cleaned as $key => $value) {
                $category = DB::table('category_words')->where('word', $value)->first();
                if (!is_null($category)) {
                    $cat = Category::find($category->category_id);

                    return $cat;
                }
            }
        }

        return null;
    }

    public function index()
    {
        setlocale(LC_TIME, 'Spanish');
        $count_categories = Category::get()->count();
        $categories = Category::whereNull('parent_id')->select('id', 'name', 'slug', 'image')->get();
        $categories_son_list = [];
        foreach ($categories as $cat) {
            $count = Chollo::where('category_id', $cat['id'])->count();
            $categories_son = Category::where('parent_id', $cat['id'])->select('id')->get()->toArray();
            $count2 = Chollo::whereIn('category_id', $categories_son)->count();
            $cat['count'] = $count + $count2;
            $categories_son_list_in = Category::where('parent_id', $cat['id'])->select('id', 'name', 'slug', 'image')->get();
            foreach ($categories_son_list_in as $catson) {
                $count = Chollo::where('category_id', $catson['id'])->count();
                $catson['count'] = $count;
            }
            if (count($categories_son_list_in) < 7 && count($categories_son_list_in) !== 0) {
                $categories_son_list_in_onlyid = Category::whereIn('parent_id', $categories_son)->select('id', 'name', 'slug', 'image')->get();
                $ind = 0;
                if (count($categories_son_list_in_onlyid) > 0) {
                    while (count($categories_son_list_in) < 7) {
                        $categories_son_list_in[count($categories_son_list_in)] = $categories_son_list_in_onlyid[$ind];
                        ++$ind;
                    }
                }
            }
            $cat['sons'] = $categories_son_list_in;
        }
        $breadcrumbs = [['name' => 'Inicio', 'url' => '/'], ['name' => 'Categorias', 'url' => '#']];

        $now = Carbon::now();
        $month = ucfirst($now->formatLocalized('%B'));

        $title = 'Listado de categorias ofertas, chollos y descuentos - Actualizas - '.$month;
        $description = 'Encuentra y comparte chollos, ofertas y descuentos gratis, aquí encontraras el listado de nuestras '.$count_categories.' categorías y 
        encontraras chollos de '.$categories[0]['name'].',  chollos de '.$categories[1]['name'].',  chollos de '.$categories[2]['name'].',  chollos de '.$categories[3]['name'].'. ⌚ actualizados en tiempo real.';

        $categories = json_encode($categories);

        return view('categories.index', compact('categories', 'breadcrumbs', 'count_categories', 'title', 'description'));
    }

    public function poolSaveSingle(Request $request)
    {
        $word = nicename(trim($request->word));
        $existing = DB::table('category_words')->where('word', $word)->where('category_id', $request->category_id)->first();
        if (!is_null($existing)) {
            return 'Palabra ya existe en el pool';
        }
        DB::table('category_words')->insert(['word' => $word, 'category_id' => $request->category_id]);

        return $request->category_id;
    }

    public function fixproductimages(){


        $chollos = Chollo::all();


        foreach ($chollos as $chollo){


            $url1 = $chollo->image;
            $url2 = $chollo->image_small;


            $result = strpos($url1,"https://soydechollos.com/");
            if($result === false) {

            }else{
                $url1 = str_replace("https://soydechollos.com/", env('APP_URL')."public/", $url1);
                $url2 = str_replace("https://soydechollos.com/", env('APP_URL')."public/", $url2);

                $chollo->image = $url1;
                $chollo->image_small = $url2;
                $chollo->save();
            }

        }

        echo "Success";
    }

    public function poolSave(Request $request, $id)
    {
        $category = Category::findOrFail($id);
        DB::table('category_words')->where('category_id', $id)->delete();
        $words = explode(',', $request->words);
        $data = [];
        foreach ($words as $key => $value) {
            array_push($data, ['word' => trim($value), 'category_id' => $id]);
        }
        DB::table('category_words')->insert($data);

        return redirect('/panel/categories')->with(['message' => 'Pool de categoria '.$category->name.' actualizado', 'alert-type' => 'success']);
    }

    public function pool($id)
    {
        $category = Category::findOrFail($id);
        $words = DB::table('category_words')->where('category_id', $id)->get();

        return view('categories.pool', compact('category', 'words'));
    }

    public function ignorePool()
    {
        if (Auth::user()->role_id != 4 && Auth::user()->role_id != 2) {
            abort(404);
        }
        $words = DB::table('ignore_words')->get();

        return view('categories.ignore', compact('words'));
    }

    public function ignorePoolSave(Request $request)
    {
        DB::table('ignore_words')->delete();
        $words = explode(',', $request->words);
        $data = [];
        foreach ($words as $key => $value) {
            array_push($data, ['word' => trim($value)]);
        }
        DB::table('ignore_words')->insert($data);

        return back()->with(['message' => 'Pool de palabras actualizado', 'alert-type' => 'success']);
    }

    public function update(Request $request)
    {
        $item = Chollo::findOrFail($request->item_id);
        $item->category_id = $request->category_id;
        $item->timestamps = false;
        $item->save();

        return $item->id;
    }

    public function consoleCategory(Request $request, $id)
    {
        $category = Category::findOrFail($id);
        $update = false;
        if (isset($request->update) && $request->update == 'true') {
            $update = true;
        }
        $items = Chollo::where('category_id', $id)->get();
        // dd($items);
        echo $items->count().' resultados en <b>'.$category->name.'</b>';
        echo '<br>';

        foreach ($items as $key => $value) {
            $cat = $this->catItem($value->name);
            if (!is_null($cat)) {
                echo $value->id.' -> '.$value->name.'----->'.$cat->name;
                echo '<br>';
                echo '<br>';
                if ($update) {
                    $value->timestamps;
                    $value->category_id = $cat->id;
                    $value->save();
                }
            } else {
                echo $value->id.' -> '.$value->name.'-----> NINGUNA CATEGORIA ENCONTRADA';
                echo '<br>';
                echo '<br>';
            }
        }
    }
}
