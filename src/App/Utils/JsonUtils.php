<?php

/**
 * Трейт JsonUtils. Содержит методы для преобразования данных в формат JSON и обратно
 *
 * @author    andrey-tech
 * @copyright 2019-2020 andrey-tech
 * @see https://github.com/andrey-tech/utils-php
 * @license   MIT
 *
 * @version 1.2.0
 *
 * v1.0.0 (14.07.2020) Начальный релиз
 * v1.1.0 (19.07.2020) Переход от единого класса утилит к трейтам утилит
 * v1.1.1 (22.07.2020) Рефракторинг
 * v1.2.0 (30.07.2020) Дополнительные опции кодирования/декодирования теперь могут быть int.
 *                     В метод fromJson() параметр $assoc
 */

declare(strict_types = 1);

namespace App\Utils;

use App\AppException;

trait JsonUtils
{
    /**
     * Кодирует данные в строку JSON
     * @param mixed $data Данные для преобразования
     * @param array|int $encodeOptions Дополнительные опции кодирования
     * @return string
     * @throws AppException
     */
    public function toJson($data, $encodeOptions = []) :string
    {
        $options = JSON_UNESCAPED_UNICODE;
        if (is_array($encodeOptions)) {
            foreach ($encodeOptions as $option) {
                $options |= $option;
            }
        } else {
            $options |= $encodeOptions;
        }

        $jsonParams = json_encode($data, $options);
        if ($jsonParams === false) {
            $errorMessage = json_last_error_msg();
            throw new AppException("Can't JSON encode ({$errorMessage}): " . print_r($data, true));
        }

        return $jsonParams;
    }

    /**
     * Декодирует строку JSON
     * @param  string $json Строка JSON
     * @param  bool $assoc Преобразовывать возвращаемые объекты в ассоциативные массивы
     * @param  array|int $decodeOptions Дополнительные опции декодирования
     * @return mixed
     * @throws AppException
     */
    public function fromJson(string $json, bool $assoc = true, $decodeOptions = [])
    {
        $options = 0;
        if (is_array($decodeOptions)) {
            foreach ($decodeOptions as $option) {
                $options |= $option;
            }
        } else {
            $options |= $decodeOptions;
        }

        $data = json_decode($json, $assoc, $depth = 512, $options);
        if (is_null($data)) {
            $errorMessage = json_last_error_msg();
            throw new AppException("Can't JSON decode ({$errorMessage}): '{$data}'");
        }

        return $data;
    }
}
