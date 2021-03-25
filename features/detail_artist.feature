Feature: Detailed artist view
  As a manager
  I need to be able to see statistics about a specific artist

  Scenario: Statistics for current year
    Given there is an artist "testArtist" with id "5"
    And Artist "5" has "3" stream from "F" on "2021-03-25"
    And Artist "5" has "3" stream from "H" on "2021-03-25"
    And Artist "5" has "6" stream from "H" on "2020-03-25"
    And Artist "5" has "3" stream from "F" on "2020-03-25"
    When I request "/artists/5"
    Then the response code is 200
    And I should see a formatted response
    And the response body contains JSON:
      """
      {
          "data": {
              "year": "2021",
              "listeningNumber": 6,
              "genderStat": [
                  {
                      "gender": "F",
                      "nb_streams": "3",
                      "percentage": 50
                  },
                  {
                      "gender": "H",
                      "nb_streams": "3",
                      "percentage": 50
                  }
              ]
          }
      }
      """

  Scenario: Statistics for previous year
    Given there is an artist "testArtist" with id "5"
    And Artist "5" has "3" stream from "F" on "2021-03-25"
    And Artist "5" has "3" stream from "H" on "2021-03-25"
    And Artist "5" has "6" stream from "F" on "2020-03-25"
    And Artist "5" has "4" stream from "H" on "2020-03-25"
    When I request "/artists/5?year=2020"
    Then the response code is 200
    And I should see a formatted response
    And the response body contains JSON:
      """
      {
          "data": {
              "year": "2020",
              "listeningNumber": 10,
              "genderStat": [
                  {
                      "gender": "F",
                      "nb_streams": "6",
                      "percentage": 60
                  },
                  {
                      "gender": "H",
                      "nb_streams": "4",
                      "percentage": 40
                  }
              ]
          }
      }
      """
