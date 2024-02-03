FROM node:alpine as frontend

# Install git incase our npm dependencies require git
RUN apk update && apk upgrade && apk add --no-cache git

# Set the workdir
WORKDIR /app

# Install and init Tailwind
RUN npm install -D tailwindcss && npx tailwindcss init

# Could copy .env files here and use it as a template here

# Copy and fire up entrypoint
COPY ./server/frontend/docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh

RUN chmod 777 /usr/local/bin/docker-entrypoint.sh \
    && chmod +x /usr/local/bin/docker-entrypoint.sh

ENTRYPOINT ["/usr/local/bin/docker-entrypoint.sh"]
