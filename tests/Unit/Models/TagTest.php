<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Contact\Tag;
use App\Models\Account\Account;
use App\Models\Contact\Contact;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TagTest extends TestCase
{
    use DatabaseTransactions;

    public function test_it_belongs_to_an_account()
    {
        $account = factory(Account::class)->create([]);
        $contact = factory(Contact::class)->create(['account_id' => $account->id]);
        $tag = factory(Tag::class)->create([
            'account_id' => $account->id,
        ]);

        $this->assertTrue($tag->account()->exists());
    }

    public function test_it_belongs_to_many_contacts()
    {
        $account = factory(Account::class)->create([]);
        $contact = factory(Contact::class)->create(['account_id' => $account->id]);
        $tag = factory(Tag::class)->create(['account_id' => $account->id]);
        $contact->tags()->sync([$tag->id => ['account_id' => $account->id]]);

        $contact = factory(Contact::class)->create(['account_id' => $account->id]);
        $tag = factory(Tag::class)->create(['account_id' => $account->id]);
        $contact->tags()->sync([$tag->id => ['account_id' => $account->id]]);

        $this->assertTrue($tag->contacts()->exists());
    }

    public function test_it_updates_the_slug()
    {
        $tag = factory(Tag::class)->create(['name' => 'this is great']);
        $tag->updateSlug();
        $this->assertEquals(
            'this-is-great',
            $tag->name_slug
        );
    }
}
