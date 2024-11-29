<?php

declare(strict_types=1);

namespace App\Tools;

use Exception;

/**
 * Extract class names with namespace from file
 */
class ClassNameByFIle
{
    /**
     * Returns an array of names of classes found in source.
     *
     * @return array<string>
     */
    public static function fromSource(string $source): array
    {
        $tokens = \token_get_all($source);
        $namespace = $class = $classLevel = $level = null;
        $classes = [];
        while (list(, $token) = each($tokens)) {
            switch (is_array($token) ? $token[0] : $token) {
                case T_NAMESPACE:
                    $namespace = ltrim(self::fetch($tokens, [T_STRING, T_NS_SEPARATOR]) . '\\', '\\');
                    break;
                case T_CLASS:
                case T_INTERFACE:
                    if ($name = self::fetch($tokens, T_STRING)) {
                        $classes[] = $namespace . $name;
                    }
                    break;
            }
        }

        return $classes;
    }

    /**
     * Returns an array of names of classes found in a file.
     *
     * @return array<string>
     */
    public static function fromFile(string $file): array
    {
        if (!\is_file($file)) {
            throw new Exception('File ' . $file . ' Does Not Exist');
        }

        $source = \file_get_contents($file);

        return self::fromSource($source);
    }

    private static function fetch(&$tokens, $take)
    {
        $res = null;
        while ($token = current($tokens)) {
            [$token, $s] = is_array($token) ? $token : [$token, $token];
            if (in_array($token, (array) $take, true)) {
                $res .= $s;
            } elseif (!in_array($token, [T_DOC_COMMENT, T_WHITESPACE, T_COMMENT], true)) {
                break;
            }
            next($tokens);
        }
        return $res;
    }
}
