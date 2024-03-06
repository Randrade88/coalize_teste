<?php
namespace app\components;

use yii\validators\Validator;

class CpfValidator extends Validator
{
    public function validateAttribute($model, $attribute)
    {
        $value = $model->$attribute;

        $cpf = preg_replace('/[^0-9]/', '', $value);

        if (strlen($cpf) != 11) {
            $this->addError($model, $attribute, 'CPF precisa conter 11 digitos.');
            return;
        }

        for ($i = 0; $i < 2; $i++) {
            $sum = 0;
            for ($j = 0; $j < 9 + $i; $j++) {
                $sum += $cpf[$j] * (($i == 0 ? 10 : 11) - $j);
            }
            $remainder = ($sum * 10) % 11;
            if ($remainder != $cpf[9 + $i] && $remainder != 10) {
                $this->addError($model, $attribute, 'Invalid CPF.');
                return;
            }
        }
    }
}