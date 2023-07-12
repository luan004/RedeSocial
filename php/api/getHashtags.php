<?php
    require_once('../connection/Conn.php');
    require_once('../dao/PostDAO.php');
    require_once('../models/Post.php');

    $maxNumberOfHashtags = 10;
    $opt = 'today'; // 'today' ou 'all'

    $conn = new Conn();

    $postDAO = new PostDAO($conn);
    $posts = $postDAO->getHashtags($opt);

    // Array de chaves numeradas (a chave é a posição do array, o valor é a hashtag)
    $hashtagsWords = array();

    // Array de chaves nomeasdas (a chave é a hashtag, o valor é a quantidade de vezes que ela foi utilizada)
    $hashtagsCount = array();

    foreach ($posts as $post) {
        $text = $post->getText();
        $words = explode(" ", $text);
        foreach ($words as $word) {

            // Checa se a palavra é uma hashtag
            if (!empty($word) && substr($word, 0, 1) === "#") {

                // Checa se essa hashtag já existe no array, caso não, adiciona ela no array
                // Isso evita que a mesma hashtag seja contada mais de uma vez
                if (in_array($word, $hashtagsWords) == false) {
                    $hashtagsWords[] = $word;
                }

                // Conta quantas vezes a hashtag foi utilizada e armaena no array de contagem
                if (array_key_exists($word, $hashtagsCount)) {
                    $hashtagsCount[$word]++;
                } else {
                    $hashtagsCount[$word] = 1;
                }
            }
        }
    }

    foreach ($hashtagsWords as $word) {
        $hashtags[] = array(
            'word' => $word,
            'count' => $hashtagsCount[$word]
        );
    }

    // Ordena o array de hashtags pela quantidade de vezes que ela foi utilizada
    usort($hashtags, function($a, $b) {
        return $b['count'] - $a['count'];
    });

    // Limita o valor máximo de hashtags a 100, para evitar a solicitação de um numero muito alto de hashtags
    if ($maxNumberOfHashtags > 100) {
        $maxNumberOfHashtags = 100;
    }

    // Limita o array a quantidade máxima de hashtags
    $hashtags = array_slice($hashtags, 0, $maxNumberOfHashtags);

    header('Content-Type: application/json');
    echo json_encode($hashtags, JSON_PRETTY_PRINT);
    $conn->close();
    exit;

?>