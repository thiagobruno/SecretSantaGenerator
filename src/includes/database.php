<?php

/**
 * set the default timezone to use
 */
date_default_timezone_set($timezone); 

/**
 * CREATE DATABASE CONNECTION
 */
$conn = new mysqli($servername, $username, $password, $dbname, $dbport);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function executeQuery($sql, $params) {
    global $conn;
    $stmt = $conn->prepare($sql);
    $stmt_type = '';
    $stmt_value = [];
    foreach($params as $param) {
        $stmt_type .= $param['type'];
        array_push($stmt_value, $param['value']);
    }
    $stmt->bind_param($stmt_type, ...$stmt_value);
    $stmt->execute();
    $conn->close();
}