<?php

namespace App\Traits;

use Illuminate\Support\Facades\Crypt;

trait Encryptable
{
    public function getAttribute($key)
    {
        $value = parent::getAttribute($key);

        if (in_array($key, $this->encryptable)) {
            if (isset($value) && ! empty($value)) {
                $value = Crypt::decrypt($value);
            }

            return $value;
        }

        return $value;
    }

    public function setAttribute($key, $value)
    {

        if (in_array($key, $this->encryptable)) {
            $value = Crypt::encrypt($value);
        }

        return parent::setAttribute($key, $value);
    }
}
