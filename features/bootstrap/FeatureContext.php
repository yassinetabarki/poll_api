<?php

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;

/**
 * Defines application features from the specific context.
 * @property  responseBody
 */
class FeatureContext implements Context
{
    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
        $this->bearerToken = "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6IjBjNmY0YTM2MDliYzIzM2FhOTkzYjhhYmRhNjYwMzNhZGU1OWUwMWRiMzYzYjY3MGRmNmMwNTJlZTA1ZTI4MzZmNWNjZDJlOTk0ZTMzMjQ5In0.eyJhdWQiOiI1IiwianRpIjoiYWVlOWEzOTQ4YWNkOGFmNzUyYTk2YjkyNDhmZTQ3M2M2ZjJkZWEwYmVlZWU2YTQyNWU2MjNjMThlNWNmMTY2YjBkYmFhMTJlYTBlYzNjODYiLCJpYXQiOjE1MTY0OTg5MDMsIm5iZiI6MTUxNjQ5ODkwMywiZXhwIjoxNTQ4MDM0OTAzLCJzdWIiOiIxIiwic2NvcGVzIjpbXX0.bdVkcd8GZBi5jJJmErOX119P-G9RioxF28l7DwkdyOEcPkgXq3-2-0o-J2dKsQxezhNZSzTD6iKkZ5jh8r6xfh6GWrOjEL7qY9oDvl8SZvdKpWEY6cQYv3O4Aph3KpVqePYk7GlG8wYSi1R3DIwXk-Txzozg4iZJn1yq7ZD2rME8wVIvxIT0Z1eOtsl3wqZGbrg_lGSxLen1AwcbYfkOXR6fUDlfOGC68wrZVAtscM1QVRyzxibbOe6SSvfLzYsSH7Vy5DtEcUcNzVaXdPm074cKNS7mW5ib2h853yl9ONAwEF0zwVQ6uXF57PL7kl0j9FrEAto5NtKmQLmcWyDURJqBwzn1QJhn94bDf7XUOh0gkGvWKH3iHDEi66BPjAt_ykdpVVM-H2qJ7TBCgsFCMnTLFGg3qNCXNZl_7-TijweyZIrHDC_j1FWhlPWoqUL4Mz_g2-9uWo2BOA3XJDi0Q6cfppzFp3zby9aLGb3Y31YD5Y2DIcQCLM-O8HYmJ1HdfkH2KQUsmw0fiXDXrgA2S7BveRAt3WL9002gte4tp2B_Xb6ykVOlU-5rXt6XyWEMdfoBjjotGV-V8vwkQYhMwJNXTcVEfti0R82IFLObavXsr3Bw_QcCPCtLrPcgWoSI-AC90o4lUtEfi8R3S3CIQNUKin_beGoXktWwLiax_0M";
    }

    /**
     * @Given I have the payload:
     */
    public function iHaveThePayload(PyStringNode $string)
    {
        $this->payload = $string;
    }

    /**
     * @When /^I request "(GET|PUT|POST|DELETE|PATCH) ([^"]*)"$/
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function iRequest($httpMethod, $argument1)
    {
        $client = new GuzzleHttp\Client();
        $this->response = $client->request(
            $httpMethod,
            'http://127.0.0.1:8000' . $argument1,
            [
                'body' => $this->payload,
                'headers' => [
                    "Authorization" => "Bearer {$this->bearerToken}",
                    "Content-Type" => "application/json",
                ],
            ]
        );
        $this->responseBody = $this->response->getBody(true);
    }

    /**
     * @Then /^I get a response$/
     */
    public function iGetAResponse()
    {
        if (empty($this->responseBody)) {
            throw new Exception('Did not get a response from the API');
        }
    }

    /**
     * @Given /^the response is JSON$/
     * @throws Exception
     */
    public function theResponseIsJson()
    {
        $data = json_decode($this->responseBody);

        if (empty($data)) {
            throw new Exception("Response was not JSON\n" . $this->responseBody);
        }
    }

    /**
     * @Then the response contains :arg1 records
     */
    public function theResponseContainsRecords($arg1)
    {
        $data=json_decode($this->responseBody);
        $count=count($data);
        return $count== $arg1;

    }

    /**
     * @Then the question containe a title of :arg1
     * @throws Exception
     */
    public function theQuestionContaineATitleOf($arg1)
    {
        $data=json_decode($this->responseBody);
        if($data->title == $arg1)
        {

        }else{
            throw new Exception("record do not match");
        }
    }

    /**
     * @Then the response contains a question
     */
    public function theResponseContainsAQuestion()
    {
        $data = json_decode($this->responseBody);

        $question = $data[0];

        if (!property_exists($question, 'question')) {
            throw new Exception('This is not a question');
        }
    }
}