<?php

namespace App\Admin;

use App\Entity\Author;
use App\Entity\Book;
use Doctrine\ORM\EntityRepository;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\CollectionType;
use Sonata\AdminBundle\Form\Type\ModelType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

use \Symfony\Bridge\Doctrine\Form\Type\EntityType;


final class BookAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper) : void
    {
        $formMapper->add('title', TextType::class);
        $formMapper->add('description', TextareaType::class);
        $formMapper->add('year', TextareaType::class);
        $formMapper->add('authors', EntityType::class, [
            'label' => 'Authors',
            'class' => Author::class,
            'query_builder' => function(EntityRepository $er) /*use ($options)*/ {
                $query = $er->createQueryBuilder('r');
                return $query;
            },
            'choice_label' => 'name',
            'placeholder' => 'Select',
            'attr' => ['style' => 'width:400px;height:100px'],
            'multiple' => true,
//                        'mapped' => false,
            'constraints' => array(
                new \Symfony\Component\Validator\Constraints\Count(['min' => 1, 'minMessage' => 'Select at least 1 author'])
            )
        ]);
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper) : void
    {
        $datagridMapper->add('title');
        $datagridMapper->add('description');
        $datagridMapper->add('year');
    }

    protected function configureListFields(ListMapper $listMapper) : void
    {
        $listMapper->addIdentifier('title');
        $listMapper->addIdentifier('description');
        $listMapper->addIdentifier('year');
    }

}