<?php
/**
 * Created by PhpStorm.
 * User: prateek
 * Date: 21/09/15
 * Time: 8:22 PM
 */

namespace Lp\Framework\Core\Store;

use Lp\Framework\Exceptions\DuplicateFileNameException;

abstract class StoreParent implements Store
{

    public function getFileAndPutWithStoreKeeper($key, $dirToSearch = null)
    {
        try {
            $fileInfo = $this->checkFileExists($key, $dirToSearch);
            StoreKeeper::putFileInfoWithStoreKeeper($key, $fileInfo);
        } catch(\Exception $e) {
            throw $e;
        }
        return $this->saveInStore($key, new $fileInfo['fullFileName']);

    }

    /**
     * Checks for $fileName in a $dirToSearch
     *
     * @param $fileName
     * @param null $dirToSearch
     * @return mixed
     * @throws DuplicateFileNameException
     */
    private function checkFileExists($fileName, $dirToSearch = null)
    {

        $namespace = $fullFileName = '';
        $uniqueCount =0;

        // full path to the directory to search
        // defaults to controllers
        $fullPath = APPLICATION_PATH.((is_null($dirToSearch)) ? self::DEFAULT_DIR_TO_SEARCH : $dirToSearch);

        // Recursive directory Iterator
        $directory = new \RecursiveDirectoryIterator($fullPath, \FilesystemIterator::SKIP_DOTS);

        // For each file / path lets check if the file, we are looking for, exists or not
        foreach (new \RecursiveIteratorIterator($directory, \RecursiveIteratorIterator::SELF_FIRST) as $index => $path ) {
            $fileNameWithoutExtension = str_replace(".php","",$path->getFileName());
            if ($path->isFile()
                && "php" === $path->getExtension()
                && (0 === strcasecmp($fileName, $fileNameWithoutExtension)))
            {
                $namespace = str_replace("/", "\\",str_replace(dirname(BASE_PATH)."/","",dirname($path->__toString())));
                $fullFileName = $namespace.'\\'.$fileNameWithoutExtension;
                $uniqueCount++;
            }
        }

        // only one file should ever be found in this tree.
        // we should have found exactly one file
        // in dir to search or none
        // if more than one throw exception in next step.

        if (1 === $uniqueCount) {
            return compact("namespace", "fullFileName");
        } elseif(0 === $uniqueCount) {
            return false;
        }
        throw new DuplicateFileNameException("More than two or none files with same name {$fileName} in {$fullPath} domain.");
    }

    protected function checkKeyValue($key, $value)
    {
        if (is_null($key) || empty($key)) {
            throw new \Exception("Illegal key {$key} for the store.");
        }
        if(is_null($value) || empty($value)) {
            throw new \Exception("Illegal value {$value} against key {$key} for the store.");
        }
    }

}