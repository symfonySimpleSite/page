<?php

namespace SymfonySimpleSite\Page\Form;


use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use SymfonySimpleSite\Page\Entity\Page;
use SymfonySimpleSite\Page\Repository\PageRepository;
use Symfony\Component\Validator\Constraints\File;

class PageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('name')
            ->add('url', TextType::class, [
                'required'=>false
            ])
            ->add('title', TextType::class, [
                'required'=>false
            ])
            ->add('image', FileType::class, [
                'label' => 'Image (png /gif / jp(e)g file)',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/png',
                            'image/gif',
                            'image/jpg',
                            'image/jpeg',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid (png|gif|jp(e)g filet',
                    ])
                ],
            ])
            ->add('isRecentlyPreview', CheckboxType::class, [
                'required'=>false
            ])
            ->add('description', TextareaType::class, [
                'required'=>false
            ])
            ->add('preview', CKEditorType::class, [
                'required'=>false
            ])
            ->add('body', CKEditorType::class, [
                'required'=>false
            ])
        ;
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Page::class,
        ]);
    }
}
