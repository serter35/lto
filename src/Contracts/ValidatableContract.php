<?php

namespace SerterSerbest\LTO\Contracts;

use Illuminate\Validation\Validator;

interface ValidatableContract
{
    public function validate();

    public function makeValidation(): Validator;
}
