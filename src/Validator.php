<?php

class Validator
{
    public array $fields;
    public array $errors = [];

    public function __construct(array $fields)
    {
        $this->fields = $fields;
    }

    /**
     * @param string $field
     * @return self
     */
    public function required(string $field): self
    {
        if (empty($this->fields[$field])) {
            $this->errors[$field][] = "The {$field} field is required!";
        }

        return $this;
    }

    /**
     * @param string $field
     * @param string $value
     * @return self
     */
    public function email(string $value): self
    {
        if (! filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $this->errors['email'][] = "The email field is not a valid email address!";
        }

        return $this;
    }

    /**
     * @param string $field
     * @param integer $min
     * @return self
     */
    public function minimal(string $field, int $min): self
    {
        if (isset($this->fields[$field]) && strlen($this->fields[$field]) < $min) {
            $this->errors[$field][] = "The {$field} must contain at least {$min} characters";
        }

        return $this;
    }

    /**
     * @return boolean
     */
    public function failed(): bool
    {
        return ! empty($this->errors);
    }

    /**
     * @return boolean
     */
    public function passed(): bool
    {
        return empty($this->errors);
    }

    /**
     * @return array
     */
    public function errors(): array
    {
        return $this->errors;
    }

    /**
     * @return void
     */
    public function validate(): void
    {
        if ($this->failed()) {
            foreach ($this->errors() as $error) {
                echo "<p style='color: red;'> {$error[0]} </p>";
            }

            die;
        }
    }
}
