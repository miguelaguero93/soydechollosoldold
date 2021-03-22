<?php  
function setfooter(){
	return header("Location: ".env('APP_URL')."/api/setfooter");
}
function chollo_url($item){
    return '/'.$item->slug;
}
function chollo_url_alt($slug){
    return "/".$slug;
}
function store_url($item){
    return '/tienda/'.$item->slug;
}
function chollo_link($item){
	return "<a href='/oferta/$item->id/c'>$item->name</a>";
}

function nicename($name){
    $nameurl = trim($name);
    $nameurl = mb_strtolower($nameurl);
    $nameurl = str_replace('-','', $nameurl);
    // $nameurl = str_replace('-','', $nameurl);
    $find = ['¿','?',':','/','.','(',')','|',',','[',']','"','º','!','¡','-'];
    $nameurl = str_replace($find,'',$nameurl);    

    $nameurl = str_replace("'", "", $nameurl);

    $nameurl = preg_replace('/\s+/',' ', $nameurl);
    $find = [' ','í','á','ó','é','ú','%','ñ'];
    $replace = ['-','i','a','o','e','u','_porciento','n'];
    $nameurl = trim(str_replace($find,$replace,$nameurl));    
    // $nameurl = htmlentities($nameurl);
    $nameurl = str_replace('&nbsp;','-', $nameurl);
    return $nameurl;
}

?>