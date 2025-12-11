<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\ShortUrl;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UrlShortenerAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate');
        $this->artisan('db:seed', ['--class' => 'SuperAdminSeeder']);
    }

    /** @test */
    public function admin_cannot_create_short_urls()
    {
        $company = Company::create([
            'name' => 'Test Company',
            'email' => 'test@company.com',
        ]);
        $admin = User::create([
            'name' => 'Test Admin',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
            'role' => 'Admin',
            'company_id' => $company->id,
        ]);

        $response = $this->actingAs($admin)->post('/urls', [
            'long_url' => 'https://example.com',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error', 'You do not have permission to create short URLs');
        $this->assertDatabaseMissing('short_urls', [
            'long_url' => 'https://example.com',
        ]);
    }

    /** @test */
    public function member_cannot_create_short_urls()
    {
        $company = Company::create([
            'name' => 'Test Company',
            'email' => 'test2@company.com',
        ]);
        $member = User::create([
            'name' => 'Test Member',
            'email' => 'member@test.com',
            'password' => bcrypt('password'),
            'role' => 'Member',
            'company_id' => $company->id,
        ]);

        $response = $this->actingAs($member)->post('/urls', [
            'long_url' => 'https://example.com',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error', 'You do not have permission to create short URLs');
        $this->assertDatabaseMissing('short_urls', [
            'long_url' => 'https://example.com',
        ]);
    }

    /** @test */
    public function superadmin_cannot_create_short_urls()
    {
        $superadmin = User::where('role', 'SuperAdmin')->first();

        $response = $this->actingAs($superadmin)->post('/urls', [
            'long_url' => 'https://example.com',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error', 'You do not have permission to create short URLs');
        $this->assertDatabaseMissing('short_urls', [
            'long_url' => 'https://example.com',
        ]);
    }

    /** @test */
    public function sales_can_create_short_urls()
    {
        $company = Company::create([
            'name' => 'Test Company',
            'email' => 'test3@company.com',
        ]);
        $sales = User::create([
            'name' => 'Test Sales',
            'email' => 'sales@test.com',
            'password' => bcrypt('password'),
            'role' => 'Sales',
            'company_id' => $company->id,
        ]);

        $response = $this->actingAs($sales)->post('/urls', [
            'long_url' => 'https://example.com',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Short URL created successfully!');
        $this->assertDatabaseHas('short_urls', [
            'long_url' => 'https://example.com',
            'user_id' => $sales->id,
            'company_id' => $company->id,
        ]);
    }

    /** @test */
    public function manager_can_create_short_urls()
    {
        $company = Company::create([
            'name' => 'Test Company',
            'email' => 'test4@company.com',
        ]);
        $manager = User::create([
            'name' => 'Test Manager',
            'email' => 'manager@test.com',
            'password' => bcrypt('password'),
            'role' => 'Manager',
            'company_id' => $company->id,
        ]);

        $response = $this->actingAs($manager)->post('/urls', [
            'long_url' => 'https://example.com',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Short URL created successfully!');
        $this->assertDatabaseHas('short_urls', [
            'long_url' => 'https://example.com',
            'user_id' => $manager->id,
            'company_id' => $company->id,
        ]);
    }

    /** @test */
    public function admin_can_only_see_short_urls_not_from_their_company()
    {
        $company1 = Company::create(['name' => 'Company 1', 'email' => 'company1@test.com']);
        $company2 = Company::create(['name' => 'Company 2', 'email' => 'company2@test.com']);
        
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
            'role' => 'Admin',
            'company_id' => $company1->id,
        ]);

        $sales1 = User::create([
            'name' => 'Sales 1',
            'email' => 'sales1@test.com',
            'password' => bcrypt('password'),
            'role' => 'Sales',
            'company_id' => $company1->id,
        ]);

        $sales2 = User::create([
            'name' => 'Sales 2',
            'email' => 'sales2@test.com',
            'password' => bcrypt('password'),
            'role' => 'Sales',
            'company_id' => $company2->id,
        ]);

        // Create URLs in both companies
        $url1 = ShortUrl::create([
            'short_code' => 'testcode1',
            'user_id' => $sales1->id,
            'company_id' => $company1->id,
            'long_url' => 'https://company1.com',
            'hits' => 0,
        ]);

        $url2 = ShortUrl::create([
            'short_code' => 'testcode2',
            'user_id' => $sales2->id,
            'company_id' => $company2->id,
            'long_url' => 'https://company2.com',
            'hits' => 0,
        ]);

        $response = $this->actingAs($admin)->get('/admin/urls');

        $response->assertStatus(200);
        $response->assertDontSee($url1->long_url);
        $response->assertSee($url2->long_url);
    }

    /** @test */
    public function member_can_only_see_short_urls_not_created_by_themselves()
    {
        $company = Company::create([
            'name' => 'Test Company',
            'email' => 'test5@company.com',
        ]);
        
        $member = User::create([
            'name' => 'Test Member',
            'email' => 'member2@test.com',
            'password' => bcrypt('password'),
            'role' => 'Member',
            'company_id' => $company->id,
        ]);

        $sales1 = User::create([
            'name' => 'Sales 1',
            'email' => 'sales3@test.com',
            'password' => bcrypt('password'),
            'role' => 'Sales',
            'company_id' => $company->id,
        ]);

        $sales2 = User::create([
            'name' => 'Sales 2',
            'email' => 'sales4@test.com',
            'password' => bcrypt('password'),
            'role' => 'Sales',
            'company_id' => $company->id,
        ]);

        // Create URLs by different users
        $url1 = ShortUrl::create([
            'short_code' => 'testcode3',
            'user_id' => $sales1->id,
            'company_id' => $company->id,
            'long_url' => 'https://sales1.com',
            'hits' => 0,
        ]);

        $url2 = ShortUrl::create([
            'short_code' => 'testcode4',
            'user_id' => $sales2->id,
            'company_id' => $company->id,
            'long_url' => 'https://sales2.com',
            'hits' => 0,
        ]);

        // Member should see both URLs since they didn't create them
        $response = $this->actingAs($member)->get('/member/urls');

        $response->assertStatus(200);
        $response->assertSee($url1->long_url);
        $response->assertSee($url2->long_url);
    }

    /** @test */
    public function short_urls_are_publicly_resolvable_and_redirect_to_original_url()
    {
        $company = Company::create([
            'name' => 'Test Company',
            'email' => 'test6@company.com',
        ]);
        $sales = User::create([
            'name' => 'Test Sales',
            'email' => 'sales5@test.com',
            'password' => bcrypt('password'),
            'role' => 'Sales',
            'company_id' => $company->id,
        ]);

        $shortUrl = ShortUrl::create([
            'short_code' => 'test1234',
            'user_id' => $sales->id,
            'company_id' => $company->id,
            'long_url' => 'https://example.com',
            'hits' => 0,
        ]);

        $response = $this->get('/s/test1234');

        $response->assertRedirect('https://example.com');
        $this->assertEquals(1, $shortUrl->fresh()->hits);
    }

    /** @test */
    public function short_url_hits_increment_on_redirect()
    {
        $company = Company::create([
            'name' => 'Test Company',
            'email' => 'test7@company.com',
        ]);
        $sales = User::create([
            'name' => 'Test Sales',
            'email' => 'sales6@test.com',
            'password' => bcrypt('password'),
            'role' => 'Sales',
            'company_id' => $company->id,
        ]);

        $shortUrl = ShortUrl::create([
            'short_code' => 'test1234',
            'user_id' => $sales->id,
            'company_id' => $company->id,
            'long_url' => 'https://example.com',
            'hits' => 0,
        ]);

        $this->get('/s/test1234');
        $this->assertEquals(1, $shortUrl->fresh()->hits);

        $this->get('/s/test1234');
        $this->assertEquals(2, $shortUrl->fresh()->hits);
    }
}

