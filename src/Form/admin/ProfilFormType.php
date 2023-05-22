<?php

namespace App\Form\admin;

use App\Entity\User;
use SebastianBergmann\CodeCoverage\Report\Text;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class ProfilFormType extends AbstractType
{
    private $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, options: [
                'label' => $this->translator->trans('profil.label.email'),
                'attr' => [
                    'placeholder' => $this->translator->trans('profil.placeholder.email'),
                ]
            ])
            ->add('prenom', TextType::class, options: [
                'label' => $this->translator->trans('profil.label.prenom'),
                'attr' => [
                    'placeholder' => $this->translator->trans('profil.placeholder.prenom'),
                ]
            ])
            ->add('nom', TextType::class, options: [
                'label' => $this->translator->trans('profil.label.nom'),
                'attr' => [
                    'placeholder' => $this->translator->trans('profil.placeholder.nom'),
                ]
            ])
            ->add('ville', TextType::class, options: [
                'label' => $this->translator->trans('profil.label.ville'),
                'attr' => [
                    'placeholder' => $this->translator->trans('profil.placeholder.ville'),
                ]
            ])
            ->add('telephone', TextType::class, options: [
                'label' => $this->translator->trans('profil.label.telephone'),
                'attr' => [
                    'placeholder' => $this->translator->trans('profil.placeholder.telephone'),
                ]
            ])
            ->add('presentation', TextareaType::class, options: [
                'label' => $this->translator->trans('profil.label.presentation'),
                'attr' => [
                    'rows' => '10',
                    'placeholder' => $this->translator->trans('profil.placeholder.presentation'),
                ]
            ])
            ->add('site', TextType::class, options: [
                'label' => $this->translator->trans('profil.label.site'),
                'attr' => [
                    'placeholder' => $this->translator->trans('profil.placeholder.site'),
                ]
            ])
            ->add('permis', TextType::class, options: [
                'label' => $this->translator->trans('profil.label.permis'),
                'attr' => [
                    'placeholder' => $this->translator->trans('profil.placeholder.permis'),
                ]
            ])
            ->add('linkedin', TextType::class, options: [
                'label' => $this->translator->trans('profil.label.linkedin'),
                'attr' => [
                    'placeholder' => $this->translator->trans('profil.placeholder.linkedin'),
                ]
            ])
            ->add('jobs', TextType::class, options: [
                'label' => $this->translator->trans('profil.label.jobs'),
                'attr' => [
                    'placeholder' => $this->translator->trans('profil.placeholder.jobs'),
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'translation_domain' => 'profilForms',
        ]);
    }
}
