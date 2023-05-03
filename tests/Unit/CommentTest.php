<?php

namespace Tests\Unit;

use App\Models\User;
use App\Models\Comment;
use PHPUnit\Framework\TestCase;

// Данный тест должен проверять, что у комментария есть специальное свойство для возврата имени автора и этого свойство действительно содержит имя пользователя, который написал данный комментарий. Также надо учитывать, что комментарий может оставить аноним, а значит для анонимных комментариев тоже должен выводиться какой-то дефолтный текст с именем автора.

class CommentTest extends TestCase
{
    public function test_example(): void
    {
        $this->assertTrue(true);
    }
}
