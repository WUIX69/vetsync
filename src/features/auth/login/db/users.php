<?php

function single($uuid)
{
    global $conn;

    $stmt = $conn->prepare('SELECT * FROM users WHERE uuid=? LIMIT 1');
    $stmt->execute([$uuid]);
    return $stmt->fetch(PDO::FETCH_ASSOC) ?? [];
}

function singleWhereUserEmail($email)
{
    global $conn;

    $stmt = $conn->prepare("SELECT * FROM users WHERE email=? LIMIT 1");
    $stmt->execute([$email]);
    return $stmt->fetch(PDO::FETCH_ASSOC) ?? [];
}

function singleWhereAdminEmail($email)
{
    return [];
}

