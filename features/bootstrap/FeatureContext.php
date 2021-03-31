<?php

use Behat\Behat\Hook\Scope\AfterScenarioScope;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Middleware;
use Psr\Http\Message\RequestInterface;

/**
 * Defines application features from the specific context.
 */
class FeatureContext extends \Imbo\BehatApiExtension\Context\ApiContext
{
    /** @var mysqli */
    private $databaseConnection;

    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
        $this->databaseConnection = new \mysqli(
            getenv('MYSQL_HOST'),
            getenv('MYSQL_USERNAME'),
            getenv('MYSQL_PASSWD'),
            getenv('MYSQL_DBNAME')
        );
    }

    /**
     * Manipulate the API client
     *
     * @param ClientInterface $client
     * @return self
     */
    public function setClient(ClientInterface $client) {
        $stack = $client->getConfig('handler');
        $stack->push(Middleware::mapRequest(function(RequestInterface $request) {
            // Add something to the request and return the new instance
            return $request->withAddedHeader('Some-Custom-Header', 'some value');
        }));

        return parent::setClient($client);
    }

    /**
     * @Given there is an artist :artistName with id :artistId
     */
    public function thereIsAnArtistWithId($artistName, $artistId)
    {
        $sql = "INSERT INTO `artists` (`id`, `name`)
                VALUES(?, ?)          
        ";

        $stmt = $this->databaseConnection->prepare($sql);
        $stmt->bind_param('is', $artistId, $artistName);
        $stmt->execute();
    }

    /**
     * @Given Artist :artistId has :nbStream stream from :gender on :date
     */
    public function artistHasStreamFromOn($artistId, $nbStream, $gender, $date)
    {
        $sql = "INSERT INTO `artists_data` (`artist_id`, `date`, `gender`, `nb_streams`)
                VALUES(?, ?, ?, ?)          
        ";

        $stmt = $this->databaseConnection->prepare($sql);
        $stmt->bind_param('issi', $artistId, $date, $gender, $nbStream);
        $stmt->execute();
    }

    /**
     * @Then I should see a formatted response
     */
    public function iShouldSeeAFormattedResponse()
    {
        $content = json_decode($this->response->getBody()->getContents(), true);

        \Assert\Assertion::keyExists($content, 'timestamp');
        \Assert\Assertion::keyExists($content, 'success');
        \Assert\Assertion::keyExists($content, 'message');
        \Assert\Assertion::keyExists($content, 'data');
        \Assert\Assertion::keyExists($content, 'errors');
    }

    /**
     * @Then artist :artistId should have :value in :path
     */
    public function artistShouldHaveIn($artistId, $value, $path)
    {
        $sql = "SELECT * FROM `artists`
                WHERE id = ?          
        ";

        $stmt = $this->databaseConnection->prepare($sql);
        $stmt->bind_param('i', $artistId);
        $stmt->execute();

        $result = $stmt->get_result();
        if (false === $result) {
            throw new \Exception('Artist not found');
        }

        $result = $result->fetch_array();

        if($result[$path] !== $value)
        {
            throw new \Exception('Value '.$value.' for '.$path.' not found in artist '.$artistId.'. Found : '.$result[$path]);
        }
    }


    /**
     * @AfterScenario
     */
    public function cleanDB(AfterScenarioScope $scope)
    {
        $sql = "DELETE FROM `artists`;";

        $stmt = $this->databaseConnection->prepare($sql);
        $stmt->execute();

        $sql = "DELETE FROM `artists_data`;";

        $stmt = $this->databaseConnection->prepare($sql);
        $stmt->execute();
    }
}
