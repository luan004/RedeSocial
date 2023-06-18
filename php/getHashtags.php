<?php
    require_once('db.php');

    $query = $con->query("SELECT text FROM posts");

    if ($query->num_rows) {
        $wordCount = array();

        while ($row = $query->fetch_object()) {
            $text = $row->text;
            $words = explode(" ", $text);

            foreach ($words as $word) {
                if (!empty($word) && substr($word, 0, 1) === "#") {
                    if (array_key_exists($word, $wordCount)) {
                        $wordCount[$word]++;
                    } else {
                        $wordCount[$word] = 1;
                    }
                }
            }
        }

        $json = array();

        foreach ($wordCount as $word => $count) {
            $json[] = array(
                'word' => $word,
                'count' => $count
            );
        }
    } else {
        $json = array(
            'success' => false
        );
    }

    usort($json, function($a, $b) {
        return $b['count'] - $a['count'];
    });
    
    $json = array_slice($json, 0, 10);

    $json = array(
        'success' => true,
        'data' => $json
    );

    header('Content-Type: application/json');
    echo json_encode($json, JSON_PRETTY_PRINT);
    $con->close();
    exit;
?>
