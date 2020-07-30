<?php

/**
 * Трейт FileUtils. Содержит общие методы для работы с файлами и каталогами
 *
 * @author    andrey-tech
 * @copyright 2019-2020 andrey-tech
 * @see https://github.com/andrey-tech/utils-php
 * @license   MIT
 *
 * @version 1.1.1
 *
 * v1.0.0 (14.07.2020) Начальный релиз
 * v1.1.0 (19.07.2020) Переход от единого класса утилит к трейтам утилит
 * v1.1.1 (30.07.2020) Исправлен метод getAbsoluteFileName()
 *
 */

declare(strict_types = 1);

namespace App\Utils;

use App\AppException;

trait FileUtils
{
    /**
     * Возвращает абсолютное имя файла и создает необходимые каталоги рекурсивно
     * @param string $relativeFileName Относительное имя файла
     * @param bool $createDir Создавать каталоги рекурсивно
     * @return string|null
     * @throws AppException
     */
    public function getAbsoluteFileName(string $relativeFileName, bool $createDir = true)
    {
        // Проверяем наличие каталога для файла во всех путях включения
        $includePath = explode(PATH_SEPARATOR, get_include_path());
        foreach ($includePath as $path) {
            $absoluteFileName = $path . DIRECTORY_SEPARATOR . $relativeFileName;
            $checkDir = dirname($absoluteFileName);
            if (is_dir($checkDir)) {
                return $absoluteFileName;
            }
        }

        if (! $createDir || empty($includePath)) {
            return null;
        }

        // Создаем необходимые каталоги по первому пути включения
        $path = array_shift($includePath);
        $absoluteFileName = $path . DIRECTORY_SEPARATOR . $relativeFileName;
        $checkDir = dirname($absoluteFileName);
        if (! mkdir($checkDir, $mode = 0755, $recursive = true)) {
            throw new AppException("Can't create dir '{$checkDir}'");
        }

        return $absoluteFileName;
    }
}
