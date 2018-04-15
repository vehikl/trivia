<?php

use PHPUnit\Framework\TestCase;

class GameTest extends TestCase
{
    /**
     * @dataProvider seedProvider
     */
    public function testGameProducesExpectedOutput($seed)
    {
        $file = tempnam('/tmp', 'game-');
        playGame($seed, $file);

        $this->assertMatchesSeededOutput($seed, file_get_contents($file));

        unlink($file);
    }

    protected function assertMatchesSeededOutput($seed, $actualOutput)
    {
        $this->assertStringEqualsFile(__DIR__ . '/fixtures/output-seeded-with-' . $seed . '.txt', $actualOutput);
    }

    public function seedProvider()
    {
        return array_map([$this, 'seedToArray'], range(1, 1000));
    }

    private function seedToArray($seed)
    {
        return [$seed];
    }
}
