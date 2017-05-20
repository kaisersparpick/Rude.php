<?php
declare(strict_types = 1);

/**
 * The logic for this processor was taken from
 * https://www.html5rocks.com/en/tutorials/cors/#toc-cors-server-flowchart
 */

use Kaiser\Rude\Rude;
use Kaiser\Rude\Rule;

class CorsFlowProcessor {
    private $request;
    private $rude;
    private $result;

    /**
     * CorsFlowProcessor constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->rude = new Rude(false);
    }

    /**
     * Does the request have an 'Origin' header?
     * @return ?bool
     */
    public function hasOriginHeader(): ?bool
    {
        return getRandomValue();
    }


    /**
     * Is the HTTP method an OPTIONS request?
     * @return ?bool
     */
    public function isOptionsRequest(): ?bool
    {
        return getRandomValue();
    }

    /**
     * Is there an 'Access-Control-Request-Method' header?
     * @return ?bool
     */
    public function hasAcrmHeader(): ?bool
    {
        return getRandomValue();
    }

    /**
     * Is the 'Access-Control-Request-Method' header valid?
     * @return ?bool
     */
    public function isAcrmHeaderValid(): ?bool
    {
        return getRandomValue();
    }

    /**
     * Is there an 'Access-Control-Request-Header' header?
     * @return ?bool
     */
    public function hasAcrhHeader(): ?bool
    {
        return getRandomValue();
    }

    /**
     * Is the 'Access-Control-Request-Header' header valid?
     * @return ?bool
     */
    public function isAcrhHeaderValid(): ?bool
    {
        return getRandomValue();
    }

    /**
     * Set Access-Control headers
     * @return bool
     */
    public function setAccessControlHeaders(): bool
    {
        // Set 'Access-Control-Allow-Methods' header
        // Set 'Access-Control-Allow-Headers' response header
        // (Optional) Set 'Access-Control-Max-Age' response header
        return true;
    }

    /**
     * Set 'Access-Control-Expose-Headers' response header
     * @return bool
     */
    public function setAccessControlExposeHeaders(): bool
    {
        return true;
    }

    /**
     * Set 'Access-Control-Allow-Origin' response header
     * @return bool
     */
    public function setAccessControlAllowOrigin(): bool
    {
        return true;
    }

    /**
     * Set 'Access-Control-Allow-Credentials' response header
     * @return bool
     */
    public function setAccessControlAllowCredentials(): bool
    {
        return true;
    }

    /**
     * Is this a preflight request?
     * @return bool
     */
    public function isPreflightRequest(): bool
    {
        return $this->request->isPreflight;
    }

    /**
     * It's a preflight request
     * @return bool
     */
    public function preflightRequest(): bool
    {
        $this->request->isPreflight = true;
        return true;
    }

    /**
     * It's an actual request
     * @return bool
     */
    public function actualRequest(): bool
    {
        $this->request->isPreflight = false;
        return true;
    }

    /**
     * Are cookies allowed?
     * @return ?bool
     */
    public function areCookiesAllowed(): ?bool
    {
        return getRandomValue();
    }

    /**
     * Return HTTP 200 reponse with no body
     * @return ?bool
     */
    public function http200(): ?bool
    {
        $this->result = 'Returning HTTP 200 reponse with no body';
        return null;
    }

    /**
     * Continue processing the response
     * @return ?bool
     */
    public function continueProcessing(): ?bool
    {
        $this->result = 'Continue processing';
        return null;
    }

    /**
     * @return ?bool
     */
    public function invalidCorsRequest(): ?bool
    {
        $this->result = 'Invalid CORS request';
        return null;
    }

    /**
     * @return ?bool
     */
    public function invalidPreflightRequest(): ?bool
    {
        $this->result = 'Invalid Preflight request';
        return null;
    }

    /**
     * Add Rude rules.
     * These can be generated dynamically or loaded from a datasource.
     */
    public function addRules(): void
    {
        $this->rude->addRule(new Rule(
            [$this, 'hasOriginHeader'], [$this, 'isOptionsRequest'], [$this, 'invalidCorsRequest']));
        $this->rude->addRule(new Rule(
            [$this, 'isOptionsRequest'], [$this, 'hasAcrmHeader'], [$this, 'actualRequest']));
        $this->rude->addRule(new Rule(
            [$this, 'hasAcrmHeader'], [$this, 'preflightRequest'], [$this, 'actualRequest']));
        $this->rude->addRule(new Rule(
            [$this, 'preflightRequest'], [$this, 'isAcrmHeaderValid'], null));
        $this->rude->addRule(new Rule(
            [$this, 'isAcrmHeaderValid'], [$this, 'hasAcrhHeader'], [$this, 'invalidPreflightRequest']));
        $this->rude->addRule(new Rule(
            [$this, 'hasAcrhHeader'], [$this, 'isAcrhHeaderValid'], [$this, 'setAccessControlHeaders']));
        $this->rude->addRule(new Rule(
            [$this, 'isAcrhHeaderValid'], [$this, 'setAccessControlHeaders'], [$this, 'invalidPreflightRequest']));
        $this->rude->addRule(new Rule(
            [$this, 'actualRequest'], [$this, 'setAccessControlExposeHeaders'], null));
        $this->rude->addRule(new Rule(
            [$this, 'setAccessControlExposeHeaders'], [$this, 'setAccessControlAllowOrigin'], null));
        $this->rude->addRule(new Rule(
            [$this, 'setAccessControlHeaders'], [$this, 'setAccessControlAllowOrigin'], null));
        $this->rude->addRule(new Rule(
            [$this, 'areCookiesAllowed'], [$this, 'setAccessControlAllowCredentials'], [$this, 'isPreflightRequest']));
        $this->rude->addRule(new Rule(
            [$this, 'setAccessControlAllowCredentials'], [$this, 'isPreflightRequest'], null));
        $this->rude->addRule(new Rule(
            [$this, 'setAccessControlAllowOrigin'], [$this, 'areCookiesAllowed'], null));
        $this->rude->addRule(new Rule(
            [$this, 'isPreflightRequest'], [$this, 'http200'], [$this, 'continueProcessing']));
        $this->rude->addRule(new Rule(
            [$this, 'invalidPreflightRequest'], null, null));
        $this->rude->addRule(new Rule(
            [$this, 'invalidCorsRequest'], null, null));
        $this->rude->addRule(new Rule(
            [$this, 'http200'], null, null));
        $this->rude->addRule(new Rule(
            [$this, 'continueProcessing'], null, null));
    }

    /**
     * Run the process.
     * @return void
     */
    public function run(): void
    {
        $this->addRules();
        $this->rude->check([$this, 'hasOriginHeader']);

        echo "The result is: " . $this->result . "\n";
        echo "And the path was: " . $this->rude->getPath() . "\n";
    }
}

/**
 * This is a dummy request class.
 */
class Request {
    public $isPreflight;

    /**
     * Request constructor.
     */
    public function __construct() {
        $this->isPreflight = false;
    }
}

function getRandomValue(): ?bool { return (random_int(0, 13) % 2 === 0); }
