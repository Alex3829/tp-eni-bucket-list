<?php

namespace App\Form;

use App\Entity\Wish;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Event\PreSetDataEvent;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;


class WishType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Your idea'
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Please describe it',
                'required' => false
            ])
            ->add('author', TextType::class, [
                'label' => 'Your username'
            ])
            ->add('image', FileType::class, [
                'label' => 'Image',
                'required' => false,
                'mapped' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1000k',
                        'mimeTypes' => ['image/jpeg', 'image/png'],
                        'mimeTypesMessage' => 'Please upload a valid image (JPG or PNG)',
                    ]),
                ],
            ])
            ->addEventListener(FormEvents::PRE_SET_DATA, function (PreSetDataEvent $event): void {
                $wish = $event->getData();
                $form = $event->getForm();

                if ($wish->getImageName()) {
                    $form->add('deleteImage', CheckboxType::class, [
                        'label' => 'Delete image',
                        'required' => false,
                        'mapped' => false,
                        'value' => false
                    ]);
                }
            });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Wish::class,
        ]);
    }
}
