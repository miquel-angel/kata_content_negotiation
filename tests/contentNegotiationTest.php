<?php
require_once '../src/contentNegotiation.php';

/**
 * Class to test contentNegotiation class
 */
class contentNegotiationTest extends PHPUnit_Framework_TestCase
{
    /**
     * contentNegotiation
     *
     * @var
     */
    private $obj;

    /**
     * Initialize the class for the tests.
     */
    public function setUp()
    {
        $this->obj = new contentNegotiation();
    }


    /**
     * Tests that if not exists available should return null.
     */
    public function testWhenWePassHeaderWithEmptyAvailableValues()
    {
        $this->assertNull( $this->obj->getAvailable( 'ca', array() ), 'If not exist any available, should return null.' );
    }

    /**
     * DataProvider to test the invalid arguments for the getAvailable method.
     *
     * @return array
     */
    public function whenPassInvalidArgumentsProvider()
    {
        return array(
            'Test when the user input are null' => array(
                'user_input'        => null,
                'available_values'  => array( 'es' )
            ),
            'Test when the user input is string empty'  => array(
                'user_input'        => '',
                'available_values'  => array( 'ca' )
            ),
            'Test when we don\'t have any available value'  => array(
                'user_input'        => 'en',
                'available_values'  => array()
            )
        );
    }

    /**
     * Tests that if some of the params are null or empty, should return null.
     *
     * @param mixed $user_input The user inputs for the test.
     * @param mixed $available_values The available values that we have.
     * @dataProvider whenPassInvalidArgumentsProvider
     */
    public function testWhenWePassInvalidArgumentsReturnNull( $user_input, $available_values )
    {
        $this->assertNull(
            $this->obj->getAvailable( $user_input, $available_values ),
            'If we pass invalids values, should return null.'
        );
    }

    /**
     * Tests that if we pass only one value, and that exist in the available values, should return that.
     */
    public function testWhenWeOnlyRequireOneAndExistInTheAvailableValues()
    {
        $this->assertEquals(
            'ca',
            $this->obj->getAvailable( 'ca', array( 'en', 'es', 'ca' ) ),
            'If we only pass one value, and exist, should return that.'
        );
    }

    /**
     * If the we only require one and not exists, should return null.
     */
    public function testWhenWeOnlyRequireOneAndNotExist()
    {
        $this->assertNull( $this->obj->getAvailable( 'ca', array( 'es' ) ), 'If not exist in the available return null' );
    }

    /**
     * Tests that when we pass more than one, but all of them with the default priority, and only one is in the available.
     */
    public function testWhenWeRequireMoreThanOneAndOnlyExistOneAndAllOfThemWithDefaultPriority()
    {
        $this->assertEquals(
            'en',
            $this->obj->getAvailable( 'en,es', array( 'en' ) ),
            'When only exist one, should return that without matter the priority.'
        );
    }

    /**
     * Tests that when we pass more than one, but all of them with the default priority, and no one is in the available.
     */
    public function testWhenWeRequireMoreThanOneAndOnlyNotExistOneAndAllOfThemWithDefaultPriority()
    {
        $this->assertNull(
            $this->obj->getAvailable( 'en,es', array( 'zh' ) ),
            'When no one is available, should return null.'
        );
    }

    /**
     * Tests that when exist more than one take the concrete that has the highest priority, in that test not take account the default.
     */
    public function testWhenRequireMoreThanOneAndExistMoreThanOneButNotWithTheDefaultPriority()
    {
        $this->assertEquals(
            'ca',
            $this->obj->getAvailable( 'es,ca;0.5,en;0.1', array( 'en', 'ca' ) ),
            'If we have more than one we return the concrete with the highest priority'
        );
    }

    /**
     * Tests that when exist more than one take the concrete that has the highest priority, in that test take account the default.
     */
    public function testWhenRequireMoreThanOneAndExistMoreThanOneAndReturnThatHaveTheDefaultPriority()
    {
        $this->assertEquals(
            'ca',
            $this->obj->getAvailable( 'es;0.5,ca,en;0.1', array( 'en', 'ca', 'es' ) ),
            'If we have more than one we return the concrete with the highest priority'
        );
    }
}