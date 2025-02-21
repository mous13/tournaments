<?php

declare(strict_types=1);

namespace Vanguard\Tournaments\Front\Form;

use Symfony\Component\Asset\Packages;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Vanguard\Tournaments\Core\Entity\Tournament;

class TournamentType extends AbstractType
{
    public function __construct(
        private readonly Packages $packages,
    ) {}

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Tournament::class,
        ]);
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $tournament = $options['data'] ?? null;

        $imagePreviewUrl = null;
        if ($tournament && $tournament->getImage()) {
            $imagePreviewUrl = $this->packages->getUrl($tournament->getImage(), 'tournaments.logo');
        }

        $builder
            ->add('name', TextType::class, [])
            ->add('visibility', ChoiceType::class, [
                'label' => 'Visibility',
                'help' => 'Invite only or public?',
                'choices' =>
                    [
                        'Invite Only' => 'invite',
                        'Public' => 'public',
                    ]
            ])
            ->add('participantType', ChoiceType::class, [
                'label' => 'Participant Type',
                'help' => 'Is this tournament for users vs users or teams vs teams?',
                'choices' =>
                    [
                        'Users' => 'users',
                        'Teams' => 'teams',
                    ]
            ])
            ->add('participantLimit', ChoiceType::class, [
                'label' => 'Participant Limit',
                'help' => 'Is this tournament for users vs users or teams vs teams?',
                'choices' =>
                    [
                        '2' => '2',
                        '4' => '4',
                        '6' => '6',
                        '8' => '8',
                        '10' => '10',
                        '12' => '12',
                        '14' => '14',
                        '16' => '16',
                        '18' => '18',
                        '20' => '20',
                        '22' => '22',
                        '24' => '24',
                        '26' => '26',
                        '28' => '28',
                        '30' => '30',
                    ]
            ])
            ->add('image', FileType::class, [
                'label' => 'vanguard.awards.admin.form.image',
                'required' => false,
                'mapped' => false,
                'attr' => [
                    'preview' => $imagePreviewUrl,
                ],
                'constraints' => [
                    new Assert\Image(
                        maxSize: '10M',
                    ),
                ],
            ]);
    }


}
