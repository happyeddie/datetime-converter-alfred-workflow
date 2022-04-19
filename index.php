<?php
$query  = $argv[1];
$result = "";

$tzs = explode(",", getenv("TZ"));
$default_tz = date_default_timezone_get();

// timestamp
$rules_timestamp                = "/^\d+$/";
$rules_timestamp_second         = "/^\d{1,10}$/";
$rules_timestamp_millisecond    = "/^\d{13}$/";

foreach ($tzs as $key => $tz) {
    $tzs[$key] = trim($tz);

    if ($tzs[$key] == "") {
        unset($tzs[$key]);
    }
}

if (!$tzs) {
    $tzs = [
        $default_tz,
    ];
}

$items  = [];

foreach ($tzs as $tz) {
    date_default_timezone_set($tz);

    if (preg_match($rules_timestamp, $query)) {
        if (preg_match($rules_timestamp_second, $query)) {
            $result = date("Y-m-d H:i:s", $query);
        }
        else if (preg_match($rules_timestamp_millisecond, $query)) {
            $second = substr($query, 0, 10);
            $ms     = substr($query, 10, 3);
            $result = date("Y-m-d H:i:s", $second) . '.' . $ms;
        }
        else {
            $result = "{$query} is invaild timestamp";
            $items[] = [
                "title" => "{$result}",
            ];
            break;
        }
    }
    else {
        $split  = explode(".", $query);
        $result = strtotime($split[0]);
    
        if (count($split) > 1 && preg_match("/^\d{3}$/", $split[1])) {
            $result .= $split[1];
        }
    
        if (!preg_match($rules_timestamp, $result)) {
            $result = "{$query} is invaild datetime";
            $items[] = [
                "title" => "{$result}",
            ];
            break;
        }
    }
    
    $items[] = [
        "title" => "{$result}",
        "subtitle" => "{$tz}",
        "arg"   => "{$result}",
    ];
}

$output = [
    "items" => $items,
];

echo json_encode($output);

date_default_timezone_set($default_tz);
?>