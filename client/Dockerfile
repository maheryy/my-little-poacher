FROM node:16-alpine as BUILD_IMAGE
WORKDIR /app
COPY . .
WORKDIR /app
RUN npm ci && npm run build

FROM caddy:2.6-alpine
COPY --from=BUILD_IMAGE /app/dist /srv
COPY Caddyfile /etc/caddy/Caddyfile
EXPOSE 80
EXPOSE 443
