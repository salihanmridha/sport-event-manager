<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class PhoneUniqueRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(
        protected $model,
        protected $phoneCode,
        protected $columnNamePhone,
        protected $ownerId = null
    )
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        $exists = $this->model->where('phone_code', $this->phoneCode)
            ->where($this->columnNamePhone, $value)
            ->when($this->ownerId, function ($q){ $q->where('id', '!=', $this->ownerId);})
            ->exists();

        return !$exists;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return __('validation.exists', ['attribute' => 'Phone number']);
    }
}
