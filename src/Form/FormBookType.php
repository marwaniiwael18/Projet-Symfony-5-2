<?php

namespace App\Form;

use App\Entity\Book;
use App\Entity\Author; // Assuming you have an Author entity, you should import it at the top.
use phpDocumentor\Reflection\PseudoTypes\True_;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FormBookType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('ref')
            ->add('title')
            ->add('category', ChoiceType::class, [ // Note the comma after 'category'
                'choices' => [
                    'Science' => 'science',
                    'Art' => 'art',
                    'Sport' => 'sport',
                ],
                'expanded' => True,
                'multiple' => false,
            ])
            ->add('published')
            ->add('publicationDate')
            ->add('idAuthor', EntityType::class, [
                'class' => Author::class,
                'choice_label' => 'username'
            ])
            ->add('Submit', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Book::class,
        ]);
    }
}
