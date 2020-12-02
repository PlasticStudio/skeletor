<?php

use SilverStripe\Assets\Filesystem;
use SilverStripe\Control\HTTPRequest;
use SilverStripe\BehatExtension\Context\MainContextAwareTrait;
use SilverStripe\BehatExtension\Context\SilverStripeContext;
use SilverStripe\Core\Injector\Injector;
use SilverStripe\MinkFacebookWebDriver\FacebookWebDriver;
use DNADesign\Populate\PopulateTask;

/**
 * Default context for this module
 */
class FeatureContext extends SilverStripeContext
{
    use MainContextAwareTrait;

    public function __construct(array $parameters = null)
    {
        parent::__construct($parameters);
        //$populate = Injector::inst()->get(PopulateTask::class);
        //$populate->run(new HTTPRequest('GET', 'dev/tasks/DNADesign-Populate-PopulateTask', ['flush'=>1]));
    }

    /**
     * @Given I populate default records
     */
    public function iPopulateDefaultRecords()
    {
        $populate = Injector::inst()->get(PopulateTask::class);
        $populate->run(new HTTPRequest('GET', 'dev/tasks/DNADesign-Populate-PopulateTask', ['flush'=>1]));
    }

    /**
     * @Given I click the element :selector
     *
     * @param $selector
     */
    public function iClickTheElement($selector)
    {
        $page = $this->getSession()->getPage();
        $element = $page->find('css', $selector);
        $element->click();
    }

    /**
     * @When I click the element :selector with javascript
     *
     * @param $selector
     */
    public function iClickTheElementWithJavascript($selector)
    {
        $javascript = 'document.querySelector("'.$selector.'").dispatchEvent(new MouseEvent("click"));';
        $this->getSession()->executeScript($javascript);
    }

    /**
     * @Then I fill in date with :date
     */
    // public function iFillInDateWith($date)
    // {
    //     $javascript = "document.getElementById('Form_Form_ProposedLiveDate').value='".$date."'";
    //     $this->getSession()->executeScript($javascript);
    // }

    /**
     * @Then I take a screenshot with name :arg1
     */
    public function iTakeAScreenshotWithName($arg1)
    {
        // Validate driver
        $driver = $this->getSession()->getDriver();
        if (!($driver instanceof FacebookWebDriver)) {
            file_put_contents('php://stdout', 'ScreenShots are only supported for FacebookWebDriver: skipping');
            return;
        }

        // Check paths are configured
        $path = $this->getMainContext()->getScreenshotPath();
        if (!$path) {
            file_put_contents('php://stdout', 'ScreenShots path not configured: skipping');
            return;
        }

        Filesystem::makeFolder($path);
        $path = realpath($path);

        if (!file_exists($path)) {
            file_put_contents('php://stderr', sprintf('"%s" is not valid directory and failed to create it' . PHP_EOL, $path));
            return;
        }

        if (file_exists($path) && !is_dir($path)) {
            file_put_contents('php://stderr', sprintf('"%s" is not valid directory' . PHP_EOL, $path));
            return;
        }
        if (file_exists($path) && !is_writable($path)) {
            file_put_contents('php://stderr', sprintf('"%s" directory is not writable' . PHP_EOL, $path));
            return;
        }

        $path = sprintf('%s/%s.png', $path, $arg1);
        $screenshot = $driver->getScreenshot();
        file_put_contents($path, $screenshot);
        file_put_contents('php://stderr', sprintf('Saving screenshot into %s' . PHP_EOL, $path));
    }

    /**
     * @Given I resize window :arg1 by :arg2
     */
    public function iResizeWindowBy($arg1, $arg2)
    {
        $this->getSession()->resizeWindow((int) $arg1, (int) $arg2);
    }
}