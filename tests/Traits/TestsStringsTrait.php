<?php

namespace Tests\Traits;

trait TestsStringsTrait {

    protected function removeSpacesFromString(string $input): string {
        return str_replace([' ', "\n", "\t"], ['', '', ''], $input);
    }

    protected function stringIsContainedInAnother(string $expected, string $actual): bool {
        return str_contains($this->removeSpacesFromString($actual), $this->removeSpacesFromString($expected));
    }

    protected function stringsHaveSameContent(string $expected, string $actual): bool {
        return $this->removeSpacesFromString($expected) === $this->removeSpacesFromString($actual);
    }

}