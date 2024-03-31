# INDéLEC website

Code source du site vitrine de l'entreprise INDéLEC.

## Setup

### Development

```bash
# Configure the project
cp .env.example .env

# Initialize
composer install
pnpm install
pnpm dev
# OR "pnpm hot" to have hot reloading

# Start
docker compose up
```

### Production

#### Configure

```bash
# Configure the project
cp .env.example .env
cp compose.prod.yml compose.override.yml

# Initialize
composer install --no-dev -oa
pnpm install
pnpm prod
rm -rf node_modules/

# Start
docker compose up -d --wait
```