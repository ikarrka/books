<?php



namespace App\Services;

/**
 * Description of AuthorService
 *
 * @author Igor
 */

use \App\Entity\Author;


class AuthorService {
    public function addDummyAuthors($entityManager) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://randomus.ru/name?type=101&sex=10&count=5");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        $words = curl_exec($ch);
        curl_close($ch);
        $words = preg_match('#<textarea id="result_textarea" (.+?)>#is', $words, $arr);
        $arr[0] = trim(str_replace(['<textarea id="result_textarea" class="textarea is-family-monospace" readonly','data-numbers="','">','>'], '', $arr[0]));
        $dummyAuthors = explode(",", $arr[0]);

        foreach($dummyAuthors as $key => $dummy) {
            $author = new Author();
            $author
                    ->setBooksCount(0)
                    ->setName($dummy)
                    ;
            $entityManager->persist($author);
            $entityManager->flush();
        }
        
        return;
        
    }
}
