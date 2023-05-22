<?php

namespace App\Form\admin;

use App\Entity\Competence;
use App\Entity\CompetenceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class CompetenceFormType extends AbstractType
{
    private $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', options: [
                'label' => $this->translator->trans('competence.label.nom'),
                'attr' => [
                    'placeholder' => $this->translator->trans('competence.placeholder.nom'),
                ]
            ])
            ->add('framework', options: [
                'label' => $this->translator->trans('competence.label.framework'),
                'attr' => [
                    'placeholder' => $this->translator->trans('competence.placeholder.framework'),
                ]
            ])
            ->add('type', EntityType::class, [
                'class' => CompetenceType::class,
                'choice_label' => 'nom',
                'label' => $this->translator->trans('competence.label.type'),
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Competence::class,
            'translation_domain' => 'competenceForms',
        ]);
    }
}
