<?php


namespace App\EventListener;

/**
 * Description of BookListener
 *
 * @author Igor
 */

use App\Entity\Book;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use App\Repository\AuthorRepository;
use Doctrine\Persistence\ManagerRegistry;

class BookListener {
    /**
     * some comment
     */

    private $em;

    public function __construct(ManagerRegistry $em)
    {
        $this->em = $em;
    }   
 
    public function postUpdate() {
        $repo = new AuthorRepository($this->em);
        $repo->updateBooksCount();
    }
    
    public function postPersist() {
        $this->postUpdate();
    }

    public function postRemove() {
        $this->postUpdate();
    }

}
