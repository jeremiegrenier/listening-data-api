Feature: Update artist
  As a manager
  I need to be able to update artist informations

  Scenario: Patch status
    Given there is an artist "testArtist" with id "5"
    And the request body is:
    """
      [
        {
          "op" : "replace",
          "path": "status",
          "value": "newStatus"
        }
      ]
    """
    When I request "/artists/5" using HTTP "PATCH"
    Then the response code is 200
    And I should see a formatted response
    And the response body contains JSON:
      """
      {
          "data": {}
      }
      """
    And artist "5" should have "newStatus" in "status"

  Scenario: Patch twitter
    Given there is an artist "testArtist" with id "5"
    And the request body is:
    """
      [
        {
          "op" : "replace",
          "path": "twitter",
          "value": "http://www.twitter.com/toto"
        }
      ]
    """
    When I request "/artists/5" using HTTP "PATCH"
    Then the response code is 200
    And I should see a formatted response
    And the response body contains JSON:
      """
      {
          "data": {}
      }
      """
    And artist "5" should have "http://www.twitter.com/toto" in "twitter"

  Scenario: Invalid operations formats
    Given there is an artist "testArtist" with id "5"
    And the request body is:
    """
      [
        {
          "op" : "replace",
        }
      ]
    """
    When I request "/artists/5" using HTTP "PATCH"
    Then the response code is 400
    And I should see a formatted response
    And the response body contains JSON:
      """
      {
          "data": {},
          "errors": [
            {
                "message": "Operations are invalid",
                "internalDetail": "Operations are invalid",
                "context": null
            }
          ]
      }
      """
    Given the request body is:
    """
      [
        {
          "path" : "replace",
        }
      ]
    """
    When I request "/artists/5" using HTTP "PATCH"
    Then the response code is 400
    And I should see a formatted response
    And the response body contains JSON:
      """
      {
          "data": {},
          "errors": [
            {
                "message": "Operations are invalid",
                "internalDetail": "Operations are invalid",
                "context": null
            }
          ]
      }
      """
  Scenario: Operation not managed yet
    Given there is an artist "testArtist" with id "5"
    And the request body is:
    """
      [
        {
          "op" : "add",
          "path": "status"
        }
      ]
    """
    When I request "/artists/5" using HTTP "PATCH"
    Then the response code is 400
    And I should see a formatted response
    And the response body contains JSON:
      """
      {
          "data": {},
          "errors": [
            {
                "message": "Operation add not managed",
                "internalDetail": "Operation add not managed",
                "context": null
            }
          ]
      }
      """
    Given the request body is:
     """
      [
        {
          "op" : "remove",
          "path": "status"
        }
      ]
    """
    When I request "/artists/5" using HTTP "PATCH"
    Then the response code is 400
    And I should see a formatted response
    And the response body contains JSON:
      """
      {
          "data": {},
          "errors": [
            {
                "message": "Operation remove not managed",
                "internalDetail": "Operation remove not managed",
                "context": null
            }
          ]
      }
      """
