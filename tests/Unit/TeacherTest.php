<?php

namespace Tests\Unit;

use \App\Models\Teacher;
// use PHPUnit\Framework\TestCase;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

class TeacherTest extends TestCase
{
    use RefreshDatabase, WithFaker;
    public function setUp():void
    {
        parent::setUp();
        // seed the database
        $this->artisan('db:seed');
    }
    public function test_makeAdmin_and_revokeAdmin()
    {
        $t = Teacher::factory()->create();
        $u = $t->user;
        $r = $u->roles;

        //When created, a teacher should have only 'teacher' role.
        $this->assertEquals(1,count($r));
        $this->assertEquals('teacher',$r->first()->description);
        $this->assertFalse($u->hasRole('admin'));

        $t->makeAdmin();
        //After makeAdmin,the teacher should have 2 roles: 'teacher' and 'admin'
        $this->assertEquals(2,count($u->roles));
        $this->assertTrue($u->hasRole('admin'));

        $t->revokeAdminPrivileges();
        //After revoking privileges,the teacher should only have 'teacher' role:
        $this->assertEquals(1,count($u->roles));
        $this->assertEquals('teacher',$r->first()->description);
        $this->assertFalse($u->hasRole('admin'));


    }
}
