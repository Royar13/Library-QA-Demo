<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit0c953c4534aff2246b0a4427e162077e
{
    public static $classMap = array (
        'Book' => __DIR__ . '/../..' . '/server/classes/Book/Book.php',
        'BookBorrow' => __DIR__ . '/../..' . '/server/classes/Book/BookBorrow.php',
        'BookValidator' => __DIR__ . '/../..' . '/server/classes/Book/BookValidator.php',
        'Database' => __DIR__ . '/../..' . '/server/classes/Database.php',
        'ErrorLogger' => __DIR__ . '/../..' . '/server/classes/Validation/ErrorLogger.php',
        'Factory' => __DIR__ . '/../..' . '/server/classes/Factory.php',
        'IDatabaseAccess' => __DIR__ . '/../..' . '/server/classes/Interfaces/IDatabaseAccess.php',
        'IWriter' => __DIR__ . '/../..' . '/server/classes/Interfaces/IWriter.php',
        'InputValidator' => __DIR__ . '/../..' . '/server/classes/Validation/InputValidator.php',
        'JSONWriter' => __DIR__ . '/../..' . '/server/classes/JSONWriter.php',
        'Param' => __DIR__ . '/../..' . '/server/classes/Param.php',
        'Reader' => __DIR__ . '/../..' . '/server/classes/Reader/Reader.php',
        'ReaderValidator' => __DIR__ . '/../..' . '/server/classes/Reader/ReaderValidator.php',
        'User' => __DIR__ . '/../..' . '/server/classes/User/User.php',
        'Validation' => __DIR__ . '/../..' . '/server/classes/Validation/Validation.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->classMap = ComposerStaticInit0c953c4534aff2246b0a4427e162077e::$classMap;

        }, null, ClassLoader::class);
    }
}
