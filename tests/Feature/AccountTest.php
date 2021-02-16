<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AccountTest extends TestCase
{

    protected $table;

    /**
     * AccountTest constructor.
     * @param string|null $name
     * @param array $data
     * @param string $dataName
     */
    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->table = with(new User())->getTable();
    }


    /**
     * Test account view
     */
    public function testAccountView()
    {
        $this->actingAs($this->user)->get(route('account'))
            ->assertStatus(200)
            ->assertSee('Change E-Mail')
            ->assertSee('Change Password')
            ->assertSee('Delete Account');
    }

    /**
     * Test update email
     */
    public function testUpdateEmail()
    {

        $email = 'new@email.net';

        $data = [
            'email' => $email,
            'email_confirmation' => $email
        ];

        $this->actingAs($this->user)->post(route('email.update'), $data, ['Accept' => 'application/json'])
            ->assertStatus(200);

        $this->user->refresh();
        $this->assertEquals($email, $this->user->email);

    }

    /**
     * Test update password
     */
    public function testUpdatePassword() {

        $new_password = 'testtest';

        $data = [
            'password' => 'demodemo',
            'new_password' => $new_password,
            'new_password_confirmation' => $new_password
        ];

        $this->actingAs($this->user)->post(route('password.update'), $data, ['Accept' => 'application/json'])
            ->assertStatus(200);

        $this->user->refresh();
        $this->assertTrue(Hash::check($new_password, $this->user->password));

    }

    /**
     * Test destroy account
     */
    public function testDestroyAccount() {

        $otherUser = User::find(2);
        $data = ['password' => 'demodemo'];

        $this->actingAs($otherUser)->post(route('account.destroy'), $data, ['Accept' => 'application/json'])
            ->assertStatus(200);

        $this->assertDatabaseMissing($this->table, ['id' => $otherUser->id]);

    }

}
