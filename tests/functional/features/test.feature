Feature: wikipedia
	In order to see wikipedia
	As regular user
	I need to be able to go to wiki

	Scenario: go to wiki
		When I go to "https://www.wikipedia.org/"
		Then I should see text matching "Terms of Use"