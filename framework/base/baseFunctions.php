<?php
/**
 * Created by PhpStorm.
 * User: prateek
 * Date: 5/09/15
 * Time: 7:30 PM
 */
namespace Lp\Framework\Base;


trait BaseFunctions
{
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
        $sourceReflection = new \ReflectionObject($sourceObject);
        $destinationReflection = new \ReflectionObject($destination);
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