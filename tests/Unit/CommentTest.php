<?php

namespace Tests\Unit;

use App\Models\User;
use App\Models\Comment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommentTest extends TestCase
{
    use RefreshDatabase;

    public function testRegisterAuthorName()
    {
        $user = User::factory()->create(['name' => 'Иван Иванов']);
        $comment = Comment::factory()->for($user, 'user')->create(['is_external' => false]);

        $authorName = $comment->getAuthorName();

        $this->assertEquals('Иван Иванов', $authorName);
    }

    public function testAnonymousAuthorName()
    {
        $comment = Comment::factory()->create(['is_external' => true]);

        $authorName = $comment->getAuthorName();

        $this->assertEquals(Comment::ANONYMOUS_NAME, $authorName);
    }
}
