<?php

require __DIR__ . '/../vendor/autoload.php';

date_default_timezone_set('Europe/Amsterdam');

use Guzzle\Http\Client;
use Guzzle\Http\Exception\BadResponseException;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use OpenConext\JanusClient\Entity\Assembler\ConnectionDescriptorAssembler;
use OpenConext\JanusClient\Entity\Connection;
use OpenConext\JanusClient\Entity\Assembler\ConnectionAssembler;
use OpenConext\JanusClient\Entity\Assembler\ConnectionDisassembler;
use OpenConext\JanusClient\Entity\ConnectionDescriptorRepository;
use OpenConext\JanusClient\Entity\ConnectionRepository;
use OpenConext\JanusClient\NewConnectionRevision;
use OpenConext\JanusClient\ResponseStatusCodeValidator;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\PropertyNormalizer;
use Symfony\Component\Serializer\Serializer;

try {
    $client = new Client(
        'https://engineblock:do8He2KKd6m1@serviceregistry.test.surfconext.nl/janus/app.php/api',
        array(
            'headers' => array(
                'User-Agent' => 'Demo - Janus Client v1.0 (https://github.com/janus-ssp/janus-client)',
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ),
        )
    );
    $logger = new Logger('connections', array(new StreamHandler('php://stdout')));

    $normalizer = new PropertyNormalizer();
    $serializer = new Serializer(
        array($normalizer),
        array(new JsonEncoder())
    );

    $statusCodeValidator = new ResponseStatusCodeValidator($logger);

    $descriptorAssembler = new ConnectionDescriptorAssembler();
    $descriptorRepository = new ConnectionDescriptorRepository($client, $logger, $descriptorAssembler, $serializer, $statusCodeValidator);

    $assembler = new ConnectionAssembler();
    $disassembler = new ConnectionDisassembler();
    $repository = new ConnectionRepository($client, $assembler, $disassembler, $serializer, $statusCodeValidator);

    print 'All connections in the repository' . PHP_EOL;
    var_dump($descriptorRepository->findAll());


    print 'A single full connection' . PHP_EOL;
    $mockIdpDescriptor = $descriptorRepository->fetchByName('http://mock-idp');
    $mockIdp = $repository->findById($mockIdpDescriptor->getId());
    var_dump($mockIdp);

    $entityId = 'https://sp.example.org/sp';

    if ($connectionDescriptor = $descriptorRepository->findByName($entityId)) {
        print 'Cleaning up an old connection.' . PHP_EOL;
        $repository->delete($connectionDescriptor->getId());
    }

    print 'Inserting a new connection.' . PHP_EOL;
    $connection = new Connection(
        $entityId,
        Connection::TYPE_SP,
        Connection::WORKFLOW_TEST,
        array(
            'name:en' => 'Janus Client Dummy SP',
            'name:nl' => 'Janus Client Dummy SP',
            'description:en' => 'Janus Client Dummy SP',
            'description:nl' => 'Janus Client Dummy SP',
            'url:nl' => 'https://github.com/janus-ssp/client',
            'url:en' => 'https://github.com/janus-ssp/client',
            'NameIDFormat' => 'urn:oasis:names:tc:SAML:2.0:nameid-format:persistent',
            'logo:0:url' => 'https://encrypted-tbn1.gstatic.com/images?q=tbn:ANd9GcRDlSm97PkUR60zjgSUeswdNx3Ha4gdoa6ZxyZAmzFvwvnh28SsB3c2Is4',
            'logo:0:height' => '55',
            'logo:0:width'  => '58',
            'contacts:0:contactType' => 'technical',
            'contacts:0:emailAddress' => 'boy@ibuildings.nl',
            'contacts:0:givenName' => 'Boy',
            'contacts:0:surName' => 'Baukema',
            
            'contacts:1:contactType' => 'support',
            'contacts:1:emailAddress' => 'boy@ibuildings.nl',
            'contacts:1:givenName' => 'Boy',
            'contacts:1:surName' => 'Baukema',

            'contacts:2:contactType' => 'administrative',
            'contacts:2:emailAddress' => 'boy@ibuildings.nl',
            'contacts:2:givenName' => 'Boy',
            'contacts:2:surName' => 'Baukema',
            
            'AssertionConsumerService:0:Binding' => 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-POST',
        ),
        'https://example.org/sp/metadata'
    );
    $connection->disableConsentFor($mockIdpDescriptor);
    $connection->deactivate();
    if (!$connection->isActive()) {
        $connection->activate();
    }

    $connection->manipulate(
        '// insert code here for EngineBlock manipulations'
    );

    $connection = $repository->insert(
        new NewConnectionRevision($connection, 'Insert from connection-crud example')
    );

    print "Inserted the following connection: ";
    var_dump($connection);

    $connection = $repository->fetchById($connection->getId())
        ->manipulate('')
        ->setMetadata('redirect:sign', true)
        ->setMetadata('certData', 'MIIDYzCCAkugAwIBAgIJAOzar9lPvHCWMA0GCSqGSIb3DQEBBQUAMEgxFDASBgNVBAMMC0VuZ2luZUJsb2NrMREwDwYDVQQLDAhTZXJ2aWNlczEQMA4GA1UECgwHU1VSRm5ldDELMAkGA1UEBhMCTkwwHhcNMTIwNjI2MDgwMzA0WhcNMjIwNjI2MDgwMzA0WjBIMRQwEgYDVQQDDAtFbmdpbmVCbG9jazERMA8GA1UECwwIU2VydmljZXMxEDAOBgNVBAoMB1NVUkZuZXQxCzAJBgNVBAYTAk5MMIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAzqcxWPW9')
        ->acceptForProduction();

    $connection = $repository->update(
        new NewConnectionRevision(
            $connection,
            'Demo Janus Client - Set some metadata'
        )
    );

    print "Updated the following connection: ";
    var_dump($connection);

    $repository->delete($connection->getId());

    print "Deleting the connection" . PHP_EOL;

    if (!$repository->findById($connection->getId())) {
        print "Deleted the connection succesfully" . PHP_EOL;
    } else {
        print "But it's still there?" . PHP_EOL;
    }
} catch (BadResponseException $e) {
    $response = $e->getResponse();
    if ($response->isContentType('application/json')) {
        print_r(json_decode($response->getBody(true)));
    }
    print_r((string) $e->getResponse());
}
