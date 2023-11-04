<?php

include('db-movies.php');

$str = "<p><i>Фільм(и) виведени згідно порядку в базі даних...</i></p>";

function show($val, $key)
{
    global $str;
    $showStr = "<h3>Результат пошуку:</h3><p>{$val['name']} ({$val['year']}) - {$val['genre']}. Сеанси: {$val['sessions']}. Режисер {$val['director']}. Видавництво {$val['studio']}.</p>";
    print_r($showStr);
}

function search($movies, $data) {
    $result = [];
    $result = [];
    $isNoFound = false;
    foreach ($movies as $movie_name => $movie) {
        if (mb_stristr($movie_name, $data)) {
            $result[] = $movie_name;
        }
        foreach ($movie as $value) {
            if (mb_stristr($value, $data)) {
                $result[] = $movie_name;
            }
        }
    }

    if(!$result) {
        $isNoFound = true;
    }

    if(!$isNoFound) {
        $noDuplic = array_unique($result);
        $search_result = array_flip($noDuplic);
        $movies_search_result = array_intersect_key($movies, $search_result);
        return $movies_search_result;
    }
}

function cmp_director($a, $b)
{
    return $a['director'] <=> $b['director'];
}

function cmp_name($a, $b)
{
    return $a['name'] <=> $b['name'];
}

function cmp_year($a, $b)
{
    return $a['year'] <=> $b['year'];
}

function cmp_genre($a, $b)
{
    return $a['genre'] <=> $b['genre'];
}

function cmp_rating($a, $b)
{
    return $a['rating'] <=> $b['rating'];
}

function cmp_studio($a, $b)
{
    return $a['studio'] <=> $b['studio'];
}

function cmp_sessions($a, $b)
{
    return $a['sessions'] <=> $b['sessions'];
}

function sorting($how_to_sort)
{
    global $movies, $str;
    uasort($movies, $how_to_sort);
    switch ($how_to_sort) {
        case "cmp_director":
            $str = "Сортування відбулось за режисерами...";
            break;
        case "cmp_year":
            $str = "Сортування відбулось за датою випуску...";
            break;
        case "cmp_name":
            $str = "Сортування відбулось за назвою...";
            break;
        case "cmp_genre":
            $str = "Сортування відбулось за жанром...";
            break;
        case "cmp_rating":
            $str = "Сортування відбулось за рейтингом...";
            break;
        case "cmp_studio":
            $str = "Сортування відбулось за кіностудією...";
            break;
        case "cmp_sessions":
            $str = "Сортування відбулось за сеансами...";
            break;
    }
}

if (isset($_POST['sendingSearch'])) {
    global $str;
    $data = $_POST["inputData"];
    $res = search($movies, $data);
    if (!$res) {
        echo "<p>На жаль, на Ваш запит інформація <b>відсутня</b>.</p>";
    }
    else {
        array_walk($res, "show");
    }
}

if (isset($_POST["sort"])) {
    $how_to_sort = $_POST["sort"];
    sorting($how_to_sort);
    array_walk($movies, "show");
    echo "<p><i>$str</i></p>";
}