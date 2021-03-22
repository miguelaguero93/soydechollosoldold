<?php

namespace App\Http\Controllers;

use App\Brand;
use App\Category;
use App\Chollo;
use App\Keyword;
use App\Sdk\AwsV4;
use App\Search;
use App\Store;
use App\User_keyword;
use Auth;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Redirect;
use Session;

class AvisadorController extends Controller
{
    private function logQuery($q)
    {
        $s = Search::where('string', $q)->first();
        if (is_null($s)) {
            $n = new Search();
            $n->string = $q;
            $n->slug = nicename($q);
            $n->count = 1;
            if (Auth::check()) {
                $n->user_id = Auth::id();
            }
            $n->save();
        } else {
            ++$s->count;
            $s->save();
        }

        return $s;
    }

    public function index(Request $request)
    {
        $query = trim($request->search);
        if (!empty($query)) {
            $query = strtolower($query);
            $query = str_replace(' ', '-', $query);

            return Redirect::to('/ofertas/'.$query);
        }

        return Redirect::to('/');
    }

    public function getAmazon(Request $request)
    {
        $query = $request->keyword;
        $amazon_discounted = $this->searchAmazonProducts($query, 50);
        $amazon_keyword = $this->searchAmazonProducts($query, null);
        // sometimes , do not know why, $amazon_keyword do not return and array, thus generatin an error
        if (is_array($amazon_keyword)) {
            $amazon = array_merge($amazon_discounted, $amazon_keyword);
        } else {
            $amazon = $amazon_discounted;
        }

        $amazon = json_encode($amazon);

        return $amazon;
    }

    public function searchAjax(Request $request)
    {
        $query = $request->search;
        $this->logQuery($query);
        setlocale(LC_TIME, 'Spanish');
        $query = trim($query);
        $query = str_replace('-', ' ', $query);

        // Categorias
        $categories = Category::where('name', 'LIKE', '%'.$query.'%')->select('id', 'name', 'image', 'slug')->limit(6)->get();
        if (count($categories) < 6) {
            $new_categories = Category::where(function ($q) use ($query) {
                $querys = explode(' ', $query);
                if (count($querys) > 0) {
                    $querysearch = '';
                    for ($i = count($querys) - 1; $i >= 0; --$i) {
                        $querysearch = $querysearch.' '.$querys[$i];
                    }
                    $q->orWhere('name', 'LIKE', '%'.$querysearch.'%');
                }
            })->select('id', 'name', 'image', 'slug')->limit(6 - count($categories))->get();
            $categories = $categories->merge($new_categories);
        }
        if (count($categories) < 6) {
            $new_categories = Category::where(function ($q) use ($query) {
                $querys = explode(' ', $query);
                if (count($querys) > 0) {
                    foreach ($querys as $unique_query) {
                        $q->where('name', 'LIKE', '%'.$unique_query.'%');
                    }
                }
            })->select('id', 'name', 'image', 'slug')->limit(6 - count($categories))->get();
            $categories = $categories->merge($new_categories);
        }
        for ($i = 0; $i < count($categories); ++$i) {
            $cholloCategoryCount = Chollo::where('category_id', $categories[$i]['id'])->where('approved', 1)->count();
            $categories[$i]['count'] = $cholloCategoryCount;
        }

        // Tiendas
        $stores = Store::where('name', 'LIKE', '%'.$query.'%')->select('id', 'name', 'image', 'visible_name', 'slug')->limit(3)->get();
        for ($i = 0; $i < count($stores); ++$i) {
            $cholloStoresCount = Chollo::where('store_id', $stores[$i]['id'])->where('approved', 1)->count();
            $stores[$i]['count'] = $cholloStoresCount;
        }

        // Marcas
        $brands = Brand::where('value', 'LIKE', '%'.$query.'%')->select('id', 'value')->limit(3)->get();
        for ($i = 0; $i < count($brands); ++$i) {
            $cholloBrandsCount = Chollo::where('brand_id', $brands[$i]['id'])->where('approved', 1)->count();
            $brands[$i]['count'] = $cholloBrandsCount;
        }

        $keywords_ids = Keyword::where('keyword', 'LIKE', '%'.$query.'%')->pluck('chollo_id')->toArray();

        // Ofertas
        $chollos = Chollo::where(function ($q) use ($query, $keywords_ids) {
            $q->whereIn('id', $keywords_ids);
            $q->orWhere('name', 'LIKE', '%'.$query.'%');
            $q->orWhere('slug', 'LIKE', '%'.$query.'%');
        })->where('approved', 1)->select('id', 'user_id', 'category_id', 'store_id', 'name', 'image_small', 'price', 'regular_price', 'link', 'updated_at', 'available', 'comments_count', 'discount', 'discount_code', 'shipping_cost', 'slug', 'votes', 'image', 'until', 'from')->orderBy('updated_at', 'DESC')->limit(3)->get();

        if (count($chollos) == 0) {
            $chollos = Chollo::where(function ($q) use ($query, $keywords_ids) {
                $q->whereIn('id', $keywords_ids);
                $querys = explode(' ', $query);
                if (count($querys) > 0) {
                    $querysearch = '';
                    for ($i = count($querys) - 1; $i >= 0; --$i) {
                        $querysearch = $querysearch.' '.$querys[$i];
                    }
                    $q->orWhere('name', 'LIKE', '%'.$querysearch.'%');
                }
                $q->orWhere('slug', 'LIKE', '%'.$query.'%');
            })->where('approved', 1)->select('id', 'user_id', 'category_id', 'store_id', 'name', 'image_small', 'price', 'regular_price', 'link', 'updated_at', 'available', 'comments_count', 'discount', 'discount_code', 'shipping_cost', 'slug', 'votes', 'image', 'until', 'from')->orderBy('updated_at', 'DESC')->limit(3)->get();
            if (count($chollos) == 0) {
                $chollos = Chollo::where(function ($q) use ($query, $keywords_ids) {
                    $querys = explode(' ', $query);
                    if (count($querys) > 0) {
                        foreach ($querys as $unique_query) {
                            $q->where('name', 'LIKE', '%'.$unique_query.'%');
                        }
                    }
                    $q->orWhere('slug', 'LIKE', '%'.$query.'%');
                })->where('approved', 1)->select('id', 'user_id', 'category_id', 'store_id', 'name', 'image_small', 'price', 'regular_price', 'link', 'updated_at', 'available', 'comments_count', 'discount', 'discount_code', 'shipping_cost', 'slug', 'votes', 'image', 'until', 'from')->orderBy('updated_at', 'DESC')->limit(3)->get();
                if (count($chollos) == 0) {
                    $chollos = Chollo::where(function ($q) use ($query, $keywords_ids) {
                        $q->whereIn('id', $keywords_ids);
                        $querys = explode(' ', $query);
                        if (count($querys) > 0) {
                            foreach ($querys as $unique_query) {
                                $q->orWhere('name', 'LIKE', '%'.$unique_query.'%');
                            }
                        }
                        $q->orWhere('slug', 'LIKE', '%'.$query.'%');
                    })->where('approved', 1)->select('id', 'user_id', 'category_id', 'store_id', 'name', 'image_small', 'price', 'regular_price', 'link', 'updated_at', 'available', 'comments_count', 'discount', 'discount_code', 'shipping_cost', 'slug', 'votes', 'image', 'until', 'from')->orderBy('updated_at', 'DESC')->limit(3)->get();
                }
            }
        }

        return ['categories' => $categories,
                 'stores' => $stores,
                 'brands' => $brands,
                 'chollos' => $chollos, ];
    }

    public function getMore(Request $request)
    {
        [$itemsCount, $items] = $this->getItems($request->get('query'), $request->get('keywords_ids'), $request->get('page'), $request->get('pagination'));
        $this->processItem($items);

        return $items;
    }

    public function getRelatedkeywords(Request $request)
    {
        $query = $request->get('query');
        Log::info('$query');
        Log::info($query);
        $this->logQuery($query);
        setlocale(LC_TIME, 'Spanish');
        $query = trim($query);
        $query = str_replace('-', ' ', $query);

        // Categorias
        $categories = Category::where('name', 'LIKE', '%'.$query.'%')->select('id', 'name')->limit(6)->get();

        // Tiendas
        $stores = Store::where('name', 'LIKE', '%'.$query.'%')->select('id', 'name')->limit(3)->get();

        // Marcas
        $brands = Brand::where('value', 'LIKE', '%'.$query.'%')->select('id', 'value')->limit(3)->get();

        $keywords = [];
        if (Auth::user()) {
            $logged = Auth::user();
            $auth_id = $logged->id;
            $keywords = User_keyword::where('user_id', $auth_id)->get();
        }

        return ['categories' => $categories,
                 'stores' => $stores,
                 'brands' => $brands,
                 'keywords' => $keywords, ];
    }

    private function getItems($query, $keywords_ids, $page = 1, $pagination = 16)
    {
        $skip = ($page - 1) * $pagination;

        $cats = Category::where('name', 'LIKE', '%'.$query.'%')->pluck('id');
        $stores = Store::where('name', 'LIKE', '%'.$query.'%')->pluck('id');

        $itemsCount = Chollo::where(function ($q) use ($query, $keywords_ids, $cats, $stores) {
            $q->whereIn('id', $keywords_ids);
            $q->orWhere('name', 'LIKE', '%'.$query.'%');
            $q->orWhere('slug', 'LIKE', '%'.$query.'%');
            $q->orWhere('slug', 'LIKE', '%'.$query.'%');
            $q->orWhereIn('category_id', $cats);
            $q->orWhereIn('store_id', $stores);
        })->where('approved', 1)->count();

        $totalItemsCount = $itemsCount;

        if (str_contains($query, ' ')) {
            $itemsCount1 = Chollo::where(function ($q) use ($query, $keywords_ids) {
                $q->whereIn('id', $keywords_ids);
                $querys = explode(' ', $query);
                if (count($querys) > 0) {
                    $querysearch = '';
                    for ($i = count($querys) - 1; $i >= 0; --$i) {
                        $querysearch = $querysearch.' '.$querys[$i];
                    }
                    $q->orWhere('name', 'LIKE', '%'.$querysearch.'%');
                }
                $q->orWhere('slug', 'LIKE', '%'.$query.'%');
            })->where('approved', 1)->count();

            $itemsCount2 = Chollo::where(function ($q) use ($query, $keywords_ids) {
                $querys = explode(' ', $query);
                if (count($querys) > 0) {
                    foreach ($querys as $unique_query) {
                        $q->where('name', 'LIKE', '%'.$unique_query.'%');
                    }
                }
                $q->orWhere('slug', 'LIKE', '%'.$query.'%');
            })->where('approved', 1)->count();

            $itemsCount3 = Chollo::where(function ($q) use ($query, $keywords_ids) {
                $q->whereIn('id', $keywords_ids);
                $querys = explode(' ', $query);
                if (count($querys) > 0) {
                    foreach ($querys as $unique_query) {
                        $q->orWhere('name', 'LIKE', '%'.$unique_query.'%');
                    }
                }
                $q->orWhere('slug', 'LIKE', '%'.$query.'%');
            })->where('approved', 1)->count();

            $totalItemsCount = $itemsCount + $itemsCount1 + $itemsCount2 + $itemsCount3;
        }

        $items = [];

        if ($itemsCount >= $skip) {
            $items = Chollo::where(function ($q) use ($query, $keywords_ids, $cats, $stores) {
                $q->whereIn('id', $keywords_ids);
                $q->orWhere('name', 'LIKE', '%'.$query.'%');
                $q->orWhere('slug', 'LIKE', '%'.$query.'%');
                $q->orWhereIn('category_id', $cats);
                $q->orWhereIn('store_id', $stores);
            })->where('approved', 1)->select('id', 'user_id', 'category_id', 'store_id', 'name', 'image_small', 'price', 'regular_price', 'link', 'updated_at', 'available', 'comments_count', 'discount', 'discount_code', 'shipping_cost', 'slug', 'votes', 'image', 'until', 'from')->orderBy('updated_at', 'DESC')->skip($skip)->limit($pagination)->get();

            if (count($items) >= $pagination) {
                return [$totalItemsCount, $items];
            }
        }

        if (str_contains($query, ' ')) {
            $skip = $skip - $itemsCount;
            if ($itemsCount1 >= $skip) {
                $pagination = $pagination - count($items);
                $more_items1 = Chollo::where(function ($q) use ($query, $keywords_ids) {
                    $q->whereIn('id', $keywords_ids);
                    $querys = explode(' ', $query);
                    if (count($querys) > 0) {
                        $querysearch = '';
                        for ($i = count($querys) - 1; $i >= 0; --$i) {
                            $querysearch = $querysearch.' '.$querys[$i];
                        }
                        $q->orWhere('name', 'LIKE', '%'.$querysearch.'%');
                    }
                    $q->orWhere('slug', 'LIKE', '%'.$query.'%');
                })
                ->where('approved', 1)
                ->select('id', 'user_id', 'category_id', 'store_id', 'name', 'image_small', 'price', 'regular_price', 'link', 'updated_at', 'available', 'comments_count', 'discount', 'discount_code', 'shipping_cost', 'slug', 'votes', 'image', 'until', 'from')
                ->orderBy('updated_at', 'DESC')
                ->skip($skip)
                ->limit($pagination)
                ->get();

                if (count($items) == 0) {
                    $items = $more_items1;
                } else {
                    $items = $items->merge($more_items1);
                }

                if (count($items) >= $pagination) {
                    return [$totalItemsCount, $items];
                }
            }

            $skip = $skip - $itemsCount1;
            if ($skip < 0) {
                $skip = $skip * -1;
            }

            if ($itemsCount2 >= $skip) {
                $pagination = $pagination - count($items);
                $more_items2 = Chollo::where(function ($q) use ($query, $keywords_ids) {
                    $querys = explode(' ', $query);
                    if (count($querys) > 0) {
                        foreach ($querys as $unique_query) {
                            $q->where('name', 'LIKE', '%'.$unique_query.'%');
                        }
                    }
                    $q->orWhere('slug', 'LIKE', '%'.$query.'%');
                })
                ->where('approved', 1)
                ->select('id', 'user_id', 'category_id', 'store_id', 'name', 'image_small', 'price', 'regular_price', 'link', 'updated_at', 'available', 'comments_count', 'discount', 'discount_code', 'shipping_cost', 'slug', 'votes', 'image', 'until', 'from')
                ->orderBy('updated_at', 'DESC')
                ->skip($skip)
                ->limit($pagination)
                ->get();

                if (count($items) == 0) {
                    $items = $more_items2;
                } else {
                    $items = $items->merge($more_items2);
                }

                if (count($items) >= $pagination) {
                    return [$totalItemsCount, $items];
                }
            }

            $skip = $skip - $itemsCount2;
            if ($skip < 0) {
                $skip = $skip * -1;
            }

            if ($itemsCount3 >= $skip) {
                $pagination = $pagination - count($items);
                $more_items3 = Chollo::where(function ($q) use ($query, $keywords_ids) {
                    $q->whereIn('id', $keywords_ids);
                    $querys = explode(' ', $query);
                    if (count($querys) > 0) {
                        foreach ($querys as $unique_query) {
                            $q->orWhere('name', 'LIKE', '%'.$unique_query.'%');
                        }
                    }
                    $q->orWhere('slug', 'LIKE', '%'.$query.'%');
                })->where('approved', 1)->select('id', 'user_id', 'category_id', 'store_id', 'name', 'image_small', 'price', 'regular_price', 'link', 'updated_at', 'available', 'comments_count', 'discount', 'discount_code', 'shipping_cost', 'slug', 'votes', 'image', 'until', 'from')->orderBy('updated_at', 'DESC')->skip($skip)->limit($pagination)->get();

                if (count($items) == 0) {
                    $items = $more_items3;
                } else {
                    $items = $items->merge($more_items3);
                }

                if (count($items) >= $pagination) {
                    return [$totalItemsCount, $items];
                }
            }

            $skip = $skip - $itemsCount3;
        }

        return [$totalItemsCount, $items];
    }

    public function index2($query = null)
    {
        setlocale(LC_TIME, 'Spanish');

        $items = 0;
        $itemsCount = 0;
        $keywords_ids = [];
        $amazon = 0;
        $activateAlert = 0;
        $query = trim($query);
        $query = str_replace('-', ' ', $query);
        $logged = 0;
        $activateAlert = 0;
        $title = 'Avisador Pro – Encuentra  todas las ofertas que buscas';
        $description = 'Encuentra cualquier oferta o descuento que necesites, nuestro Avisador Pro te buscará lo que quieras en varias webs, y si quieres.. ¡enviará alertas cuando aparezcan nuevas ofertas!';
        $breadcrumbs = [['name' => 'Inicio', 'url' => '/'], ['name' => 'Avisador PRO', 'url' => '#']];

        if (Auth::user()) {
            $logged = Auth::user();
            $activateAlert = User_keyword::where('user_id', $logged->id)->where('keyword', $query)->count();
        }

        if (!empty($query)) {
            if ($query == 'amazon') {
                return redirect('/tienda/amazon');
            }
            $keywords_ids = Keyword::where('keyword', 'LIKE', '%'.$query.'%')->pluck('chollo_id')->toArray();
            $search = $this->logQuery($query);

            [$itemsCount, $items] = $this->getItems($query, $keywords_ids);

            $this->processItem($items);

            $breadcrumbs = [['name' => 'Inicio', 'url' => '/'], ['name' => 'Avisador PRO', 'url' => '/avisador'], ['name' => $query, 'url' => '#']];

            if (empty($search->title) && empty($search->description)) {
                $now = Carbon::now();
                $month = $now->formatLocalized('%B');
                $year = ucfirst($now->format('Y'));
                $title = ucwords('Comprar '.$query.' baratas - Ofertas '.$month.' '.$year);
                $seo_string = '';

                if (is_object($items) && $items->count() > 1) {
                    foreach ($items->take(2) as $key => $value) {
                        $seo_string .= ' '.$value->name;
                    }
                }
                $description = 'Las mejores ofertas de '.$query.' online, aquí encontraras los últimos chollos para comprar al mejor precio ➡️ '.$seo_string;
            } else {
                $title = $search->title;
                $description = $search->description;
            }
        }

        $filter = null;
        $name = $title;
        $details = $description;
        $store_object = compact('description', 'title', 'name', 'details');
        $amazon = '[]';
        $keywords_ids = json_encode($keywords_ids);

        return view('avisador.index', compact('items', 'query', 'logged', 'activateAlert', 'amazon', 'breadcrumbs', 'filter', 'title', 'description', 'store_object', 'itemsCount', 'keywords_ids'));
    }

    public function alertas(Request $request)
    {
        $logged = Auth::user();
        $auth_id = $logged->id;
        // dd($auth_id);
        $keyword = trim(strtolower($request->keyword));
        if (isset($request->keyword) && (strlen($request->keyword) > 2)) {
            $existing = User_keyword::where('user_id', $auth_id)->where('keyword', $keyword)->first();
            if (is_null($existing)) {
                $acc = ['á', 'é', 'í', 'ó', 'ú'];
                $nacc = ['a', 'e', 'i', 'o', 'u'];
                $k = str_replace($acc, $nacc, $keyword);
                $new = new User_keyword();
                $new->user_id = $auth_id;
                $new->keyword = $k;
                $new->save();
                Session::flash('success', 'Alerta generada');
            }
        }

        $keywords = User_keyword::where('user_id', $auth_id)->get();
        // dd($keywords);
        // dd($aut);
        foreach ($keywords as $key => $value) {
            $value->selected = true;
        }
        $breadcrumbs = [['name' => 'Inicio', 'url' => '/'], ['name' => 'Mis Alertas', 'url' => '#']];
        $filter = null;

        return view('alerts.index', compact('keywords', 'logged', 'breadcrumbs', 'filter'));
    }

    private function processItem($items)
    {
        foreach ($items as $key => $value) {
            $value->favorite = false;
        }
        if (!Auth::guest()) {
            $user_id = Auth::id();
            $ids = $items->pluck('id');
            $user_favorites = DB::table('favorites')->where('user_id', $user_id)->whereIn('chollo_id', $ids)->get();
            foreach ($user_favorites as $key => $value) {
                $item = $items->firstWhere('id', $value->chollo_id);
                if (!is_null($item)) {
                    $item->favorite = true;
                }
            }
        }

        return $items;
    }

    private function searchAmazonProducts($query, $discounted)
    {
        $serviceName = 'ProductAdvertisingAPI';
        $region = 'eu-west-1';

        $accessKey = setting('admin.amazon_access_key');
        $secretKey = setting('admin.amazon_secret_key');
        $tag = setting('admin.amazon_partner_key');
        $payload = [
            'Keywords' => $query,
            'Resources' => [
                'Images.Primary.Large',
                'ItemInfo.Title',
                'Offers.Listings.Price',
            ],
            'PartnerTag' => $tag,
            'PartnerType' => 'Associates',
            'Marketplace' => 'www.amazon.es',
            'Operation' => 'SearchItems',
        ];
        if (!is_null($discounted)) {
            $payload['MinSavingPercent'] = $discounted;
        }

        $payload = json_encode($payload);
        $host = 'webservices.amazon.es';
        $uriPath = '/paapi5/searchitems';
        $awsv4 = new AwsV4($accessKey, $secretKey);
        $awsv4->setRegionName($region);
        $awsv4->setServiceName($serviceName);
        $awsv4->setPath($uriPath);
        $awsv4->setPayload($payload);
        $awsv4->setRequestMethod('POST');
        $awsv4->addHeader('content-encoding', 'amz-1.0');
        $awsv4->addHeader('content-type', 'application/json; charset=utf-8');
        $awsv4->addHeader('host', $host);
        $awsv4->addHeader('x-amz-target', 'com.amazon.paapi5.v1.ProductAdvertisingAPIv1.SearchItems');
        $headers = $awsv4->getHeaders();
        $headerString = '';
        foreach ($headers as $key => $value) {
            $headerString .= $key.': '.$value."\r\n";
        }
        $params = [
            'http' => [
                'header' => $headerString,
                'method' => 'POST',
                'content' => $payload,
            ],
        ];

        $stream = stream_context_create($params);
        $fp = @fopen('https://'.$host.$uriPath, 'rb', false, $stream);

        if (!$fp) {
            return 0;
        }

        $response = @stream_get_contents($fp);

        if ($response === false) {
            return 0;
        }
        $res = json_decode($response, true);
        if (isset($res['SearchResult'])) {
            return $res['SearchResult']['Items'];
        } else {
            return [];
        }
    }
}
