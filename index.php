<?php
$query  = $argv[1];
$result = "";

// timestamp
$rules_timestamp                = "/^\d+$/";
$rules_timestamp_second         = "/^\d{10}$/";
$rules_timestamp_millisecond    = "/^\d{13}$/";

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
    }
}
else {
    $result = strtotime($query);

    if (!preg_match($rules_timestamp, $result)) {
        $result = "{$query} is invaild datetime";
    }
}

$output = [
    "items" => [
        [
            "title" => "{$result}",
            "arg"   => "{$result}",
            "icon"  => [
                "path"  => "./icon.png",
            ],
        ]
    ]
];

echo json_encode($output);
?>