<?php

namespace App\Http\Controllers;

use App\Category;
use App\Chollo;
use App\Discount;
use App\Keyword;
use App\Search;
use App\Store;
use App\Traits\CommonHelpers;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class StoreController extends Controller
{
    use CommonHelpers;

    public function goToCatById($id)
    {
        $c = Category::find($id);
        if (!is_null($c)) {
            return redirect('/'.$c->slug);
        }
        abort(404);
    }

    public function goByName($name)
    {
        $s = Store::where('name', strtolower($name))->first();
        if (is_null($s)) {
            abort(404);
        }

        return redirect(store_url($s));
    }

    public function redirect(Request $request)
    {
        if (!empty($request->redirect)) {
            $site = $this->getSiteName($request->redirect);
            $link = $site[1];

            $s = Store::where('name', $site[0])->first();

            if (!is_null($s)) {
                if (!empty($s->referal)) {
                    if ($s->type == 1) {
                        if (substr($link, -1) !== '?') {
                            $link .= '?';
                        }
                        $link .= $s->referal;
                    }
                    if ($s->type == 2) {
                        $link = str_replace('STORE_URL_HERE', $link, $s->referal);
                    }
                    // tradetracker
                    if ($s->type == 3) {
                        $parsed = parse_url($link);
                        $path = urlencode($parsed['path']);
                        $link = str_replace('STORE_URL_HERE', $path, $s->referal);
                    }
                }
            }

            return redirect($link);
        } else {
            return abort(404);
        }
    }

    public function goToAmazon(Request $request)
    {
        if (isset($request->url)) {
            $site = $this->getSiteName($request->url);
            $link = $site[1].'/';
            $s = Store::where('website', $site[2])->first();
            if (!is_null($s)) {
                if (!empty($s->referal)) {
                    $link .= $s->referal;
                }
            }

            return redirect($link);
        }
    }

    public function goToStore($id)
    {
        $c = Chollo::findOrFail($id);

        $link = $c->link;
        if (empty($link)) {
            return redirect($c->slug);
        }

        $s = Store::find($c->store_id);
        if (!is_null($s)) {
            if (empty($s->referal)) {
                $s = Store::where('name', $s->name)->first();
                if (is_null($s)) {
                    return redirect($link);
                }
            }

            if (!empty($s->referal)) {
                if ($s->type == 1) {
                    if (substr($link, -1) !== '?') {
                        $link .= '?';
                    }
                    $link .= $s->referal;
                }
                if ($s->type == 2) {
                    $link = str_replace('STORE_URL_HERE', $link, $s->referal);
                }
            }
        }

        // Log::info('test 4');

        return redirect($link);
    }

    public function goToStoreD($id)
    {
        $c = Discount::findOrFail($id);

        $link = $c->url;
        if (empty($link)) {
            return redirect('/');
        }

        if (!empty($c->store_id)) {
            $s = Store::where('id', $c->store_id)->first();
            if (!is_null($s)) {
                if (!empty($s->referal)) {
                    if ($s->type == 1) {
                        $link .= $s->referal;
                    } else {
                        $link = str_replace('STORE_URL_HERE', $link, $s->referal);
                    }
                }
            }
        }

        return redirect($link);
    }

    public function index()
    {
        setlocale(LC_TIME, 'Spanish');

        $stores = Store::get();
        $letters = range('A', 'Z');
        $data = [];
        $options = [];
        foreach ($letters as $key => $value) {
            $data[$value] = [];
        }
        $urls = Chollo::orderBy('id', 'DESC')->where('store_id', '!=', null)->select('store_id', 'id')->distinct()->limit(10)->pluck('store_id')->toArray();

        $popular1 = Store::whereIn('id', $urls)->get();

        foreach ($stores as $key => $value) {
            $name = ucfirst($value->name);
            $first = substr($name, 0, 1);
            array_push($data[$first], $value);
            if (!in_array($value->slug, $options)) {
                array_push($options, ['slug' => $value->slug, 'name' => $value->visible_name]);
            }
        }

        sort($options);
        $options = json_encode($options);
        $items = json_encode(array_filter($data));
        $breadcrumbs = [['name' => 'Inicio', 'url' => '/'], ['name' => 'Tiendas', 'url' => '#']];
        $filter = null;
        $cupons = 0;

        $now = Carbon::now();
        $month = ucfirst($now->formatLocalized('%B'));

        $title = 'Lista de tiendas con ofertas y cupones disponibles - '.$month;
        $description = 'Ofertas con grandes descuentos en miles de tiendas para '.$month.' actual: ';
        $random = Store::inRandomOrder()->limit(5)->get();

        foreach ($random as $key => $value) {
            $description .= $value->name.' ';
        }

        $description = trim($description);

        return view('stores.index', compact('items', 'popular1', 'breadcrumbs', 'filter', 'options', 'cupons', 'title', 'description'));
    }

    public function tags(Request $request)
    {
        $query = Keyword::orderBy('keyword')->select('keyword', 'slug');

        if (!empty($request->l)) {
            $query->where('keyword', 'LIKE', $request->l.'%');
        }
        if (!empty($request->q)) {
            $query->where('keyword', 'LIKE', '%'.$request->q.'%');
        }
        $query->distinct('keyword');
        // $q = $query->get();
        $q = $query->paginate(150);

        $q->setPath($request->fullUrl());

        // SEO
        $title = 'Etiquetas de Avisa Chollos';
        $chunk = array_chunk($q->items(), 10);
        if (empty($chunk)) {
            abort(404);
        }
        $chunk = $chunk[0];
        $words = [0 => 'Comprar', 1 => 'ofertas', 2 => 'descuento', 3 => 'chollo', 4 => 'online'];
        $description = '';
        foreach ($chunk as $key => $value) {
            if (isset($words[$key])) {
                $description .= $words[$key].' ';
            }
            $description .= $value['keyword'].', ';
        }

        $items = json_encode($q->items());
        $filter = null;
        $breadcrumbs = [['name' => 'Inicio', 'url' => '/'], ['name' => 'Etiquetas', 'url' => '#']];

        return view('stores.tags', compact('items', 'filter', 'breadcrumbs', 'q', 'title', 'description'));
    }

    public function search(Request $request)
    {
        $query = Search::where('count', '>', 4);
        if (!empty($request->l)) {
            $query->where('string', 'LIKE', $request->l.'%');
        }
        if (!empty($request->q)) {
            $query->where('string', 'LIKE', '%'.$request->q.'%');
        }

        $q = $query->orderBy('count', 'desc')->paginate(150);
        // dd($q);
        $q->setPath($request->fullUrl());
        $items = json_encode($q->items());
        $filter = null;
        $breadcrumbs = [['name' => 'Inicio', 'url' => '/'], ['name' => 'Busquedas', 'url' => '#']];

        // SEO
        $title = 'Ofertas más buscadas';
        $description = 'Encuentra las últimas tendencias y las ofertas que más buscan los usuarios de nuestra web en la sección Busquedas';

        return view('stores.search', compact('items', 'filter', 'breadcrumbs', 'q', 'title', 'description'));
    }
}
