<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class EditableTextType extends AbstractType
{
  public function getParent(): ?string
  {
    return TextType::class;
  }

  public function getName()
  {
    return 'editable_text';
  }

  public function getBlockPrefix(): string
  {
    return 'editable_text';
  }
}
