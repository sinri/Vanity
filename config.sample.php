<?php
/**
 * Created by PhpStorm.
 * User: Sinri
 * Date: 2018/9/29
 * Time: 21:08
 */

$config = [
    // The title, change as you need
    "title" => "Vanity System",
    // File store directory path, begin with "." or "./" is recommended
    "store" => './test-store',
    // User with tokens could access to those paths, you can use pattern here
    "permission" => [
        "sample_token" => [
            "./test-store",
        ],
    ],
    // User with tokens could not access to those paths, you can use pattern here
    "forbidden" => [
        "sample_token" => [
            "./test-store/dir1/3.txt",
        ]
    ]
];