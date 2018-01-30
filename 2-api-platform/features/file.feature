Feature: File
  Scenario: Upload file
    Given I add "Accept" header equal to "application/ld+json"
    And I send a multipart "POST" request to "/upload" with form data:
    | name   | contents |
    | file | ./fixtures/files/upload_file.txt |
    Then the response status code should be 201
    And the response should be in JSON
    Then the JSON should be valid according to this schema:
    """
    {
        "type":"object",
        "$schema": "http://json-schema.org/draft-03/schema",
        "required":false,
        "properties":{
            "createdAt": {
                "type":"string",
                "required":true
            },
            "id": {
                "type":"number",
                "required":true
            },
            "mimeType": {
                "type":"string",
                "required":true
            },
            "name": {
                "type":"string",
                "required":true
            },
            "size": {
                "type":"string",
                "required":true
            },
            "updatedAt": {
                "type":"string",
                "required":true
            }
        }
    }
    """

    When I send request for get uploaded file
    Then the response status code should be 200
    And the response should be in JSON
    Then the JSON should be valid according to this schema:
    """
    {
        "type":"object",
        "$schema": "http://json-schema.org/draft-03/schema",
        "required":false,
        "properties":{
            "createdAt": {
                "type":"string",
                "required":true
            },
            "id": {
                "type":"number",
                "required":true
            },
            "mimeType": {
                "type":"string",
                "required":true
            },
            "name": {
                "type":"string",
                "required":true
            },
            "size": {
                "type":"string",
                "required":true
            },
            "updatedAt": {
                "type":"string",
                "required":true
            }
        }
    }
    """

    When I send request for delete uploaded file
    Then the response status code should be 204

    When I send request for get uploaded file
    Then the response status code should be 404