<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testShowUser()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/api/user');

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            'data' => [
                'user' => [
                    'id',
                    'name',
                    'email',
                    'avatar',
                    'role',
                ],
            ],
        ]);
        $response->assertJson([
            'data' => [
                'user' => $user->toArray(),
            ],
        ]);
    }

    public function testUpdateUserData()
    {
        $user = User::factory()->create();
        $newName = 'Новое имя';
        $newEmail = 'newemail@mail.ru';

        $response = $this->actingAs($user)->patch('/api/user', [
            'name' => $newName,
            'email' => $newEmail,
        ]);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson([
            'data' => [
                'user' => [
                    'name' => $newName,
                    'email' => $newEmail,
                ],
            ],
        ]);
    }

    public function testUpdatePasswordChanges()
    {
        $user = User::factory()->create();
        $newPassword = 'newPassword123';

        $response = $this->actingAs($user)->patch('/api/user', [
            'name' => $user->name,
            'email' => $user->email,
            'password' => $newPassword,
        ]);

        $user->refresh();

        $response->assertStatus(Response::HTTP_OK);
        $this->assertTrue(Hash::check($newPassword, $user->password));
    }

    public function testUpdateAvatarChanges()
    {
        Storage::fake('local');

        $user = User::factory()->create();
        $avatar = UploadedFile::fake()->image('avatar.jpg');

        $response = $this->actingAs($user)->patch('/api/user', [
            'name' => $user->name,
            'email' => $user->email,
            'avatar' => $avatar,
        ]);

        $user->refresh();

        $response->assertStatus(Response::HTTP_OK);

        $this->assertEquals('avatars/' . $avatar->hashName(), $user->avatar);
        Storage::disk('local')->assertExists('avatars/' . $avatar->hashName());
    }

    public function testUpdateValidationError()
    {
        $user = User::factory()->create();
        $anotherUser = User::factory()->create();

        $data = [
            'email' => $anotherUser->email,
            'password' => 'short',
            'name' => '',
            'avatar' => UploadedFile::fake()->create('document.pdf', 20480),
        ];

        $response = $this->actingAs($user)->patchJson("/api/user", $data);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors([
            'email',
            'password',
            'name',
            'avatar',
        ]);
    }

}
