<?php

namespace App\Form\public;

use App\Entity\Contact;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class ContactFormType extends AbstractType
{
    private $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, options: [
                'attr' => [
                    'placeholder' => $this->translator->trans('contact.placeholder.nom')
                ]
            ])
            ->add('mail', EmailType::class, options: [
                'attr' => [
                    'placeholder' => $this->translator->trans('contact.placeholder.mail')
                ]
            ])
            ->add('message', TextareaType::class, options: [
                'attr' => [
                    'placeholder' => $this->translator->trans('contact.placeholder.mail')
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
            'attr' => [
                'class' => 'formulaire',
            ],
            'translation_domain' => 'contactForms',
        ]);
    }
}
