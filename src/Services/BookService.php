<?php
namespace App\Services;

/**
 * Description of BookService
 *
 * @author Igor
 */

use App\Entity\Book;
use App\Entity\Author;

class BookService /*extends AbstractController*/{

    public static function addDummyBooks($entityManager) {

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://randomus.ru/name?type=101&sex=10&count=5");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        $words = curl_exec($ch);

        curl_close($ch);
        $words = preg_match('#<textarea id="result_textarea" (.+?)>#is', $words, $arr);
        $arr[0] = trim(str_replace(['<textarea id="result_textarea" class="textarea is-family-monospace" readonly','data-numbers="','">','>'], '', $arr[0]));
        $dummyBooks = explode(",", $arr[0]);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://randomus.ru/name?type=101&sex=10&count=5");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        $words = curl_exec($ch);
        curl_close($ch);
        $words = preg_match('#<textarea id="result_textarea" (.+?)>#is', $words, $arr);
        $arr[0] = trim(str_replace(['<textarea id="result_textarea" class="textarea is-family-monospace" readonly','data-numbers="','">','>'], '', $arr[0]));
        $dummyAuthors = explode(",", $arr[0]);

        $flagSuccess = false;        
        foreach($dummyBooks as $key => $dummy) {
            $flagSuccess = true;;
            //insert author
            $author = new Author();
            $author
                ->setBooksCount(1)
                ->setName(trim($dummyAuthors[$key]));
            $entityManager->persist($author);
            $entityManager->flush();
            
            //insert book
            $arr = explode(" ", $dummy);
            $title = $arr[1]."er";
            
            $description = file_get_contents('https://loripsum.net/api/1/short');
            $description = preg_match('/[.](.+?)[\.,\,,\;,\:,\?,\!]/',$description, $arr);
            $description = trim(str_replace([".",",",";",":","?","!"], "", $arr[0])  );
            
            $book = new Book();
            $book
                ->setTitle(trim($title))
                ->setDescription($description)
                ->setYear(2022);
            
            $book->addAuthor($author);
            
            $entityManager->persist($book);
            $entityManager->flush();
            
        }
        if (!$flagSuccess) {
            throw new Exception("Something went wrong");
        }
        return;
        
    }
}
