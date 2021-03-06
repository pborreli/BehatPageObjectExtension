<?php

namespace spec\SensioLabs\Behat\PageObjectExtension\PageObject;

use PHPSpec2\ObjectBehavior;
use SensioLabs\Behat\PageObjectExtension\PageObject\Element as BaseElement;

class MyElement extends BaseElement
{
    public $selector = array('xpath' => '//div[@id="my-box"]');

    public function callGetPage($name)
    {
        return $this->getPage($name);
    }

    public function callGetName()
    {
        return $this->getName();
    }
}

class Element extends ObjectBehavior
{
    /**
     * @param \Behat\Mink\Session                                                $session
     * @param \SensioLabs\Behat\PageObjectExtension\Context\PageFactoryInterface $factory
     * @param \Behat\Mink\Selector\SelectorsHandler                              $selectorsHandler
     */
    function let($session, $factory, $selectorsHandler)
    {
        // until we have proper abstract class support in PHPSpec2
        $this->beAnInstanceOf('spec\SensioLabs\Behat\PageObjectExtension\PageObject\MyElement');
        $this->beConstructedWith($session, $factory);

        $session->getSelectorsHandler()->willReturn($selectorsHandler);
        $selectorsHandler->selectorToXpath('xpath', '//div[@id="my-box"]')->willReturn('//div[@id="my-box"]');
    }

    function it_should_be_a_node_element()
    {
        $this->shouldHaveType('Behat\Mink\Element\NodeElement');
    }

    function it_should_relate_to_a_subsection_of_a_page()
    {
        $this->getXpath()->shouldReturn('//div[@id="my-box"]');
    }

    function it_gives_clear_feedback_if_method_is_invalid()
    {
        $this->shouldThrow(new \BadMethodCallException('"search" method is not available on the MyElement'))->during('search');
    }

    function it_creates_a_page($factory, $page)
    {
        $page->beAnInstanceOf('SensioLabs\Behat\PageObjectExtension\PageObject\Element');

        $factory->createPage('Home')->willReturn($page);

        $this->callGetPage('Home')->shouldReturn($page);
    }

    function it_returns_the_page_name()
    {
        $this->callGetName()->shouldReturn('MyElement');
    }
}
