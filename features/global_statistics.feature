Feature: Global manager statistics
  As a manager
  I need to be able to see statistics

  Scenario: Statistics same listen on day
    Given there is an artist "testArtist" with id "5"
    And there is an artist "otherArtist" with id "3"
    And Artist "5" has "3" stream from "F" on "2021-03-25"
    And Artist "5" has "3" stream from "H" on "2021-03-25"
    And Artist "5" has "6" stream from "H" on "2020-03-26"
    And Artist "5" has "3" stream from "F" on "2020-03-26"
    And Artist "5" has "6" stream from "H" on "2020-03-27"
    And Artist "5" has "3" stream from "F" on "2020-03-27"
    When I request "/manager?date=2020-03-27"
    Then the response code is 200
    And I should see a formatted response
    And the response body contains JSON:
      """
      {
          "data": {
              "startDate": "2020-03-25",
              "endDate": "2020-03-27",
              "average": "9.0000",
              "artistAverage": [
                  {
                      "artistId": 5,
                      "average": "9.0000",
                      "percentage": 100
                  }
              ]
          }
      }
      """

  Scenario: Statistics different listen on day
    Given there is an artist "testArtist" with id "5"
    And there is an artist "otherArtist" with id "3"
    And Artist "5" has "3" stream from "F" on "2021-03-25"
    And Artist "5" has "3" stream from "H" on "2021-03-25"
    And Artist "5" has "12" stream from "H" on "2020-03-26"
    And Artist "5" has "12" stream from "F" on "2020-03-26"
    And Artist "5" has "0" stream from "H" on "2020-03-27"
    And Artist "5" has "2" stream from "F" on "2020-03-27"
    When I request "/manager?date=2020-03-27"
    Then the response code is 200
    And I should see a formatted response
    And the response body contains JSON:
      """
      {
          "data": {
              "startDate": "2020-03-25",
              "endDate": "2020-03-27",
              "average": "13.0000",
              "artistAverage": [
                  {
                      "artistId": 5,
                      "average": "13.0000",
                      "percentage": 100
                  }
              ]
          }
      }
      """
  Scenario: Statistics same listen on day multiple artist
    Given there is an artist "testArtist" with id "5"
    And there is an artist "otherArtist" with id "3"
    And Artist "5" has "3" stream from "F" on "2021-03-25"
    And Artist "5" has "3" stream from "H" on "2021-03-25"
    And Artist "5" has "6" stream from "H" on "2020-03-26"
    And Artist "5" has "3" stream from "F" on "2020-03-26"
    And Artist "5" has "6" stream from "H" on "2020-03-27"
    And Artist "5" has "3" stream from "F" on "2020-03-27"
    And Artist "3" has "3" stream from "F" on "2021-03-25"
    And Artist "3" has "3" stream from "H" on "2021-03-25"
    And Artist "3" has "6" stream from "H" on "2020-03-26"
    And Artist "3" has "3" stream from "F" on "2020-03-26"
    And Artist "3" has "6" stream from "H" on "2020-03-27"
    And Artist "3" has "3" stream from "F" on "2020-03-27"
    When I request "/manager?date=2020-03-27"
    Then the response code is 200
    And I should see a formatted response
    And the response body contains JSON:
      """
      {
          "data": {
              "startDate": "2020-03-25",
              "endDate": "2020-03-27",
              "average": "18.0000",
              "artistAverage": [
                  {
                      "artistId": 5,
                      "average": "9.0000",
                      "percentage": 50
                  },
                  {
                      "artistId": 3,
                      "average": "9.0000",
                      "percentage": 50
                  }
              ]
          }
      }
      """

  Scenario: Statistics different listen on day multiple artist
    Given there is an artist "testArtist" with id "5"
    And there is an artist "otherArtist" with id "3"
    And Artist "5" has "3" stream from "F" on "2021-03-25"
    And Artist "5" has "3" stream from "H" on "2021-03-25"
    And Artist "5" has "12" stream from "H" on "2020-03-26"
    And Artist "5" has "12" stream from "F" on "2020-03-26"
    And Artist "5" has "0" stream from "H" on "2020-03-27"
    And Artist "5" has "2" stream from "F" on "2020-03-27"
    And Artist "3" has "0" stream from "F" on "2021-03-25"
    And Artist "3" has "9" stream from "H" on "2021-03-25"
    And Artist "3" has "2" stream from "H" on "2020-03-26"
    And Artist "3" has "2" stream from "F" on "2020-03-26"
    And Artist "3" has "6" stream from "H" on "2020-03-27"
    And Artist "3" has "4" stream from "F" on "2020-03-27"
    When I request "/manager?date=2020-03-27"
    Then the response code is 200
    And I should see a formatted response
    And the response body contains JSON:
      """
      {
          "data": {
              "startDate": "2020-03-25",
              "endDate": "2020-03-27",
              "average": "20.0000",
              "artistAverage": [
                  {
                      "artistId": 5,
                      "average": "13.0000",
                      "percentage": 65
                  },
                  {
                      "artistId": 3,
                      "average": "7.0000",
                      "percentage": 35
                  }
              ]
          }
      }
      """

  Scenario: Ask for future date
    Given there is an artist "testArtist" with id "5"
    And there is an artist "otherArtist" with id "3"
    And Artist "5" has "3" stream from "F" on "2021-03-25"
    And Artist "5" has "3" stream from "H" on "2021-03-25"
    And Artist "5" has "12" stream from "H" on "2020-03-26"
    And Artist "5" has "12" stream from "F" on "2020-03-26"
    And Artist "5" has "0" stream from "H" on "2020-03-27"
    And Artist "5" has "2" stream from "F" on "2020-03-27"
    And Artist "3" has "0" stream from "F" on "2021-03-25"
    And Artist "3" has "9" stream from "H" on "2021-03-25"
    And Artist "3" has "2" stream from "H" on "2020-03-26"
    And Artist "3" has "2" stream from "F" on "2020-03-26"
    And Artist "3" has "6" stream from "H" on "2020-03-27"
    And Artist "3" has "4" stream from "F" on "2020-03-27"
    When I request "/manager?date=2999-03-27"
    Then the response code is 400
    And I should see a formatted response
    And the response body contains JSON:
      """
      {
          "data": [],
          "errors" : [
             {
                "message": "Date given is invalid : Date could not be in future",
                "internalDetail": "Date given is invalid : Date could not be in future",
                "context": null
            }
          ]
      }
      """

  Scenario: Ask for invalid date format
    Given there is an artist "testArtist" with id "5"
    And there is an artist "otherArtist" with id "3"
    And Artist "5" has "3" stream from "F" on "2021-03-25"
    And Artist "5" has "3" stream from "H" on "2021-03-25"
    And Artist "5" has "12" stream from "H" on "2020-03-26"
    And Artist "5" has "12" stream from "F" on "2020-03-26"
    And Artist "5" has "0" stream from "H" on "2020-03-27"
    And Artist "5" has "2" stream from "F" on "2020-03-27"
    And Artist "3" has "0" stream from "F" on "2021-03-25"
    And Artist "3" has "9" stream from "H" on "2021-03-25"
    And Artist "3" has "2" stream from "H" on "2020-03-26"
    And Artist "3" has "2" stream from "F" on "2020-03-26"
    And Artist "3" has "6" stream from "H" on "2020-03-27"
    And Artist "3" has "4" stream from "F" on "2020-03-27"
    When I request "/manager?date=not-a-date"
    Then the response code is 400
    And I should see a formatted response
    And the response body contains JSON:
      """
      {
          "data": [],
          "errors" : [
             {
                "message": "Date given is invalid : Invalid date format. (Allowed : YYYY-mm-dd)",
                "internalDetail": "Date given is invalid : Invalid date format. (Allowed : YYYY-mm-dd)",
                "context": null
            }
          ]
      }
      """
