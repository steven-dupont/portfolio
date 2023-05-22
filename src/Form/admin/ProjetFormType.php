<?php

namespace App\Form\admin;

use App\Entity\Projet;
use App\Entity\ProjetType;
use App\Form\Type\SwitchType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;

class ProjetFormType extends AbstractType
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
                'label' => $this->translator->trans('projet.label.nom'),
                'attr' => [
                    'placeholder' => $this->translator->trans('projet.placeholder.nom'),
                ]
            ])
            ->add('imageFile', VichImageType::class, options: [
                'label' => $this->translator->trans('projet.label.image'),
                'required' => false,
                'delete_label' => 'Supprimer l\'image actuelle',
            ])
            ->add('lien_visuel', options: [
                'label' => $this->translator->trans('projet.label.lien_visuel'),
                'attr' => [
                    'placeholder' => $this->translator->trans('projet.placeholder.lien_visuel'),
                ]
            ])
            ->add('lien_telechargement', options: [
                'label' => $this->translator->trans('projet.label.lien_telechargement'),
                'attr' => [
                    'placeholder' => $this->translator->trans('projet.placeholder.lien_telechargement'),
                ]
            ])
            ->add('telechargement', SwitchType::class, options: [
                'label' => $this->translator->trans('projet.label.telechargement'),
                'required' => false,
            ])
            ->add('visuel', SwitchType::class, options: [
                'label' => $this->translator->trans('projet.label.visuel'),
                'required' => false,
            ])
            ->add('type',EntityType::class, options: [
                'class' => ProjetType::class,
                'choice_label' => 'nom',
                'label' => $this->translator->trans('projet.label.type'),
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Projet::class,
            'translation_domain' => 'projetForms',
        ]);
    }
}
