<?php

namespace Tenancy\Tests\Misc\Wingman\Feature\CLI\Traits;

use Tenancy\Misc\Wingman\CLI\Interactions\Quit;
use Tenancy\Misc\Wingman\CLI\Traits\UsesInteractions;
use Tenancy\Testing\TestCase;

class UsesInteractionsTest extends TestCase
{
    use UsesInteractions;

    /** @test */
    public function it_can_register_interactions()
    {
        $this->assertEmpty($this->interactions);

        $this->registerInteraction(new Quit());

        $this->assertNotEmpty($this->interactions);
    }

    /** @test */
    public function it_can_set_interactions()
    {
        $this->setInteractions([
            'Example'
        ]);

        $this->assertEquals(['Example'], $this->interactions);
    }

    /** @test */
    public function it_can_determine_the_interaction_function()
    {
        $this->registerInteraction(new Quit());

        $this->assertEquals(
            'interactQuit',
            $this->getInteractionFunction('Quit')
        );
    }

    /** @test */
    public function it_can_get_the_interaction()
    {
        $this->interaction = 'ExampleInteraction';

        $this->assertEquals(
            "ExampleInteraction",
            $this->getInteraction()
        );
    }

    /** @test */
    public function it_can_set_the_interaction()
    {
        $this->setInteraction('example');

        $this->assertEquals(
            'example',
            $this->getInteraction()
        );
    }

    /** @test */
    public function it_can_trigger_an_interaction()
    {
        $this->registerInteraction(new Quit());

        $this->triggerInteraction('Quit');
    }

    /**
     * Triggers the quit interaction, simply confirm that it is ran
     *
     * @param string $interaction
     *
     * @return void
     */
    protected function interactQuit(string $interaction)
    {
        $this->assertTrue(true);
    }
}
