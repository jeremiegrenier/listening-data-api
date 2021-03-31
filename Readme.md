Listening-data-api
==================

Api that manage listening data for managers

Installation
------------

This application is dockerised. 
To build docker image run `make docker-build`.
Then you only need to `make docker-run` to launch all containers. 

Application will be available at `localhost:9090`

If you want to launch on dev mode, add `:docker-compose.override.yml` on env var `COMPOSE_FILE`. 


