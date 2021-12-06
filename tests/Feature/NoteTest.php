<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\User;
use App\Models\Note;

class NoteTest extends TestCase
{
    public function test_index_not_authorised_user_is_redirected() {

    }

    public function test_index_authorised_user_can_see_notes() {
        
    }

    public function test_show_not_authorised_user_is_redirected() {

    }

    public function test_show_authorised_wrong_user_cannot_see_note() {

    }

    public function test_show_authorised_user_can_see_note() {

    }

    public function test_edit_not_authorised_user_is_redirected() {

    }

    public function test_edit_authorised_wrong_user_cannot_see_note() {

    }

    public function test_edit_authorised_user_can_see_note() {

    }

    public function test_update_not_authorised_user_receives_401() {

    }

    public function test_update_authorised_wrong_user_receives_403() {

    }

    public function test_update_authorised_user_can_update_note() {

    }

    public function test_update_incomplete_data_fails_validation() {

    }

    public function test_destroy_not_authorised_user_receives_401() {

    }

    public function test_destroy_authorised_wrong_user_receives_403() {

    }

    public function test_destroy_authorised_user_can_delete_note() {

    }

    public function test_create_not_authorised_user_is_redirected() {

    }

    public function test_create_authorised_user_can_render_create_form() {

    }

    public function test_store_not_authorised_user_receives_401() {

    }

    public function test_store_authorised_user_can_create_note() {

    }

    public function test_store_incomplete_data_fails_validation() {

    }
}
