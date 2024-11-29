<?php

namespace App\Tools;

use Exception;

/**
 * Search for files in a directory
 */
class FindFilesRecursivelyInDirectory
{
    protected string $path;
    protected array $filterExt = [];

    public function __construct(string $path)
    {
        $this->path = $path;
    }

    public function find(array $filterExt = []): array
    {
        $this->filterExt = $filterExt;

        if (!\is_dir($this->path)) {
            throw new Exception('DirectoryDoesNotExist');
        }

        $iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator(
            $this->path,
            \RecursiveDirectoryIterator::FOLLOW_SYMLINKS,
        ));

        $files = [];

        foreach ($iterator as $fileInfo) {
            /** @var \SplFileInfo $fileInfo */
            if (!$fileInfo->isFile()) {
                continue;
            }

            if ($this->filterExt && !in_array($fileInfo->getExtension(), $this->filterExt)) {
                continue;
            }

            //fix phar path
            $files[] = $fileInfo->getRealPath() ?: $fileInfo->getPathname();
        }

        return $files;
    }
}
