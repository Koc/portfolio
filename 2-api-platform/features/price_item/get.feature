Feature: Price Commodity

  Scenario: Retrieve the Price Commodity list
    Given I add "Accept" header equal to "application/ld+json"
    When I send a GET request to "/commodities"
    Then the response status code should be 200
    And the response should be in JSON
    And the JSON node "hydra:member" should have 30 elements
    # https://spacetelescope.github.io/understanding-json-schema/index.html
    Then the JSON should be valid according to this schema:
      """
      {
         "type":"object",
         "$schema":"http://json-schema.org/draft-03/schema",
         "id":"http://jsonschema.net",
         "required":false,
         "properties":{
            "@context":{
               "type":"string",
               "required":true
            },
            "@id":{
               "type":"string",
               "required":true
            },
            "@type":{
               "type":"string",
               "required":true
            },
            "hydra:member":{
               "type":"array",
               "required":true,
               "items":{
                  "type":"object",
                  "required":true,
                  "properties":{
                     "@id":{
                        "type":"string",
                        "required":false
                     },
                     "@type":{
                        "type":"string",
                        "required":false
                     },
                     "currency":{
                        "type":"string",
                        "required":false
                     },
                     "deliveryCondition":{
                        "type":"string",
                        "required":false
                     },
                     "deliveryDescription":{
                        "type":"null",
                        "required":false
                     },
                     "fullName":{
                        "type":"string",
                        "required":false
                     },
                     "groupCommodities":{
                        "type":"array",
                        "required":true,
                        "items":{
                           "type":"object",
                           "required":false,
                           "properties":{
                              "@id":{
                                 "type":"string",
                                 "required":false
                              },
                              "@type":{
                                 "type":"string",
                                 "required":false
                              },
                              "groupId":{
                                 "type":"number",
                                 "required":false
                              }
                           }
                        }
                     },
                     "id":{
                        "type":"number",
                        "required":true
                     },
                     "isChecked" :
                     {
                       "required": true,
                       "type": "boolean"
                     },
                     "items":{
                        "type":"array",
                        "required":true,
                        "items":{
                           "type":"object",
                           "required":false,
                           "properties":{
                              "@id":{
                                 "type":"string",
                                 "required":false
                              },
                              "@type":{
                                 "type":"string",
                                 "required":false
                              },
                              "avgPrice":{
                                 "type":"number",
                                 "required":false
                              },
                              "id":{
                                 "type":"number",
                                 "required":false
                              },
                              "isChecked":{
                                 "type":"boolean",
                                 "required":false
                              },
                              "maxPrice":{
                                 "type":"number",
                                 "required":false
                              },
                              "minPrice":{
                                 "type":"number",
                                 "required":false
                              },
                              "periodicity":{
                                 "type":"number",
                                 "required":false
                              },
                              "publishedAt":{
                                 "type":"string",
                                 "required":false
                              }
                           }
                        }
                     },
                     "nameEn":{
                        "type":"string",
                        "required":true
                     },
                     "name":{
                        "type":"string",
                        "required":true
                     },
                     "periodicity":{
                        "type":"number",
                        "required":true
                     },
                     "type":{
                        "type":"number",
                        "required":true
                     },
                     "unit":{
                        "type":"string",
                        "required":true
                     }
                  }
               }
            },
            "hydra:search":{
               "type":"object",
               "required":false,
               "properties":{
                  "@type":{
                     "type":"string",
                     "required":true
                  },
                  "hydra:mapping":{
                     "type":"array",
                     "required":false,
                     "items":{
                        "type":"object",
                        "required":false,
                        "properties":{
                           "@type":{
                              "type":"string",
                              "required":true
                           },
                           "property":{
                              "type":"string",
                              "required":true
                           },
                           "required":{
                              "type":"boolean",
                              "required":true
                           },
                           "variable":{
                              "type":"string",
                              "required":true
                           }
                        }
                     }
                  },
                  "hydra:template":{
                     "type":"string",
                     "required":false
                  },
                  "hydra:variableRepresentation":{
                     "type":"string",
                     "required":false
                  }
               }
            },
            "hydra:totalItems":{
               "type":"number",
               "required":true
            },
            "hydra:view":{
               "type":"object",
               "required":false,
               "properties":{
                  "@id":{
                     "type":"string",
                     "required":true
                  },
                  "@type":{
                     "type":"string",
                     "required":true
                  },
                  "hydra:first":{
                     "type":"string",
                     "required":true
                  },
                  "hydra:last":{
                     "type":"string",
                     "required":true
                  },
                  "hydra:next":{
                     "type":"string",
                     "required":true
                  }
               }
            }
         }
      }
      """

  Scenario: Retrieve the Price Commodity list for group 45
    Given I add "Accept" header equal to "application/ld+json"
    When I send a GET request to "/commodities?group=45"
    Then the response status code should be 200
    And the response should be in JSON
    And the JSON path expression '"hydra:member"[*].groupCommodities[*].groupId[]' should have result
    And the each item of JSON path expression '"hydra:member"[*].groupCommodities[*].groupId[]' should be equal to 45

  Scenario: Retrieve the Price Commodity list for date 01.08.2017
    Given I add "Accept" header equal to "application/ld+json"
    When I send a GET request to "/commodities?date=01.08.2017"
    Then the response status code should be 200
    And the response should be in JSON
    And the JSON path expression '"hydra:member"[*].items[]' should have result
    And the each item of JSON path expression '"hydra:member"[*].items[].publishedAt' should starts with '2017-08-01'

  Scenario: Retrieve the Price Commodity list for date 01.08.2017 and group 45
    Given I add "Accept" header equal to "application/ld+json"
    When I send a GET request to "/commodities?date=01.08.2017&group=45"
    Then the response status code should be 200
    And the response should be in JSON
    And the JSON path expression '"hydra:member"[*].items[]' should have result
    And the each item of JSON path expression '"hydra:member"[*].items[].publishedAt' should starts with '2017-08-01'
    And the JSON path expression '"hydra:member"[*].groupCommodities[*].groupId[]' should have result
    And the each item of JSON path expression '"hydra:member"[*].groupCommodities[*].groupId[]' should be equal to 45
