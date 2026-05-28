<?php

namespace App\Form;

use App\Application\Review\DTO\CreateReviewDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReviewType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('companyName')
            ->add('rating')
            ->add('reviewText')
            ->add('authorEmail')
            ->add('save', SubmitType::class, [
                'label' => 'Mentés',
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CreateReviewDTO::class,
        ]);
    }
}
