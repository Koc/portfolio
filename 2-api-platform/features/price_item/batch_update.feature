Feature: Price Item

  Scenario: Update Item Price with id 1
    Given I reset "item price" with id "1"
    And I add "Accept" header equal to "application/ld+json"
    When I add "Content-Type" header equal to "application/ld+json"
    And I send a POST request to "/items/batch" with body:
    """
    {
      "priceItemRequests": [
        {
          "item": "/api/items/1",
          "minPrice": 11,
          "maxPrice": 33,
          "avgPrice": 22
        }
      ]
    }
    """
    Then the response status code should be 201
    And the response should be in JSON
    And the JSON should be equal to:
    """
    {
      "@context": "/api/contexts/Item",
      "@id": "/api/items/batch",
      "@type": "hydra:Collection",
      "hydra:member": [
        {
          "@id": "/api/items/1",
          "@type": "Item",
          "id": 1,
          "publishedAt": "2017-08-01T21:00:00+00:00",
          "minPrice": 11,
          "maxPrice": 33,
          "avgPrice": 22,
          "isChecked": true,
          "periodicity": 4
        }
      ],
      "hydra:totalItems": 1
    }
    """

    And I send a GET request to "/items/1"
    Then the response status code should be 200
    And the response should be in JSON
    And the JSON node "minPrice" should contain "11"
    And the JSON node "maxPrice" should contain "33"
    And the JSON node "avgPrice" should contain "22"

  Scenario: Update Item Price with id 1
    Given I reset "item price" with id "1"
    And I add "Accept" header equal to "application/ld+json"
    When I add "Content-Type" header equal to "application/ld+json"
    And I send a POST request to "/items/batch" with body:
    """
    {
      "priceItemRequests": [
        {
          "item": "/api/items/1",
          "minPrice": 11,
          "maxPrice": 33,
          "avgPrice": 22
        }
      ]
    }
    """
    Then the response status code should be 201
    And the response should be in JSON
    And the JSON should be equal to:
    """
    {
      "@context": "/api/contexts/Item",
      "@id": "/api/items/batch",
      "@type": "hydra:Collection",
      "hydra:member": [
        {
          "@id": "/api/items/1",
          "@type": "Item",
          "id": 1,
          "publishedAt": "2017-08-01T21:00:00+00:00",
          "minPrice": 11,
          "maxPrice": 33,
          "avgPrice": 22,
          "isChecked": true,
          "periodicity": 4
        }
      ],
      "hydra:totalItems": 1
    }
    """
    And I send a GET request to "/items/1"
    Then the response status code should be 200
    And the response should be in JSON
    And the JSON node "minPrice" should contain "11"
    And the JSON node "maxPrice" should contain "33"
    And the JSON node "avgPrice" should contain "22"

  Scenario: Update Item Price with id 1, 2
    Given I reset "item price" with id "1"
    And I reset "item price" with id "2"
    And I add "Accept" header equal to "application/ld+json"
    When I add "Content-Type" header equal to "application/ld+json"
    And I send a POST request to "/items/batch" with body:
    """
    {
      "priceItemRequests": [
        {
          "item": "/api/items/1",
          "minPrice": 11,
          "maxPrice": 33,
          "avgPrice": 22
        },
        {
          "item": "/api/items/2",
          "minPrice": 44,
          "maxPrice": 77,
          "avgPrice": 55
        }
      ]
    }
    """
    Then the response status code should be 201
    And the response should be in JSON
    And the JSON should be equal to:
    """
    {
      "@context": "/api/contexts/Item",
      "@id": "/api/items/batch",
      "@type": "hydra:Collection",
      "hydra:member": [
        {
          "@id": "/api/items/1",
          "@type": "Item",
          "id": 1,
          "publishedAt": "2017-08-01T21:00:00+00:00",
          "minPrice": 11,
          "maxPrice": 33,
          "avgPrice": 22,
          "isChecked": true,
          "periodicity": 4
        },
        {
          "@id": "/api/items/2",
          "@type": "Item",
          "id": 2,
          "publishedAt": "2017-08-01T21:00:00+00:00",
          "minPrice": 44,
          "maxPrice": 77,
          "avgPrice": 55,
          "isChecked": true,
          "periodicity": 4
        }
      ],
      "hydra:totalItems": 2
    }
    """
    And I send a GET request to "/items/1"
    Then the response status code should be 200
    And the response should be in JSON
    And the JSON node "minPrice" should contain "11"
    And the JSON node "maxPrice" should contain "33"
    And the JSON node "avgPrice" should contain "22"
    And I send a GET request to "/items/2"
    Then the response status code should be 200
    And the response should be in JSON
    And the JSON node "minPrice" should contain "44"
    And the JSON node "maxPrice" should contain "77"
    And the JSON node "avgPrice" should contain "55"


  Scenario: Update Item Price with id 1, 2 with errors
    Given I add "Accept" header equal to "application/ld+json"
    When I add "Content-Type" header equal to "application/ld+json"
    And I send a POST request to "/items/batch" with body:
    """
    {
      "priceItemRequests": [
        {
          "item": "/api/items/1",
          "minPrice": -11,
          "maxPrice": 33,
          "avgPrice": 22
        },
        {
          "item": "/api/items/2",
          "minPrice": 44,
          "maxPrice": "qwww",
          "avgPrice": 55
        }
      ]
    }
    """
    Then the response status code should be 400
    And the response should be in JSON
    And the JSON should be equal to:
    """
    {
      "@context": "/api/contexts/ConstraintViolationList",
      "@type": "ConstraintViolationList",
      "hydra:title": "An error occurred",
      "hydra:description": "priceItemRequests[0].minPrice: This value should be greater than or equal to 0.\npriceItemRequests[1].maxPrice: This value should be of type numeric.",
      "violations": [
        {
          "propertyPath": "priceItemRequests[0].minPrice",
          "message": "This value should be greater than or equal to 0."
        },
        {
          "propertyPath": "priceItemRequests[1].maxPrice",
          "message": "This value should be of type numeric."
        }
      ]
    }
    """
