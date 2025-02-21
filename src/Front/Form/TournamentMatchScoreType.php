<?php

declare(strict_types=1);

namespace Vanguard\Tournaments\Front\Form;

use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vanguard\Tournaments\Core\Entity\TournamentMatch;

class TournamentMatchScoreType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => TournamentMatch::class,
        ]);
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder
            ->add('scoreOne', IntegerType::class, [
                'label' => 'Score for Participant One',
            ])
            ->add('scoreTwo', IntegerType::class, [
                'label' => 'Score for Participant Two',
            ]);
    }


}
