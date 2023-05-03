<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

// У каждого фильма есть рейтинг, который расчитывается как среднее арифметическое. Убедитесь, что свойство rating действительно возвращает правильный рейтинг, который основыывается на оценках этого фильма, оставленных пользователями.

class FilmTest extends TestCase
{
    public function test_example(): void
    {
        $this->assertTrue(true);
    }
}
