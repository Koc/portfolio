<?php

use App\Entity\Apk\User;
use Behat\Behat\Context\Context;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTManager;
use PHPUnit\Framework\Assert;
use Ubirak\RestApiBehatExtension\Json\JsonInspector;
use Ubirak\RestApiBehatExtension\RestApiContext;
use App\Entity\Price\Item;
use App\Entity\WorldPrice\Item as WorldItem;

/**
 * Defines application features from the specific context.
 */
class FeatureContext implements Context
{
    private $jsonInspector;

    private $manager;

    private $jwtManager;

    public function __construct(JsonInspector $jsonInspector, Registry $doctrine, JWTManager $jwtManager)
    {
        $this->jsonInspector = $jsonInspector;
        $this->manager = $doctrine;
        $this->jwtManager = $jwtManager;
    }

    /**
     * @BeforeScenario
     * @login
     *
     * @param BeforeScenarioScope $scope
     */
    public function login(BeforeScenarioScope $scope)
    {
        $user = $this->manager
            ->getManager()
            ->getRepository(User::class)
            ->findOneBy([]);

        $token = $this->jwtManager->create($user);

        $scope->getEnvironment()
            ->getContext(RestApiContext::class)
            ->iAddHeaderEqualTo('Authorization', "Bearer $token")
        ;
    }

    /**
     * @Then the each item of JSON path expression :pathExpression should be not empty
     */
    public function theEachItemOfJsonPathExpressionShouldBeNotEmpty($pathExpression)
    {
        $actualJsonItems = $this->jsonInspector->searchJsonPath($pathExpression);

        Assert::assertNotEmpty($actualJsonItems);
    }

    /**
     * @Then the each item of JSON path expression :pathExpression should be equal to :expectedJson
     */
    public function theEachItemOfJsonPathExpressionShouldBeEqualToJson($pathExpression, $expectedJson)
    {
        $actualJsonItems = $this->jsonInspector->searchJsonPath($pathExpression);

        foreach ($actualJsonItems as $actualJsonItem) {
            Assert::assertEquals($expectedJson, $actualJsonItem);
        }
    }

    /**
     * @Then the each item of JSON path expression :pathExpression should starts with :prefix
     */
    public function theEachItemOfJsonPathExpressionShouldStartsWith($pathExpression, $prefix)
    {
        $actualJsonItems = $this->jsonInspector->searchJsonPath($pathExpression);

        foreach ($actualJsonItems as $actualJsonItem) {
            Assert::assertStringStartsWith($prefix, $actualJsonItem);
        }
    }

    /**
     * @Then the each item of JSON path expression :pathExpression should contain :expectedString
     */
    public function theEachItemOfJsonPathExpressionShouldContain($pathExpression, $expectedString)
    {
        $actualJsonItems = $this->jsonInspector->searchJsonPath($pathExpression);

        foreach ($actualJsonItems as $actualJsonItem) {
            Assert::assertContains($expectedString, $actualJsonItem);
        }
    }

    /**
     * @Then the JSON path expression :pathExpression should contain :expectedString
     */
    public function theJsonPathExpressionShouldContain($pathExpression, $expectedString)
    {
        $actualJsonItems = $this->jsonInspector->searchJsonPath($pathExpression);

        Assert::assertContains($expectedString, $actualJsonItems);
    }

    /**
     * @Given /^I reset "([^"]*)" with id "([^"]*)"$/
     */
    public function iResetWithId($name, $id)
    {
        switch ($name) {
            case 'item price':
                $item = $this->manager->getRepository(Item::class)->find((int)$id);

                if ($item) {
                    $item->setAvgPrice(0);
                    $item->setMaxPrice(0);
                    $item->setMinPrice(0);
                    $this->manager->getManager()->flush();
                }
                break;

            case 'world item price':
                $worldItem = $this->manager->getRepository(WorldItem::class)->find((int)$id);

                if ($worldItem) {
                    $worldItem->setPrice(0);
                    $this->manager->getManager()->flush();
                }
                break;
        }
    }
}
