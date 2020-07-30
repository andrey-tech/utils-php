# Utils PHP

Набор трейтов, содержащих общие вспомогательные методы для работы с файлами, каталогами, данными в формате JSON и т.п.

# Содержание

<!-- MarkdownTOC levels="1,2,3,4,5,6" autoanchor="true" autolink="true" -->

- [Требования](#%D0%A2%D1%80%D0%B5%D0%B1%D0%BE%D0%B2%D0%B0%D0%BD%D0%B8%D1%8F)
- [Установка](#%D0%A3%D1%81%D1%82%D0%B0%D0%BD%D0%BE%D0%B2%D0%BA%D0%B0)
- [Трейт `FileUtils`](#%D0%A2%D1%80%D0%B5%D0%B9%D1%82-fileutils)
- [Трейт `JsonUtils`](#%D0%A2%D1%80%D0%B5%D0%B9%D1%82-jsonutils)
- [Трейт `Utils`](#%D0%A2%D1%80%D0%B5%D0%B9%D1%82-utils)
- [Автор](#%D0%90%D0%B2%D1%82%D0%BE%D1%80)
- [Лицензия](#%D0%9B%D0%B8%D1%86%D0%B5%D0%BD%D0%B7%D0%B8%D1%8F)

<!-- /MarkdownTOC -->

<a id="%D0%A2%D1%80%D0%B5%D0%B1%D0%BE%D0%B2%D0%B0%D0%BD%D0%B8%D1%8F"></a>
## Требования

- PHP >= 7.0.
- Произвольный автозагрузчик классов, реализующий стандарт [PSR-4](https://www.php-fig.org/psr/psr-4/).

<a id="%D0%A3%D1%81%D1%82%D0%B0%D0%BD%D0%BE%D0%B2%D0%BA%D0%B0"></a>
## Установка

Установка через composer:
```
$ composer require andrey-tech/utils-php
```

или добавить

```
"andrey-tech/utils-php"
```

в секцию require файла composer.json.

<a id="%D0%A2%D1%80%D0%B5%D0%B9%D1%82-fileutils"></a>
# Трейт `FileUtils`

Трейт `\App\Utils\FileUtils` содержит общие вспомогательные методы для работы с файлами и каталогами.  
При возникновении ошибок выбрасывается исключение с объектом класса `\App\AppException`.

- `getAbsoluteFileName(string $relativeFileName, bool $createDir = true) :?string`  
    Возвращает абсолютное имя файла.  
    * `$relativeFileName` - относительное имя файла для поиска в путях включения;
    * `$createDir` - создавать необходимые каталоги рекурсивно.
    
    Метод ищет *предполагаемое* местонахождение файла в путях включения (include_path) 
    по имени каталога, в переданном относительном имени файла (то есть сам файл и каталог могут отсутствовать).  
    Создает необходимые каталоги рекурсивно по первому пути включения.  
    Возвращает абсолютное имя файла или `null`, если каталог файла отсутствует и `$createDir = false`.  

    Пример:

    * пути включения (include_path): `.:/php/includes:/php/phar`;
    * относительное имя файла: `$relativeFileName = 'protected/temp/debug.log'`;
    * включено создание необходимых каталоги рекурсивно: `$createDir = true`.
    
    Метод `getAbsoluteFileName()` будет искать каталог `protected/temp/` по каждому из путей включения: `.`, `/php/includes`, `/php/phar`.
    Если каталог `protected/temp/` не найден ни по одному из путей включения, то метод создаст каталог `protected/temp/` рекурсивно по первому пути включения - `.`
    и вернет абсолютное имя файла в этом каталоге - `./protected/temp/debug.log`.  
    Если каталог `protected/temp/` будет найден в одном из путей включения, то метод сразу вернет абсолютное имя файла в этом каталоге.

```php
use \App\Utils\FileUtils;

class Example
{
    use FileUtils;

    public function __construct()
    {
        echo get_include_path() . PHP_EOL;
        $relativeFileName = 'protected/temp/debug.log';
        $absFileName = $this->getAbsoluteFileName($relativeFileName, $createDir = true);
        echo $absFileName . PHP_EOL;
    }
}

$e = new Example();
```

<a id="%D0%A2%D1%80%D0%B5%D0%B9%D1%82-jsonutils"></a>
# Трейт `JsonUtils`

Трейт `\App\Utils\JsonUtils` содержит общие вспомогательные методы для преобразования данных в формат JSON и обратно.
При возникновении ошибок выбрасывается исключение с объектом класса `\App\AppException`.

- `toJson(mixed $data, array|int $encodeOptions = []) :string` Кодирует данные в строку JSON.  
    * `$data` - данные для преобразования;
    * `$encodeOptions` - дополнительные [опции кодирования](https://www.php.net/manual/ru/json.constants.php) в виде массива или битовой маски.

- `fromJson(string $json, bool $assoc = true, array|int $decodeOptions = [])` Декодирует строку JSON.   
    * `$json` - строка JSON для декодирования;
    * `$assoc` - преобразовывать возвращаемые объекты в ассоциативные массивы;
    * `$decodeOptions` - дополнительные [опции декодирования](https://www.php.net/manual/ru/json.constants.php) в виде массива или битовой маски.

```php
use \App\Utils\JsonUtils;

class Example
{
    use JsonUtils;

    public function __construct()
    {
        $data = [
            'array' => [ 0 => 0, 1 => 1, 2 => 2, 3 => 3 ],
            'object' => [ 'a' => '1', 'b' => 2, 'c' => 3 ],
        ];

        $json1 = $this->toJson($data, $encodeOptions = JSON_PRETTY_PRINT|JSON_PARTIAL_OUTPUT_ON_ERROR);
        echo $json1. PHP_EOL;

        $json2= $this->toJson($data, $encodeOptions = [ JSON_PRETTY_PRINT, JSON_FORCE_OBJECT ]);
        echo $json2. PHP_EOL;

        $data1 = $this->fromJson($json1);
        print_r($data1);

        $data2 = $this->fromJson($json2, $assoc = false);
        print_r($data2);
    }
}

$e = new Example();
```

<a id="%D0%A2%D1%80%D0%B5%D0%B9%D1%82-utils"></a>
# Трейт `Utils`

Трейт `\App\Utils\Utils` содержит другие общие вспомогательные методы.  
При возникновении ошибок выбрасывается исключение с объектом класса `\App\AppException`.

- `isNumericArray(mixed $variable) :bool` Проверяет, что значение переменной является НЕ ассоциативным (числовым) массивом.
    * `$variable` - переменная для проверки.

```php
use \App\Utils\Utils;

class Example
{
    use Utils;

    public function __construct()
    {
        $data1 = [ 0 => 0, 1 => 1, 2 => 2, 3 => 3 ];
        var_dump($this->isNumericArray($data1));

        $data2 = [ 'a' => '1', 'b' => 2, 'c' => 3 ];
        var_dump($this->isNumericArray($data2));
    }
}

$e = new Example();
```

<a id="%D0%90%D0%B2%D1%82%D0%BE%D1%80"></a>
## Автор

© 2020 andrey-tech

<a id="%D0%9B%D0%B8%D1%86%D0%B5%D0%BD%D0%B7%D0%B8%D1%8F"></a>
## Лицензия

Данный код распространяется на условиях лицензии [MIT](./LICENSE).
