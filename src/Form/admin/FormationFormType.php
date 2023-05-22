<?php

namespace App\Form\admin;

use App\Entity\Formation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class FormationFormType extends AbstractType
{
    private $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('poste', options:[
                'label' => $this->translator->trans('formation.label.poste'),
                'attr' => [
                    'placeholder' => $this->translator->trans('formation.placeholder.poste'),
                ],
            ])
            ->add('date', options: [
                'label' => $this->translator->trans('formation.label.date'),
                'attr' => [
                    'placeholder' => $this->translator->trans('formation.placeholder.date'),
                ],
            ])
            ->add('localisation', options: [
                'label' => $this->translator->trans('formation.label.localisation'),
                'attr' => [
                    'placeholder' => $this->translator->trans('formation.placeholder.localisation'),
                ],
            ])
            ->add('contenu', options:[
                'label' => $this->translator->trans('formation.label.contenu'),
                'attr' => [
                    'class' => 'tinymce d-none',
                    'data-tinymce' => 'data-tinymce',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Formation::class,
            'translation_domain' => 'formationForms',
        ]);
    }
}
