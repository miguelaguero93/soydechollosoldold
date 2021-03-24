<?php

namespace App\Http\Controllers;

use App\Category;
use App\CategoryWord;
use App\Chollo;
use App\User;
use Auth;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
ini_set('max_execution_time', 1800);

class CategoryController extends Controller
{
    public $breadcrumbs = [];

    var $preposiciones = array('de','para','con','sin');

    var $palabrasClaveComprobacion = array('hombre','mujer','niño','niña','bebe','hombres','mujeres','niños','bebes','niñas','deportivas');

    public function getCategory(Request $request){
        $name = $request->name;
        $categories = $this->catItem($name);
        $results = [];


        if (!empty($categories)) {
            array_unique($categories,SORT_REGULAR);
            foreach ($categories as $cat){
                $this->getMailCat($cat);
                $bread = $this->breadcrumbs;
                $maincat = end($bread);
                $seccat = $cat->name;
                $seccatid = $cat->id;
                $results[] = [$maincat['id'],$seccatid,$maincat['name'],$seccat,$cat->cleaned_word];
            }
        }


        return $results;
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

    private function catItem($name){


        $ignore = DB::table('ignore_words')->pluck('word')->toArray();

        $pool = DB::table('category_words')->where('approved','=',1)->pluck('word')->toArray();

        $brands = DB::table('brands')->pluck('value')->toArray();

        $find = ['í','á','ó','é','ú'];

        $replace = ['i','a','o','e','u'];

        $result = null;

        $name = strtolower(str_replace($find,$replace,$name));

        $categories = Category::all()->toArray();

        foreach ($categories as $key=>$category){
            $categories[$key] = preg_replace("/[^A-Za-z0-9ñ ]/", '', str_replace($find,$replace,array_map('strtolower',$category)));
        }

        $re2 = '/\b[a-z]+(?:-[a-z]+)*!/i';
        preg_match($re2, $name, $matches3, PREG_OFFSET_CAPTURE, 0);
        if(count($matches3) > 0){

            $name = str_replace($matches3[0][0],"", $name);
        }


        $name = preg_replace("/[^A-Za-z0-9ñ ]/", '', $name);



        $isComposed = false;
        $prepositions = array();

        //Recorremos las preposiciones y comprobamos si existen en la frase, si existen se establece $isComposed a true para indicar que es una palabra "compuesta" y se añade la preposicion detectada al array de preposiciones
        foreach ($this->preposiciones as $preposicion){


            $re = '/\b('.$preposicion.')\b/i';

            preg_match($re, $name, $matches, PREG_OFFSET_CAPTURE, 0);

            if(count($matches) > 0){

                $isComposed = true;
                $prepositions[] = $matches[0][0];

            }

        }


        //Se comprueba si hay alguna 'Y' en el texto, si es así se establece $And a true para que el algoritmo tenga en cuenta que está esta letra
        $re2 = '/\b(y)\b/i';
        preg_match($re2, $name, $matches2, PREG_OFFSET_CAPTURE, 0);
        $And = false;
        if(count($matches2) > 0){
            $And = true;
        }


        $pool = array_filter($pool);
        $words = explode(' ', $name);



        $poolsFinded = [];
        foreach ($pool as $check){
            $matches5 = [];
            //Comprobamos si alguna palabra de la pool está en la frase
            $re5 = '/\b('.$check.')\b/i';
            preg_match($re5, $name, $matches4, PREG_OFFSET_CAPTURE, 0);

            //Si es así obtenemos la categoría y la devolvemos directamente


            if(!count($matches4) > 0) {
                $re5 = '/\b('.$check.'s)\b/i';
                preg_match($re5, $name, $matches5, PREG_OFFSET_CAPTURE, 0);


                if (count($matches5) > 0){


                    $category_word = DB::table('category_words')->where('word', '=', $check)->first();

                    $poolsFinded[] = [$category_word->category_id,$category_word->word,$check];

                }else{
                    $searchString = "";

                    foreach ($words as $word){

                        if(!in_array($word,$this->preposiciones)){

                            $searchString .= $word." ";
                        }

                    }

                    $finalString = substr($searchString,0,-1);

                    preg_match($re5, $finalString, $matches6, PREG_OFFSET_CAPTURE, 0);
                    if(count($matches6) > 0) {

                        $category_word = DB::table('category_words')->where('word', '=', $check)->first();

                        $poolsFinded[] = [$category_word->category_id,$category_word->word,$check];
                    }

                }

            }else{
                $category_word = DB::table('category_words')->where('word', '=', $check)->first();

                $poolsFinded[] = [$category_word->category_id,$check];
            }
        }


        if(count($poolsFinded) > 1){


            $finalResults = [];

            foreach ($poolsFinded as $pool){


                similar_text($name,$pool[1],$percentage);


                $finalResults[] =  [$pool[0],$pool[1],$percentage];
            }


            $key = array_search(max(array_column($finalResults,1)), array_column($finalResults,1));


            $cat = Category::find($finalResults[$key][0]);
            return [$cat];

        }elseif(count($poolsFinded) > 0){

            $cat = Category::find($poolsFinded[0][0]);
            return [$cat];
        }


        $word = "";




        $isForGender = false;


        foreach ($words as $word){

            if(in_array($word,$this->palabrasClaveComprobacion)){

                $isForGender = true;
            }

        }


        $deleted = [];

        //Eliminamos las palabras excluidas
        foreach ($words as $key => $word){

            //Comprobamos si las palabras están en el array de marcas(comprobamos palabra simple con marca entera)


            if(in_array($word,array_map('strtolower', $brands))){

                //Se eliminan las marcas del Array de words que no esten precedidads por una preposicion o sean las primeras palabras
                if(!in_array( isset($words[$key-1]) ? $words[$key-1] : $key - 1,$this->preposiciones) && $isComposed == true && isset($words[$key-1]) || $isForGender == true){

                    unset($words[$key]);
                }

            }

            if(in_array($word,$ignore)){

                unset($words[$key]);

                if(in_array(isset($words[$key+1]) ? $words[$key+1] : 'zzzzzzzzzzzzzzzzzzzzzzzzzzz',$this->preposiciones)){

                    unset($words[$key+1]);
                }elseif(in_array(isset($words[$key-1]) ? $words[$key-1] : 'zzzzzzzzzzzzzzzzzzzzzz',$this->preposiciones)){
                    unset($words[$key-1]);
                }


            }

            $checkNumbers1 = '/[0-9]+x+[0-9]+/m';
            preg_match($checkNumbers1, $word, $resultNumbers1, PREG_OFFSET_CAPTURE, 0);

            $checkNumbers2 = '/[0-9]+x+[0-9]+ml+/m';
            preg_match($checkNumbers2, $word, $resultNumbers2, PREG_OFFSET_CAPTURE, 0);

            $checkNumbers3 = '/[0-9]+x+[0-9]+ml+/m';
            preg_match($checkNumbers3, $word, $resultNumbers3, PREG_OFFSET_CAPTURE, 0);

            $checkNumbers4 = '/[0-9]+ml+/m';
            preg_match($checkNumbers4, $word, $resultNumbers4, PREG_OFFSET_CAPTURE, 0);

            $checkNumbers5 = '/[0-9]+gr+/m';
            preg_match($checkNumbers5, $word, $resultNumbers5, PREG_OFFSET_CAPTURE, 0);

            $checkNumbers6 = '/[0-9]+/m';
            preg_match($checkNumbers6, $word, $resultNumbers6, PREG_OFFSET_CAPTURE, 0);

            $keyPlus = isset($words[$key+1]) ? $key+1 : $key;
            $keyMinus = isset($words[$key-1]) ? $key-1 : $key;

            if(count($resultNumbers1) > 0){


                if(in_array($words[$keyMinus],$this->preposiciones)){
                    unset($words[$keyMinus]);
                }

                if(in_array($words[$keyPlus],$this->preposiciones)){
                    unset($words[$keyPlus]);
                }

                unset($words[$key]);

            }elseif(count($resultNumbers2) > 0){


                if(in_array($words[$keyMinus],$this->preposiciones)){
                    unset($words[$keyMinus]);
                }

                if(in_array($words[$keyPlus],$this->preposiciones)){
                    unset($words[$keyPlus]);
                }

                unset($words[$key]);

            }elseif(count($resultNumbers3) > 0){


                if(in_array($words[$keyMinus],$this->preposiciones)){
                    unset($words[$keyMinus]);
                }

                if(in_array($words[$keyPlus],$this->preposiciones)){
                    unset($words[$keyPlus]);
                }

                unset($words[$key]);

            }elseif(count($resultNumbers4) > 0){


                if(in_array($words[$keyMinus],$this->preposiciones)){
                    unset($words[$keyMinus]);
                }

                if(in_array($words[$keyPlus],$this->preposiciones)){
                    unset($words[$keyPlus]);
                }

                unset($words[$key]);

            }elseif(count($resultNumbers5) > 0){


                if(in_array($words[$keyMinus],$this->preposiciones)){
                    unset($words[$keyMinus]);
                }

                if(in_array($words[$keyPlus],$this->preposiciones)){
                    unset($words[$keyPlus]);
                }

                unset($words[$key]);

            }elseif(count($resultNumbers6) > 0){


                if(in_array($words[$keyMinus],$this->preposiciones)){
                    unset($words[$keyMinus]);
                }

                if(in_array($words[$keyPlus],$this->preposiciones)){
                    unset($words[$keyPlus]);
                }

                unset($words[$key]);

            }




        }



        $words = array_values(array_filter($words));


        foreach ($this->preposiciones as $preposicion){

            if(!in_array($preposicion,$words)){

                $isComposed = false;

            }else{

                $isComposed = true;
                break;
            }

        }


        $cleaned = array();



        //Si es una palabra compuesta por una o mas preposiciones pasa por el if
        if($isComposed === true){

            // echo "Tiene preposicion<br>";
            //recorremos las preposiciones
            foreach ($prepositions as $key => $value) {

                //Se obtiene la posicion de la preposicion en el array
                $keys = array_keys(array_column($words,null), $value);

                if(!empty($keys)){

                    $str = "";

                    if($And === true){
                        //Obtenemos la posicion de Y en el array
                        $keyAnd =  array_search("y",$words,true);
                    }


                    //Recorremos las keys que hemos obtenido de las preposicones(por ejemplo si hay mas de un 'de')
                    foreach ($keys as $key){

                        if(isset($words[$key - 1])){
                            if($key > 1 && $key < 4){

                                if($And === true && $keyAnd < 3 ){
                                    $str = $words[$keyAnd-1]." ".$words[$key-2]." ".$words[$key-1];
                                }elseif($key == 3 && isset($words[$key+1])){

                                    $str =  $words[0]."  ".$words[$key]." ".$words[$key+1];

                                }elseif($key==1){
                                    $str = $words[$key-1]." ".$words[$key]." ".$words[$key+1];
                                }else{

                                    $str = $words[$key-2]." ".$words[$key-1]." ".$words[$key+1];
                                }

                            }else{


                                if(isset($words[$key+1])){
                                    $str = ($And === true && $keyAnd > 3) || $key > 5 ? $words[$key-1]." ".$words[$key+1]  : $words[0]." ".$words[$key]." ".$words[$key+1];
                                }else{
                                    $str = ($And === true && $keyAnd > 3) || $key > 5 ? $words[$key-1]." ".$words[$key+1]  : $words[$key-1]." ".$words[$key];
                                }


                            }

                            array_push($cleaned,$str);
                        }


                    }

                }

            }

            $result = self::searchCategory($cleaned,$categories);

        }
        else{



            $cleaned = array();

            $word1 = isset($words[1]) && $words[1] != 'y' ? " ".$words[1] : "";
            $str2 = $words[0].$word1;

            array_push($cleaned,$str2);


            $result = self::searchCategory($cleaned,$categories);

            if(empty($result)){

                $str = "";

                foreach ($words as $key => $value) {

                    if ( ( !is_numeric($value) && strlen($value) > 3 && !in_array($value, $ignore) )  || ( in_array($value, $pool) && !in_array($value, $ignore) ) ) {
                        $str .= "$value ";
                    }
                }




                array_push($cleaned, substr($str,0,-1));

                $result = self::searchCategory($cleaned,$categories);



                if(empty($result)){
                    $cleaned = array();

                    $str3 = $words[0];
                    array_push($cleaned,$str3);
                    $result = self::searchCategory($cleaned,$categories);
                }


            }

        }



        return $result;
    }

    public function searchCategory($cleaned, $categories)
    {
        $results = array();
        $reCheck = array();




        foreach ($cleaned as $key => $value) {

            foreach ($categories as $key => $category){

                $string = $value;


                //Comparamos las cadenas de texto de la categoría y la frase
                similar_text ( trim($category['name']) , trim($string) , $percent);

                //Si el porcentaje de similitud es mayor o igual al 90% se añade al array de $results (Cuando el porcentaje es mayor o igual a 90 es que las palabras son practicamente las mismas)
                if($percent >= 90){

                    array_push($results, array($category['id'],$percent));


                }elseif($percent >= 76 && count(explode(" ", $category['name'])) > 3){

                    array_push($results, array($category['id'], $percent));
                }
                elseif($percent >= 70 && count(explode(" ", $category['name'])) > 3){



                    $categoryWords = explode(" ", $category['name']);

                    $categoryMounted = trim($categoryWords[0])." ".trim($categoryWords[1])." ".trim($categoryWords[2]);

                    similar_text ( $categoryMounted , trim($string) , $percentsecond);
                    if($percentsecond >= 90) {
                        array_push($results, array($category['id'], $percentsecond));
                    }

                }
                //Si el porcentaje es mayor o igual a 70 pero menor a 90 se añade al array $recheck para que afine la busqueda mas adelante
                else{


                    $words = explode(" ", $string);

                    $checkString = "";
                    $checkString2 = "";
                    foreach ($words as $key => $word){

                        if (!in_array($word,$this->preposiciones)){

                            $checkString .= $word." ";

                            if($key < 2){
                                $checkString2 .= $word." ";
                            }

                        }
                    }

                    $checkString = trim($checkString);
                    $checkString2 = trim($checkString2);

                    similar_text ( trim($category['name']) , $checkString , $percentCheck);
                    similar_text ( trim($category['name']) , $checkString2, $percentCheck2);

                    if($percentCheck >= 90){

                        array_push($results, array($category['id'],$percentCheck));


                    }elseif($percentCheck2 >= 90){

                        array_push($results, array($category['id'],$percentCheck2));

                    }else{


                        $firstcheck =  explode(' y ', trim($category['name']));
                        $secondcheck = explode(' ', trim($category['name']));


                        $categories2 = array_map('trim', $firstcheck);
                        $categories3 = array_map('trim', $secondcheck);

                        $finalStringBreaked = explode(' ', trim($string));

                        $omitResult = false;
                        $omitResult2 = false;




                        foreach ($this->palabrasClaveComprobacion as $palabra){

                            if(!in_array($palabra,$finalStringBreaked) && in_array($palabra,$categories2)){

                                $omitResult = true;

                            }

                            if(!in_array($palabra,$finalStringBreaked) && in_array($palabra,$categories3)){

                                $omitResult2 = true;

                            }

                        }

                        foreach ($this->preposiciones as $preposicion){

                            if(!in_array($preposicion,$finalStringBreaked) && in_array($preposicion,$categories3)){

                                $omitResult2 = true;

                            }


                        }


                        $firstcheckHasPre = false;

                        $pre = "";

                        foreach ($firstcheck as $key=> $check){

                            $checkFull = explode(' ', $check);

                            foreach ($checkFull as $key2=>$check2){
                                if(in_array(trim($check2),$this->preposiciones) && isset($checkFull[$key2+1])){

                                    $firstcheckHasPre = true;
                                    $pre = $check2." ".$checkFull[$key2+1];
                                }
                            }
                        }

                        foreach ($firstcheck as $check){
                            $havePreNow = false;

                            $checkFull = explode(' ', $check);

                            foreach ($checkFull as $check2){
                                if(in_array($check2,$this->preposiciones)){
                                    $havePreNow = true;

                                }
                            }


                            $thirdCheck = $firstcheckHasPre == true && $havePreNow == false ? $pre : "";
                            similar_text ( trim($check) , trim($string) , $percent1);
                            similar_text ( trim($check) , $checkString , $percent2);
                            similar_text ( trim($check." ".$thirdCheck) , trim($string) , $percent3);


                            //Si el porcentaje es mayor o igual a 85% se añade al array de $results
                            if($percent1 >= 90 && $omitResult==false){


                                array_push($results, array($category['id'],$percent1));


                            }elseif($percent2 >= 90 && $omitResult==false) {

                                array_push($results, array($category['id'],$percent2));


                            }elseif($percent3 >= 90 && $omitResult==false) {

                                array_push($results, array($category['id'],$percent2));


                            }

                        }



                        if(empty($results)){
                            foreach ($secondcheck as $check){

                                similar_text ( trim($check) , trim($string) , $percent3);
                                similar_text ( trim($check) , $checkString , $percent4);

                                //Si el porcentaje es mayor o igual a 85% se añade al array de $results
                                if($percent3 >= 85  && $omitResult2==false){


                                    array_push($results, array($category['id'],$percent3));


                                }elseif($percent4 >= 85 && $omitResult2==false) {

                                    array_push($results, array($category['id'],$percent4));



                                }  // Si no se añade al array para volver a comprobar en la siguiente fase del algoritmo
                                else{

                                    if(!in_array($string,$reCheck)){
                                        array_push($reCheck,$string);
                                    }

                                }

                            }
                        }

                    }
                }

            }

        }

        if(empty($results)){

            $results = self::recheck($reCheck, $categories);

            if(empty($results)) {
                $results = self::recheck($reCheck, $categories, 0,true);
            }

            if(empty($results)){
                $results = self::recheck($reCheck,$categories,1);

            }
            if(empty($results)){
                $results = self::recheck($reCheck,$categories,2);

            }
        }


        $results = array_unique($results,SORT_REGULAR);



        if (count($results) > 0 && count($results) < 3){

            $key = array_search(max(array_column($results,1)), array_column($results,1));

            $categories = [];

            $cat = Category::find($results[$key][0]);

            $cat->cleaned_word = $cleaned[0];

            $categories[] = $cat;

            return $categories;

        }
        elseif(count($results) > 2){

            $categories = [];

            foreach($results as $result){

                $cat = Category::find($result[0]);
                $cat->cleaned_word = $cleaned[0];
                $categories[] = $cat;

            }

            return $categories;

        }
        elseif(count($results) > 0){
            $cat = Category::find($results[0][0]);
            $cat->cleaned_word = $cleaned[0];

            return [$cat];
        }
        else{

            $final = [];
            $finalSearch = explode(' ',$cleaned[0]);
            foreach ($categories as $category){

                $cats = explode(' ', $category['name']);

                foreach ($cats as $cat){

                    similar_text ( $cat , $finalSearch[0] , $percentage1);

                    if($percentage1 >= 90 && $category['order'] < 3){
                        array_push($final, array($category['id'],$category['name'],$percentage1));

                    }
                }

            }

            if(empty($final)){
                foreach ($categories as $category) {

                    $cats = explode(' ', $category['name']);
                    foreach ($cats as $cat) {
                        foreach ($finalSearch as $word) {
                            similar_text($cat, $word, $percentage);

                            if ($percentage >= 90) {
                                array_push($final, array($category['id'], $category['name'], $percentage));

                            }


                        }
                    }
                }
            }


            if(count($final)> 1){

                $categories = [];

                foreach($final as $result){

                    $cat = Category::find($result[0]);
                    $cat->cleaned_word = $cleaned[0];
                    $categories[] = $cat;

                }

                return $categories;

            }elseif(count($final) > 0){
                $cat = Category::find($final[0][0]);

                $cat->cleaned_word = $cleaned[0];
                return [$cat];
            }else{


                return [];


            }

        }
    }

    public function recheck($reCheck, $categories,$keyMaster = 0,$secondCheck = false)
    {

        $results = [];

        foreach ($reCheck as $key => $value) {

            foreach ($categories as $key => $category){

                similar_text ( trim($category['name']) , trim($value) , $percent);

                if($percent >= 90){
                    array_push($results, array($category['id'], $category['name'],$percent));
                }else{


                    $finalStringBreaked = explode(' ', $value);
                    $testString = "";

                    foreach ($finalStringBreaked as $key => $word){
                        if (in_array(trim($word), $this->preposiciones)) {

                            unset($finalStringBreaked[$key]);
                        }

                    }

                    $omitResult = false;

                    $categories2 = explode(" y ",$category['name']);
                    $categories3 = explode(" ",$category['name']);
                    $categories4 = explode(",",$category['name']);

                    $categories2 = array_map('trim', $categories2);
                    $wordWithoutPre = false;
                    $categoryWithoutPre = true;


                    foreach ($this->palabrasClaveComprobacion as $palabra){

                        if(!in_array($palabra,$finalStringBreaked) && (in_array($palabra,$categories2) || in_array($palabra,$categories3) || in_array($palabra,$categories4))){

                            $omitResult = true;

                        }

                    }



                    foreach ($this->preposiciones as $preposicion){

                        if(!in_array($preposicion,$finalStringBreaked) && (in_array($preposicion,$categories2) || in_array($preposicion,$categories3)  )){

                            $omitResult = true;

                        }

                    }

                    foreach ($categories3 as $category2){



                        foreach ($finalStringBreaked as $key => $word){

                            similar_text ( trim($category2) , trim($word) , $percentt);
                            similar_text ( substr(trim($category2),0,-1) , trim($word) , $percentt2);
                            similar_text ( trim($category2), substr(trim($word),0,-1) , $percentt3);
                            similar_text ( substr(trim($category2),0,-1) , substr(trim($word),0,-1) , $percentt4);


                            if(in_array($word,$this->preposiciones)){
                                $keyMaster = $keyMaster+1;
                            }

                            if($percentt >= 90 && ( ($omitResult==false || $secondCheck == true) && $key == $keyMaster)){

                                if(count($categories3) == 1){

                                    array_push($results, array($category['id'], $category['name'],$percentt));

                                }elseif($secondCheck == true) {

                                    array_push($results, array($category['id'], $category['name'], $percentt));
                                }



                            }elseif($percentt2 == 100 && (($omitResult==false || $secondCheck == true) && $key == $keyMaster)){


                                if(count($categories3) == 1){

                                    array_push($results, array($category['id'], $category['name'],$percentt));

                                }elseif($secondCheck == true) {

                                    array_push($results, array($category['id'], $category['name'], $percentt));
                                }


                            }elseif($percentt3 == 100 && ((($omitResult==false || $secondCheck == true) && $key == $keyMaster))){

                                if(count($categories3) == 1){

                                    array_push($results, array($category['id'], $category['name'],$percentt));

                                }elseif($secondCheck == true) {

                                    array_push($results, array($category['id'], $category['name'], $percentt));
                                }


                            }elseif($percentt4 == 100 && ((($omitResult==false || $secondCheck == true) && $key == $keyMaster))){

                                if(count($categories3) == 1){

                                    array_push($results, array($category['id'], $category['name'],$percentt));

                                }elseif($secondCheck == true) {

                                    array_push($results, array($category['id'], $category['name'], $percentt));
                                }


                            }/*elseif($percentt >= 85 && $omitResult==false){
                                    echo "$category2 | $word | $percentt <br>";
                                    array_push($results,  array($category['id'],$category['name'],$percentt));
                                }*/

                        }


                    }

                }

            }

        }

        return $results;
    }

    public function addPoolWord(Request $request){
        $word = trim($request->word);

        DB::table('category_words')->insert(['word'=> $word,'category_id'=>$request->category_id,'approved'=>0]);

        return $request->category_id;
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
        $word = trim($request->word);
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

    public function fixuserimages(){


        $users = User::all();


        foreach ($users as $user){


            $url1 = $user->avatar;


            $result = strpos($url1,"https://soydechollos.com/");
            if($result === false) {

            }else{
                $url1 = str_replace("https://soydechollos.com/", env('APP_URL')."public/", $url1);

                $user->avatar = $url1;
                $user->save();
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
        $words = DB::table('category_words')->where('category_id', $id)->where('approved',1)->get();

        return view('categories.pool', compact('category', 'words'));
    }

    public function poolWords(){
        $words = DB::table('category_words')
            ->leftJoin('categories','category_words.category_id','=','categories.id')
            ->select(DB::raw('COUNT(category_words.word) as wordCount'),'category_words.id','category_words.word','category_words.approved','categories.name','categories.id as categoryID')
            ->where('approved',1)->groupBy('category_words.word')->get();

        $categories = Category::where('deleted_at','=',null)->get();

        return view('pools.poolWords',compact('words'));
    }

    public function poolPending(){

        $words = DB::table('category_words')
            ->leftJoin('categories','category_words.category_id','=','categories.id')->select('category_words.id','category_words.word','category_words.approved','categories.name','categories.id as categoryID')->where('approved',0)->get();

        $categories = Category::where('deleted_at','=',null)->get();

        return view('categories.poolPending',compact('words','categories'));
    }

    public function approvePoolPending(Request $request){

        $result = [false,"error"];
        $id = $request->id;
        $word = $request->word;
        $category = $request->category;


        try{

            $categoryWord = CategoryWord::findOrFail($id);

            $categoryWord->word = $word;

            $categoryWord->category_id = $category;

            $categoryWord->approved = 1;

            $categoryWord->save();

            $result = ["Palabra añadida a la pool correctamente","success"];

        }catch (\Exception $e){


            $result = [$e->getMessage(),"error"];

        }


        return redirect('panel/category/pending/pools')->with(['message' => $result[0], 'alert-type' => $result[1]]);
    }

    public function denyPoolPending(Request $request){

        $id = $request->id;

        try{

            CategoryWord::destroy($id);

            $result = ["Palabra rechazada correctamente","success"];

        }catch (\Exception $e){


            $result = [$e->getMessage(),"error"];

        }

        return redirect('panel/category/pending/pools')->with(['message' => $result[0], 'alert-type' => $result[1]]);
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
