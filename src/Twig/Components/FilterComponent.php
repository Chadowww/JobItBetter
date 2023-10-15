<?php

namespace App\Twig\Components;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormView;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent()]
final class FilterComponent
{
    private FormView $form;
    private int $min;
    private int $max;

    public function __construct()
    {
    }
    public function mount(FormView $form, int $min, int $max): void
    {
        $this->form = $form;
        $this->min = $min;
        $this->max = $max;
    }

    public function getForm(): FormView
    {
        return $this->form;
    }

    public function getMin(): int
    {
        return $this->min;
    }

    public function getMax(): int
    {
        return $this->max;
    }
}
