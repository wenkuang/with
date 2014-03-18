<?php

namespace WebDriver\Behat;

use Behat\Behat\Context\BehatContext;
use WebDriver\By;
use WebDriver\Element;
use WebDriver\Exception\ExceptionInterface;
use WebDriver\Exception\NoSuchElementException;
use WebDriver\Util\Xpath;

abstract class AbstractWebDriverContext extends BehatContext
{
    const CLICKABLE_TEXT_XPATH = '//a[contains(normalize-space(.),{text})]|//input[@type="submit" and contains(normalize-space(@value), {text})]|//button[contains(normalize-space(.),{text})]|//button[contains(normalize-space(@value), {text})]|//button[contains(normalize-space(.), {text})]';
    const LABEL_TO_INPUT_XPATH = '//*[(self::select or self::input or self::textarea) and @id=//label[contains(normalize-space(.), {text})]/@for]|//*[(self::select or self::input or self::textarea) and contains(normalize-space(@placeholder), {text})]';

    protected $browser;

    protected $browserReference;
    protected $baseUrl;
    protected $timeout;


    public function setBrowserInformations($browserReference, $baseUrl, $timeout)
    {
        if (!is_callable($browserReference)) {
            throw new \InvalidArgumentException(sprintf('Expected a callable, got a "%s".', is_object($browserReference) ? get_class($browserReference) : gettype($browserReference)));
        }

        $this->browser = null;
        $this->browserReference = $browserReference;
        $this->baseUrl = rtrim($baseUrl, '/');
        $this->timeout = $timeout;
    }

    /**
     * Try repeating a statement until it stops throwing exception.
     *
     * It's used for operations having a fail-tolerated risk.
     *
     * @return mixed return value of closure
     */
    public function tryRepeating(\Closure $closure, $time = null)
    {
        if (null === $time) {
            $time = $this->timeout;
        }

        $last = null;
        $count = 0;
        do {
            $count++;
            try {
                return $closure($this->getBrowser());
            } catch (\Exception $e) {
                $last = $e;
            }

            $wait = min(1000, $time);
            $time -= $wait;
            usleep($wait*1000);
        } while ($time > 0);

        throw new \RuntimeException(sprintf('Repeatedly failed, %s time: %s', $count, $last->getMessage()), 0, $last);

    }

    /**
     * @return WebDriver\Element
     */
    public function getElement(By $by, Element $element = null)
    {
        try {
            $obj = null === $element ? $this->getBrowser() : $element;

            return $obj->element($by);
        } catch (NoSuchElementException $e) {
            throw new \RuntimeException(sprintf('Element "%s" not found in page (visible text: "%s").', $by->toString(), $this->getBrowser()->getText()));
        } catch (ExceptionInterface $e) {
            throw new \RuntimeException(sprintf('Error while searching for element "%s" : %s (visible text: "%s").', $by->toString(), $e->getMessage(), $this->getBrowser()->getText()));
        }
    }

    /**
     * Proxy to browser, catch errors to display body on error.
     *
     * @return array
     */
    public function getElements(By $by, Element $element = null)
    {
        try {
            $obj = null === $element ? $this->getBrowser() : $element;

            return $obj->elements($by);
        } catch (ExceptionInterface $e) {
            throw new \RuntimeException(sprintf('Error while searching for element "%s" : %s (visible text: "%s").', $by->toString(), $e->getMessage(), $this->getBrowser()->getText()));
        }
    }

    /**
     * @return Browser
     */
    public function getBrowser()
    {
        if (null === $this->browser) {
            if (null === $this->browserReference) {
                throw new \RuntimeException('Browser reference is missing from context.');
            }

            $this->browser = call_user_func($this->browserReference);
        }

        return $this->browser;
    }

    public function getUrl($url = '/')
    {
        if (!preg_match('#^https?://#', $url)) {
            $url = rtrim($this->baseUrl, '/').'/'.ltrim($url, '/');
        }

        return $url;
    }

    /**
     * Converts a text selector to a By object.
     *
     * Tries to find magic expressions (css=#foo, xpath=//div[@id="foo"], id=foo, name=q, class=active).
     *
     * @return By|string returns a By instance when magic matched, the string otherwise
     */
    protected function parseSelector($text)
    {
        if (0 === strpos($text, 'id=')) {
            return By::id(substr($text, 3));
        }

        if (0 === strpos($text, 'xpath=')) {
            return By::xpath(substr($text, 6));
        }

        if (0 === strpos($text, 'css=')) {
            return By::css(substr($text, 4));
        }

        if (0 === strpos($text, 'name=')) {
            return By::name(substr($text, 5));
        }

        if (0 === strpos($text, 'class=')) {
            return By::className(substr($text, 6));
        }

        return $text;
    }

    protected function unescape($value)
    {
        return str_replace('""', '"', $value);
    }

    protected function escape($value)
    {
        return str_replace('"', '""', $value);
    }
}
