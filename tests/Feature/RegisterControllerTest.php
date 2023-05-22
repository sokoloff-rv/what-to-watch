<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class RegisterControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testSuccessfulRegistration()
    {
        Storage::fake('local');

        $data = [
            'name' => 'Имя пользователя',
            'email' => 'email@mail.ru',
            'password' => 'password123',
            'avatar' => UploadedFile::fake()->image('avatar.jpg'),
        ];

        $user = User::where('email', $data['email'])->first();
        $response = $this->postJson('/api/register', $data);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure(['data' => ['token']]);

        $this->assertNotNull($user);
        $this->assertTrue(Hash::check($data['password'], $user->password));
        $this->assertEquals('avatars/' . $data['avatar']->hashName(), $user->avatar);
        Storage::disk('local')->assertExists('avatars/' . $data['avatar']->hashName());
    }

    public function testRegistrationValidationError()
    {
        $data = [
            'name' => '',
            'email' => 'not-valid-email',
            'password' => 'short',
            'avatar' => UploadedFile::fake()->create('document.pdf', 20480),
        ];

        $response = $this->postJson("/api/register", $data);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors(['name', 'email', 'password', 'avatar']);
    }
}
