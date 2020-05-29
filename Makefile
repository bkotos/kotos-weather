composer-install:
	@docker-compose up

package: composer-install
	@rm ./kotos-weather.zip
	@zip -r ./kotos-weather.zip ./src ./vendor ./assets ./LICENSE ./kotos-weather.php
