<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class LocalLoginTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config(['database.default' => 'sqlite', 'database.connections.sqlite.url' => null,
            'database.connections.sqlite.database' => ':memory:', 'cache.default' => 'array',
            'session.driver' => 'array', 'activitylog.enabled' => false]);
        DB::purge('sqlite');
        (require database_path('migrations/0001_01_01_000000_create_users_table.php'))->up();
        DB::statement('CREATE TABLE roles (id INTEGER PRIMARY KEY, name TEXT)');
        DB::statement('CREATE TABLE role_user (id INTEGER PRIMARY KEY, role_id INTEGER, user_id INTEGER)');
        DB::table('roles')->insert([['id' => 1, 'name' => 'Admin'], ['id' => 2, 'name' => 'Viewer']]);
    }

    private function user(): User
    {
        $id = DB::table('users')->insertGetId(['name' => 'Existing admin', 'email' => 'admin@example.test',
            'password' => Hash::make('local-password'), 'created_at' => now(), 'updated_at' => now()]);
        DB::table('role_user')->insert(['user_id' => $id, 'role_id' => 1]);

        return User::findOrFail($id);
    }

    public function test_login_pages_show_local_credentials_without_sso(): void
    {
        foreach (['/login', '/loginv3'] as $path) {
            $this->get($path)->assertOk()->assertSee('name="email"', false)->assertSee('name="password"', false)
                ->assertDontSee('Masuk dengan SSO')->assertDontSee('/sso/login', false);
        }
    }

    public function test_existing_account_logs_in_locally_and_keeps_profile_and_roles(): void
    {
        $user = $this->user();
        $password = $user->password;
        $this->post('/login', ['email' => $user->email, 'password' => 'local-password'])->assertRedirect('/appv2');
        $this->assertAuthenticatedAs($user);
        $this->assertTrue($user->fresh()->hasRole('Admin'));
        $this->assertSame($password, $user->fresh()->password);
        $this->assertDatabaseCount('users', 1);
    }

    public function test_invalid_password_is_rejected_and_does_not_create_account(): void
    {
        $user = $this->user();
        $this->post('/login', ['email' => $user->email, 'password' => 'wrong'])->assertSessionHasErrors('email');
        $this->assertGuest();
        $this->assertDatabaseCount('users', 1);
    }

    public function test_logout_requires_local_login_again(): void
    {
        $user = $this->user();
        $this->actingAs($user)->post('/logout')->assertRedirect('/login');
        $this->assertGuest();
        $this->get('/appv2')->assertRedirect('/login');
    }

    public function test_sso_entry_points_and_callbacks_no_longer_exist(): void
    {
        foreach (['/sso/login', '/sso/connect', '/sso/callback', '/sso/link', '/sso/confirm'] as $path) {
            $this->get($path)->assertNotFound();
        }
        $this->post('/sso/backchannel-logout', ['logout_token' => 'invalid'])->assertNotFound();
        $this->assertDatabaseCount('users', 0);
    }

    public function test_admin_can_create_local_accounts_and_set_local_password(): void
    {
        $admin = $this->user();
        $this->actingAs($admin)->postJson('/appv2/users', ['name' => 'Local colleague', 'email' => 'colleague@example.test',
            'password' => 'new-password', 'password_confirmation' => 'new-password', 'role_id' => 2])->assertOk();
        $user = User::where('email', 'colleague@example.test')->firstOrFail();
        $this->assertTrue(Hash::check('new-password', $user->password));
        $this->assertTrue($user->hasRole('Viewer'));
        $this->putJson('/appv2/users/'.$user->id, ['name' => $user->name, 'email' => $user->email,
            'password' => 'changed-password', 'password_confirmation' => 'changed-password', 'role_id' => 2])->assertOk();
        $this->assertTrue(Hash::check('changed-password', $user->fresh()->password));
    }
}
