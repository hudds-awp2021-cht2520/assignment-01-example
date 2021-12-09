<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\User;
use App\Models\Note;

class NoteTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_not_authorised_user_is_redirected() {
        $response = $this
            ->followingRedirects()
            ->get('/notes');

        $response->assertViewIs('auth.login');
    }

    public function test_index_authorised_user_can_see_notes() {
        $user = User::factory()->create();
        $notes = Note::factory()->count(5)->create([
            'user_id' => $user->id
        ]);

        $response = $this
            ->followingRedirects()
            ->actingAs($user)
            ->get('/notes');
        
        $response->assertOk();
        
        $response->assertSee(array_column($notes->toArray(), 'content'));
    }

    public function test_show_not_authorised_user_is_redirected() {
        $user = User::factory()->create();
        $note = Note::factory()->create([
            'user_id' => $user->id
        ]);

        $response = $this
            ->followingRedirects()
            ->get("/notes/{$note->id}");

        $response->assertViewIs('auth.login');
    }

    public function test_show_authorised_wrong_user_cannot_see_note() {
        $user = User::factory()->create();
        $note = Note::factory()->create([
            'user_id' => $user->id
        ]);
        $wrongUser = User::factory()->create();

        $response = $this
            ->followingRedirects()
            ->actingAs($wrongUser)
            ->get("/notes/{$note->id}");

        $response->assertForbidden();
    }

    public function test_show_authorised_user_can_see_note() {
        $user = User::factory()->create();
        $note = Note::factory()->create([
            'user_id' => $user->id
        ]);

        $response = $this
            ->followingRedirects()
            ->actingAs($user)
            ->get("/notes/{$note->id}");

        $response->assertOk();
        $response->assertSee($note->content);
    }

    public function test_edit_not_authorised_user_is_redirected() {
        $user = User::factory()->create();
        $note = Note::factory()->create([
            'user_id' => $user->id
        ]);

        $response = $this
            ->followingRedirects()
            ->get("/notes/{$note->id}/edit");

        $response->assertViewIs('auth.login');
    }

    public function test_edit_authorised_wrong_user_cannot_see_note() {
        $user = User::factory()->create();
        $note = Note::factory()->create([
            'user_id' => $user->id
        ]);
        $wrongUser = User::factory()->create();

        $response = $this
            ->followingRedirects()
            ->actingAs($wrongUser)
            ->get("/notes/{$note->id}/edit");

        $response->assertForbidden();
    }

    public function test_edit_authorised_user_can_see_note() {
        $user = User::factory()->create();
        $note = Note::factory()->create([
            'user_id' => $user->id
        ]);

        $response = $this
            ->followingRedirects()
            ->actingAs($user)
            ->get("/notes/{$note->id}/edit");

        $response->assertOk();
        $response->assertSee($note->content);
    }

    public function test_update_not_authorised_user_is_redirected() {
        $newContent = 'Some test content';
        $user = User::factory()->create();
        $note = Note::factory()->create([
            'user_id' => $user->id
        ]);

        $response = $this
            ->followingRedirects()
            ->put("/notes/{$note->id}", [
                'content' => $newContent
            ]);

        $latestNote = $note->fresh();

        $response->assertViewIs('auth.login');
        $this->assertNotEquals($latestNote->content, $newContent);
    }

    public function test_update_authorised_wrong_user_receives_403() {
        $newContent = 'Some test content';
        $user = User::factory()->create();
        $note = Note::factory()->create([
            'user_id' => $user->id
        ]);
        $wrongUser = User::factory()->create();

        $response = $this
            ->followingRedirects()
            ->actingAs($wrongUser)
            ->put("/notes/{$note->id}", [
                'content' => $newContent
            ]);

        $latestNote = $note->fresh();

        $response->assertForbidden();
        $this->assertNotEquals($latestNote->content, $newContent);
    }

    public function test_update_authorised_user_can_update_note() {
        $newContent = 'Some test content';
        $user = User::factory()->create();
        $note = Note::factory()->create([
            'user_id' => $user->id
        ]);

        $response = $this
            ->followingRedirects()
            ->actingAs($user)
            ->put("/notes/{$note->id}", [
                'content' => $newContent
            ]);

        $latestNote = $note->fresh();

        $response->assertOk();
        $response->assertViewIs('notes.index');
        $this->assertEquals($latestNote->content, $newContent);
    }

    public function test_update_incomplete_data_fails_validation() {
        $newContent = '';
        $user = User::factory()->create();
        $note = Note::factory()->create([
            'user_id' => $user->id
        ]);

        $response = $this
            ->actingAs($user)
            ->put("/notes/{$note->id}", [
                'content' => $newContent
            ]);

        $latestNote = $note->fresh();

        $response->assertSessionHasErrors([
            'content' => 'The content field is required.'
        ]);
        $this->assertNotEquals($latestNote->content, $newContent);
    }

    public function test_destroy_not_authorised_user_is_redirected() {
        $user = User::factory()->create();
        $note = Note::factory()->create([
            'user_id' => $user->id
        ]);

        $response = $this
            ->followingRedirects()
            ->delete("/notes/{$note->id}");

        $response->assertViewIs('auth.login');
        $this->assertDatabaseHas('notes', [
            'id' => $note->id,
            'content' => $note->content
        ]);
    }

    public function test_destroy_authorised_wrong_user_receives_403() {
        $user = User::factory()->create();
        $note = Note::factory()->create([
            'user_id' => $user->id
        ]);
        $wrongUser = User::factory()->create();

        $response = $this
            ->followingRedirects()
            ->actingAs($wrongUser)
            ->delete("/notes/{$note->id}");

        $response->assertForbidden();
        $this->assertDatabaseHas('notes', [
            'id' => $note->id,
            'content' => $note->content
        ]);
    }

    public function test_destroy_authorised_user_can_delete_note() {
        $user = User::factory()->create();
        $note = Note::factory()->create([
            'user_id' => $user->id
        ]);

        $response = $this
            ->followingRedirects()
            ->actingAs($user)
            ->delete("/notes/{$note->id}");

        $response->assertOk();
        $response->assertSee('The note was deleted.');
        $this->assertDatabaseMissing('notes', [
            'id' => $note->id,
            'content' => $note->content
        ]);
    }

    public function test_create_not_authorised_user_is_redirected() {
        $response = $this
            ->followingRedirects()
            ->get('/notes/create');

        $response->assertViewIs('auth.login');
    }

    public function test_create_authorised_user_can_render_create_form() {
        $user = User::factory()->create();

        $response = $this
            ->followingRedirects()
            ->actingAs($user)
            ->get('/notes/create');

        $response->assertOk();
        
        $response->assertSee('Create Note');
    }
/*
    public function test_store_not_authorised_user_is_redirected() {
        $newContent = 'Some test content';

        $response = $this
            ->followingRedirects()
            ->post('/notes', [
                'content' => $newContent
            ]);

        $response->assertViewIs('auth.login');
        $this->assertDatabaseMissing('notes', [
            'content' => $newContent
        ]);
    }

    public function test_store_authorised_user_can_create_note() {
        $newContent = 'Some test content';
        $user = User::factory()->create();

        $response = $this
            ->followingRedirects()
            ->actingAs($user)
            ->post('/notes', [
                'content' => $newContent
            ]);

        $response->assertOk();
        $response->assertSee('A note was created.');
        $this->assertDatabaseHas('notes', [
            'user_id' => $user->id,
            'content' => $newContent
        ]);
    }
*/
    public function test_store_incomplete_data_fails_validation() {
        $newContent = '';
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->post('/notes', [
                'content' => $newContent
            ]);

        $response->assertSessionHasErrors([
            'content' => 'The content field is required.'
        ]);

        $this->assertDatabaseMissing('notes', [
            'user_id' => $user->id,
            'content' => $newContent
        ]);
    }
}
