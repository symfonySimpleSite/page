<?php

namespace SymfonySimpleSite\Page\Form;


use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use SymfonySimpleSite\Page\Entity\Page;
use SymfonySimpleSite\Page\Repository\PageRepository;

class PageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('type', ChoiceType::class, [
                'choices' => Page::getAllTypes()
            ])
            ->add('parent', EntityType::class, [
                'required' => false,
                'label' => 'Parent',
                'empty_data' => '',
                'class' => Page::class,
                'choice_value' => 'id',

                'query_builder' => function (PageRepository $pageRepository) {
                    return $pageRepository
                        ->getItemsQueryBuilder(1)
                        ->orderBy("{$pageRepository->getAlias()}.createdDate", "ASC")
                        ;
                }
            ])
            ->add('name')
            ->add('url')
            ->add('title')
            ->add('description')
            ->add('preview')
            ->add('body')
        ;
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Page::class,
        ]);
    }
}
