<?php

namespace App\Form;

use App\Entity\Article;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Category;
use Vich\UploaderBundle\Form\Type\VichImageType;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Titre')
            ->add('Description')
            ->add('Date')
            ->add('category', EntityType::class, ['class' => Category::class, 'choice_label' => 'label'])
            ->add('imageFile', VichImageType::class, array('required' => false));
        // ->add('imageFile', VichImageType::class, [
        //     'label' => 'Image du article',
        //     'label_attr' => [
        //         'class' => 'form-mabel mt-4'
        //     ]
        // ])
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
