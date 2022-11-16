<?php

namespace Application\Form;

use Laminas\Form\Form;
use Laminas\I18n\Validator\IsFloat;
use Laminas\InputFilter\InputFilterProviderInterface;

class UsunForm extends Form implements InputFilterProviderInterface
{
    public function __construct()
    {
        parent::__construct('usun');

        $this->setAttributes(['method' => 'post', 'class' => 'form']);
        $this->add([
            'name' => 'Tak',
            'type' => 'Submit',
            'attributes' => [
                'value' => 'Tak',
                'class' => 'btn btn-primary',
            ],
        ]);
        $this->add([
            'name' => 'Nie',
            'type' => 'Submit',
            'attributes' => [
                'value' => 'Nie',
                'class' => 'btn btn-primary',
            ],
        ]);
    }

    public function getInputFilterSpecification()
    {
        return [
        ];
    }
}