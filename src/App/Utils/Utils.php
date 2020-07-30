<?php

/**
 * Трейт Utils. Содержит общие вспомогательные методы
 *
 * @author    andrey-tech
 * @copyright 2019-2020 andrey-tech
 * @see https://github.com/andrey-tech/utils-php
 * @license   MIT
 *
 * @version 1.1.0
 *
 * v1.0.0 (14.07.2020) Начальный релиз
 * v1.1.0 (19.07.2020) Переход от единого класса утилит к трейтам утилит
 *
 */

declare(strict_types = 1);

namespace App\Utils;

trait Utils
{
    /**
     * Проверяет является ли значение перевенной НЕ ассоциативным (числовым) массивом
     * @param  mixed  $variable Переменная
     * @return boolean
     */
    public static function isNumericArray($variable) :bool
    {
        if (! is_array($variable)) {
            return false;
        }

        foreach (array_keys($variable) as $key) {
            if ($key !== (int) $key) {
                return false;
            }
        }

        return true;
    }
}
