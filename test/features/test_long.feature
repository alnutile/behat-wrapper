Feature: ls
  Scenario: List files in directory
    Given I am in a directory "tmp"
    And I have a file named "foo"
    And I have a file named "bar"
    When I run "ls"
    Then I should get:
    """
    bar
    foo
    """
    And I wait
    When I run "ls"
    Then I should get:
    """
    bar
    foo
    """