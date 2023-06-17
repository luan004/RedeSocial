<?php
    require_once('db.php');
    require_once('auth.php');

    $query = $con->query("
    SELECT SUBSTRING_INDEX(SUBSTRING_INDEX(text, ' ', t.n), ' ', -1) as hashtag, COUNT(*) as total
    FROM posts
    CROSS JOIN (SELECT 1 n UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4) t
    WHERE DATE(dt) = CURDATE() AND text LIKE '#%'
    GROUP BY hashtag
    ORDER BY total DESC
    LIMIT 10
    ");
    

    header('Content-Type: application/json');
    echo json_encode($json, JSON_PRETTY_PRINT);
    $con->close();
    exit;
?>
