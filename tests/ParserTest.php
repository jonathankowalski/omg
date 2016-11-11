<?php

use JonathanKowalski\Omg\Parser;
use PHPUnit\Framework\TestCase;

class ParserTest extends TestCase
{
    /**
     * @var Parser
     */
    protected static $parser;

    public static function setUpBeforeClass()
    {
        self::$parser = new Parser();
    }

    public function testFetch()
    {
        $o = self::$parser->parse(
            'http://www.rottentomatoes.com/m/10011268-oceans/'
        );
        $this->assertEquals(
            array(
                'og:title'     => 'Oceans (Disneynature\'s Oceans)',
                'og:type'      => 'video.movie',
                'og:image'     => 'https://resizing.flixster.com/RsK6qv6-1iZ7xC3aisN5NdCEevg=/300x300/v1.bTsxMTMxNTE5OTtqOzE3MjMwOzEyMDA7MTk5ODsyNjY0',
                'og:url'       => 'https://www.rottentomatoes.com/m/10011268-oceans/',
                'og:description' => 'Winged Migration co-directors Jacques Cluzaud and Jacques Perrin re-team for this documentary produced for Walt Disney Studios\' Disneynature banner and exploring the many mysteries of our planet\'s oceans. Almost three-quarters of the earth\'s surface is covered by oceans, yet strangely we seem to know more about deep space than the world of the sea. There\'s no question that the ocean has played a crucial role in the history and sustenance of humankind, but what secrets does the underwater world hold? Follow filmmakers Cluzaud and Perrin beneath the ocean waves as they seek out the answer to this and explore the many dangers and mysteries of the deep.',
            ),
            $o
        );
    }

    /**
     * @expectedException \JonathanKowalski\Omg\CantFetchException
     */
    public function testExceptionWhenEmpty()
    {
        self::$parser->parse("");
    }

    /**
     * @expectedException \JonathanKowalski\Omg\CantFetchException
     */
    public function testExceptionWhenNotExists(){
        self::$parser->parse("http://idontexists.exists");
    }

}