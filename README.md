## Round-Robin Tournament Simulation with Results Visualization

Simple round-robin tournament simulation web application. Supports up to 12 total teams.  
The application is fully dockerized using Alpine images to facilitate smooth and fast setup.

<img width="400" alt="Tournament creation page" src="https://github.com/user-attachments/assets/a31e29f4-fae2-4dae-a3c7-1ecc364c61bb" />
<img width="400" alt="Tournament games page" src="https://github.com/user-attachments/assets/a21b0d73-1884-48b9-8bc8-382aa1f8b845" />
<img width="400" alt="Tournament view page" src="https://github.com/user-attachments/assets/a37273dc-ed76-4ee7-8676-4a62025be0ca" />
<img width="400" alt="Tournament games page" src="https://github.com/user-attachments/assets/27ae397e-024b-4f75-9898-e24abd5f4e6e" />

### Core Technologies
- PHP 8.4.4
- PostgreSQL 17.4
- Nginx 1.27.4
- Node 23.9
- Docker Compose v2

### Frameworks
- Symfony v7.2+
- Vue.js v3.5+

### Setup
To set up the project you should [have](https://docs.docker.com/compose/install) Docker Compose v2 installed on your system.

1. Make the app's bash script `chmod +x core` executable
2. Run the `./core app:setup` command
   - After initial setup — you can use `./core app:start`
   - Run `./core app:stop` to stop the containers
3. Visit http://localhost:5173 to interact with the application

It's recommended to run the application through containers as they are prepared with the necessary  
dockerized environments to run it without issues on any host machine.

Please note that ports `5173`, `8000` and `5432` on the host machine should  
be freed as they are being used by the containers.


### Automated testing (WIP)

There are currently some tests covering key functionality and edge cases.  
More to come soon. You can execute `./core tests:run` to run these tests.
