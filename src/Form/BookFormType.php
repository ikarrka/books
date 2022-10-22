<?php

namespace App\Form;

use App\Entity\Book;
use App\Entity\Author;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\FileType;
use \Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use \Symfony\Bridge\Doctrine\Form\Type\EntityType;

use Symfony\Component\Validator\Constraints\File;
use \Doctrine\ORM\EntityRepository;

class BookFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('description')
            ->add('year')
            ->add('authors', EntityType::class, [
               'label' => 'Authors',
                'class' => Author::class,
                'query_builder' => function(EntityRepository $er) use ($options) {
                    $query = $er->createQueryBuilder('r');
//                    if ($options['status']>0) {
//                        $query->where($query)
//                    }
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
            ])
            ->add('cover', FileType::class, [
                'label' => 'Cover (Image file)',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/jpeg',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid Image document',
                    ])
                ],
            ])                                        
            ->add('Save', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Book::class,
        ]);
    }
}
