<?php
/**
 * Created by PhpStorm.
 * User: prateek
 * Date: 5/09/15
 * Time: 7:30 PM
 */
namespace Lp\Framework\Base;

use Lp\Framework\Exceptions\DuplicateFileNameException;

trait BaseFunctions
{
    /**
     * Checks for $fileName in a $dirToSearch
     *
     * @param $fileName
     * @param $fileStore $fileStore instance from the class
     * @param null $dirToSearch
     * @return mixed
     * @throws DuplicateFileNameException
     */
    private function checkFileExists($fileName, $dirToSearch = null)
    {

        $namespace = $fullFileName = '';
        $uniqueCount =0;

        //lets check if the file is in store or not.
        $returnArray = Store::getFromStore(Store::STORE_TYPE_FILE, $fileName);

        if (!$returnArray) {

            // full path to the directory to search
            // defaults to controllers
            $fullPath = APPLICATION_PATH.((is_null($dirToSearch)) ? "controllers" : $dirToSearch);

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
            // if two files exists throw an Exception

            if (1 === $uniqueCount) {

                $returnArray = compact("namespace", "fullFileName");
                // lets cache this result for performance sake
                Store::saveInStore(Store::STORE_TYPE_FILE, $fileName, $returnArray);

            } else {
                throw new DuplicateFileNameException("More than two or none files with same name {$fileName} in {$fullPath} domain.");
            }
        }

        // either we should have stored it
        // or file should be in the store
        // or exception would have been raised.
        return $returnArray;
    }

    /**
     * Use reflection class to cast one obj type to other
     *
     * Long live Stack overflow for such solutions
     * @param $destination
     * @param $sourceObject
     * @return mixed
     */
    private function cast($destination, $sourceObject)
    {
        if (is_string($destination)) {
            $destination = new $destination();
        }
        $sourceReflection = new ReflectionObject($sourceObject);
        $destinationReflection = new ReflectionObject($destination);
        $sourceProperties = $sourceReflection->getProperties();
        foreach ($sourceProperties as $sourceProperty) {
            $sourceProperty->setAccessible(true);
            $name = $sourceProperty->getName();
            $value = $sourceProperty->getValue($sourceObject);
            if ($destinationReflection->hasProperty($name)) {
                $propDest = $destinationReflection->getProperty($name);
                $propDest->setAccessible(true);
                $propDest->setValue($destination,$value);
            } else {
                $destination->$name = $value;
            }
        }
        return $destination;
    }
}