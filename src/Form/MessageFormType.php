<?php

namespace App\Form;

use App\Entity\Message;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;

class MessageFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('texte', null, [
                'label' => false
            ])
            // ->add('Annuler', ButtonType::class, [
            //     'attr' => ['class' => 'btn-add']
            // ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Message::class,
        ]);
    }
}
