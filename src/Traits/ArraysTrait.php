<?php

namespace Udacity\Traits;

/**
 * this trait holds common logic for manipulating arrays
 */
trait ArraysTrait {

    /**
     * checks if an input array is a list with ascending ordered keys starting with 0
     * 
     * @param array $inputArr
     * @return boolean
     */
    public function arrayIsZeroIndexedOrderedList(array $inputArr): bool {
        $count = 0;
        foreach (array_keys($inputArr) as $key) {
            if ($key !== $count) {
                return false;
            }
            $count++;
        }
        return true;
    }

    /**
     * checks if all elements of an array are key value arrays
     * 
     * @param array $inputArr
     * @return boolean
     */
    public function allArrayElementsAreKeyValueArrs(array $inputArr): bool {
        foreach (array_values($inputArr) as $val) {
            if (!is_array($val) || $this->arrayIsZeroIndexedOrderedList($val)) {
                return false;
            }
        }
        return true;
    }

}