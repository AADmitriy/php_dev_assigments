### Start command
docker compose up -d

### Seed command (wait couple of seconds for connection to db to establish)
docker compose exec web php ../config/seed.php

### Clear up command for development
docker compose down --rmi local -v

### Complete clear up command
docker compose down --rmi all -v